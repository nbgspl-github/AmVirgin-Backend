<?php
namespace App\Http\Controllers\App\Shop;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\StatusCodes;
use App\Models\Category;
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
use Throwable;
class ProductsController extends BaseController{
use ValidatesRequest;
use FluentResponse;
	public function __construct(){
		parent::__construct();
		//$this->ruleSet->load('rules.shop.product');
	}

	public function index(Request $request)
	{   
        $validator = Validator::make($request->all(), [ 
        'offset'    => 'required',
        'limit'     => 'required',
        ]);
        if ($validator->fails()) { 
        return response()->json(['response'=>$validator->errors()], 500);            
        }
        $offset = $request->input('offset');
        $limit = $request->input('limit');
        $sort = array("field"=>"id" , "key"=>"desc");
        if($request->has('sort'))
        {  
            if($request->sort == "Oldest")
            {
            $sort = array("field"=>"id" , "key"=>"asc");
            }
            if($request->sort == "Cheapest")
            {
            $sort = array("field"=>"originalPrice" , "key"=>"asc");
            }
            if($request->sort == "Highest")
            {
            $sort = array("field"=>"originalPrice" , "key"=>"desc");
            }
        }
        $Getproducts = Product::orderBy($sort['field'],$sort['key'])->offset($offset)->limit($limit)->get();
        if ($Getproducts == null) {
           $response=$this->error()->message('Product not found !');
        }else {
            foreach($Getproducts as $pdata){
            $productsimage= ProductImage::where('productId',$pdata['id'])->select('productId','path','tag')->get();
            $success[]=array(
                'id'=>$pdata['id'], 
                'name'=>$pdata['name'], 
                'slug'=>$pdata['slug'], 
                'categoryId'=>$pdata['categoryId'], 
                'sellerId'=>$pdata['sellerId'], 
                'productType'=>$pdata['productType'], 
                'productMode'=>$pdata['productMode'], 
                'listingType'=>$pdata['listingType'], 
                'originalPrice'=>$pdata['originalPrice'],
                'offerValue'=>$pdata['offerValue'], 
                'offerType'=>$pdata['offerType'],
                'currency'=>$pdata['currency'], 
                'taxRate'=>$pdata['taxRate'], 
                'countryId'=>$pdata['countryId'], 
                'stateId'=>$pdata['stateId'],
                'cityId'=>$pdata['cityId'],
                'zipCode'=>$pdata['zipCode'], 
                'address'=>$pdata['address'], 
                'status'=>$pdata['status'],
                'promoted'=>$pdata['promoted'],
                'promotionStart'=>$pdata['promotionStart'],
                'promotionEnd'=>$pdata['promotionEnd'],
                'visibility'=>$pdata['visibility'], 
                'rating'=>$pdata['rating'], 
                'hits'=>$pdata['hits'],
                'stock'=>$pdata['stock'], 
                'shippingCostType'=>$pdata['shippingCostType'], 
                'shippingCost'=>$pdata['shippingCost'], 
                'soldOut'=>$pdata['soldOut'], 
                'deleted'=>$pdata['deleted'], 
                'draft'=>$pdata['draft'],
                'shortDescription'=>$pdata['shortDescription'], 
                'longDescription'=>$pdata['longDescription'], 
                'sku'=>$pdata['sku'], 
                'trailer'=>$pdata['trailer'],
                'created_at'=>$pdata['created_at'],
                'images'=>$productsimage,    
            );    
            }
        $response = $this->success()->status(HttpCreated)->setValue('data', $success)->message(__('All products show successfully'));
        }
        return $response->send();
    }
    
    public function details($id=null){
        $Getproduct = Product::where('id',$id)->get();
        $Getproductsimages= ProductImage::where('productId',$id)->select('productId','path','tag')->get();
        $success['images']=$Getproductsimages;
        $success['details']=$Getproduct;
        $response = $this->success()->status(HttpCreated)->setValue('data', $success)->message(__('All products show successfully'));
        return $response->send();
    }
    
