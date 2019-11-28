@extends('admin.layouts.header')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.layouts.pageHeader', ['breadcrumbs' =>['Dashboard'=>route('home'),'Categories'=>route('categories.index'),'Edit'=>'#'],'title'=>trans('strings.categories.edit')])
				</div>
				<div class="card-body animatable">
					<form action="{{route('categories.update',$category->getId())}}" method="POST" data-parsley-validate="true">
						@csrf
						<div class="form-group">
							<label>Name</label>
							<input type="text" name="slug" class="form-control" required placeholder="Type category slug" minlength="1" maxlength="100"/>
						</div>
						<div class="form-group">
							<label>Parent category</label>
							<select name="parent_id" class="form-control">
								<option value="-1">Select a parent category</option>
								@foreach ($categories as $c)
									@if($category->getId()==$c->getId())
										<option value="{{$c->getId()}}" selected>{{$c->getName()}}</option>
									@else
										<option value="{{$c->getId()}}">{{$c->getName()}}</option>
									@endif
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label>Description</label>
							<textarea type="text" name="description" class="form-control" required placeholder="Describe your category"></textarea>
						</div>
						<div class="form-group">
							<label>Keywords</label>
							<input type="text" name="keywords" class="form-control" placeholder="Type keywords (separate multiple with semi-colon)"/>
						</div>
						<div class="form-group">
							<label>Order</label>
							<select name="order" class="form-control">
								@for ($i = 1; $i <= 100; $i++)
									<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						</div>
						<div class="form-group">
							<label>Homepage Order</label>
							<select name="homepage_order" class="form-control">
								@for ($i = 1; $i <= 100; $i++)
									<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						</div>
						<div class="form-group">
							<label>Visibility</label>
							<select name="visibility" class="form-control">
								<option value="0">Hidden</option>
								<option value="1">Visible</option>
							</select>
						</div>
						<div class="form-group">
							<label>Homepage Visibility</label>
							<select name="homepage_visible" class="form-control">
								<option value="0">Hidden</option>
								<option value="1">Visible</option>
							</select>
						</div>
						<div class="form-group">
							<label>Visibility</label>
							<select name="navigation_visible" class="form-control">
								<option value="0">Hidden</option>
								<option value="1">Visible</option>
							</select>
						</div>
						<div class="form-group">
							<label>First Image</label>
							<input type="file" placeholder="Pick an image" name="image_1" class="form-control" style="height: unset; padding-left: 6px">
						</div>
						<div class="form-group">
							<label>Second Image</label>
							<input type="file" placeholder="Pick an image" name="image_2" class="form-control" style="height: unset; padding-left: 6px">
						</div>
						<div class="form-group mb-0">
							<div>
								<button type="submit" class="btn btn-primary waves-effect waves-light">
									Create
								</button>
								<button type="button" onclick="window.location.href='{{route("categories.index")}}'" class="btn btn-secondary waves-effect m-l-5">
									Cancel
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop