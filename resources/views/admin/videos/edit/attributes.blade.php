@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit TV series details'])
				</div>
				<form id="uploadForm" action="{{route('admin.tv-series.update.attributes',$payload->getKey())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
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
											<input id="title" type="text" name="title" class="form-control" required placeholder="Type here the video/movie title" minlength="1" maxlength="256" value="{{old('title',$payload->getTitle())}}"/>
										</div>
										<div class="form-group">
											<label for="duration">Duration<span class="text-primary">*</span></label>
											<input id="duration" pattern="^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$" type="text" name="duration" class="form-control" required placeholder="Type duration of video in hh:mm:ss" value="{{old('duration',$payload->getDuration())}}"/>
										</div>
										<div class="form-group">
											<label for="cast">Cast<span class="text-primary">*</span></label>
											<input id="cast" type="text" name="cast" class="form-control" required placeholder="Type here the movie's cast name (separate with ,)" minlength="1" maxlength="256" value="{{old('cast',$payload->getCast())}}"/>
										</div>
										<div class="form-group">
											<label for="director">Director<span class="text-primary">*</span></label>
											<input id="director" type="text" name="director" class="form-control" required placeholder="Type here the movie's director's name (separate with ,)" minlength="1" maxlength="256" value="{{old('director',$payload->getDirector())}}"/>
										</div>
										<div class="form-group">
											<label for="description">Overview (Description)<span class="text-primary">*</span></label>
											<textarea id="description" name="description" class="form-control" required placeholder="Type short summary about the movie or video" minlength="1" maxlength="2000">{{old('description',$payload->getDescription())}}</textarea>
										</div>
										<div class="form-group">
											<label for="trailer">Trailer video (choose new to overwrite)<span class="text-primary">*</span></label>
											<input id="trailer" type="file" placeholder="Choose a trailer video file..." name="trailer" class="form-control" style="height: unset; padding-left: 6px">
										</div>
										<div class="form-group">
											<label for="genre">Choose a genre<span class="text-primary">*</span></label>
											<select id="genre" name="genreId" class="form-control" required>
												@foreach($genres as $genre)
													@if(old('genreId',$payload->getGenreId())==$genre->getKey())
														<option value="{{$genre->getKey()}}" selected>{{$genre->getName()}}</option>
													@else
														<option value="{{$genre->getKey()}}">{{$genre->getName()}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label for="released">Release date<span class="text-primary">*</span></label>
											<input id="released" type="date" name="released" class="form-control" required placeholder="Choose or type release date" value="{{old('released',$payload->getReleased())}}"/>
										</div>
										<div class="form-group">
											<label for="rating">Rating<span class="text-primary">*</span></label>
											<input id="rating" type="number" name="rating" class="form-control" required placeholder="Type rating for this movie/video" min="0.00" max="5.00" step="0.01" value="{{old('rating',$payload->getRating())}}"/>
										</div>
										<div class="form-group">
											<label for="pgRating">PG Rating<span class="text-primary">*</span></label>
											<select id="pgRating" name="pgRating" class="form-control" required>
												@switch(old('pgRating',$payload->getPgRating()))
													@case('G')
													<option value="G" selected>G - General audience</option>
													<option value="PG">PG - Parental Guidance advised</option>
													<option value="PG-13">PG-13 - Parental Guidance required (not appropriate for under 13)</option>
													<option value="R">R - Restricted</option>
													<option value="NC-17">NC-17 - No children 17 and under admitted</option>
													@break
													@case('PG')
													<option value="G">G - General audience</option>
													<option value="PG" selected>PG - Parental Guidance advised</option>
													<option value="PG-13">PG-13 - Parental Guidance required (not appropriate for under 13)</option>
													<option value="R">R - Restricted</option>
													<option value="NC-17">NC-17 - No children 17 and under admitted</option>
													@break
													@case('PG-13')
													<option value="G">G - General audience</option>
													<option value="PG">PG - Parental Guidance advised</option>
													<option value="PG-13" selected>PG-13 - Parental Guidance required (not appropriate for under 13)</option>
													<option value="R">R - Restricted</option>
													<option value="NC-17">NC-17 - No children 17 and under admitted</option>
													@break
													@case('R')
													<option value="G">G - General audience</option>
													<option value="PG">PG - Parental Guidance advised</option>
													<option value="PG-13">PG-13 - Parental Guidance required (not appropriate for under 13)</option>
													<option value="R" selected>R - Restricted</option>
													<option value="NC-17">NC-17 - No children 17 and under admitted</option>
													@break
													@case('NC-17')
													<option value="G">G - General audience</option>
													<option value="PG">PG - Parental Guidance advised</option>
													<option value="PG-13">PG-13 - Parental Guidance required (not appropriate for under 13)</option>
													<option value="R">R - Restricted</option>
													<option value="NC-17" selected>NC-17 - No children 17 and under admitted</option>
													@break
												@endswitch
											</select>
										</div>
										<div class="form-group">
											<label for="subscriptionType">Subscription type<span class="text-primary">*</span></label>
											<select id="subscriptionType" name="subscriptionType" class="form-control" required onchange="subscriptionTypeChanged(this.value);">
												@if(old('subscriptionType',$payload->getSubscriptionType())=='free')
													<option value="free" selected>Free</option>
													<option value="paid">Paid</option>
													<option value="subscription">Subscription</option>
												@elseif(old('subscriptionType',$payload->getSubscriptionType())=='paid')
													<option value="free">Free</option>
													<option value="paid" selected>Paid</option>
													<option value="subscription">Subscription</option>
												@else
													<option value="free">Free</option>
													<option value="paid">Paid</option>
													<option value="subscription" selected>Subscription</option>
												@endif
											</select>
										</div>
										<div class="form-group">
											<label for="price">Price<span class="text-primary">*</span></label>
											<input id="price" type="number" name="price" class="form-control" required placeholder="Type price for this movie/video" min="0" max="10000" step="1" @if(old('subscriptionType',$payload->getSubscriptionType())!='paid') readonly @endif value="{{old('price',$payload->getPrice())}}"/>
										</div>
										<div class="form-group">
											<label>Show on homepage?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="customCheck2" name="showOnHome" @if(old('showOnHome',$payload->showOnHome())==true) checked @endif>
													<label class="custom-control-label stretched-link" for="customCheck2">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Mark as trending?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="trending" name="trending" @if(old('trending',$payload->isTrending())==true) checked @endif>
													<label class="custom-control-label" for="trending">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group mb-0">
											<label for="rank">Trending rank</label>
											<select id="rank" name="rank" class="form-control">
												@for ($i = 1; $i <= 10; $i++)
													@if (old('rank',$payload->getRank())==$i)
														<option value="{{$i}}" selected>{{$i}}</option>
													@else
														<option value="{{$i}}">{{$i}}</option>
													@endif
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
															<img id="posterPreview" class="img-fluid" style="max-height: 800px!important; min-height: 800px;" src="{{\Illuminate\Support\Facades\Storage::disk('secured')->url($payload->getPoster())}}"/>
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
															<img id="backdropPreview" class="img-fluid" style="max-height: 298px!important; min-height: 298px;" src="{{\Illuminate\Support\Facades\Storage::disk('secured')->url($payload->getBackdrop())}}"/>
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
							<div class="col-6">
								<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
									Update
								</button>
							</div>
							<div class="col-6 pl-sm-3">
								<a href="{{route("admin.tv-series.index")}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
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
		let manuallyFired = true;

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
			window.location.href = '/admin/tv-series';
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
			if (manuallyFired)
				return;

			event.preventDefault();
			const validator = $('#uploadForm').parsley();
			if (!validator.isValid()) {
				alertify.alert('Fix the errors in the form and retry.');
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
			axios.post('/admin/videos/{{$payload->getKey()}}/attributes', formData, config,).then(response => {
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