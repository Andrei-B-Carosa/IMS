<div class="page-issued-devices">
    <div class="card card-issued-devices">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                    <input type="text" class="form-control form-control-solid w-250px ps-13 search" placeholder="Search here . . ." />
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">

                    {{-- <button type="button" class="btn btn-light-primary me-3"
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
                    </div> --}}

                    <button type="button" class="btn btn-light-primary me-3 export-issued-devices">
                        <i class="ki-outline ki-exit-up fs-2"></i> Export
                    </button>
{{--
                    <a href="{{ route('employee.new_inventory') }}" type="button" class="btn btn-primary">
                        <i class="ki-outline ki-plus fs-2"></i> New Inventory
                    </a> --}}
                </div>

            </div>
        </div>

        <div class="card-body pt-0">
            <x-elements.datatable id="issued-devices" class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-elements.datatable>
        </div>
    </div>
</div>

