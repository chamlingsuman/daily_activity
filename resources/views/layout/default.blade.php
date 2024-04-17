{{-- Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ Metronic::printAttrs('html') }}
    {{ Metronic::printClasses('html') }}>

<head>
    <meta charset="utf-8" />

    {{-- Title Section --}}
    <title>{{ config('app.name') }} | @yield('title', $page_title ?? '')</title>

    {{-- Meta Data --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="@yield('page_description', $page_description ?? '')" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    {{-- Favicon --}}
    {{-- <link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico') }}" /> --}}
    <link rel="shortcut icon" href="{{ asset('sajilobroker.jpg') }}" />

    {{-- Fonts --}}
    {{ Metronic::getGoogleFontsInclude() }}

    {{-- Global Theme Styles (used by all pages) --}}
    @foreach (config('layout.resources.css') as $style)
        <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($style)) : asset($style) }}"
            rel="stylesheet" type="text/css" />
    @endforeach

    {{-- Layout Themes (used by all pages) --}}
    @foreach (Metronic::initThemes() as $theme)
        <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($theme)) : asset($theme) }}"
            rel="stylesheet" type="text/css" />
    @endforeach

    <style>
        .display-sm-none {
            display: flex;
        }

        .card {
            border: none;
        }

        .checkbox-lg {
            height: 2em;
            width: 2em;
        }

        .nav-tabs {
            border-bottom: none !important;
        }

        @media (max-width: 991.98px) {
            .display-sm-none {
                display: none !important;
            }
        }

        .select2-container .select2-selection--single {
            height: calc(1.5em + 1.3rem + 2px) !important;
            padding: 0.65rem 1rem !important;
            font-size: 1rem !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0 !important;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #E4E6EF !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
        }

        .nav.nav-tabs.nav-tabs-line .nav-link:hover:not(.disabled),
        .nav.nav-tabs.nav-tabs-line .nav-link.active,
        .nav.nav-tabs.nav-tabs-line .show>.nav-link {
            background-color: transparent;
            border: 0;
            border-bottom: 1px solid #509963;
            transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .nav .show>.nav-link,
        .nav .nav-link:hover:not(.disabled),
        .nav .nav-link.active {
            color: #509963;
        }

        #top-navbar a.nav-link {
            font-size: 1em;
            padding: 1em;
            color: #141617
        }

        #top-navbar a.nav-link.active {
            border-top: 1px solid #65bd7d;
            color: #65bd7d;
        }

        #top-navbar a.nav-link:hover {
            border-top: 1px solid #65bd7d;
            color: #65bd7d;
        }

        .dropdown-toggle.nav-link:after,
        .dropdown-toggle.btn:after {

            content: "";
        }

        .dropdown-menu {
            border-top: 2px solid #65bd7d;

        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }


        h2 {
            margin-bottom: 28px;
            font-size: 20px;
        }


        .contact {
            padding-left: 30px;
        }

        a.text-hover-green {
            font-size: 16px;
            color: hsla(0, 0%, 100%, calc(100% - 20%));
        }

        a.text-hover-green:hover {
            color: #65bd7d !important;
        }

        .copyright {
            background-color: #141617;
            padding: 20px 30px;
        }

        .copyright p {
            font-size: 14px;
            color: #FFFFFF99;
        }

        .card-custom2 {
            position: relative;
            min-width: 0;
            word-wrap: break-word;
            background-color: #ffffff;
            background-clip: border-box;
            border-radius: 0.42rem;
            border: 0;
        }


        .form-group label {
            font-size: 14px !important;
        }

        input,
        select,
        select option {
            font-size: 14px !important;
        }

        .tooltip-inner {
            text-align: left;
        }

        .big-radio {
            height: 2em;
            width: 2em;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            color: #101221;
            text-decoration: none;
            background-color: #EBEDF3;
        }

        .dropdown-item.active,
        .dropdown-item:active {
            color: #101221 !important;
            text-decoration: none;
            background-color: #EBEDF3 !important;
        }
    </style>
    {{-- Includable CSS --}}
    @yield('styles')
</head>

<body {{ Metronic::printAttrs('body') }} {{ Metronic::printClasses('body') }}>

    @if (config('layout.page-loader.type') != '')
        @include('layout.partials._page-loader')
    @endif

    @include('layout.base._layout')



    {{-- Global Config (global config for global JS scripts) --}}
    <script>
        var KTAppSettings =
            @php
                echo json_encode(config('layout.js'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            @endphp;
    </script>

    {{-- Global Theme JS Bundle (used by all pages) --}}
    @foreach (config('layout.resources.js') as $script)
        <script src="{{ asset($script) }}" type="text/javascript"></script>
    @endforeach
    <script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>

    {{-- Includable JS --}}
    @stack('script')
    @yield('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            // startConnection();
            $("form").submit(function() {
                KTApp.block('#kt_blockui_card', {
                    overlayColor: '#000000',
                    state: 'primary',
                    message: 'Processing...',

                });
            });



            $('.password, .opass, .cpass, .pass').on('copy paste cut', function(e) {
                e.preventDefault();
            });

            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            $('.deleteBtn').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                swal.fire({
                    title: "Delete!",
                    text: "Are you sure you want to delete this?",
                    icon: "question",
                    buttonsStyling: false,
                    confirmButtonText: "Yes I'm sure",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-default"
                    }
                }).then(function(result) {
                    console.log(result);
                    if (result.hasOwnProperty('value')) {
                        $this.parents('form').submit();
                    }
                });
            });
            $('.deleteBtnforholiday').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                swal.fire({
                    title: "Delete!",
                    text: "Are you sure you want to delete this holiday?",
                    icon: "question",
                    buttonsStyling: false,
                    confirmButtonText: "Yes I'm sure",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-default"
                    }
                }).then(function(result) {
                    console.log(result);
                    if (result.hasOwnProperty('value')) {
                        $this.parents('form').submit();
                    }
                });
            });

            $('.disableRole').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                swal.fire({
                    title: "Disable!",
                    text: "Are you sure you want to disable?",
                    icon: "question",
                    buttonsStyling: false,
                    confirmButtonText: "Yes I'm sure",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-default"
                    }
                }).then(function(result) {
                    console.log(result);
                    if (result.hasOwnProperty('value')) {
                        $this.parents('form').submit();
                    }
                });
            });

            $('.enableRole').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                swal.fire({
                    title: "Enable!",
                    text: "Are you sure you want to enable?",
                    icon: "question",
                    buttonsStyling: false,
                    confirmButtonText: "Yes I'm sure",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-default"
                    }
                }).then(function(result) {
                    console.log(result);
                    if (result.hasOwnProperty('value')) {
                        $this.parents('form').submit();
                    }
                });
            });


        });
    </script>

</body>

</html>
