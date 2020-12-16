@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Seller Deleted Products'])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center">Banner</th>
							<th class="text-center">Name</th>
							<th class="text-center">Description</th>
							<th class="text-center">Original Price</th>
							<th class="text-center">Offer Value</th>
							<th class="text-center">Discount Type</th>
							<th class="text-center">Action(s)</th>
						</tr>
						</thead>
						<tbody>
						@foreach($products as $product)
							<tr id="content_row_{{$product->getKey()}}">
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">
									@if($product->images()->count()>0&&\App\Storage\SecuredDisk::access()->exists($product->images()->first()->path))
										<img src="{{\App\Storage\SecuredDisk::access()->url($product->images()->first()->path)}}" style="width: 100px; height: 100px" alt="{{$product->getName()}}"/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 25px"></i>
									@endif
								</td>
								<td class="text-center">{{$product->name()}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::ellipsis($product->shortDescription(),40)}}</td>
								<td class="text-center">{{$product->originalPrice()}}</td>
								<td class="text-center">{{$product->offerValue()}}</td>
								<td class="text-center">
									@if($product->offerType()==\App\Library\Enums\Products\OfferTypes::None)
										{{'No offer'}}
									@elseif ($product->offerType()==\App\Library\Enums\Products\OfferTypes::FlatRate)
										{{'Fixed amount'}}
									@elseif($product->offerType()==\App\Library\Enums\Products\OfferTypes::Percentage)
										{{'Percentage off'}}
									@endif
								</td>
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger shadow-sm" href="{{route('admin.subscription-plans.edit',$product->getKey())}}" @include('admin.extras.tooltip.left', ['title' => 'View details'])><i class="dripicons-search"></i></a>
											<a class="btn btn-outline-primary shadow-sm" href="javascript:void(0);" onclick="deleteResource({{$product->getKey()}});" @include('admin.extras.tooltip.left', ['title' => 'Approve delete request?'])><i class="dripicons-trash"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		let dataTable = null;

		$(document).ready(() => {
			dataTable = $('#datatable').DataTable({
				initComplete: function () {
					$('#datatable_wrapper').addClass('px-0 mx-0');
				}
			});
		});

		deleteRoute = (id) => {
			return 'products/' + id;
		};

		deleteResource = (id) => {
			window.genreId = id;
			alertify.confirm("Approving delete request will permanently delete this product? ",
				(ev) => {
					ev.preventDefault();
					axios.delete(deleteRoute(id))
						.then(response => {
							if (response.status === 200) {
								dataTable.rows('#content_row_' + id).remove().draw();
								toastr.success(response.data.message);
							} else {
								toastr.error(response.data.message);
							}
						})
						.catch(error => {
							toastr.error('Something went wrong. Please try again in a while.');
						});
				},
				(ev) => {
					ev.preventDefault();
				});
		}
	</script>
@stop