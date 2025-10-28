<?php

namespace App\Filament\Pages;

use App\Models\Faq;
use App\Models\HowTo;
use Filament\Pages\Page;

class CustomerService extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string $view = 'filament.pages.customer-service';
    
    public function getTitle(): string
    {
        return 'Customer Service';
    }
    
    public function getFaqs()
    {
        return Faq::active()->ordered()->get();
    }
    
    public function getHowTos()
    {
        return HowTo::active()->ordered()->get();
    }
}
