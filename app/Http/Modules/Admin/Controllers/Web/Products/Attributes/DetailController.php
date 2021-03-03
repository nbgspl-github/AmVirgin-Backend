<?php

namespace App\Http\Modules\Admin\Controllers\Web\Products\Attributes;

class DetailController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function pending (\App\Models\Product $product)
    {
        return view('admin.products.details.pending')->with('product', $product);
    }

    public function approve (\App\Models\Product $product): \Illuminate\Http\RedirectResponse
    {
        if ($product->status == 'pending') {
            $product->update(['status' => 'approved']);
            return redirect()->route('admin.products.pending')->with('success', 'Product approved successfully.');
        } else {
            return redirect()->route('admin.products.pending')->with('error', 'Product can\'t be marked approved now.');
        }
    }

    public function reject (\App\Models\Product $product): \Illuminate\Http\RedirectResponse
    {
        if ($product->status == 'pending') {
            $product->update(['status' => 'rejected']);
            return redirect()->route('admin.products.pending')->with('success', 'Product rejected successfully.');
        } else {
            return redirect()->route('admin.products.pending')->with('success', 'Product can\'t be marked rejected now.');
        }
    }

    public function approved (\App\Models\Product $product)
    {
        return view('admin.products.details.approved')->with('product', $product);
    }

    public function rejected (\App\Models\Product $product)
    {
        return view('admin.products.details.approved')->with('product', $product);
    }

    public function deleted (\App\Models\Product $product)
    {
        return view('admin.products.details.approved')->with('product', $product);
    }
}