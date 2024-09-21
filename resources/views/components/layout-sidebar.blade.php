<aside class="main-sidebar sidebar-info-primary elevation-4 fixed" style="position: fixed;">
    <a href="" class="brand-link" style="text-decoration: none; ">
        <span class="brand-text" >{{ __('BMS') }}</span>
    </a>
    <div class="sidebar" style="">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <i class="fa-solid fa-gauge"></i>
                        <p class="text-sm">
                            {{ __('Dashboard') }}
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('clients')}}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p class="text-sm">
                            {{ __('Clients') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin-hub')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p class="text-sm">
                            {{ __('Activity Log') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-file-invoice-dollar"></i>
                        <p class="text-sm">
                            {{ __('Services') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Internal') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('external-services')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('External') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
               
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-chart-pie"></i>
                        <p class="text-sm">
                            {{ __('Financial Summary') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p class="text-sm">
                            {{ __('Bookkeeping') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p class="text-sm">
                            {{ __('Settings') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-file-import"></i>
                        <p class="text-sm">
                            {{ __('Archives') }}
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>