<div class="d-flex flex-column gap-7 gap-lg-10">
    <div class="card card-accountability-list">
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
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <a href="{{ route('employee.new_accountability') }}" type="button" class="btn btn-primary">
                        <i class="ki-outline ki-plus fs-2"></i> New Accountability
                    </a>
                </div>

            </div>
        </div>
        <div class="card-body pt-0">
            <x-elements.datatable id="accountability-list" class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-elements.datatable>
        </div>
    </div>
</div>
