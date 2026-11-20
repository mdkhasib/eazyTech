<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ContactMessage;
use App\Models\Department;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\LeaveRequest;
use App\Models\PortfolioItem;
use App\Models\Project;
use App\Models\Role;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Task;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WorkLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Demo data for EazyTech Solution. Everything below is SAMPLE content —
 * replace it from the admin panel (people, clients, testimonials, numbers).
 */
class DatabaseSeeder extends Seeder
{
    public const PASSWORD = 'Demo@12345';

    public function run(): void
    {
        $this->settings();
        $roles = $this->roles();
        $depts = $this->departments();
        $members = $this->team($roles, $depts);
        $this->services();
        $clients = $this->clients();
        $projects = $this->projects($clients);
        $this->tasks($projects, $members);
        $this->portfolio($clients);
        $this->testimonials();
        $this->messages();
        $this->hr($members);
        $this->careers();
    }

    private function settings(): void
    {
        $s = [
            'site_name'      => 'EazyTech Solution',
            'tagline'        => 'We plan. We build. We grow your business.',
            'email'          => 'hello@eazytechsolution.com',
            'phone'          => '+880 1700-000000',
            'whatsapp'       => '8801700000000',
            'address'        => 'Remote-first studio · Serving clients worldwide',
            'working_hours'  => 'Sun – Thu, 10:00 – 19:00',
            'facebook'       => 'https://facebook.com/',
            'instagram'      => 'https://instagram.com/',
            'linkedin'       => 'https://linkedin.com/',
            'youtube'        => 'https://youtube.com/',
            'currency'      => '$',
            'stat_projects'  => '120',
            'stat_clients'   => '60',
            'stat_team'      => '25',
            'stat_years'     => '5',
        ];
        foreach ($s as $k => $v) {
            Setting::put($k, $v);
        }
    }

    private function roles(): array
    {
        $defs = [
            ['Super Admin', 'super-admin', 'Full access to everything.', ['*']],
            ['Project Manager', 'project-manager', 'Runs projects, tasks, clients and the portfolio.',
                ['dashboard', 'team', 'tasks', 'projects', 'clients', 'portfolio', 'messages']],
            ['HR Manager', 'hr-manager', 'Team, HR, payroll and hiring.',
                ['dashboard', 'team', 'hr', 'careers']],
            ['Developer', 'developer', 'Sees the dashboard and the task board only.',
                ['dashboard', 'tasks', 'projects']],
            ['Marketing Lead', 'marketing-lead', 'Website content, leads and social proof.',
                ['dashboard', 'portfolio', 'services', 'testimonials', 'messages', 'clients']],
        ];
        $out = [];
        foreach ($defs as [$name, $slug, $desc, $perms]) {
            $out[$slug] = Role::create([
                'name' => $name, 'slug' => $slug, 'description' => $desc,
                'permissions' => $perms, 'is_system' => $slug === 'super-admin',
            ]);
        }

        return $out;
    }

    private function departments(): array
    {
        $defs = [
            'Management' => '#F28A2A', 'Engineering' => '#0DB5BC', 'Design' => '#EC5A5E',
            'Marketing' => '#F2D624', 'Operations & HR' => '#8B7CFF',
        ];
        $out = [];
        foreach ($defs as $name => $color) {
            $out[$name] = Department::create(['name' => $name, 'color' => $color]);
        }

        return $out;
    }

