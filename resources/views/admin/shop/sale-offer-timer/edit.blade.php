@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	@include('admin.modals.durationPicker',['title'=>'Choose countdown duration'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Modify Sale Offer Timer Details'])
				</div>
				<form id="uploadForm" action="{{route('admin.shop.sale-offer-timer.update')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-body">
										<div class="form-group">
											<label for="title">@required (Title)</label>
											<input id="title" type="text" name="title" class="form-control" required placeholder="Type section title here" minlength="1" maxlength="50" value="{{old('title',$payload->title)}}"/>
										</div>
										<div class="form-group">
											<label for="countDown">@required (CountDown duration)</label>
											<input id="countDown" type="text" name="countDown" class="form-control" required placeholder="Choose duration" value="{{old('duration',$payload->countDown)}}"/>
										</div>
										<div class="form-group">
											<label for="statements">@required (Marquee Text (upto 3 short statements))</label>
											<textarea id="statements" name="statements" class="form-control" required placeholder="Type short summary about the movie or video" rows="3" minlength="1" maxlength="100">{{$payload->statements}}</textarea>
										</div>
										<div class="form-group mb-0">
											<label>Visible?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="customCheck2" name="visible" @if($payload->visible) checked @endif>
													<label class="custom-control-label stretched-link" for="customCheck2">Yes</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-primary">
											Save
										</button>
									</div>
									<div class="col-6">
										<a href="{{route('admin.shop.choices')}}" class="btn btn-secondary waves-effect btn-block shadow-secondary">
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
	<script src="{{asset('assets/admin/utils/DurationPicker.js')}}"></script>
	<script>
		$(document).ready(function () {
			setupDurationPicker({
				modalId: 'durationPicker',
				durationId: 'countDown'
			});
		});
	</script>
@stop