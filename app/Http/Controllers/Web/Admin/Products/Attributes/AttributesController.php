<?php

namespace App\Http\Controllers\Web\Admin\Products\Attributes;

use App\Http\Controllers\BaseController;
use App\Models\Attribute;
use App\Models\Category;

class AttributesController extends BaseController{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$attributes = Attribute::all();
	}

	public function create(){
		$categories = Category::all();
		return \view('admin.attributes.create')->with('categories', $categories);
	}
}