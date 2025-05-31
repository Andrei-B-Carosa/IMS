<?php

namespace App\View\Components\elements;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class modal extends Component
{
     /**
     * Create a new component instance.
     */
    public $id;
    public $title;
    public $action;

    public function __construct($id, $title,$action,)
    {
        $this->id = $id;
        $this->title = $title;
        $this->action = $action;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.elements.modal');
    }
}
