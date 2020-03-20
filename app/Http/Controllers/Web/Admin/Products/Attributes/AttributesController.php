<?php

namespace App\Http\Controllers\Web\Admin\Products\Attributes;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use Illuminate\View\View;

class AttributesController extends BaseController{
	public function __construct(){
		parent::__construct();
	}

	public function index(){

	}

	public function create(){
		$categories = Category::all();
		return \view('admin.attributes.create')->with('categories', $categories);
	}
}