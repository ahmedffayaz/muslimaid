<div class="nk-sidebar nk-sidebar-fixed is-light is-compact" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">

            
            <a href="{{route('admin.home.index')}}" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="@isset($settings['dashboard_logo']){{asset('storage/dashboard/images/logo/'.$settings['dashboard_logo'])}}@else{{asset('admin-dashboard/images/logo.png')}}@endif"  alt="logo">
                <img class="logo-dark logo-img" src="@isset($settings['dashboard_logo']){{asset('storage/dashboard/images/logo/'.$settings['dashboard_logo'])}}@else{{asset('admin-dashboard/images/logo-dark.png')}}@endif" alt="logo-dark">
                <img class="logo-small logo-img logo-img-small" src="@isset($settings['dashboard_small_logo']){{asset('storage/dashboard/images/logo/'.$settings['dashboard_small_logo'])}}@else{{asset('admin-dashboard/images/logo-small.png')}}@endif" alt="logo-small">
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
                        <a href="{{route('admin.home.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-home-fill"></em></span>
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-db-fill"></em></span>
                            <span class="nk-menu-text">Data</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{route('admin.categories.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-grid-alt-fill"></em></span>
                                    <span class="nk-menu-text">Categories</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item">
                                <a href="{{route('admin.stores.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-cart-fill"></em></span>
                                    <span class="nk-menu-text">Stores</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item">
                                <a href="{{route('admin.reviews.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-notice"></em></span>
                                    <span class="nk-menu-text">Store Reviews</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item">
                                <a href="{{route('admin.vouchers.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-money"></em></span>
                                    <span class="nk-menu-text">Vouchers</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-sign-gbp"></em></span>
                            <span class="nk-menu-text">Cashbacks</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{route('admin.commissions.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-sign-gbp"></em></span>
                                    <span class="nk-menu-text">Manage Cashbacks</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item">
                                <a href="{{route('admin.commissions.create_multiple')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-sign-gbp"></em></span>
                                    <span class="nk-menu-text">Add Multiple Cashbacks</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item">
                                <a href="{{route('admin.cashouts.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-cc-alt2-fill"></em></span>
                                    <span class="nk-menu-text">Cashouts</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="{{route('admin.users.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Users</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                   
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                            <span class="nk-menu-text">Reports</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{route('admin.clicks.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-arrow-up-right"></em></span>
                                    <span class="nk-menu-text">Exit Clicks</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            
                            {{-- <li class="nk-menu-item">
                                <a href="{{route('admin.cashouts.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-cc-alt2-fill"></em></span>
                                    <span class="nk-menu-text">Cashouts</span>
                                </a>
                            </li><!-- .nk-menu-item --> --}}
                            <li class="nk-menu-item">
                                <a href="{{route('admin.reports.performance')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-cc-alt2-fill"></em></span>
                                    <span class="nk-menu-text">Store Performance</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item">
                                <a href="{{route('admin.reports.earnings')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-cc-alt2-fill"></em></span>
                                    <span class="nk-menu-text">Earnings</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-setting-fill"></em></span>
                            <span class="nk-menu-text">Settings</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{route('admin.networks.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-activity-round-fill"></em></span>
                                    <span class="nk-menu-text">Networks</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item">
                                <a href="{{route('admin.settings.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-setting-fill"></em></span>
                                    <span class="nk-menu-text">Settings</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            
                            <li class="nk-menu-item">
                                <a href="{{route('admin.languages.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-text2"></em></span>
                                    <span class="nk-menu-text">Languages</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item">
                                <a href="{{route('admin.translations.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                                    <span class="nk-menu-text">Translations</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                            
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item ">
                        @php
                         $new_tickets = \App\Models\Ticket::where('new_ticket',1)->get();    
                        @endphp
                        <a href="{{route('admin.tickets.index')}}" class="nk-menu-link @if(count($new_tickets)) icon-status icon-status-info @endif">
                            <span class="nk-menu-icon "><em class="icon ni ni-chat-fill"></em></span>
                            <span class="nk-menu-text">Tickets</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    
                   
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>

