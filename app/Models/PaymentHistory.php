<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentHistory extends \App\Library\Database\Eloquent\Model
{
	use SoftDeletes;
}