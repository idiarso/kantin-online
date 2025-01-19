<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function hours()
    {
        $operatingHours = Setting::where('key', 'operating_hours')->first();
        $hours = $operatingHours ? json_decode($operatingHours->value, true) : [
            'monday' => ['open' => '07:00', 'close' => '16:00', 'closed' => false],
            'tuesday' => ['open' => '07:00', 'close' => '16:00', 'closed' => false],
            'wednesday' => ['open' => '07:00', 'close' => '16:00', 'closed' => false],
            'thursday' => ['open' => '07:00', 'close' => '16:00', 'closed' => false],
            'friday' => ['open' => '07:00', 'close' => '14:00', 'closed' => false],
            'saturday' => ['open' => '00:00', 'close' => '00:00', 'closed' => true],
            'sunday' => ['open' => '00:00', 'close' => '00:00', 'closed' => true],
        ];

        return view('admin.canteen.hours', compact('hours'));
    }

    public function updateHours(Request $request)
    {
        $request->validate([
            'hours.*.open' => 'required_if:hours.*.closed,false|date_format:H:i',
            'hours.*.close' => 'required_if:hours.*.closed,false|date_format:H:i',
            'hours.*.closed' => 'boolean'
        ]);

        Setting::updateOrCreate(
            ['key' => 'operating_hours'],
            ['value' => json_encode($request->hours)]
        );

        return redirect()->route('admin.canteen.hours')
            ->with('success', 'Operating hours updated successfully');
    }

    public function general()
    {
        $settings = Setting::whereIn('key', [
            'school_name',
            'school_address',
            'contact_email',
            'contact_phone',
            'currency',
            'timezone'
        ])->pluck('value', 'key')->toArray();

        return view('admin.settings.general', compact('settings'));
    }

    public function notifications()
    {
        $settings = Setting::whereIn('key', [
            'email_notifications',
            'sms_notifications',
            'low_balance_alert',
            'order_confirmation',
            'daily_report'
        ])->pluck('value', 'key')->toArray();

        return view('admin.settings.notifications', compact('settings'));
    }

    public function update(Request $request, $type)
    {
        $validatedData = $request->validate([
            'settings.*' => 'required'
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', ucfirst($type) . ' settings updated successfully');
    }
} 