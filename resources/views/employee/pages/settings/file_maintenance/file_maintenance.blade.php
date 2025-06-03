<div class="page-file-maintenance-settings">

    <div class="d-flex flex-column flex-lg-row">
        <div class="d-none d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-275px">
            <div class="card card-flush mb-0">
                <div class="card-body pt-5">
                    <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
                        <li>
                            <div class="menu-item">
                                <h4 class="menu-content text-muted mb-0 fs-7 text-uppercase">
                                    File Maintenance
                                </h4>
                            </div>
                        </li>
                        <li class="nav-item w-md-200px me-0 mb-2">
                            <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                data-tab="item" data-bs-toggle="tab" href="#tab_content1">
                                Item
                            </a>
                        </li>
                        <li class="nav-item w-md-200px me-0 mb-2">
                            <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                data-tab="item-type" data-bs-toggle="tab" href="#tab_content2">
                                Item Type
                            </a>
                        </li>
                        <li class="nav-item w-md-200px me-0 mb-2">
                            <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                data-tab="item-brand" data-bs-toggle="tab" href="#tab_content3">
                                Item Brand
                            </a>
                        </li>
                        <li class="nav-item w-md-200px me-0 mb-2">
                            <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                data-tab="item-suppliers" data-bs-toggle="tab" href="#tab_content4">
                                Item Suppliers
                            </a>
                        </li>
                        <li class="nav-item w-md-200px me-0 mb-2">
                            <a class="nav-link text-dark text-active-light fw-bold tab" tab-loaded="false"
                                data-tab="company-location" data-bs-toggle="tab" href="#tab_content5">
                                Company Location
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex-lg-row-fluid ms-3">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade item" id="tab_content1" role="tabpanel">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-0 pb-6 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                    <a href="new-item" type="button" class="btn btn-primary">
                                        Add Item
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-0">
                            <x-elements.datatable id="item" class="table-striped table-sm align-middle table-row-dashed dataTable">
                            </x-elements.datatable>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade item-type" id="tab_content2" role="tabpanel">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-0 pb-6 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-item-type">
                                        Add Item Type
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-0">
                            <x-elements.datatable id="item-type" class="table-striped table-sm align-middle table-row-dashed dataTable">
                            </x-elements.datatable>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade item-brand" id="tab_content3" role="tabpanel">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-0 pb-6 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-item-brand">
                                        Add Item Brand
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-0">
                            <x-elements.datatable id="item-brand" class="table-striped table-sm align-middle table-row-dashed dataTable">
                            </x-elements.datatable>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade item-suppliers" id="tab_content4" role="tabpanel">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-0 pb-6 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-item-suppliers">
                                        Add Item Supplier
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-0">
                            <x-elements.datatable id="item-suppliers" class="table-striped table-sm align-middle table-row-dashed dataTable">
                            </x-elements.datatable>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade company-location" id="tab_content5" role="tabpanel">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-0 pb-6 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-company-location">
                                        Add Location
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-0">
                            <x-elements.datatable id="company-location" class="table-striped table-sm align-middle table-row-dashed dataTable">
                            </x-elements.datatable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-elements.modal
    id="add-item-type"
    title="Item Type Details"
    action="/file-maintenance/item-type/update">
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-elements.input
                name="name"
                id=""
                label="Name"
                required="true"
                value=""
                placeholder="Name"
                class="form-control form-control-solid"
                disabled="false"
                remote-validation="true"
            />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
        </div>
        <div class="row">
            <div class="fv-row mb-7 col-6 fv-plugins-icon-container">
                <x-elements.input
                    name="item_number"
                    id=""
                    label="Item Number"
                    required="true"
                    value=""
                    placeholder="Item Number"
                    class="form-control form-control-solid"
                    disabled="false"
                    remote-validation="true"
                />
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <div class="fv-row mb-7 col-6 fv-plugins-icon-container">
                <x-elements.input
                    name="item_code"
                    id=""
                    label="Item Code"
                    required="true"
                    value=""
                    placeholder="Name"
                    class="form-control form-control-solid"
                    disabled="false"
                    remote-validation="true"
                />
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
        </div>
        <div class="d-flex fv-row flex-column mb-7">
            <x-elements.textarea
                id="description"
                name="description"
                label="Description"
                class="form-control-solid"
                data-required="false"
            />
        </div>
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-elements.select
                id="is_active"
                name="is_active"
                label="Status"
                :options="['1' => 'Active', '2' => 'Inactive']"
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
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-elements.select
                id="display_to"
                name="display_to"
                label="Display To"
                :options="['1' => 'Accountability', '2' => 'Material Issuance', '3'=>'Both']"
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
    </div>
</x-elements.modal>
{{-- 
<x-elements.modal
    id="add-item-brand"
    title="Item Brand Details"
    action="/file-maintenance/item-brand/update">
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-elements.input
                name="name"
                id=""
                label="Name"
                required="true"
                value=""
                placeholder="Name"
                class="form-control form-control-solid"
                disabled="false"
                remote-validation="true"
            />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
        </div>
        <div class="d-flex fv-row flex-column mb-7">
            <x-elements.textarea
                id="description"
                name="description"
                label="Description"
                class="form-control-solid"
                data-required="false"
            />
        </div>
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-elements.select
                id="is_active"
                name="is_active"
                label="Status"
                :options="['1' => 'Active', '2' => 'Inactive']"
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
    </div>
</x-elements.modal>

<x-elements.modal
    id="add-item-suppliers"
    title="Item Supplier Details"
    action="/file-maintenance/item-suppliers/update">
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-elements.input
                name="name"
                id=""
                label="Name"
                required="true"
                value=""
                placeholder="Name"
                class="form-control form-control-solid"
                disabled="false"
                remote-validation="true"
            />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
        </div>
        <div class="d-flex fv-row flex-column mb-7">
            <x-elements.textarea
                id="description"
                name="description"
                label="Description"
                class="form-control-solid"
                data-required="false"
            />
        </div>
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-elements.select
                id="is_active"
                name="is_active"
                label="Status"
                :options="['1' => 'Active', '2' => 'Inactive']"
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
    </div>
</x-elements.modal>

<x-elements.modal
    id="add-company-location"
    title="Company Location Details"
    action="/file-maintenance/company-location/update" >
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
        <div class="row">
            <div class="fv-row mb-7 col-6 fv-plugins-icon-container">
                <x-elements.input
                    name="name"
                    id=""
                    label="Company Location"
                    value=""
                    placeholder="Company Location"
                    class="form-control form-control-solid mb-3 mb-lg-0"
                    disabled="false"
                    remote-validation="true"
                    required="true"
                />
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <div class="fv-row mb-7 col-6 fv-plugins-icon-container">
                <x-elements.input
                    name="location_code"
                    id=""
                    label="Location Code"
                    value=""
                    placeholder="Location Code"
                    class="form-control form-control-solid mb-3 mb-lg-0"
                    disabled="false"
                    remote-validation="true"
                    required="true"
                />
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
        </div>
        <div class="fv-row mb-8 fv-plugins-icon-container">
                <x-elements.select
                    id="is_active"
                    name="is_active"
                    label="Status"
                    :options="['1' => 'Active', '2' => 'Inactive']"
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
        <div class="d-flex  fv-row flex-column mb-8">
            <x-elements.textarea
                id="description"
                name="description"
                label="Description"
                class="form-control-solid"
                data-required="false"
            >
            </x-elements.textarea>
        </div>
    </div>
</x-elements.modal> --}}
