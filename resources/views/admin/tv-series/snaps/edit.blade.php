@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Tv series - Edit or update snapshots','onClick'=>['link'=>'addRow()','text'=>'Add row']])
				</div>
				<form id="uploadForm" action="{{route('admin.tv-series.update.attributes',$payload->getKey())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body" id="container">

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
		const template = '' +
			'<div class="form-row">' +
			'<div class="col-6">' +
			'<img id="backdropPreview" class="img-fluid" style="max-height: 346px!important; min-height: 346px;" src=""/>' +
			'</div>' +
			'<div class="col-6">' +
			'<img id="backdropPreview" class="img-fluid" style="max-height: 346px!important; min-height: 346px;" src=""/>' +
			'</div>' +
			'</div>';

		/**
		 * Stores the number of image-holder currently visible on page.
		 * @type {number}
		 */
		let currentIndex = 0;

		/**
		 * Holds all the image-holders visible on page.
		 * @type {*[]}
		 */
		let contents = [];

		function addRow() {
			const template = Mustache.render(template, {
				value: 'abc'
			});
			$('#container').append(template);
		}

		loadImage = (event, id) => {
			const reader = new FileReader();
			reader.onload = function () {
				const output = document.getElementById('preview_' + id);
				output.src = reader.result;
			};
			const poster = event.target.files[0];
			reader.readAsDataURL(poster);
		};

		showPicker = (id) => {
			$('#pickImage').trigger('click');
		};
	</script>
@stop