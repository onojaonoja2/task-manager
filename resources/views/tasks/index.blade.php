@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Tasks</h1>
        
        <form action="/" method="GET" class="flex items-center gap-2">
            <label for="project" class="text-sm text-gray-600">Filter by Project:</label>
            <select 
                name="project" 
                id="project" 
                onchange="this.form.submit()"
                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            >
                <option value="">Unassigned Tasks</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ (string) $projectId === (string) $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if($tasks->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
            <p class="text-gray-500">No tasks yet.</p>
            <a href="{{ $projectId ? route('tasks.create', $projectId) : route('tasks.create') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-900">Create your first task</a>
        </div>
    @else
        <ul id="task-list" class="space-y-3">
            @foreach($tasks as $task)
                <li 
                    data-id="{{ $task->id }}" 
                    class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200 cursor-move hover:shadow-sm transition-shadow"
                >
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium">
                            {{ $task->priority }}
                        </span>
                        <span class="text-gray-900">{{ $task->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a 
                            href="{{ route('tasks.edit', $task) }}" 
                            class="px-3 py-1 text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md"
                        >
                            Edit
                        </a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="button" 
                                onclick="showDeleteModal('{{ route('tasks.destroy', $task) }}')"
                                class="px-3 py-1 text-sm text-red-700 bg-red-100 hover:bg-red-200 rounded-md"
                            >
                                Delete
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="flex gap-4">
        <a href="{{ $projectId ? route('tasks.create', $projectId) : route('tasks.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            Add Task
        </a>
        <a href="{{ route('projects.create') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
            Add Project
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const taskList = document.getElementById('task-list');
    
    if (taskList) {
        new Sortable(taskList, {
            animation: 150,
            ghostClass: 'bg-indigo-50',
            onEnd: function(evt) {
                const taskIds = [];
                taskList.querySelectorAll('li[data-id]').forEach(function(el) {
                    taskIds.push(el.dataset.id);
                });

                fetch('{{ route('tasks.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ tasks: taskIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        });
    }

    function showDeleteModal(url) {
        Swal.fire({
            title: 'Delete Task',
            text: 'Are you sure you want to delete this task?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush