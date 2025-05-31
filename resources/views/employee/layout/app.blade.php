<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <title>IMS |</title>
    <meta charset="utf-8" />
    <meta content="{{ csrf_token() }}" name="csrf-token" id="csrf-token">
    <meta content="{{ url('assets') }}" name="asset-url">
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    {{-- <link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>

<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" class="app-default">

    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if ( document.documentElement ) {
            if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if ( localStorage.getItem("data-bs-theme") !== null ) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">

            @include('employee.layout.header')

            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">

                <div id="kt_app_toolbar" class="app-toolbar  py-6 ">

                    <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex align-items-start ">
                        <div class="d-flex flex-column flex-row-fluid">
                            <div class="d-flex align-items-center pt-1">
                                <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
                                    <li class="breadcrumb-item text-white fw-bold lh-1">
                                        <a href="index.html" class="text-white text-hover-primary">
                                            <i class="ki-outline ki-home text-gray-700 fs-6"></i> </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>
                                    </li>
                                    <li class="breadcrumb-item text-white fw-bold lh-1 current-directory text-capitalize">

                                    </li>
                                </ul>
                            </div>
                            {{-- <div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-13 pb-6">
                                <div class="page-title me-5">
                                    <h1 class="page-heading text-capitalize d-flex text-white fw-bold fs-2 flex-column justify-content-center my-0 current-directory">
                                    </h1>
                                </div>
                                <div class="d-flex align-self-center flex-center flex-shrink-0">
                                    <a href="#"
                                        class="btn btn-flex btn-sm btn-outline btn-active-color-primary btn-custom px-4"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
                                        <i class="ki-outline ki-plus-square fs-4 me-2"></i> Invite
                                    </a>

                                    <a href="#"
                                        class="btn btn-sm btn-active-color-primary btn-outline btn-custom ms-3 px-4"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">
                                        Set Your Target
                                    </a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="app-container  container-xxl">

                    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-outline ki-arrow-up"></i>
    </div>

    <script>
        var hostUrl = "assets/index.html";
    </script>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

    <script type="text/javascript">
        var asset_url = $('meta[name="asset-url"]').attr("content");
        var csrf_token = $('meta[name="csrf-token"]').attr("content");
        var app = $("#kt_app_content");
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
        var page_block = new KTBlockUI(document.querySelector('.app-wrapper'), {
            message: `<div class="blockui-message"><span class="spinner-border text-primary"></span>Loading. . .</div>`,
        });
    </script>

    <script src="{{ asset('js/employee/navbar.js') }}" type="module"></script>
</body>
</html>
