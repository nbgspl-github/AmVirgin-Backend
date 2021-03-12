@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="page-title animatable">Product Details - (Pending Approval)</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-8 mx-auto">
                                <div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input id="name" type="text" name="name" class="form-control" required
                                                   placeholder="Type a name" value="{{old('name',$product->name)}}"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <input id="description" type="text" name="description"
                                                   class="form-control bg-white"
                                                   value="{{old('description',$product->description??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="seller">Seller</label>
                                            <input id="seller" type="text" name="seller" class="form-control bg-white"
                                                   value="{{old('seller',$product->seller->name??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="brand">Brand</label>
                                            <input id="brand" type="text" name="brand" class="form-control bg-white"
                                                   value="{{old('brand',$product->brand->name??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="listing">Listing</label>
                                            <input id="listing" type="text" name="listing" class="form-control bg-white"
                                                   value="{{old('listingStatus',$product->listingStatus??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <input id="type" type="text" name="type" class="form-control bg-white"
                                                   value="{{old('type',$product->type??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="ideal_for">Ideal For</label>
                                            <input id="ideal_for" type="text" name="idealFor"
                                                   class="form-control bg-white"
                                                   value="{{old('idealFor',$product->idealFor??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-4">
                                                    <label for="originalPrice">Original Price</label>
                                                    <input id="originalPrice" type="text" name="originalPrice"
                                                           class="form-control bg-white"
                                                           value="{{old('originalPrice',$product->originalPrice??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-4">
                                                    <label for="sellingPrice">Selling Price</label>
                                                    <input id="sellingPrice" type="text" name="sellingPrice"
                                                           class="form-control bg-white"
                                                           value="{{old('sellingPrice',$product->sellingPrice??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-4">
                                                    <label for="discount">Discount %</label>
                                                    <input id="discount" type="text" name="discount"
                                                           class="form-control bg-white"
                                                           value="{{old('discount',$product->discount??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="fulfillmentBy">Fulfillment By</label>
                                            <input id="fulfillmentBy" type="text" name="fulfillmentBy"
                                                   class="form-control bg-white"
                                                   value="{{old('fulfillmentBy',$product->fulfillmentBy??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="rating">Rating</label>
                                            <input id="rating" type="text" name="rating" class="form-control bg-white"
                                                   value="{{old('rating',$product->rating??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="stock">Stock</label>
                                            <input id="stock" type="text" name="stock" class="form-control bg-white"
                                                   value="{{old('stock',$product->stock??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="lowStockThreshold">Low Stock Threshold</label>
                                            <input id="lowStockThreshold" type="text" name="lowStockThreshold"
                                                   class="form-control bg-white"
                                                   value="{{old('lowStockThreshold',$product->lowStockThreshold??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="sku">Stock Keeping Unit</label>
                                            <input id="sku" type="text" name="sku" class="form-control bg-white"
                                                   value="{{old('sku',$product->sku??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="styleCode">Style Code</label>
                                            <input id="styleCode" type="text" name="styleCode"
                                                   class="form-control bg-white"
                                                   value="{{old('styleCode',$product->styleCode??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="procurementSla">Procurement SLA</label>
                                            <input id="procurementSla" type="text" name="procurementSla"
                                                   class="form-control bg-white"
                                                   value="{{old('procurementSla',$product->procurementSla??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                   readonly/>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-4">
                                                    <label for="localShippingCost">Local Shipping</label>
                                                    <input id="localShippingCost" type="text" name="localShippingCost"
                                                           class="form-control bg-white"
                                                           value="{{old('localShippingCost',$product->localShippingCost??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-4">
                                                    <label for="zonalShippingCost">Zonal Shipping</label>
                                                    <input id="zonalShippingCost" type="text" name="zonalShippingCost"
                                                           class="form-control bg-white"
                                                           value="{{old('zonalShippingCost',$product->zonalShippingCost??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-4">
                                                    <label for="internationalShippingCost">International
                                                        Shipping</label>
                                                    <input id="internationalShippingCost" type="text"
                                                           name="internationalShippingCost"
                                                           class="form-control bg-white"
                                                           value="{{old('internationalShippingCost',$product->internationalShippingCost??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-3">
                                                    <label for="packageWeight">Package Weight</label>
                                                    <input id="packageWeight" type="text" name="packageWeight"
                                                           class="form-control bg-white"
                                                           value="{{old('packageWeight',$product->packageWeight??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-3">
                                                    <label for="packageLength">Package Length</label>
                                                    <input id="packageLength" type="text" name="packageLength"
                                                           class="form-control bg-white"
                                                           value="{{old('packageLength',$product->packageLength??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-3">
                                                    <label for="packageBreadth">Package Breadth</label>
                                                    <input id="packageBreadth" type="text" name="packageBreadth"
                                                           class="form-control bg-white"
                                                           value="{{old('packageBreadth',$product->packageBreadth??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-3">
                                                    <label for="packageHeight">Package Height</label>
                                                    <input id="packageHeight" type="text" name="packageHeight"
                                                           class="form-control bg-white"
                                                           value="{{old('packageHeight',$product->packageHeight??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-4">
                                                    <label for="domesticWarranty">Domestic Warranty</label>
                                                    <input id="domesticWarranty" name="domesticWarranty"
                                                           class="form-control bg-white"
                                                           readonly value="{{$product->domesticWarranty}}">
                                                </div>
                                                <div class="col-4">
                                                    <label for="internationalWarranty">International Warranty</label>
                                                    <input id="internationalWarranty" name="internationalWarranty"
                                                           class="form-control bg-white"
                                                           readonly value="{{$product->internationalWarranty}}">
                                                </div>
                                                <div class="col-4">
                                                    <label for="serviceType">Service Type</label>
                                                    <input id="serviceType" type="text" name="serviceType"
                                                           class="form-control bg-white"
                                                           value="{{old('serviceType',$product->warrantyServiceType??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="warrantySummary">Warranty Summary</label>
                                            <textarea id="warrantySummary" name="warrantySummary"
                                                      class="form-control bg-white"
                                                      readonly>{{$product->warrantySummary}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="domesticWarranty">Returnable</label>
                                            <textarea id="domesticWarranty" name="domesticWarranty"
                                                      class="form-control bg-white"
                                                      readonly>{{$product->returnable?'True':'False'}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-4">
                                                    <label for="returnable">Returnable</label>
                                                    <input id="returnable" type="text" name="returnable"
                                                           class="form-control bg-white"
                                                           value="{{old('returnable',$product->returnable?'Yes':'No')}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-4">
                                                    <label for="returnPeriod">Returnable Period</label>
                                                    <input id="returnPeriod" type="text" name="returnPeriod"
                                                           class="form-control bg-white"
                                                           value="{{old('returnPeriod',$product->returnPeriod??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-4">
                                                    <label for="returnType">Return Type</label>
                                                    <input id="returnType" type="text" name="returnType"
                                                           class="form-control bg-white"
                                                           value="{{old('returnType',$product->returnType??\App\Library\Utils\Extensions\Str::NotAvailable)}}"
                                                           readonly/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <h4 class="mt-0 header-title">Images</h4>
                                            <div id="carouselExampleIndicators" class="carousel slide"
                                                 data-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach($product->images as $image)
                                                        <div class="carousel-item @if($loop->first) active @endif">
                                                            <img src="{{$image->path}}" class="d-block w-100"
                                                                 style="max-height: 250px!important;" alt="...">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <a class="carousel-control-prev" href="#carouselExampleIndicators"
                                                   role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#carouselExampleIndicators"
                                                   role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-8 mx-auto">
                                <div class="row">
                                    <div class="col-4">
                                        <form action="{{route('admin.products.pending.approve',$product->id)}}">
                                            <button type="submit"
                                                    class="btn btn-success waves-effect waves-light btn-block shadow-success">
                                                Approve
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-4">
                                        <form action="{{route('admin.products.pending.reject',$product->id)}}">
                                            <button type="submit"
                                                    class="btn btn-primary waves-effect waves-light btn-block shadow-primary">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{route('admin.products.pending')}}"
                                           class="btn btn-secondary waves-effect btn-block shadow-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="application/javascript">
    </script>
@stop