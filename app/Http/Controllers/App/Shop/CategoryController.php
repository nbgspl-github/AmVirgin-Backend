<?php

namespace App\Http\Controllers\App\Shop;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CategoryController extends BaseController{
	use ValidatesRequest;
	use FluentResponse;

	public function __construct(){
		parent::__construct();
		//$this->ruleSet->load('rules.shop.product');

	}

	public function index(){
		$response = null;
		$parientArr = null;

		$subcategory_data = 'null';
		try {
			$Maincategorys = Category::where('parentId', '0')->get();
			if ($Maincategorys == null || count($Maincategorys) == 0) {
				$response = $this->failed()->status(HttpResourceNotFound)->message(__(' Category not found'));;
			}
			else {
				foreach ($Maincategorys as $mdata) {
					$Getcategory = Category::where('parentId', $mdata['id'])->get();//get category data

					if (count($Getcategory) > 0) {
						foreach ($Getcategory as $cvalue) {
							//get subcategory data
							$GetSubcategory = Category::where('parentId', $cvalue['id'])->select('id', 'name', 'poster', 'description', 'visibility')->get();//get category data
							if (count($GetSubcategory) > 0) {
								$subcategory_data = $GetSubcategory;
							}
							else {
								$subcategory_data = [];
							}
							/*--Get category data--*/
							$category_data[] = [
								'id' => $cvalue['id'],
								'name' => $cvalue['name'],
								'visibility' => $cvalue['visibility'],
								'poster' => Storage::disk('secured')->url($cvalue['poster']),
								'description' => $cvalue['description'],
								'sub_category' => $subcategory_data];
						}
					}
					else {
						$category_data = [];
					}
					$parientArr[] = ['id' => $mdata['id'],
						'name' => $mdata['name'],
						'visibility' => $mdata['visibility'],
						'poster' => Storage::disk('secured')->url($mdata['poster']),
						'icon' => Storage::disk('secured')->url($mdata['icon']),
						'description' => $mdata['description'],
						'category' => $category_data,
					];
				}
				$response = $this->success()->status(HttpOkay)->setValue('Maincategory', $parientArr)
					->message(__(' Category show successfully'));
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

}
