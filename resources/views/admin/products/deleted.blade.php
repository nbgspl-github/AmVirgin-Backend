@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					<div class="row">
						<div class="col-8">
							<h5 class="page-title animatable">Products (Deleted by Seller)</h5>
						</div>
						<div class="col-4 my-auto">
							<form action="{{route('admin.products.rejected')}}">
								<div class="form-row float-right">
									<div class="col-auto my-1">
										<input type="text" name="query" class="form-control" id="inlineFormCustomSelect" value="{{request('query')}}" placeholder="Type & hit enter">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Seller</th>
							<th>Brand</th>
							<th>Name</th>
                            <th>Original</th>
                            <th>Selling</th>
                            <th>Discount</th>
                            <th>Variants</th>
                            <th>Created</th>
                            <th>Action(s)</th>
                        </tr>
                        </thead>

                        <tbody>
                        <x-blank-table-indicator :data="$products"/>
                        @foreach($products as $product)
							<tr id="content_row_{{$product->getKey()}}">
								<td>{{$products->firstItem()+$loop->index}}</td>
								<td>
									<span class="badge badge-secondary badge-pill">{{$product->seller->name??\App\Library\Utils\Extensions\Str::NotAvailable}}</span>
								</td>
								<td>
									<span class="badge badge-secondary badge-pill">{{$product->brand->name??\App\Library\Utils\Extensions\Str::NotAvailable}}</span>
								</td>
								<td>{{$product->name}}</td>
								<td>{{$product->originalPrice}}</td>
								<td>{{$product->sellingPrice}}</td>
								<td>{{$product->discount}}</td>
								<td>{{$product->variants()->count()}}</td>
								<td>{{$product->created_at->format('Y-m-d')}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group" role="group">
											<a class="btn btn-outline-danger" href="{{route('admin.products.deleted.details',$product->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'View product details'])><i class="mdi mdi-lightbulb-outline"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{$products->links()}}
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
	</script>
@stop