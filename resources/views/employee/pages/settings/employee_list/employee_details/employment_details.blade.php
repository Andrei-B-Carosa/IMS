<div class="card card-flush mb-5">
    <div class="card-header py-7">
        <div class="card-title">
            <h2 class="">Employment Details</h2>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary me-3 cancel d-none">
                Cancel
            </button>
            <button type="button" class="btn btn-primary edit">
                <span class="indicator-label">
                    Edit Details
                </span>
                <span class="indicator-progress">
                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
            <button type="button" class="btn btn-success save d-none">
                <span class="indicator-label">
                    Save Details
                </span>
                <span class="indicator-progress">
                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="card-body pb-5">
        @include('components.employee-details.employment-details')
    </div>
</div>
