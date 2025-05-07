<div class="modal fade" id="modal-edit-other-item" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg ">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">Item Details</h1>
                    <div class="text-muted fs-5">To update, fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body px-10">
                <form id="form-edit-other-item" modal-id="#modal-edit-other-item" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/accountability-details/update-issued-items">
                    <div class="px-5">
                        <div class="d-flex flex-column col-12  fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Item</label>
                            <input type="text" name="item" class="form-control mb-3 mb-lg-0" value="{{ $query->item_inventory->name }}">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column col-12  fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Description</label>
                            <input type="text" name="description" class="form-control mb-3 mb-lg-0" value="{{ $query->item_inventory->description }}">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex fv-row flex-column mb-7" id="">
                            <label class="fs-6 required fw-semibold mb-2">Remarks</label>
                            <textarea class="form-control form-control-solid" rows="5" name="remarks" placeholder="Remarks">{{ $query->remarks }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal-edit-other-item" data-id="{{ Crypt::encrypt($query->id) }}"
                    class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal-edit-other-item" class="btn btn-light me-3 cancel">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
