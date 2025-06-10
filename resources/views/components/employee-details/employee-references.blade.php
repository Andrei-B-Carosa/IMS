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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_references">
                        Add References
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body py-0">
            <x-table
                id="references"
                class="table-striped table-sm align-middle table-row-dashed dataTable">
            </x-table>
        </div>
    </div>

    <x-modal
        id="add_references"
        title="Reference Details"
        action="">
        <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
            <div class="row">
                {{-- <div class="fv-row mb-7 fv-plugins-icon-container col-4">
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
                </div> --}}
                <div class="fv-row mb-7 fv-plugins-icon-container col-12">
                    <x-input
                        name="name"
                        id=""
                        label="Name"
                        value=""
                        placeholder="Name"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container col-12">
                    <x-input
                        name="address"
                        id=""
                        label="Address"
                        value=""
                        placeholder="Address"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                        data-required="false"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
            </div>
            <div class="row">
                <div class="fv-row mb-7 fv-plugins-icon-container col-6">
                    <x-input
                        name="email"
                        id=""
                        label="Email"
                        value=""
                        placeholder="Email"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                        data-required="false"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
                <div class="fv-row mb-7 fv-plugins-icon-container col-6">
                    <x-input
                        name="mobile_number"
                        id=""
                        label="Mobile Number"
                        value=""
                        placeholder="Mobile Number"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                        {{-- data-required="false" --}}
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
            </div>
            <div class="row">
                <div class="fv-row mb-7 fv-plugins-icon-container col-8">
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
                <div class="fv-row mb-7 fv-plugins-icon-container col-4">
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
                <div class="fv-row mb-7 fv-plugins-icon-container col-12">
                    <x-input
                        name="relation_to_reference"
                        id=""
                        label="Relation to Reference"
                        value=""
                        placeholder="Relation to Reference"
                        class="form-control form-control-solid"
                        disabled="false"
                        remote-validation="true"
                        data-required="false"
                    />
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                </div>
            </div>
        </div>
    </x-modal>
</div>
