@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Choose edit action'])
				</div>
				<div class="card-body animatable">
					<div class="jumbotron" style="background-image: url({{\Illuminate\Support\Facades\Storage::disk('secured')->url($payload->getBackdrop())}});">
						<div style="background-color: rgba(0,0,0,0.75); border-radius: 8px; backdrop-filter: blur(4px)" class="row p-4 shadow">
							<div class="col-auto my-auto"><img src="{{\Illuminate\Support\Facades\Storage::disk('secured')->url($payload->getPoster())}}" alt="" class="img-fluid" style="max-height: 200px;"/></div>
							<div class="col-10">
								<div>
									<h1 class="display-4 text-white">{{$payload->getTitle()}}</h1>
									<p class="lead text-white">{{$payload->getDescription()}}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="w-100">
						<div class="row">
							<div class="col-sm-6">
								<div class="card shadow-none border">
									<div class="card-body">
										<h5 class="card-title">Tv Series details</h5>
										<p class="card-text">Outer details such title, description, duration etc.</p>
										<a href="{{route('admin.tv-series.edit.attributes',$payload->getKey())}}" class="btn btn-primary shadow-sm">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 mr-0">
								<div class="card shadow-none border">
									<div class="card-body">
										<h5 class="card-title">Media</h5>
										<p class="card-text">Poster, backdrop, trailer can be updated or deleted.</p>
										<a href="{{route('admin.tv-series.edit.media',$payload->getKey())}}" class="btn btn-primary shadow-sm">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-sm-6">
								<div class="card shadow-none border">
									<div class="card-body">
										<h5 class="card-title">Snapshots</h5>
										<p class="card-text">Video snapshots can be added, updated or deleted.</p>
										<a href="{{route('admin.tv-series.edit.snaps',$payload->getKey())}}" class="btn btn-primary shadow-sm">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 mr-0">
								<div class="card shadow-none border">
									<div class="card-body">
										<h5 class="card-title">Content (Videos)</h5>
										<p class="card-text">Choose this to add, edit or update videos under this series.</p>
										<a href="{{route('admin.tv-series.edit.content',$payload->getKey())}}" class="btn btn-primary shadow-sm">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
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
