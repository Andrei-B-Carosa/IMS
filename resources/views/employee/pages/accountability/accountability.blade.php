<div class="page-accountability">
    <div class="card mb-7 shadow-sm">
       <div class="card-header border-0 py-3">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control ps-10 form-control-lg input-search" name="search" value="" placeholder="Search here . . ." />
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">

                    {{-- <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
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
                                            Active
                                        </span>
                                    </label>
                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="radio" name="filter_status" value="2" />
                                        <span class="form-check-label text-gray-600">
                                            Inactive
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary filter" data-kt-menu-dismiss="true">Apply</button>
                            </div>
                        </div>
                    </div> --}}
                    <div class="d-flex flex-stack flex-wrap gap-4">
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-gray-400 fs-7 me-2">Tag&nbsp;Number</div>
                            <select
                                class="form-select form-select-transparent text-graY-800 fs-base lh-1 fw-bold py-0 ps-3 w-auto sfilter"
                                data-control="select2" data-hide-search="true"
                                data-dropdown-css-class="w-150px" name="filter_tag_number">
                                <option value="all" selected>Show all</option>
                            </select>
                        </div>
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
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                    <a href={{ route('employee.new_accountability') }} class="btn btn-primary btn-lg rounded-1 fs-6">
                        <i class="ki-outline ki-plus fs-2"></i>
                        New Accountability
                    </a>
                </div>
            </div>
            <!--end::Card toolbar-->
        </div>
        {{-- <div class="card-body">
            <div class="d-flex flex-wrap flex-stack">
                <div class="flex-column align-items-start justify-content-center ">
                    <div class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" class="form-control ps-10 input-search" name="search" value="" placeholder="Search here . . ." />
                    </div>
                </div>
                <div class="d-flex flex-wrap my-1">
                    <div class="m-0">
                        <a href={{ route('employee.new_accountability') }} class="btn btn-primary btn-lg rounded-1 fs-6">
                            New Accountability
                        </a>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9 accountability-list">
    </div>
    <div class="d-flex flex-stack flex-wrap pt-10 justify-content-center d-none">
        <ul class="pagination">
            <li class="page-item previous">
                <a href="#" class="page-link"><i class="previous"></i></a>
            </li>

            <li class="page-item active">
                <a href="#" class="page-link">1</a>
            </li>

            <li class="page-item">
                <a href="#" class="page-link">2</a>
            </li>

            <li class="page-item">
                <a href="#" class="page-link">3</a>
            </li>

            <li class="page-item">
                <a href="#" class="page-link">4</a>
            </li>

            <li class="page-item">
                <a href="#" class="page-link">5</a>
            </li>

            <li class="page-item">
                <a href="#" class="page-link">6</a>
            </li>

            <li class="page-item next">
                <a href="#" class="page-link"><i class="next"></i></a>
            </li>
        </ul>
    </div>
</div>