    public function categoryby(Request $request,$categoryId=null)
	{   
        $validator = Validator::make($request->all(), [ 
        'offset'    => 'required',
        'limit'     => 'required',
        ]);
        if ($validator->fails()) { 
        return response()->json(['response'=>$validator->errors()], 500);            
        }
        $offset = $request->input('offset');
        $limit = $request->input('limit');
        $sort = array("field"=>"id" , "key"=>"desc");
        if($request->has('sort'))
        {  
            if($request->sort == "Oldest")
            {
            $sort = array("field"=>"id" , "key"=>"asc");
            }
            if($request->sort == "Cheapest")
            {
            $sort = array("field"=>"originalPrice" , "key"=>"asc");
            }
            if($request->sort == "Highest")
            {
            $sort = array("field"=>"originalPrice" , "key"=>"desc");
            }
        }
        //if($categoryid)
        $Getproducts = Product::where('categoryId',$categoryId)->orderBy($sort['field'],$sort['key'])->offset($offset)->limit($limit)->get();

        if ($Getproducts == null) {
           $response=$this->error()->message('Product not found !');
        }else {
            foreach($Getproducts as $pdata){
            $productsimage= ProductImage::where('productId',$pdata['id'])->select('productId','path','tag')->get();
            $success[]=array(
                'id'=>$pdata['id'], 
                'name'=>$pdata['name'], 
                'slug'=>$pdata['slug'], 
                'categoryId'=>$pdata['categoryId'], 
                'sellerId'=>$pdata['sellerId'], 
                'productType'=>$pdata['productType'], 
                'productMode'=>$pdata['productMode'], 
                'listingType'=>$pdata['listingType'], 
                'originalPrice'=>$pdata['originalPrice'],
                'offerValue'=>$pdata['offerValue'], 
                'offerType'=>$pdata['offerType'],
                'currency'=>$pdata['currency'], 
                'taxRate'=>$pdata['taxRate'], 
                'countryId'=>$pdata['countryId'], 
                'stateId'=>$pdata['stateId'],
                'cityId'=>$pdata['cityId'],
                'zipCode'=>$pdata['zipCode'], 
                'address'=>$pdata['address'], 
                'status'=>$pdata['status'],
                'promoted'=>$pdata['promoted'],
                'promotionStart'=>$pdata['promotionStart'],
                'promotionEnd'=>$pdata['promotionEnd'],
                'visibility'=>$pdata['visibility'], 
                'rating'=>$pdata['rating'], 
                'hits'=>$pdata['hits'],
                'stock'=>$pdata['stock'], 
                'shippingCostType'=>$pdata['shippingCostType'], 
                'shippingCost'=>$pdata['shippingCost'], 
                'soldOut'=>$pdata['soldOut'], 
                'deleted'=>$pdata['deleted'], 
                'draft'=>$pdata['draft'],
                'shortDescription'=>$pdata['shortDescription'], 
                'longDescription'=>$pdata['longDescription'], 
                'sku'=>$pdata['sku'], 
                'trailer'=>$pdata['trailer'],
                'created_at'=>$pdata['created_at'],
                'images'=>$productsimage,    
            );    
            }
        $response = $this->success()->status(HttpCreated)->setValue('data', $success)->message(__('All products show successfully'));
        }
        return $response->send();
    }

    public function addtocart($productId=null){
        $product= Product::find($productId)->first();
        if ($product == null) {
            $response=$this->error()->message('failed to addto cart !');
         }else {
            $cart = Session::get('cart');
            $cart[$product->id] = array(
            "id" => $product->id,
            "name" => $product->name,
            "price" => $product->pictoriginalPrice,
            "currency" => $product->currency,
            "taxRate" => $product->taxRate,
            "qty" => 1,
            );
           Session::put('cart', $cart);
        }
        $response = $this->success()->status(HttpCreated)->setValue('data', $success)->message(__('addto cart successfully'));
        return $response->send();
    }
}
