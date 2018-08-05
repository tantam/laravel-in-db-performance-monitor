<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin Monitor | @yield('title')</title>

        <!-- Scripts -->
        <script src="{{ asset('js/inDbPerformanceMonitor.js') }}" defer></script>
        @stack('scripts')

        <!-- Styles -->
        <link href="{{ asset('css/inDbPerformanceMonitor.css') }}" rel="stylesheet">
        @stack('styles')

    </head>
    <body>
        <div id="app">
            <br/>
            <div class="container">
                <div class="row" style="padding: 0px; margin: 0px; line-height: 0px">
                    <div class="col-md-12" style="padding: 0px; margin: 0px">
                        @if(session('__asamir_token'))
                        <ol class="breadcrumb">
                            <li><a href="{{url('admin-monitor/dashboard')}}" class="">Dashboard</a></li>
                            <li><a href="{{url('admin-monitor/requests')}}" class="">Requests List</a></li>
                            <li><a href="{{url('admin-monitor/statistics-report')}}" class="">Statistics Report</a></li>
                            <li><a href="{{url('admin-monitor/errors-report')}}" class="">Errors Report</a></li>
                            <li>Latest By 
                                <a href="{{url('admin-monitor/request/-1')}}" class="">Session ID</a>
                                or
                                <a href="{{url('admin-monitor/request/-2')}}" class="">IP</a></li>
                            <li><a href="{{url('admin-monitor/change-password')}}" class="">Change Password</a></li>
                            <li><a href="{{url('admin-monitor/logout')}}" class="">Exit</a></li>
                            <li style="float: right"><h5 style="text-align: right; bottom: 0px"><span class="label label-warning">Your Session ID = {{session()->getId()}}</span> - <span class="label label-warning">Your IP = {{request()->ip()}} </span></h5></li>
                        </ol>           
                        @endif
                    </div>
                </div>
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(session('alert-' . $msg))
                    <p class="alert alert-{{ $msg }}">{{ session('alert-' . $msg) }}</p>
                    @endif
                    @endforeach
                </div>
            </div>

            @yield('content')
            <div class="container">
                <div style="text-align: center; padding: 5px; bottom: 0px; width: 100%" class="alert-success">
                    <h5>© {{date('Y')}} Copyrights: <a href="#">Ahmed Samir</a> | <a href="https://github.com/engahmed99/laravel-in-db-performance-monitor">GitHub</a></h5>
                </div>
            </div>
            <br/>
            <br/>
        </div>
    </body>
</html>
