<?php

namespace App\Http\Controllers\App\Shop;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\StatusCodes;
use App\Models\Category;
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
class CategoryController extends BaseController{
use ValidatesRequest;
use FluentResponse;


	public function __construct(){
		parent::__construct();
		//$this->ruleSet->load('rules.shop.product');

	}

	public function index()
	{
		$Maincategorys = Category::where('parentId','0')->get();
		//multipal image upload
		if ($Maincategorys == null) 
		{
		  $response=$this->error()->message('Product not found !');
		}else{
				$parientArr=array();
				$category_data=array();
				$subcategory_data='null';
				foreach ($Maincategorys as $mdata) 
				{
					$Getcategory=Category::where('parentId',$mdata['id'])->get();//get category data
					if(count($Getcategory)>0)
					{
						foreach ($Getcategory as $cvalue) 
						{
						  //get subcategory data
							$GetSubcategory=Category::where('parentId',$cvalue['id'])->select('id','name','poster','description','visibility')->get();//get category data
							if(count($GetSubcategory)>0){
							$subcategory_data=$GetSubcategory;
							}
						     /*--Get category data--*/
							$category_data[]=array('id'=>$cvalue['id'],
							'name'=>$cvalue['name'],
							'visibility'=>$cvalue['visibility'],
							'poster'=>$cvalue['poster'],
							'description'=>$cvalue['description'],
							'sub_category'=>$subcategory_data);
						}
					}else{
					  $category_data=array();
					}
						$parientArr[]=array('id'=>$mdata['id'],
						'name'=>$mdata['name'],
						'visibility'=>$mdata['visibility'],
						'poster'=>$mdata['poster'],
						'icon'=>$mdata['icon'],
						'description'=>$mdata['description'],
						'category'=>$category_data
						);
				}
			    //$response= response()->json(['response'=>$success]);
			    $response = $this->success()->status(HttpCreated)->setValue('Maincategory', $parientArr)->message(__('All category show successfully'));
		    }
		    return $response->send();
	}



}
