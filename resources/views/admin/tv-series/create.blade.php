@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Add a Tv/Web Series'])
				</div>
				<form id="uploadForm" action="{{route('admin.tv-series.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-6 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header text-primary font-weight-bold bg-white">
										Type the following details to proceed to next step...
									</div>
									<div class="card-body">
										<div class="form-group">
											<label for="title">Title<span class="text-primary">*</span></label>
											<input id="title" type="text" name="title" class="form-control" required placeholder="Type here the series title" minlength="1" maxlength="256" value="{{old('title')}}"/>
										</div>
										<div class="form-group">
											<label for="duration">Duration<span class="text-primary">*</span></label>
											<input id="duration" pattern="^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$" type="text" name="duration" class="form-control" required placeholder="Type collective duration of series in hh:mm:ss" value="{{old('duration')}}"/>
										</div>
										<div class="form-group">
											<label for="cast">Cast<span class="text-primary">*</span></label>
											<input id="cast" type="text" name="cast" class="form-control" required placeholder="Type here the series' cast(s) name (separate with /)" minlength="1" maxlength="256" value="{{old('cast')}}"/>
										</div>
										<div class="form-group">
											<label for="director">Director<span class="text-primary">*</span></label>
											<input id="director" type="text" name="director" class="form-control" required placeholder="Type here the series' director(s) name (separate with /)" minlength="1" maxlength="256" value="{{old('director')}}"/>
										</div>
										<div class="form-group">
											<label for="description">Overview (Description)<span class="text-primary">*</span></label>
											<textarea id="description" name="description" class="form-control" required placeholder="Type short summary about the series" minlength="1" rows="5" maxlength="2000">{{old('description')}}</textarea>
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
											<input id="rating" type="number" name="rating" class="form-control" required placeholder="Type rating for this series" min="0.00" max="5.00" value="0.00" step="0.01"/>
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
											<input id="price" type="number" name="price" class="form-control" required placeholder="Type price for this series" min="0" max="10000" step="1" readonly/>
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
													<input type="checkbox" class="custom-control-input" id="customCheck2" name="showOnHome">
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
											<label for="rank">Trending rank</label>
											<select id="rank" name="rank" class="form-control">
												<option value="" disabled selected>Choose...</option>
												@for ($i = 1; $i <= 10; $i++)
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
						<div class="row">
							<div class="col-6 mx-auto">
								<div class="row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-primary">
											Save & Proceed
										</button>
									</div>
									<div class="col-6">
										<a href="{{route("admin.genres.index")}}" class="btn btn-secondary waves-effect btn-block shadow-secondary">
											Cancel
										</a>
									</div>
								</div>
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

		// $('#uploadForm').submit(function (event) {
		// 	event.preventDefault();
		// 	const validator = $('#uploadForm').parsley();
		// 	if (!validator.isValid()) {
		// 		alertify.alert('Fix the errors in the form and retry.');
		// 		return;
		// 	}
		// 	if (lastPoster === null) {
		// 		alertify.alert('Movie poster is required.');
		// 		return;
		// 	}
		// 	if (lastBackdrop === null) {
		// 		alertify.alert('Movie backdrop is required.');
		// 		return;
		// 	}
		//
		// 	const config = {
		// 		onUploadProgress: uploadProgress,
		// 		headers: {
		// 			'Content-Type': 'multipart/form-data'
		// 		}
		// 	};
		// 	const formData = new FormData(this);
		// 	modal.modal({
		// 		keyboard: false,
		// 		show: true,
		// 		backdrop: 'static'
		// 	});
		// 	axios.post('/admin/tv-series/store', formData, config,).then(response => {
		// 		const status = response.data.status;
		// 		modal.modal('hide');
		// 		if (status !== 200) {
		// 			alertify.alert(response.data.message);
		// 		} else {
		// 			modalFinal.modal({
		// 				show: true,
		// 				keyboard: false,
		// 				backdrop: 'static'
		// 			});
		// 		}
		// 	}).catch(error => {
		// 		modal.modal('hide');
		// 		console.log(error);
		// 		toastr.error('Something went wrong. Please try again.');
		// 	});
		// });

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