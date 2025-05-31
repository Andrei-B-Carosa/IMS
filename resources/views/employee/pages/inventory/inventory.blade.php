<div class="page-inventory-list">
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
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bold">Filter Options</div>
                        </div>
                        <!--end::Header-->

                        <!--begin::Separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Separator-->

                        <!--begin::Content-->
                        <div class="px-7 py-5" data-kt-user-table-filter="form">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-semibold">Role:</label>
                                <select class="form-select form-select-solid fw-bold"
                                    data-kt-select2="true" data-placeholder="Select option"
                                    data-allow-clear="true" data-kt-user-table-filter="role"
                                    data-hide-search="true">
                                    <option></option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Analyst">Analyst</option>
                                    <option value="Developer">Developer</option>
                                    <option value="Support">Support</option>
                                    <option value="Trial">Trial</option>
                                </select>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-semibold">Two Step
                                    Verification:</label>
                                <select class="form-select form-select-solid fw-bold"
                                    data-kt-select2="true" data-placeholder="Select option"
                                    data-allow-clear="true"
                                    data-kt-user-table-filter="two-step"
                                    data-hide-search="true">
                                    <option></option>
                                    <option value="Enabled">Enabled</option>
                                </select>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end">
                                <button type="reset"
                                    class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                    data-kt-menu-dismiss="true"
                                    data-kt-user-table-filter="reset">Reset</button>
                                <button type="submit"
                                    class="btn btn-primary fw-semibold px-6"
                                    data-kt-menu-dismiss="true"
                                    data-kt-user-table-filter="filter">Apply</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Content-->
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
