@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Notification Settings</h2>
    </div>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.settings.update', 'notifications') }}" method="POST" class="p-8">
            @csrf

            <div class="space-y-6">
                <!-- Notification Channels -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Notification Channels</h3>
                    <p class="mt-1 text-sm text-gray-500">Configure how notifications are sent to users.</p>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="settings[email_notifications]" id="email_notifications"
                                    value="1" {{ ($settings['email_notifications'] ?? '') == '1' ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                            <div class="ml-3">
                                <label for="email_notifications" class="font-medium text-gray-700">Email Notifications</label>
                                <p class="text-gray-500 text-sm">Send notifications via email</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="settings[sms_notifications]" id="sms_notifications"
                                    value="1" {{ ($settings['sms_notifications'] ?? '') == '1' ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                            <div class="ml-3">
                                <label for="sms_notifications" class="font-medium text-gray-700">SMS Notifications</label>
                                <p class="text-gray-500 text-sm">Send notifications via SMS</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Types -->
                <div class="pt-6">
                    <h3 class="text-lg font-medium text-gray-900">Notification Types</h3>
                    <p class="mt-1 text-sm text-gray-500">Select which types of notifications to send.</p>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="settings[low_balance_alert]" id="low_balance_alert"
                                    value="1" {{ ($settings['low_balance_alert'] ?? '') == '1' ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                            <div class="ml-3">
                                <label for="low_balance_alert" class="font-medium text-gray-700">Low Balance Alerts</label>
                                <p class="text-gray-500 text-sm">Notify users when their balance is low</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="settings[order_confirmation]" id="order_confirmation"
                                    value="1" {{ ($settings['order_confirmation'] ?? '') == '1' ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                            <div class="ml-3">
                                <label for="order_confirmation" class="font-medium text-gray-700">Order Confirmations</label>
                                <p class="text-gray-500 text-sm">Send confirmation after each order</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="settings[daily_report]" id="daily_report"
                                    value="1" {{ ($settings['daily_report'] ?? '') == '1' ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                            <div class="ml-3">
                                <label for="daily_report" class="font-medium text-gray-700">Daily Reports</label>
                                <p class="text-gray-500 text-sm">Send daily transaction reports to administrators</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 