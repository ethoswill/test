<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Permission;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load individual permissions
        $individualPermissions = $this->record->roles()
            ->where('slug', 'like', 'individual-permissions-%')
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('slug')
            ->toArray();
            
        $data['permissions'] = $individualPermissions;
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove permissions from the main data array as we'll handle them separately
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Handle individual permissions
        $permissions = $this->form->getState()['permissions'] ?? [];
        
        // Remove existing individual permissions role
        $existingIndividualRole = $this->record->roles()
            ->where('slug', 'like', 'individual-permissions-%')
            ->first();
            
        if ($existingIndividualRole) {
            $this->record->removeRole($existingIndividualRole);
            $existingIndividualRole->delete();
        }
        
        // Create new individual permissions role if needed
        if (!empty($permissions)) {
            $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id');
            
            $individualRole = \App\Models\Role::create([
                'slug' => 'individual-permissions-' . $this->record->id,
                'name' => 'Individual Permissions - ' . $this->record->full_name,
                'description' => 'Individual permissions for ' . $this->record->full_name,
            ]);
            
            $individualRole->permissions()->sync($permissionIds);
            $this->record->assignRole($individualRole);
        }
    }
}