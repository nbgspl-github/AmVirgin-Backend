<?php

namespace App\Models;

use App\Library\Enums\Common\Directories;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Uploads;
use App\Models\Auth\Seller;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends \App\Library\Database\Eloquent\Model
{
    use DynamicAttributeNamedMethods;

    protected $table = 'support_tickets';
    protected $attributes = [
        'orderId' => '[]', 'attachments' => '[]',
    ];
    protected $casts = [
        'orderId' => 'array', 'attachments' => 'array',
    ];
    public const TICKET_OPEN = 'open';
    public const TICKET_CLOSED = 'closed';
    public const TICKET_RESOLVED = 'resolved';

    public function setAttachmentsAttribute ($value): void
    {
        if (is_array($value) && count($value) > 0) {
            $files = Arrays::Empty;
            foreach ($value as $file) {
                Arrays::push($files, Uploads::access()->putFile(Directories::SellerSupportAttachments, $file));
            }
            $this->attributes['attachments'] = jsonEncode($files);
        }
    }

    public function getAttachmentsAttribute ($value): array
    {
        $decoded = jsonDecodeArray($this->attributes['attachments']);
        if (is_array($decoded) && count($decoded) > 0) {
            $paths = Arrays::Empty;
            foreach ($decoded as $file) {
                $path = Uploads::existsUrl($file);
                if ($path != null)
                    Arrays::push($paths, $path);
            }
            return $paths;
        } else {
            return Arrays::Empty;
        }
    }

    public function seller (): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'sellerId');
    }
}