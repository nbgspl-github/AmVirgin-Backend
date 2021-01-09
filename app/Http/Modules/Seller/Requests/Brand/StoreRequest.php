<?php

namespace App\Http\Modules\Seller\Requests\Brand;

use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Rule;
use App\Models\Brand;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:2', 'max:255'],
			'logo' => ['bail', 'nullable', 'image', 'min:1', 'max:5124'],
			'website' => ['bail', 'nullable', 'url'],
			'productSaleMarketPlace' => ['bail', 'nullable', 'string', 'min:2', 'max:255'],
			'sampleMRPTagImage' => ['bail', 'nullable', 'image', 'min:1', 'max:5120'],
			'isBrandOwner' => ['bail', 'nullable', 'boolean'],
			'categoryId' => ['bail', 'required', \App\Models\Category::exists()],
			'documentProof' => ['bail', 'nullable', 'mimes:pdf', 'max:5120'],
			'documentType' => ['bail', 'nullable', Rule::in(Arrays::values(Brand::DocumentType))],
			'trademarkNumber' => ['bail', 'required_if:documentType,trademark-certificate', 'string', 'min:2', 'max:255'],
			'trademarkStatus' => ['bail', 'required_if:documentType,trademark-certificate', 'string', 'min:2', 'max:255'],
			'trademarkStatusOn' => ['bail', 'required_if:documentType,trademark-certificate', 'date'],
			'trademarkClass' => ['bail', 'required_if:documentType,trademark-certificate', 'string', 'min:2', 'max:255'],
			'trademarkAppliedDate' => ['bail', 'required_if:documentType,trademark-certificate', 'date'],
			'trademarkExpiryDate' => ['bail', 'required_if:documentType,trademark-certificate', 'date', 'after:trademarkAppliedDate'],
			'trademarkType' => ['bail', 'required_if:documentType,trademark-certificate', Rule::in(['device', 'word', 'logo', 'other'])],
			'balExpiryDate' => ['bail', 'required_if:documentType,brand-authorization-letter', 'date'],
			'invoiceDate' => ['bail', 'required_if:documentType,invoice', 'date'],
			'invoiceNumber' => ['bail', 'required_if:documentType,invoice', 'string', 'min:2', 'max:255'],
			'sellerGstIN' => ['bail', 'required_if:documentType,invoice', 'string', 'min:2', 'max:255'],
			'supplierGstIN' => ['bail', 'required_if:documentType,invoice', 'string', 'min:2', 'max:255'],
		];
	}
}