    private function team(array $roles, array $depts): array
    {
        $mk = function (string $email, string $name, string $role) use ($roles) {
            return User::create([
                'name' => $name, 'email' => $email, 'password' => self::PASSWORD,
                'role_id' => $roles[$role]->id, 'is_active' => true, 'email_verified_at' => now(),
            ]);
        };
        $u = [
            'admin'     => $mk('admin@eazytechsolution.com', 'Arif Rahman', 'super-admin'),
            'manager'   => $mk('manager@eazytechsolution.com', 'Nusrat Jahan', 'project-manager'),
            'hr'        => $mk('hr@eazytechsolution.com', 'Maliha Sultana', 'hr-manager'),
            'dev'       => $mk('dev@eazytechsolution.com', 'Tanvir Hasan', 'developer'),
            'marketing' => $mk('marketing@eazytechsolution.com', 'Sadia Karim', 'marketing-lead'),
        ];

        $rows = [
            // key, user, dept, name, designation, type, mode, salary, hourly, hours, skills, bio
            ['arif', 'admin', 'Management', 'Arif Rahman', 'Founder & CEO', 'permanent', 'hybrid', 3000, null, 45,
                ['Strategy', 'Product', 'Sales'], 'Sets the vision, owns client relationships and makes sure every product we ship moves the needle.'],
            ['nusrat', 'manager', 'Operations & HR', 'Nusrat Jahan', 'Head of Projects', 'permanent', 'hybrid', 2000, null, 40,
                ['Agile', 'Scrum', 'Client success'], 'Keeps every sprint on time and every client in the loop.'],
            ['tanvir', 'dev', 'Engineering', 'Tanvir Hasan', 'Lead Full-stack Engineer', 'permanent', 'hybrid', 2400, null, 40,
                ['Laravel', 'React', 'MySQL', 'AWS'], 'Architects our ERP, e-commerce and SaaS platforms.'],
            ['maliha', 'hr', 'Operations & HR', 'Maliha Sultana', 'HR & Operations Manager', 'permanent', 'onsite', 1500, null, 40,
                ['Hiring', 'Payroll', 'Culture'], 'Builds the team behind the products and keeps people happy.'],
            ['sadia', 'marketing', 'Marketing', 'Sadia Karim', 'Digital Marketing Lead', 'permanent', 'hybrid', 1600, null, 40,
                ['Meta Ads', 'SEO', 'Funnels', 'Analytics'], 'Turns ad spend into measurable growth for our clients.'],
            ['sumon', null, 'Engineering', 'Sumon Das', 'Backend Engineer', 'permanent', 'onsite', 1400, null, 40,
                ['PHP', 'Laravel', 'APIs', 'Redis'], 'Obsessed with clean APIs and fast queries.'],
            ['imran', null, 'Engineering', 'Imran Hossain', 'Mobile App Developer', 'remote_part_time', 'remote', null, 18, 25,
                ['Flutter', 'Firebase', 'Android', 'iOS'], 'Ships polished Flutter apps from wherever the Wi-Fi is good.'],
            ['farhana', null, 'Design', 'Farhana Akter', 'UI/UX Designer', 'remote_part_time', 'remote', null, 20, 24,
                ['Figma', 'Design systems', 'Prototyping'], 'Designs interfaces people actually enjoy using.'],
            ['rafi', null, 'Marketing', 'Rafi Ahmed', 'AI Video Producer', 'remote_part_time', 'remote', null, 16, 20,
                ['AI video', 'Motion', 'Premiere', 'Reels'], 'Makes scroll-stopping AI-assisted video ads.'],
            ['jui', null, 'Marketing', 'Jui Rahman', 'Social Media Executive', 'remote_part_time', 'remote', null, 12, 20,
                ['Content', 'Copywriting', 'Community'], 'Runs the calendars, captions and communities.'],
        ];
        $members = [];
        foreach ($rows as $i => $r) {
            [$key, $user, $dept, $name, $title, $type, $mode, $sal, $hr, $hrs, $skills, $bio] = $r;
            $members[$key] = TeamMember::create([
                'user_id' => $user ? $u[$user]->id : null,
                'department_id' => $depts[$dept]->id,
                'name' => $name, 'slug' => Str::slug($name), 'designation' => $title,
                'photo' => 'images/team/' . Str::slug($name) . '.svg',
                'email' => Str::slug($name, '.') . '@eazytechsolution.com',
                'phone' => '+880 17' . random_int(10000000, 99999999),
                'bio' => $bio, 'skills' => $skills, 'employment_type' => $type, 'work_mode' => $mode,
                'monthly_salary' => $sal, 'hourly_rate' => $hr, 'weekly_hours' => $hrs,
                'join_date' => now()->subMonths(random_int(3, 48))->startOfMonth(),
                'status' => 'active', 'is_public' => true, 'sort_order' => $i,
                'linkedin' => 'https://linkedin.com/', 'github' => $dept === 'Engineering' ? 'https://github.com/' : null,
            ]);
        }
        $members['jui']->update(['status' => 'on_leave']);
        foreach ($u as $user) { // admin logins reuse their team portrait
            $user->update(['avatar' => 'images/team/' . Str::slug($user->name) . '.svg']);
        }

        return $members;
    }

