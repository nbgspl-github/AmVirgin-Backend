<?php

namespace App\Queries;

abstract class BaseQuery{
	protected abstract function model(): string;
}