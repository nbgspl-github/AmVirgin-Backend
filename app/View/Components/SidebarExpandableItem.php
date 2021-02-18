<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarExpandableItem extends Component
{
	public $url, $icon, $title;

	/**
	 * Create a new component instance.
	 *
	 * @param string $icon
	 * @param string $title
	 */
	public function __construct (string $icon, string $title)
	{
		$this->icon = $icon;
		$this->title = $title;
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|string
	 */
	public function render ()
	{
		return view('components.sidebar-expandable-item');
	}
}