@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row justify-content-between">
                        <div class="col-6 my-auto">
                            <h5 class="page-title animatable">Statistics</h5>
                        </div>
                        <div class="col-6 my-auto text-right">
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                    data-target="#exampleModal">
                                Filters
                            </button>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{route('admin.stats.videos.index')}}" id="filterForm">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Choose from & to...</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-left">
                                                <div class="form-row">
                                                    <div class="col-12 my-1">
                                                        <div class="form-group">
                                                            <label for="from">From</label>
                                                            <input type="date" name="from" class="form-control"
                                                                   id="from"
                                                                   value="{{request('from')}}" placeholder="From"
                                                                   required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="to">To</label>
                                                            <input type="date" name="to" class="form-control"
                                                                   id="to"
                                                                   value="{{request('to')}}" placeholder="To" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-outline-primary">Filter
                                                </button>
                                                <button type="button" onclick="resetFilters();"
                                                        class="btn btn-outline-secondary">Reset
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable table-responsive">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Views</th>
                            <th>Action(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator :data="$stats"/>
                        @foreach ($stats as $stat)
                            <tr>
                                <td>{{($loop->index+1)}}</td>
                                <td>
                                    <a href="{{route('admin.stats.videos.show',$stat->video_id)}}"
                                       class="btn btn-link">{{\App\Library\Utils\Extensions\Str::ellipsis($stat->video->title??\App\Library\Utils\Extensions\Str::NotAvailable,25)}}</a>
                                </td>
                                <td>{{$stat->video->type->description}}</td>
                                <td>{{$stat->views}}</td>

                                <td>
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-outline-danger"
                                               href="{{route('admin.stats.videos.show',$stat->video_id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'View details'])><i
                                                        class="mdi mdi-lightbulb-outline"></i></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{--                    {{$stats->links()}}--}}
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
