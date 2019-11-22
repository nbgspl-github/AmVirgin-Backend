@extends('layouts.header')
@section('content')
	@include('layouts.breadcrumbs', ['data' => ['Genres'=>route('genres.all')]])
	<div class="row">
		<div class="col-12">
			<div class="card m-b-30">
				<div class="card-body px-0 pb-3">
					<div class="row pr-3">
						<div class="col-6"><h4 class="mt-0 header-title ml-3 mb-4">All Genres</h4></div>
						<div class="col-6"><a class="float-right btn btn-outline-primary waves-effect waves-light shadow-sm fadeInRightBig" href="{{route('genres.new')}}">Add Genre</a></div>
					</div>
					<table id="datatable" class="table table-bordered dt-responsive nowrap pr-0 pl-0" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center">Poster</th>
							<th class="text-center">Name</th>
							<th class="text-center">Description</th>
							<th class="text-center">Active</th>
							<th class="text-center">Action(s)</th>
						</tr>
						</thead>

						<tbody>
						@foreach($genres as $genre)
							<tr>
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">
									@if($genre->getPoster()!=null)
										<img src="{{$genre->getPoster()}}" style="width: 100px; height: 100px" alt="{{$genre->getName()}}" @include('extras.tooltip.right', ['title' => $genre->getName()])/>
									@else
										<i class="mdi mdi-close-box-outline text-muted" style="font-size: 90px"></i>
									@endif
								</td>
								<td class="text-center">{{$genre->getName()}}</td>
								<td class="text-center">{{$genre->getDescription()}}</td>
								<td class="text-center">
									<div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
										@if($genre->getStatus()==true)
											<label class="btn btn-outline-danger active">
												<input type="radio" name="options" id="option2" onchange="toggleStatus('{{$genre->getId()}}',true);"/> On
											</label>
											<label class="btn btn-outline-primary">
												<input type="radio" name="options" id="option3" onchange="toggleStatus('{{$genre->getId()}}',false);"/> Off
											</label>
										@else
											<label class="btn btn-outline-danger">
												<input type="radio" name="options" id="option2" onchange="toggleStatus('{{$genre->getId()}}',true);"/> On
											</label>
											<label class="btn btn-outline-primary active">
												<input type="radio" name="options" id="option3" onchange="toggleStatus('{{$genre->getId()}}',false);"/> Off
											</label>
										@endif
									</div>
								</td>
								<td class="text-center">
									<div class="row">
										<div class="col-6">
											<a class="btn btn-outline-danger shadow-sm shadow-danger" href="" @include('extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
										</div>
										<div class="col-6">
											<a class="btn btn-outline-primary shadow-sm shadow-primary" href="" @include('extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>

					</table>

				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		$(document).ready(function () {
			$('#datatable').DataTable();
		});

		toggleStatus = (id, state) => {
			console.log('Called');
			showLoader();
			$.ajax({
				type: "PUT",
				url: '{{route('genres.update.status')}}',
				data: {id: id, status: state},
				dataType: "json",
				success: function (data) {
					hideLoader();
					console.log(data);
				},
				failed: function (d) {
					hideLoader();
					console.log(d);
				}
			});
			{{--axios.put('{{route('genres.update.status')}}').then(response => {--}}
			{{--	hideLoader();--}}
			{{--	console.log(response);--}}
			{{--}).catch(reason => {--}}
			{{--	hideLoader();--}}
			{{--	console.log(reason);--}}
			{{--});--}}
		}
	</script>
@stop