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
                    5
                @endif
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ship Date ETA Widget</h3>
        </div>
    </div>
    
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ship Date ETA</label>
            <div class="flex items-center space-x-2">
                <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border flex-1">
                    @if($record->ship_date_eta)
                        <span class="text-gray-900 dark:text-white">{{ $record->ship_date_eta->format('M d, Y') }}</span>
                    @else
                        <span class="text-gray-500 dark:text-gray-400">No date set</span>
                    @endif
                </div>
                <div class="text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if($isCurrentStep)
        <div class="mt-6 flex justify-end">
            @foreach($this->getActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    @endif
</div>
