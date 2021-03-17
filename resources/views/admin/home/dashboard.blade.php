@extends('admin.app.app')
@section('content')
    <div class="row">
        <x-stats-card title="Orders Today" icon="ti-package" value="{{$ordersToday}}" column="2"/>
        <x-stats-card title="Sales Today" icon="ti-receipt" value="{{$salesToday}}" column="2"/>
        <x-stats-card title="Customers Registered Today" icon="ti-user" value="{{$customersToday}}" column="4"/>
        <x-stats-card title="Sellers Registered Today" icon="ti-user" value="{{$sellersToday}}" column="4"/>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header border-0 py-0">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="header-title py-3 mb-0 animatable">Recently Registered Customers</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable pt-0 table-responsive">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator :data="$customers"/>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{($loop->index+1)}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($customer->name,25)}}</td>
                                <td>{{$customer->mobile}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($customer->email,50)}}</td>
                                <td>{{$customer->created_at->format(\App\Library\Utils\Extensions\Time::SIMPLIFIED_FORMAT)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header border-0 py-0">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="header-title py-3 mb-0 animatable">Recently Registered Sellers</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable pt-0 table-responsive">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator :data="$sellers"/>
                        @foreach ($sellers as $seller)
                            <tr>
                                <td>{{($loop->index+1)}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($seller->name,25)}}</td>
                                <td>{{$seller->mobile}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($seller->email,50)}}</td>
                                <td>{{$customer->created_at->format(\App\Library\Utils\Extensions\Time::SIMPLIFIED_FORMAT)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header border-0 py-0">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="header-title py-3 mb-0 animatable">Most Viewed Articles</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable pt- table-responsive0">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Type</th>
                            <th>Views</th>
                            <th>Shares</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator :data="$articles"/>
                        @foreach ($articles as $article)
                            <tr>
                                <td>{{($loop->index+1)}}</td>
                                <td>
                                    @if($article->thumbnail!=null)
                                        <img src="{{$article->thumbnail}}" style="max-height: 100px" alt=""
                                             class="img-thumbnail"/>
                                    @else
                                        {{\App\Library\Utils\Extensions\Str::NotAvailable}}
                                    @endif
                                </td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($article->title,50)}}</td>
                                <td>{{$article->author}}</td>
                                <td>{{$article->type->description}}</td>
                                <td>{{$article->views}}</td>
                                <td>{{$article->shares}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header border-0 py-0">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="header-title py-3 mb-0 animatable">Most Viewed Videos/Series</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable pt-0 table-responsive">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Subscription</th>
                            <th>Views</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator :data="$videos"/>
                        @foreach ($videos as $video)
                            <tr>
                                <td>{{($loop->index+1)}}</td>
                                <td>
                                    @if($video->poster!=null)
                                        <img src="{{$video->poster}}" style="max-height: 100px" alt=""
                                             class="img-thumbnail"/>
                                    @else
                                        {{\App\Library\Utils\Extensions\Str::NotAvailable}}
                                    @endif
                                </td>
                                <td>{{\App\Library\Utils\Extensions\Str::ellipsis($video->title,50)}}</td>
                                <td>{{$video->type->description}}</td>
                                <td>{{\App\Library\Utils\Extensions\Str::ucfirst($video->subscription_type)}}</td>
                                <td>{{$video->hits}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        // let token = null;
        // $(document).ready(() => {
        // 	anime.timeline({loop: false}).add({
        // 		targets: ['.animatableX'],
        // 		translateX: [-40, 0],
        // 		translateZ: 0,
        // 		opacity: [0, 1],
        // 		easing: "easeOutExpo",
        // 		duration: 1000,
        // 		delay: (el, i) => 100 * i
        // 	});
        // 	anime.timeline({loop: false}).add({
        // 		targets: ['.animatable'],
        // 		translateY: [-40, 0],
        // 		translateZ: 0,
        // 		opacity: [0, 1],
        // 		easing: "easeOutExpo",
        // 		duration: 2000,
        // 		delay: (el, i) => 200 * i
        // 	});
        // });
    </script>
@stop