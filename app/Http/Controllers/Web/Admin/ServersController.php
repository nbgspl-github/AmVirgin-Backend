<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Models\Video\MediaServer;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServersController extends BaseController
{
	use ValidatesRequest;

	protected $ruleSet;

	public function __construct ()
	{
		$this->ruleSet = config('rules.admin.servers');
	}

	public function index ()
	{
		$payload = MediaServer::all();
		return view('admin.servers.index')->with('servers', $payload);
	}

	public function create ()
	{
		return view('admin.servers.create');
	}

	public function edit ($id)
	{
		try {
			$payload = MediaServer::findOrFail($id);
			return view('admin.servers.edit')->with('server', $payload);
		} catch (ModelNotFoundException $exception) {
			return responseWeb()->route('admin.servers.index')->error($exception->getMessage())->send();
		}
	}

	public function store ()
	{
		$response = responseWeb();
		try {
			$payload = $this->requestValid(request(), $this->ruleSet['store']);
			MediaServer::create($payload);
			$response->success(__('strings.server.store.success'))->route('admin.servers.index');
		} catch (ValidationException $exception) {
			$response->back()->data(request()->all())->error($exception->getError());
		} catch (Exception $exception) {
			$response->back()->data(request()->all())->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function update ($id)
	{
		$response = responseWeb();
		try {
			$server = MediaServer::findOrFail($id);
			$payload = $this->requestValid(request(), $this->ruleSet['update']);
			$server->update($payload);
			$response->success(__('strings.server.store.success'))->route('admin.servers.index');
		} catch (ModelNotFoundException $exception) {
			$response->route('admin.servers.index')->error($exception->getMessage());
		} catch (ValidationException $exception) {
			$response->back()->data(request()->all())->error($exception->getError());
		} catch (Exception $exception) {
			$response->back()->data(request()->all())->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function delete ($id)
	{

	}
}