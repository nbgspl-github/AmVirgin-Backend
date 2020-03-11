<?php

namespace App\Http\Controllers\App\Seller\Attributes;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class AttributeValuesController extends ExtendedResourceController {
	public function __construct() {
		parent::__construct();
	}

	public function show(int $attributeId) {
		$response = responseApp();
		try {
			$attribute = Attribute::with('values')->where('id', $attributeId)->firstOrFail();
			$values = $attribute->values;
			$values->transform(function (AttributeValue $attribute) {
				return [
					'id' => $attribute->getKey(),
					'value' => $attribute->value,
				];
			});
			$response->status(HttpOkay)->message(function () use ($values) {
				return sprintf('Found %d values for the attribute.', $values->count());
			})->setValue('data', $values);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find attribute for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard() {
		return auth('seller-api');
	}
}