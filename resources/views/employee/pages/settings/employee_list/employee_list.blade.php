<div class="page-employee-masterlist-settings">
    <div class="card mb-5 mb-xl-8 card-employee-masterlist">
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
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-light-primary me-2" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="ki-duotone ki-filter fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Filter
                    </button>

                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                        <div class="px-7 py-5">
                            <div class="fs-4 text-dark fw-bold">Filter Options</div>
                        </div>
                        <div class="separator border-gray-200"></div>
                        <div class="px-7 py-5">
                            <div class="mb-7">
                                <label class="form-label fs-5 fw-semibold mb-3">Status:</label>
                                <div class="d-flex flex-column flex-wrap fw-semibold">
                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                        <input class="form-check-input" type="radio" name="filter_status" value="all" checked="checked" />
                                        <span class="form-check-label text-gray-600">
                                            View All
                                        </span>
                                    </label>
                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                        <input class="form-check-input" type="radio" name="filter_status" value="1" />
                                        <span class="form-check-label text-gray-600">
                                            Hired
                                        </span>
                                    </label>
                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="radio" name="filter_status" value="2" />
                                        <span class="form-check-label text-gray-600">
                                            Resigned
                                        </span>
                                    </label>
                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                        <input class="form-check-input" type="radio" name="filter_status" value="3" />
                                        <span class="form-check-label text-gray-600">
                                            Terminated
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                {{-- <button type="button" class="btn btn-light btn-active-light-primary me-2 reset" data-kt-menu-dismiss="true" >Reset</button> --}}

                                <button type="button" class="btn btn-primary filter_status" data-kt-menu-dismiss="true">Apply</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <button type="button" class="btn btn-info btn-flex me-2 view-archive">
                        <i class="ki-duotone ki-save-2 fs-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Archive Record
                    </button>
                    <a href="{{ route('employee.register_employee') }}" target="_blank" type="button" class="btn btn-primary btn-flex">
                        <i class="ki-duotone ki-plus fs-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Add New Employee
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body py-0">
            <x-elements.datatable
                id="employee_masterlist"
                class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-elements.datatable>
        </div>
    </div>

    <div class="modal fade" id="modal_archive_employee" tabindex="-1" aria-hidden="false" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="">
                    <h3 class="text-capitalize">User List</h3>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" class="form-control form-control-solid ps-15 search form-sm" placeholder="Search here . . ." />
                    </div>
                    <x-elements.datatable id="employee_archive_masterlist" class="table-striped table-sm align-middle table-row-dashed dataTable">
                    </x-elements.datatable>
                </div>
            </div>
        </div>
    </div>
</div>
