<x-filament-panels::page>
    <div class="mb-4">
        <a href="{{ url('/admin/login-infos/create') }}" class="text-black underline hover:no-underline text-base font-medium">
            New Login Info
        </a>
    </div>

    <div class="space-y-4" x-data>
        @forelse($this->getLoginInfos() as $loginInfo)
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden" x-data="{ open: false }">
                <!-- Header - Clickable -->
                <button 
                    @click="open = !open"
                    class="w-full flex justify-between items-start p-6 hover:bg-gray-50 transition-colors text-left"
                    type="button"
                >
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $loginInfo->website_name }}</h3>
                        @if($loginInfo->url)
                            <p class="text-sm text-blue-600 mt-1">{{ $loginInfo->url }}</p>
                        @endif
                        @if($loginInfo->username)
                            <p class="text-sm font-medium text-gray-600 mt-1">{{ $loginInfo->username }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 ml-4">
                        <a 
                            href="{{ url('/admin/login-infos/' . $loginInfo->id . '/edit') }}" 
                            @click.stop
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                        >
                            Edit
                        </a>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>

                <!-- Collapsible Content -->
                <div 
                    x-show="open"
                    x-collapse
                    class="border-t border-gray-200"
                >
                    <div class="p-6 pt-4">
                        <div class="space-y-4">
                            @if($loginInfo->password)
                                <div class="flex bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Password</h4>
                                        <span class="text-base text-gray-900 font-mono">{{ $loginInfo->password }}</span>
                                    </div>
                                </div>
                            @endif
                            
                            @if($loginInfo->description)
                                <div class="flex bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Notes</h4>
                                        <span class="text-base text-gray-900 leading-relaxed block">{{ $loginInfo->description }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No Login Info</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Get started by adding login details for your team.
                </p>
                <div class="mt-6">
                    <a href="{{ url('/admin/login-infos/create') }}" class="text-black underline hover:no-underline text-base font-medium">
                        New Login Info
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
