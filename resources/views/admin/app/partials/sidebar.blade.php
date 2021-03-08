<!-- Left Sidebar Start -->
<div class="left side-menu" style="box-shadow: 0 0 5px #858585;">
    <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
        <i class="ion-close"></i>
    </button>

    <div class="left-side-logo d-block d-lg-none">
        <div class="text-center">
            <a href="/" class="logo"><img src="{{asset("assets/admin/images/logo.png")}}" height="50" alt="logo"></a>
        </div>
    </div>

    <div class="sidebar-inner slimscrollleft">

        <div id="sidebar-menu">
            <ul>
                <!--Main Section-->
                <x-sidebar-header title="Main"/>
                <x-sidebar-item url="{{route('admin.home')}}" icon="ti-dashboard" title="Dashboard"/>
                <x-sidebar-item url="{{route('admin.customers.index')}}" icon="ti-user" title="Customers"/>
                <x-sidebar-item url="{{route('admin.sellers.index')}}" icon="ti-user" title="Sellers"/>
                <x-sidebar-expandable-item icon="ti-archive" title="Miscellaneous">
                    <x-sidebar-item url="{{route('admin.extras.about-us.edit')}}" icon="" title="About Us"/>
                    <x-sidebar-item url="{{route('admin.extras.privacy-policy.edit')}}" icon="" title="Privacy Policy"/>
                    <x-sidebar-item url="{{route('admin.extras.terms-and-conditions.edit')}}" icon=""
                                    title="Terms & Conditions"/>
                    <x-sidebar-item url="{{route('admin.extras.faq.edit')}}" icon="" title="FAQ"/>
                    <x-sidebar-item url="{{route('admin.extras.shipping-policy.edit')}}" icon=""
                                    title="Shipping Policy"/>
                    <x-sidebar-item url="{{route('admin.extras.return-policy.edit')}}" icon="" title="Returns Policy"/>
                    <x-sidebar-item url="{{route('admin.extras.cancellation-policy.edit')}}" icon=""
                                    title="Cancellation Policy"/>
                </x-sidebar-expandable-item>

                <x-sidebar-header title="Shop"/>
                <x-sidebar-item url="{{route('admin.announcements.index')}}" icon="ti-announcement"
                                title="Announcements"/>
                <x-sidebar-item url="{{route('admin.advertisements.index')}}" icon="ti-image" title="Advertisements"/>
                <x-sidebar-item url="{{route('admin.orders.index')}}" icon="ti-bag" title="Orders"/>
                <x-sidebar-expandable-item icon="ti-package" title="Products">
                    <x-sidebar-item url="{{route('admin.products.pending')}}" icon="" title="Pending"/>
                    <x-sidebar-item url="{{route('admin.products.approved')}}" icon="" title="Approved"/>
                    <x-sidebar-item url="{{route('admin.products.rejected')}}" icon="" title="Rejected"/>
                    <x-sidebar-item url="{{route('admin.products.deleted')}}" icon="" title="Deleted"/>
                </x-sidebar-expandable-item>
                <x-sidebar-item url="{{route('admin.shop.choices')}}" icon="ti-panel" title="Homepage"/>
                <x-sidebar-item url="{{route('admin.payments.index')}}" icon="ti-receipt" title="Payments"/>
                <x-sidebar-item url="{{route('admin.transactions.index')}}" icon="ti-receipt" title="Transactions"/>

                <!--Catalog Section-->
                <x-sidebar-header title="Catalog"/>
                <x-sidebar-expandable-item icon="ti-files" title="Attributes">
                    <x-sidebar-item url="{{route('admin.products.attributes.index')}}" icon="" title="All"/>
                    <x-sidebar-item url="{{route('admin.attributes.sets.index')}}" icon="" title="Sets"/>
                </x-sidebar-expandable-item>
                <x-sidebar-item url="{{route('admin.brands.index')}}" icon="ti-medall-alt" title="Brands"/>
                <x-sidebar-item url="{{route('admin.categories.index')}}" icon="ti-flag-alt-2" title="Categories"/>


                <x-sidebar-header title="Entertainment"/>
                <x-sidebar-item url="{{route('admin.campaigns.index')}}" icon="ti-bar-chart"
                                title="Campaign"/>
                <x-sidebar-item url="{{route('admin.genres.index')}}" icon="ti-flag" title="Genres"/>
                <x-sidebar-item url="{{route('admin.sliders.index')}}" icon="ti-gallery" title="Sliders"/>
                <x-sidebar-item url="{{route('admin.videos.index')}}" icon="ti-video-clapper" title="Videos"/>
                <x-sidebar-item url="{{route('admin.tv-series.index')}}" icon="ti-video-clapper" title="Tv Series"/>
                <x-sidebar-item url="{{route('admin.subscription-plans.index')}}" icon="ti-receipt" title="Plans"/>

                <x-sidebar-header title="Analytics"/>
                <x-sidebar-item url="{{route('admin.genres.index')}}" icon="ti-stats-up" title="Statistics"/>

                <x-sidebar-header title="News"/>
                <x-sidebar-item url="{{route('admin.news.articles.index')}}" icon="ti-write" title="Articles"/>
                <x-sidebar-item url="{{route('admin.news.categories.index')}}" icon="ti-flag-alt" title="Categories"/>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
