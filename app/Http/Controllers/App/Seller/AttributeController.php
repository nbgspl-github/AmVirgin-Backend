<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Base\AppController;
use App\Http\Controllers\Base\ResourceController;
use App\Http\Resources\Attributes\AttributeCollection;
use App\Http\Resources\Attributes\AttributeResource;
use App\Models\Attribute;

class AttributeController extends ResourceController{
	protected function provider(){
		return Attribute::class;
	}

	protected function resource(){
		return AttributeResource::class;
	}

	protected function collection(){
		return AttributeCollection::class;
	}
}