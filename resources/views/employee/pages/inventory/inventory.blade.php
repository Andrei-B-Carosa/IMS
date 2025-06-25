<div class="page-inventory-list pt-3">
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#tab_devices">Devices</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#tab_consumables">Consumables</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab_devices" role="tab-panel">
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

                                    <button type="button" class="btn btn-light-primary me-3"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-outline ki-filter fs-2"></i> Filter
                                    </button>
                                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px"
                                        data-kt-menu="true">
                                        <div class="px-7 py-5">
                                            <div class="fs-5 text-dark fw-bold">Filter Options</div>
                                        </div>
                                        <div class="separator border-gray-200"></div>
                                        <div class="px-7 py-5" data-kt-user-table-filter="form">
                                            <div class="mb-10">
                                                <label class="form-label fs-6 fw-semibold">Item:</label>
                                                <select class="form-select form-select-solid fw-bold"
                                                    data-placeholder="Select option"
                                                    data-allow-clear="true"
                                                    data-hide-search="true" name="filter_item">
                                                    <option></option>
                                                    <option value="all" selected>All</option>
                                                    <option value="1">Accountability</option>
                                                    <option value="2">Material Issuance</option>
                                                </select>
                                            </div>
                                            <div class="mb-10">
                                                <label class="form-label fs-6 fw-semibold">Status:</label>
                                                <select class="form-select form-select-solid fw-bold"
                                                    data-placeholder="Select option"
                                                    data-allow-clear="true"
                                                    data-hide-search="true" name="filter_status">
                                                    <option></option>
                                                    <option value="all" selected>All</option>
                                                    <option value="1">Available</option>
                                                    <option value="2">Issued</option>
                                                </select>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-primary fw-semibold px-6 filter"
                                                    data-kt-menu-dismiss="true">Apply</button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-light-primary me-3"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                        <i class="ki-outline ki-exit-up fs-2"></i> Export
                                    </button>

                                    <a href="{{ route('employee.new_inventory') }}" type="button" class="btn btn-primary">
                                        <i class="ki-outline ki-plus fs-2"></i> New Inventory
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
            <div class="tab-pane fade show active" id="tab_consumables" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                </div>
            </div>
        </div>
    </div>

<x-elements.modal
    id="request-repair"
    title="Repair Details"
    action="/inventory/update-repair">
    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
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
                :options="['1' => 'Hardware', '2' => 'Software']"
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
                :options="['1' => 'In Progress', '2' => 'Resolved', '3'=>'Not Repairable']"
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
        <div class="d-flex fv-row flex-column mb-7">
            <x-elements.textarea
                id="description"
                name="description"
                label="Description"
                class="form-control-solid"
                data-required="false"
            />
        </div>

    </div>
</x-elements.modal>

</div>

