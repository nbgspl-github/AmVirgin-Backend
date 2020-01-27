<?php

namespace App\Http\Controllers\App\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Base\ResourceController;
use App\Interfaces\StatusCodes;
use App\Models\Product;
use App\Models\ProductImage;
use App\Traits\FluentResponse;
use Illuminate\Support\Facades\Validator;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Constants\OfferTypes;
use App\Constants\ProductStatus;
use App\Interfaces\Directories;
use Illuminate\Support\Facades\Storage;
use Throwable;
class ProductsController extends ResourceController{
use ValidatesRequest;
use FluentResponse;

	public function __construct(){
		parent::__construct();
		$this->ruleSet->load('rules.seller.product');

	}

	public function index(){
	$sellerId = $this->user()->getKey();
		$Getproducts = Product::where('sellerId', '=', $sellerId)->get();
		//$GetproductsImages = ProductImage::find($productid);
		//multipal image upload
	  if ($Getproducts == null) {
			 $response=$this->error()->message('Product not found !');
	  }else {
			foreach ($Getproducts as $productdata) {
				$image=ProductImage::where('productId',$productdata->id)->select('path')->get();
				$productData[]=array(
				'image'=>$image,
				'name' => $productdata->name,
				'slug' => $productdata->slug,
				'categoryId' => $productdata->categoryId,
				'sellerId' => $productdata->sellerId,
				'productType' => $productdata->productType,
				'productMode' => $productdata->productMode,
				'listingType' => $productdata->listingType,
				'originalPrice' => $productdata->originalPrice,
				'offerValue' => $productdata->offerValue,
				'offerType' => $productdata->offerType,
				'currency' => $productdata->currency,
				'taxRate' => $productdata->taxRate,
				'countryId' => $productdata->countryId,
				'stateId' => $productdata->stateId,
				'cityId' => $productdata->cityId,
				'zipCode' => $productdata->zipCode,
				'address' => $productdata->address,
				'status' => $productdata->status,
				'promoted' => $productdata->promoted,
				'promotionStart' => $productdata->promotionStart,
				'promotionEnd' => $productdata->promotionEnd,
				'visibility' => $productdata->visibility,
				'stock' => $productdata->stock,
				'shippingCostType' => $productdata->shippingCostType,
				'shippingCost' => $productdata->shippingCost,
				'soldOut' => $productdata->soldOut,
				'draft' => $productdata->draft,
				'shortDescription' => $productdata->shortDescription,
				'longDescription' => $productdata->longDescription,
				'sku' => $productdata->sku,
			   );
			}

			$response = $this->success()->status(HttpOkay)->setValue('data', $productData)->message(__('All products show successfully'));
	}
			return $response->send();
	}
	


	public function edit($id = null){
		$product = Product::where('id', '=', $id)->get();
		//multipal image upload
		if ($product == null) {
		$response=$this->error()->message('product not found !');
		}else {
		$success['images']=array();
		$productimage=ProductImage::where('productId', '=', $id)->get();

		foreach ($productimage as $key => $value) {
		  $success['images'][]=$value['path'];
		}
		$success['products-data'] = $product;
		$response = $this->success()->status(HttpOkay)->setValue('data', $success)->message(__('product details successfully'));
		}
		return $response->send();
	}


