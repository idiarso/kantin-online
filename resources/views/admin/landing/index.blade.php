<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Landing Page Management') }}
            </h2>
            <a href="{{ route('admin.landing.create') }}" 
               class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
                Add New Content
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="sortable-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Order
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Section
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Title
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Image
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($contents as $content)
                                    <tr data-id="{{ $content->id }}" class="cursor-move">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $content->order }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $content->section }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $content->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($content->image)
                                                <img src="{{ Storage::url($content->image) }}" 
                                                     alt="{{ $content->title }}"
                                                     class="w-16 h-16 object-cover rounded">
                                            @else
                                                No Image
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $content->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $content->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.landing.edit', $content) }}" 
                                                   class="text-blue-600 hover:text-blue-900">Edit</a>
                                                <form action="{{ route('admin.landing.destroy', $content) }}" 
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        new Sortable(document.querySelector('#sortable-table tbody'), {
            animation: 150,
            onEnd: function(evt) {
                const items = [...evt.to.children].map((tr, index) => ({
                    id: tr.dataset.id,
                    order: index
                }));

                fetch('{{ route("admin.landing.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: items })
                });
            }
        });
    </script>
    @endpush
</x-app-layout> 