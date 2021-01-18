@extends('admin.app.app')
@section('content')
	@include('admin.modals.productDetails')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Modify Hot Deals Section'])
				</div>
				<form action="{{route('admin.shop.hot-deals.update')}}" method="post">
					@csrf
					<div class="card-body animatable">
						<div class="row">
							<div class="col-8 mx-auto">
								<div class="card shadow-sm border animated slideInLeft">
									<div class="card-header">
										<div class="row">
											<div class="col-8 my-auto">Choose upto 50 products</div>
											<div class="col-4"><input type="text" class="form-control" name="" id="" placeholder="Search for a product" onkeyup="handleSearch(this.value);"></div>
										</div>
									</div>
									<div class="card-body pb-2">
										<ul style="list-style-type: none;" class="px-0 py-0 mb-0" id="list">
											@foreach($products as $product)
												<li class="w-100 mb-3">
													<div class="custom-control custom-checkbox mr-sm-2" data-name="{{$product['name']}}">
														<input type="checkbox" name="choice[]" class="custom-control-input" id="check_{{$product['id']}}" onchange="handleStateChanged();" @if($product['hotDeal']) checked @endif value="{{$product['id']}}">
														<label class="custom-control-label" for="check_{{$product['id']}}">{{$product['name']}}</label>
														<button type="button" onclick="handleViewDetails({{$product['id']}});" class="btn btn-sm btn-outline-primary float-right">View</button>
													</div>
												</li>
											@endforeach
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="row">
									<div class="col-6 pr-0">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-primary">
											Save
										</button>
									</div>
									<div class="col-6">
										<a href="{{route("admin.shop.choices")}}" class="btn btn-secondary waves-effect btn-block shadow-secondary">
											Cancel
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		let count = 0;
		window.onload = () => {
			count = $('input[type=checkbox]:checked').length;
		};

		handleStateChanged = () => {
			const checked = event.target.checked;
			if (checked) {
				if (count >= 50) {
					alertify.log('Only 50 products are allowed.');
					event.target.checked = false;
				} else {
					count++;
				}
			} else {
				if (count >= 0) {
					count--;
				}
			}
		};

		handleSearch = (value) => {
			$("div[data-name]").filter((index, item) => {
				$(item).toggle($(item).attr('data-name').toLowerCase().indexOf(value.toLowerCase()) !== -1);
			});
		};

		handleViewDetails = (key) => {
			alertify.error('Loading product details. Please wait!');
			axios.get('hot-deals/' + key).then((response) => {
				if (response.data.status === 200) {
					const data = response.data.data;
					setupModal(data);
				} else {
					alertify.alert(response.data.message);
				}
			}).catch((error) => {
				alertify.alert('Something went wrong. Please try again later.');
			});
		};

		setupModal = (data) => {
			$('#productName').val(data.name);
			$('#productCategory').val(data.category);
			$('#productSeller').val(data.seller);
			$('#productPrice').val(data.price);
			$('#productStock').val(data.stock);
			$('#productSKU').val(data.sku);
			$('#productDetailModal').modal('show');
		};
	</script>
@stop