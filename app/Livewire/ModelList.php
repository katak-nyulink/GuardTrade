<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

abstract class ModelList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected function getModel(): string
    {
        throw new \Exception('You must implement getModel() in your list component');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $modelClass = $this->getModel();
        $query = $modelClass::query();

        if ($this->search) {
            $query->where(function ($q) {
                // Implement search logic in child class
            });
        }

        $items = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.model-list', [
            'items' => $items,
        ]);
    }
}
