# Task Management System (Web & API)

A Laravel task management system with a web dashboard and a RESTful API. It has two user groups: **Administrators**, who manage projects, staff and tasks, and **Staff members**, who view and update their own assigned tasks.

## Features

- **Web and API:** Bootstrap 5 web interface plus token-based REST API endpoints.
- **Separate logins:** independent Admin and Staff authentication areas.
- **Multi-guard authentication:**
  - Web: session guards `admin` and `staff` (`config/auth.php`).
  - API: Laravel Sanctum tokens (`auth:sanctum`), scoped by user type with the `user.type` middleware.
- **Queued notifications:** assigning a task dispatches `SendTaskAssignedNotification` (a `ShouldQueue` job) that sends `TaskAssignedMail` in the background.
- **Account status verification:** the `staff.active` middleware blocks staff whose account is inactive or unverified, on both web and API, including mid-session.
- **Validation:** Form Request classes for projects, staff, tasks and task status updates.
- **Seeders and factories:** realistic English demo data, including default Admin and Staff accounts.
- **Consistent UI:** every page extends `resources/views/layouts/app.blade.php` (violet and white theme).

## Tech Stack

Laravel (latest stable), PHP 8.2+, MySQL, Laravel Sanctum, Bootstrap 5.

## Setup Steps

1. **Clone the project and install dependencies**
   ```bash
   git clone <repository-url> task-manager
   cd task-manager
   composer install
   ```

2. **Create the environment file**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Create an empty MySQL database** (for example `task_manager`) and update `.env`:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_manager
   DB_USERNAME=root
   DB_PASSWORD=

   QUEUE_CONNECTION=database
   ```

4. **Configure mail** in `.env`.

   For local testing, emails are written to `storage/logs/laravel.log`:
   ```dotenv
   MAIL_MAILER=log
   ```

   To send real emails (for example Gmail SMTP with a Google App Password):
   ```dotenv
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email@gmail.com
   MAIL_PASSWORD=your_app_password
   MAIL_FROM_ADDRESS="your_email@gmail.com"
   MAIL_FROM_NAME="Task Manager"
   ```

   Optional: set the email of the seeded Demo Staff account so you receive the notification emails:
   ```dotenv
   DEMO_STAFF_EMAIL=your_real_email@example.com
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Start the application** (two terminals)
   ```bash
   php artisan serve
   ```
   ```bash
   php artisan queue:work
   ```
   Keep `queue:work` running, otherwise notification emails are not sent. Restart it after changing `.env` or code.

7. **Open the app:** http://localhost:8000

## Default Login Credentials

All seeded accounts use the password **`password`**.

### Web

| Role | URL | Email | Password |
|---|---|---|---|
| Admin | `/admin/login` | admin@example.com | password |
| Admin | `/admin/login` | rachel.thompson@example.com | password |
| Staff (active, verified) | `/staff/login` | staff@example.com (or the value of `DEMO_STAFF_EMAIL`) | password |
| Staff (active, verified) | `/staff/login` | emma.johnson@example.com | password |
| Staff (active, verified) | `/staff/login` | liam.smith@example.com | password |
| Staff (inactive, blocked) | `/staff/login` | inactive@example.com | password |
| Staff (unverified, blocked) | `/staff/login` | unverified@example.com | password |

Other active staff accounts: olivia.williams, noah.brown, ava.davis and ethan.miller (all `@example.com`).

### API

Use the same credentials with `POST /api/admin/login` or `POST /api/staff/login`.

## Seeded Data

2 admins, 9 staff, 3 projects and 12 tasks with realistic English titles, descriptions, statuses and due dates.

## Web Routes

| Area | URL | Description |
|---|---|---|
| Admin | `/admin/login` | Admin login |
| Admin | `/admin/dashboard` | Overview, project progress, task status, recent tasks |
| Admin | `/admin/projects` | Projects CRUD |
| Admin | `/admin/staff` | Staff CRUD (set active and verified status) |
| Admin | `/admin/tasks` | Tasks CRUD with assignment |
| Staff | `/staff/login` | Staff login |
| Staff | `/staff/dashboard` | My tasks and status counts |
| Staff | `/staff/tasks/{id}` | View a task and update its status |

## API Documentation

Base URL: `http://localhost:8000/api`

Send these headers on every request:

```
Accept: application/json
Content-Type: application/json
Authorization: Bearer <token>      (all routes except login)
```

A ready-to-import Postman collection is included: `postman/Task_Manager_API.postman_collection.json`. Run the **1. Auth** folder first to store the tokens automatically.

