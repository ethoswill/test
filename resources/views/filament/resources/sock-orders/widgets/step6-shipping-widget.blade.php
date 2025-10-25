@php
    $isCompleted = $this->isCompleted();
    $isCurrentStep = $this->isCurrentStep();
    $isDisabled = !$isCurrentStep && !$isCompleted;
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 {{ $isDisabled ? 'opacity-50' : '' }}">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $isCompleted ? 'bg-green-500' : ($isCurrentStep ? 'bg-blue-500' : 'bg-gray-300') }} text-white text-sm font-medium">
                @if($isCompleted)
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @else
                    6
                @endif
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Shipped / Tracking Number Widget</h3>
        </div>
    </div>
    
    <div class="space-y-4">
        <div class="flex items-center space-x-3">
            <input 
                type="checkbox" 
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                {{ $record->is_shipped ? 'checked' : '' }}
                disabled
            >
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mark As Shipped</label>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tracking Number</label>
            <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border">
                @if($record->tracking_number)
                    <span class="text-gray-900 dark:text-white">{{ $record->tracking_number }}</span>
                @else
                    <span class="text-gray-500 dark:text-gray-400">No tracking number</span>
                @endif
            </div>
        </div>
        
        @if($record->is_shipped)
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-800 dark:text-green-200 font-medium">Order Shipped</p>
                </div>
            </div>
        @endif
    </div>

    @if($isCurrentStep)
        <div class="mt-6 flex justify-end">
            @foreach($this->getActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    @endif
</div>
