@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>trans('admin.categories.edit')])
				</div>
				<div class="card-body animatable">
					<form action="{{route('admin.categories.update',$category->getId())}}" method="POST" data-parsley-validate="true">
						@csrf
						<div class="form-group">
							<label>Name</label>
							<input type="text" name="name" class="form-control" required placeholder="Type category name" minlength="1" maxlength="100" value="{{old('name',$category->getName())}}"/>
						</div>
						<div class="form-group">
							<label>Parent category</label>
							<select name="parentId" class="form-control">
								@foreach($all as $item)
									<optgroup label="{{$item->name}}">
										<option value="{{$item->id}}">{{$item->name}}</option>
										@foreach($item->subItems as $subItem)
											<option value="$subItem->id">{{$subItem->name}}</option>
										@endforeach
									</optgroup>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label>Description</label>
							<textarea type="text" name="description" class="form-control" required placeholder="Describe your category"></textarea>
						</div>
						<div class="form-group">
							<label>Visibility</label>
							<select name="visibility" class="form-control">
								<option value="1" selected>Visible</option>
								<option value="0">Hidden</option>
							</select>
						</div>
						<div class="form-group">
							<label>First Image</label>
							<input type="file" placeholder="Pick an image" name="poster" class="form-control" style="height: unset; padding-left: 6px">
						</div>
						<div class="form-group mb-0">
							<div>
								<button type="submit" class="btn btn-primary waves-effect waves-light">
									Update
								</button>
								<a type="button" href="{{route('admin.categories.index')}}" class="btn btn-secondary waves-effect m-l-5">
									Cancel
								</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop