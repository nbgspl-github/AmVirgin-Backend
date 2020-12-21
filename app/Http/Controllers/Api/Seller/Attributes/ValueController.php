<?php

namespace App\Http\Controllers\Api\Seller\Attributes;

use App\Http\Controllers\Api\ApiController;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ValueController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function show (int $attributeId)
	{
		$response = responseApp();
		try {
			$attribute = Attribute::findOrFail($attributeId);
			$sellerInterfaceType = $attribute->sellerInterfaceType();
			$hasValues = $attribute->predefined();
			if ($hasValues) {
				$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message('This attribute does not have a default value or set of values. Your should instead provide a value yourself.');
			} else {
				$values = $attribute->values;
				$values->transform(function (AttributeValue $attribute) {
					return [
						'key' => $attribute->id(),
						'value' => $attribute->value(),
					];
				});
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message(function () use ($values) {
					return sprintf('Listing %d values for the attribute.', $values->count());
				})->setValue('data', $values);
			}
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find attribute for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth('seller-api');
	}
}