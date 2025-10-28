<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Useful Contact Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Useful Contact Info</h2>
            @if($this->getContactInfo()->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($this->getContactInfo() as $contact)
                        <div class="border {{ $contact->is_emergency ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200' }} rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <svg class="h-6 w-6 {{ $this->getIconColor($contact->icon_color) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $this->getIconPath($contact->icon_name) !!}
                                </svg>
                                <h3 class="font-semibold text-gray-900 ml-2">{{ $contact->department }}</h3>
                                @if($contact->is_emergency)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        24/7
                                    </span>
                                @endif
                            </div>
                            @if($contact->title)
                                <p class="text-sm font-medium text-gray-700 mb-1">{{ $contact->title }}</p>
                            @endif
                            @if($contact->description)
                                <p class="text-sm text-gray-600 mb-2">{{ $contact->description }}</p>
                            @endif
                            @if($contact->phone)
                                <p class="text-sm font-medium text-gray-900">Phone: {{ $contact->phone }}</p>
                            @endif
                            @if($contact->email)
                                <p class="text-sm font-medium text-gray-900">Email: {{ $contact->email }}</p>
                            @endif
                            @if($contact->hours)
                                <p class="text-sm text-gray-600 mt-1">{{ $contact->hours }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No contact information yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding contact information.</p>
                    <div class="mt-6">
                        <a href="{{ route('filament.admin.resources.contact-infos.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Contact Info
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>