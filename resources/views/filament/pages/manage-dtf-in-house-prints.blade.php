<x-filament-panels::page>
    @if (method_exists($this, 'getWidgets'))
        <x-filament-widgets::widgets 
            :widgets="$this->getWidgets()" 
        />
    @endif
</x-filament-panels::page>

