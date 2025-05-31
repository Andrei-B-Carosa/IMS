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

    <div id="kt_app_content_container" class="app-container  container-xxl ">
        <div class="card my-10">
            <div class="card-body border-top p-9">
                <div class="">
                    <h1>Company Device Registration</h1>
                    <p class="fs-6 fw-semibold text-gray-600 py-2">
                        Fill up the form to register the company devices issued to you. Contact MIS for concerns.
                    </p>
                </div>
            </div>
        </div>

        <div class="card card-flush pt-3 mb-5 mb-xl-10">
            <div class="card-header">
                <div class="card-title">
                    <h2 class="fw-bold">{{ $data['device_type'] }} Details</h2>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="mb-10">
                    <div class="d-flex flex-wrap">
                        <div class="flex-equal me-5">
                            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                <tbody>
                                    @if ($data['device_type'] == 'Laptop')
                                        <tr>
                                            <td class="text-gray-500">Laptop Model:</td>
                                            <td class="text-gray-800"> {{ $data['model'] }} </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="text-gray-500 min-w-175px w-175px">User Account:</td>
                                        <td class="text-gray-800 min-w-200px">
                                            <a href="javascript:;" class="text-gray-800 text-hover-primary">{{ $data['user_account'] }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-500">Device Name:</td>
                                        <td class="text-gray-800"> {{ $data['device_name'] }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex-equal">
                            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                <tbody>
                                    <tr>
                                        <td class="text-gray-500 min-w-175px w-175px">Operating System:</td>
                                        <td class="text-gray-800 min-w-200px">
                                            <a href="javascript:;" class="text-gray-800">{{ $data['windows_version'] }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-500">Installation Date:</td>
                                        <td class="text-gray-800"> {{ date('m-d-Y',strtotime($data['os_installed_date'])) }} </td>
                                    </tr>
                                    @if ($data['device_type'] == 'Laptop')
                                        <tr>
                                            <td class="text-gray-500">Serial Number:</td>
                                            <td class="text-gray-800"> {{ $data['serial_number'] }} </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <h5 class="mb-4">Specification:</h5>
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                            <thead>
                                <tr class="border-bottom border-gray-200 text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-800">
                                <tr>
                                    <td>
                                        <label class="">CPU : {{ $data['cpu'] }}</label>
                                        <div class="text-muted"></div>
                                    </td>
                                </tr>
                                @foreach ($data['ram']['summary'] as $key => $ram)
                                    <tr>
                                        <td>
                                            <label class="">RAM : {{ $ram['size_gb'].' GB' }}</label>
                                            <div class="text-muted">({{ $ram['manufacturer']  }})</div>
                                        </td>
                                    </tr>
                                @endforeach

                                @foreach ($data['storage'] as $storage)
                                <tr>
                                    <td>
                                        <label class="">Storage : {{ $storage['size_gb'].' GB' }}</label>
                                        <div class="text-muted">({{ $storage['model']  }})</div>
                                    </td>
                                </tr>
                                @endforeach

                                @foreach ($data['gpu'] as $gpu)
                                <tr>
                                    <td>
                                        <label class="">GPU : {{ $gpu['name'] }}</label>
                                        <div class="text-muted">({{ $gpu['type']  }})</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($data['device_type'] !='Laptop')
            {{-- Monitor Details --}}
            <div class="card card-flush mb-10" id="">
                <div class="card-header pt-7" id="">
                    <div class="card-title">
                        <h2>Monitor Details</h2>
                    </div>
                </div>
                <div class="card-body pt-10">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                            <thead>
                                <tr class="border-bottom border-gray-200 text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-800">
                                @foreach ($data['monitors'] as $key => $monitors)
                                    <tr>
                                        <td>
                                            <label class="">{{ $monitors['name'] }}</label>
                                            <div class="text-muted">{{ $monitors['manufacturer']  }}</div>
                                        </td>
                                        <td>
                                            <label class="">{{ $monitors['serial_number'] }}</label>
                                            <div class="text-muted">Serial Number</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Other Accessories --}}
        <div class="card card-flush mb-10" id="">
            <div class="card-header pt-7" id="">
                <div class="card-title">
                    <h2>Other Accessories</h2>
                </div>
            </div>
            <div class="card-body pt-10">
                <form class="repeater-other-accessories">
                    <div data-repeater-list="other-accessories">
                        <div data-repeater-item>
                            <div class="form-group row mb-7">
                                <div class="col-md-7 fv-row">
                                    <label class="form-label">Accessories</label>
                                    <select class="form-select form-accessories" data-kt-repeater="select2" name="accessories" data-allow-clear="true" data-placeholder="Select an option">
                                        {!! $other_accessories !!}

                                    </select>
                                </div>
                                <div class="col-md-3 fv-row">
                                    <label class="form-label">Serial Number / Device ID</label>
                                    <input type="text" class="form-control"  name="serial_number" placeholder="Enter serial number" />
                                </div>
                                <div class="col-md-2 d-none">
                                    <a href="javascript:;" data-repeater-delete class="btn btn-flex btn-sm btn-light-danger mt-3 mt-md-9">
                                        <i class="ki-duotone ki-trash fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="javascript:;" data-repeater-create class="btn btn-flex btn-light-primary">
                            <i class="ki-duotone ki-plus fs-3"></i>
                            Add Accessories
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Employee Details --}}
        <div class="card card-flush mb-10" id="">
            <form class="repeater-issued-to">
                <div class="card-header pt-7 border-0" id="">
                    <div class="card-title">
                        <h2>Issued To</h2>
                    </div>
                    <div class="card-toolbar">
                        {{-- <a href="javascript:;" data-repeater-create class="btn btn-flex btn-light-primary">
                            <i class="ki-duotone ki-plus fs-3"></i>
                            Add Employee
                        </a> --}}
                    </div>
                </div>
                <div class="card-body pt-10">
                    <div data-repeater-list="issued-to" class="mb-5">
                        <div data-repeater-item>
                            <div class="form-group row mb-7">
                                <div class="col-md-10 fv-row">
                                    <label class="form-label required">Name</label>
                                    <select class="form-select" data-kt-repeater="select2" data-placeholder="Select an option" name="employee">
                                    </select>
                                </div>
                                <div class="col-md-2 d-none">
                                    <a href="javascript:;" data-repeater-delete class="btn btn-flex btn-sm btn-light-danger mt-3 mt-md-9">
                                        <i class="ki-duotone ki-trash fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="javascript:;" data-repeater-create class="btn btn-flex btn-light-primary">
                            <i class="ki-duotone ki-plus fs-3"></i>
                            Add Accessories
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Issued By --}}
        <div class="card card-flush mb-10" id="">
            <form class="issued-by">
                <div class="card-header pt-7 border-0" id="">
                    <div class="card-title">
                        <h2>Issued By & Acknowledgement</h2>
                    </div>
                </div>
                <div class="card-body pt-10">
                    <div class="form-group">
                        <div class="col-md-12 fv-row mb-7">
                            <label class="form-label required">Issued By</label>
                            <select class="form-select" data-kt-repeater="select2" data-placeholder="Select an option" name="issued_by">
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-column mb-5 fv-row rounded-3 p-7 border border-dashed border-gray-300">
                        <div class="fs-5 fw-bold form-label mb-3">
                            Device Registration Acknowledgment
                        </div>

                        <label class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="acknowledgement" value="1">
                            <span class="form-check-label text-gray-600">
                                I confirm that the details I entered are correct and I have received all of the devices listed in this registration.
                            </span>
                        </label>
                    </div>
                </div>
            </form>
        </div>

         <!--begin::Action buttons-->
        <div class="d-grid gap-2 mb-10">
            <button type="button" class="btn btn-primary btn-lg rounded-1 p-5 submit-device-registration">
                <span class="indicator-label">
                    Submit Registration
                </span>
                <span class="indicator-progress">
                    Please wait... <span
                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
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
        var blockUI = new KTBlockUI(document.querySelector('.app-default'), {
            message: `<div class="blockui-message"><span class="spinner-border text-primary"></span>Loading. . .</div>`,
        });
    </script>

    <script src="{{ asset('js/global/register_device.js') }}" type="module"></script>
</body>
