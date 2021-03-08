@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row">
                        <div class="col-8 w-100">
                            <h5 class="page-title animatable">Campaign Advertisements</h5>
                        </div>
                        <div class="col-4 my-auto">
                            <form action="{{route('admin.campaigns.index')}}">
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
                                                <a class="dropdown-item"
                                                   href="{{route('admin.campaigns.article.create')}}">Article</a>
                                                <a class="dropdown-item"
                                                   href="{{route('admin.campaigns.videos.create')}}">Video
                                                    Article</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Views</th>
                            <th>Action(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($campaigns as $campaign)
                            <tr>
                                <td>{{($campaigns->firstItem()+$loop->index)}}</td>
                                <td>
                                    @if($campaign->thumbnail!=null)
                                        <img src="{{$campaign->thumbnail}}" style="max-height: 100px" alt=""
                                             class="img-thumbnail"/>
                                    @else
                                        {{\App\Library\Utils\Extensions\Str::NotAvailable}}
                                    @endif
                                </td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($campaign->title,50)}}</td>
                                <td>{{$campaign->type->description}}</td>
                                <td>{{$campaign->views}}</td>
                                <td>
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-outline-danger"
                                               href="{{route('admin.campaigns.edit',$campaign->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i
                                                        class="mdi mdi-pencil"></i></a>
                                            <a class="btn btn-outline-primary"
                                               href="javascript:_delete('{{$campaign->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i
                                                        class="mdi mdi-minus-circle-outline"></i></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$campaigns->links()}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="application/javascript">
        _delete = key => {
            alertify.confirm("Are you sure? This action is irreversible!",
                yes => {
                    axios.delete(`/admin/campaigns/${key}`).then(response => {
                        alertify.alert(response.data.message, () => {
                            location.reload();
                        });
                    }).catch(e => {
                        alertify.confirm('Something went wrong. Retry?', yes => {
                            _delete(key);
                        });
                    });
                }
            )
        }
    </script>
@stop