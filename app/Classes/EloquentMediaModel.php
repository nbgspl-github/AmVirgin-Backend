<?php

namespace App\Classes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EloquentMediaModel extends Model{
	protected $disk;
	protected $directory;
	protected $mediaVisibility = 'public';
	protected $downloadableAttributes = [

	];
	protected $supportedActions = [
		'store',
		'download',
		'generate',
		'delete',
	];
	private $collectionSupportedActions;
	private $collectionDownloadableAttributes;

	/**
	 * EloquentMediaModel constructor.
	 */
	public function __construct(){
		parent::__construct();
		$this->collectionSupportedActions = collect($this->supportedActions);
		$this->collectionDownloadableAttributes = collect($this->downloadableAttributes);
	}

	public function storeFile($file){
		return Storage::disk($this->disk)->putFile($this->directory, $file, $this->mediaVisibility);
	}

	public function generateUrl(string $filePath){
		return Storage::disk($this->disk)->url($filePath);
	}

	public function downloadFile(string $filePath){
		return Storage::disk($this->disk)->download($filePath);
	}

	public function deleteFile($filePath){
		return Storage::disk($this->disk)->delete($filePath);
	}

	public function __call($method, $parameters){
		if (!method_exists($this, $method)) {
			$this->collectionSupportedActions->each(function (string $action) use ($method){
				if (Str::startsWith($method, $action)) {
					// Extract suffix from method
					$suffix = substr($method, strlen($action));
					$methodName = $action . $this->downloadableAttributes[$suffix]['method'];
					if ($this->collectionDownloadableAttributes->has($suffix)) {
						dd('No suffix');
						printf('Calculated method name is %s', $methodName);
						return "Called";
					}
					else {
						dd('Has suffix');
						return 'Not found';
					}
				}
			});
		}
		return parent::__call($method, $parameters);
	}
}