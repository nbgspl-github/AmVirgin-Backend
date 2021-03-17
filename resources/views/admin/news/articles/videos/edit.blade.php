@extends('admin.app.app')
@section('styles')

@endsection
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit video article'])
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<form id="videoForm" action="{{route('admin.news.articles.videos.update',$article->id)}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label>@required(Thumbnail)</label>
									<input type="file" name="thumbnail" id="thumbnail" data-default-file="{{$article->thumbnail}}" class="form-control" data-max-file-size="2M" data-allowed-file-extensions="jpg png jpeg"/>
								</div>
								<div class="form-group">
									<label for="title">Title<span class="text-primary">*</span></label>
									<input id="title" type="text" name="title" class="form-control" required placeholder="Type title here" minlength="2" maxlength="100" value="{{old('title',$article->title)}}"/>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="category_id">Category<span class="text-primary">*</span></label>
                                            <select name="category_id" id="category_id" class="form-control"
                                                    title="Choose">
                                                @foreach($categories as $category)
													<option value="{{$category->id}}" @if($category->id==$article->category_id) selected @endif>{{$category->name}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-6">
											<label for="author">@required(Author)</label>
											<input id="author" type="text" name="author" class="form-control" minlength="2" maxlength="50" value="{{old('author',$article->author)}}">
										</div>
									</div>

								</div>
								<div class="form-group">
									<label>@required(Video)</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="video" name="video" accept=".mp4, .mkv">
										<label class="custom-file-label" for="video">{{$article->video}}</label>
										<small class="text-muted">Choose new to replace previous</small>
									</div>
								</div>
								<div class="form-group">
									<div class="custom-control custom-checkbox mr-sm-2">
										<input type="checkbox" name="publish" class="custom-control-input" id="publish" @if($article->published) checked @endif>
										<label class="custom-control-label" for="publish">Publish?</label>
									</div>
								</div>
								<div class="form-row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
											Update
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
	<script>
		const articleId = `{{$article->id}}`;
		$(document).on('change', '.custom-file-input', function (event) {
			$(this).next('.custom-file-label').html(event.target.files[0].name);
		})
		$(document).ready(() => {
			$('#thumbnail').dropify();
			$('#videoForm').submit(function (event) {
				event.preventDefault();
				submitSource(this);
			});
		});

		function submitSource(event) {
			const config = {
				onUploadProgress: uploadProgress,
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			};
			const formData = new FormData(event);
			showProgressDialog(true, () => {
				axios.post(`/admin/news/articles/videos/${articleId}`, formData, config,).then(response => {
					showProgressDialog(false);
					alertify.alert(response.data.message, () => {
						location.href = `/admin/news/articles`;
					});
				}).catch(error => {
					showProgressDialog(false);
					alertify.alert('Something went wrong. Please try again.');
				});
			});
		}

		uploadProgress = (event) => {
			let percentCompleted = Math.round((event.loaded * 100) / event.total);
			setProgress(percentCompleted);
		}
	</script>
@stop