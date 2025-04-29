<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TreeTable extends Component
{
    public $items;
    public $headers;

    public function __construct($items, $headers = [])
    {
        $this->items = $items;
        $this->headers = $headers;
    }

    public function render()
    {
        return view('components.tree-table');
    }
}
