# EazyTech Solution — website + admin panel (Laravel 13)

Public marketing website and a role-based admin panel for EazyTech Solution.
Everything is database-driven (MySQL, built entirely from migrations).

## Run it

1. Start **Laragon → Start All** (MySQL must be running).
2. Double-click **`start-server.bat`** → opens http://127.0.0.1:8000

First-time / reset setup (already done once on this machine):

```powershell
$env:Path = "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64;C:\laragon\bin\composer;" + $env:Path
composer install
copy .env.example .env          # DB is mysql / eazytechsolution / root / no password
php artisan key:generate
php artisan migrate:fresh --seed   # creates every table and loads the demo data
```

## Demo logins — `/admin/login`

Password for every account: **`Demo@12345`** (the login page also has one-click "Demo accounts" buttons while `APP_ENV=local`).

| Role | Email | Can open |
|---|---|---|
| Super Admin | admin@eazytechsolution.com | everything |
| Project Manager | manager@eazytechsolution.com | dashboard, team, tasks, projects, clients, portfolio, leads |
| HR Manager | hr@eazytechsolution.com | dashboard, team, HR & payroll, work logs, leave, careers |
| Developer | dev@eazytechsolution.com | dashboard, tasks, projects |
| Marketing Lead | marketing@eazytechsolution.com | dashboard, portfolio, services, testimonials, leads, clients |

## What is inside

**Public site** — Home (animated hero, services, industry-software showcase, process, Meta-ads/AI-video band, portfolio, testimonials, team, contact form), Services (+ detail page for each), Our Work (+ case-study pages), About, Careers (+ application form), Contact + FAQ.

**Admin panel** — Dashboard · Task board (drag & drop Kanban) · Projects · Clients · Team · Departments · HR & payroll (permanent salary + remote part-time hourly pay from approved work logs) · Work logs · Leave requests · Job openings · Applications · Portfolio · Services · Testimonials · Leads & messages · Users · Roles & permissions · Site settings. Dark/light theme toggle.

**HR model** — each team member is *Permanent*, *Remote part-time*, *Remote full-time*, *Contract* or *Intern*.
Permanent staff have a monthly salary; remote part-timers have an hourly rate + contracted weekly hours, log hours in **Work logs**, and the monthly payroll sheet pays only **approved** hours.

## Database (migrations in `database/migrations`)

`roles`, `users` (+role), `departments`, `team_members`, `work_logs`, `leave_requests`, `job_openings`, `job_applications`, `clients`, `services`, `projects`, `tasks`, `portfolio_items`, `testimonials`, `contact_messages`, `settings` (+ Laravel's sessions / cache / jobs).

## Replace the sample content

All names, clients, testimonials, portfolio items, statistics, phone/email/social links are **placeholders**.
Edit them in the admin: *Settings* (contact info, social links, homepage numbers, currency), *Team*, *Clients*, *Portfolio*, *Testimonials*, *Services*.
Uploaded images are stored in `public/uploads/`.

## Before going live

- Set `APP_ENV=production`, `APP_DEBUG=false` in `.env` (this also hides the demo-account box on the login page).
- Create real users, delete the demo users, and change passwords.
- Configure mail if you want email notifications for new leads.
