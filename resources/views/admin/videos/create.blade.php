@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Add a video/movie'])
				</div>
				<form id="uploadForm" action="{{route('admin.videos.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-md-12 col-lg-6 col-xl-6 mr-0 pr-0">
								<div class="card shadow-none" style="border: 1px solid #b4b9bf;">
									<div class="card-header text-primary font-weight-bold bg-white">
										Attributes
									</div>
									<div class="card-body">
										<div class="form-group">
											<label for="title">Title<span class="text-primary">*</span></label>
											<input id="title" type="text" name="title" class="form-control" required placeholder="Type here the video/movie title" minlength="1" maxlength="256" value="{{old('title')}}"/>
										</div>
										<div class="form-group">
											<label for="duration">Duration<span class="text-primary">*</span></label>
											<input id="duration" pattern="^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$" type="text" name="duration" class="form-control" required placeholder="Type duration of video in hh:mm:ss" value="{{old('title')}}"/>
										</div>
										<div class="form-group">
											<label for="cast">Cast<span class="text-primary">*</span></label>
											<input id="cast" type="text" name="cast" class="form-control" required placeholder="Type here the movie's cast name" minlength="1" maxlength="256" value="{{old('title')}}"/>
										</div>
										<div class="form-group">
											<label for="director">Director<span class="text-primary">*</span></label>
											<input id="director" type="text" name="director" class="form-control" required placeholder="Type here the movie's director's name" minlength="1" maxlength="256" value="{{old('title')}}"/>
										</div>
										<div class="form-group">
											<label for="description">Overview (Description)<span class="text-primary">*</span></label>
											<textarea id="description" name="description" class="form-control" required placeholder="Type short summary about the movie or video" minlength="1" maxlength="2000">{{old('description')}}</textarea>
										</div>
										<div class="form-group">
											<label for="trailer">Trailer video<span class="text-primary">*</span></label>
											<input id="trailer" type="file" placeholder="Choose a trailer video file..." name="trailer" class="form-control" style="height: unset; padding-left: 6px" required>
										</div>
										<div class="form-group">
											<label for="genre">Choose a genre<span class="text-primary">*</span></label>
											<select id="genre" name="genreId" class="form-control" required>
												<option value="" disabled selected>Choose...</option>
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
											<label for="released">Release date<span class="text-primary">*</span></label>
											<input id="released" type="date" name="released" class="form-control" required placeholder="Choose or type release date"/>
										</div>
										<div class="form-group">
											<label for="rating">Rating<span class="text-primary">*</span></label>
											<input id="rating" type="number" name="rating" class="form-control" required placeholder="Type rating for this movie/video" min="0.00" max="5.00" value="0.00" step="0.01"/>
										</div>
										<div class="form-group">
											<label for="pgRating">PG Rating<span class="text-primary">*</span></label>
											<select id="pgRating" name="pgRating" class="form-control" required>
												<option value="" disabled selected>Choose...</option>
												<option value="G">G - General audience</option>
												<option value="PG">PG - Parental Guidance advised</option>
												<option value="PG-13">PG-13 - Parental Guidance required (not appropriate for under 13)</option>
												<option value="R">R - Restricted</option>
												<option value="NC-17">NC-17 - No children 17 and under admitted</option>
											</select>
										</div>
										<div class="form-group">
											<label for="subscriptionType">Subscription type<span class="text-primary">*</span></label>
											<select id="subscriptionType" name="subscriptionType" class="form-control" required onchange="subscriptionTypeChanged(this.value);">
												<option value="" disabled selected>Choose...</option>
												<option value="free">Free</option>
												<option value="paid">Paid</option>
												<option value="subscription">Subscription</option>
											</select>
										</div>
										<div class="form-group">
											<label for="price">Price<span class="text-primary">*</span></label>
											<input id="price" type="number" name="price" class="form-control" required placeholder="Type price for this movie/video" min="0.00" max="10000.00" step="0.01" readonly/>
										</div>
										<div class="form-group">
											<label>Push notify customers?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="customCheck">
													<label class="custom-control-label" for="customCheck">Notify</label>
												</div>
											</div>
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
												<option value="" disabled selected>Choose...</option>
												@for ($i = 1; $i <= 10; $i++)
													<option value="{{$i}}">{{$i}}</option>
												@endfor
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-6 col-xl-6 mt-3 mt-sm-0">
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
															<h3 class="my-0 header-title">Poster <span class="text-warning">(Max 5MB)</span></h3>
														</div>
														<div class="col-6">
															<button type="button" class="btn btn-outline-primary rounded shadow-sm float-right" onclick="$('#pickPoster').trigger('click');">Browse</button>
														</div>
													</div>
												</div>
												<div class="card-body p-0 rounded">
													<div class="row">
														<div class="col-12 text-center">
															<img id="posterPreview" class="img-fluid" style="max-height: 800px!important; min-height: 800px;"/>
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
															<h3 class="my-0 header-title">Backdrop <span class="text-warning">(Max 5MB)</span></h3>
														</div>
														<div class="col-6">
															<button type="button" class="btn btn-outline-primary rounded shadow-sm float-right" onclick="$('#pickBackdrop').trigger('click');">Browse</button>
														</div>
													</div>
												</div>
												<div class="card-body p-0 rounded">
													<div class="row">
														<div class="col-12 text-center">
															<img id="backdropPreview" class="img-fluid" style="max-height: 367px!important; min-height: 367px;"/>
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
										Video (Minimum required is 1)
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label for="videoA">(1.) Video file <span class="text-warning">(Max 2GB)<span class="text-primary">*</span></span></label>
													<input id="videoA" type="file" placeholder="Choose a video..." name="videoA" class="form-control" style="height: unset; padding-left: 6px" required>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="languageA">Language<span class="text-primary">*</span></label>
													<select id="languageA" name="mediaLanguageIdA" class="form-control" required>
														<option value="" disabled selected>Choose...</option>
														@foreach($languages as $language)
															@if ($language->getCode()=='en')
																<option value="{{$language->getKey()}}" selected>{{$language->getName()}}</option>
															@else
																<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
															@endif
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="mediaQualityA">Media quality<span class="text-primary">*</span></label>
													<select id="mediaQualityA" name="mediaQualityIdA" class="form-control" required>
														<option value="" disabled selected>Choose...</option>
														@foreach($qualities as $quality)
															<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label for="videoB">(2.) Video file <span class="text-warning">(Max 2GB)</span></label>
													<input id="videoB" type="file" placeholder="Choose a video..." name="videoB" class="form-control" style="height: unset; padding-left: 6px">
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="languageB">Language</label>
													<select id="languageB" name="mediaLanguageIdB" class="form-control">
														<option value="" disabled selected>Choose...</option>
														@foreach($languages as $language)
															@if ($language->getCode()=='hi')
																<option value="{{$language->getKey()}}" selected>{{$language->getName()}}</option>
															@else
																<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
															@endif
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="mediaQualityB">Media quality</label>
													<select id="mediaQualityB" name="mediaQualityIdB" class="form-control">
														<option value="" disabled selected>Choose...</option>
														@foreach($qualities as $quality)
															<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label for="videoC">(3.) Video file <span class="text-warning">(Max 2GB)</span></label>
													<input id="videoC" type="file" placeholder="Choose a video..." name="videoC" class="form-control" style="height: unset; padding-left: 6px">
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="languageC">Language</label>
													<select id="languageC" name="mediaLanguageIdC" class="form-control">
														<option value="" disabled selected>Choose...</option>
														@foreach($languages as $language)
															<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="mediaQualityC">Media quality</label>
													<select id="mediaQualityC" name="mediaQualityIdC" class="form-control">
														<option value="" disabled selected>Choose...</option>
														@foreach($qualities as $quality)
															<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label for="videoD">(4.) Video file <span class="text-warning">(Max 2GB)</span></label>
													<input id="videoD" type="file" placeholder="Choose a video..." name="videoD" class="form-control" style="height: unset; padding-left: 6px">
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="languageD">Language</label>
													<select id="languageD" name="mediaLanguageIdD" class="form-control">
														<option value="">Choose...</option>
														@foreach($languages as $language)
															<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="mediaQualityD">Media quality</label>
													<select id="mediaQualityD" name="mediaQualityIdD" class="form-control">
														<option value="">Choose...</option>
														@foreach($qualities as $quality)
															<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label for="video">(5.) Video file <span class="text-warning">(Max 2GB)</span></label>
													<input id="videoE" type="file" placeholder="Choose a video..." name="videoE" class="form-control" style="height: unset; padding-left: 6px">
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="languageE">Language</label>
													<select id="languageE" name="mediaLanguageIdE" class="form-control">
														<option value="" disabled selected>Choose...</option>
														@foreach($languages as $language)
															<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="mediaQualityE">Media quality</label>
													<select id="mediaQualityE" name="mediaQualityIdE" class="form-control">
														<option value="" disabled selected>Choose...</option>
														@foreach($qualities as $quality)
															<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
														@endforeach
													</select>
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
							<div class="col-6">
								<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
									Save
								</button>
							</div>
							<div class="col-6 pl-sm-3">
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

		function subscriptionTypeChanged(type) {
			const elem = $('#price');
			if (type === 'free' || type === 'subscription') {
				elem.attr('readonly', true);
				elem.prop('required', false);
			} else {
				elem.attr('readonly', false);
				elem.prop('required', true);
			}
		}
	</script>
@stop