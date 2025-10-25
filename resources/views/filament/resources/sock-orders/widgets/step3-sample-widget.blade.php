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
                    3
                @endif
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sample Widget</h3>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Upload Image</label>
            @if($record->sample_image_1)
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                    <img src="{{ Storage::url($record->sample_image_1) }}" alt="Sample Image 1" class="w-full h-48 object-cover rounded">
                </div>
            @else
                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Upload Image</p>
                </div>
            @endif
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Upload Image</label>
            @if($record->sample_image_2)
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                    <img src="{{ Storage::url($record->sample_image_2) }}" alt="Sample Image 2" class="w-full h-48 object-cover rounded">
                </div>
            @else
                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Upload Image</p>
                </div>
            @endif
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
