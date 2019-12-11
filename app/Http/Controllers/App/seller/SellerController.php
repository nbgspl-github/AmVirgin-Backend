<?php

namespace App\Http\Controllers\app\Seller;

use App\Category;
use App\Http\Controllers\Base\AppController;
use App\Http\Controllers\Base\ResourceController;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Exception;

class SellerController extends ResourceController{

	public function categories(){
		$categories = Category::all();
		try {
			$categories->transform(function (Category $category){
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
		} catch (Exception $exception){
			return response()->json(['message' => 'Technical Error'], 400);
		}
	}

	public function add_product(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'customAttributes' 		=> 'required',
			//'attributes' 		=> 'required',
			'original_price' 	=> 'required',
			'category_id' 		=> 'required',
			'offer_price' 		=> 'required',
			'tax' 				=> 'required',
			'rating' 			=> 'required',
			'user_id' 			=> 'required',
			'short_description' => 'required',
			'long_description' 	=> 'required',
			'sku' 				=> 'required',
			'product_identifier'=> 'required',
			'attributes' 		=> 'required',
			'custom_attributes' => 'required'
		]);
		$cat_id = $request->input('category_id');
		if ($validator->fails()){
			return response()->json(['response' => $validator->errors()], 401);
		}
		$customAttributes 	= $request->input('customAttributes');
		$attributes 		= $request->input('attributes');
		
		//Custom Attributes Name Saving to database
		foreach($customAttributes as $key=>$value){
			$exist = DB::table('product_attributes')->where('attribute_name',$key)->get();
			if(count($exist) == 0){
				DB::table('product_attributes')->insert(
					['cat_id' =>$cat_id,'attribute_name' =>$key,'status'=>1]
				);
			}
		}

		$data = [
			'name' 				=> $request->input('name'),
			'original_price' 	=> $request->input('original_price'),
			'category_id' 		=> $request->input('category_id'),
			'user_id' 			=> $request->input('user_id'),
			'offer_price' 		=> $request->input('offer_price'),
			'tax' 				=> $request->input('tax'),
			'rating' 			=> $request->input('rating'),
			'short_description' => $request->input('short_description'),
			'long_description' 	=> $request->input('long_description'),
			'sku' 				=> $request->input('sku'),
			'product_identifier' => $request->input('product_identifier'),
			'active' 			=> $request->input('active'),
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
			'name' 				=> $request->input('name'),
			'original_price' 	=> $request->input('original_price'),
			'category_id' 		=> $request->input('category_id'),
			'user_id' 			=> $request->input('user_id'),
			'offer_price' 		=> $request->input('offer_price'),
			'tax' 				=> $request->input('tax'),
			'attribute_name' 	=> $request->input('attribute_name'),
			'attribute_val' 	=> $request->input('attribute_val'),
			'rating' 			=> $request->input('rating'),
			'short_description' => $request->input('short_description'),
			'long_description' 	=> $request->input('long_description'),
			'sku' 				=> $request->input('sku'),
			'product_identifier'=> $request->input('product_identifier'),
			'active' 			=> $request->input('active'),
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
		}
		else {
			return response()->json(['message' => 'Technical Error'], 400);
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function provider(){
		// TODO: Implement provider() method.
	}

	protected function resource(){
		// TODO: Implement resource() method.
	}

	protected function collection(){
		// TODO: Implement collection() method.
	}
}
