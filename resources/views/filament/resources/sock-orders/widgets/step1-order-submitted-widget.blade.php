@php
    $isCompleted = $this->isCompleted();
    $isCurrentStep = $this->isCurrentStep();
    $isDisabled = !$isCurrentStep && !$isCompleted;
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 {{ $isDisabled ? 'opacity-50' : '' }}">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $isCompleted ? 'bg-green-500' : ($isCurrentStep ? 'bg-blue-500' : 'bg-gray-300') }} text-white text-sm font-medium">
                @if($isCompleted)
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @else
                    1
                @endif
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Submitted by customer</h3>
        </div>
        <x-filament::badge
            :color="$record->getStatusColor()"
            :icon="$record->getStatusIcon()"
        >
            Current Sock Status
        </x-filament::badge>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-2">
            <p class="text-sm text-gray-600 dark:text-gray-400">Order ID</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $record->order_number ?? 'N/A' }}</p>
        </div>
        <div class="space-y-2">
            <p class="text-sm text-gray-600 dark:text-gray-400">Order Date</p>
            <p class="text-lg text-gray-900 dark:text-white">{{ $record->order_date?->format('M d, Y') ?? 'N/A' }}</p>
        </div>
    </div>
    
    @if($record->sock_images)
        <div class="mt-6">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Images Of Sock</h4>
            <div class="grid grid-cols-2 gap-4">
                @foreach($record->sock_images as $image)
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 text-center">
                        <img src="{{ Storage::url($image) }}" alt="Sock Image" class="w-full h-32 object-cover rounded">
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="mt-6">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Images Of Sock</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Upload Image</p>
                </div>
                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Upload Image</p>
                </div>
            </div>
        </div>
    @endif

    @if($isCurrentStep)
        <div class="mt-6 flex justify-end">
            @foreach($this->getActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    @endif
</div>
