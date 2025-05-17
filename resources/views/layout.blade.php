<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('components.layout-header')

<div class="preloader flex-column justify-content-center align-items-center">
    <div class="loaders">
        <div class="load-inner load-one"></div>
        <div class="load-inner load-two"></div>
        <div class="load-inner load-three"></div>
        <span class="text">Loading...</span>
      </div>
</div>
@include('components.layout-navbar')
@include('components.layout-sidebar')

<body class="sidebar-mini">
    <div class="content-wrapper h-100" style="background-color: #E5E7EB;">
        @yield('content')
       
    </div>
        
</body>

@include('components.layout-footer')
@include('components.layout-scripts')
</html>