<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        return view('site.home', [
            'services' => Service::active()->get(),
            'featured' => PortfolioItem::published()->where('is_featured', true)->take(6)->get(),
            'testimonials' => Testimonial::where('is_published', true)->latest()->get(),
            'team' => TeamMember::public()->take(4)->get(),
        ]);
    }

    public function services()
    {
        $services = Service::active()->get();

        return view('site.services', [
            'software' => $services->where('category', 'software'),
            'marketing' => $services->where('category', 'marketing'),
        ]);
    }

    public function service(string $slug)
    {
        $service = Service::active()->where('slug', $slug)->firstOrFail();

        return view('site.service', [
            'service' => $service,
            'related' => Service::active()->where('id', '!=', $service->id)->where('category', $service->category)->take(3)->get(),
            'work' => PortfolioItem::published()->take(3)->get(),
        ]);
    }

    public function portfolio()
    {
        return view('site.portfolio', [
            'items' => PortfolioItem::published()->get(),
            'categories' => PortfolioItem::CATEGORIES,
        ]);
    }

    public function project(string $slug)
    {
        $item = PortfolioItem::published()->where('slug', $slug)->firstOrFail();
        $others = PortfolioItem::published()->where('id', '!=', $item->id)->take(3)->get();

        return view('site.project', ['item' => $item->load('client'), 'others' => $others]);
    }

    public function about()
    {
        return view('site.about', [
            'team' => TeamMember::public()->with('department')->get(),
        ]);
    }

    public function careers()
    {
        return view('site.careers', ['openings' => JobOpening::where('is_open', true)->latest()->get()]);
    }

    public function apply(Request $request)
    {
        if ($request->filled('website')) { // honeypot
            return back();
        }
        $data = $request->validate([
            'job_opening_id' => ['nullable', 'exists:job_openings,id'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'cover_letter' => ['nullable', 'string', 'max:3000'],
        ]);
        JobApplication::create($data);

        return redirect()->to(route('site.careers') . '#apply')->with('success', 'Thanks! Your application is in — our HR team will reach out soon.');
    }

    public function contact()
    {
        return view('site.contact', ['services' => Service::active()->pluck('title')]);
    }

    public function sendContact(Request $request)
    {
        if ($request->filled('website')) { // honeypot — pretend success
            return back()->with('success', 'Thanks! We will get back to you shortly.');
        }
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'service' => ['nullable', 'string', 'max:150'],
            'budget' => ['nullable', 'string', 'max:60'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
        ]);
        ContactMessage::create($data + ['ip' => $request->ip()]);

        return redirect()->to(url()->previous() . '#contact-form')
            ->with('success', 'Thanks! Your message is on its way — we usually reply within one business day.');
    }
}
