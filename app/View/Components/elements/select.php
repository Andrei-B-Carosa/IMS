<?php

namespace App\View\Components\elements;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class select extends Component
{
    public $id;

    public $label;
    public $class;
    public $name;
    public $disabled;
    public $options;
    public $selected;

    // public $attributes;

    public function __construct($id,$label,$class,$name, $options = [],$selected,$disabled)
    {
        $this->id = $id;

        $this->label = $label;
        $this->class = $class;
        $this->name = $name;
        $this->options = $options;
        $this->selected = $selected;
        $this->disabled = $disabled;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.elements.select');
    }
}
