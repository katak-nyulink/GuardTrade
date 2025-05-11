<?php

namespace App\Livewire\Users;

use App\Livewire\ModelList;
use App\Models\User;

class UserList extends ModelList
{
    protected function getModel(): string
    {
        return User::class;
    }

    public function search($query)
    {
        return $query->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%');
    }
}
