<?php

namespace App\Directives;

class RequiredLabel extends BaseDirective{
	public function keyword(): string{
		return 'required';
	}

	public function handle($argument): string{
		return "$argument<span class=\"text-muted\">*</span>";
	}
}