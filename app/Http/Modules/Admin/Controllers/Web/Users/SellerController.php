<?php

namespace App\Http\Modules\Admin\Controllers\Web\Users;

use App\Exceptions\ValidationException;
use App\Library\Enums\Common\Tables;
use App\Models\Auth\Seller;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SellerController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	use ValidatesRequest;

	protected $ruleSet;

	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet = config('rules.admin.sellers');
	}

	public function index ()
	{
		$payload = Seller::all();
		return view('admin.sellers.index')->with('sellers', $payload);
	}

	public function create ()
	{
		return view('admin.sellers.create');
	}

	public function edit ($id = null)
	{
		$seller = Seller::find($id);
		if ($seller != null) {
			return view('admin.sellers.edit')->with('seller', $seller);
		} else {
			return responseWeb()->route('admin.sellers.index')->error(trans('admin.sellers.not-found'))->send();
		}
	}

	public function store (Request $request)
	{
		$response = null;
		try {
			$payload = $this->requestValid($request, $this->ruleSet['store']);
			Seller::create($payload);
			$response = responseWeb()->route('admin.sellers.index')->success(__('strings.sellers.store.success'));
		} catch (ValidationException $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getMessage());
		} catch (Exception $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function update (Request $request, $id = null)
	{
		$response = null;
		$seller = Seller::find($id);
		try {
			if ($seller == null)
				throw new ModelNotFoundException(__('strings.sellers.not-found'));
			$additional = [
				'mobile' => [Rule::unique(Tables::Sellers, 'mobile')->ignore($seller->getKey())],
				'email' => [Rule::unique(Tables::Sellers, 'email')->ignore($seller->getKey())],
			];
			$payload = $this->requestValid($request, $this->ruleSet['update'], $additional);
			$seller->update($payload);
			$response = responseWeb()->route('admin.sellers.index')->success(__('strings.seller.update.success'));
		} catch (ModelNotFoundException $exception) {
			$response = responseWeb()->route('admin.sellers.index')->error($exception->getMessage());
		} catch (ValidationException $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getError());
		} catch (Exception $exception) {
			$response = responseWeb()->route('admin.sellers.index')->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}