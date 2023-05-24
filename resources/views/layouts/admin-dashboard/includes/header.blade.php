<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand @isset($settings['dashboard_menu_type']) @if ($settings['dashboard_menu_type'] != 'top') d-xl-none  @endif @endisset  ">
                <a href="{{ route(getAdminPrefix() . '.home.index') }}" class="logo-link">
                    <img class="logo-light logo-img"
                        src="@if (isset($settings['dashboard_logo']) && $settings['dashboard_logo'] != 'default.png') {{ asset('storage/dashboard/images/logo/' . $settings['dashboard_logo']) }}@else{{ asset('admin-dashboard/images/logo.png') }} @endif"
                        alt="logo">
                    <img class="logo-dark logo-img"
                        src="@if (isset($settings['dashboard_logo']) && $settings['dashboard_logo'] != 'default.png') {{ asset('storage/dashboard/images/logo/' . $settings['dashboard_logo']) }}@else{{ asset('admin-dashboard/images/logo-dark.png') }} @endif"
                        alt="logo-dark">
                </a>
            </div><!-- .nk-header-brand -->
            @isset($settings['dashboard_menu_type'])
                @if ($settings['dashboard_menu_type'] == 'top')
                    <div class="nk-header-search ml-3 ml-xl-0">
                        <div class="dropdown">
                            <a href="{{ route(getAdminPrefix() . '.home.index') }}" class="dropbtn user-name"><em class="icon ni ni-home-fill"></em> Dashboard</a>
                        </div>
                        <div class="dropdown">
                            <a href="{{ route(getAdminPrefix() . '.stores.index') }}"
                                class="dropbtn user-name dropdown-indicator {{ getNewIndicatorClassForAdmin('reviews', 'before') }}"><em
                                    class="icon ni ni-db-fill"></em> Data</a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-content">
                                <div class="dropdown-inner px-4">
                                    <ul class="link-list">
                                        @can('view stores')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.stores.index') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-cart-fill"></em></span>
                                                    <span class="nk-menu-text">Stores</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view categories')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.categories.index') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-grid-alt-fill"></em></span>
                                                    <span class="nk-menu-text">Categories</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view reviews')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.reviews.index') }}" class="{{ getNewIndicatorClassForAdmin('reviews') }}">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-notice"></em></span>
                                                    <span class="nk-menu-text">Store Reviews</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view vouchers')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.vouchers.index') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-money"></em></span>
                                                    <span class="nk-menu-text">Vouchers</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view charities')
                                        <li class="">
                                            <a href="{{ '/' . getAdminPrefix() . '/charities' }}"  class="">
                                                <span class="nk-menu-icon"><em class="icon ni ni-star"></em></span>
                                                <span class="nk-menu-text">Charities</span>
                                            </a>
                                        </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view countries')
                                        <li class="">
                                            <a  href="{{ '/' . getAdminPrefix() . '/countries' }}"  class="">
                                                <span class="nk-menu-icon"><em class="icon ni ni-flag"></em></span>
                                                <span class="nk-menu-text">Countries</span>
                                            </a>
                                        </li><!-- .nk-menu-item -->
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="{{ route(getAdminPrefix() . '.commissions.index') }}"
                                class="dropbtn user-name dropdown-indicator {{ getNewIndicatorClassForAdmin('sales', 'before') }}"><em
                                    class="icon ni ni-sign-gbp"></em> Sales</a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-content">
                                <div class="dropdown-inner px-4">
                                    <ul class="link-list">
                                        @can('view cashbacks')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.commissions.index') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-sign-gbp"></em></span>
                                                    <span class="nk-menu-text">Cashbacks</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view cashouts')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.cashouts.index') }}" class="{{ getNewIndicatorClassForAdmin('sales') }}">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-cc-alt2-fill"></em></span>
                                                    <span class="nk-menu-text">Cashouts</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @can('view users')
                            <div class="dropdown">
                                <a href="{{ route(getAdminPrefix() . '.users.index') }}" class="dropbtn user-name"><em class="icon ni ni-users-fill"></em> Users</a>
                            </div>
                        @endcan
                        <div class="dropdown">
                            <a href="{{ route(getAdminPrefix() . '.clicks.index') }}" class="dropbtn user-name dropdown-indicator"><em class="icon ni ni-file-docs"></em> Reports</a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-content">
                                <div class="dropdown-inner px-4">
                                    <ul class="link-list">
                                        @can('view clicks')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.clicks.index') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-arrow-up-right"></em></span>
                                                    <span class="nk-menu-text">Exit Clicks</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view performance')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.reports.performance') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-cc-alt2-fill"></em></span>
                                                    <span class="nk-menu-text">Store Performance</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view earnings')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.reports.earnings') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-coin-gbp"></em></span>
                                                    <span class="nk-menu-text">Earnings</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="{{ route(getAdminPrefix() . '.settings.index') }}" class="dropbtn user-name dropdown-indicator"><em class="icon ni ni-setting-fill"></em> Settings</a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-content">
                                <div class="dropdown-inner px-4">
                                    <ul class="link-list">
                                        @can('view settings')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.settings.index') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-setting-fill"></em></span>
                                                    <span class="nk-menu-text">System Settings</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        <li class="">
                                            <a href="{{ route(getAdminPrefix() . '.email_templates.index') }}" class="">
                                                <span class="nk-menu-icon"><em class="icon ni ni-emails"></em></span>
                                                <span class="nk-menu-text">Email Templates</span>
                                            </a>
                                        </li><!-- .nk-menu-item -->
                                        @can('view networks')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.networks.index') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-activity-round-fill"></em></span>
                                                    <span class="nk-menu-text">Networks</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view languages')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.languages.coming-soon') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-text2"></em></span>
                                                    <span class="nk-menu-text">Languages</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view translations')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.translations.coming-soon') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                                                    <span class="nk-menu-text">Translations</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        @can('view permissions')
                                            <li class="">
                                                <a href="{{ route(getAdminPrefix() . '.settings.permissions') }}" class="">
                                                    <span class="nk-menu-icon"><em class="icon ni ni-lock-alt-fill"></em></span>
                                                    <span class="nk-menu-text">Permissions</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        <li class="">
                                            <a href="{{ route(getAdminPrefix() . '.sliders.index') }}" class="">
                                                <span class="nk-menu-icon"><em class="icon ni ni-layers"></em></span>
                                                <span class="nk-menu-text">Sliders</span>
                                            </a>
                                        </li><!-- .nk-menu-item -->
                                        <li class="">
                                            <a href="{{ '/' . getAdminPrefix() . '/menu' }}" class="">
                                                <span class="nk-menu-icon"><em class="icon ni ni-menu"></em></span>
                                                <span class="nk-menu-text">Menu</span>
                                            </a>
                                        </li><!-- .nk-menu-item -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="{{ route(getAdminPrefix() . '.pages.index') }}"
                                class="dropbtn user-name dropdown-indicator {{ getNewIndicatorClassForAdmin('tickets', 'before') }}"><em
                                    class="icon ni ni-layout-alt-fill"></em> CMS</a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-content ">
                                <div class="dropdown-inner px-4">
                                    <ul class="link-list">
                                        @can('view tickets')
                                            <li class=" ">
                                                <a href="{{ route(getAdminPrefix() . '.tickets.index') }}" class="{{ getNewIndicatorClassForAdmin('tickets') }}">
                                                    <span class="nk-menu-icon "><em class="icon ni ni-chat-fill"></em></span>
                                                    <span class="nk-menu-text">Tickets</span>
                                                </a>
                                            </li><!-- .nk-menu-item -->
                                        @endcan
                                        <li class=" ">
                                            <a href="{{ route(getAdminPrefix() . '.pages.index') }}" class="">
                                                <span class="nk-menu-icon "><em class="icon ni ni-text-rich"></em></span>
                                                <span class="nk-menu-text">Pages</span>
                                            </a>
                                        </li><!-- .nk-menu-item -->
                                        <li class=" ">
                                            <a href="{{ route(getAdminPrefix() . '.blogs.index') }}" class="">
                                                <span class="nk-menu-icon "><em class="icon ni ni-article"></em></span>
                                                <span class="nk-menu-text">Blog</span>
                                            </a>
                                        </li><!-- .nk-menu-item -->
                                        <li class=" ">
                                            <a href="{{ route(getAdminPrefix() . '.testimonials.index') }}" class="">
                                                <span class="nk-menu-icon "><em class="icon ni ni-star-fill"></em></em></span>
                                                <span class="nk-menu-text">Testimonials</span>
                                            </a>
                                        </li><!-- .nk-menu-item -->
                                        <li class="">
                                            <a href="{{ route(getAdminPrefix() . '.seo.index') }}" class="">
                                                <span class="nk-menu-icon"><em class="icon ni ni-menu"></em></span>
                                                <span class="nk-menu-text">SEO Rules</span>
                                            </a>
                                        </li><!-- .nk-menu-item -->
                                        <li>
                                            <a href="{{ route(getAdminPrefix() . '.api-docs') }}" target="_blank">
                                                <span class="nk-menu-icon"><em class="icon ni ni-file-doc"></em></span>
                                                <span class="nk-menu-text">API Docs</span>
                                            </a>
                                        </li><!-- .nk-menu-item -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div><!-- .nk-header-news -->
                @endif
            @endisset
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li> <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary btn-dim btn-sm d-none d-md-inline-flex"><em
                                class="icon ni ni-external-alt mr-1"></em> Visit Site</a></li>
                    <li class="dropdown user-dropdown" style="display: inherit!important">
                        <a class="dropdown-toggle mr-n1">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    @if (Auth::user()->avatar == 'default.png'  || !Storage::exists('public/users/images/avatar/' .  Auth::user()->avatar))
                                        <img src="{{ asset('admin-dashboard/images/avatar.png') }}" alt="store logo" class=""
                                            style="max-width:50px;max-height:50px" />
                                    @else
                                        <img
                                            src="@isset(Auth::user()->avatar){{ asset('storage/users/images/avatar/' . Auth::user()->avatar) }}@else{{ asset('admin-dashboard/images/cloud-uploading.png') }}@endif"
                                     alt="store logo" class="" style="max-width:50px;max-height:50px"/>
                                @endif
                                </div>
                                <div class="user-info d-none d-xl-block">
                                    <div class="user-name dropdown-indicator">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-content2">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                    @if (Auth::user()->avatar == 'default.png' || !Storage::exists('public/users/images/avatar/' .  Auth::user()->avatar))
                                        <img src="{{ asset('admin-dashboard/images/avatar.png') }}"
                                         alt="store logo" class="" style="max-width:50px;max-height:50px"/>
                                    @else
                                         <img src="@isset(Auth::user()->avatar){{ asset('storage/users/images/avatar/' . Auth::user()->avatar) }}@else{{ asset('admin-dashboard/images/cloud-uploading.png') }}@endif"
                                         alt="store logo" class="" style="max-width:50px;max-height:50px"/>
                                    @endif
                                        </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                        <span class="sub-text">{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-inner">
                            <ul class="link-list">
                                <li><a href="{{ route(getAdminPrefix() . '.profile.index') }}"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                                <li><a href="#"
                                        onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();">
                                        <em class="icon ni ni-signout"></em><span>Sign out</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
