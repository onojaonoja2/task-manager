# Task Manager Application

A simple Laravel task management application with drag-and-drop reordering and project filtering.

## Requirements

- PHP 8.3+
- MySQL 5.7+
- Composer
- Node.js & npm

## Features

- Create, edit, and delete tasks
- Assign tasks to projects
- Drag-and-drop task reordering (automatically updates priority)
- Filter tasks by project
- Separate priority tracking per project

## Setup Instructions

### 1. Clone or Extract Project

Extract the zip file to your desired location.

### 2. Install PHP Dependencies

```bash
cd task-manager
composer install
```

### 3. Configure Environment

The `.env` file is pre-configured with your MySQL credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_planner
DB_USERNAME=root
DB_PASSWORD=MaLow89246!
```

If you need to change these values, edit the `.env` file.

### 4. Create Database

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS task_planner;"
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Install Frontend Dependencies

```bash
npm install
```

### 7. Build Assets

```bash
npm run build
```

## Running the Application

### Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### Using Laravel's All-in-One Command

```bash
composer run dev
```

This starts:
- PHP development server (port 8000)
- Queue listener
- Log viewer
- Vite dev server

## Usage

1. **Create a Project**: Click "Add Project" in the navigation to create a project category
2. **Create a Task**: Click "Add Task" to create a new task
3. **Assign to Project**: Select a project from the dropdown when creating a task
4. **Filter by Project**: Use the dropdown at the top of the task list to filter by project
5. **Reorder Tasks**: Drag and drop tasks to reorder - priority updates automatically

## Project Structure

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── TaskController.php
│   │       └── ProjectController.php
│   └── Models/
│       ├── Task.php
│       └── Project.php
├── database/
│   └── migrations/
│       ├── 2024_01_01_000003_create_projects_table.php
│       └── 2024_01_01_000004_create_tasks_table.php
├── resources/
│   └── views/
│       ├── layouts/app.blade.php
│       ├── tasks/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       └── projects/
│           └── create.blade.php
└── routes/
    └── web.php
```

## Deployment

### Production Checklist

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Configure your web server (Apache/Nginx) to point to the `public` directory

### Example Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/task-manager/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## License

MIT License