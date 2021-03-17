@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>trans('admin.categories.index'),'action'=>['link'=>route('admin.categories.create'),'text'=>'Add']])
				</div>
                <div class="card-body table-responsive">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Type</th>
                            <th>Order</th>
                            <th>Listing</th>
                            <th>Action(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator columns="7" :data="$categories"/>
                        @foreach ($categories as $category)
							<tr id="{{'category_row_'.$category->getKey()}}">
								<td>{{$categories->firstItem()+$loop->index}}</td>
								<td>{{$category->name}}</td>
								<td>{{\App\Models\Category::parents($category)}}</td>
								<td>{{$category->type->description}}</td>
								<td>{{$category->order}}</td>
								<td>{{\App\Library\Utils\Extensions\Str::ucfirst($category->listing)}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group" role="group">
											<a class="btn btn-outline-danger" href="{{route('admin.categories.edit',$category->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
											@if($category->type==\App\Library\Enums\Categories\Types::Vertical)
												<a class="btn btn-outline-secondary" href="{{route('admin.categories.download',$category->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'Download Details Sheet'])><i class="mdi mdi-download"></i></a>
											@endif
											<a class="btn btn-outline-primary" href="javascript:deleteCategory('{{$category->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{$categories->links()}}
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		deleteCategory = (key) => {
			alertify.confirm("This action will delete chosen category and all its descendants and is irreversible. Proceed?",
				(yes) => {
					axios.delete(`admin/categories/${key}`)
						.then(response => {
							alertify.alert(response.data.message, () => {
								location.reload()
							})
						})
						.catch(error => {
							alertify.confirm('Something went wrong. Retry?', yes => {
								deleteCategory(key)
							})
						});
				}
			)
		}
	</script>
@stop