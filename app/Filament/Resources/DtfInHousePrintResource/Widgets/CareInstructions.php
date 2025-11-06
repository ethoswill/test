<?php

namespace App\Filament\Resources\DtfInHousePrintResource\Widgets;

use App\Models\DtfWidgetContent;
use Filament\Widgets\Widget;
use Filament\Forms\Components\RichEditor;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Support\Contracts\TranslatableContentDriver;

class CareInstructions extends Widget implements HasActions
{
    use InteractsWithActions;

    protected static string $view = 'filament.resources.dtf-in-house-print-resource.widgets.care-instructions';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 7;
    
    protected static bool $isLazy = false;

    public $content = '';
    public bool $hasFormsModalRendered = false;
    public bool $hasInfolistsModalRendered = false;
    public ?array $mountedFormComponentActions = [];

    public function mount(): void
    {
        $widget = DtfWidgetContent::firstOrCreate(
            ['widget_name' => 'care_instructions'],
            ['content' => '']
        );
        
        $this->content = $widget->content ?: '';
        
        foreach ($this->getActions() as $action) {
            if ($action instanceof Action) {
                $this->cacheAction($action);
            }
        }
    }

    public function editContent(): Action
    {
        return Action::make('edit_content')
            ->label('Edit')
            ->icon('heroicon-o-pencil')
            ->form([
                RichEditor::make('content')
                    ->label('Content')
                    ->placeholder('Enter your notes here. You can use HTML tags like <h3>Heading</h3> and <br> for line breaks.')
                    ->helperText('You can use HTML tags like <h3>, <h2>, <br>, <p>, <strong>, <em>, etc.')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->default(fn () => $this->content),
            ])
            ->action(function (array $data): void {
                $widget = DtfWidgetContent::firstOrNew(['widget_name' => 'care_instructions']);
                $widget->content = $data['content'];
                $widget->save();

                $this->content = $widget->content;

                Notification::make()
                    ->title('Content updated successfully!')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation(false)
            ->modalHeading('Edit Care Instructions')
            ->modalSubmitActionLabel('Save');
    }

    protected function getActions(): array
    {
        return [
            $this->editContent(),
        ];
    }

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null;
    }

    public function getMountedFormComponentAction() { return null; }
    public function mountedFormComponentActionShouldOpenModal(): bool { return false; }
    public function mountedFormComponentActionHasForm(): bool { return false; }
    public function getMountedFormComponentActionForm() { return null; }
    public function unmountFormComponentAction(bool $shouldCancelParentActions = true, bool $shouldCloseModal = true): void {}
}

