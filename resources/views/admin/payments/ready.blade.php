@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row">
                        <div class="col-8">
                            <h5 class="page-title animatable">Ready to Pay</h5>
                        </div>
                        <div class="col-4 my-auto">
                            <form action="{{route('admin.payments.index')}}">
                                <div class="form-row float-right">
                                    <div class="col-auto my-1">
                                        <input type="text" name="query" class="form-control" id="inlineFormCustomSelect"
                                               value="{{request('query')}}" placeholder="Type number & hit enter">
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
                            <th>Seller</th>
                            <th>Order Number</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Sales</th>
                            <th>Selling Fee</th>
                            <th>Courier Charges</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator :data="$payments"/>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{($payments->firstItem()+$loop->index)}}</td>
                                <td>
                                    <span class="badge badge-pill badge-secondary">{{$payment->seller->name??\App\Library\Utils\Extensions\Str::NotAvailable}}</span>
                                </td>
                                <td>{{$payment->order->number}}</td>
                                <td>{{$payment->description}}</td>
                                <td>{{$payment->quantity}}</td>
                                <td>{{$payment->sales}}</td>
                                <td>{{$payment->selling_fee}}</td>
                                <td>{{$payment->courier_charges}}</td>
                                <td>{{$payment->total}}</td>
                                <td>{{empty($payment->paid_at)?'Pending':'Completed'}}</td>
                                <td>
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-outline-danger"
                                               href="javascript:_details('{{$payment->id}}')" @include('admin.extras.tooltip.bottom', ['title' => 'View details'])><i
                                                        class="mdi mdi-lightbulb-outline"></i></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $payments->links() }}
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
                            _details(key);
                        });
                    });
            });
        }
    </script>
@stop