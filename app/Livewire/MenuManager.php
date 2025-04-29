<?php

namespace App\Livewire;

use App\Models\Menu;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Developermithu\Tallcraftui\Traits\WithTcTable;

class MenuManager extends Component
{
    use WithTcTable;

    public $menus;
    public $menus2;

    public $title, $route, $icon, $type = 'link', $group, $permission_name, $parent_id, $menu_id;
    public array $columns = [
        'title' => ['label' => 'Title', 'sortable' => true],
        'icon' => ['label' => 'Icon', 'sortable' => true],
        'type' => ['label' => 'Type', 'sortable' => false],
        'group' => ['label' => 'Group', 'sortable' => false],
        'permission_name' => ['label' => 'Permission', 'sortable' => true],
        'action' => ['label' => 'Actions', 'sortable' => false],
    ];

    protected $rules = [
        'title' => 'required|string',
        'type' => 'required|in:link,dropdown',
        'route' => 'nullable|string',
        'icon' => 'nullable|string',
        'group' => 'nullable|string',
        'permission_name' => 'nullable|string',
        'parent_id' => 'nullable|exists:menus,id',
    ];

    public function mount()
    {
        $this->loadMenus();
    }

    public function loadMenus()
    {
        $this->menus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();
        $this->menus2 = Menu::query()
            // ->with('children')->whereNull('parent_id')
            ->when($this->tcSearch, function ($query) {
                $query->where('title', 'LIKE', '%' . $this->tcSearch . '%');
            })
            ->tap(fn($query) => $this->tcApplySorting($query))
            ->paginate($this->tcPerPage);
    }

    public function save()
    {
        Gate::authorize('manage menus');

        $this->validate();

        Menu::updateOrCreate(['id' => $this->menu_id], [
            'title' => $this->title,
            'route' => $this->route,
            'icon' => $this->icon,
            'type' => $this->type,
            'group' => $this->group,
            'permission_name' => $this->permission_name,
            'parent_id' => $this->parent_id,
        ]);

        $this->resetInput();
        $this->loadMenus();
        session()->flash('success', 'Menu saved!');
    }

    public function edit($id)
    {
        Gate::authorize('manage menus');

        $menu = Menu::findOrFail($id);
        $this->menu_id = $menu->id;
        $this->title = $menu->title;
        $this->route = $menu->route;
        $this->icon = $menu->icon;
        $this->type = $menu->type;
        $this->group = $menu->group;
        $this->permission_name = $menu->permission_name;
        $this->parent_id = $menu->parent_id;
    }

    public function delete($id)
    {
        Gate::authorize('manage menus');

        Menu::findOrFail($id)->delete();
        $this->loadMenus();
    }

    public function reorder($list)
    {
        Gate::authorize('manage menus');

        foreach ($list as $index => $item) {
            Menu::find($item['id'])->update([
                'order' => $index,
                'parent_id' => $item['parent_id'] ?? null,
            ]);
        }

        $this->loadMenus();
    }

    public function resetInput()
    {
        $this->reset(['title', 'route', 'icon', 'type', 'group', 'permission_name', 'parent_id', 'menu_id']);
    }

    public function formatColumnValue($key, $menu)
    {
        return match ($key) {
            'type' => ucfirst($menu->type),
            'action' => null,
            default => $menu->$key,
        };
    }

    public function render()
    {
        return view('livewire.menu-manager');
    }
}
