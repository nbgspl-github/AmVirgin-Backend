@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Attribute Sets','action'=>['link'=>route('admin.attributes.sets.create'),'text'=>'Create an attribute set']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Name</th>
							<th class="text-center">Category</th>
							<th class="text-center">Actions</th>
						</tr>
						</thead>

						<tbody>
						@foreach($sets as $set)
							<tr>
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">{{$set->name}}</td>
								<td class="text-center">{{\App\Models\Category::parents($set->category)}}</td>
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-primary shadow-sm" href="javascript:deleteAttributeSet('{{$set->category->id}}');" @include('admin.extras.tooltip.right', ['title' => 'Delete attribute set'])><i class="mdi mdi-delete"></i></a>
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
		deleteAttributeSet = key => {
			alertify.confirm("Are you sure? This action is irreversible!",
				yes => {
					axios.delete(`/admin/attributes/sets/${key}`).then(response => {
						alertify.alert(response.data.message, () => {
							location.reload();
						});
					}).catch(e => {
						alertify.confirm('Something went wrong. Retry?', yes => {
							deleteAttributeSet(key);
						});
					});
				}
			)
		}
	</script>
@stop