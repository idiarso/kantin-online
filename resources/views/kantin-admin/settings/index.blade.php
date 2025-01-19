<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Pengaturan Kantin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- General Settings -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-medium">Pengaturan Umum</h3>
                        <form action="{{ route('kantin.admin.settings.update') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="canteen_name" value="Nama Kantin" />
                                <x-text-input id="canteen_name" 
                                             name="canteen_name" 
                                             type="text"
                                             class="block w-full mt-1"
                                             value="{{ old('canteen_name', $settings['canteen_name'] ?? '') }}" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="opening_hours" value="Jam Buka" />
                                    <x-text-input id="opening_hours" 
                                                 name="opening_hours" 
                                                 type="time"
                                                 class="block w-full mt-1"
                                                 value="{{ old('opening_hours', $settings['opening_hours'] ?? '') }}" />
                                </div>
                                <div>
                                    <x-input-label for="closing_hours" value="Jam Tutup" />
                                    <x-text-input id="closing_hours" 
                                                 name="closing_hours" 
                                                 type="time"
                                                 class="block w-full mt-1"
                                                 value="{{ old('closing_hours', $settings['closing_hours'] ?? '') }}" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="min_order_amount" value="Minimal Order (Rp)" />
                                    <x-text-input id="min_order_amount" 
                                                 name="min_order_amount" 
                                                 type="number"
                                                 class="block w-full mt-1"
                                                 value="{{ old('min_order_amount', $settings['min_order_amount'] ?? 0) }}" />
                                </div>
                                <div>
                                    <x-input-label for="max_daily_limit" value="Limit Harian (Rp)" />
                                    <x-text-input id="max_daily_limit" 
                                                 name="max_daily_limit" 
                                                 type="number"
                                                 class="block w-full mt-1"
                                                 value="{{ old('max_daily_limit', $settings['max_daily_limit'] ?? 0) }}" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="preparation_buffer" value="Buffer Persiapan (Menit)" />
                                    <x-text-input id="preparation_buffer" 
                                                 name="preparation_buffer" 
                                                 type="number"
                                                 class="block w-full mt-1"
                                                 value="{{ old('preparation_buffer', $settings['preparation_buffer'] ?? 15) }}" />
                                </div>
                                <div>
                                    <x-input-label for="low_stock_threshold" value="Batas Stok Minimum" />
                                    <x-text-input id="low_stock_threshold" 
                                                 name="low_stock_threshold" 
                                                 type="number"
                                                 class="block w-full mt-1"
                                                 value="{{ old('low_stock_threshold', $settings['low_stock_threshold'] ?? 10) }}" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="maintenance_mode"
                                           name="maintenance_mode"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                           {{ isset($settings['maintenance_mode']) && $settings['maintenance_mode'] ? 'checked' : '' }}>
                                    <label for="maintenance_mode" class="ml-2 text-sm text-gray-600">
                                        Mode Maintenance
                                    </label>
                                </div>
                                <x-primary-button>Simpan Pengaturan</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-medium">Metode Pembayaran</h3>
                        <form action="{{ route('kantin.admin.settings.payment-methods') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                @php
                                    $paymentMethods = json_decode($settings['payment_methods'] ?? '[]', true);
                                @endphp
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="payment_methods_balance"
                                           name="payment_methods[]"
                                           value="balance"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                           {{ in_array('balance', $paymentMethods) ? 'checked' : '' }}>
                                    <label for="payment_methods_balance" class="ml-2">
                                        Saldo Digital
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="payment_methods_cash"
                                           name="payment_methods[]"
                                           value="cash"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                           {{ in_array('cash', $paymentMethods) ? 'checked' : '' }}>
                                    <label for="payment_methods_cash" class="ml-2">
                                        Tunai
                                    </label>
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-primary-button>Update Metode Pembayaran</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-medium">Pengaturan Notifikasi</h3>
                        <form action="{{ route('kantin.admin.settings.notifications') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                @php
                                    $notificationSettings = json_decode($settings['notification_settings'] ?? '[]', true);
                                @endphp
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="notification_settings_order_received"
                                           name="notification_settings[order_received]"
                                           value="1"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                           {{ isset($notificationSettings['order_received']) && $notificationSettings['order_received'] ? 'checked' : '' }}>
                                    <label for="notification_settings_order_received" class="ml-2">
                                        Pesanan Baru
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="notification_settings_order_status_changed"
                                           name="notification_settings[order_status_changed]"
                                           value="1"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                           {{ isset($notificationSettings['order_status_changed']) && $notificationSettings['order_status_changed'] ? 'checked' : '' }}>
                                    <label for="notification_settings_order_status_changed" class="ml-2">
                                        Perubahan Status Pesanan
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="notification_settings_low_stock_alert"
                                           name="notification_settings[low_stock_alert]"
                                           value="1"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                           {{ isset($notificationSettings['low_stock_alert']) && $notificationSettings['low_stock_alert'] ? 'checked' : '' }}>
                                    <label for="notification_settings_low_stock_alert" class="ml-2">
                                        Peringatan Stok Menipis
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="notification_settings_daily_report"
                                           name="notification_settings[daily_report]"
                                           value="1"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                           {{ isset($notificationSettings['daily_report']) && $notificationSettings['daily_report'] ? 'checked' : '' }}>
                                    <label for="notification_settings_daily_report" class="ml-2">
                                        Laporan Harian
                                    </label>
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-primary-button>Update Pengaturan Notifikasi</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 