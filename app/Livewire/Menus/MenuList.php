<?php

namespace App\Livewire\Menus;

use App\Models\Menu;
use Livewire\Component;
use Livewire\WithPagination;

class MenuList extends Component
{
    use WithPagination;

    // public $menus;
    public $search = '';
    public $perPage = 20;
    public $sortBy = 'title';
    public $sortDirection = 'asc';
    public $page = 1;
    public $selectedMenu = null;
    public $selectedPermission = null;
    public $selectedGroup = null;

    public array $columns = [
        'title' => ['label' => 'Title', 'sortable' => true, 'width' => '28%'],
        'route' => ['label' => 'Route', 'sortable' => true, 'width' => '22%'],
        // 'icon' => ['label' => 'Icon', 'sortable' => true, 'width' => '10%'],
        // 'type' => ['label' => 'Type', 'sortable' => false],
        // 'group' => ['label' => 'Group', 'sortable' => false],
        'permission_name' => ['label' => 'Permission', 'sortable' => true, 'width' => 'auto'],
        'action' => ['label' => 'Actions', 'sortable' => false, 'width' => '16%'],
    ];

    public function mount()
    {
        // Remove loadMenus call
    }

    public function delete(Menu $menu)
    {
        $menu = \App\Models\Menu::findOrFail($menu);
        if ($menu) {
            $menu->delete();
            $this->loadMenus();
            session()->flash('message', 'Menu deleted successfully.');
        } else {
            session()->flash('error', 'Menu not found.');
        }
    }
    public function edit(Menu $menu)
    {
        $menu = \App\Models\Menu::findOrFail($menu);
        if ($menu) {
            $this->emit('editMenu', $menu);
        } else {
            session()->flash('error', 'Menu not found.');
        }
    }
    public function create()
    {
        $this->emit('createMenu');
    }

    private function getNestedMenus()
    {
        return Menu::query()
            ->whereNull('parent_id')
            ->with('children')
            ->when($this->search, function ($query) {
                return $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $menus = $this->getNestedMenus();

        return view('livewire.menus.menu-list', [
            'menus' => $menus
        ]);
    }
}
