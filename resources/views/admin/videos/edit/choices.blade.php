@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Choose edit action'])
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
										<a href="{{route('admin.videos.edit.attributes',$payload->getKey())}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 mr-0">
								<div class="card shadow-none border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Media</h5>
										<p class="card-text">Choose this to update media such as poster, backdrop, and trailer.</p>
										<a href="{{route('admin.videos.edit.media',$payload->getKey())}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-sm-6 pr-0">
								<div class="card shadow-none border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Snapshots</h5>
										<p class="card-text">Choose this to update or delete video snapshots.</p>
										<a href="{{route('admin.videos.edit.snaps',$payload->getKey())}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 mr-0">
								<div class="card shadow-none border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Sources</h5>
										<p class="card-text">Choose this to update video sources for this video.</p>
										<a href="{{route('admin.videos.edit.source',$payload->getKey())}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-sm-6 pr-0">
								<div class="card shadow-none border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Audio</h5>
										<p class="card-text">Choose this to update audio sources for this video.</p>
										<a href="{{route('admin.videos.edit.audio',$payload->getKey())}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 mr-0">
								<div class="card shadow-none border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Subtitles</h5>
										<p class="card-text">Choose this to update subtitle sources for this video.</p>
										<a href="{{route('admin.videos.edit.subtitle',$payload->getKey())}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
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