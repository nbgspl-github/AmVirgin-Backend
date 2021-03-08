@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row">
                        <div class="col-8 w-100">
                            <h5 class="page-title animatable">Statistics</h5>
                        </div>
                        <div class="col-4 my-auto">
                            <form action="{{route('admin.stats.videos.index')}}">
                                <div class="form-row float-right">
                                    <div class="col-auto my-1">
                                        <input type="text" name="query" class="form-control" id="inlineFormCustomSelect"
                                               value="{{request('query')}}" placeholder="Type & hit enter">
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
                            <th>Title</th>
                            <th>Type</th>
                            <th>Customer</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>IP</th>
                            <th>Duration Watched</th>
                            <th>Action(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($stats as $stat)
                            <tr>
                                <td>{{($stats->firstItem()+$loop->index)}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($stat->video->title??\App\Library\Utils\Extensions\Str::NotAvailable,25)}}</td>
                                <td>{{$stat->video->type->description}}</td>
                                <td>{{$stat->customer->name??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
                                <td>{{$stat->latitude}}</td>
                                <td>{{$stat->longitude}}</td>
                                <td>{{$stat->ip}}</td>
                                <td>{{$stat->duration}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$stats->links()}}
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
                    axios.delete(`/admin/advertisements/${key}`).then(response => {
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
