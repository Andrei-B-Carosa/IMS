<div class="modal fade" id="modal-{{ $id }}" tabindex="-1" aria-hidden="false" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title text-capitalize">{{ $title }}</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <span class="fw-bolder link-primary">Submit</span>.
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <form id="form-{{ $id }}" modal-id="#modal-{{ $id }}" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                        action="{{ $action }}" enctype="multipart/form-data">
                    {{ $slot }}
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_{{ $id }}" data-id="" id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_{{ $id }}" id="" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
