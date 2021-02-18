<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatsCard extends Component
{
	public $title, $value, $icon;

	/**
	 * Create a new component instance.
	 *
	 * @param string $title
	 * @param string $value
	 * @param string $icon
	 */
	public function __construct (string $title, string $value, string $icon)
	{
		$this->title = $title;
		$this->value = $value;
		$this->icon = $icon;
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|string
	 */
	public function render ()
	{
		return view('components.stats-card');
	}
}