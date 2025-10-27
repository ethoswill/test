<?php

namespace App\Filament\Resources\BottleResource\Widgets;

use App\Models\TeamNote;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class BottlesHeader extends Widget
{
    protected static string $view = 'filament.resources.bottle-resource.widgets.bottles-header';

    protected int | string | array $columnSpan = 'full';

    public $content = '';
    public $isEditable = false;
    public bool $showEditModal = false;
    public string $editContent = '';

    public function mount(): void
    {
        // Check if user is super admin - check for 'super-admin' role
        $user = Auth::user();
        
        // TEMPORARY: Allow all logged-in users to edit for testing
        $this->isEditable = $user !== null;
        
        // Get or create the team note for this page
        $teamNote = TeamNote::firstOrCreate(
            ['page' => 'bottles'],
            ['content' => '']
        );
        
        // Get content, use empty string if null or invalid
        $content = $teamNote->content ?? '';
        
        // Simple UTF-8 sanitization
        if ($content && !mb_check_encoding($content, 'UTF-8')) {
            $content = '';
        }
        
        $this->content = $content ?: '';
    }

    public function getViewData(): array
    {
        return [];
    }

    public function openEditModal(): void
    {
        $this->editContent = $this->content;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editContent = '';
    }

    public function saveNotes(): void
    {
        $teamNote = TeamNote::firstOrNew(['page' => 'bottles']);
        $teamNote->content = $this->editContent;
        $teamNote->save();

        Notification::make()
            ->title('Notes updated successfully!')
            ->success()
            ->send();
        
        // Refresh the content
        $this->content = $teamNote->content;
        $this->showEditModal = false;
    }
}

