@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
<div class="max-w-md">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create New Project</h1>
        <a href="/" class="text-gray-600 hover:text-gray-900">← Back</a>
    </div>

    <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                required
                value="{{ old('name') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3"
                placeholder="Enter project name"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
            <textarea 
                name="description" 
                id="description" 
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3"
                placeholder="Enter project description"
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button 
                type="submit" 
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
            >
                Create Project
            </button>
            <a 
                href="/" 
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
            >
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection