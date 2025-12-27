<!-- Main sidebar -->
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-section sidebar-user my-1">
            <div class="sidebar-section-body">
                <div class="media">
                    <a href="#" class="mr-3">
                        <img src="https://ui-avatars.com/api/?name={{ \App\Helpers\S::getUserName() }}"
                            class="rounded-circle" alt="">
                    </a>

                    <div class="media-body">
                        <div class="font-weight-semibold">{{ \App\Helpers\S::getUserName() }}</div>
                        <div class="font-size-sm line-height-sm opacity-50">
                            {{ \App\Helpers\S::getUser() ? \App\Helpers\S::getUser()->username : '' }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <button type="button"
                            class="btn btn-outline-light-100 text-white border-transparent btn-icon rounded-pill btn-sm sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                            <i class="icon-transmission"></i>
                        </button>

                        <button type="button"
                            class="btn btn-outline-light-100 text-white border-transparent btn-icon rounded-pill btn-sm sidebar-mobile-main-toggle d-lg-none">
                            <i class="icon-cross2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->


        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                {{-- Header --}}
                <li class="nav-item-header pt-0">
                    <div class="text-uppercase font-size-xs line-height-xs">
                        {{ \App\Helpers\S::getUserRole()->target_type }}</div> <i class="icon-menu"
                        title="{{ \App\Helpers\S::getUserRole()->target_type }}"></i>
                </li>

                @if (\App\Helpers\S::getUserSetting('PG_SANDBOX') && \App\Helpers\S::getUserSetting('PG_SANDBOX')->value == 1)
                    <li class="nav-item">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle w-100 d-flex justify-content-between" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                @if (config('app.env') == 'production')
                                    Production
                                @else
                                    Sandbox
                                @endif
                            </button>
                            <form class="dropdown-menu w-100"
                                @if (config('app.env') == 'production') action="{{ config('services.app.sandbox.url') . route('cms.env.post', ['token' => session()->get('jwt_token'), 'username' => base64_encode(\App\Helpers\S::getUser()->username)], false) }}"
                            @else
                            action="{{ config('services.app.production.url') . route('cms.env.post', ['token' => session()->get('jwt_token'), 'username' => base64_encode(\App\Helpers\S::getUser()->username)], false) }}" @endif
                                method="post" aria-labelledby="dropdownMenuButton">
                                @csrf
                                <button type="submit" class="dropdown-item w-100" href="#">
                                    @if (config('app.env') == 'production')
                                        Sandbox
                                    @else
                                        Production
                                    @endif
                                </button>
                            </form>
                        </div>
                    </li>
                @endif

                {{-- ,/Header --}}

                @foreach (\App\Helpers\SidebarHelper::getSidebarMenuFiltered() as $menu_item)
                    @if ($menu_item->type == \App\Models\SidebarMenuItem::TYPE_MENU)
                        <li class="nav-item">
                            <a href="{{ $menu_item->route }}" class="nav-link">
                                <i class="{{ $menu_item->icon_class }}"></i><span>{{ $menu_item->title }}</span>
                            </a>
                        </li>
                    @elseif ($menu_item->type == \App\Models\SidebarMenuItem::TYPE_SUBMENU)
                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link"><i class="{{ $menu_item->icon_class }}"></i>
                                <span>{{ $menu_item->title }}</span></a>

                            <ul class="nav nav-group-sub" data-submenu-title="{{ $menu_item->title }}">
                                @foreach ($menu_item->sub_menu_items as $sub_menu_item)
                                    <li class="nav-item">
                                        <a href="{{ $sub_menu_item->route }}" class="nav-link"><i
                                                class="{{ $sub_menu_item->icon_class }}"></i>
                                            <span>{{ $sub_menu_item->title }}</span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach



                {{-- <!-- Main -->
                <li class="nav-item">
                    <a href="{{ route("cms.dashboard") }}" class="nav-link active">
                        <i class="icon-home4"></i><span>Dashboard</span>
                    </a>
                </li>
                <!-- /main -->

                <!-- Layout -->
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-stack2"></i> <span>Page layouts</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Page layouts">
                        <li class="nav-item"><a href="layout_static.html" class="nav-link">Static layout</a></li>
                        <li class="nav-item"><a href="layout_no_header.html" class="nav-link">No header</a></li>
                        <li class="nav-item"><a href="layout_no_footer.html" class="nav-link">No footer</a></li>
                        <li class="nav-item-divider"></li>
                        <li class="nav-item"><a href="layout_fixed_header.html" class="nav-link">Fixed header</a></li>
                        <li class="nav-item"><a href="layout_fixed_footer.html" class="nav-link">Fixed footer</a></li>
                        <li class="nav-item-divider"></li>
                        <li class="nav-item"><a href="layout_2_sidebars_1_side.html" class="nav-link">2 sidebars on 1 side</a></li>
                        <li class="nav-item"><a href="layout_2_sidebars_2_sides.html" class="nav-link">2 sidebars on 2 sides</a></li>
                        <li class="nav-item"><a href="layout_3_sidebars.html" class="nav-link">3 sidebars</a></li>
                        <li class="nav-item-divider"></li>
                        <li class="nav-item"><a href="layout_boxed_page.html" class="nav-link">Boxed page</a></li>
                        <li class="nav-item"><a href="layout_boxed_content.html" class="nav-link">Boxed content</a></li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-tree5"></i> <span>Menu levels</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Menu levels">
                        <li class="nav-item"><a href="#" class="nav-link"><i class="icon-IE"></i> Second level</a></li>
                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link"><i class="icon-firefox"></i> Second level with child</a>
                            <ul class="nav nav-group-sub">
                                <li class="nav-item"><a href="#" class="nav-link"><i class="icon-android"></i> Third level</a></li>
                                <li class="nav-item nav-item-submenu">
                                    <a href="#" class="nav-link"><i class="icon-apple2"></i> Third level with child</a>
                                    <ul class="nav nav-group-sub">
                                        <li class="nav-item"><a href="#" class="nav-link"><i class="icon-html5"></i> Fourth level</a></li>
                                        <li class="nav-item"><a href="#" class="nav-link"><i class="icon-css3"></i> Fourth level</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item"><a href="#" class="nav-link"><i class="icon-windows"></i> Third level</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="icon-chrome"></i> Second level</a></li>
                    </ul>
                </li>
                <!-- /layout --> --}}

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
<!-- /main sidebar -->
