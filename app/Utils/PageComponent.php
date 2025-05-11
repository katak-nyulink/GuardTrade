<?php

namespace App\Utils;

use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\View\View;
use ReflectionClass;
use InvalidArgumentException;

class PageComponent extends Component
{
    protected string $layout = 'components.layouts.app'; // Default layout
    protected string $title = 'Page Title'; // Default title
    protected string $description = 'Page Description'; // Default description
    protected string $pathName; // Default page view

    public function render(): View
    {
        $viewName = $this->resolveViewName();

        if (!view()->exists($viewName)) {
            throw new InvalidArgumentException("View [{$viewName}] not found.");
        }

        return view($viewName)
            ->layout($this->layout, $this->getViewAttributes());
    }

    protected function resolveViewName(): string
    {
        $className = str_replace(
            ['_', '\\'],
            ['-', '.'],
            $this->getViewName()
        );

        if (empty($this->pathName)) {
            return 'livewire.' . $className;
        } else {
            return 'livewire.' . $this->pathName . '.' . $className;
        }
    }

    protected function getViewAttributes(): array
    {
        $reflection = new ReflectionClass($this);
        $attributes = get_object_vars($this);

        // Remove parent class properties
        $parentProps = $reflection->getParentClass()->getProperties();
        foreach ($parentProps as $prop) {
            unset($attributes[$prop->getName()]);
        }

        return array_filter($attributes, fn($value) => !is_null($value));
    }

    private function getViewName(): string
    {
        return Str::snake(
            (new ReflectionClass($this))->getShortName()
        );
    }
}
