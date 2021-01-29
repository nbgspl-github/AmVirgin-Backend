<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarItem extends Component
{
	public $url, $icon, $title;

	/**
	 * Create a new component instance.
	 *
	 * @param string $url
	 * @param string $icon
	 * @param string $title
	 */
	public function __construct (string $url, string $icon, string $title)
	{
		$this->url = $url;
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
		return view('components.sidebar-item');
	}
}