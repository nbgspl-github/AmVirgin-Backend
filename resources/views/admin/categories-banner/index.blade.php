@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
		@include('admin.extras.header', ['title'=>trans('admin.categories-banner.index'),'action'=>['link'=>route('admin.categories-banner.create'),'text'=>'Add Category Banner']])
				</div>
				<div class="card-body animatable">
					<div class="table-responsive">
						<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
							<thead>
							<tr>
								<th>No.</th>
								<th>Banner</th>
								<th>Title</th>
                                <th>Order</th>
                                <th>Section Title</th>
                                <th>Layout Type</th>
                                <th>ValidFrom</th>
                                <th>ValidUntil</th>
                        
								
								<th>Action(s)</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($categoriesBanner as $category)
								<tr id="{{'category_row_'.$category->getKey()}}">
									<td>{{$loop->index+1}}</td>
									<td class="text-center">
									@if($category->image !=null)
										<?php  
										$im=explode(",", $category->image); 
										
										?>
								
									 @for ($i =0; $i<count($im); $i++)
									
										  <img src="{{Storage::disk('secured')->url(@$im[$i])}}" style="width: 50px; height: 50px" alt="{{ $category->title }}" @include('admin.extras.tooltip.right', ['title' =>''])/>
									@endfor
										@else
											<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 25px"></i>
										@endif
									</td>
									<td>{{ $category->title }}</td>
									<td>{{ $category->order }}</td>
									<td>{{ $category->sectionTitle }}</td>
									<td>{{ $category->layoutType }}</td>
									<td>{{ $category->validFrom }}</td>
									<td>{{ $category->validUntil }}</td>
									
									
									<td class="text-center">
										<div class="btn-toolbar" role="toolbar">
											<div class="btn-group mx-auto" role="group">
												<a class="btn btn-outline-danger" href="{{route('admin.categories-banner.edit',$category->getKey())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
												<a class="btn btn-outline-primary" href="javascript:void(0);" onclick="deleteCategory('{{$category->getKey()}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
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

		/**
		 * Returns route for Update/Status route.
		 * @param id
		 * @returns {string}
		 */
		updateStatusRoute = (id) => {
			return 'categories-banner/' + id + '/status';
		};

		/**
		 * Returns route for Genre/Delete route.
		 * @param id
		 * @returns {string}
		 */
		deleteRoute = (id) => {
			return 'categories-banner/' + id;
		};

		/**
		 * Callback for active status changes.
		 * @param id
		 * @param state
		 */
		toggleStatus = (id, state) => {
			axios.put(updateStatusRoute(id),
				{id: id, status: state})
				.then(response => {
					if (response.status === 200) {
						toastr.success(response.data.message);
					} else {
						toastr.error(response.data.message);
					}
				})
				.catch(reason => {
					console.log(reason);
					toastr.error('Failed to update status.');
				});
		};

		/**
		 * Callback for delete genre trigger.
		 * @param id
		 */
		deleteCategory = (id) => {
			window.genreId = id;
			alertify.confirm("Are you sure you want to delete this category-banner ? ",
				(ev) => {
					ev.preventDefault();
					axios.delete(deleteRoute(id))
						.then(response => {
							if (response.data.status === 200) {
								$('#category_row_' + id).remove();
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