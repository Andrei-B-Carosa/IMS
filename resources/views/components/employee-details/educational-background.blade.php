<div class="">
    <div class="card mb-5 mb-xl-8 card-employee-masterlist">
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_education">
                        Add Education
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body py-0">
            <x-table
                id="educational_background"
                class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-table>
        </div>
    </div>

    <x-modal
        id="add_education"
        title="Education Details"
        action="">
        <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
            <div class="row">
                <div class="fv-row mb-7 fv-plugins-icon-container col-4">
                    <x-select
                    id="level"
                    name="level"
                    label="Level"
                    :options="['1' => 'Elementary', '2' => 'Secondary', '3'=>'Vocational/Trade Course', '4'=>'College', '5'=>'Graduate Studies']"
                    placeholder="Select an option"
                    selected=""
                    class="form-select-solid"
                    data-control="select2"
                    data-placeholder="Select an option"
                    data-minimum-results-for-search="Infinity"
                />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container col-8">
                    <x-input
                        name="school"
                        id=""
                        label="Name of School"
                        value=""
                        placeholder="Name of School"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
            </div>
            <div class="fv-row mb-7 fv-plugins-icon-container">
                <x-input
                    name="degree"
                    id=""
                    label="Basic Education/Degree/Course"
                    value=""
                    placeholder="Basic Education/Degree/Course"
                    class="form-control form-control-solid"
                    disabled="false"
                    remote-validation="true"
                />
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <div class="separator border-1 my-2"></div>
            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">PERIOD OF ATTENDANCE</label>
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
                <div class="fv-row col-4 mb-7 fv-plugins-icon-container">
                    <x-input
                        name="year_graduate"
                        id=""
                        label="Year Graduated"
                        value=""
                        placeholder="Year Graduated"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row col-8 mb-7 fv-plugins-icon-container">
                    <x-input
                        name="units"
                        id=""
                        label="Highest Level/Units Earned"
                        value=""
                        placeholder="Highest Level/Units Earned"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row col-12 mb-7 fv-plugins-icon-container">
                    <x-input
                        name="honors"
                        id=""
                        label="Academic Honors Received"
                        value=""
                        placeholder="Academic Honors Received"
                        class="form-control form-control-solid"
                        disabled="false"
                        data-required="false"
                        remote-validation="false"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
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
