<aside class="main-sidebar sidebar-info-primary elevation-4 fixed" style="position: fixed; background: #063D58">
    <a href="" class="brand-link" style="text-decoration: none; ">
        <img  class="brand-image" src="{{ asset('images/Rams_logo.png') }}" alt="">
        <span class="brand-text" >{{ __('BMS') }}</span>
    </a>
    <div class="sidebar" style="background: #063D58">
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
                    <a href="{{route('users')}}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p class="text-sm">
                            {{ __('Users') }}
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
                    <a href="{{route('external-services')}}" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-file-invoice-dollar"></i>
                        <p class="text-sm">
                            {{ __('Services') }}
                            {{-- <i class="right fas fa-angle-left"></i> --}}
                        </p>
                    </a>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item bg-dark">
                            <a href="#" class="nav-link">
                                <p>{{ __('Internal') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('external-services')}}" class="nav-link">
                                <p>{{ __('External') }}</p>
                            </a>
                        </li>
                    </ul> --}}
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
                @if (Auth::user()->Role === 'Admin' || Auth::user()->Role === 'Accountant')
                <li class="nav-item">
                    <a href="{{route('settings')}}" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p class="text-sm">
                            {{ __('Settings') }}
                        </p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-file-import"></i>
                        <p class="text-sm">
                            {{ __('Archives') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('chart-of-accounts')}}" class="nav-link">
                        <i class="nav-icon fas fa-file-import"></i>
                        <p class="text-sm">
                            {{ __('Chart of Accounts') }}
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{route('billings')}}" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p class="text-sm">
                            {{ __('Billings') }}
                        </p>
                    </a>
                </li> --}}
            </ul>
        </nav>
    </div>
</aside>