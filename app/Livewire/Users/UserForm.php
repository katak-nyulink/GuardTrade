<?php

namespace App\Livewire\Users;

use App\Livewire\ModelForm;
use App\Models\User;

class UserForm extends ModelForm
{
    protected function getModelClass(): string
    {
        return User::class;
    }

    public function getRules(): array
    {
        return [
            'model.name' => 'required|string|max:255',
            'model.email' => 'required|email|unique:users,email,' . ($this->model->id ?? ''),
            'model.password' => $this->isEdit ? 'nullable|min:8' : 'required|min:8',
        ];
    }

    protected function getRedirectPath(): string
    {
        return route('users.index');
    }
}
