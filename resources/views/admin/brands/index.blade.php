@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm custom-card">
                <div class="card-header py-0">
                    @include('admin.extras.header', ['title'=>'Brands','action'=>['link'=>route('admin.brands.create'),'text'=>'Add a Brand']])
                </div>
                <div class="card-body animatable">
                    <table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Logo</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($brands as $brand)
                            <tr>
                                <td class="text-center">{{$loop->index+1}}</td>
                                <td class="text-center">{{$brand->name()}}</td>
                                <td class="text-center">{{$brand->logo()}}</td>
                                <td class="text-center">{{\App\Library\Utils\Extensions\Str::stringifyBoolean($brand->active())}}</td>
                                <td class="text-center">{{$brand->status()}}</td>
                                <td class="text-center">
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group mx-auto" role="group">
                                            <a class="btn btn-outline-danger shadow-sm"
                                                    href="{{route('admin.brands.edit',$brand->id())}}" @include('admin.extras.tooltip.left', ['title' => 'Edit brand details'])><i
                                                        class="mdi mdi-pencil"></i></a>
                                            @if($brand->status()=='pending')
                                                <a class="btn btn-outline-danger shadow-sm"
                                                        href="{{route('admin.brands.approve',$brand->id())}}" @include('admin.extras.tooltip.left', ['title' => 'Approve'])><i
                                                            class="mdi mdi-check"></i></a>
                                            @endif
                                            <a class="btn btn-outline-primary shadow-sm" href="javascript:void(0);"
                                               onclick="deleteMovie('');" @include('admin.extras.tooltip.right', ['title' => 'Delete this brand'])><i
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
        let dataTable = null;

        $(document).ready(() => {
            dataTable = $('#datatable').DataTable({
                initComplete: function () {
                    $('#datatable_wrapper').addClass('px-0 mx-0');
                }
            });
        });
    </script>
@stop