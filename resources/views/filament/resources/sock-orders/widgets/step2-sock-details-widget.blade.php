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
                    2
                @endif
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sock Details</h3>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thread Color</label>
                <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border">
                    {{ $record->thread_color ?? 'Not specified' }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thread Color(s)</label>
                <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border">
                    {{ $record->thread_colors ?? 'Not specified' }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grip Design</label>
                @if($record->grip_design_file)
                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border">
                        <a href="{{ Storage::url($record->grip_design_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            View File
                        </a>
                    </div>
                @else
                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border text-gray-500">
                        No file uploaded
                    </div>
                @endif
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Packaging Design</label>
                @if($record->packaging_design_file)
                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border">
                        <a href="{{ Storage::url($record->packaging_design_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            View File
                        </a>
                    </div>
                @else
                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border text-gray-500">
                        No file uploaded
                    </div>
                @endif
            </div>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo Style</label>
                <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border">
                    {{ $record->logo_style ?? 'Not specified' }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Embroidered Logo Thread Color(s)</label>
                <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border">
                    {{ $record->embroidered_logo_thread_colors ?? 'Not specified' }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grip Color</label>
                <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border">
                    {{ $record->grip_color ?? 'Not specified' }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">EID</label>
                <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-md border">
                    {{ $record->eid ?? 'Not generated' }}
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
