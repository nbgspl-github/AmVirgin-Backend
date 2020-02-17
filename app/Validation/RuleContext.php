<?php

namespace App\Validation;

interface RuleContext{
	public function index(): array;

	public function edit(): array;

	public function store(): array;

	public function update(): array;

	public function show(): array;

	public function delete(): array;
}