# JobPortal — Job Seeker Module

A PHP MVC job portal platform — **Job Seeker** role implementation.

## Features

### Authentication
- Register with name, email, phone, and password; select "Job Seeker" role
- Login/logout with session-based authentication
- CSRF protection on all forms

### Profile Management
- Build a detailed seeker profile: headline, professional summary, skills (tag-style input), years of experience, education level, current and expected salary range, preferred location
- Upload and replace resume (PDF, max 5 MB); view and download own resume
- Upload a profile picture (JPEG/PNG/GIF/WebP, max 2 MB)
- Manage all profile sections from a unified edit page

### Job Browsing & Search
- Browse all active job postings
- Search by keyword (matches title, description, and company name)
- Filter job results by category, location, job type (full-time/part-time/remote/contract), experience level, and salary range
- Filters update results via AJAX without page reload

### Job Detail & Application
- View job detail page: full description, requirements, benefits, salary range, company info, posted by (employer or recruiter agency), and application deadline
- Apply to a job: write a cover letter and attach resume (from profile or new upload)
- Duplicate application prevention enforced (UNIQUE KEY constraint)
- Withdraw a pending application before the employer has reviewed it

### Application Tracking
- View all submitted applications with current status (submitted/reviewed/shortlisted/interview/rejected/withdrawn)
- Color-coded status badges on a dedicated tracking page

### Saved Jobs & Alerts
- Save and unsave jobs to a personal bookmarks list; browse bookmarks
- Set up job alerts: keyword, category, location, and job type preferences
- View all active alerts; delete alerts
- Receive in-platform notifications when a new job matches an alert preference

### Communication
- Read and reply to messages from employers and recruiters
- View and respond to recruiter outreach messages about specific opportunities

### Complaints
- Submit a complaint to the admin about a misleading job posting or employer conduct
- View complaint history with status (open/resolved) and admin notes

## Tech Stack

- **Backend:** PHP 8+ (custom MVC framework)
- **Database:** MySQL / MariaDB with mysqli
- **Frontend:** HTML5, CSS3, JavaScript (vanilla)
- **Icons:** Font Awesome 6
- **Fonts:** Google Fonts (Inter + Outfit)

## Setup

1. Import `database/schema.sql` into MySQL
2. Update `config/database.php` with your credentials
3. Place in your XAMPP `htdocs` folder
4. Access via `http://localhost/JobPortal_Seeker/public`

## Project Structure

```
├── config/          → App config, DB config, route definitions
├── core/            → MVC base classes (Router, Controller, Model, Auth, Middleware)
├── controllers/     → AuthController, SeekerController, MessageController, ApiController
├── models/          → UserModel, SeekerModel, JobModel, ApplicationModel, etc.
├── views/
│   ├── auth/        → Login, Register pages
│   ├── layouts/     → Shared header/footer templates
│   ├── errors/      → 403, 404 error pages
│   └── seeker/      → All 11 seeker view templates
├── public/          → Front controller, CSS, JS, uploads
└── database/        → SQL schema
```
