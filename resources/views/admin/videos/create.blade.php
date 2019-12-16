@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Add a video'])
				</div>
				<form action="{{route('admin.videos.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card shadow-none" style="border: 1px solid #ced4da;">
									<div class="card-header text-primary font-weight-bold bg-white">
										Attributes
									</div>
									<div class="card-body">
										<div class="form-group">
											<label for="title">Title<span class="text-primary">*</span></label>
											<input id="title" type="text" name="title" class="form-control" required placeholder="Type here the movie's title" minlength="1" maxlength="100" value="{{old('movieDBId')}}"/>
										</div>
										<div class="form-group">
											<label for="movieDBId">TheMovieDB Id<span class="text-primary">*</span></label>
											<input id="movieDBId" type="text" name="movieDBId" class="form-control" required placeholder="Type reference number from TheMovieDB" minlength="1" maxlength="100" value="{{old('movieDBId')}}"/>
										</div>
										<div class="form-group">
											<label for="description">Overview (Description)<span class="text-primary">*</span></label>
											<textarea id="description" name="description" class="form-control" required placeholder="Type short summary about the movie or video" minlength="1" maxlength="100">{{old('description')}}</textarea>
										</div>
										<div class="form-group">
											<label for="genre">Choose a genre<span class="text-primary">*</span></label>
											<select id="genre" name="genreId" class="form-control">
												<option value="-1">Choose...</option>
												@foreach($genres as $genre)
													<option value="{{$genre->getKey()}}">{{$genre->getName()}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label for="releaseDate">Release date<span class="text-primary">*</span></label>
											<input id="releaseDate" type="date" name="releaseDate" class="form-control" required placeholder="Choose or type release date" minlength="1" maxlength="100" value="{{old('releaseDate')}}"/>
										</div>
										<div class="form-group">
											<label for="averageRating">Average rating<span class="text-primary">*</span></label>
											<input id="averageRating" type="number" name="averageRating" class="form-control" required placeholder="Type average rating for this movie/video" minlength="1" maxlength="100" value="{{old('averageRating')}}"/>
										</div>
										<div class="form-group">
											<label for="votes">Votes<span class="text-primary">*</span></label>
											<input id="votes" type="number" name="votes" class="form-control" required placeholder="Type votes given to this movie/video" minlength="1" maxlength="100" value="{{old('votes')}}"/>
										</div>
										<div class="form-group">
											<label for="popularity">Popularity<span class="text-primary">*</span></label>
											<input id="popularity" type="number" name="popularity" class="form-control" required placeholder="Type popularity of this movie/video" minlength="1" maxlength="100" value="{{old('popularity')}}"/>
										</div>
										<div class="form-group">
											<label for="server">Choose a server<span class="text-primary">*</span></label>
											<select id="server" name="serverId" class="form-control">
												<option value="-1">Choose...</option>
												@foreach($genres as $genre)
													<option value="{{$genre->getKey()}}">{{$genre->getName()}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label for="language">Language<span class="text-primary">*</span></label>
											<select id="language" name="languageId" class="form-control">
												<option value="-1">Choose...</option>
												@foreach($genres as $genre)
													<option value="{{$genre->getKey()}}">{{$genre->getName()}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label for="mediaQuality">Media quality<span class="text-primary">*</span></label>
											<select id="mediaQuality" name="mediaQuality" class="form-control">
												<option value="0">Choose...</option>
												<option value="1">Standard (480p)</option>
												<option value="2">HD (720p)</option>
												<option value="3">FHD (1080p)</option>
												<option value="4">UHD (2160p)</option>
												<option value="5">4K (3840p)</option>
											</select>
										</div>
										<div class="form-group pb-0 mb-0">
											<label>Push notify customers?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="customCheck">
													<label class="custom-control-label" for="customCheck">Notify</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card shadow-none" style="border: 1px solid #ced4da;">
									<div class="card-header text-primary font-weight-bold bg-white">
										Media
									</div>
									<div class="card-body">
										<div class="form-group">
											<div class="card bg-white shadow-none" style="border: 1px solid #ced4da;">
												<div class="card-header">
													<div class="row">
														<div class="d-none">
															<input id="pickPoster" type="file" name="poster" onclick="this.value=null;" onchange="previewPoster(event);" class="form-control" style="height: unset; padding-left: 6px" accept=".jpg, .png, .jpeg, .bmp" value="{{old('poster')}}">
														</div>
														<div class="col-6">
															<h3 class="my-0 header-title">Poster</h3>
														</div>
														<div class="col-6">
															<button type="button" class="btn btn-outline-primary rounded shadow-sm float-right" onclick="$('#pickPoster').trigger('click');">Browse</button>
														</div>
													</div>
												</div>
												<div class="card-body p-0 rounded">
													<div class="row">
														<div class="col-12 text-center">
															<img id="posterPreview" class="img-fluid" style="max-height: 600px!important; min-height: 600px;"/>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group pb-0 mb-0">
											<div class="card shadow-none bg-white" style="border: 1px solid #ced4da;">
												<div class="card-header">
													<div class="row">
														<div class="d-none">
															<input id="pickBackdrop" type="file" name="poster" onclick="this.value=null;" onchange="previewBackdrop(event);" class="form-control" style="height: unset; padding-left: 6px" accept=".jpg, .png, .jpeg, .bmp" value="{{old('poster')}}">
														</div>
														<div class="col-6">
															<h3 class="my-0 header-title">Backdrop</h3>
														</div>
														<div class="col-6">
															<button type="button" class="btn btn-outline-primary rounded shadow-sm float-right" onclick="$('#pickBackdrop').trigger('click');">Browse</button>
														</div>
													</div>
												</div>
												<div class="card-body p-0 rounded">
													<div class="row">
														<div class="col-12 text-center">
															<img id="backdropPreview" class="img-fluid" style="max-height: 255px!important; min-height: 255px;"/>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="form-row">
							<div class="col-md-6">
								<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
									Save
								</button>
							</div>
							<div class="col-md-6">
								<a href="{{route("admin.videos.index")}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
									Cancel
								</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	<script>
		var lastPoster = null;
		var lastBackdrop = null;
		previewPoster = (event) => {
			const reader = new FileReader();
			reader.onload = function () {
				const output = document.getElementById('posterPreview');
				output.src = reader.result;
			};
			lastPoster = event.target.files[0];
			reader.readAsDataURL(lastPoster);
		};

		previewBackdrop = (event) => {
			const reader = new FileReader();
			reader.onload = function () {
				const output = document.getElementById('backdropPreview');
				output.src = reader.result;
			};
			lastBackdrop = event.target.files[0];
			reader.readAsDataURL(lastBackdrop);
		};

		openImagePicker = () => {
			$('#pickImage').trigger('click');
		}
	</script>
@stop