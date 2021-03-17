@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row">
                        <div class="col-8">
                            <h5 class="page-title animatable">Brands</h5>
                        </div>
                        <div class="col-4 my-auto">
                            <form action="{{route('admin.brands.index')}}">
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
                <div class="card-body animatable table-responsive">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        <x-blank-table-indicator columns="5" :data="$brands"/>
                        @foreach($brands as $brand)
                            <tr>
                                <td>{{$brands->firstItem()+$loop->index}}</td>
                                <td>
                                    @if($brand->logo!=null)
                                        <img src="{{$brand->logo}}" alt="" class="img-fluid img-thumbnail"
                                             style="max-width: 100px;">
                                    @else
                                        {{\App\Library\Utils\Extensions\Str::NotAvailable}}
                                    @endif
                                </td>
                                <td>{{$brand->name}}</td>
                                <td>{{$brand->status->description}}</td>
                                <td>
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-outline-danger shadow-sm"
                                               href="{{route('admin.brands.show',$brand->id)}}" @include('admin.extras.tooltip.left', ['title' => 'View brand details'])><i
                                                        class="mdi mdi-lightbulb-outline"></i></a>
                                            <a class="btn btn-outline-primary shadow-sm"
                                               href="javascript:deleteBrand('{{$brand->id}}')" @include('admin.extras.tooltip.right', ['title' => 'Delete this brand'])><i
                                                        class="mdi mdi-delete"></i></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$brands->links()}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="application/javascript">
        deleteBrand = (key) => {
            alertify.confirm('This action is irreversible. Proceed?', yes => {
                axios.delete(`admin/brands/${key}`).then(response => {
                    alertify.alert(response.data.message, () => {
                        location.reload();
                    })
                }).catch(error => {
                    alertify.confirm('Something went wrong. Retry?', yes => {
                        deleteBrand(key);
                    })
                })
            })
        }
    </script>
@stop