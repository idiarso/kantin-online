@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Edit User: {{ $user->name }}</h2>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Update the user's basic information.</p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Leave blank to keep current password</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            onchange="toggleRoleFields(this.value)">
                            <option value="">Select Role</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="parent" {{ old('role', $user->role) === 'parent' ? 'selected' : '' }}>Parent</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Role-specific Fields -->
            <div class="mt-8 space-y-6">
                <!-- Student Fields -->
                <div id="student-fields" class="hidden space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Student Information</h3>
                        <p class="mt-1 text-sm text-gray-500">Additional information required for students.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                            <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $user->student_id) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('student_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="class" class="block text-sm font-medium text-gray-700">Class</label>
                            <input type="text" name="class" id="class" value="{{ old('class', $user->class) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('class')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Teacher Fields -->
                <div id="teacher-fields" class="hidden space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Teacher Information</h3>
                        <p class="mt-1 text-sm text-gray-500">Additional information required for teachers.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee ID</label>
                            <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('employee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject', $user->subject) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Parent Fields -->
                <div id="parent-fields" class="hidden space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Parent Information</h3>
                        <p class="mt-1 text-sm text-gray-500">Additional information required for parents.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleRoleFields(role) {
    // Hide all role-specific fields first
    document.getElementById('student-fields').classList.add('hidden');
    document.getElementById('teacher-fields').classList.add('hidden');
    document.getElementById('parent-fields').classList.add('hidden');
    
    // Show fields based on selected role
    if (role === 'student') {
        document.getElementById('student-fields').classList.remove('hidden');
    } else if (role === 'teacher') {
        document.getElementById('teacher-fields').classList.remove('hidden');
    } else if (role === 'parent') {
        document.getElementById('parent-fields').classList.remove('hidden');
    }
}

// Show fields for current role on page load
document.addEventListener('DOMContentLoaded', function() {
    const role = document.getElementById('role').value;
    if (role) {
        toggleRoleFields(role);
    }
});
</script>
@endpush
@endsection 