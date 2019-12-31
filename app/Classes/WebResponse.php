<?php

namespace App\Classes;

use App\Contracts\FluentConstructor;

class WebResponse implements FluentConstructor{

	private $route = 'back';

	private $message = null;

	private $flashData = [];

	private $type = 'error';

	private $view = null;

	private $parameter = null;

	private $payload = [];

	/**
	 * @inheritDoc
	 */
	public static function instance(){
		return new self();
	}

	public function back(){
		$this->route = 'back';
		return $this;
	}

	public function route(string $route, string $parameter = null){
		$this->route = $route;
		$this->parameter = $parameter;
		return $this;
	}

	public function view(string $view){
		$this->view = $view;
		return $this;
	}

	public function payload(string $key, string $value){
		$this->payload[$key] = $value;
		return $this;
	}

	public function error(string $message){
		$this->type = 'error';
		$this->message = $message;
		return $this;
	}

	public function success(string $message){
		$this->type = 'success';
		$this->message = $message;
		return $this;
	}

	public function info(string $message){
		$this->type = 'info';
		$this->message = $message;
		return $this;
	}

	public function warning(string $message){
		$this->type = 'warning';
		$this->message = $message;
		return $this;
	}

	public function data(array $data){
		$this->flashData = $data;
		return $this;
	}

	public function send(){
		if ($this->message != null) {
			switch ($this->type) {
				case 'error':
					notify()->error($this->message);
					break;

				case 'success':
					notify()->success($this->message);
					break;

				case 'info':
					notify()->info($this->message);
					break;

				case 'warning':
					notify()->warning($this->message);
					break;
			}
		}
		if ($this->view != null) {

		}
		else {
			if ($this->route == 'back') {
				if (count($this->flashData) > 0)
					return redirect()->back()->withInput($this->flashData);
				else
					return redirect()->back();
			}
			else {
				if (count($this->flashData) > 0) {
					if ($this->parameter != null) {
						return redirect(route($this->route, $this->parameter))->withInput($this->flashData);
					}
					else {
						return redirect(route($this->route))->withInput($this->flashData);
					}
				}
				else {
					if ($this->parameter != null) {
						return redirect(route($this->route, $this->parameter));
					}
					else {
						return redirect(route($this->route));
					}
				}
			}
		}
	}
}