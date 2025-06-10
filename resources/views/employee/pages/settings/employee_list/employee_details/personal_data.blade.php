<div class="d-flex flex-column flex-lg-row">
    <div class="d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-275px mb-7 me-3">
        <div class="card card-flush mb-0">
            <div class="card-body pb-5">
                <ul class="nav nav-tabs nav-pills d-inline-block border-0 me-5 mb-3 mb-md-0 fs-6">
                    <li class="nav-item w-md-200px me-0 mb-5">
                        <a class="nav-link text-dark text-active-light fw-bold sub-tab" tab-loaded="false"
                            data-tab="1" data-bs-toggle="tab" data-tab-title="Personal Information" href="#tab_content1">
                            Personal Information
                        </a>
                    </li>
                    {{-- <li class="nav-item w-md-200px me-0 mb-5">
                        <a class="nav-link text-dark text-active-light fw-bold sub-tab" tab-loaded="false"
                            data-tab="2" data-tab-title="Family Background" data-bs-toggle="tab" href="#tab_content2">
                            Family Background
                        </a>
                    </li>
                    <li class="nav-item w-md-200px me-0 mb-5">
                        <a class="nav-link text-dark text-active-light fw-bold sub-tab" tab-loaded="false"
                            data-tab="3" data-tab-title="Educational Background" data-bs-toggle="tab" href="#tab_content3">
                            Educational Background
                        </a>
                    </li>
                    <li class="nav-item w-md-200px me-0 mb-5">
                        <a class="nav-link text-dark text-active-light fw-bold sub-tab" tab-loaded="false"
                            data-tab="4" data-tab-title="Work Experience" data-bs-toggle="tab" href="#tab_content4">
                            Work Experience
                        </a>
                    </li>
                    <li class="nav-item w-md-200px me-0 mb-5">
                        <a class="nav-link text-dark text-active-light fw-bold sub-tab" tab-loaded="false"
                            data-tab="5"  data-tab-title="Document Attachments" data-bs-toggle="tab" href="#tab_content5">
                            Document Attachments
                        </a>
                    </li>
                    <li class="nav-item w-md-200px me-0 mb-5">
                        <a class="nav-link text-dark text-active-light fw-bold sub-tab" tab-loaded="false"
                            data-tab="6" data-tab-title="References"  data-bs-toggle="tab" href="#tab_content6">
                            References
                        </a>
                    </li> --}}
                </ul>
            </div>
        </div>
    </div>
    <div class="flex-lg-row-fluid mb-7">
        <div class="flex-lg-row-fluid">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade tab1" id="tab_content1" role="tabpanel">
                    <div class="card card-flush h-lg-100">
                        <div class="card-header py-7">
                            <div class="card-title">
                                <h2 class="tab_title"></h2>
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
                        <div class="card-body pt-5" id="tab_content1">
                            <div class="" id="card1"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade tab2" id="tab_content2" role="tabpanel">
                    <div class="" id="card2"></div>
                </div>
                <div class="tab-pane fade tab3" id="tab_content3" role="tabpanel">
                    <div class="" id="card3"></div>
                </div>
                <div class="tab-pane fade tab4" id="tab_content4" role="tabpanel">
                    <div class="" id="card4"></div>
                </div>
                <div class="tab-pane fade tab5" id="tab_content5" role="tabpanel">
                    <div class="" id="card5"></div>
                </div>
                <div class="tab-pane fade tab6" id="tab_content6" role="tabpanel">
                    <div class="" id="card6"></div>
                </div>
                <div class="tab-pane fade tab7" id="tab_content7" role="tabpanel">
                    <div class="" id="card7"></div>
                </div>
            </div>
        </div>
    </div>
</div>
