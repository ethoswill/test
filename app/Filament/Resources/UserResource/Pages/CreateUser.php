<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Permission;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove permissions from the main data array as we'll handle them separately
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Handle permissions after user creation
        $permissions = $this->form->getState()['permissions'] ?? [];
        
        if (!empty($permissions)) {
            $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id');
            
            // Create a temporary role for individual permissions
            $individualRole = \App\Models\Role::firstOrCreate([
                'slug' => 'individual-permissions-' . $this->record->id,
                'name' => 'Individual Permissions - ' . $this->record->full_name,
                'description' => 'Individual permissions for ' . $this->record->full_name,
            ]);
            
            $individualRole->permissions()->sync($permissionIds);
            $this->record->assignRole($individualRole);
        }
    }
}