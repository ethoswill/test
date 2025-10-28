<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Useful Contact Info -->
        <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-2xl font-bold text-gray-900">Useful Contact Info</h2>
                        <p class="text-sm text-gray-600 mt-1">Quick access to important contacts for your team</p>
                    </div>
                </div>
                <a href="{{ url('/admin/contact-infos/create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Contact Info
                </a>
            </div>
            
            @if($this->getContactInfo()->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($this->getContactInfo() as $contact)
                        <div class="bg-white {{ $contact->is_emergency ? 'border-2 border-amber-300 shadow-lg' : 'border border-gray-200' }} rounded-xl p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 {{ $contact->is_emergency ? 'bg-amber-100' : 'bg-blue-50' }} rounded-lg p-3">
                                        <svg class="h-6 w-6 {{ $this->getIconColor($contact->icon_color) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            {!! $this->getIconPath($contact->icon_name) !!}
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $contact->department }}</h3>
                                        @if($contact->is_emergency)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                24/7 Emergency
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Details -->
                            <div class="space-y-3">
                                @if($contact->title)
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span class="text-base font-semibold text-gray-900">{{ $contact->title }}</span>
                                    </div>
                                @endif
                                
                                @if($contact->description)
                                    <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500">
                                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $contact->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">No contact information yet</h3>
                    <p class="mt-2 text-sm text-gray-500">Get started by adding contact information for your team.</p>
                    <div class="mt-6">
                        <a href="{{ url('/admin/contact-infos/create') }}" class="inline-flex items-center px-5 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md hover:shadow-lg transition-all">
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