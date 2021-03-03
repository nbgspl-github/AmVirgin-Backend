<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatsCard extends Component
{
    public $title, $value, $icon, $column;

    /**
     * Create a new component instance.
     *
     * @param string $title
     * @param string $value
     * @param string $icon
     * @param string $column
     */
    public function __construct (string $title, string $value, string $icon, string $column = '3')
    {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->column = $column;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render ()
    {
        return view('components.stats-card');
    }
}
