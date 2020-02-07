<?php

namespace App\Directives;

abstract class BaseDirective{
	public abstract function keyword(): string;

	public abstract function handle($argument): string;
}