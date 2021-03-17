@extends('admin.app.app')
@section('content')
	<div class="row ">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Videos','action'=>['link'=>route('admin.videos.create'),'text'=>'Add']])
				</div>
                <div class="card-body animatable table-responsive">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="">#</th>
                            <th class="">Poster</th>
                            <th class="">Title</th>
                            <th class="">Description</th>
                            <th class="">Audio</th>
                            <th class="">Subtitles</th>
                            <th class="">Rating</th>
                            <th class="">Trending</th>
                            <th class="">Processing</th>
                            <th class="">Action(s)</th>
                        </tr>
                        </thead>

                        <tbody>
                        <x-blank-table-indicator :data="$videos"/>
                        @foreach($videos as $video)
							<tr id="content_row_{{$video->id}}">
								<td class="">{{$loop->index+1}}</td>
								<td class="">
									@if($video->poster!=null)
										<img src="{{$video->poster}}" style="width: 100px; height: 150px; filter: drop-shadow(2px 2px 8px black)" alt="{{$video->title}}"/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 25px"></i>
									@endif
								</td>
								<td class="">{{$video->title}}</td>
								<td class="">{{\App\Library\Utils\Extensions\Str::ellipsis($video->description,255)}}</td>
								<td>
									@foreach($video->audios as $audio)
										<span class="badge badge-secondary">
											{{$audio->language->name}}
										</span>
									@endforeach
								</td>
								<td>
									@foreach($video->subtitles as $subtitle)
										<span class="badge badge-secondary">
											{{$subtitle->language->name}}
										</span>
									@endforeach
								</td>
								<td class="">{{$video->rating}}</td>
								<td class="">{{\App\Library\Utils\Extensions\Str::stringifyBoolean($video->trending)}}</td>
                                <td class="">
                                    @if($video->isTranscoding())
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                                <td class="">
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-outline-success shadow-sm"
                                               href="{{route('admin.stats.videos.show',$video->id)}}" @include('admin.extras.tooltip.left', ['title' => 'Statistics'])><i
                                                        class="mdi mdi-chart-line"></i></a>
                                            <a class="btn btn-outline-danger shadow-sm"
                                               href="{{route('admin.videos.edit.action',$video->id)}}" @include('admin.extras.tooltip.left', ['title' => 'Edit'])><i
                                                        class="mdi mdi-pencil"></i></a>
                                            @if($video->isTranscoding())
                                                <a class="btn btn-outline-primary shadow-sm disabled"
                                                   href="javascript:void(0);"
                                                   onclick="deleteVideo('{{$video->id}}');" @include('admin.extras.tooltip.right', ['title' => 'This action is unavailable at this time.'])><i
                                                            class="mdi mdi-delete"></i></a>
                                            @else
                                                <a class="btn btn-outline-primary shadow-sm" href="javascript:void(0);"
                                                   onclick="deleteVideo('{{$video->id}}');" @include('admin.extras.tooltip.right', ['title' => 'Delete this video'])><i
                                                            class="mdi mdi-delete"></i></a>
                                            @endif
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