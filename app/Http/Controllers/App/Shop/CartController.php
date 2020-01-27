<?php
namespace App\Http\Controllers\App\Shop;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\StatusCodes;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCart;
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
use Illuminate\Support\Facades\Storage;
class CartController extends BaseController{
use ValidatesRequest;
use FluentResponse;
	public function __construct(){
		parent::__construct();
		//$this->ruleSet->load('rules.shop.product');
    }
    
    public function index(Request $request){
        $response = null;
        try{
         
           if($request->cartId !='' &&  $request->customerId !=''){
             $carts = ProductCart::where('cartId',$request->cartId)->Where('customerId',$request->customerId)->get();
           }elseif($request->customerId !='' && $request->cartId ==''){
             $carts = ProductCart::Where('customerId',$request->customerId)->get();
           }elseif($request->customerId =='' && $request->cartId !=''){
             $carts = ProductCart::Where('cartId', $request->cartId)->get();
           }else{
            $response = $this->failed()->status(HttpResourceNotFound)->message(__(' cart is empty !'));
           }
           
           if(count($carts)>0){    
                $response = $this->success()->status(HttpOkay)->setValue('data', $carts)->message(__('show cart'));
           }else{
               $response = $this->failed()->status(HttpResourceNotFound)->message(__(' cart is empty !'));
           }   
        
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
    
	
    public function store(Request $request){
        $response = null;
        try{
            $this->requestValid($request, [
                'cartId' => ['bail', 'required'],
                'productId' => ['bail', 'required', 'numeric', 'min:1', 'max:99999'],
                'quantity' => ['bail', 'required', 'numeric', 'min:1', 'max:20'],
                
              ]);
            $product = ProductCart::create($request->all());
            $response = $this->success()->status(HttpCreated)->setValue('data', $product)->message(__('cart add successfully'));
        
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

    
    public function delete($id){
      $response = null;
      try{  
        $GetCartProduct = ProductCart::find($id);
        if ($GetCartProduct == null) {
          $response = $this->failed()->status(HttpResourceNotFound);
        }
        $GetCartProduct->delete();
          $response = $this->success()->status(HttpOkay)->message(__('Successfully remove this item'));
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
    
    
    
}
