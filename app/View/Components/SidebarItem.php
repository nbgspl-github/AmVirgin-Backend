<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarItem extends Component
{
	public $url, $icon, $title, $pattern;

	/**
	 * Create a new component instance.
	 *
	 * @param string $url
	 * @param string $icon
	 * @param string $title
	 * @param string $pattern
	 */
	public function __construct (string $url, string $icon, string $title, string $pattern = '')
	{
		$this->url = $url;
		$this->icon = $icon;
		$this->title = $title;
		$this->pattern = $pattern;
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