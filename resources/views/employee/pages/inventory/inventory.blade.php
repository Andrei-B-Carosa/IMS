<div class="page-inventory-list pt-3">
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4" data-tab="devices" data-bs-toggle="tab" href="#tab_devices">Devices</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4" data-tab="consumables" data-bs-toggle="tab" href="#tab_consumables">Consumables</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4" data-tab="repair" data-bs-toggle="tab" href="#tab_repair">Repair Request</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade" id="tab_devices" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-inventory-list">
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
                                            <div class="text-gray-400 fs-7 me-2">Category</div>
                                            <select
                                                class="form-select form-select-transparent text-graY-800 fs-base lh-1 fw-bold py-0 ps-3 w-auto sfilter"
                                                data-control="select2" data-hide-search="true"
                                                data-dropdown-css-class="w-150px" name="filter_category">
                                                <option value="all" selected>Show all</option>
                                            </select>
                                        </div>
                                        <div class="d-flex align-items-center fw-bold">
                                            <div class="text-gray-400 fs-7 me-2">Status</div>
                                            <select
                                                class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto sfilter"
                                                data-control="select2" data-hide-search="true" data-minimum-results-for-search="Infinity"
                                                data-dropdown-css-class="w-150px"
                                                data-placeholder="Select an option" name="filter_status">
                                                <option></option>
                                                <option value="all" selected>Show All</option>
                                                <option value="0">For Disposal</option>
                                                <option value="1">Available</option>
                                                <option value="2">Issued</option>
                                                <option value="4">Under Repair</option>
                                            </select>
                                        </div>
                                        <div class="d-flex align-items-center fw-bold">
                                            <div class="text-gray-400 fs-7 me-2">Location</div>
                                            <select
                                                class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto sfilter"
                                                data-control="select2" data-minimum-results-for-search="Infinity" data-hide-search="true"
                                                data-dropdown-css-class="w-150px"
                                                data-placeholder="" name="filter_location">
                                                <option value="all" selected>Show all</option>
                                            </select>
                                        </div>
                                        <div class="d-flex align-items-center fw-bold">
                                            <div class="text-gray-400 fs-7 me-2">Year</div>
                                            <select
                                                class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto sfilter"
                                                data-control="select2" data-minimum-results-for-search="Infinity" data-hide-search="true"
                                                data-dropdown-css-class="w-150px"
                                                data-placeholder="" name="filter_year">
                                                <option value="all" selected>Show all</option>

                                            </select>
                                        </div>
                                    </div>
                                    <a href="{{ route('employee.new_inventory') }}" type="button" class="btn btn-primary">
                                        <i class="ki-outline ki-plus fs-2"></i> New Device
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <x-elements.datatable id="inventory-list" class="table-striped table-sm align-middle table-row-dashed dataTable">
                            </x-elements.datatable>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_consumables" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-consumable-list">
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
                                                <option value="1">Available</option>
                                                <option value="2">Issued</option>
                                            </select>
                                        </div>
                                        <div class="d-flex align-items-center fw-bold">
                                            <div class="text-gray-400 fs-7 me-2">Location</div>
                                            <select
                                                class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto sfilter"
                                                data-control="select2" data-minimum-results-for-search="Infinity" data-hide-search="true"
                                                data-dropdown-css-class="w-150px"
                                                data-placeholder="" name="filter_location">
                                                <option value="all" selected>Show all</option>
                                            </select>
                                        </div>
                                        <div class="d-flex align-items-center fw-bold">
                                            <div class="text-gray-400 fs-7 me-2">Year</div>
                                            <select
                                                class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto sfilter"
                                                data-control="select2" data-minimum-results-for-search="Infinity" data-hide-search="true"
                                                data-dropdown-css-class="w-150px"
                                                data-placeholder="" name="filter_year">
                                                <option value="all" selected>Show all</option>

                                            </select>
                                        </div>
                                    </div>
                                    <a href="{{ route('employee.new_inventory.consumables') }}" type="button" class="btn btn-primary">
                                        <i class="ki-outline ki-plus fs-2"></i> New Consumables
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <x-elements.datatable id="inventory-consumable-list" class="table-striped table-sm align-middle table-row-dashed dataTable">
                            </x-elements.datatable>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_repair" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-repair-list">
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
                                                <option value="1">In Progress</option>
                                                <option value="2">Resolved</option>
                                                <option value="3">Not Repairable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-request-repair">
                                        <i class="ki-outline ki-plus fs-2"></i>Request Repair
                                    </button>
                                </div>

                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <x-elements.datatable id="repair-list" class="table-striped table-sm align-middle table-row-dashed dataTable">
                            </x-elements.datatable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<x-elements.modal
    id="request-repair"
    title="Repair Details"
    action="/repair/update">
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 750px;">
        <div class="card-rounded bg-info bg-opacity-5 p-5 mb-7 other-details d-none">
        </div>
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <label class="form-label">Devices</label>
            <select class="form-select form-accessories" data-kt-repeater="select2" name="device" data-allow-clear="false" data-placeholder="Select an option">
            </select>
        </div>
        <div class="row">
            <div class="fv-row col-6 mb-7">
                <label class="required fw-semibold fs-6 mb-2">Start At</label>
                <input type="text" name="start_at" input-control="date-picker" default-date="current" class="form-control mb-3 mb-lg-0 flatpickr">
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <div class="fv-row col-6 mb-7">
                <label class="fw-semibold fs-6 mb-2">End At</label>
                <input type="text" name="end_at" input-control="date-picker" default-date="" class="form-control mb-3 mb-lg-0 flatpickr">
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
        </div>
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-elements.select
                id="repair_type"
                name="repair_type"
                label="Repair Type"
                :options="['1' => 'Hardware', '2' => 'Software', '3' => 'Both']"
                placeholder="Select an option"
                selected="1"
                class="fw-bold "
                data-control="select2"
                data-placeholder="Select an option"
                data-minimum-results-for-search="Infinity"
                disabled="false"
            />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
        </div>
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-elements.select
                id="status"
                name="status"
                label="Status"
                :options="['1' => 'In Progress', '2' => 'Resolved', '3'=>'Not Repairable', '4'=>'Under Warranty']"
                placeholder="Select an option"
                selected="1"
                class="fw-bold "
                data-control="select2"
                data-placeholder="Select an option"
                data-minimum-results-for-search="Infinity"
                disabled="false"
                readonly="true"
            />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
        </div>
        <div class="fv-row mb-7 fv-plugins-icon-container">
            <x-elements.select
                id="requested_by"
                name="requested_by"
                label="Requested By"
                :options="[]"
                placeholder="Select an option"
                selected="1"
                class="fw-bold "
                data-control="select2"
                data-placeholder="Select an option"
                disabled="false"
            />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
        </div>
        <div class="d-flex fv-row flex-column mb-7">
            <x-elements.textarea
                id="initial_diagnosis"
                name="initial_diagnosis"
                label="Initial Diagnosis"
                class="form-control-solid"
                data-required="false"
            />
        </div>
        <div class="d-flex fv-row flex-column mb-7">
            <x-elements.textarea
                id="work_to_be_done"
                name="work_to_be_done"
                label="Work to be Done"
                class="form-control-solid"
                data-required="false"
            />
        </div>
    </div>
</x-elements.modal>

</div>

