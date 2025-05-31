<?php

namespace App\View\Components\elements;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class input extends Component
{
    public $id;

    public $label;
    public $class;
    public $name;
    public $disabled;
    public $type;
    public $required;

    public function __construct($id,$required,$label,$class,$name,$disabled,$type='text')
    {
        $this->id = $id;

        $this->label = $label;
        $this->type = $type;
        $this->class = $class;
        $this->name = $name;
        $this->disabled = $disabled;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.elements.input');
    }
}
