<?php

namespace App\Models;

use App\Classes\Arrays;
use App\Interfaces\Directories;
use App\Storage\SecuredDisk;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model {
	protected $table = 'support-tickets';
	protected $attributes = [
		'orderId' => [], 'attachments' => [],
	];
	protected $fillable = [
		'email', 'subject', 'description', 'orderId', 'callbackNumber', 'sellerId', 'status', 'attachments',
	];
	protected $hidden = [
		'id', 'created_at', 'updated_at',
	];
	protected $casts = [
		'orderId' => 'array', 'attachments' => 'array',
	];

	protected static function boot () {
		parent::boot();
		self::creating(static function (SupportTicket $supportTicket) {
			dd($supportTicket);
		});
	}

	public function setAttachmentsAttribute ($value) : void {
		if (is_array($value) && count($value) > 0) {
			$files = Arrays::Empty;
			foreach ($value as $file) {
				Arrays::push($files, SecuredDisk::access()->putFile(Directories::SellerSupportAttachments, $file));
			}
			$this->attributes['attachments'] = jsonEncode($files);
		}
	}

	public function getAttachmentsAttribute ($value) : array {
		$decoded = jsonDecodeArray($this->attributes['attachments']);
		if (is_array($decoded) && count($decoded) > 0) {
			$paths = Arrays::Empty;
			foreach ($decoded as $file) {
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