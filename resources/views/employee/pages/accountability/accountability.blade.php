<div class="d-flex flex-column gap-7 gap-lg-10">
    <div class="card card-accountability-list">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                    <input type="text" class="form-control form-control-solid w-250px ps-13 search" placeholder="Search here . . ." />
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">
                    <div class="d-flex flex-stack flex-wrap gap-4">
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-gray-400 fs-7 me-2">Status</div>
                            <select
                                class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto sfilter"
                                data-control="select2" data-hide-search="true" data-minimum-results-for-search="Infinity"
                                data-dropdown-css-class="w-150px"
                                data-placeholder="Select an option" name="filter_status">
                                <option></option>
                                <option value="all" selected>Show All</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <a href="{{ route('employee.new_accountability') }}" type="button" class="btn btn-primary">
                        <i class="ki-outline ki-plus fs-2"></i> New Accountability
                    </a>
                </div>

            </div>
        </div>
        <div class="card-body pt-0">
            <x-elements.datatable id="accountability-list" class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-elements.datatable>
        </div>
    </div>
</div>

<x-elements.modal
    id="transfer-accountability"
    title="Transfer Accountability"
    action="/accountability/transfer">
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="card-rounded bg-info bg-opacity-5 p-5 mb-7 other-details d-none">
        </div>
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <div class="col-md-12 fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-2">Form No.</label>
                <input type="text" name="form_no" class="form-control" value="">
            </div>
            <div class="col-md-12 fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-2">Issued At</label>
                <input type="text" name="issued_at" input-control="date-picker" default-date="current" class="form-control mb-3 mb-lg-0 flatpickr">
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <div class="col-md-12 fv-row mb-7">
                <label class="form-label required">Issued To</label>
                <select class="form-select" data-kt-repeater="select2" data-placeholder="Select an option" name="received_by" data-allow-clear="true">
                </select>
            </div>
            <div class="d-flex fv-row flex-column mb-7" id="">
                <label class="fs-6 fw-semibold mb-2">Remarks</label>
                <textarea class="form-control form-control-solid" rows="5" name="remarks" placeholder="Remarks"></textarea>
            </div>
        </div>
    </div>
</x-elements.modal>
