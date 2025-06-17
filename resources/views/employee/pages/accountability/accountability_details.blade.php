<div class="page-accountability-details">

    <div class="card mb-10 card-accountability-details" id="">
        <div class="card-header cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Accountability No. {{ $data->form_no ??' ' }}</h3>
            </div>

            @if($data->status !=2)
                <button class="btn btn-primary align-self-center edit-accountability" data-id="{{ Crypt::encrypt($data->id) }}">
                    Edit Details
                </button>
            @endif
        </div>
        <div class="card-body p-9">
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Issued At: </label>

                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ date('M d, Y',strtotime($data->issued_at)) }}</span>
                </div>
            </div>
            @if(isset($data->returned_at))
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
            </div>
        </div>
    </div>

    <div class="card card-flush py-4 mb-10 card-issued-items">
        <div class="card-header">
            <div class="card-title">
                <h2>Issued Items</h2>
            </div>
            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">
                    {{-- <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12 search"
                        placeholder="Search here ..." /> --}}
                    @if($data->status !=2)
                        <button class="btn btn-primary align-self-center add-item" data-id="{{ Crypt::encrypt($data->id) }}">
                            Add Item
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <x-elements.datatable id="issued-item" class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-elements.datatable>
        </div>
    </div>

    <div class="card card-flush py-4 mb-10 card-issued-to">
        <div class="card-header">
            <div class="card-title">
                <h2>Accountable To</h2>
            </div>
            <div class="card-toolbar">
                {{-- <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12 search"
                        placeholder="Search here ..." />
                </div> --}}
                @if($data->status !=2)
                    <button class="btn btn-primary align-self-center add-personnel" data-id="{{ Crypt::encrypt($data->id) }}">
                        Add Personnel
                    </button>
                @endif
            </div>
        </div>

        <div class="card-body pt-0">
            <x-elements.datatable id="issued-to" class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-elements.datatable>
        </div>
    </div>

    <div class="modal fade" id="modal-accountability-details" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header justify-content-center" id="">
                    <div class="text-center">
                        <h1 class="mb-3 modal-title">Accountability Details</h1>
                        <div class="text-muted fs-5">Fill-up the form and click
                            <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-body px-10">
                    <form id="form-accountability-details" modal-id="#modal-accountability-details" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="">
                        <div class="px-5">
                            <div class="d-flex flex-column col-12  fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fw-semibold fs-6 mb-2">Accountability No.</label>
                                <input type="text" name="form_no" class="form-control mb-3 mb-lg-0" value="">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">Issued At</label>
                                    <input type="text" name="date_issued" input-control="date-picker" default-date="" class="form-control form-select-solid mb-3 mb-lg-0 flatpickr">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                                <div class="fv-row mb-7 col-6 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">Returned At</label>
                                    <input type="text" name="returned_at" input-control="date-picker" default-date="" class="form-control form-select-solid mb-3 mb-lg-0 flatpickr">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <x-elements.select
                                        id="issued_by"
                                        name="issued_by"
                                        label="Issued By"
                                        :options="[]"
                                        placeholder="Select an option"
                                        selected=""
                                        class="fw-bold form-select-solid"
                                        data-control="select2"
                                        data-placeholder="Select an option"
                                        data-minimum-results-for-search="Infinity"
                                        data-allow-clear="true"
                                        disabled="false"
                                    />
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <x-elements.select
                                        id="received_by"
                                        name="received_by"
                                        label="Received By"
                                        :options="[]"
                                        placeholder="Select an option"
                                        selected=""
                                        class="fw-bold form-select-solid"
                                        data-control="select2"
                                        data-placeholder="Select an option"
                                        data-minimum-results-for-search="Infinity"
                                        data-allow-clear="true"
                                        disabled="false"
                                    />
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <x-elements.select
                                    id="accountability_status"
                                    name="accountability_status"
                                    label="Accountability Status"
                                    :options="['1' => 'Active', '2' => 'Inactive']"
                                    placeholder="Select an option"
                                    selected=""
                                    class="fw-bold form-select-solid"
                                    data-control="select2"
                                    data-placeholder="Select an option"
                                    data-minimum-results-for-search="Infinity"
                                    data-allow-clear="true"
                                    disabled="false"
                                />
                                <div class="alert-accountability-status"></div>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="fv-row mb-7 fv-plugins-icon-container" id="">
                                <label class="fs-6 fw-semibold mb-2">Remarks</label>
                                <textarea class="form-control form-control-solid" rows="5" name="remarks" placeholder="Remarks"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" modal-id="#modal-accountability-details" data-id=""
                        class="btn btn-primary me-4 submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" modal-id="#modal-accountability-details" class="btn btn-light me-3 cancel">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-other-details" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header justify-content-center" id="">
                    <div class="text-center">
                        <h1 class="mb-3 modal-title"></h1>
                        <div class="text-muted fs-5">Fill-up the form and click
                            <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-body px-10">
                    <form id="form-edit-other-details" modal-id="#modal-edit-other-details" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="">
                        <div class="px-5">
                            <div class="card-rounded bg-info bg-opacity-5 p-5 mb-7 other-details">
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 fv-plugins-icon-container col-6">
                                    <label class="required fw-semibold fs-6 mb-2">Issued at</label>
                                    <input type="text" name="issued_at" input-control="date-picker" default-date="" class="form-control form-select-solid mb-3 mb-lg-0 flatpickr">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                                <div class="fv-row mb-7 fv-plugins-icon-container col-6">
                                    <label class="fw-semibold fs-6 mb-2">Return at</label>
                                    <input type="text" name="returned_at" input-control="date-picker" default-date="" class="form-control form-select-solid mb-3 mb-lg-0 flatpickr">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <x-elements.select
                                id="status"
                                name="status"
                                label="Status"
                                :options="['1' => 'Issued', '2' => 'Returned']"
                                placeholder="Select an option"
                                selected=""
                                class="fw-bold form-select-solid"
                                data-control="select2"
                                data-placeholder="Select an option"
                                data-minimum-results-for-search="Infinity"
                                disabled="false"
                            />
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                            <div class="fv-row mb-7 fv-plugins-icon-container" id="">
                                <label class="fs-6 fw-semibold mb-2">Remarks</label>
                                <textarea class="form-control form-control-solid" rows="5" name="remarks" placeholder="Remarks"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" modal-id="#modal-edit-other-details" data-id=""
                        class="btn btn-primary me-4 submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" modal-id="#modal-edit-other-details" class="btn btn-light me-3 cancel">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-available-items" tabindex="-1" aria-hidden="false" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h2 class="fw-bold">Add Item</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" class="form-control form-control-solid ps-12 search"
                            placeholder="Search here ..." />
                    </div>
                    <x-elements.datatable id="available-items" class="table-striped table-sm align-middle table-row-dashed dataTable">
                    </x-elements.datatable>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-available-personnel" tabindex="-1" aria-hidden="false" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h2 class="fw-bold">Add Personnel</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" class="form-control form-control-solid ps-12 search"
                            placeholder="Search here ..." />
                    </div>
                    <x-elements.datatable id="available-personnel" class="table-striped table-sm align-middle table-row-dashed dataTable">
                    </x-elements.datatable>
                </div>
            </div>
        </div>
    </div>

</div>
