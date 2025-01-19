<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="role" :value="__('Role')" />
                            <x-select id="role" name="role" class="mt-1 block w-full" required>
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="seller" {{ old('role') === 'seller' ? 'selected' : '' }}>Seller</option>
                                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div id="class-field" style="display: none;">
                            <x-input-label for="class" :value="__('Class')" />
                            <x-text-input id="class" name="class" type="text" class="mt-1 block w-full" :value="old('class')" />
                            <x-input-error :messages="$errors->get('class')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="balance" :value="__('Initial Balance')" />
                            <x-text-input id="balance" name="balance" type="number" class="mt-1 block w-full" :value="old('balance', 0)" />
                            <x-input-error :messages="$errors->get('balance')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create User') }}</x-primary-button>
                            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const roleSelect = document.getElementById('role');
        const classField = document.getElementById('class-field');

        roleSelect.addEventListener('change', function() {
            classField.style.display = this.value === 'student' ? 'block' : 'none';
        });

        // Show/hide class field on page load
        if (roleSelect.value === 'student') {
            classField.style.display = 'block';
        }
    </script>
    @endpush
</x-app-layout> 