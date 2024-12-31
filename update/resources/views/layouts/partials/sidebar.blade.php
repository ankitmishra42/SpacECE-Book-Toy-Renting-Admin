@php
    $appSetting = App\Models\AppSetting::first();
@endphp
<nav class="navbar navbar-vertical {{ $appSetting?->direction == 'rtl' ? 'fixed-right' : 'fixed-left' }} navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid" style="min-height:0">
        <!-- Brand -->
        <a class="navbar-brand position-relative" href="{{ route('root') }}">
            @role('store')
                <img src="{{ auth()->user()->store->logo?->file ?? asset('web/logo.png') }}" class="navbar-brand-img">
            @else
                <img src="{{ $appSetting->websiteLogoPath ?? asset('web/logo.png') }}" class="navbar-brand-img"
                    alt="Admin Logo">
            @endrole
            @php
                $role = auth() ->user()->getRoleNames()[0] ?? 'Admin';
                $author = $role == 'store' ? 'Shop' : $role;
            @endphp
            <span class="auth">{{ $author }}</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerGoldStar"
            aria-controls="navbarTogglerGoldStar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="navbarTogglerGoldStar">
            {{-- Main Admin --}}
            <ul class="navbar-nav">
                <div class="position-absolute top-0 right-0 d-md-none navbarCloseBtn" data-toggle="collapse"
                    data-target="#navbarTogglerGoldStar">
                    <i class="fas fa-angle-left"></i>
                </div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('root') ? 'active' : '' }}" href="{{ route('root') }}">
                        <i class="fa fa-desktop text-teal"></i>
                        <span class="nav-link-text">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @role('store|root')
                    @can('order.index')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('order.*') ? 'active' : '' }}"
                                href="{{ route('order.index') }}">
                                <i class="fa fa-shopping-cart text-orange"></i>
                                <span class="nav-link-text">{{ __('Orders') }}</span>
                            </a>
                        </li>
                    @endcan
                @endrole

                @canany(['product.index', 'coupon.index', 'variant.index', 'service.index'])
                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('service.*', 'variant.*', 'product.*', 'coupon.*') ? 'active' : '' }}"
                            href="#product_manage" data-toggle="collapse" aria-expanded="false" role="button"
                            aria-controls="navbar-examples">
                            <i class="fas fa-th-large text-primary"></i>
                            <span class="nav-link-text">{{ __('Product_Manage') }}</span>
                        </a>

                        <div class="collapse {{ request()->routeIs('service.*', 'variant.*', 'product.*', 'coupon.*') ? 'show' : '' }}"
                            id="product_manage">
                            <ul class="nav nav-sm flex-column">
                                @can('service.index')
                                    <a class="nav-link sub-menu {{ request()->routeIs('service.*') ? 'active' : '' }}"
                                        href="{{ route('service.index') }}" href="{{ route('service.index') }}">
                                        <i class="fas fa-tools"></i>
                                        <span class="nav-link-text">{{ __('Services') }}</span>
                                    </a>
                                @endcan

                                @can('variant.index')
                                    <a class="nav-link sub-menu {{ request()->routeIs('variant.*') ? 'active' : '' }}"
                                        href="{{ route('variant.index') }}">
                                        <i class="fas fa-list"></i>
                                        <span class="nav-link-text">{{ __('Variants') }}</span>
                                    </a>
                                @endcan
                                @can('product.index')
                                    <a class="nav-link sub-menu {{ request()->routeIs('product.*') ? 'active' : '' }}"
                                        href="{{ route('product.index') }}">
                                        <i class="fas fa-tshirt"></i>
                                        <span class="nav-link-text">{{ __('Products') }}</span>
                                    </a>
                                @endcan

                                @can('coupon.index')
                                    <a class="nav-link sub-menu {{ request()->routeIs('coupon.*') ? 'active' : '' }}"
                                        href="{{ route('coupon.index') }}">
                                        <i class="fa fa-percentage"></i>
                                        <span class="nav-link-text">{{ __('Coupon') }}</span>
                                    </a>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                @can('notification.index')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('notification.*') ? 'active' : '' }}"
                            href="{{ route('notification.index') }}">
                            <i class="fas fa-bell text-primary"></i>
                            <span class="nav-link-text">{{ __('Notifications') }}</span>
                        </a>
                    </li>
                @endcan

                @role('store')

                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('pos.*') ? 'active' : '' }}"
                            href="#posManage" data-toggle="collapse" aria-expanded="false" role="button" aria-controls="navbar-examples">
                            <i class="fas fa-store-alt text-success"></i>
                            <span class="nav-link-text">{{ __('POS Manage') }}</span>
                        </a>

                        <div class="collapse {{ request()->routeIs('pos.*') ? 'show' : '' }}"
                            id="posManage">
                            <ul class="nav nav-sm flex-column">
                                <a class="nav-link sub-menu {{ request()->routeIs('pos.index') ? 'active' : '' }}" href="{{ route('pos.index') }}" href="{{ route('pos.index') }}">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="nav-link-text">{{ __('POS') }}</span>
                                </a>

                                <a class="nav-link sub-menu {{ request()->routeIs('pos.sales', 'pos.order.show') ? 'active' : '' }}"
                                    href="{{ route('pos.sales') }}">
                                    <i class="fas fa-list"></i>
                                    <span class="nav-link-text">{{ __('Pos Sales') }}</span>
                                </a>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('revenue.*') ? 'active' : '' }}"
                            href="{{ route('revenue.index') }}">
                            <i class="fas fa-file text-red"></i>
                            <span class="nav-link-text">{{ __('Report') }}</span>
                        </a>
                    </li>
                @else
                    @can('revenue.index')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('revenue.*', 'shoplist.*') ? 'active' : '' }}"
                                href="#report_manage" data-toggle="collapse" aria-expanded="false" role="button"
                                aria-controls="navbar-examples">
                                <i class="fa fa-file text-red"></i>
                                <span class="nav-link-text">{{ __('Report') }}</span>
                            </a>

                            <div class="collapse {{ request()->routeIs('revenue.*', 'shoplist.*') ? 'show' : '' }}"
                                id="report_manage">
                                <ul class="nav nav-sm flex-column">
                                    <a class="nav-link sub-menu {{ request()->routeIs('shoplist.*') ? 'active' : '' }}"
                                        href="{{ route('shoplist.index') }}">
                                        <i class="fas fa-tools"></i>
                                        <span class="nav-link-text">{{ __('Shop_List') }}</span>
                                    </a>
                                    @role('root|admin')
                                        <a class="nav-link sub-menu {{ request()->routeIs('revenue.*') ? 'active' : '' }}"
                                            href="{{ route('revenue.index') }}">
                                            <i class="fas fa-shopping-cart"></i>
                                            <span class="nav-link-text">{{ __('Orders') }}</span>
                                        </a>
                                    @endrole
                                </ul>
                            </div>
                        </li>
                    @endcan
                @endrole

                @can('banner.promotional')
                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('banner.*') ? 'active' : '' }}"
                            href="{{ route('banner.promotional') }}">
                            <i class="fas fa-image text-dark"></i>
                            <span class="nav-link-text">{{ __('App_Banners') }}</span>
                        </a>
                    </li>
                @endcan

                @can('customer.index')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}"
                            href="{{ route('customer.index') }}">
                            <i class="fa fa-users text-red"></i>
                            <span class="nav-link-text">{{ __('Customer') }}</span>
                        </a>
                    </li>
                @endcan

                {{-- @can('driver.index')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('driver.*') ? 'active' : '' }}"
                            href="{{ route('driver.index') }}">
                            <i class="fas fa-shipping-fast text-orange"></i>
                            <span class="nav-link-text">{{ __('Drivers') }}</span>
                        </a>
                    </li>
                @endcan
                 --}}

                @can('shops.map-view')
                    <li class="nav-item">
                        <a class="nav-link sub-menu {{ request()->routeIs('shops.map-view') ? 'active' : '' }}"
                            href="{{ route('shops.map-view') }}">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                            <span class="nav-link-text">{{ __('Stores_in_map') }}</span>
                        </a>
                    </li>
                @endcan

                @role('root')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}"
                            href="{{ route('shop.index') }}">
                            <i class="fa fa-store text-purple"></i>
                            <span class="nav-link-text">{{ __('Shops') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                            href="{{ route('admin.index') }}">
                            <i class="fas fa-user-secret"></i>
                            <span class="nav-link-text">{{ __('Admins') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('setting.*', 'deliveryCost', 'mobileApp', 'socialLink.*', 'appSetting.*') ? 'active' : '' }}"
                            href="#setting" data-toggle="collapse" aria-expanded="false" role="button"
                            aria-controls="navbar-examples">
                            <i class="fa fa-cog text-red"></i>
                            <span class="nav-link-text">{{ __('Settings') }}</span>
                        </a>

                        <div class="collapse {{ request()->routeIs('setting.*', 'deliveryCost', 'mobileApp', 'socialLink.*', 'appSetting.*', 'schedule.*', 'areas.*', 'about.*') ? 'show' : '' }}"
                            id="setting">
                            <ul class="nav nav-sm flex-column">
                                @foreach (config('enums.settings') as $index => $item)
                                    <a class="nav-link sub-menu {{ url()->full() == config('app.url') . '/settings/' . $index || url()->full() == config('app.url') . '/settings/' . $index . '/edit' ? 'active' : '' }}"
                                        href="{{ route('setting.show', $index) }}">
                                        @if ($index == 'privacy-policy')
                                            <i class="fas fa-vote-yea"></i>
                                        @endif
                                        @if ($index == 'trams-of-service')
                                            <i class="fas fa-toilet-paper"></i>
                                        @endif
                                        @if ($index == 'contact-us')
                                            <i class="fas fa-envelope-open-text"></i>
                                        @endif
                                        <span class="nav-link-text">{{ __($index) }}</span>
                                    </a>
                                @endforeach

                                <a class="nav-link sub-menu {{ request()->routeIs('about.*') ? 'active' : '' }}"
                                    href="{{ route('about.index') }}">
                                    <i class="fas fa-info-circle"></i>
                                    <span class="nav-link-text">{{ __('about-us') }}</span>
                                </a>

                                <a class="nav-link sub-menu {{ request()->routeIs('mobileApp') ? 'active' : '' }}"
                                    href="{{ route('mobileApp') }}">
                                    <i class="fa fa-link"></i>
                                    <span class="nav-link-text">{{ __('Mobile_App_Link') }}</span>
                                </a>
                                <a class="nav-link sub-menu {{ url()->full() == config('app.url') . '/pickup/scheduls' ? 'active' : '' }}"
                                    href="{{ route('schedule.index', 'pickup') }}">
                                    <span class="nav-link-text">{{ __('Pickup_Schedules') }}</span>
                                </a>
                                <a class="nav-link sub-menu {{ url()->full() == config('app.url') . '/delivery/scheduls' ? 'active' : '' }}"
                                    href="{{ route('schedule.index', 'delivery') }}">
                                    <span class="nav-link-text">{{ __('Delivery_Schedules') }}</span>
                                </a>

                                <a class="nav-link sub-menu {{ request()->routeIs('appSetting.*') ? 'active' : '' }}"
                                    href="{{ route('appSetting.index') }}">
                                    <i class="fas fa-globe"></i>
                                    <span class="nav-link-text">{{ __('App_Setting') }}</span>
                                </a>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('sms-gateway.*', 'mail-config.*', 'stripeKey.*', 'mapApiKey.*', 'fcm.*') ? 'active' : '' }}"
                            href="#thirdParty" data-toggle="collapse" aria-expanded="false" role="button"
                            aria-controls="navbar-examples">
                            <i class="fa fa-cog text-red"></i>
                            <span class="nav-link-text">{{ __('Third_Party_Config') }}</span>
                        </a>

                        <div class="collapse {{ request()->routeIs('sms-gateway.*', 'mail-config.*', 'stripeKey.*', 'mapApiKey.*', 'fcm.*') ? 'show' : '' }}"
                            id="thirdParty">
                            <ul class="nav nav-sm flex-column">
                                <a class="nav-link sub-menu {{ request()->routeIs('stripeKey.*') ? 'active' : '' }}"
                                    href="{{ route('stripeKey.index') }}">
                                    <i class="fab fa-cc-stripe"></i>
                                    <span class="nav-link-text">{{ __('Stripe_payment') }}</span>
                                </a>
                                <a class="nav-link sub-menu {{ request()->routeIs('sms-gateway.*') ? 'active' : '' }}"
                                    href="{{ route('sms-gateway.index') }}">
                                    <i class="fa fa-sms"></i>
                                    <span class="nav-link-text">{{ __('SMS_Gateway') }}</span>
                                </a>
                                <a class="nav-link sub-menu {{ request()->routeIs('mail-config.*') ? 'active' : '' }}"
                                    href="{{ route('mail-config.index') }}">
                                    <i class="fa fa-envelope"></i>
                                    <span class="nav-link-text">{{ __('Mail_Config') }}</span>
                                </a>
                                <a class="nav-link sub-menu {{ request()->routeIs('mapApiKey.*') ? 'active' : '' }}"
                                    href="{{ route('mapApiKey.index') }}">
                                    <i class="fa fa-map"></i>
                                    <span class="nav-link-text">{{ __('Google Map Key') }}</span>
                                </a>

                                <a class="nav-link sub-menu {{ request()->routeIs('fcm.*') ? 'active' : '' }}"
                                    href="{{ route('fcm.index') }}">
                                    <i class="fa fa-bell"></i>
                                    <span class="nav-link-text">{{ __('FCM Config') }}</span>
                                </a>
                            </ul>
                        </div>
                    </li>
                @endrole

                <li class="nav-item">
                    <a class="nav-link sub-menu {{ request()->routeIs('invoiceManage.*') ? 'active' : '' }}"
                        href="{{ route('invoiceManage.index') }}">
                        <i class="fas fa-print"></i>
                        <span class="nav-link-text">{{ __('Invoice_Manage') }}</span>
                    </a>
                </li>

                @role('store')
                    <li class="nav-item">
                        <a class="nav-link sub-menu {{ request()->routeIs('store.*') ? 'active' : '' }}"
                            href="{{ route('store.index') }}">
                            <i class="fas fa-user text-primary"></i>
                            <span class="nav-link-text">{{ __('Profile') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link sub-menu {{ url()->full() == config('app.url') . '/pickup/scheduls' ? 'active' : '' }}"
                            href="{{ route('schedule.index', 'pickup') }}">
                            <i class="fas fa-truck-pickup text-warning"></i>
                            <span class="nav-link-text">{{ __('Pickup_Schedules') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link sub-menu {{ url()->full() == config('app.url') . '/delivery/scheduls' ? 'active' : '' }}"
                            href="{{ route('schedule.index', 'delivery') }}">
                            <i class="fas fa-truck text-success"></i>
                            <span class="nav-link-text">{{ __('Delivery_Schedules') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link sub-menu {{ request()->routeIs('area.*') ? 'active' : '' }}"
                            href="{{ route('area.index') }}">
                            <i class="fas fa-map text-primary"></i>
                            <span class="nav-link-text">{{ __('Area Zone') }}</span>
                        </a>
                    </li>

                @else
                    @can('profile.index')
                        <li class="nav-item">
                            <a class="nav-link sub-menu {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                                href="{{ route('profile.index') }}">
                                <i class="fas fa-user text-primary"></i>
                                <span class="nav-link-text">{{ __('Profile') }}</span>
                            </a>
                        </li>
                    @endcan
                @endrole

                <li class="nav-item">
                    <a class="nav-link sub-menu {{ request()->routeIs('language.*') ? 'active' : '' }}"
                        href="{{ route('language.index') }}">
                        <i class="fas fa-language text-primary"></i>
                        <span class="nav-link-text">{{ __('Language') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" onclick="event.preventDefault(); document.getElementById('logout').submit()"
                        href="#">
                        <i class="fas fa-sign-out-alt text-warning"></i>
                        <span class="nav-link-text">{{ __('Logout') }}</span>
                    </a>
                    <form id="logout" action="{{ route('logout') }}" method="POST"> @csrf </form>
                </li>

            </ul>
        </div>
        @php
            use App\Models\Language;

            $languages = Language::All();
        @endphp
        <div class="footer_bottom">
            <div class="local">
                <i class="fa fa-language lanIcon"></i>
                <select id="language" name="ln" class="form-control">
                    <option value="">{{ __('Select_Language') }}</option>
                    @foreach ($languages as $language)
                    <option value="{{ $language->name }}" {{ session()->get('local') == $language->name ? 'selected' : '' }}>
                        {{ __($language->title) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="profile d-flex justify-content-start">
                <div>
                    <img src="{{ auth()->user()->profile_photo_path }}" alt="" width="50"
                        height="50">
                </div>
                <div>
                    <h3 class="name m-0">{{ auth()->user()->name }}</h3>
                    <p class="email m-0">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
</nav>
