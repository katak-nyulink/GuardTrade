<?php

namespace App\Livewire\Table;

use Livewire\Component;

class NestedTable extends Component
{
    public $columns;
    public $items;

    public function mount($columns = [], $items = [])
    {
        $this->columns = $columns;
        $this->items = $items;
    }

    public function render()
    {
        return view('livewire.table.nested-table');
    }
}
