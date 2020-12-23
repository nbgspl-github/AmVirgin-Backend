<?php

namespace App\Http\Controllers\Api\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Library\Enums\Advertisements\Status;
use App\Library\Enums\Common\Directories;
use App\Library\Utils\Uploads;
use App\Models\Advertisement;
use App\Resources\Advertisements\Seller\ListResource;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class AdvertisementController extends ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'index' => [
				'page' => ['bail', 'nullable', 'numeric', 'min:1', 'max:10000'],
				'active' => ['bail', 'sometimes', 'boolean']
			],
			'store' => [
				'subject' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'message' => ['bail', 'nullable', 'string', 'min:1', 'max:5000'],
				'banner' => ['bail', 'required', 'image', 'max:5124'],
				'active' => ['bail', 'sometimes', 'boolean']
			],
			'update' => [
				'subject' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'message' => ['bail', 'nullable', 'string', 'min:1', 'max:5000'],
				'banner' => ['bail', 'nullable', 'image', 'max:5124'],
				'active' => ['bail', 'sometimes', 'boolean']
			],
		];
	}

	public function index () : JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			$validated['page'] = $validated['page'] ?? 1;
			$advertisementCollection = Advertisement::startQuery()->active(request('active', true))->latest()->useAuth()->paginate(50);
			$resourceCollection = ListResource::collection($advertisementCollection);
			$response->status($resourceCollection->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)->message('Listing all advertisements by this seller.')->setValue('payload', $resourceCollection);
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function store () : JsonResponse
	{
		$response = responseApp();
		try {
			$payload = $this->requestValid(request(), $this->rules['store']);
			$payload['banner'] = \request()->hasFile('banner') ? Uploads::access()->putFile(Directories::Advertisement, \request()->file('banner')) : null;
			$payload['sellerId'] = $this->guard()->id();
			$advertisement = Advertisement::query()->create($payload);
			$resource = new ListResource($advertisement);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Advertisement created successfully.')->setValue('payload', $resource);
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function show (Advertisement $advertisement) : JsonResponse
	{
		$response = responseApp();
		try {
			$resource = new ListResource($advertisement);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing advertisement details.')->setValue('payload', $resource);
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function update (Advertisement $advertisement) : JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['update']);
			if ($advertisement->seller->is($this->user()) && $advertisement->status->is(Status::Pending)) {
				$advertisement->update($validated);
				$response->status(\Illuminate\Http\Response::HTTP_NO_CONTENT)->message('Advertisement updated successfully!');
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_NOT_MODIFIED)->message('Advertisement can not be updated now.');
			}
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function delete (Advertisement $advertisement) : JsonResponse
	{
		$response = responseApp();
		try {
			if ($advertisement->seller->is($this->user()) && $advertisement->status->is(Status::Pending)) {
				$advertisement->delete();
				$response->status(\Illuminate\Http\Response::HTTP_NO_CONTENT)->message('Advertisement deleted successfully!');
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_NOT_MODIFIED)->message('Advertisement can not be deleted now.');
			}
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}