### Authentication

| Method | Endpoint | Body | Access |
|---|---|---|---|
| POST | `/api/admin/login` | `email`, `password` | Public |
| POST | `/api/staff/login` | `email`, `password` | Public (inactive or unverified staff get 403) |
| POST | `/api/admin/logout` | none | Admin |
| POST | `/api/staff/logout` | none | Staff |

Login response:
```json
{ "token": "1|AbCdEf...", "user": { "id": 1, "name": "Super Admin", "email": "admin@example.com" } }
```

### Admin Endpoints (token must belong to an Admin)

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/admin/projects` | List projects (paginated) |
| POST | `/api/admin/projects` | Create project: `name`, `description`, `status` |
| GET | `/api/admin/projects/{id}` | Show project with tasks |
| PUT | `/api/admin/projects/{id}` | Update project |
| DELETE | `/api/admin/projects/{id}` | Delete project |
| GET | `/api/admin/staff` | List staff |
| POST | `/api/admin/staff` | Create staff: `name`, `email`, `password`, `is_active`, `verified` |
| PUT | `/api/admin/staff/{id}` | Update staff |
| DELETE | `/api/admin/staff/{id}` | Delete staff |
| GET | `/api/admin/tasks` | List tasks |
| POST | `/api/admin/tasks` | Create task (queues the notification email) |
| GET | `/api/admin/tasks/{id}` | Show task |
| PUT | `/api/admin/tasks/{id}` | Update task (re-assigning queues a new email) |
| DELETE | `/api/admin/tasks/{id}` | Delete task |

Task body example:
```json
{
  "project_id": 1,
  "assigned_to": 1,
  "title": "Prepare release notes",
  "description": "Summarize changes for version 1.0.",
  "status": "pending",
  "due_date": "2026-11-30"
}
```

Allowed `status` values: tasks use `pending`, `in_progress`, `completed`; projects use `active`, `on_hold`, `completed`.

### Staff Endpoints (token must belong to an active, verified Staff member)

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/staff/tasks` | List my assigned tasks |
| GET | `/api/staff/tasks/{id}` | Show one of my tasks |
| PATCH | `/api/staff/tasks/{id}/status` | Update status: `{"status": "completed"}` |

### Response Codes

| Code | Meaning |
|---|---|
| 200 / 201 / 204 | Success, created, deleted |
| 401 | Missing or invalid token, or wrong credentials |
| 403 | Wrong user type, inactive or unverified staff, or a task not assigned to you |
| 422 | Validation errors |

## Architecture Notes

| Requirement | Implementation |
|---|---|
| Guards and multi-auth | `admin` and `staff` session guards in `config/auth.php`; Sanctum tokens with the `user.type` middleware for the API |
| Jobs and queues | `app/Jobs/SendTaskAssignedNotification.php` (database queue), `app/Mail/TaskAssignedMail.php` |
| Middlewares | `EnsureStaffIsActive` (`staff.active`), `EnsureUserType` (`user.type`), `auth:*`, `guest:*` |
| Validations | `ProjectRequest`, `StaffRequest`, `TaskRequest`, `UpdateTaskStatusRequest` |
| Migrations | `admins`, `staff`, `projects`, `tasks`, plus the Laravel defaults (jobs, sanctum tokens) |
| Seeders and factories | `AdminFactory`, `StaffFactory`, `ProjectFactory`, `TaskFactory`, `DatabaseSeeder` |
| Eloquent relationships | Admin hasMany Projects and Tasks; Project belongsTo Admin, hasMany Tasks; Staff hasMany Tasks; Task belongsTo Project, Staff (assignee) and Admin (creator) |

## Testing the Notification Flow

1. Make sure `queue:work` is running and Demo Staff has an email you can check.
2. Log in as admin, create a task, and assign it to Demo Staff.
3. The page redirects immediately. The worker terminal shows `SendTaskAssignedNotification ... DONE`.
4. The email arrives in the inbox (or appears in `storage/logs/laravel.log` when `MAIL_MAILER=log`).

## Troubleshooting

- **API returns HTML instead of JSON:** add the `Accept: application/json` header.
- **No emails sent:** check that `queue:work` is running, run `php artisan config:clear`, and inspect `php artisan queue:failed`.
- **Duplicate entry errors when seeding:** use `php artisan migrate:fresh --seed`, not `db:seed` on top of existing data.
- **Stale routes or views:** run `php artisan optimize:clear`.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
