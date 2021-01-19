@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>"$payload->title"])
				</div>
				<div class="card-body animatable">
					<div class="jumbotron bg-white animated zoomIn" style="background-image: url({{$payload->backdrop}});">
						<div style="background-color: rgba(0,0,0,0.75); border-radius: 8px;" class="row shadow w-100">
							@if($payload->poster!=null)
								<div class="col-auto my-auto">
									<img src="{{$payload->poster}}" alt="" class="img-fluid" style="max-height: 200px;"/>
								</div>
								<div class="col-10">
									<div>
										<h1 class="display-4 text-white">{{$payload->title}}</h1>
										<p class="lead text-white">{{$payload->description}}</p>
									</div>
								</div>
							@else
								<div class="col-12">
									<div>
										<h1 class="display-4 text-white">{{$payload->title}}</h1>
										<p class="lead text-white">{{$payload->description}}</p>
									</div>
								</div>
							@endif
						</div>
					</div>
					<div class="w-100">
						<div class="row">
							<div class="col-sm-6 pr-0">
								<div class="card shadow-none border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Attributes</h5>
										<p class="card-text">Choose this to update attributes such as title, description, duration etc.</p>
										<a href="{{route('admin.videos.edit.attributes',$payload->getKey())}}" class="btn btn-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="card shadow-none border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Media</h5>
										<p class="card-text">Choose this to update media such as poster, backdrop, and trailer.</p>
										<a href="{{route('admin.videos.edit.media',$payload->getKey())}}" class="btn btn-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-sm-4 pr-0">
								<div class="card shadow-none border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Source</h5>
										<p class="card-text">Choose this to update video source for this video.</p>
										<a href="{{route('admin.videos.edit.source',$payload->getKey())}}" class="btn btn-primary @if($payload->isTranscoding()) disabled @endif">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-4 pr-0">
								<div class="card shadow-none border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Audio</h5>
										<p class="card-text">Choose this to update audio sources for this video.</p>
										<a href="{{route('admin.videos.edit.audio',$payload->getKey())}}" class="btn btn-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-4 mr-0">
								<div class="card shadow-none border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Subtitles</h5>
										<p class="card-text">Choose this to update subtitle sources for this video.</p>
										<a href="{{route('admin.videos.edit.subtitle',$payload->getKey())}}" class="btn btn-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="w-100 mt-3">
							<div class="row">
								<div class="col-sm-12">
									<div class="card shadow-none border animated slideInLeft">
										<div class="card-body">
											<h5 class="card-title">Running Queues</h5>
											<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
												<thead>
												<tr>
													<th>#</th>
													<th>Started At</th>
													<th>Ended At</th>
													<th>Progress</th>
													<th>Value</th>
													<th>Status</th>
												</tr>
												</thead>
												<tbody>
												@foreach ($queues as $queue)
													<tr>
														<td>{{($loop->index+1)}}</td>
														<td>{{$queue->started_at??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
														<td>{{$queue->completed_at??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
														@if(empty($queue->completed_at))
															<td>
																<div class="rounded progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: {{$queue->progress}}%; height: 16px;"></div>
															</td>
														@else
															<td>
																<div class="rounded progress-bar progress-bar-striped bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: {{$queue->progress}}%; height: 16px;"></div>
															</td>
														@endif
														<td>{{$queue->progress}}%</td>
														<td>{{$queue->status}}</td>
													</tr>
												@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
@stop