@extends('layouts.admin')

@section('header')
    <h2 class="text-2xl font-semibold text-gray-800">Manage Announcements</h2>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('admin.landing.announcements.update') }}" method="POST">
            @csrf
            
            <div id="announcements-container">
                @forelse($announcements ?? [] as $index => $announcement)
                    <div class="announcement-item border-b border-gray-200 pb-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Announcement #{{ $index + 1 }}</h3>
                            <button type="button" onclick="removeAnnouncement(this)" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="announcements[{{ $index }}][title]" 
                                value="{{ old("announcements.$index.title", $announcement->title) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                            <textarea name="announcements[{{ $index }}][content]" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>{{ old("announcements.$index.content", $announcement->content) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="announcements[{{ $index }}][status]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="active" {{ old("announcements.$index.status", $announcement->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old("announcements.$index.status", $announcement->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No announcements yet. Add one below.</p>
                @endforelse
            </div>

            <div class="flex justify-between items-center mt-6">
                <button type="button" onclick="addAnnouncement()" 
                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Add Announcement
                </button>

                <button type="submit" 
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function addAnnouncement() {
        const container = document.getElementById('announcements-container');
        const index = container.children.length;
        
        const template = `
            <div class="announcement-item border-b border-gray-200 pb-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Announcement #${index + 1}</h3>
                    <button type="button" onclick="removeAnnouncement(this)" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="announcements[${index}][title]" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    <textarea name="announcements[${index}][content]" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="announcements[${index}][status]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', template);
    }

    function removeAnnouncement(button) {
        const item = button.closest('.announcement-item');
        item.remove();
        
        // Update announcement numbers
        const items = document.querySelectorAll('.announcement-item');
        items.forEach((item, index) => {
            item.querySelector('h3').textContent = `Announcement #${index + 1}`;
        });
    }
</script>
@endpush
@endsection 