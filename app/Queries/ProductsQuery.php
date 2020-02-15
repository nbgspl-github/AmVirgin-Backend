<?php

namespace App\Queries;

use App\Models\Product;

class ProductsQuery extends QueryProvider{
	protected $model = Product::class;
}