@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row">
                        <div class="col-8 w-100">
                            <h5 class="page-title animatable">Announcements</h5>
                        </div>
                        <div class="col-4 my-auto">
                            <form action="{{route('admin.announcements.index')}}">
                                <div class="form-row float-right">
                                    <div class="col-auto my-1">
                                        <input type="text" name="query" class="form-control" id="inlineFormCustomSelect"
                                               value="{{request('query')}}" placeholder="Type & hit enter">
                                    </div>
                                    <div class="col my-auto">
                                        <a href="{{route('admin.announcements.create')}}"
                                           class="btn btn-outline-primary">Add</a>
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
                            <th>#</th>
                            <th>Banner</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Valid From</th>
                            <th>Valid Until</th>
                            <th>Action(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator columns="7" :data="$announcements"/>
                        @foreach ($announcements as $announcement)
                            <tr>
                                <td>{{($announcements->firstItem()+$loop->index)}}</td>
                                <td>
                                    @if($announcement->banner!=null)
                                        <img src="{{$announcement->banner}}" alt="" class="img-fluid img-thumbnail"
                                             style="max-width: 100px;">
                                    @else
                                        {{\App\Library\Utils\Extensions\Str::NotAvailable}}
                                    @endif
                                </td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($announcement->title,25)}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($announcement->content,255)}}</td>
                                <td>{{$announcement->validFrom}}</td>
                                <td>{{$announcement->validUntil}}</td>
                                <td>
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-outline-danger"
                                               href="{{route('admin.announcements.edit',$announcement->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i
                                                        class="mdi mdi-pencil"></i></a>
                                            <a class="btn btn-outline-primary"
                                               href="javascript:_delete('{{$announcement->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i
                                                        class="mdi mdi-minus-circle-outline"></i></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$announcements->links()}}
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
                    axios.delete(`/admin/announcements/${key}`).then(response => {
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
