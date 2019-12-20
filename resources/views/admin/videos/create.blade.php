@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Add a video'])
				</div>
				<form id="uploadForm" action="{{route('admin.videos.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card shadow-none" style="border: 1px solid #b4b9bf;">
									<div class="card-header text-primary font-weight-bold bg-white">
										Attributes
									</div>
									<div class="card-body">
										<div class="form-group">
											<label for="title">Title<span class="text-primary">*</span></label>
											<input id="title" type="text" name="title" class="form-control" required placeholder="Type here the movie's title" minlength="1" maxlength="256" value="{{old('title')}}"/>
										</div>
										<div class="form-group">
											<label for="movieDBId">TheMovieDB Id<span class="text-primary">*</span></label>
											<input id="movieDBId" type="text" name="movieDBId" class="form-control" required placeholder="Type reference number from TheMovieDB" minlength="1" maxlength="100" value="{{old('movieDBId')}}"/>
										</div>
										<div class="form-group">
											<label for="imdbId">IMDB Id<span class="text-primary">*</span></label>
											<input id="imdbId" type="text" name="imdbId" class="form-control" required placeholder="Type reference number from IMDB" minlength="1" maxlength="100" value="{{old('imdbId')}}"/>
										</div>
										<div class="form-group">
											<label for="description">Overview (Description)<span class="text-primary">*</span></label>
											<textarea id="description" name="description" class="form-control" required placeholder="Type short summary about the movie or video" minlength="1" maxlength="2000">{{old('description')}}</textarea>
										</div>
										<div class="form-group">
											<label for="genre">Choose a genre<span class="text-primary">*</span></label>
											<select id="genre" name="genreId" class="form-control" required>
												<option value="0">Choose...</option>
												@foreach($genres as $genre)
													@if(old('genreId',-1)==$genre->getKey())
														<option value="{{$genre->getKey()}}" selected>{{$genre->getName()}}</option>
													@else
														<option value="{{$genre->getKey()}}">{{$genre->getName()}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label for="releaseDate">Release date<span class="text-primary">*</span></label>
											<input id="releaseDate" type="date" name="releaseDate" class="form-control" required placeholder="Choose or type release date" minlength="1" maxlength="100" value="2016"/>
										</div>
										<div class="form-group">
											<label for="averageRating">Average rating<span class="text-primary">*</span></label>
											<input id="averageRating" type="number" name="averageRating" class="form-control" required placeholder="Type average rating for this movie/video" minlength="1" maxlength="100" value="63"/>
										</div>
										<div class="form-group">
											<label for="votes">Votes<span class="text-primary">*</span></label>
											<input id="votes" type="number" name="votes" class="form-control" required placeholder="Type votes given to this movie/video" minlength="1" maxlength="100" value="45947"/>
										</div>
										<div class="form-group">
											<label for="popularity">Popularity<span class="text-primary">*</span></label>
											<input id="popularity" type="number" name="popularity" class="form-control" required placeholder="Type popularity of this movie/video" minlength="1" maxlength="100" value="66"/>
										</div>
										<div class="form-group">
											<label for="server">Choose a server<span class="text-primary">*</span></label>
											<select id="server" name="serverId" class="form-control" required>
												<option value="">Choose...</option>
												@foreach($servers as $server)
													<option value="{{$server->getKey()}}">{{$server->getName()}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label for="language">Language<span class="text-primary">*</span></label>
											<select id="language" name="mediaLanguageId" class="form-control" required>
												<option value="">Choose...</option>
												@foreach($languages as $language)
													<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label for="mediaQuality">Media quality<span class="text-primary">*</span></label>
											<select id="mediaQuality" name="mediaQualityId" class="form-control" required>
												<option value="">Choose...</option>
												@foreach($qualities as $quality)
													<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
												@endforeach
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
								<div class="card shadow-none" style="border: 1px solid #b4b9bf;">
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
															<h3 class="my-0 header-title">Poster <span class="text-warning">(Max 3MB)</span></h3>
														</div>
														<div class="col-6">
															<button type="button" class="btn btn-outline-primary rounded shadow-sm float-right" onclick="$('#pickPoster').trigger('click');">Browse</button>
														</div>
													</div>
												</div>
												<div class="card-body p-0 rounded">
													<div class="row">
														<div class="col-12 text-center">
															<img id="posterPreview" class="img-fluid" style="max-height: 684px!important; min-height: 684px;"/>
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
															<input id="pickBackdrop" type="file" name="backdrop" onclick="this.value=null;" onchange="previewBackdrop(event);" class="form-control" style="height: unset; padding-left: 6px" accept=".jpg, .png, .jpeg, .bmp" value="{{old('backdrop')}}">
														</div>
														<div class="col-6">
															<h3 class="my-0 header-title">Backdrop <span class="text-warning">(Max 2MB)</span></h3>
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
						<div class="row mt-4">
							<div class="col-12">
								<div class="card shadow-none" style="border: 1px solid #b4b9bf;">
									<div class="card-header text-primary font-weight-bold bg-white">
										Video
									</div>
									<div class="card-body">
										<div class="form-group">
											<label for="previewUrl">Preview URL</label>
											<input id="previewUrl" type="url" name="previewUrl" class="form-control" required placeholder="Type or paste preview url here" minlength="1" maxlength="100" value="http://hrms.zobofy.com/"/>
										</div>
										<div class="form-group">
											<label for="externalLink">External Link</label>
											<input id="externalLink" type="url" name="externalLink" class="form-control" placeholder="Enter a url to HLS/M3U8/MP4/WEBM/OGV/FLV/EMBED/IFRAME" minlength="1" maxlength="100" value="http://hrms.zobofy.com/"/>
										</div>
										<div class="form-group">
											<div class="text-center"><span class="badge badge-primary text-white font-weight-bold text-center font-16 text-capitalize">or</span></div>
										</div>
										<div class="form-group">
											<label for="video">Video file <span class="text-warning">(Max 2GB)</span></label>
											<input id="video" type="file" placeholder="Choose a video..." name="video" class="form-control" style="height: unset; padding-left: 6px">
										</div>
										<div class="form-group">
											<label>Show on homepage?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="customCheck2" name="visibleOnHome">
													<label class="custom-control-label stretched-link" for="customCheck2">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Mark as trending?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="trending" name="trending">
													<label class="custom-control-label" for="trending">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group mb-0">
											<label for="trendingRank">Trending rank</label>
											<select id="trendingRank" name="trendingRank" class="form-control">
												<option value="">Choose...</option>
												@for ($i = 1; $i < 11; $i++)
													<option value="{{$i}}">{{$i}}</option>
												@endfor
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="form-row">
							<div class="col-md-6 pr-3">
								<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
									Save
								</button>
							</div>
							<div class="col-md-6 pl-3">
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
		let lastPoster = null;
		let lastBackdrop = null;
		let progressRing = null;
		let progressPercent = null;
		let CancelToken = axios.CancelToken;
		let source = CancelToken.source();
		let modal = null;
		let modalFinal = null;

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
		};

		function uploadProgress(progressEvent) {
			let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
			let percentCompletedValue = (percentCompleted / 100.0);
			console.log('Percentage is ' + percentCompletedValue);
			progressPercent.text(percentCompleted + ' %');
			progressRing.circleProgress({
				value: percentCompletedValue
			});
		}

		function finishUpload() {
			window.location.href = '/admin/videos';
		}

		$(document).ready(function () {
			progressRing = $('#progressCircle');
			progressPercent = $('#progressPercent');
			progressRing.circleProgress({
				value: 0.0,
				animation: false,
				fill: {
					color: '#cf3f43'
				}
			});
			modal = $('#progressModal');
			modalFinal = $('#okayBox');
			$('#trending').change(function () {
				if (this.checked) {
					$('#trendingRank').prop("required", true);
				} else {
					$('#trendingRank').prop("required", false);
				}
			});
		});

		$('#uploadForm').submit(function (event) {
			event.preventDefault();
			const validator = $('#uploadForm').parsley();
			if (!validator.isValid()) {
				alertify.alert('Fix the errors in the form and retry.');
				return;
			}
			if (lastPoster === null) {
				alertify.alert('Movie poster is required.');
				return;
			}
			if (lastBackdrop === null) {
				alertify.alert('Movie backdrop is required.');
				return;
			}

			const config = {
				onUploadProgress: uploadProgress,
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			};
			const formData = new FormData(this);
			modal.modal({
				keyboard: false,
				show: true,
				backdrop: 'static'
			});
			axios.post('/admin/videos/store', formData, config,).then(response => {
				const status = response.data.status;
				modal.modal('hide');
				if (status !== 200) {
					alertify.alert(response.data.message);
				} else {
					modalFinal.modal({
						show: true,
						keyboard: false,
						backdrop: 'static'
					});
				}
			}).catch(error => {
				modal.modal('hide');
				console.log(error);
				toastr.error('Something went wrong. Please try again.');
			});
		});
	</script>
@stop