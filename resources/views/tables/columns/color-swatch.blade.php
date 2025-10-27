@php
    $hex = strtoupper($getState() ?? '');
    // Remove # if present
    $hex = ltrim($hex, '#');
    // Ensure it's a valid hex code
    if (strlen($hex) === 6 && ctype_xdigit($hex)) {
        $hexCode = '#' . $hex;
    } else {
        $hexCode = '#FFFFFF'; // Default to white if invalid
    }
@endphp

<div class="flex items-center gap-2">
    <div 
        class="w-10 h-10 rounded-full border-2 border-gray-300 shadow-sm" 
        style="background-color: {{ $hexCode }};"
        title="Hex: {{ $hexCode }}"
    ></div>
    <span class="text-sm font-mono text-gray-600 dark:text-gray-400">{{ $hexCode }}</span>
</div>

