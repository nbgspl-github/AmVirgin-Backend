<?php

namespace App\Classes;

use stdClass;

class Methods extends stdClass{
	const Index = "index";
	const Store = "store";
	const Update = "update";
	const Create = "create";
	const Delete = "delete";
	const UpdateStatus = "updateStatus";
	const Edit = "edit";
	const Show = "show";
	const Send = "send";
	const Seller = "seller";
	const ChooseAction = 'choose';

	public static function auth(){
		return new AuthMethods();
	}
}