    private function services(): void
    {
        $defs = [
            ['Web Application Development', 'web-application-development', 'software', 'code', true,
                'Fast, secure and scalable web apps — dashboards, portals, SaaS and booking platforms.',
                'We design and engineer custom web applications that run your operations and delight your customers. From a lean MVP to a multi-tenant SaaS, we build on proven stacks with clean architecture, automated tests and cloud-ready deployments.',
                ['Custom dashboards & portals', 'SaaS & multi-tenant platforms', 'REST / GraphQL APIs', 'Payment & third-party integrations', 'Role-based access & audit logs', 'Cloud deployment & monitoring']],
            ['E-commerce Solutions', 'ecommerce-solutions', 'software', 'cart', true,
                'Online stores built to convert — payments, inventory, delivery and marketing built in.',
                'Launch a store that sells 24/7. We build custom Laravel and Shopify/WooCommerce storefronts with local and international payment gateways, courier integration, inventory sync and conversion-focused UX.',
                ['Custom storefront & checkout', 'bKash / Nagad / Stripe / PayPal', 'Courier & delivery integration', 'Inventory & order management', 'Coupons, bundles & loyalty', 'SEO & analytics ready']],
            ['Mobile App Development', 'mobile-app-development', 'software', 'phone', true,
                'Native-feel Android & iOS apps with a single codebase and a beautiful UI.',
                'Customers live on their phones. We build performant Flutter and React Native apps with push notifications, offline mode, payments and admin back-offices — and take you all the way to the Play Store and App Store.',
                ['Android & iOS (Flutter / React Native)', 'Push notifications & chat', 'Offline-first sync', 'Payments & maps', 'Admin back-office', 'Store publishing & updates']],
            ['ERP Software', 'erp-software', 'software', 'grid', true,
                'One system for sales, purchase, stock, production, HR and accounts — tailored to you.',
                'Stop juggling spreadsheets. Our modular ERP connects every department in real time, with role-based access, approvals, reports and an audit trail. Start with the modules you need and grow from there.',
                ['Sales, purchase & inventory', 'Production & BOM', 'Multi-branch & multi-warehouse', 'Approval workflows', 'Real-time reports', 'Custom modules on demand']],
            ['Accounting Software', 'accounting-software', 'software', 'calculator', false,
                'Automated bookkeeping, invoicing, VAT and financial statements you can trust.',
                'Accurate books without the headache. Double-entry accounting, invoicing, expenses, bank reconciliation, VAT/tax reports and live financial statements — with multi-currency and multi-company support.',
                ['Double-entry ledger', 'Invoices, bills & payments', 'Bank reconciliation', 'VAT / tax reports', 'Balance sheet & P&L', 'Multi-currency & multi-company']],
            ['HR & Payroll Software', 'hr-payroll-software', 'software', 'users', false,
                'Employees, attendance, leave and payroll — for permanent staff and remote part-timers.',
                'Manage your whole workforce in one place: employee profiles, attendance, leave, hourly timesheets for remote part-time workers, automated payroll and payslips.',
                ['Employee profiles & documents', 'Attendance & leave', 'Hourly timesheets for part-timers', 'Automated payroll & payslips', 'Recruitment pipeline', 'Self-service portal']],
            ['Shop & POS Software', 'shop-pos-software', 'software', 'store', false,
                'Lightning-fast point-of-sale, stock and billing software for retail shops and chains.',
                'Barcode billing, stock alerts, supplier management, customer dues and daily sales reports — works on any PC or tablet, even with a spotty connection.',
                ['Barcode & touch POS', 'Stock & low-stock alerts', 'Supplier & purchase management', 'Customer dues & loyalty', 'Multi-outlet reporting', 'Thermal receipt printing']],
            ['Pharmacy Management', 'pharmacy-management-software', 'software', 'pill', false,
                'Medicine stock, batch & expiry tracking, prescriptions and fast billing for pharmacies.',
                'Built for the way pharmacies really work: batch and expiry tracking, generic-brand mapping, supplier returns, controlled-drug logs, and instant billing with profit insights.',
                ['Batch & expiry tracking', 'Generic ↔ brand mapping', 'Supplier orders & returns', 'Prescription records', 'Expiry & low-stock alerts', 'Profit & loss reports']],
            ['Custom Software & AI Automation', 'custom-software-ai-automation', 'software', 'cpu', true,
                'AI chatbots, workflow automation and bespoke software that removes manual work.',
                'If you can describe the process, we can automate it. AI assistants for support and sales, document processing, WhatsApp / Messenger bots and custom internal tools that give your team hours back every week.',
                ['AI chatbots & assistants', 'WhatsApp / Messenger automation', 'Document & data processing', 'Workflow & CRM automation', 'Custom internal tools', 'LLM integrations']],
            ['Digital Marketing & SEO', 'digital-marketing-seo', 'marketing', 'trending', false,
                'Full-funnel strategy, SEO and content that bring qualified customers, not just clicks.',
                'We combine strategy, content, SEO and analytics into a growth engine. You get a clear plan, transparent reporting and campaigns tuned for revenue — not vanity metrics.',
                ['Growth strategy & funnels', 'On-page & technical SEO', 'Content marketing', 'Email & WhatsApp marketing', 'Analytics & conversion tracking', 'Monthly performance reports']],
            ['Meta Ads (Facebook & Instagram)', 'meta-ads', 'marketing', 'megaphone', true,
                'Performance campaigns on Facebook & Instagram built to cut cost per lead and scale sales.',
                'Creative testing, audience research, pixel & conversion API setup and weekly optimisation. We manage the budget like it is our own and report on what matters: leads, sales and ROAS.',
                ['Campaign strategy & setup', 'Pixel & Conversion API', 'Creative & copy testing', 'Retargeting funnels', 'Lead-gen & sales campaigns', 'Weekly optimisation & reports']],
            ['Social Media Management', 'social-media-management', 'marketing', 'share', false,
                'Content calendars, designs, reels and community management — consistent and on-brand.',
                'Show up every day without lifting a finger. We plan, design, write, schedule and engage — across Facebook, Instagram, LinkedIn, TikTok and YouTube.',
                ['Content calendar & strategy', 'Post, reel & story design', 'Copywriting & captions', 'Community management', 'Influencer outreach', 'Monthly insights report']],
            ['AI Video Marketing', 'ai-video-marketing', 'marketing', 'video', true,
                'AI-powered promo videos, product ads and reels produced in days — at a fraction of the cost.',
                'Our AI-assisted studio creates scroll-stopping videos: product explainers, ad creatives, voice-overs in multiple languages and avatar presenters. Test more creative, faster, and find the winners.',
                ['AI product & explainer videos', 'Ad creatives for Meta / TikTok', 'AI voice-over & dubbing', 'Avatar presenters', 'Reels, Shorts & TikToks', 'Rapid creative testing']],
            ['Branding & UI/UX Design', 'branding-ui-ux-design', 'marketing', 'palette', false,
                'Logos, brand identity, and interfaces that look premium and feel effortless.',
                'Great design builds trust instantly. We craft brand identities, design systems, website and app interfaces — grounded in research and tested with real users.',
                ['Logo & brand identity', 'Design systems', 'Website & app UI/UX', 'Prototyping & user testing', 'Social & ad creatives', 'Brand guidelines']],
        ];
        foreach ($defs as $i => [$title, $slug, $cat, $icon, $featured, $short, $desc, $features]) {
            Service::create([
                'title' => $title, 'slug' => $slug, 'category' => $cat, 'icon' => $icon,
                'short_description' => $short, 'description' => $desc, 'features' => $features,
                'sort_order' => $i + 1, 'is_featured' => $featured, 'is_active' => true,
            ]);
        }
    }

