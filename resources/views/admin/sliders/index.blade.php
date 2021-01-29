@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Sliders','action'=>['link'=>route('admin.sliders.create'),'text'=>'Add']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Banner</th>
							<th class="text-center">Title</th>
							<th class="text-center">Description</th>
							<th class="text-center">Rating</th>
							<th class="text-center">Active</th>
							<th class="text-center">Type</th>
							<th class="text-center">Target</th>
							<th class="text-center">Action(s)</th>
						</tr>
						</thead>

						<tbody>
						@foreach($slides as $slide)
							<tr id="genre_row_{{$slide->id}}">
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">
									@if($slide->banner!=null)
										<img src="{{$slide->banner}}" style="width: 100px; height: 60px" alt="{{$slide->title}}"/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 90px"></i>
									@endif
								</td>
								<td class="text-center">{{$slide->title}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::ellipsis($slide->description,100)}}</td>
								<td class="text-center">{{$slide->rating??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
								<td class="text-center">
									<div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
										@if($slide->active==true)
											<label class="btn btn-outline-danger active" @include('admin.extras.tooltip.left', ['title' => 'Set slider active'])>
												<input type="radio" name="options" id="optionOn_{{$slide->id}}" onchange="_status('{{$slide->id}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary" @include('admin.extras.tooltip.right', ['title' => 'Set slider inactive'])>
												<input type="radio" name="options" id="optionOff_{{$slide->id}}" onchange="_status('{{$slide->id}}',0);"/> Off
											</label>
										@else
											<label class="btn btn-outline-danger" @include('admin.extras.tooltip.left', ['title' => 'Set slider active'])>
												<input type="radio" name="options" id="optionOn_{{$slide->id}}" onchange="_status('{{$slide->id}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary active" @include('admin.extras.tooltip.right', ['title' => 'Set slider inactive'])>
												<input type="radio" name="options" id="optionOff_{{$slide->id}}" onchange="_status('{{$slide->id}}',0);"/> Off
											</label>
										@endif
									</div>
								</td>
								<td class="text-center">{{$slide->type()}}</td>
								@if($slide->type==\App\Models\Slider::TargetType['ExternalLink'])
									<td class="text-center">
										<a class="btn btn-outline-secondary waves-effect waves-light shadow-sm fadeInRightBig" target="_blank" href="{{$slide->target}}">{{\App\Library\Utils\Extensions\Str::ellipsis($slide->target)}}</a>
									</td>
								@else
									<td class="text-center">
										@php
											$video=\App\Models\Video\Video::find($slide->target);
										@endphp
										<button class="btn btn-outline-secondary waves-effect waves-light shadow-sm fadeInRightBig">{{$video->title??\App\Library\Utils\Extensions\Str::NotAvailable}}</button>
									</td>
								@endif
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger" href="{{route('admin.sliders.edit',$slide->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary" href="javascript:_delete('{{$slide->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
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
		$(document).ready(() => {

		});

		/**
		 * Returns route for Genre/Update/Status route.
		 * @param id
		 * @returns {string}
		 */
		updateStatusRoute = (id) => {
			return 'sliders/' + id + '/status';
		};

		/**
		 * Returns route for Genre/Delete route.
		 * @param id
		 * @returns {string}
		 */
		deleteSlideRoute = (id) => {
			return 'sliders/' + id;
		};

		_status = (key, state) => {
			axios.put(`/admin/sliders/${key}/status`, {active: state})
				.then(response => {
					alertify.alert(response.data.message, () => {
						location.reload();
					});
				})
				.catch(e => {
					alertify.confirm('Something went wrong. Retry?', yes => {
						_delete(key);
					});
				});
		}

		_delete = (key) => {
			alertify.confirm("This action is irreversible. Proceed?",
				(yes) => {
					axios.delete(`/admin/sliders/${key}}`)
						.then(response => {
							alertify.alert(response.data.message, () => {
								location.reload();
							});
						}).catch(e => {
						alertify.confirm('Something went wrong. Retry?', yes => {
							_delete(key);
						});
					});
				}
			);
		}
	</script>
@stop