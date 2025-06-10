<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IMS | Employee Registration</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="shortcut icon" href="{{ asset('images/pnc-logo.png') }}" /> --}}
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/employee_registration/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <style>
        /* Make the parent div non-scrollable */
        .parent-div {
            height: 100vh;
            /* Adjust the height as needed */
            overflow: hidden;
            /* Hide overflow content */
            /* background-image: url("{{ asset('images/hris-bg-1.jpg') }}"); */
        }

        /* Make the child div scrollable */
        .child-div {
            height: 100%;
            overflow-y: auto;
            /* Enable vertical scrollbar if content exceeds height */
        }

        .child-div::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default app-page">
    <script>
        var defaultThemeMode = "light";
        var themeMode;

        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
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
    <!--begin::Page loading(append to body)-->
    <div class="page-loader flex-column bg-dark bg-opacity-25">
        <span class="spinner-border text-primary" role="status"></span>
        <span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
    </div>
    <!--end::Page loading-->

    <div class="container-fluid parent-div">
        <div class="child-div">
            <div class="d-flex flex-column flex-root">
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content  flex-column-fluid">
                            <div class="app-container container-xxl pt-10" id="Page">
                                <div id="kt_app_content_container" class="app-container container-fluid mb-10">
                                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100 bg-dark"
                                        style="background-image: url('{{ asset('images/wave-bg-dark.svg') }}'); background-size: 700px 500px;">
                                        <!--begin::Card body-->
                                        <div class="card-body d-flex flex-column mb-3">
                                            <div class="row">
                                                <div
                                                    class="col-md-8 d-flex flex-column justify-content-center align-items-start">
                                                    <h4 class="fw-bold text-white">Welcome to {{ config('company.company_name') }} </h4>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fs-2hx fw-bolder me-6 text-white">Employee
                                                            Onboarding</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 d-flex justify-content-end">
                                                    {{-- <img src="{{ asset('images/pnc-logo.png') }}" height="120"
                                                        alt=""> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                </div>
                                @include('employee.pages.settings.employee_list.employee_registration.content')
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <script type="text/javascript">
        var hostUrl = "assets/index.html";
        var asset_url = $('meta[name="asset-url"]').attr("content");
        var csrf_token = $('meta[name="csrf-token"]').attr("content");
        var app = $("#kt_app_main");
        var BASE_URL = window.location.host;
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
        var page_block = new KTBlockUI(document.querySelector('.app-page'), {
            message: `<div class="blockui-message"><span class="spinner-border text-primary"></span>Loading. . .</div>`,
        });
    </script>

    <script src="{{ asset('js/employee/fn_controller/settings/employee_list/employee_registration.js') }}" type="module"></script>

</body>

</html>