    private function clients(): array
    {
        $defs = [
            ['Kamal Uddin', 'FreshCart Retail', 'Retail', 'Bangladesh', 'active', 'Referral'],
            ['Dr. Anika Rahman', 'MediPlus Pharmacy', 'Healthcare', 'Bangladesh', 'active', 'Facebook'],
            ['Olivia Grant', 'Northwind Fitness', 'Health & Fitness', 'United Kingdom', 'active', 'Website'],
            ['Hasan Mahmud', 'UrbanNest Properties', 'Real Estate', 'UAE', 'active', 'Referral'],
            ['Priya Menon', 'Spice Route Foods', 'Food & Beverage', 'India', 'past', 'LinkedIn'],
            ['Daniel Brooks', 'Brightpath Academy', 'Education', 'Canada', 'active', 'Website'],
            ['Sajid Karim', 'StyleHub Fashion', 'Fashion', 'Bangladesh', 'lead', 'Facebook'],
            ['Laura Fischer', 'Alpine Logistics', 'Logistics', 'Germany', 'lead', 'LinkedIn'],
        ];
        $out = [];
        foreach ($defs as $i => [$name, $company, $industry, $country, $status, $source]) {
            $out[] = Client::create([
                'name' => $name, 'company' => $company, 'industry' => $industry, 'country' => $country,
                'status' => $status, 'source' => $source,
                'logo' => 'images/clients/' . Str::slug($company) . '.svg',
                'email' => Str::slug($name, '.') . '@example.com',
                'phone' => '+1 555-01' . str_pad((string) ($i + 10), 2, '0'),
                'website' => 'https://' . Str::slug($company) . '.example.com',
                'notes' => 'Sample client record.',
            ]);
        }

        return $out;
    }

