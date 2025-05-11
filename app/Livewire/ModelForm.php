<?php

namespace App\Livewire;

use Livewire\Component;

abstract class ModelForm extends Component
{
    public $model;
    public $isEdit = false;

    public function getRules()
    {
        return [];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->isEdit = true;
            $this->model = $this->getModelClass()::findOrFail($id);
        } else {
            $this->model = new ($this->getModelClass())();
        }
    }

    abstract protected function getModelClass(): string;

    public function save()
    {
        $this->validate();
        $this->model->save();

        session()->flash('message', 'Saved successfully.');
        return redirect()->to($this->getRedirectPath());
    }

    abstract protected function getRedirectPath(): string;
}
