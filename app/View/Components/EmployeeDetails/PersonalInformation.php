<?php

namespace App\View\Components\EmployeeDetails;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PersonalInformation extends Component
{
    /**
     * Create a new component instance.
     */
    public $employee;
    public $isRegisterEmployee;

    public function __construct($employee,$isRegisterEmployee)
    {
        $this->employee=$employee;
        $this->isRegisterEmployee=$isRegisterEmployee;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.employee-details.personal-information');
    }
}
