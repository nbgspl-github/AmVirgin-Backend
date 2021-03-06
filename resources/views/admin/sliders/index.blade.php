@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row">
                        <div class="col-8 w-100">
                            <h5 class="page-title animatable">Sliders</h5>
                        </div>
                        <div class="col-4 my-auto">
                            <form action="{{route('admin.sliders.index')}}">
                                <div class="form-row float-right">
                                    <div class="col-auto my-1">
                                        <input type="text" name="query" class="form-control" id="inlineFormCustomSelect"
                                               value="{{request('query')}}" placeholder="Type & hit enter">
                                    </div>
                                    <div class="col my-auto">
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button"
                                                    class="btn btn-outline-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Add
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a class="dropdown-item" href="{{route('admin.sliders.link.create')}}">Link
                                                    Slider</a>
                                                <a class="dropdown-item" href="{{route('admin.sliders.video.create')}}">Video
                                                    Slider</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable table-responsive">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                        <x-blank-table-indicator :data="$slides"/>
                        @foreach($slides as $slide)
                            <tr id="genre_row_{{$slide->id}}">
                                <td class="text-center">{{$loop->index+1}}</td>
                                <td class="text-center">
                                    @if($slide->banner!=null)
                                        <img src="{{$slide->banner}}" style="width: 100px; height: 60px"
                                             alt="{{$slide->title}}"/>
                                    @else
                                        <i class="mdi mdi-close-box-outline text-muted shadow-sm"
                                           style="font-size: 90px"></i>
                                    @endif
                                </td>
                                <td class="text-center">{{$slide->title}}</td>
                                <td class="text-center">{{\App\Library\Utils\Extensions\Str::ellipsis($slide->description,100)}}</td>
                                <td class="text-center">{{$slide->rating??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
                                        @if($slide->active==true)
                                            <label class="btn btn-outline-danger active" @include('admin.extras.tooltip.left', ['title' => 'Set slider active'])>
                                                <input type="radio" name="options" id="optionOn_{{$slide->id}}"
                                                       onchange="_status('{{$slide->id}}',1);"/> On
                                            </label>
                                            <label class="btn btn-outline-primary" @include('admin.extras.tooltip.right', ['title' => 'Set slider inactive'])>
                                                <input type="radio" name="options" id="optionOff_{{$slide->id}}"
                                                       onchange="_status('{{$slide->id}}',0);"/> Off
                                            </label>
                                        @else
                                            <label class="btn btn-outline-danger" @include('admin.extras.tooltip.left', ['title' => 'Set slider active'])>
                                                <input type="radio" name="options" id="optionOn_{{$slide->id}}"
                                                       onchange="_status('{{$slide->id}}',1);"/> On
                                            </label>
                                            <label class="btn btn-outline-primary active" @include('admin.extras.tooltip.right', ['title' => 'Set slider inactive'])>
                                                <input type="radio" name="options" id="optionOff_{{$slide->id}}"
                                                       onchange="_status('{{$slide->id}}',0);"/> Off
                                            </label>
                                        @endif
                                    </div>
                                </td>
                                @if($slide->type=='external-link')
                                    <td class="text-center">External Link</td>
                                @else
                                    <td class="text-center">Video Link</td>
                                @endif
                                @if($slide->type==\App\Models\Slider::TargetType['ExternalLink'])
                                    <td class="text-center">
                                        <a class="btn btn-outline-secondary waves-effect waves-light shadow-sm fadeInRightBig"
                                           target="_blank"
                                           href="{{$slide->target}}">{{\App\Library\Utils\Extensions\Str::ellipsis($slide->target)}}</a>
                                    </td>
                                @else
                                    <td class="text-center">
                                        @php
                                            $video=\App\Models\Video\Video::find($slide->target);
                                        @endphp
                                        @if($video!=null)
                                            @if($video->type=='movie')
                                                <a class="btn btn-outline-secondary waves-effect waves-light shadow-sm fadeInRightBig"
                                                   target="_blank"
                                                   href="{{route('admin.videos.edit.action',$video->id)}}">{{\App\Library\Utils\Extensions\Str::ellipsis($video->title)}}</a>
                                            @else
                                                <a class="btn btn-outline-secondary waves-effect waves-light shadow-sm fadeInRightBig"
                                                   target="_blank"
                                                   href="{{route('admin.tv-series.edit.action',$video->id)}}">{{\App\Library\Utils\Extensions\Str::ellipsis($video->title)}}</a>
                                            @endif
                                        @else
                                            <a disabled
                                               class="btn btn-outline-secondary waves-effect waves-light shadow-sm fadeInRightBig"
                                               target="_blank"
                                               href="javascript:void(0);">{{\App\Library\Utils\Extensions\Str::NotAvailable}}</a>
                                        @endif
                                    </td>
                                @endif
                                <td class="text-center">
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group mx-auto" role="group">
                                            <a class="btn btn-outline-danger"
                                               href="{{route('admin.sliders.edit',$slide->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i
                                                        class="mdi mdi-pencil"></i></a>
                                            <a class="btn btn-outline-primary"
                                               href="javascript:_delete('{{$slide->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i
                                                        class="mdi mdi-delete"></i></a>
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