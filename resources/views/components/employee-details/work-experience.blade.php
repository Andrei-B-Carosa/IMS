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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_work_experience">
                        Add Work Experience
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body py-0">
            <x-table
                id="work_experience"
                class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-table>
        </div>
    </div>

    <x-modal
        id="add_work_experience"
        title="Work Experience Details"
        action="">
        <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
            <div class="row">
                <div class="fv-row mb-7 fv-plugins-icon-container col-12">
                    <x-input
                        name="company"
                        id=""
                        label="Company"
                        value=""
                        placeholder="Company"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container col-6">
                    <x-input
                        name="position"
                        id=""
                        label="Position"
                        value=""
                        placeholder="Position"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container col-6">
                    <x-input
                        name="department"
                        id=""
                        label="Department"
                        value=""
                        placeholder="Department"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                        data-required="false"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
            </div>
            <div class="row">
                <div class="fv-row mb-7 fv-plugins-icon-container col-4">
                    <x-select
                    id="is_government"
                    name="is_government"
                    label="Is Government ?"
                    :options="['1' => 'Yes', '2' => 'No']"
                    placeholder="Select an option"
                    selected=""
                    class="form-select-solid"
                    data-control="select2"
                    data-placeholder="Select an option"
                    data-minimum-results-for-search="Infinity"
                    data-allow-clear="true"
                />
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container col-8">
                    <x-input
                        name="salary"
                        id=""
                        label="Salary"
                        value=""
                        placeholder="Salary"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
            </div>
            <div class="separator border-1 my-2"></div>
            <label class="d-flex align-items-center fs-6 fw-semibold mb-3">PERIOD OF ATTENDANCE</label>
            <div class="row">
                <div class="d-flex flex-column col-6 fv-row mb-7 fv-plugins-icon-container">
                    <label class="required fw-semibold fs-6 mb-2">From</label>
                    <input type="text" name="date_from" input-control="date-picker" default-format="24" default-date="current" class="form-control form-control-solid mb-3 mb-lg-0">
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="d-flex flex-column col-6 fv-row mb-7 fv-plugins-icon-container">
                    <label class="required fw-semibold fs-6 mb-2">To</label>
                    <input type="text" name="date_to" input-control="date-picker" default-format="24"  default-date="current" class="form-control form-control-solid mb-3 mb-lg-0">
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
           </div>
           <div class="row">
                <div class="form-group fv-row row">
                    <x-input
                        name="supporting_document"
                        id=""
                        type="file"
                        label="Supporting Document"
                        value=""
                        placeholder="Supporting Document"
                        class="form-control form-control-solid"
                        disabled="false"
                        data-required="false"
                        data-accepted="pdf"
                        remote-validation="false"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
           </div>
        </div>
    </x-modal>
</div>
