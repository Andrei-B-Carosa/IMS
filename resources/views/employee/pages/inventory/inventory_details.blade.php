<div class="page-inventory-details">

    <!--begin::Alert-->
    {{-- <div class="alert alert-dismissible bg-light-danger border border-danger d-flex flex-column flex-sm-row p-5 mb-5">
        <!--begin::Icon-->
        <i class="ki-duotone ki-trash fs-2hx text-danger me-4 mt-2 mb-sm-0">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
            <span class="path4"></span>
            <span class="path5"></span>
        </i>
        <!--end::Icon-->

        <!--begin::Wrapper-->
        <div class="d-flex flex-column pe-0 pe-sm-10">
            <!--begin::Title-->
            <h4 class="fw-semibold">Item is Disposed</h4>
            <!--end::Title-->

            <!--begin::Content-->
            <span>Scroll down for more details . . .</span>
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Close-->
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="ki-duotone ki-cross fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
        </button>
        <!--end::Close-->
    </div> --}}
    <!--end::Alert-->

    <div class="d-flex align-items-center gap-2 gap-lg-3 mb-5">
        <a href="/inventory" class="btn btn-sm fw-bold btn-danger">
            <i class="ki-duotone ki-black-left fs-2"></i>
            Exit Inventory Details
        </a>
    </div>

    {{-- General Details --}}
    <div class="card card-flush py-4 my-10" id="card-general-details">
        <div class="card-header border-0 rotate cursor-pointer" data-bs-toggle="collapse" data-bs-target="#card_general_details_collapsible">
            <div class="card-title">General Details</div>
            <div class="card-toolbar">
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-1"></i>
                </div>
            </div>
        </div>
        <div id="card_general_details_collapsible" class="collapse show">
            <div class="card-body pt-0">
                <form id="form-general-details" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/inventory-details/update-general-details">

                    <div class="mb-7 fv-row">
                        <label class="required form-label">Name</label>
                        <input type="text" name="name" class="form-control mb-2" placeholder="Item Name" value="{{ $data->name }}" />
                    </div>
                    {{-- @if($data->item_type->display_to == 1 || $data->item_type->display_to == 3)
                        <div class="mb-7 fv-row">
                            <label class="required form-label">Tag Number</label>
                            <input type="text" name="tag_number" class="form-control mb-2" placeholder="Item Name" value="{{ $data->generate_tag_number() }}" disabled/>
                        </div>
                    @endif --}}
                    @if($data->item_type_id != 1 && $data->item_type_id !=8)
                        <div class="fv-row mb-7 fv-plugins-icon-container" id="">
                            <label class="fs-6 fw-semibold mb-2">Description</label>
                            <textarea class="form-control form-control-solid" rows="5" name="description" placeholder="Description">{{ $data->description }}</textarea>
                        </div>
                    @endif
                    {{-- <div class="row mb-7">
                        <div class="fv-row col-6 flex-md-root">
                            <label class="required form-label">Item Type</label>
                            <select class="form-select mb-2" name="item_type"
                                data-control="select2" data-hide-search="true" data-allow-clear="true"
                                data-placeholder="Select an option">
                                {!! $item_type_options !!}
                            </select>
                        </div>
                        <div class="fv-row col-6 flex-md-root">
                            <label class=" form-label">Item Brand</label>
                            <select class="form-select mb-2" name="item_brand"
                                data-control="select2" data-hide-search="true" data-allow-clear="true"
                                data-placeholder="Select an option">
                                {!! $item_brand_options !!}
                            </select>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="mb-7 fv-row col-6">
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control mb-2"  placeholder="Serial Number" value="{{ $data->serial_number }}" />
                        </div>
                        <div class="mb-7 fv-row col-6">
                            <label class=" form-label">Price</label>
                            <input type="text" name="price" class="form-control mb-2"  placeholder="Price" value="{{ $data->price }}" />
                        </div>
                    </div>
                    {{-- <div class="fv-row mb-7 ">
                        <label class="form-label">Supplier</label>
                        <select class="form-select mb-2" name="supplier"
                            data-control="select2" data-hide-search="true" data-allow-clear="true"
                            data-placeholder="Select an option">
                            {!! $supplier_options !!}
                        </select>
                    </div> --}}
                    <div class="row">
                        <div class="fv-row col-6 flex-md-root col-6">
                            <label class="required fw-semibold fs-6 mb-2">Purchased Date</label>
                            <input type="text" name="received_at" input-control="date-picker" value="{{ date('m-d-Y',strtotime($data->received_at)) }}" class="form-control form-select-solid mb-3 mb-lg-0 flatpickr">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="fv-row mb-7 col-6">
                            <label class="fw-semibold fs-6 mb-2 me-2">
                                Warranty End At
                            </label>
                                @if($data->warranty_end_at > $data->received_at && (isset($data->warranty_end_at) && isset($data->received_at)))
                                    <span class="badge badge-danger">Warranty Ended</span>
                                @endif
                            <input type="text" name="warranty_end_at" input-control="date-picker" value="{{ isset($data->warranty_end_at) ? date('m-d-Y',strtotime($data->warranty_end_at)) : null }}" default-date="" class="form-control  mb-3 mb-lg-0 flatpickr">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="fv-row fv-plugins-icon-container mb-7">
                        <label class="required form-label">Received By</label>
                        <select class="form-select mb-2" name="received_by"
                            data-control="select2" data-hide-search="true" data-allow-clear="true"
                            data-placeholder="Select an option">
                            {!! $mis_personnel_options !!}
                        </select>
                    </div>
                    <div class="fv-row mb-7 col-12 fv-plugins-icon-container">
                        <label class="required form-label">Item Located At</label>
                        <select class="form-select mb-2" name="company_location"
                                data-control="select2" data-hide-search="true" data-allow-clear="true"
                                data-placeholder="Select an option">
                                {!! $clocation_options !!}
                        </select>
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                    <div class="fv-row mb-7 fv-plugins-icon-container">
                        <x-elements.select
                            id="status"
                            name="status"
                            label="Item Status"
                            :options="['0'=>'Disposed','1' => 'Available', '2' => 'Issued', '3'=>'Temporary Issued', '4'=>'Under Repair']"
                            {{-- :options="['1' => 'Available', '2' => 'Issued']" --}}
                            placeholder="Select an option"
                            selected="{{ $data->status }}"
                            class="fw-bold form-select-solid"
                            data-control="select2"
                            data-placeholder="Select an option"
                            data-minimum-results-for-search="Infinity"
                            data-allow-clear="true"
                            disabled="{{ $data->status == 2|| $data->status==4 || $data->status==0? 'true':'false' }}"
                        />
                        <div class="alert-status"></div>
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                    <div class="fv-row mb-7 fv-plugins-icon-container" id="">
                        <label class="fs-6 fw-semibold mb-2">Remarks</label>
                        <textarea class="form-control form-control-solid" rows="5" name="remarks" placeholder="Remarks">{{ $data->remarks }}</textarea>
                    </div>
                </form>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    {{-- <button id="" class="btn btn-light me-5 cancel">
                        Cancel
                    </button> --}}
                    @if($data->status!=0)
                        <button type="button" class="btn btn-primary submit">
                            <span class="indicator-label">
                                Save Changes
                            </span>
                            <span class="indicator-progress">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- This is a conditional div if item is system unit or laptop since they have a specification details --}}
    @if($data->item_type_id == 1 || $data->item_type_id ==8)
        <div class="card card-flush py-4 mb-10" id="card-item-details">
            <div class="card-header rotate cursor-pointer" data-bs-toggle="collapse" data-bs-target="#card_item_details_collapsible">
                <div class="card-title text-capitalize">{{ strtolower($data->item_type->name) }} Details</div>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
            </div>
            <div id="card_item_details_collapsible" class="collapse">
                <div class="card-body pt-0">
                    <form id="form-item-details" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/inventory-details/update-item-details">
                        @if($data->item_type_id == 8)
                            <div class="laptop-details row">
                                <div class="mb-7 col-12 fv-row">
                                    <label class="required form-label">Laptop Model</label>
                                    <input type="text" name="laptop_model" class="form-control mb-2" placeholder="Item Name" value="{{ $data->description['model'] }}" />
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="mb-7 fv-row col-6">
                                <label class="form-label fw-bold required">CPU</label>
                                <input type="text" name="cpu" class="form-control mb-2" placeholder="Item Name" value="{{ $data->description['cpu'] }}" />
                            </div>

                            <div class="mb-7 fv-row col-6">
                                <label class="form-label fw-bold">Device Name</label>
                                <input type="text" name="device_name" class="form-control mb-2" placeholder="Item Name" value="{{ $data->description['device_name'] }}" />
                            </div>
                        </div>

                        <div class="mb-7 fv-row">
                            <label class="form-label fw-bold">OS Installation Date</label>
                            <input type="text" name="os_installed_date" input-control="date-picker" value="{{ date('m-d-Y',strtotime($data->description['os_installed_date'])) }}" class="form-control form-select-solid mb-3 mb-lg-0 flatpickr">
                        </div>

                        <div class="mb-7 fv-row">
                            <label class="form-label fw-bold">Windows Version</label>
                            <label class="required form-label">Name</label>
                            <input type="text" name="windows_version" class="form-control mb-2" placeholder="Item Name" value="{{ $data->description['windows_version'] }}" />
                        </div>

                        <div class="separator my-5"></div>

                        <div class=" py-5 rounded-1 mb-10">
                            <div class="repeater-ram">
                                <div class="d-flex flex-stack mb-5">
                                    <h5 class="fw-bold m-0">MEMORY</h5>
                                    <button type="button" data-repeater-create class="btn btn-sm btn-flex btn-light-primary">
                                        <i class="ki-duotone ki-plus fs-3"></i>
                                        Add Field
                                    </button>
                                </div>
                                <div data-repeater-list="ram">
                                    @foreach ($array_specs['ram_options'] as $ram)
                                        <div data-repeater-item class="mb-10">
                                            <div class="form-group row">
                                                <div class="col-md-7 fv-row">
                                                    <label class="form-label">Manufacturer </label>
                                                    <select class="form-select" data-control="select2" name="ram" data-allow-clear="true" data-placeholder="Select an option">
                                                        {!! $ram['html'] !!}
                                                    </select>
                                                </div>
                                                <div class="col-md-4 fv-row">
                                                    <label class="form-label">Serial Number :</label>
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
                        </div>

                        <div class="separator my-5"></div>

                        <div class="py-5 rounded-1 mb-10">
                            <div class="repeater-storage ">
                                <div class="d-flex flex-stack mb-5">
                                    <h5 class="fw-bold m-0">STORAGE</h5>
                                    <button type="button" data-repeater-create class="btn btn-sm btn-flex btn-light-primary ">
                                        <i class="ki-duotone ki-plus fs-3"></i>
                                        Add Field
                                    </button>
                                </div>
                                <div data-repeater-list="storage">
                                    @foreach ($array_specs['storage_options'] as $storage)
                                        <div data-repeater-item class="mb-10">
                                            <div class="form-group row">
                                                <div class="col-md-7 fv-row">
                                                    <label class="form-label">Model: </label>
                                                    <select class="form-select" name="storage" data-control="select2" data-allow-clear="true" data-placeholder="Select an option">
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
                        </div>

                        <div class="separator my-5"></div>

                        <div class="py-5 rounded-1 mb-10">
                            <div class="repeater-gpu">
                                <div class="d-flex flex-stack mb-5">
                                    <h5 class="fw-bold m-0">VIDEO CARD</h5>
                                    <button type="button" data-repeater-create class="btn btn-sm btn-flex btn-light-primary">
                                        <i class="ki-duotone ki-plus fs-3"></i>
                                        Add Field
                                    </button>
                                </div>
                                <div data-repeater-list="gpu">
                                    @foreach ($array_specs['gpu_options'] as $gpu)
                                    <div data-repeater-item class="mb-10">
                                        <div class="form-group row">
                                            <div class="col-md-7 fv-row">
                                                <label class="form-label">Model: </label>
                                                <select class="form-select" data-control="select2" name="gpu" data-allow-clear="true" data-placeholder="Select an option">
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

                    </form>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        @if($data->status!=0)
                            <button type="button" class="btn btn-primary submit">
                                <span class="indicator-label">
                                    Save Changes
                                </span>
                                <span class="indicator-progress">
                                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    @endif

    {{-- Item Logs --}}
    @if($data->item_type->display_to == 1 || $data->item_type->display_to == 3)
        <div class="card card-flush py-4 mb-10">
            <div class="card-header border-0 rotate cursor-pointer" data-bs-toggle="collapse" data-bs-target="#card_item_logs_collapsible">
                <div class="card-title">Item Logs</div>

                <div class="card-toolbar">
                    {{-- <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                        <input type="text" class="form-control form-control-solid w-250px ps-13 search" placeholder="Search here . . ." />
                    </div> --}}
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
            </div>
            <div id="card_item_logs_collapsible" class="collapse">
                <div class="card-body pt-0">
                    <x-elements.datatable id="item-logs" class="table-striped table-sm align-middle table-row-dashed dataTable">
                    </x-elements.datatable>
                </div>
            </div>
        </div>
    @endif

</div>
