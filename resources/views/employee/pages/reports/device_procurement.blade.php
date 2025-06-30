<div class="page-device-procurement">
    <div class="card card-device-procurement">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                {{-- <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                    <input type="text" class="form-control form-control-solid w-250px ps-13 search" placeholder="Search here . . ." />
                </div> --}}
            </div>

            <div class="card-toolbar">
                <div class="d-flex flex-stack flex-wrap gap-4">
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
                <div class="d-flex align-items-center position-relative my-1">
                    <button type="button" class="btn btn-light-primary me-3 export-device-procurement">
                        <i class="ki-outline ki-exit-up fs-2"></i> Export to Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body pt-0 card-body-device-procurement">

        </div>
    </div>
</div>
