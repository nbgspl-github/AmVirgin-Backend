<?php

namespace App\Models;

use App\Classes\Arrays;
use App\Interfaces\Directories;
use App\Storage\SecuredDisk;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model {
	protected $table = 'support-tickets';
	protected $attributes = [
		'orderId' => [],
	];
	protected $fillable = [
		'email', 'subject', 'description', 'orderId', 'callbackNumber', 'sellerId', 'status', 'attachments',
	];
	protected $hidden = [
		'id', 'created_at', 'updated_at',
	];
//	protected $casts = [
//		'orderId' => 'array', 'attachments' => 'array',
//	];

	public function setAttachmentsAttribute ($value) : void {
		if (is_array($value) && count($value) > 0) {
			$files = Arrays::Empty;
			foreach ($value as $file) {
				Arrays::push($files, SecuredDisk::access()->putFile(Directories::SellerSupportAttachments, $file));
			}
			$this->attributes['attachments'] = $files;
		}
	}

	public function getAttachmentsAttribute ($value) : array {
		if (is_array($this->attributes['attachments']) && count($this->attributes['attachments']) > 0) {
			$paths = Arrays::Empty;
			foreach ($this->attributes['attachments'] as $file) {
				$path = SecuredDisk::existsUrl($file);
				if ($path != null)
					Arrays::push($paths, $path);
			}
			return $paths;
		}
		else {
			return Arrays::Empty;
		}
	}
}