@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					<div class="row">
						<div class="col-8">
							<h5 class="page-title animatable">Edit announcement</h5>
						</div>
					</div>
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.announcements.update',$announcement->id)}}" method="POST"
								  data-parsley-validate="true" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label for="banner">Logo</label>
									<input type="file" name="banner" id="banner" data-show-remove="false" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2M" data-default-file="{{$announcement->banner}}">
								</div>
								<div class="form-group">
									<label for="title">Title</label>
									<input id="title" type="text" name="title" class="form-control" required
										   placeholder="Title" minlength="2" maxlength="255" value="{{old('title',$announcement->title)}}"/>
								</div>
								<div class="form-group">
									<label for="description">Content</label>
									<textarea class="form-control" name="content" id="content" cols="30" rows="10" required>
										{{old('content',$announcement->content)}}
									</textarea>
								</div>
								<div class="form-group">
									<label for="valid_from">Valid From</label>
									<input id="valid_from" type="datetime-local" name="validFrom" class="form-control"
										   required placeholder="Valid From" value="{{date("Y-m-d\TH:i:s",strtotime(old('validFrom',$announcement->validFrom)))}}"/>
								</div>
								<div class="form-group">
									<label for="valid_until">Valid Until</label>
									<input id="valid_until" type="datetime-local" name="validUntil" class="form-control"
										   required placeholder="Valid Until" value="{{date("Y-m-d\TH:i:s",strtotime(old('validFrom',$announcement->validUntil)))}}"/>
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-6 pr-0">
											<button type="submit"
													class="btn btn-primary waves-effect waves-light btn-block">
												Save
											</button>
										</div>
										<div class="col-6">
											<a href="{{route("admin.announcements.index")}}"
											   class="btn btn-secondary waves-effect m-l-5 btn-block">
												Cancel
											</a>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script>
		$(document).ready(() => {
			$('#banner').dropify({})
		});
	</script>
@stop
