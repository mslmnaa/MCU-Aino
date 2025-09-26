# Copilot Instructions for MCU System

## Project Overview
- This is a Laravel-based web application for managing Medical Check Up (MCU) data, including patient records, lab results, and health analysis.
- The system supports roles (admin, user) with different dashboard views and permissions.
- Core entities: `Patient`, `Order`, and various lab result models (e.g., `LabHematologi`, `LabUrine`).
- Health data is analyzed and compared over time using `HealthAnalysisService`.

## Key Architecture & Patterns
- **Models**: Located in `app/Models/`, each lab and medical aspect has its own model/table.
- **Controllers**: In `app/Http/Controllers/`, grouped by domain (e.g., `PatientController`, `DashboardController`).
- **Services**: Business logic (e.g., health analysis) is in `app/Services/`.
- **Routes**: Defined in `routes/web.php` (web) and use Laravel's resourceful and custom routes. Admin/user separation via middleware.
- **Migrations**: Database schema in `database/migrations/`.
- **Views**: Blade templates in `resources/views/` (not shown above).

## Developer Workflows
- **Development server**: `php artisan serve` (or use `composer dev` for full stack with queue, logs, and Vite).
- **Build assets**: `npm run build` (uses Vite and TailwindCSS).
- **Run tests**: `composer test` or `php artisan test`.
- **Migrate DB**: `php artisan migrate` (SQLite by default).
- **Import/export**: Admins can import patient data and export health records via web UI.

## Project-Specific Conventions
- **Lab result models**: Each lab type (hematology, urine, etc.) has a dedicated model/table, all linked to `Order`.
- **Patient sharing**: Patients have a `share_id` for sharing health check results.
- **Role-based access**: Use `role:admin` middleware for admin-only routes.
- **Health comparison**: Use `HealthAnalysisService` for multi-year lab/vital trend analysis.

## Integration & Dependencies
- Uses `maatwebsite/excel` for import/export (Excel/CSV).
- Uses `barryvdh/laravel-dompdf` for PDF generation.
- Frontend uses Vite, TailwindCSS, and Axios.

## Examples
- To add a new lab type: create a model, migration, and link it to `Order`.
- To extend health analysis: update `HealthAnalysisService` and related controllers.

## References
- Main logic: `app/Models/`, `app/Services/HealthAnalysisService.php`, `routes/web.php`
- Workflows: `composer.json` (scripts), `package.json` (frontend)

---
If you are unsure about a workflow or pattern, check the referenced files or ask for clarification.
