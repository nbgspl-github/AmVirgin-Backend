<?php

namespace App\Http\Modules\Seller\Controllers\Api\Agreements;

use App\Library\Utils\Extensions\Str;
use App\Models\Settings;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;

class AgreementController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	use ValidatesRequest;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER)->except('show');
	}

	public function index () : JsonResponse
	{
		$response = responseApp();
		$agreed = $this->seller()->mouAgreed;
		$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Retrieved seller agreed status.')->setValue('agreed', $agreed);
		return $response->send();
	}

	public function show () : JsonResponse
	{
		$response = responseApp();
		$agreement = Settings::get('mou', Str::Empty);
		$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Showing MOU.')->setValue('payload', $agreement);
		return $response->send();
	}

	public function update () : JsonResponse
	{
		$response = responseApp();
		$validated = $this->validate(['agreed' => 'bail|required|boolean']);
		$this->seller()->update(['mouAgreed' => $validated['agreed']]);
		$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Updated agreement status successfully.');
		return $response->send();
	}
}