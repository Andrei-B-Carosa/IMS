<div class="">
    <div class="card mb-5 mb-xl-8">
        <div class="card-header border-0 pb-6 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12 search" placeholder="Search here ..." />
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_documents">
                        Add Documents
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body py-0">
            <x-table
                id="documents"
                class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-table>
        </div>
    </div>

    <x-modal
        id="add_documents"
        title="Document Details"
        action="">
        <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
            <div class="form-group fv-row row">
                <label class="col-lg-3 col-form-label text-lg-right required">Document Type:</label>
                <div class="col-lg-8 mb-8">
                    <div class="fv-plugins-icon-container">
                        <select name="file_type" data-control="select2" data-placeholder="Select an option"
                                class="form-select form-select-solid fw-bold" data-required="true" remote-validation="false">
                                {!! $options['document_type'] !!}
                                <option value="others">Others</option>
                        </select>
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="form-group fv-row row mb-8 others d-none">
                <label class="col-lg-3 col-form-label text-lg-right required">Others:</label>
                <div class="col-lg-8">
                    <input class="form-control form-control-solid" type="text" name="others" disabled>
                </div>
            </div>
            <div class="form-group fv-row row">
                <label class="col-lg-3 col-form-label text-lg-right required">Upload File:</label>
                <div class="col-lg-8">
                    <input class="form-control" type="file" name="files" data-required="true" remote-validation="false">
                    <span class="form-text text-muted">Max file size is 20MB</span>
                </div>
            </div>
        </div>
    </x-modal>
</div>
