<x-filament-panels::page>
    @php
        $widgets = $this->getHeaderWidgets();
    @endphp
    
    @if ($widgets)
        <x-filament-widgets::widgets
            :widgets="$widgets"
        />
    @endif
</x-filament-panels::page>

