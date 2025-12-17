<div class="page-new-inventory">

    <div class="d-flex align-items-center gap-2 gap-lg-3 mb-5">
        <a href="/inventory" class="btn btn-sm fw-bold btn-danger">
            <i class="ki-duotone ki-black-left fs-2"></i>
            Exit New Inventory Setup
        </a>
    </div>

    {{-- New Inventory Details --}}
    <div class="card card-flush py-4 mb-10" id="card-new-inventory">

        <div class="card-header">
            <div class="card-title">
                <h2>General Details</h2>
            </div>
        </div>

        <div class="card-body pt-0">
            <form id="form-new-inventory" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="inventory/update">
                <div class="fv-row mb-7">
                    <label class="form-label required">Select an Item</label>
                    <select class="form-select mb-2" name="item"
                        data-control="select2" data-hide-search="false" data-allow-clear="true"
                        data-placeholder="Select an Item">
                        {!! $item_options !!}
                    </select>
                </div>
                <div class="fv-row mb-7">
                    <label class="form-label">Serial Number</label>
                    <input type="text" name="serial_number" class="form-control mb-2"  placeholder="Serial Number" value="" />
                </div>
                <div class="row mb-7">
                    <div class="fv-row col-6 flex-md-root">
                        <label class="required fw-semibold fs-6 mb-2">Purchased At</label>
                        <input type="text" name="received_at" input-control="date-picker" default-date="" class="form-control  mb-3 mb-lg-0 flatpickr">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                    <div class="fv-row col-6  flex-md-root">
                        <label class="fw-semibold fs-6 mb-2">Warranty End At</label>
                        <input type="text" name="warranty_end_at" input-control="date-picker" default-date="" class="form-control  mb-3 mb-lg-0 flatpickr">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                </div>
                <div class="fv-row mb-7">
                    <label class="form-label required">Supplier</label>
                    <select class="form-select mb-2" name="supplier"
                        data-control="select2" data-minimum-results-for-search="Infinity" data-allow-clear="true"
                        data-placeholder="Select an option">
                        {!! $supplier_options !!}
                    </select>
                </div>
                <div class="fv-row mb-7">
                    <label class="required form-label">Received By (MIS Personnel)</label>
                    <select class="form-select mb-2" name="received_by"
                        data-control="select2" data-hide-search="true" data-allow-clear="true"
                        data-placeholder="Select an option">
                        {!! $mis_personnel_options !!}
                    </select>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container">
                    <x-elements.select
                        id="company_location"
                        name="company_location"
                        label="Item Located At ?"
                        :options="[]"
                        placeholder="Select an option"
                        selected="1"
                        class="fw-bold "
                        data-control="select2"
                        data-placeholder="Select an option"
                        data-minimum-results-for-search="Infinity"
                        data-allow-clear="true"
                        disabled="false"
                    />
                    <div class="alert-status"></div>
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container">
                    <x-elements.select
                        id="status"
                        name="status"
                        label="Status"
                        :options="['1' => 'Available', '6'=>'Deployed']"
                        placeholder="Select an option"
                        selected="1"
                        class="fw-bold"
                        data-control="select2"
                        data-placeholder="Select an option"
                        data-minimum-results-for-search="Infinity"
                        data-allow-clear="true"
                        disabled="false"
                    />
                    <div class="alert-status"></div>
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container" id="">
                    <label class="fs-6 fw-semibold mb-2">Remarks</label>
                    <textarea class="form-control form-control-solid" rows="5" name="remarks" placeholder="Remarks"></textarea>
                </div>
                {{-- <div class="row">
                    <div class="fv-row mb-7 col-6 fv-plugins-icon-container">
                        <x-elements.select
                            id="company_location"
                            name="company_location"
                            label="Company Location"
                            :options="[]"
                            placeholder="Select an option"
                            selected="1"
                            class="fw-bold "
                            data-control="select2"
                            data-placeholder="Select an option"
                            data-minimum-results-for-search="Infinity"
                            data-allow-clear="true"
                            disabled="false"
                        />
                        <div class="alert-status"></div>
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                    <div class="mb-7 col-6 fv-row">
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control mb-2"  placeholder="Serial Number" value="" />
                    </div>
                </div>
                <div class="row mb-7">
                    <div class="fv-row col-6 flex-md-root">
                        <label class="required fw-semibold fs-6 mb-2">Received At</label>
                        <input type="text" name="received_at" input-control="date-picker" default-date="current" class="form-control  mb-3 mb-lg-0 flatpickr">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>

                    <div class="fv-row col-6 flex-md-root">
                        <label class="required form-label">Received By</label>
                        <select class="form-select mb-2" name="received_by"
                            data-control="select2" data-hide-search="true" data-allow-clear="true"
                            data-placeholder="Select an option">
                            {!! $mis_personnel_options !!}
                        </select>
                    </div>
                </div>
                <div class="fv-row mb-7">
                    <label class="form-label required">Supplier</label>
                    <select class="form-select mb-2" name="supplier"
                        data-control="select2" data-minimum-results-for-search="Infinity" data-allow-clear="true"
                        data-placeholder="Select an option">
                        {!! $supplier_options !!}
                    </select>
                </div>
                <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Warranty End At</label>
                        <input type="text" name="warranty_end_at" input-control="date-picker" default-date="" class="form-control  mb-3 mb-lg-0 flatpickr">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container">
                    <x-elements.select
                        id="status"
                        name="status"
                        label="Status"
                        :options="['1' => 'Available']"
                        placeholder="Select an option"
                        selected="1"
                        class="fw-bold"
                        data-control="select2"
                        data-placeholder="Select an option"
                        data-minimum-results-for-search="Infinity"
                        data-allow-clear="true"
                        disabled="false"
                    />
                    <div class="alert-status"></div>
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container" id="">
                    <label class="fs-6 fw-semibold mb-2">Remarks</label>
                    <textarea class="form-control form-control-solid" rows="5" name="remarks" placeholder="Remarks"></textarea>
                </div> --}}
            </form>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-end">
                {{-- <button id="" class="btn btn-light me-5 cancel">
                    Cancel
                </button> --}}
                <button type="button" class="btn btn-primary submit">
                    <span class="indicator-label">
                        Save Changes
                    </span>
                    <span class="indicator-progress">
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>

    </div>

</div>
