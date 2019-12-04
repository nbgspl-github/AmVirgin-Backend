<?php

namespace App\Http\Controllers\app\Seller;

use App\Category;
use App\Http\Controllers\Base\AppController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Exception;

class SellerController extends AppController
{

	public function categories()
	{
		$categories = Category::all();
		try {
			$categories->transform(function (Category $category) {
				return [
					'id' => $category->getId(),
					'name' => $category->getName(),
					'description' => $category->getDescription(),
				];
			});
			return response()->json($categories, 200);
		} catch (Exception $exception) {
			return response()->json(['message' => 'Technical Error'], 400);
		}
	}

	public function products()
	{
		$result = DB::table('products')->get();
		//print_r($result);die;
		try {
			return response()->json($result, 200);
		} catch (Exception $exception) {
			return response()->json(['message' => 'Technical Error'], 400);
		}
	}

	public function add_product(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'product_type' => 'required',
			'category_id' => 'required',
			'country_id' => 'required',
			'state_id' => 'required',
			'city_id' => 'required',
			'address' => 'required',
			'zip_code' => 'required',
			'user_id' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['response' => $validator->errors()], 401);
		}
		$data = [
			'name' => $request->input('name'),
			'product_type' => $request->input('product_type'),
			'category_id' => $request->input('category_id'),
			'country_id' => $request->input('country_id'),
			'state_id' => $request->input('state_id'),
			'city_id' => $request->input('city_id'),
			'address' => $request->input('address'),
			'zip_code' => $request->input('zip_code'),
			'user_id' => $request->input('user_id'),
		];
		$result = DB::table('products')->insert($data);
		//print_r($result);die;
		if ($result) {
			return response()->json(['data' => $data, 'message' => 'Product added Successfully'], 200);
		} else {
			return response()->json(['message' => 'Technical Error'], 400);
		}
	}

	public function product_edit(Request $request)
	{
		$validator  = Validator::make($request->all(), [
			'product_id' => 'required|exists:products,id'
		]);
		if ($validator->fails()) {
			return response()->json(['response' => $validator->errors()], 401);
		}
		$id = $request->input('product_id');
		$data = [
			'name' 		=> $request->input('name'),
			'address' 	=> $request->input('address'),
			'zip_code' 	=> $request->input('zip_code')
		];
		$update = DB::table('products')->where('id', $id)->update($data);
		if ($update) {
			return response()->json(['message' => "Product Updated Successfully", 'data' => $data], 200);
		} else {
			return response()->json(['message' => 'Technical Error'], 400);
		}
	}

	public function product_delete(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'product_id' => 'required|exists:products,id'
		]);
		if ($validator->fails()) {
			return response()->json(['response' => $validator->errors()], 401);
		}
		$id = $request->input('product_id');
		$delete = DB::table('products')->where('id', $id)->delete();
		if ($delete) {
			return response()->json(['data' => 'Product Deleted Successfully'], 200);
		} else {
			return response()->json(['message' => 'Technical Error'], 400);
		}
	}
}
