<div class="page-new-item">
    <div class="d-flex align-items-center gap-2 gap-lg-3 mb-5">
        <a href="/file_maintenance" class="btn btn-sm fw-bold btn-danger">
            <i class="ki-duotone ki-black-left fs-2"></i>
            Exit Setup
        </a>
    </div>

    {{-- General Details --}}
    <div class="card card-flush py-4 mb-10" id="card-new-item-general-details">

        <div class="card-header">
            <div class="card-title">
                <h2>General Details</h2>
            </div>
        </div>

        <div class="card-body pt-0">
            <form id="form-new-item-general-details" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/file-maintenance/item/new-item">
                <div class="mb-7 fv-row">
                    <label class="required form-label">Name</label>
                    <input type="text" name="name" class="form-control mb-2" placeholder="Item Name" value="" />
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container d-none" id="">
                    <label class="fs-6 fw-semibold mb-2 required">Description</label>
                    <textarea class="form-control form-control-solid" rows="5" name="description" placeholder="Description"></textarea>
                </div>
                <div class="row mb-7">
                    <div class="fv-row col-6 flex-md-root">
                        <label class="required form-label">Item Type</label>
                        <select class="form-select mb-2" name="item_type"
                            data-control="select2" data-allow-clear="true"
                            data-placeholder="Select an option">
                            {!! $item_type_options !!}
                        </select>
                    </div>
                    <div class="fv-row col-6 flex-md-root">
                        <label class=" form-label">Item Brand</label>
                        <select class="form-select mb-2" name="item_brand"
                            data-control="select2" data-allow-clear="true"
                            data-placeholder="Select an option">
                            {!! $item_brand_options !!}
                        </select>
                    </div>
                </div>
                <div class="mb-7 fv-row">
                    <label class=" form-label required">Price</label>
                    <input type="text" name="price" class="form-control mb-2"  placeholder="Price" value="" />
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container">
                    <x-elements.select
                        id="is_active"
                        name="is_active"
                        label="Is Active ?"
                        :options="['1' => 'Active', '2' => 'Inactive']"
                        placeholder="Select an option"
                        selected="1"
                        class="fw-bold form-select-solid"
                        data-control="select2"
                        data-placeholder="Select an option"
                        data-minimum-results-for-search="Infinity"
                        data-allow-clear="true"
                        {{-- disabled="{{ $data->is_active == 2? 'true':'false' }}" --}}
                        disabled="false"
                    />
                    <div class="alert-status"></div>
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                {{-- <div class="fv-row mb-7 fv-plugins-icon-container" id="">
                    <label class="fs-6 fw-semibold mb-2">Remarks</label>
                    <textarea class="form-control form-control-solid" rows="5" name="remarks" placeholder="Remarks"></textarea>
                </div> --}}
            </form>
        </div>
    </div>

    {{-- Item Details --}}
    <div class="card card-flush py-4 mb-10 d-none" id="card-new-item-details">

        <div class="card-header">
            <div class="card-title">

            </div>
        </div>

        <div class="card-body pt-0">
            <form id="form-new-item-details" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="" data-include-fv="false">
                <div class="laptop-details d-none row">
                    <div class="mb-7 fv-row">
                        <label class="required form-label">Laptop Model</label>
                        <input type="text" name="laptop_model" class="form-control mb-2" placeholder="Item Name" value="" />
                    </div>
                </div>
                <div class="row">
                    <div class="mb-7 fv-row col-6">
                        <label class="form-label fw-bold required">CPU</label>
                        <input type="text" name="cpu" class="form-control mb-2" placeholder="Item Name" value="" />
                    </div>

                    <div class="mb-7 fv-row col-6">
                        <label class="form-label fw-bold">Device Name</label>
                        <input type="text" name="device_name" class="form-control mb-2" placeholder="Item Name" value="" />
                    </div>
                </div>

                <div class="mb-7 fv-row">
                    <label class="form-label fw-bold">OS Installation Date</label>
                    <input type="text" name="os_installed_date" input-control="date-picker" value="" class="form-control form-select-solid mb-3 mb-lg-0 flatpickr">
                </div>

                <div class="mb-7 fv-row">
                    <label class="form-label fw-bold">Windows Version</label>
                    <input type="text" name="windows_version" class="form-control mb-2" placeholder="Item Name" value="" />
                </div>

                <div class="separator my-5"></div>

                <div class=" py-5 rounded-1 mb-10">
                    <div class="repeater-ram">
                        <div class="d-flex flex-stack mb-5">
                            <h5 class="fw-bold m-0">MEMORY</h5>
                            <button type="button" data-repeater-create class="btn btn-sm btn-flex btn-light-primary ">
                                <i class="ki-duotone ki-plus fs-3"></i>
                                Add Field
                            </button>
                        </div>
                        <div data-repeater-list="ram">
                            <div data-repeater-item class="mb-10">
                                <div class="form-group row">
                                    <div class="col-md-11 fv-row">
                                        <label class="form-label">Manufacturer </label>
                                        <select class="form-select" data-control="select2" name="ram" data-allow-clear="true" data-placeholder="Select an option">
                                            {!! $ram_options !!}
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger mt-3 mt-md-9">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator my-5"></div>

                <div class="py-5 rounded-1 mb-10">
                    <div class="repeater-storage">
                        <div class="d-flex flex-stack mb-5">
                            <h5 class="fw-bold m-0">STORAGE</h5>
                            <button type="button" data-repeater-create class="btn btn-sm btn-flex btn-light-primary ">
                                <i class="ki-duotone ki-plus fs-3"></i>
                                Add Field
                            </button>
                        </div>
                        <div data-repeater-list="storage">
                            <div data-repeater-item class="mb-10">
                                <div class="form-group row">
                                    <div class="col-md-11 fv-row">
                                        <label class="form-label">Model: </label>
                                        <select class="form-select" name="storage" data-control="select2" data-allow-clear="true" data-placeholder="Select an option">
                                            {!! $storage_options !!}
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger mt-3 mt-md-9">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator my-5"></div>

                <div class="py-5 rounded-1 mb-10">
                    <div class="repeater-gpu">
                        <div class="d-flex flex-stack mb-5">
                            <h5 class="fw-bold m-0">VIDEO CARD</h5>
                            <button type="button" data-repeater-create class="btn btn-sm btn-flex btn-light-primary ">
                                <i class="ki-duotone ki-plus fs-3"></i>
                                Add Field
                            </button>
                        </div>
                        <div data-repeater-list="gpu">
                            <div data-repeater-item class="mb-10">
                                <div class="form-group row">
                                    <div class="col-md-11 fv-row">
                                        <label class="form-label">Model: </label>
                                        <select class="form-select" data-control="select2" name="gpu" data-allow-clear="true" data-placeholder="Select an option">
                                        {!! $gpu_options !!}
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger mt-3 mt-md-9">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="d-grid gap-2 mb-10">
        <button type="button" class="btn btn-primary btn-lg rounded-1 p-5 submit-new-item">
            <span class="indicator-label">
                Submit New Item
            </span>
            <span class="indicator-progress">
                Please wait... <span
                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</div>
