<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /** group => [key => [label, type, help]] */
    public const GROUPS = [
        'Company' => [
            'site_name' => ['Company name', 'text'],
            'tagline' => ['Tagline', 'text'],
            'currency' => ['Currency symbol', 'text'],
        ],
        'Contact' => [
            'email' => ['Public email', 'email'],
            'phone' => ['Phone', 'text'],
            'whatsapp' => ['WhatsApp number (digits only, with country code)', 'text'],
            'address' => ['Address / location line', 'text'],
            'working_hours' => ['Working hours', 'text'],
        ],
        'Social links' => [
            'facebook' => ['Facebook URL', 'url'],
            'instagram' => ['Instagram URL', 'url'],
            'linkedin' => ['LinkedIn URL', 'url'],
            'youtube' => ['YouTube URL', 'url'],
        ],
        'Homepage numbers' => [
            'stat_projects' => ['Projects delivered', 'text'],
            'stat_clients' => ['Happy clients', 'text'],
            'stat_team' => ['Team size', 'text'],
            'stat_years' => ['Years of experience', 'text'],
        ],
    ];

    public function edit()
    {
        return view('admin.settings', ['groups' => self::GROUPS, 'values' => Setting::all_()]);
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach (self::GROUPS as $fields) {
            foreach ($fields as $key => [$label, $type]) {
                $rules[$key] = ['nullable', 'string', 'max:255'] + ($type === 'url' ? [3 => 'url'] : ($type === 'email' ? [3 => 'email'] : []));
            }
        }
        $data = $request->validate($rules);
        foreach ($data as $k => $v) {
            Setting::put($k, $v);
        }

        return back()->with('success', 'Settings saved.');
    }
}
