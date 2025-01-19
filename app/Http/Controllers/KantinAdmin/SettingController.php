<?php

namespace App\Http\Controllers\KantinAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        
        return view('kantin-admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'canteen_name' => 'required|string|max:255',
            'opening_hours' => 'required|string',
            'closing_hours' => 'required|string',
            'min_order_amount' => 'required|numeric|min:0',
            'max_daily_limit' => 'required|numeric|min:0',
            'preparation_buffer' => 'required|numeric|min:0',
            'low_stock_threshold' => 'required|numeric|min:0',
            'enable_notifications' => 'boolean',
            'maintenance_mode' => 'boolean'
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('settings');

        return back()->with('success', 'Pengaturan berhasil disimpan');
    }

    public function updateOperationalHours(Request $request)
    {
        $request->validate([
            'operational_hours' => 'required|array',
            'operational_hours.*.day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'operational_hours.*.open' => 'required|boolean',
            'operational_hours.*.opening_time' => 'required_if:operational_hours.*.open,true',
            'operational_hours.*.closing_time' => 'required_if:operational_hours.*.open,true'
        ]);

        Setting::updateOrCreate(
            ['key' => 'operational_hours'],
            ['value' => json_encode($request->operational_hours)]
        );

        Cache::forget('settings');

        return back()->with('success', 'Jam operasional berhasil diperbarui');
    }

    public function updatePaymentMethods(Request $request)
    {
        $request->validate([
            'payment_methods' => 'required|array',
            'payment_methods.*' => 'required|in:balance,cash'
        ]);

        Setting::updateOrCreate(
            ['key' => 'payment_methods'],
            ['value' => json_encode($request->payment_methods)]
        );

        Cache::forget('settings');

        return back()->with('success', 'Metode pembayaran berhasil diperbarui');
    }

    public function updateNotificationSettings(Request $request)
    {
        $request->validate([
            'notification_settings' => 'required|array',
            'notification_settings.order_received' => 'boolean',
            'notification_settings.order_status_changed' => 'boolean',
            'notification_settings.low_stock_alert' => 'boolean',
            'notification_settings.daily_report' => 'boolean'
        ]);

        Setting::updateOrCreate(
            ['key' => 'notification_settings'],
            ['value' => json_encode($request->notification_settings)]
        );

        Cache::forget('settings');

        return back()->with('success', 'Pengaturan notifikasi berhasil diperbarui');
    }
} 