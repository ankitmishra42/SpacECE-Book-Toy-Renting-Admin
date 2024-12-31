<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $type;

    public $name;

    public $placeholder;

    public $value;
    public $required;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $name, $placeholder = null, $value = null, $required = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input');
    }
}
