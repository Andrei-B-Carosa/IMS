<div class="page-role-list">
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9 role-list">
    </div>

    <div class="modal fade" id="modal_add_user" tabindex="-1" aria-hidden="false" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="">
                    <h3 class="text-capitalize">Add New User</h3>
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
                    <x-elements.datatable id="employee_list" class="table-striped table-sm align-middle table-row-dashed dataTable">
                    </x-elements.datatable>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_user_list" tabindex="-1" aria-hidden="false" data-bs-backdrop="static" data-bs-keyboard="false">
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
                    <x-elements.datatable id="user_list" class="table-striped table-sm align-middle table-row-dashed dataTable">
                    </x-elements.datatable>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_edit_role" tabindex="-1" aria-hidden="false" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="">
                    <h3 class="text-capitalize">Role Details</h3>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body mx-5">
                    <div class="d-flex flex-column me-n7 pe-7">
                        <div class="fv-row mb-10">
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="">Role</span>
                            </label>
                            <input class="form-control form-control-solid" placeholder="Enter a role name" name="role_name" data-id="" disabled/>
                        </div>
                        <div class="fv-row">
                            <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="role_details_table">
                                    <tbody class="text-gray-600 fw-semibold"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
