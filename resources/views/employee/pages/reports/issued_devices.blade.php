<div class="page-issued-devices">
    <div class="card mb-7" id="card-filter">
        <div class="card-body">
            <div class="align-items-center">
                <div class="row">
                    <div class="col-9">
                        <div class="flex-column align-items-start justift-content-center flex-equal">
                            <div class="position-relative w-100">
                                <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="text" class="form-control form-control-solid ps-10 search"
                                    name="search" value="" placeholder="Search here . . ." />

                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="justify-content-center align-items-end ms-5">
                            <div class="align-items-center">
                                <button type="submit" class="btn btn-primary me-5 btn-search">Search</button>

                                <a href="#" id="kt_horizontal_search_advanced_link"
                                    class="btn btn-link" data-bs-toggle="collapse"
                                    data-bs-target="#kt_advanced_search_form">Advanced Search</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="collapse show" id="kt_advanced_search_form">
                <div class="separator separator-dashed mt-9 mb-6"></div>
                <div class="row g-0 mb-8">
                    <div class="col-lg-2 me-3">
                        <label class="fs-6 form-label fw-bold text-dark">Select a Month</label>
                        <select class="form-select form-select-solid sfilter"
                            data-control="select2"
                            data-placeholder="Select a Location"
                            data-hide-search="true" name="filter_month">
                            <option></option>
                            <option value="all" selected>Show All</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}"
                                    {{ old('month', request('month')) == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-2 me-3">
                        <label class="fs-6 form-label fw-bold text-dark">Select a Year</label>
                        <select class="form-select form-select-solid sfilter"
                            data-control="select2"
                            data-placeholder="Work Type"
                            data-hide-search="true" name="filter_year">
                            <option></option>
                            <option value="all" selected>Show All</option>
                        </select>
                    </div>
                    <div class="col-lg-2 me-3">
                        <label class="fs-6 form-label fw-bold text-dark">Category</label>
                        <select class="form-select form-select-solid sfilter"
                            data-control="select2"
                            data-placeholder="Work Type"
                            data-hide-search="true" name="filter_category">
                            <option></option>
                            <option value="all" selected>Show All</option>
                        </select>
                    </div>
                    <div class="col-lg-2 me-3">
                        <label class="fs-6 form-label fw-bold text-dark">Status</label>
                        <select class="form-select form-select-solid sfilter"
                            data-control="select2"
                            data-placeholder="Work Type"
                            data-hide-search="true" name="filter_status">
                            <option></option>
                            <option value="all" selected>Show All</option>
                            <option value="0">For Disposal</option>
                            <option value="1">Available</option>
                            <option value="2">Issued</option>
                            <option value="4">Under Repair</option>
                            <option value="5">Under Warranty</option>
                            <option value="6">Deployed</option>
                        </select>
                    </div>
                    <div class="col-lg-2 me-3">
                        <label class="fs-6 form-label fw-bold text-dark">Location</label>
                        <select class="form-select form-select-solid sfilter"
                            data-control="select2"
                            data-placeholder="Work Type"
                            data-hide-search="true" name="filter_location">
                            <option></option>
                            <option value="all" selected>Show All</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-issued-devices">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                {{-- <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                    <input type="text" class="form-control form-control-solid w-250px ps-13 search" placeholder="Search here . . ." />
                </div> --}}
            </div>

            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">

                    <button type="button" class="btn btn-light-primary me-3 export-issued-devices">
                        <i class="ki-outline ki-exit-up fs-2"></i> Export
                    </button>
                </div>

            </div>
        </div>

        <div class="card-body pt-0">
            <x-elements.datatable id="issued-devices" class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-elements.datatable>
        </div>
    </div>
</div>

