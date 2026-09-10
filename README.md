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

### Laravel, Vue, and Inertia

I started the project using Laravel's official Vue starter kit through `laravel new`. I chose this setup because I am already comfortable working with Laravel and MySQL, while Vue gave me a good way to build the interactive parts of the project without separating the frontend into another application.

The starter kit also provided the base authentication flow, TypeScript, Tailwind CSS, and Inertia. I kept Inertia mainly for the application shell, layouts, and authentication, while the project data itself is retrieved and modified through the REST endpoints required by the assessment.

### REST API Structure

The project CRUD is exposed through the required `/projects` endpoints.

I used Form Requests for create, update, and list/filter validation instead of putting all validation rules inside the controller. I also used an API Resource so the JSON returned to the Vue frontend has a predictable structure rather than returning the Eloquent model directly.

For this project, I felt that this was enough separation without introducing additional service or repository layers that would add more structure than the application actually needed.

### Project Status and Priority

I used PHP backed enums for project status and priority instead of keeping the allowed values as repeated strings throughout the application.

The database stores the values as strings, while Laravel uses the enums for model casting and request validation. This keeps values such as `Planning`, `In Progress`, and `High` consistent in the backend.

### Search, Filtering, Sorting, and Pagination

I implemented search, filtering, sorting, and pagination on the server side.

The Vue frontend sends the selected options as query parameters to `GET /projects`, and Laravel applies them to the database query before returning the results.

I chose this instead of loading every project into Vue and filtering the array in the browser because the backend remains responsible for querying the data, and the same API behavior can still work as the number of projects grows.

Pagination is currently limited to 10 projects per page to keep the interface simple.

### Validation and Error Handling

The main validation rules are handled by Laravel on the server. This includes required client and project names, valid status and priority values, and ensuring that the due date is not earlier than the start date.

The Vue forms display the validation errors returned by the API rather than relying only on frontend validation. This keeps the backend as the final source of truth for valid project data.

I also added automated feature tests around the REST API, including CRUD operations, validation, authentication, filtering, sorting, and pagination.

### Scope and Simplicity

Since the assessment is a relatively small project tracker, I tried to avoid adding architecture only for the sake of adding architecture.

For example, I considered extracting additional service/repository layers and combining the create and edit forms into more abstractions, but for the current scope I found the existing separation between controllers, Form Requests, API Resources, models, enums, and Vue components easier to follow and maintain.

My focus was to keep the implementation straightforward while still leaving the project organized enough to extend later.

## AI Tool Disclosure

I used ChatGPT during the development of this assessment.

I mainly used it to help plan the order of development, discuss implementation choices, review code, and troubleshoot issues while I was working through the project. It was also useful for speeding up repetitive work, such as drafting similar validation rules, test cases, Vue form structures, and other boilerplate that I then reviewed and adjusted for the application.

I did not treat generated code as final output. I went through the implementation as I added it, tested the features manually, ran the automated test suite and frontend checks, and made changes when something did not behave as expected.

One example was the Vue/Inertia layout setup. I initially ended up rendering the application layout twice, which caused duplicate sidebar controls. I traced the issue through the starter kit's layout structure and corrected the page to use the global Inertia layout properly.

AI was therefore used as a development and review tool rather than as a replacement for understanding or testing the implementation.

## License

This project was created specifically for the Koda Kollectiv Full Stack Developer Technical Assessment.