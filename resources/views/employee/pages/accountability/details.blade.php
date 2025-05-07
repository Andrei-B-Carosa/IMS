<div class="page-accountability-details">
    
    <div class="card mb-10" id="kt_profile_details_view">
        <div class="card-header cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Accountability No. {{ $data->form_no ??' ' }}</h3>
            </div>
            <button class="btn btn-primary align-self-center">
                Edit Details
            </button>
        </div>
        <div class="card-body p-9">
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Date Issued: </label>

                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ date('m-d-Y',strtotime($data->issued_at)) }}</span>
                </div>
            </div>
            {{-- {{ dd($data); }} --}}
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Issued By: </label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $data->issued_by_emp->fullname() }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">
                    Accountability Status:
                </label>
                <div class="col-lg-8 d-flex align-items-center">
                    <span class="badge badge-{{ $data->status==1?'success':'danger' }}">{{ $data->status==1?'Active':'Inactive' }}</span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Active Custodian: </label>
                <div class="col-lg-8">
                    <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{ $data->issued_to_status_1_count }} personnel</a>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Total Item: </label>
                <div class="col-lg-8">
                    <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{ $data->accountability_item_status_1_count }} items</a>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">
                    Item Returned:
                </label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $data->accountability_item_status_2_count }} items</span>
                </div>
            </div>

            {{-- <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed  p-6">
                <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                <div class="d-flex flex-stack flex-grow-1 ">
                    <div class=" fw-semibold">
                        <h4 class="text-gray-900 fw-bold">We need your attention!</h4>

                        <div class="fs-6 text-gray-700 ">Your payment was declined. To start using tools, please
                            <a class="fw-bold" href="/metronic8/demo34/account/billing.html">Add Payment Method</a>.
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    <div class="card card-flush py-4 mb-10 card-issued-items">
        <div class="card-header">
            <div class="card-title">
                <h2>Issued Items</h2>
            </div>
            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12 search"
                        placeholder="Search here ..." />
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <x-elements.datatable id="issued-item" class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-elements.datatable>
        </div>
    </div>

    <div class="card card-flush py-4 mb-10 card-issued-to">
        <div class="card-header">
            <div class="card-title">
                <h2>Accountable To</h2>
            </div>
            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12 search"
                        placeholder="Search here ..." />
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <x-elements.datatable id="issued-to" class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-elements.datatable>
        </div>
    </div>
</div>