    private function projects(array $clients): array
    {
        $svc = Service::pluck('id', 'slug');
        $defs = [
            [0, 'shop-pos-software', 'FreshCart POS & Inventory', 'in_progress', 'high', 8500, 65, -40, 25],
            [1, 'pharmacy-management-software', 'MediPlus Pharmacy Suite', 'review', 'high', 6400, 88, -70, 8],
            [2, 'mobile-app-development', 'Northwind Fitness App', 'in_progress', 'medium', 12000, 45, -30, 45],
            [3, 'web-application-development', 'UrbanNest Listing Portal', 'planning', 'medium', 18000, 10, -5, 80],
            [4, 'meta-ads', 'Spice Route Meta Ads Q4', 'completed', 'medium', 4500, 100, -120, -15],
            [5, 'erp-software', 'Brightpath School ERP', 'in_progress', 'urgent', 22000, 55, -60, 30],
            [0, 'ai-video-marketing', 'FreshCart AI Video Campaign', 'in_progress', 'low', 3000, 70, -14, 10],
            [3, 'digital-marketing-seo', 'UrbanNest SEO Retainer', 'on_hold', 'low', 2400, 30, -50, 60],
        ];
        $out = [];
        foreach ($defs as [$c, $s, $title, $status, $prio, $budget, $progress, $startOff, $dueOff]) {
            $out[] = Project::create([
                'client_id' => $clients[$c]->id, 'service_id' => $svc[$s] ?? null, 'title' => $title,
                'description' => 'Sample project for demonstration.', 'status' => $status, 'priority' => $prio,
                'budget' => $budget, 'progress' => $progress,
                'start_date' => now()->addDays($startOff), 'due_date' => now()->addDays($dueOff),
            ]);
        }

        return $out;
    }

