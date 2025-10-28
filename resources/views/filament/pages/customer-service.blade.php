<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Customer FAQ's -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer FAQ's</h2>
            @if($this->getFaqs()->count() > 0)
                <div class="space-y-4">
                    @foreach($this->getFaqs() as $faq)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">{{ $faq->question }}</h3>
                            <p class="text-sm text-gray-600">{{ $faq->answer }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No FAQs yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding some frequently asked questions.</p>
                    <div class="mt-6">
                        <a href="{{ route('filament.admin.resources.faqs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add FAQ
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- How To's -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">How To's</h2>
            @if($this->getHowTos()->count() > 0)
                <div class="space-y-4">
                    @foreach($this->getHowTos() as $howTo)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">{{ $howTo->title }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ $howTo->description }}</p>
                            @if(!empty($howTo->steps))
                                <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                                    @foreach($howTo->steps as $step)
                                        <li>{{ $step['step'] }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No How To's yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding some helpful guides.</p>
                    <div class="mt-6">
                        <a href="{{ route('filament.admin.resources.how-tos.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add How To
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Useful Contact Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Useful Contact Info</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Sales -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                        <h3 class="font-semibold text-gray-900 ml-2">Sales</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">For product inquiries and orders</p>
                    <p class="text-sm font-medium text-gray-900">Phone: (555) 123-4567</p>
                    <p class="text-sm font-medium text-gray-900">Email: sales@example.com</p>
                    <p class="text-sm text-gray-600">Hours: Mon-Fri 9am-6pm EST</p>
                </div>

                <!-- Customer Support -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="font-semibold text-gray-900 ml-2">Customer Support</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">For technical assistance and inquiries</p>
                    <p class="text-sm font-medium text-gray-900">Phone: (555) 123-4568</p>
                    <p class="text-sm font-medium text-gray-900">Email: support@example.com</p>
                    <p class="text-sm text-gray-600">Hours: Mon-Fri 8am-8pm EST</p>
                </div>

                <!-- Accounts & Billing -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <h3 class="font-semibold text-gray-900 ml-2">Accounts & Billing</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">For billing and account questions</p>
                    <p class="text-sm font-medium text-gray-900">Phone: (555) 123-4569</p>
                    <p class="text-sm font-medium text-gray-900">Email: billing@example.com</p>
                    <p class="text-sm text-gray-600">Hours: Mon-Fri 9am-5pm EST</p>
                </div>

                <!-- Production -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <h3 class="font-semibold text-gray-900 ml-2">Production</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">For order status and production updates</p>
                    <p class="text-sm font-medium text-gray-900">Phone: (555) 123-4570</p>
                    <p class="text-sm font-medium text-gray-900">Email: production@example.com</p>
                    <p class="text-sm text-gray-600">Hours: Mon-Fri 7am-5pm EST</p>
                </div>

                <!-- Design & Technical -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <h3 class="font-semibold text-gray-900 ml-2">Design & Technical</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">For design files and specifications</p>
                    <p class="text-sm font-medium text-gray-900">Phone: (555) 123-4571</p>
                    <p class="text-sm font-medium text-gray-900">Email: design@example.com</p>
                    <p class="text-sm text-gray-600">Hours: Mon-Fri 9am-6pm EST</p>
                </div>

                <!-- Emergency Contact -->
                <div class="border border-yellow-400 rounded-lg p-4 bg-yellow-50">
                    <div class="flex items-center mb-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="font-semibold text-gray-900 ml-2">24/7 Emergency</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Urgent issues requiring immediate attention</p>
                    <p class="text-sm font-medium text-gray-900">Phone: (555) 123-9999</p>
                    <p class="text-sm font-medium text-gray-900">Email: emergency@example.com</p>
                    <p class="text-sm text-gray-600">Available 24/7 for critical issues</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>