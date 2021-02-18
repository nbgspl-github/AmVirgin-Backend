@extends('admin.app.app')
@section('styles')
	<link href="{{asset("assets/admin/plugins/summernote/summernote-bs4.css")}}" rel="stylesheet"/>
@endsection
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Create content article'])
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<form action="{{route('admin.news.articles.content.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label for="title">Title<span class="text-primary">*</span></label>
									<input id="title" type="text" name="title" class="form-control" required placeholder="Type title here" minlength="2" maxlength="100" value="{{old('title')}}"/>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-4">
											<label for="category_id">Category<span class="text-primary">*</span></label>
											<select name="category_id" id="category_id" class="form-control selectpicker" title="Choose">
												@foreach($categories as $category)
													<option value="{{$category->id}}">{{$category->name}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-4">
											<label for="author">@required(Author)</label>
											<input id="author" type="text" name="author" class="form-control" minlength="2" maxlength="50">
										</div>
										<div class="col-4">
											<label for="estimated_read">@required(Estimated read)</label>
											<select name="estimated_read" id="estimated_read" class="form-control selectpicker" title="Choose">
												@for($i=1;$i<=120;$i++)
													<option value="{{$i}}">{{$i}} minutes</option>
												@endfor
											</select>
										</div>
									</div>

								</div>
								<div class="form-group">
									<label>@required(Thumbnail)</label>
									<input type="file" name="thumbnail" id="thumbnail" class="form-control" data-max-file-size="2M" data-allowed-file-extensions="jpg png jpeg"/>
								</div>
								<div class="form-group">
									<label for="content">@required(Content)</label>
									<textarea name="content" id="content" cols="30" rows="10"></textarea>
								</div>
								<div class="form-group">
									<div class="custom-control custom-checkbox mr-sm-2">
										<input type="checkbox" name="publish" class="custom-control-input" id="publish">
										<label class="custom-control-label" for="publish">Publish?</label>
									</div>
								</div>
								<div class="form-row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
											Save
										</button>
									</div>
									<div class="col-6">
										<a href="{{route("admin.news.articles.index")}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
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
			$('#thumbnail').dropify();
			$('#content').summernote({
				height: 300,
				toolbar: [
					['style', ['style']],
					['font', ['bold', 'underline', 'clear']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['table', ['table']],
					['insert', ['link']],
					['view', ['fullscreen', 'codeview', 'help']]
				]
			});
		})
	</script>
@stop