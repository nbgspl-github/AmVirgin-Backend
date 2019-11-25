@extends('layouts.header')
@section('content')
	@include('modals.lightbox')
	@include('layouts.breadcrumbs', ['data' => ['Genres'=>route('genres.index'),'Edit'=>'#']])
	<div class="row px-2">
		<div class="card card-body">
			<h4 class="mt-0 header-title">Edit Genre</h4>
			<p class="text-muted m-b-30 font-14">Update genre details and hit Update</p>
			<form action="{{route('genres.update',$genre->getId())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="form-group">
					<label>Name<span class="text-primary">*</span></label>
					<input type="text" name="name" class="form-control" required placeholder="Type here the genre's name or title" minlength="1" maxlength="100" value="{{old('name',$genre->name)}}"/>
				</div>
				<div class="form-group">
					<label>Description</label>
					<input type="text" name="description" class="form-control" placeholder="Type here the genre's description" value="{{old('description',$genre->description)}}"/>
				</div>
				<div class="form-group">
					<label>Poster</label>
					<input type="file" name="poster" onclick="this.value=null;" onchange="previewImage(event);" class="form-control" data-parsley-type="file" style="height: unset; padding-left: 6px" accept=".jpg, .png, .jpeg, .bmp" value="{{$genre->poster}}"/>
				</div>
				<div class="form-group">
					<label>Status</label>
					<div class="btn-group btn-group-toggle d-block" data-toggle="buttons">
						@if ($genre->getStatus()==1)
							<label class="btn btn-outline-danger active">
								<input type="radio" name="status" value="1" onchange="switchActive();"/>Active
							</label>
							<label class="btn btn-outline-primary">
								<input type="radio" name="status" value="0"/>Inactive
							</label>
						@else
							<label class="btn btn-outline-danger">
								<input type="radio" name="status" value="1"/>Active
							</label>
							<label class="btn btn-outline-primary active">
								<input type="radio" name="status" value="0"/>Inactive
							</label>
						@endif
					</div>
				</div>
				<div class="form-group">
					<div>
						<button type="submit" class="btn btn-primary waves-effect waves-light">
							Save
						</button>
						<a href="{{route("genres.index")}}" class="btn btn-secondary waves-effect m-l-5">
							Cancel
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection

@section('javascript')
	<script>
		var lastFile = null;
		previewImage = (event) => {
			const reader = new FileReader();
			reader.onload = function () {
				const output = document.getElementById('preview');
				output.src = reader.result;
			};
			reader.onloadend = () => {
				$('#lightbox').modal('show');
			};
			lastFile = event.target.files[0];
			reader.readAsDataURL(lastFile);
		};

		displayLightBox = () => {
			$('#lightbox').modal('show');
		};

		switchActive = () => {

		};
	</script>
@stop