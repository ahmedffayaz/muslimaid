<div class="nk-sidebar nk-sidebar-fixed is-light is-compact
@isset($settings['dashboard_menu_type']) @if ($settings['dashboard_menu_type'] == 'top')
d-xl-none
@endif @endisset"
    data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="{{ route('admin.home.index') }}" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img"
                    src="@if (isset($settings['website_logo']) && $settings['website_logo'] != 'default.png') {{ asset('storage/dashboard/images/logo/' . $settings['website_logo']) }}@else{{ asset('admin-dashboard/images/logo.png') }} @endif"
                    alt="logo">
                <img class="logo-dark logo-img"
                    src="@if (isset($settings['website_logo']) && $settings['website_logo'] != 'default.png') {{ asset('storage/dashboard/images/logo/' . $settings['website_logo']) }}@else{{ asset('admin-dashboard/images/logo-dark.png') }} @endif"
                    alt="logo-dark">
                <img class="logo-small logo-img logo-img-small"
                    @isset($settings['favicon'])
                    @if ($settings['favicon'] == 'default.png')
                    src="{{ asset('admin-dashboard/images/favicon.png') }}"
                    @else
                    src="{{ asset('storage/dashboard/images/logo/' . $settings['favicon']) }}"
                @endif
                @else
                src="{{ asset('admin-dashboard/images/favicon.png') }}"
                @endif alt="logo-small">
            </a>
        </div>
        <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex compact-active" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-item">
                        <a href="{{ route('admin.home.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-home-fill"></em></span>
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @canany(['view categories', 'view stores', 'view reviews', 'view vouchers'])
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon {{ getNewIndicatorClassForAdmin('reviews') }}"><em class="icon ni ni-db-fill"></em></span>
                            <span class="nk-menu-text">Data</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @can('view stores')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.stores.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-cart-fill"></em></span>
                                    <span class="nk-menu-text">Stores</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view categories')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.categories.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-grid-alt-fill"></em></span>
                                    <span class="nk-menu-text">Categories</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view reviews')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.reviews.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon {{ getNewIndicatorClassForAdmin('reviews') }}"><em class="icon ni ni-notice"></em></span>
                                    <span class="nk-menu-text">Store Reviews</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view vouchers')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.vouchers.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-money"></em></span>
                                    <span class="nk-menu-text">Vouchers</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view charities')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.charities.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-star"></em></span>
                                    <span class="nk-menu-text">Charities</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view countries')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.countries.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-flag"></em></span>
                                    <span class="nk-menu-text">Countries</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    @endcanany
                    @canany(['view cashbacks', 'add cashbacks', 'view cashouts'])
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon {{ getNewIndicatorClassForAdmin('sales') }}"> <em class="icon ni ni-sign-gbp"></em></span>
                            <span class="nk-menu-text">Sales</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @can('view cashbacks')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.commissions.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-sign-gbp"></em></span>
                                    <span class="nk-menu-text">Cashbacks</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view cashbouts')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.cashouts.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon {{ getNewIndicatorClassForAdmin('sales') }}"> <em class="icon ni ni-cc-alt2-fill"></em></span>
                                    <span class="nk-menu-text">Cashouts</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    @endcanany
                    @can('view users')
                    <li class="nk-menu-item">
                        <a href="{{ route('admin.users.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Users</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @endcan
                    @canany(['view clicks', 'view performance', 'view earnings'])
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                            <span class="nk-menu-text">Reports</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @can('view clicks')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.clicks.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-arrow-up-right"></em></span>
                                    <span class="nk-menu-text">Exit Clicks</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view performance')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.reports.performance') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-cc-alt2-fill"></em></span>
                                    <span class="nk-menu-text">Store Performance</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view earnings')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.reports.earnings') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-coin-gbp"></em></span>
                                    <span class="nk-menu-text">Earnings</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    @endcanany
                    @canany(['view networks', 'view settings', 'view languages', 'view translations'])
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-setting-fill"></em></span>
                            <span class="nk-menu-text">Settings</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @can('view settings')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.settings.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-setting-fill"></em></span>
                                    <span class="nk-menu-text">System Settings</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.email_templates.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-emails"></em></span>
                                    <span class="nk-menu-text">Email Templates</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @can('view networks')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.networks.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-activity-round-fill"></em></span>
                                    <span class="nk-menu-text">Networks</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view languages')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.languages.coming-soon') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-text2"></em></span>
                                    <span class="nk-menu-text">Languages</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view translations')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.translations.coming-soon') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                                    <span class="nk-menu-text">Translations</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            @can('view permissions')
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.settings.permissions') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-lock-alt-fill"></em></span>
                                    <span class="nk-menu-text">Permissions</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            <li class="nk-menu-item">
                                <a href="{{ route('admin.sliders.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-layers"></em></span>
                                    <span class="nk-menu-text">Sliders</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item">
                                <a href="/admin/menu" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-menu"></em></span>
                                    <span class="nk-menu-text">Menu</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    @endcanany
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon {{ getNewIndicatorClassForAdmin('tickets') }}"><em class="icon ni ni-layout-alt-fill"></em></span>
                            <span class="nk-menu-text">CMS</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @can('view tickets')
                            <li class="nk-menu-item ">
                                <a href="{{ route('admin.tickets.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon {{ getNewIndicatorClassForAdmin('tickets') }}"><em class="icon ni ni-chat-fill"></em></span>
                                    <span class="nk-menu-text">Tickets</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            @endcan
                            <li class="nk-menu-item ">
                                <a href="{{ route('admin.pages.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon "><em class="icon ni ni-text-rich"></em></span>
                                    <span class="nk-menu-text">Pages</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item ">
                                <a href="{{ route('admin.blogs.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon "><em class="icon ni ni-article"></em></span>
                                    <span class="nk-menu-text">Blog</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item ">
                                <a href="{{ route('admin.testimonials.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon "><em class="icon ni ni-star-fill"></em></span>
                                    <span class="nk-menu-text">Testimonials</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item ">
                                <a href="{{ route('admin.seo.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon "><em class="icon ni ni-menu"></em></span>
                                    <span class="nk-menu-text">Seo rules</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                        </ul>
                    </li>
                    @if(Auth::user()->hasRole('admin'))
                    <li class="nk-menu-item">
                        <a href="{{url('docs')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-file-doc"></em></span>
                            <span class="nk-menu-text">API Docs</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @endif
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>
