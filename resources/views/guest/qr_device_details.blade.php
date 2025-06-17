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

    <div id="kt_app_content_container" class="app-container  container-xxl">

        <div class="card my-10 card-accountability-details" id="">
            <div class="card-header">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Item Details</h3>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Item: </label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $data['name'] }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Description: </label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">
                            {!! $data['description'] !!}
                        </span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Status: </label>
                    <div class="col-lg-8">
                        <span class="badge badge-{{ $data['status_badge']['class'] }}">
                            {{ $data['status_badge']['label'] }}
                        </span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Tag Number: </label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $data['tag_number'] }}</span>
                    </div>
                </div>
                @if($data['serial_number'])
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">Serial Number: </label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $data['serial_number'] }}</span>
                        </div>
                    </div>
                @endif
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Price: </label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">P{{ $data['price'] }}</span>
                    </div>
                </div>
                @if($data['warranty_end_at'])
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">Warranty End at: </label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $data['warranty_end_at'] }}</span>
                        </div>
                    </div>
                @endif
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Remarks: </label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $data['remarks'] }}</span>
                    </div>
                </div>
                {{-- @if(isset($data->returned_at))
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">Returned At: </label>

                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ date('M d, Y',strtotime($data->returned_at)) }}</span>
                        </div>
                    </div>
                @endif
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Issued By: </label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $data->issued_by_emp->fullname() }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Received By: </label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $data->received_by_emp->fullname() }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">
                        Accountability Status:
                    </label>
                    <div class="col-lg-8 d-flex align-items-center">
                        <span class="badge badge-{{ $data->status==1?'success':'danger' }}">{{ $data->status==1?'Active':'Inactive' }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Active Custodian: </label>
                    <div class="col-lg-8">
                        <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{ $data->issued_to_status_1_count }} personnel</a>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Active Item: </label>
                    <div class="col-lg-8">
                        <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{ $data->accountability_item_status_1_count }} item</a>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">
                        Item Returned:
                    </label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $data->accountability_item_status_2_count }} item</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">
                        Remarks:
                    </label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $data->remarks }}</span>
                    </div>
                </div> --}}
            </div>
        </div>

        <div class="card my-10 card-accountability-history" id="">
            <div class="card-header">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Accountability History</h3>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="table-responsive">
                    <table class="table table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="">#</th>
                                <th class="">Accountability No.</th>
                                <th class="">Issued By</th>
                                <th class="">Issued At</th>
                                <th class="">Returned At </th>
                                <th class="">Status</th>
                                <th class="">Accountable To </th>
                                <th class="">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class=" fw-semibold">
                            @foreach($accountability_history as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row['form_no'] }}</td>
                                    <td>{{ $row['issued_by'] }}</td>
                                    <td>{{ $row['issued_at'] }}</td>
                                    <td>{{ $row['returned_at'] }}</td>
                                    <td>{{ $row['accountable_to'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $row['status_badge']['class'] }}">
                                            {{ $row['status_badge']['label'] }}
                                        </span>
                                    </td>
                                    <td>{{ $row['remarks'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card my-10 card-repair-history" id="">
            <div class="card-header">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Repair History</h3>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="">#</th>
                                <th class="">Repair Type</th>
                                <th class="">Start At</th>
                                <th class="">End At</th>
                                <th class="">Created By</th>
                                <th class="">Status </th>
                                <th class="">Description</th>
                            </tr>
                        </thead>
                        <tbody class=" fw-semibold">
                            @foreach($repair_history as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row['repair_type'] }}</td>
                                    <td>{{ $row['start_at'] }}</td>
                                    <td>{{ $row['end_at'] }}</td>
                                    <td>{{ $row['initialize_by'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $row['status_badge']['class'] }}">
                                            {{ $row['status_badge']['label'] }}
                                        </span>
                                    </td>
                                    <td>{{ $row['description'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        var blockUI = new KTBlockUI(document.querySelector('.app-default'), {
            message: `<div class="blockui-message"><span class="spinner-border text-primary"></span>Loading. . .</div>`,
        });
    </script>
