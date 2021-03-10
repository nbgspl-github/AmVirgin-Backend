@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row justify-content-between">
                        <div class="col-6 my-auto">
                            <h5 class="page-title animatable">Showing statistics for - {{$video->title}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <caption>Customers who watched this series</caption>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Views</th>
                            <th>Duration</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator :data="$views"/>
                        @foreach($views as $view)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$view->customer->name??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
                                <td>{{$view->views}}</td>
                                <td>{{\App\Library\Utils\Extensions\Time::toDuration($view->duration)}}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal_{{$loop->index}}">
                                        Show
                                    </button>
                                    <div class="modal fade" id="exampleModal_{{$loop->index}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Customer Views</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-sm">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Time</th>
                                                            <th scope="col">Duration</th>
                                                            <th scope="col">Latitude</th>
                                                            <th scope="col">Longitude</th>
                                                            <th scope="col">IP</th>
                                                            <th scope="col">Address</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <x-blank-table-indicator :data="$view->entries"/>
                                                        @foreach($view->entries as $record)
                                                            <tr>
                                                                <td>{{$loop->index+1}}</td>
                                                                <td>{{$record->created_at}}</td>
                                                                <td>{{$record->duration}}</td>
                                                                <td>{{$record->latitude??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
                                                                <td>{{$record->longitude??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
                                                                <td>{{$record->ip??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
                                                                <td>{{$record->address??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
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

        function resetFilters() {
            window.history.replaceState({}, document.title, '{{route('admin.stats.videos.index')}}');
            $('#from').val('');
            $('#to').val('');
            window.location.reload();
        }
    </script>
@stop
