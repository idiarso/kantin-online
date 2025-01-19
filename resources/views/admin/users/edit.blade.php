<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                            <p class="mt-1 text-sm text-gray-600">Leave blank to keep current password</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="role" :value="__('Role')" />
                            <x-select id="role" name="role" class="mt-1 block w-full" required>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="seller" {{ old('role', $user->role) === 'seller' ? 'selected' : '' }}>Seller</option>
                                <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Teacher</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div id="class-field" style="display: {{ $user->role === 'student' ? 'block' : 'none' }}">
                            <x-input-label for="class" :value="__('Class')" />
                            <x-text-input id="class" name="class" type="text" class="mt-1 block w-full" :value="old('class', $user->class)" />
                            <x-input-error :messages="$errors->get('class')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="balance" :value="__('Balance')" />
                            <x-text-input id="balance" name="balance" type="number" class="mt-1 block w-full" :value="old('balance', $user->balance)" />
                            <x-input-error :messages="$errors->get('balance')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update User') }}</x-primary-button>
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
    </script>
    @endpush
</x-app-layout> 