    private function tasks(array $projects, array $m): void
    {
        $admin = User::where('email', 'admin@eazytechsolution.com')->first();
        $defs = [
            [0, 'tanvir', 'Barcode scanner integration', 'in_progress', 'high', 3],
            [0, 'sumon', 'Stock transfer between outlets API', 'todo', 'medium', 9],
            [0, 'farhana', 'POS touch-screen UI polish', 'review', 'medium', 5],
            [1, 'sumon', 'Expiry alert notifications', 'done', 'high', -3],
            [1, 'tanvir', 'Batch-wise purchase returns', 'review', 'urgent', 2],
            [1, 'nusrat', 'UAT sign-off with client', 'todo', 'high', 6],
            [2, 'imran', 'Workout tracker screens', 'in_progress', 'high', 7],
            [2, 'farhana', 'Onboarding illustrations', 'done', 'low', -6],
            [2, 'imran', 'Push notification service', 'todo', 'medium', 14],
            [3, 'nusrat', 'Scope & wireframes workshop', 'in_progress', 'medium', 4],
            [3, 'farhana', 'Listing page prototype', 'todo', 'medium', 12],
            [5, 'tanvir', 'Fee collection module', 'in_progress', 'urgent', -1],
            [5, 'sumon', 'Exam results import', 'todo', 'high', 8],
            [5, 'maliha', 'Teacher payroll rules', 'review', 'medium', 10],
            [6, 'rafi', 'Storyboard & script for 5 AI videos', 'done', 'medium', -4],
            [6, 'rafi', 'Final render & ad cuts', 'in_progress', 'high', 2],
            [6, 'sadia', 'Launch campaign on Meta', 'todo', 'high', 4],
            [7, 'jui', 'Monthly content calendar', 'todo', 'low', 15],
            [4, 'sadia', 'Wrap-up performance report', 'done', 'medium', -12],
        ];
        foreach ($defs as $i => [$p, $who, $title, $status, $prio, $dueOff]) {
            Task::create([
                'project_id' => $projects[$p]->id, 'assigned_to' => $m[$who]->id, 'created_by' => $admin->id,
                'title' => $title, 'description' => 'Sample task.', 'status' => $status, 'priority' => $prio,
                'due_date' => now()->addDays($dueOff), 'sort_order' => $i,
            ]);
        }
    }

    private function portfolio(array $clients): void
    {
        $defs = [
            ['FreshCart POS & Inventory', 'retail-pos-inventory', 'erp', 'teal', 0, 'Multi-outlet retail POS with live stock sync.',
                'Replaced paper registers and spreadsheets with a fast barcode POS, central inventory and supplier management for a growing retail chain.',
                ['Laravel', 'MySQL', 'Vue', 'Thermal printing'], '3×', 'faster checkout', 2026, true],
            ['MediPlus Pharmacy Suite', 'pharmacy-management-suite', 'erp', 'mint', 1, 'Batch, expiry and prescription management for pharmacies.',
                'Batch and expiry tracking, generic-brand mapping and instant billing that cut medicine wastage and billing errors.',
                ['Laravel', 'MySQL', 'Livewire'], '−38%', 'expired stock', 2026, true],
            ['Northwind Fitness App', 'fitness-mobile-app', 'mobile', 'coral', 2, 'Flutter app for workouts, classes and memberships.',
                'A cross-platform fitness app with class booking, workout tracking, payments and push notifications for a UK gym brand.',
                ['Flutter', 'Firebase', 'Stripe'], '4.8★', 'store rating', 2026, true],
            ['UrbanNest Listing Portal', 'real-estate-portal', 'web', 'orange', 3, 'High-performance property listing platform.',
                'Search, map view, agent dashboards and lead capture for a Dubai real-estate group.',
                ['Laravel', 'React', 'Elasticsearch'], '+120%', 'qualified leads', 2025, true],
            ['Spice Route Meta Ads', 'food-brand-meta-ads', 'marketing', 'yellow', 4, 'Facebook & Instagram campaigns for a food brand.',
                'Creative testing and retargeting funnels that doubled online orders while lowering acquisition cost.',
                ['Meta Ads', 'Creative', 'Pixel & CAPI'], '4.6×', 'ROAS', 2025, true],
            ['Brightpath School ERP', 'school-erp', 'erp', 'violet', 5, 'Admissions, fees, exams and payroll in one system.',
                'A modular school ERP covering admissions, fee collection, exams, attendance, parent app and teacher payroll.',
                ['Laravel', 'MySQL', 'Flutter'], '12 hrs', 'saved weekly', 2026, false],
            ['StyleHub Online Store', 'fashion-ecommerce', 'ecommerce', 'coral', null, 'Fashion e-commerce with bKash and card payments.',
                'A blazing-fast storefront with size guides, wishlists, courier tracking and mobile-money payments.',
                ['Laravel', 'Tailwind', 'bKash', 'Stripe'], '+85%', 'conversion rate', 2025, false],
            ['AI Video Ad Studio', 'ai-video-campaign', 'ai', 'teal', 0, 'AI-produced product videos for social ads.',
                'Twenty ad variants produced in a week with AI voice-over and avatar presenters — testing found three winning creatives.',
                ['AI video', 'Meta Ads', 'Motion'], '−52%', 'cost per lead', 2026, true],
            ['Alpine Fleet Dashboard', 'logistics-dashboard', 'web', 'mint', 7, 'Real-time fleet & shipment tracking dashboard.',
                'Live maps, driver apps, proof-of-delivery and automated customer notifications.',
                ['Laravel', 'Vue', 'WebSockets', 'Maps'], '99.9%', 'uptime', 2025, false],
        ];
        foreach ($defs as $i => [$title, $slug, $cat, $theme, $c, $summary, $desc, $tags, $mv, $ml, $year, $feat]) {
            PortfolioItem::create([
                'client_id' => $c !== null ? $clients[$c]->id : null,
                'title' => $title, 'slug' => $slug, 'category' => $cat, 'theme' => $theme, 'summary' => $summary,
                'description' => $desc, 'tags' => $tags, 'cover' => 'images/portfolio/' . $slug . '.svg', 'metric_value' => $mv, 'metric_label' => $ml, 'year' => $year,
                'is_featured' => $feat, 'is_published' => true, 'sort_order' => $i,
            ]);
        }
    }

