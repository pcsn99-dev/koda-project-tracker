# Client Project Tracker

A full-stack project management application built for the Koda Kollectiv Full Stack Developer Technical Assessment.

The application allows authenticated users to create, view, update, delete, search, filter, sort, and paginate client projects.

## Tech Stack

- Laravel 13
- Vue 3
- TypeScript
- Inertia 3
- MySQL
- Tailwind CSS
- Pest
- Laravel Sail / Docker

## Features

- Project CRUD
- REST API
- Authentication
- Server-side validation
- Search by client or project name
- Filter by status
- Filter by priority
- Sorting
- Server-side pagination
- Responsive project management interface
- Automated API feature tests
- Docker development environment

## Project Fields

Each project contains:

- Client Name
- Project Name
- Description
- Status
- Priority
- Start Date
- Due Date

Supported statuses:

- Planning
- In Progress
- On Hold
- Completed

Supported priorities:

- Low
- Medium
- High

## REST API

| Method | Endpoint         | Description        |
| ------ | ---------------- | ------------------ |
| GET    | `/projects`      | List projects      |
| GET    | `/projects/{id}` | Retrieve a project |
| POST   | `/projects`      | Create a project   |
| PUT    | `/projects/{id}` | Update a project   |
| DELETE | `/projects/{id}` | Delete a project   |

`GET /projects` also supports:

| Parameter        | Description                       |
| ---------------- | --------------------------------- |
| `search`         | Search client or project name     |
| `status`         | Filter by project status          |
| `priority`       | Filter by project priority        |
| `sort_by`        | Select the field used for sorting |
| `sort_direction` | `asc` or `desc`                   |
| `page`           | Pagination page                   |

Example:

```text
/projects?search=website&status=In%20Progress&priority=High&sort_by=due_date&sort_direction=asc&page=1
```

## Local Setup

### Requirements

- PHP
- Composer
- Node.js / npm
- MySQL

### Installation

Clone the repository:

```bash
git clone https://github.com/YOUR-USERNAME/koda-project-tracker.git
cd koda-project-tracker
```

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Create a MySQL database and configure the following values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koda_project_tracker
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seed sample data:

```bash
php artisan migrate --seed
```

Start the application:

```bash
composer run dev
```

The application will be available at:

```text
http://localhost:8000
```

Register an account or sign in to access the project tracker.

## Docker Setup

Docker support is provided using Laravel Sail.

Requirements:

- Docker Desktop
- WSL2 on Windows

Install Composer dependencies first, then start the Docker environment:

```bash
./vendor/bin/sail up -d
```

Run migrations and seed data:

```bash
./vendor/bin/sail artisan migrate --seed
```

Install and build frontend dependencies if needed:

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

Run the test suite:

```bash
./vendor/bin/sail artisan test
```

Stop the containers:

```bash
./vendor/bin/sail down
```

## Testing

Run the backend test suite:

```bash
php artisan test
```

Run PHP formatting:

```bash
./vendor/bin/pint
```

Run frontend checks:

```bash
npm run types:check
npm run lint:check
npm run format:check
```

The API feature tests cover project CRUD, validation, authentication, search, filtering, sorting, and pagination.

## Technical Decisions

### Laravel + Vue + Inertia

Laravel handles routing, validation, authentication, persistence, and the REST API. Vue handles the interactive project management interface. Inertia allows Vue to be used within the Laravel application without maintaining a separate frontend repository.

### REST API

Project operations are exposed through the required REST endpoints. API Resources provide a consistent JSON response structure, while Form Requests keep validation separate from controller logic.

### PHP Enums

Project status and priority are represented with PHP backed enums. This keeps allowed values consistent between model casting and validation.

### Server-Side Filtering and Pagination

Search, filtering, sorting, and pagination are performed by the backend rather than loading the entire dataset into the browser. This keeps the API scalable and ensures that the backend remains the source of truth.

### Validation

Validation is enforced server-side. In particular, the due date must be on or after the start date, and status and priority values are restricted to the defined enum values.

### Scope

The implementation intentionally avoids unnecessary repository/service abstractions because the application's domain logic is small. Controllers, Form Requests, API Resources, Eloquent models, and enums provide sufficient separation for the scope of the assessment.

## AI Tool Disclosure

AI tools were used during development, primarily ChatGPT, for development guidance, code review, debugging assistance, and discussion of implementation approaches.

All generated or suggested code was reviewed, adapted, tested, and validated as part of the implementation.

## License

This project was created specifically for the Koda Kollectiv Full Stack Developer Technical Assessment.
