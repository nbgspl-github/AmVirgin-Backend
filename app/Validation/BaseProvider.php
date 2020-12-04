<?php

namespace App\Validation;

abstract class BaseProvider implements RuleContext{
	public function index(): array{
		return [];
	}

	public function store(): array{
		return [];
	}

	public function edit(): array{
		return [];
	}

	public function delete(): array{
		return [];
	}

	public function update(): array{
		return [];
	}

	public function show(): array{
		return [];
	}
}