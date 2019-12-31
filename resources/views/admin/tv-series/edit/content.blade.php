@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit series'])
				</div>
				<div class="card-body animatable">
					<div class="row pr-3">
						<div class="col-6"><h4 class="mt-0 header-title ml-3 mb-4">Add videos & seasons</h4></div>
						<div class="col-6">
							<button class="float-right btn btn-outline-primary waves-effect waves-light shadow-sm fadeInRightBig" onclick="addVideoRow();">Add video</button>
						</div>
					</div>
					<form action="" class="p-3" id="form">
						<div id="video_row" class="form-row p-3 border shadow-sm mb-3" style="border-radius: 12px; border-color: #acacac">
							<div class="col-md-1">
								<div class="form-group mb-0">
									<label>Season</label>
									<select name="season" class="form-control" required>
										@for ($i = 1; $i <= 10; $i++)
											<option value="{{$i}}">{{$i}}</option>
										@endfor
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group mb-0">
									<label>Episode number</label>
									<select name="episode" class="form-control" required>
										@for ($i = 1; $i <= 100; $i++)
											<option value="{{$i}}">{{$i}}</option>
										@endfor
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mb-0">
									<label>Language</label>
									<select name="language" class="form-control" required>
										@foreach ($languages as $language)
											<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mb-0">
									<label>Quality</label>
									<select name="quality" class="form-control" required>
										@foreach ($qualities as $quality)
											<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mb-0">
									<label>Video</label>
									<input type="file" id="video" class="form-control w-100 d-flex flex-grow-0" style="padding-left: 4px; padding-top: 4px" required accept="video/*">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		const template = '{{$data}}';

		function addVideoRow() {
			$('#form').append(template);
		}
	</script>
@stop