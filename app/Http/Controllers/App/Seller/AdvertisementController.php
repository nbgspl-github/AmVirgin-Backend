<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ValidatesRequest;

class AdvertisementController extends Controller
{
	use ValidatesRequest;


	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'store' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
				'image' => ['bail', 'nullable', 'image'],				
			],
			'update' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
				'image' => ['bail', 'nullable', 'image'],
			],
		];
	}
    public function store(){
		$response = responseWeb();
		try {
			$payload = $this->requestValid(request(), $this->rules['store']);
			$payload['image'] = \request()->hasFile('image') ? SecuredDisk::access()->putFile(Directories::Advertisement, \request()->file('image')) : null;
			$payload['specials'] = [];
			Category::create($payload);
			$response->route('admin.categories.index')->success('Created category successfully.');
		}
		catch (ValidationException $exception) {
			$response->back()->data(\request()->all())->error($exception->getMessage());
		}
		catch (Throwable $exception) {
			$response->back()->data(\request()->all())->error($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}
}