    private function testimonials(): void
    {
        $defs = [
            ['Kamal Uddin', 'Managing Director', 'FreshCart Retail', 'EazyTech replaced our spreadsheets with a POS that our cashiers learned in a day. Stock is finally accurate across every outlet.'],
            ['Dr. Anika Rahman', 'Owner', 'MediPlus Pharmacy', 'Expiry alerts alone have paid for the software. The team understood how a pharmacy actually works.'],
            ['Olivia Grant', 'Founder', 'Northwind Fitness', 'Our app launched on time, looks stunning and members love it. Communication was fast and transparent throughout.'],
            ['Priya Menon', 'Marketing Head', 'Spice Route Foods', 'Their Meta Ads team doubled our online orders in two months. Reports are clear and honest — no vanity metrics.'],
        ];
        foreach ($defs as [$n, $p, $c, $q]) {
            Testimonial::create(['photo' => 'images/team/' . Str::slug($n) . '.svg', 'name' => $n, 'position' => $p, 'company' => $c, 'quote' => $q, 'rating' => 5, 'is_published' => true]);
        }
    }

    private function messages(): void
    {
        $services = Service::pluck('title')->all();
        $names = ['Mahfuz Alam', 'Rina Das', 'Chris Walker', 'Aisha Khan', 'Tom Becker', 'Nadia Hossain', 'Jamal Ahmed',
            'Sara Lopez', 'Rubel Mia', 'Tania Islam', 'Peter Novak', 'Mim Akter', 'Kabir Hasan', 'Ella Stone'];
        $budgets = ['< $1,000', '$1,000 – $5,000', '$5,000 – $15,000', '$15,000+'];
        // 6 months of enquiries with a gently rising trend (older months get fewer)
        $plan = [0 => 11, 1 => 9, 2 => 7, 3 => 6, 4 => 4, 5 => 3]; // monthsAgo => count
        $i = 0;
        foreach ($plan as $monthsAgo => $count) {
            for ($k = 0; $k < $count; $k++, $i++) {
                $n = $names[$i % count($names)];
                $when = now()->subMonthsNoOverflow($monthsAgo)->startOfMonth()->addDays(random_int(0, 24))->addHours(random_int(8, 20));
                if ($when->isFuture()) {
                    $when = now()->subHours(random_int(2, 90));
                }
                ContactMessage::create([
                    'name' => $n, 'email' => Str::slug($n, '.') . $i . '@example.com', 'phone' => '+1 555-02' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'service' => $services[$i % count($services)], 'budget' => $budgets[$i % 4],
                    'message' => 'Hi team, we would like to discuss a project and get a quotation. Please get in touch.',
                    'status' => $monthsAgo === 0 ? ($k < 4 ? 'new' : 'read') : ($monthsAgo === 1 ? 'replied' : 'archived'),
                    'created_at' => $when, 'updated_at' => $when,
                ]);
            }
        }
    }

