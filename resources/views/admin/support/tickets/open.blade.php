@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="page-title animatable">Support Tickets - Open</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Issue</th>
                            <th>Seller</th>
                            <th>Email</th>
                            <th>Action(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator columns="6" :data="$tickets"/>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>{{($tickets->firstItem()+$loop->index)}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($ticket->subject)}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($ticket->issue)}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($ticket->seller->name??\App\Library\Utils\Extensions\Str::NotAvailable)}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($ticket->email)}}</td>
                                <td>@include('admin.support.tickets.details',['ticket'=>$ticket])</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="application/javascript">
        _details = key => {
            setLoading(true, () => {
                axios.get(`/admin/customers/${key}`)
                    .then(response => {
                        setLoading(false);
                        bootbox.dialog({
                            title: 'Details',
                            message: response.data,
                            centerVertical: false,
                            size: 'small',
                            scrollable: true,
                        });
                    })
                    .catch(error => {
                        setLoading(false);
                        alertify.confirm('Something went wrong. Retry?', yes => {
                            showDetails(key);
                        });
                    });
            });
        }

        deleteCustomer = key => {
            alertify.confirm("Are you sure? This action is irreversible!",
                yes => {
                    axios.delete(`/admin/customers/${key}`).then(response => {
                        alertify.alert(response.data.message, () => {
                            location.reload();
                        });
                    }).catch(e => {
                        alertify.confirm('Something went wrong. Retry?', yes => {
                            deleteCustomer(key);
                        });
                    });
                }
            )
        }
    </script>
@stop