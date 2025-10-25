<div class="p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Load Saved View</h3>
    
    @php
        $savedViews = \App\Models\SavedReportView::getUserViews(auth()->id(), 'product_sales');
    @endphp
    
    @if($savedViews->count() > 0)
        <div class="space-y-2">
            @foreach($savedViews as $view)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $view->name }}</h4>
                            <p class="text-sm text-gray-500">
                                {{ $view->is_default ? 'Default View' : 'Custom View' }}
                                • {{ count($view->visible_columns) }} columns
                                • {{ $view->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button 
                            wire:click="loadView({{ $view->id }})"
                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Load
                        </button>
                        @if(!$view->is_default)
                            <button 
                                wire:click="deleteView({{ $view->id }})"
                                class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                Delete
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No saved views</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by saving your current view.</p>
        </div>
    @endif
</div>
