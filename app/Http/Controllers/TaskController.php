<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $projectId = $request->query('project');

        $query = Task::query()->orderBy('priority', 'asc');

        if ($projectId) {
            $query->where('project_id', $projectId);
        } else {
            $query->whereNull('project_id');
        }

        $tasks = $query->get();
        $projects = Project::all();

        return view('tasks.index', compact('tasks', 'projects', 'projectId'));
    }

    public function create(?int $project = null): View
    {
        $selectedId = $project;
        
        if ($project) {
            $project = Project::findOrFail($project);
            $projects = collect([$project]);
        } else {
            $projects = Project::all();
        }
        
        return view('tasks.create', compact('projects', 'selectedId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $projectId = $request->input('project_id');

        $maxPriority = Task::where('project_id', $projectId)
            ->when(!$projectId, fn($q) => $q->whereNull('project_id'))
            ->max('priority') ?? 0;

        Task::create([
            'name' => $request->input('name'),
            'project_id' => $projectId ?: null,
            'priority' => $maxPriority + 1,
        ]);

        return redirect('/?project=' . ($projectId ?? ''))->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        $projects = Project::all();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $oldProjectId = $task->project_id;
        $newProjectId = $request->input('project_id');

        if ($oldProjectId != $newProjectId) {
            Task::where('project_id', $oldProjectId)
                ->when(!$oldProjectId, fn($q) => $q->whereNull('project_id'))
                ->where('priority', '>', $task->priority)
                ->decrement('priority');

            $maxPriority = Task::where('project_id', $newProjectId)
                ->when(!$newProjectId, fn($q) => $q->whereNull('project_id'))
                ->max('priority') ?? 0;

            $task->update([
                'name' => $request->input('name'),
                'project_id' => $newProjectId ?: null,
                'priority' => $maxPriority + 1,
            ]);
        } else {
            $task->update(['name' => $request->input('name')]);
        }

        return redirect('/?project=' . ($newProjectId ?? ''))->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $projectId = $task->project_id;
        $priority = $task->priority;

        $task->delete();

        Task::where('project_id', $projectId)
            ->when(!$projectId, fn($q) => $q->whereNull('project_id'))
            ->where('priority', '>', $priority)
            ->decrement('priority');

        return redirect('/?project=' . ($projectId ?? ''))->with('success', 'Task deleted successfully.');
    }

    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'tasks' => 'required|array',
            'tasks.*' => 'required|integer|exists:tasks,id',
        ]);

        $taskIds = $request->input('tasks');

        foreach ($taskIds as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}