    private function hr(array $m): void
    {
        $partTimers = ['imran', 'farhana', 'rafi', 'jui'];
        foreach ($partTimers as $key) {
            // this month + last month, weekdays only
            foreach ([0, 1] as $monthsAgo) {
                $start = now()->subMonthsNoOverflow($monthsAgo)->startOfMonth();
                $end = $monthsAgo === 0 ? now()->subDay() : $start->copy()->endOfMonth();
                for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                    if ($d->isWeekend() || random_int(0, 9) < 3) {
                        continue;
                    }
                    WorkLog::create([
                        'team_member_id' => $m[$key]->id, 'work_date' => $d->toDateString(),
                        'hours' => random_int(2, 6) + (random_int(0, 1) ? 0.5 : 0),
                        'description' => 'Project work',
                        'status' => $d->diffInDays(now()) > 3 ? 'approved' : 'pending',
                    ]);
                }
            }
        }

        $leaves = [
            ['jui', 'sick', 1, 3, 'Fever and rest', 'approved'],
            ['sumon', 'annual', 12, 16, 'Family trip', 'pending'],
            ['nusrat', 'casual', 6, 6, 'Personal errand', 'pending'],
            ['tanvir', 'annual', -20, -16, 'Vacation', 'approved'],
            ['sadia', 'sick', -9, -8, 'Medical appointment', 'rejected'],
        ];
        foreach ($leaves as [$k, $type, $a, $b, $reason, $status]) {
            LeaveRequest::create([
                'team_member_id' => $m[$k]->id, 'type' => $type, 'reason' => $reason, 'status' => $status,
                'start_date' => now()->addDays($a)->toDateString(), 'end_date' => now()->addDays($b)->toDateString(),
            ]);
        }
    }

    private function careers(): void
    {
        $defs = [
            ['Senior Laravel Developer', 'senior-laravel-developer', 'Engineering', 'permanent', 'Hybrid · On-site / Remote',
                'Build ERP, e-commerce and SaaS platforms for clients around the world. You will own features end-to-end, review code and mentor juniors.',
                "4+ years with PHP & Laravel\nStrong MySQL and API design skills\nExperience with Vue or React\nGood English communication"],
            ['Flutter Developer (Part-time)', 'flutter-developer-part-time', 'Engineering', 'remote_part_time', 'Remote · 20–25 hrs/week',
                'Join our mobile squad on a flexible remote part-time basis. Work on cross-platform apps for retail, health and fitness brands.',
                "2+ years with Flutter\nFirebase & REST integration\nPublished apps in the stores\nAbility to commit 20+ hours a week"],
            ['AI Video Editor (Part-time)', 'ai-video-editor-part-time', 'Marketing', 'remote_part_time', 'Remote · flexible hours',
                'Produce AI-assisted ad creatives, reels and explainers for our marketing clients.',
                "Experience with Premiere / CapCut / DaVinci\nFamiliar with AI video & voice tools\nStrong sense of pacing and storytelling\nPortfolio of short-form ads"],
        ];
        foreach ($defs as [$title, $slug, $dept, $type, $loc, $desc, $req]) {
            JobOpening::create(['title' => $title, 'slug' => $slug, 'department' => $dept, 'employment_type' => $type,
                'location' => $loc, 'description' => $desc, 'requirements' => $req, 'is_open' => true]);
        }
        $first = JobOpening::first();
        JobApplication::create(['job_opening_id' => $first->id, 'name' => 'Sample Applicant', 'email' => 'applicant@example.com',
            'phone' => '+880 1800-000000', 'portfolio_url' => 'https://github.com/', 'cover_letter' => 'Sample application.', 'status' => 'new']);
    }
}
