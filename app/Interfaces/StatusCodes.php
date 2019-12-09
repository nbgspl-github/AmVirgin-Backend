<?php

namespace App\Interfaces;

interface StatusCodes {
	const Okay = 200;

	const Created = 201;

	const NoContent = 203;

	const InvalidRequestFormat = 400;

	const ResourceNotFound = 404;

	const ServerError = 500;

	const ResourceAlreadyExists = 409;
}