<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Hisrixork | Calendrier de congés</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/color.css') }}">
        <link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">

        <!-- Styles -->
        <style>
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                height: 0;
                width: 0;
                border-radius: 100%;
                background: #fff;
                margin: auto;
                opacity: 0;
                z-index: -1;
                -webkit-transition: height 1s, width 1s, border-radius .3s .6s, opacity 1s;
                -moz-transition: height 1s, width 1s, border-radius .3s .6s, opacity 1s;
                -ms-transition: height 1s, width 1s, border-radius .3s .6s, opacity 1s;
                -o-transition: height 1s, width 1s, border-radius .3s .6s, opacity 1s;
                transition: height 1s, width 1s, border-radius .3s .6s, opacity 1s;
            }

            .sidebar.in {
                height: 100%;
                width: 100%;
                border-radius: 0;
                opacity: 1;
                background: #fff;
                z-index: 1;
            }

            .sidebar-toggle,
            .export,
            .pick-date {
                position: fixed;
                right: 30px;
                bottom: 30px;
                height: 60px;
                width: 60px;
                /*background: #000;*/
                z-index: 2;
            }

            .export {
                right: 100px;
            }

            .pick-date {
                right: 170px;
            }

            .content {
            }

            .row {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }

            @media screen and (max-width: 575px) {

                .sidebar {
                    position: fixed;
                    top: inherit;
                    right: 0;
                    height: 50px;
                    -webkit-box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.18), 0 -4px 15px 0 rgba(0, 0, 0, 0.15);
                    -moz-box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.18), 0 -4px 15px 0 rgba(0, 0, 0, 0.15);
                    box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.18), 0 -4px 15px 0 rgba(0, 0, 0, 0.15);
                }

                .content {
                    /*margin-bottom: 70px;*/
                }
            }

        </style>
        @yield('stylesheet')
    </head>
    <body>
        <div class="main container-fluid p-0">

            @if(!in_array(\Illuminate\Support\Facades\Request::route()->getName(), ['login', 'register']))
                <div class="sidebar-toggle bg-color-4 rounded-circle d-flex justify-content-center align-items-center z-depth-1-half cursor-pointer"
                     data-url="{{ auth()->user() ? route('logout') : route('login') }}"
                     data-type="{{ auth()->user() ? 'logout' : 'login' }}">
                    {{--<i class="fa fa-times text-white"></i>--}}
                    @auth
                        <i class="fa fa-sign-out-alt text-white"></i>
                        {{--<i class="fa fa-sign-in-alt text-white"></i>--}}
                    @else
                        <i class="fa fa-sign-in-alt text-white"></i>

                    @endauth
                    {{--<i class="fa fa-bars text-white"></i>--}}
                </div>
            @endif

            <div class="sidebar col-12 d-flex justify-content-center align-items-center">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>


            <div class="content">

                @yield('content')

            </div>


        </div>

        @include('includes.formload')

        <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
        <script type="application/javascript">
            $(function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                axios.defaults.headers.common = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }

                $('.sidebar-toggle').click(function () {
                    // $(this).find('.fa').toggleClass('fa-times fa-bars')
                    // $('.sidebar').toggleClass('in')

                    if ($(this).attr('data-type') === 'login')
                        location.href = $(this).attr('data-url')
                    else
                        axios.post($(this).attr('data-url')).then((r) => {
                            location.reload()
                        })

                })

            })

            let getUrlParameter = function getUrlParameter(sParam) {
                let sPageURL = decodeURIComponent(window.location.search.substring(1)),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');

                    if (sParameterName[0] === sParam) {
                        return sParameterName[1] === undefined ? true : sParameterName[1];
                    }
                }
            }

        </script>
        @yield('javascript')
    </body>
</html>
