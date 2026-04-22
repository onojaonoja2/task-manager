# Task Manager Application

A Laravel-based task management application with drag-and-drop reordering, project organization, and priority tracking.

## Features

- **Create Tasks** - Add tasks with name and optional project assignment
- **Edit Tasks** - Modify task name or reassign to different project
- **Delete Tasks** - Remove tasks with confirmation popup
- **Drag-and-Drop Reordering** - Drag tasks to reorder; priority automatically updates based on position
- **Project Management** - Create projects to organize tasks
- **Filter by Project** - View tasks for specific project or unassigned tasks
- **Separate Priority Tracking** - Each project has its own priority sequence (#1, #2, #3...)
- **Unassigned Tasks** - Tasks without project assignment are tracked separately

## Prerequisites

- PHP 8.3+
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Node.js 18+ and npm

## Quick Start (5 Minutes)

### 1. Extract & Enter Project

```bash
cd task-manager
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Database

Create the MySQL database:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS task_planner;"
```

Update `.env` with your MySQL credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_planner
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Install & Build Frontend Assets

```bash
npm install
npm run build
```

### 6. Start Development Server

```bash
php artisan serve
```

The application will be available at **http://localhost:8000**

---

## Detailed Installation Guide

### Option 1: Fresh Installation

```bash
# 1. Extract project
cd path/to/project

# 2. Install dependencies
composer install
npm install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create database (MySQL)
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS task_planner;"

# 6. Update .env with your MySQL credentials
# Edit DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 7. Run migrations
php artisan migrate

# 8. Build assets
npm run build

# 9. Start server
php artisan serve
```

### Option 2: Using Composer Scripts

```bash
# Run all setup commands at once
composer run setup
```

---

## Database Schema

### Projects Table

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | Primary key |
| name | VARCHAR(255) | Project name |
| description | TEXT | Project description (nullable) |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Update timestamp |

### Tasks Table

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | Primary key |
| project_id | BIGINT | Foreign key to projects (nullable) |
| name | VARCHAR(255) | Task name |
| priority | INT | Position number (1 = highest) |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Update timestamp |

### Relationships

- **Project → Tasks**: One-to-Many (a project has many tasks)
- **Task → Project**: Many-to-One (a task belongs to one project)

---

## Development Commands

### Running the Application

```bash
# Start development server
php artisan serve

# Or use the all-in-one command (server + queue + logs + vite)
composer run dev
```

### Running Tests

```bash
# Run all tests
composer run test

# Run with configuration cache cleared
php artisan config:clear
php artisan test
```

### Code Style

```bash
# Format code with Laravel Pint
vendor/bin/pint
```

---

## Usage Guide

### Creating a Project

1. Click **Add Project** in the navigation
2. Enter project name
3. Optionally add description
4. Click **Create Project**

### Creating a Task

1. Navigate to the project view (or stay on Unassigned)
2. Click **Add Task**
3. Enter task name
4. Select project (or leave as Unassigned)
5. Click **Create Task**

Tasks are automatically assigned the next priority number in their project group.

### Reordering Tasks

1. Click and drag any task to its new position
2. Drop the task in the new position
3. Priority numbers automatically update

### Filtering Tasks

Use the dropdown at the top of the task list to:
- View tasks for a specific project
- View unassigned tasks

---

## Deployment Guide

### Production Checklist

### 1. Environment Configuration

Set production values in `.env`:

```env
APP_NAME="Task Manager"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### 2. Cache Commands

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Permissions

```bash
# Ensure storage directory is writable
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 4. Database

Ensure your production database is created and migrations are run:

```bash
php artisan migrate --force
```

### 5. Build Assets for Production

```bash
npm run build
```

### 6. Web Server Configuration

#### Nginx Example

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/project/public;
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

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache Example

Ensure Apache is configured with mod_rewrite and point DocumentRoot to `public/`.

### 7. Queue Worker (Optional)

If using queues for email or background jobs:

```bash
php artisan queue:work database --sleep=3 --tries=3 --max-time=3600
```

---

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
├── routes/
│   └── web.php
├── .env
├── artisan
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

---

## Troubleshooting

### Common Issues

#### "Class 'App\Models\Task' not found"

Run:
```bash
composer dump-autoload
php artisan cache:clear
```

#### Database connection error

Check your `.env` credentials and ensure the database exists:

```bash
mysql -u root -p -e "SHOW DATABASES;"
```

#### Assets not loading

Rebuild assets:
```bash
npm run build
```

#### Route not found

Clear route cache:
```bash
php artisan route:clear
php artisan cache:clear
```

---

## License

MIT License