	public function store(Request $request){
		$sellerId = $this->user()->getKey();
		$response =$this->response();
		$images = $request->file('files');
		//Product::getPdo()->lastInsertId();
		try {
			$this->requestValid($request, $this->rules('store'));
			$product = Product::create([
			'name' => $request->productName,
			'slug' => Str::slug($request->productName),
			'categoryId' => $request->categoryId,
			'sellerId' => $sellerId,
			'productType' => $request->productType,
			'productMode' => $request->productMode,
			'listingType' => $request->listingType,
			'originalPrice' => $request->originalPrice,
			'offerValue' => $request->offerValue,
			'offerType' => $request->offerType,
			'currency' => $request->currency,
			'taxRate' => $request->taxRate,
			'countryId' => $request->countryId,
			'stateId' => $request->stateId,
			'cityId' => $request->cityId,
			'zipCode' => $request->zipCode,
			'address' => $request->address,
			'status' => $request->status,
			'promoted' => $request->promoted,
			'promotionStart' => date('Y-m-d H:i:s', strtotime($request->promotionStart)),
			'promotionEnd' => date('Y-m-d H:i:s', strtotime($request->promotionEnd)),
			'visibility' => $request->visibility,
			'stock' => $request->stock,
			'shippingCostType' => $request->shippingCostType,
			'shippingCost' => $request->shippingCost,
			'soldOut' => $this->isSoldOut($request),
			'draft' => $request->draft,
			'shortDescription' => $request->shortDescription,
			'longDescription' => $request->longDescription,
			'sku' => $request->sku,
			]);

		$productId=$product->getKey();
		$storeImage=[];
		//multipal image upload
			if (count($request->file('files'))>0 && $productId !='') 
			{         
					foreach ($request->file('files') as $imgdata) {
						$productimage=new ProductImage();
						$productimage->productId=$productId;
						$productimage->path=Storage::disk('secured')->putFile(Directories::ProductImage,$imgdata,'private');
						$productimage->tag='Product-Image';
						$productimage->save();
					}
				}
			   
			$response = $this->success()->status(HttpCreated)->setValue('data', $product)->message(__('successfully add products'));
		}
		catch (ValidationException $exception) {
			$response = $this->failed()->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function show($id){
		$sellerId = $this->user()->getKey();
		$response = null;
		try {
			$product = $this->retrieveChild(function ($query) use ($sellerId, $id){
				$query->where('sellerId', $sellerId)->where('id', $id);
			});
			$response = $this->success()->setValue('data', $product);
		}
		catch (ModelNotFoundException $exception) {
			$response = $this->failed()->status(HttpResourceNotFound);
		}
		catch (Exception $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

 public function update(Request $request, $productId = null){
	 	$rules=$this->rules('update');

	   $validator = Validator::make($request->all(), [
		 'categoryId' => ['bail', 'required', 'exists:categories,id'],
 		 'productType' => ['bail', 'required', 'string', 'min:1', 'max:256'],
 		 'productMode' => ['bail', 'required', 'string', 'min:1', 'max:256'],
 		 'listingType' => ['bail', 'required', 'string', 'min:1', 'max:256'],
 		 'originalPrice' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
 		 'offerType' => ['bail', 'required', Rule::in([OfferTypes::FlatRate, OfferTypes::Percentage])], /*Since we only have two offer types for now, it's 0 and 1, later on we'll add as required.*/
 		 'offerValue' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
 		 'currency' => ['bail', 'nullable', 'string', 'min:2', 'max:5', 'exists:currencies,code'],
 		 'taxRate' => ['bail', 'required', 'numeric', 'min:0.00', 'max:99.99'],
 		 'countryId' => ['bail', 'required', 'exists:countries,id'],
 		 'stateId' => ['bail', 'required', 'numeric', 'min:1', 'max:9999999'],
 		 'cityId' => ['bail', 'required', 'numeric', 'min:1', RuleMaxInt],
 		 'zipCode' => ['bail', 'required', 'min:1', RuleMaxInt],
 		 'address' => ['bail', 'required', 'string', 'min:2', 'max:500'],
 		 'status' => ['bail', 'nullable', Rule::in([ProductStatus::DifferentStatus, ProductStatus::SomeOtherStatus, ProductStatus::SomeStatus])],
 		 'promoted' => ['bail', 'boolean'],
 		 'promotionStart' => ['bail', 'required_with:promoted', 'date'],
 		 'promotionEnd' => ['bail', 'required_with:promoted', 'date', 'after:promotionStart'],
 		 'visibility' => ['bail', 'boolean'],
 		 'stock' => ['bail', 'required', 'numeric', 'min:0', RuleMaxStock],
 		 'shippingCostType' => ['bail', 'required', Rule::in(['free', 'chargeable'])],
 		 'shippingCost' => ['bail', 'required_if:shippingCostType,chargeable'],
 		 'draft' => ['bail', 'boolean'],
 		 'shortDescription' => ['bail', 'required', 'string', 'min:1', 'max:1000'],
 		 'longDescription' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
 		 'sku' => ['bail', 'required', 'string', 'min:1', 'max:256'],
		]);
		$data = array(
		//'name'=>$request->name,	
		'categoryId' => $request->categoryId,
		'productType' => $request->productType,
		'productMode' => $request->productMode,
		'listingType' => $request->listingType,
		'originalPrice' => $request->originalPrice,
		'offerValue' => $request->offerValue,
		'offerType' => $request->offerType,
		'currency' => $request->currency,
		'taxRate' => $request->taxRate,
		'countryId' => $request->countryId,
		'stateId' => $request->stateId,
		'cityId' => $request->cityId,
		'zipCode' => $request->zipCode,
		'address' => $request->address,
		'status' => $request->status,
		'promoted' => $request->promoted,
		'promotionStart' => date('Y-m-d H:i:s', strtotime($request->promotionStart)),
		'promotionEnd' => date('Y-m-d H:i:s', strtotime($request->promotionEnd)),
		'visibility' => $request->visibility,
		'stock' => $request->stock,
		'shippingCostType' => $request->shippingCostType,
		'shippingCost' => $request->shippingCost,
		'soldOut' => $this->isSoldOut($request),
		'draft' => $request->draft,
		'shortDescription' => $request->shortDescription,
		'longDescription' => $request->longDescription,
		'sku' => $request->sku,
	);
	//updated image
	$Newimages = $request->file('files');
	if(! empty($Newimages)) {
		//delete old image
		/*$image_path = "/images/filename.ext";  // Value is not URL but directory file path
		if(File::exists($image_path)) {
		  File::delete($image_path);
		}*/
			$GetImages = ProductImage::where('productId', $productId)->get();
			if($GetImages !=null){
               $GetImages->each(function(ProductImage $item){
					 $item->delete();
				 });
		   }

		   if (count($request->file('files'))>0) 
		   {      foreach ($request->file('files') as $imgdata) {
					   $productimage=new ProductImage();
					   $productimage->productId=$productId;
					   $productimage->path=Storage::disk('secured')->putFile(Directories::ProductImage,$imgdata,'private');
					   $productimage->tag='Product-Image';
					   $productimage->save();
				   }
			   }
		}


		if($validator->fails()){
			  // $response = $this->error()->message($validator->getMessage());
			  $response = $this->failed()->status(HttpResourceNotFound);
		}else{
			$products = Product::retrieve($productId);
			if ($products == null){
				$response = $this->failed()->status(HttpResourceNotFound);
			}else {
				$update = Product::find($productId);
				Product::where('id', $productId)->update($data);
				$response = $this->success()->status(HttpOkay)->message(__('product updated successfully'));
			}

		}

	return $response->send();

 }

	public function patch(Request $request, $id){

	}

	public function delete($id){
			$response = null;
	  try{  
			$product = Product::find($id);
			$productimage = ProductImage::where('productId',$id)->select('id')->get();
			
			if ($product == null) {
				$response = $this->failed()->status(HttpResourceNotFound);
			}
		  $product->delete();
		  ProductImage::destroy($productimage->toArray());
	      $response = $this->success()->status(HttpOkay)->message(__('product deleted successfully'));
		}

			catch (ModelNotFoundException $exception) {
				$response = $this->failed()->status(HttpResourceNotFound);
			}
			catch (Exception $exception) {
				$response = $this->error()->message($exception->getMessage());
			}
			finally {
				return $response->send();
			}
	}

	protected function parentProvider(){
		return null;
	}

	protected function provider(){
		return Product::class;
	}

	protected function resourceConverter(Model $model){

	}

	protected function collectionConverter(Collection $collection){

	}

	protected function guard(){
		return Auth::guard('seller-api');
	}

	private function isSoldOut(Request $request){
		return $request->stock < 1;
	}
}
