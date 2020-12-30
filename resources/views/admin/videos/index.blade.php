@extends('admin.app.app')
@section('content')
	<div class="row ">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Videos','action'=>['link'=>route('admin.videos.create'),'text'=>'Add']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Poster</th>
							<th class="text-center">Title</th>
							<th class="text-center">Description</th>
							<th class="text-center">Rating</th>
							<th class="text-center">Trending</th>
							<th class="text-center">Pending</th>
							<th class="text-center">Action(s)</th>
						</tr>
						</thead>

						<tbody>
						@foreach($videos as $video)
							<tr id="content_row_{{$video->id}}">
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">
									@if($video->poster!=null)
										<img src="{{$video->poster}}" style="width: 100px; height: 150px; filter: drop-shadow(2px 2px 8px black)" alt="{{$video->title}}"/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 25px"></i>
									@endif
								</td>
								<td class="text-center">{{$video->title}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::ellipsis($video->description,50)}}</td>
								<td class="text-center">{{$video->rating}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::stringifyBoolean($video->trending)}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::stringifyBoolean($video->pending)}}</td>
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger shadow-sm" href="{{route('admin.videos.edit.action',$video->id)}}" @include('admin.extras.tooltip.left', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary shadow-sm" href="javascript:void(0);" onclick="deleteVideo('{{$video->id}}');" @include('admin.extras.tooltip.right', ['title' => 'Delete this video'])><i class="mdi mdi-delete"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{$videos->links()}}
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		deleteVideo = key => {
			alertify.confirm("Are you sure? This action is irreversible!",
				yes => {
					axios.delete(`/admin/videos/${key}`).then(response => {
						location.reload();
					}).catch(e => {
						alertify.confirm('Something went wrong. Retry?', yes => {
							deleteVideo(key);
						});
					});
				}
			)
		}
	</script>
@stop