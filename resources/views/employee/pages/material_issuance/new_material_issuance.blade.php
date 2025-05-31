
<div class="page-new-material-issuance">
    <div class="d-flex align-items-center gap-2 gap-lg-3 mb-5">
        <a href="/material_issuance" class="btn btn-sm fw-bold btn-danger">
            <i class="ki-duotone ki-black-left fs-2"></i>
            Exit Material Issuance Setup
        </a>
    </div>

    {{-- Material Issuance Details --}}
    <div class="card card-flush mb-10" id="">
        <form class="material-issuance-details">
            <div class="card-header pt-7 border-0" id="">
                <div class="card-title">
                    <h2>Material Issuance Details</h2>
                </div>
            </div>
            <div class="card-body pt-10">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">MIS No.</label>
                            <input type="text" name="form_no" class="form-control" value="">
                        </div>
                        <div class="col-md-6 fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">MRS No.</label>
                            <input type="text" name="mrs_no" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-12 fv-row mb-7">
                        <label class="form-label required">Issued By</label>
                        <select class="form-select" data-kt-repeater="select2" data-placeholder="Select an option" name="issued_by" disabled>
                            {!! $issued_by_option !!}
                        </select>
                    </div>
                    <div class="col-md-12 fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Issued At</label>
                        <input type="text" name="issued_at" input-control="date-picker" default-date="current" class="form-control mb-3 mb-lg-0 flatpickr">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                    <div class="col-md-12 fv-row mb-7">
                        <label class="form-label required">Received By</label>
                        <select class="form-select" data-kt-repeater="select2" data-placeholder="Select an option" name="received_by" data-allow-clear="true">
                            {!! $employee_option !!}
                        </select>
                    </div>
                    <div class="fv-row mb-7 fv-plugins-icon-container">
                        <x-elements.select
                        id="status"
                        name="status"
                        label="Status"
                        :options="['1' => 'Complete', '2' => 'Pending']"
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
                    <div class="d-flex fv-row flex-column mb-7" id="">
                        <label class="fs-6 fw-semibold mb-2">Remarks</label>
                        <textarea class="form-control form-control-solid" rows="5" name="remarks" placeholder="Remarks"></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Issued Item --}}
    <div class="card card-flush mb-10" id="">
        <div class="card-header pt-7" id="">
            <div class="card-title">
                <h2>Issued Item</h2>
            </div>
        </div>
        <div class="card-body pt-10">
            <form class="repeater-issued-item">
                <div data-repeater-list="issued-item">
                    <div data-repeater-item>
                        <div class="form-group row mb-5">
                            <div class="col-md-7 fv-row">
                                <label class="form-label">Item</label>
                                <select class="form-select form-accessories" data-kt-repeater="select2" name="accessories" data-allow-clear="true"
                                data-placeholder="Select an option">
                                    {!! $inventory_options !!}
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control"  name="quantity" placeholder="Quantity" />
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
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
                        Add Item
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Issued To --}}
    <div class="card card-flush mb-10" id="">
        <form class="repeater-issued-to">
            <div class="card-header pt-7 border-0" id="">
                <div class="card-title">
                    <h2>Issued To</h2>
                </div>
            </div>
            <div class="card-body pt-10">
                <div data-repeater-list="issued-to" class="">
                    <div data-repeater-item>
                        <div class="form-group row mb-7">
                            <div class="col-md-10 fv-row">
                                <label class="form-label required">Personnel</label>
                                <select class="form-select" data-kt-repeater="select2" data-placeholder="Select an option" name="employee" data-allow-clear="true">
                                    {!! $employee_option !!}
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
                        Add Employee
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!--begin::Action buttons-->
    <div class="d-grid gap-2 mb-10">
        <button type="button" class="btn btn-primary btn-lg rounded-1 p-5 submit">
            <span class="indicator-label">
                Submit New Accountabiliy
            </span>
            <span class="indicator-progress">
                Please wait... <span
                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</div>
