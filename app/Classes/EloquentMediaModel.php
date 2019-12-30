<?php

namespace App\Classes;

use App\Exceptions\DownloadableAttributeMissingException;
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
		'url',
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
			foreach ($this->collectionSupportedActions as $action) {
				if (Str::startsWith($method, $action)) {
					// Extract suffix from method
					$suffix = substr($method, strlen($action));
					if ($this->collectionDownloadableAttributes->has($suffix)) {
						$attribute = $this->downloadableAttributes[$suffix]['attribute'];
						if ($action == 'url') {
							return Storage::disk($this->disk)->url($this->getAttributeValue($attribute));
						}
						else if ($action == 'download') {
							return Storage::disk($this->disk)->download($this->getAttributeValue($attribute));
						}
						else if ($action == 'store') {
							return Storage::disk($this->disk)->putFile($parameters[0], $parameters[1]);
						}
						else if ($action == 'delete') {
							return Storage::disk($this->disk)->delete($this->getAttributeValue($attribute));
						}
						else {
							throw new DownloadableAttributeMissingException(sprintf('No supported attribute [%s] was found for action [%s]. Method name was [%s]', $suffix, $action, $method));
						}
					}
					else {
						throw new DownloadableAttributeMissingException(sprintf('No supported attribute [%s] was found for action [%s]. Method name was [%s]', $suffix, $action, $method));
					}
				}
			}
		}
		return parent::__call($method, $parameters);
	}
}