<div class="modal fade" id="modal-edit-system-unit" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg ">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">System Unit Details</h1>
                    <div class="text-muted fs-5">To update, fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body px-10">
                <form id="form-edit-system-unit" modal-id="#modal-edit-system-unit" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/accountability-details/update-issued-items">
                    <div class="px-5">
                        <div class="row">
                            <div class="d-flex flex-column col-12  fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fw-semibold fs-6 mb-2">Item</label>
                                <input type="text" name="item" class="form-control mb-3 mb-lg-0" value="{{ $query->item_inventory->name }}">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="d-flex flex-column col-12  fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fw-semibold fs-6 mb-2">CPU</label>
                                <input type="text" name="cpu" class="form-control mb-3 mb-lg-0" value="{{ $query->item_inventory->description['cpu'] }}">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>

                            <div class="separator my-5"></div>
                            <div class="repeater-ram">
                                <div class="d-flex flex-stack mb-5">
                                    <h5 class="fw-bold m-0">Ram</h5>
                                    <button type="button" data-repeater-create class="btn btn-sm btn-flex btn-light-primary ">
                                        <i class="ki-duotone ki-plus fs-3"></i>
                                        Add Field
                                    </button>
                                </div>
                                <div data-repeater-list="ram">
                                    @foreach ($ram_options as $ram)
                                        <div data-repeater-item>
                                            <div class="form-group row mb-5">
                                                <div class="col-md-7 fv-row">
                                                    <label class="form-label">Manufacturer </label>
                                                    <select class="form-select" data-kt-repeater="select2" name="ram" data-allow-clear="true" data-placeholder="Select an option">
                                                        {!! $ram['html'] !!}
                                                    </select>
                                                </div>
                                                <div class="col-md-4 fv-row">
                                                    <label class="form-label">Serial / Device ID</label>
                                                    <input type="text" class="form-control"  name="serial_number" placeholder="Enter serial number" value="{{ $ram['serial_number'] }}" />
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger mt-3 mt-md-9">
                                                        <i class="ki-outline ki-cross fs-1"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="separator my-5"></div>
                            <div class="repeater-storage">
                                <div class="d-flex flex-stack mb-5">
                                    <h5 class="fw-bold m-0">Storage</h5>
                                    <button type="button" data-repeater-create class="btn btn-sm btn-flex btn-light-primary ">
                                        <i class="ki-duotone ki-plus fs-3"></i>
                                        Add Field
                                    </button>
                                </div>
                                <div data-repeater-list="storage">
                                    @foreach ($storage_options as $storage)
                                        <div data-repeater-item>
                                            <div class="form-group row mb-5">
                                                <div class="col-md-7 fv-row">
                                                    <label class="form-label">Model: </label>
                                                    <select class="form-select" data-kt-repeater="select2" name="storage" data-allow-clear="true" data-placeholder="Select an option">
                                                        {!! $storage['html'] !!}
                                                    </select>
                                                </div>
                                                <div class="col-md-4 fv-row">
                                                    <label class="form-label">Serial Number: </label>
                                                    <input type="text" class="form-control"  name="serial_number" placeholder="Enter serial number" value="{{ $storage['serial_number'] }}" />
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger mt-3 mt-md-9">
                                                        <i class="ki-outline ki-cross fs-1"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            <div class="separator my-5"></div>
                            <div class="repeater-gpu">
                                <div class="d-flex flex-stack mb-5">
                                    <h5 class="fw-bold m-0">Dedicated GPU</h5>
                                    <button type="button" data-repeater-create class="btn btn-sm btn-flex btn-light-primary ">
                                        <i class="ki-duotone ki-plus fs-3"></i>
                                        Add Field
                                    </button>
                                </div>
                                <div data-repeater-list="gpu">
                                    @foreach ($gpu_options as $gpu)
                                    <div data-repeater-item>
                                        <div class="form-group row mb-5">
                                            <div class="col-md-7 fv-row">
                                                <label class="form-label">Model: </label>
                                                <select class="form-select" data-kt-repeater="select2" name="gpu" data-allow-clear="true" data-placeholder="Select an option">
                                                    {!! $gpu['html'] !!}
                                                </select>
                                            </div>
                                            <div class="col-md-4 fv-row">
                                                <label class="form-label">Serial Number: </label>
                                                <input type="text" class="form-control"  name="serial_number" placeholder="Enter serial number" value="{{ $gpu['serial_number'] }}" />
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger mt-3 mt-md-9">
                                                    <i class="ki-outline ki-cross fs-1"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="separator my-5"></div>
                        <div class="d-flex flex-column col-12  fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Device Name</label>
                            <input type="text" name="device_name" class="form-control mb-3 mb-lg-0" value="{{ $query->item_inventory->description['device_name'] }}">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column col-12  fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Windows Ver.</label>
                            <input type="text" name="windows_version" class="form-control mb-3 mb-lg-0" value="{{ $query->item_inventory->description['windows_version'] }}">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column col-12  fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">OS Installation Date</label>
                            <input type="text" name="os_installed_date" class="form-control mb-3 mb-lg-0" value="{{ $query->item_inventory->description['os_installed_date'] }}">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex fv-row flex-column mb-7" id="kt_modal_add_user_scroll">
                            <label class="fs-6 required fw-semibold mb-2">Remarks</label>
                            <textarea class="form-control form-control-solid" rows="5" name="remarks" placeholder="Remarks">{{ $query->remarks }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal-edit-system-unit" data-id="{{ Crypt::encrypt($query->id) }}" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal-edit-system-unit" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
