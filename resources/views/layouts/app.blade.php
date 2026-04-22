<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Manager')</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-semibold text-gray-800">Tasks</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('tasks.create') }}" class="text-gray-600 hover:text-gray-900">Add Task</a>
                    <a href="{{ route('projects.create') }}" class="text-gray-600 hover:text-gray-900">Add Project</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>