# JobPortal — Employer Module

A PHP MVC job portal platform — **Employer** role implementation.

## Features

### Authentication
- Register a company account and submit details for admin verification.
- Login/logout with session-based authentication.
- CSRF protection on all forms.

### Company Profile
- Manage company profile details: company name, industry, size, description, website, and address.
- Upload and update company logo.
- Restricted features before admin verification is completed.

### Job Management
- Create new job postings: title, category, description, requirements, benefits, salary range, location, job type, experience level, and deadline.
- Edit, close, or delete existing job postings.
- Repost closed jobs or toggle between active and closed statuses via AJAX.
- View all job postings in a dashboard including metrics like applications and days until deadline.

### Applicant Tracking
- View all applications for a specific job, with filtering by status.
- View individual applicant profiles, including cover letter and downloadable resume.
- Update application status (reviewed/shortlisted/interview/rejected) via AJAX dropdown.
- View shortlisted candidates across all job postings in a unified list.

### Communication & Analytics
- Send in-platform messages to applicants (e.g., interview invitations, rejection notices).
- View hiring analytics per job (application funnel, conversion rates).
- View overall company recruitment analytics (total jobs, total apps).
- Manage relationship with recruiter agencies and submit complaints to admin.

## Tech Stack

- **Backend:** PHP 8+ (custom MVC framework)
- **Database:** MySQL / MariaDB with mysqli
- **Frontend:** HTML5, CSS3 (JobPortal 1 Design System), JavaScript (vanilla)
- **Icons:** Font Awesome 6
- **Fonts:** Google Fonts (Geist + Geist Mono)

## Setup

1. Import `database/schema.sql` into MySQL
2. Update `config/database.php` with your credentials
3. Place in your XAMPP `htdocs` folder
4. Access via `http://localhost/JobPortal_Employer/public`

## Project Structure

```
├── config/          → App config, DB config, route definitions
├── core/            → MVC base classes (Router, Controller, Model, Auth, Middleware)
├── controllers/     → AuthController, EmployerController, MessageController, ApiController
├── models/          → UserModel, EmployerModel, JobModel, ApplicationModel, AnalyticsModel, etc.
├── views/
│   ├── auth/        → Login, Register pages
│   ├── layouts/     → Shared header/footer templates
│   ├── errors/      → 403, 404 error pages
│   └── employer/    → All employer view templates
├── public/          → Front controller, CSS, JS, uploads
└── database/        → SQL schema
```
