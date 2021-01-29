@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					<div class="row">
						<div class="col-8">
							<h5 class="page-title animatable">Create News Category</h5>
						</div>
					</div>
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.news.categories.store')}}" method="POST" data-parsley-validate="true">
								@csrf
								<div class="form-group">
									<label for="name">Name</label>
									<input id="name" type="text" name="name" class="form-control" required placeholder="Name" minlength="2" maxlength="100" value="{{old('name')}}"/>
								</div>
								<div class="form-group">
									<label for="description">Description</label>
									<input id="description" type="text" name="description" class="form-control" required placeholder="Description" value="{{old('description')}}"/>
								</div>
								<div class="form-group">
									<label for="order">Order</label>
									<select name="order" id="order" class="form-control">
										@for($i=0;$i<127;$i++)
											<option value="{{$i}}">{{$i}}</option>
										@endfor
									</select>
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-6 pr-0">
											<button type="submit" class="btn btn-primary waves-effect waves-light btn-block">
												Save
											</button>
										</div>
										<div class="col-6">
											<a href="{{route("admin.news.categories.index")}}" class="btn btn-secondary waves-effect m-l-5 btn-block">
												Cancel
											</a>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		_delete = key => {
			alertify.confirm("Are you sure? This action is irreversible!",
				yes => {
					axios.delete(`/admin/news/categories/${key}`).then(response => {
						alertify.alert(response.data.message, () => {
							location.reload();
						});
					}).catch(e => {
						alertify.confirm('Something went wrong. Retry?', yes => {
							_delete(key);
						});
					});
				}
			)
		}
	</script>
@stop