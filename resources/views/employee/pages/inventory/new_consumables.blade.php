
<div class="page-new-consumables">
    <div class="d-flex align-items-center gap-2 gap-lg-3 mb-5">
        <a href="/inventory" class="btn btn-sm fw-bold btn-danger">
            <i class="ki-duotone ki-black-left fs-2"></i>
            Exit New Consumables
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
                <div class="fv-row mb-7 fv-plugins-icon-container d-none">
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
                </div>
            </form>
        </div>
    </div>

    {{-- Item Details --}}
    <div class="card card-flush mb-10" id="">
        <div class="card-header pt-7" id="">
            <div class="card-title">
                <h2>List of Consumable</h2>
            </div>
        </div>
        <div class="card-body pt-10">
            <form class="repeater-issued-item">
                <div data-repeater-list="issued-item">
                    <div data-repeater-item>
                        <div class="form-group row mb-5">
                            <div class="col-md-7 fv-row">
                                <label class="form-label">Item</label>
                                <select class="form-select form-accessories" data-kt-repeater="select2" name="accessories"
                                    data-allow-clear="true" data-placeholder="Select an option">
                                    {!! $item_options !!}
                                </select>
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" placeholder="Quantity" />
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            <div class="col-md-2 d-none">
                                <a href="javascript:;" data-repeater-delete
                                    class="btn btn-flex btn-sm btn-light-danger mt-3 mt-md-9">
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

    <div class="d-grid gap-2 mb-10">
        <button type="button" class="btn btn-primary btn-lg rounded-1 p-5 submit">
            <span class="indicator-label">
                Submit New Consumables
            </span>
            <span class="indicator-progress">
                Please wait... <span
                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</div>

