@extends('admin.app.app')
@section('styles')
	<link href="{{asset("assets/admin/plugins/summernote/summernote-bs4.css")}}" rel="stylesheet"/>
@endsection
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Privacy Policy'])
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<form action="{{route('admin.extras.privacy-policy.update')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<textarea name="privacy_policy" id="privacy_policy" cols="30" rows="10">{{old('privacy_policy',$privacy_policy)}}</textarea>
								</div>
								<div class="form-row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
											Update
										</button>
									</div>
									<div class="col-6">
										<a href="{{route("admin.home")}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
											Cancel
										</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	<script src="{{asset("assets/admin/plugins/summernote/summernote-bs4.min.js")}}"></script>
	<script>
		$(document).ready(() => {
			$('#privacy_policy').summernote({
				height: 600,
				toolbar: [
					['style', ['style']],
					['font', ['bold', 'underline', 'clear']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['table', ['table']],
					['insert', ['link', 'picture']],
					['view', ['fullscreen', 'codeview', 'help']]
				]
			});
		})
	</script>
@stop