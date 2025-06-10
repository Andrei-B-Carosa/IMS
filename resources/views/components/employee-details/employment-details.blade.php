<div class="w-100">
    <form id="form2" card-id="#card2" action="">
        <div class="w-100 d-flex flex-column mb-5 fv-row rounded-3 p-7  border-dashed border-success">
            <div class="fv-row">
                <div class="row mb-6">
                    <label class="col-4 col-form-label required fw-bold fs-6">Employee No.</label>
                    <div class="col-8 fv-row">
                        <input type="text" class="form-control-sm form-control" id="emp_no" placeholder="Employee No. "
                            value="{{ isset($employee) ? $employee->emp_no : '' }}" name="emp_no"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                    </div>
                </div>
                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Department</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="department_id" data-minimum-results-for-search="Infinity"
                            data-control="select2" data-placeholder="Select an option" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif>
                            {!! $options['department'] !!}
                        </select>
                    </div>
                </div> --}}
                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Company</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="company_id" data-minimum-results-for-search="Infinity"
                                data-control="select2" data-placeholder="Select an option" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif>
                            {!! $options['company'] !!}
                        </select>
                    </div>
                </div> --}}
                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Location</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="location_id" data-minimum-results-for-search="Infinity" data-control="select2"
                            data-placeholder="Select an option" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif>
                            {!! $options['company_location'] !!}
                        </select>
                    </div>
                </div> --}}
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Department</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="department_id" data-minimum-results-for-search="Infinity"
                            data-control="select2" data-placeholder="Select an option" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif>
                            {!! $options['department'] !!}
                        </select>
                    </div>
                </div>
                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Section</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="section_id" data-control="select2" data-allow-clear="true"
                        data-minimum-results-for-search="Infinity" data-placeholder="Select an option" data-required="false" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif >
                            {!! $options['section'] !!}
                        </select>
                    </div>
                </div> --}}
                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Employment Type</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="employment_id" data-control="select2" data-allow-clear="true"
                            data-minimum-results-for-search="Infinity" data-placeholder="Select an option" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif>
                            {!! $options['employment_type'] !!}
                        </select>
                    </div>
                </div> --}}
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Position Title</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="position_id" data-control="select2" data-placeholder="Select an option" data-allow-clear="true"
                        data-minimum-results-for-search="Infinity" data-placeholder="Select an option" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif>
                            {!! $options['position'] !!}
                        </select>
                    </div>
                </div>
                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Classification</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="classification_id"
                            data-control="select2" data-placeholder="Select an option" data-minimum-results-for-search="Infinity" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif>
                            {!! $options['classification'] !!}
                        </select>
                    </div>
                </div> --}}
                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Pay Type</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="pay_type_id" data-control="select2" data-placeholder="Select an option" disabled>
                            <option value="" selected disabled>Select a type</option>
                            @foreach ($pay_types as $row)
                                <option value="{{ $row->pay_type_id }}"
                                    @if(isset($employee->pay_type_id) && $employee->pay_type_id == $row->pay_type_id) selected @endif
                                    >{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}

                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Work Arrangement</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="work_status"
                            data-minimum-results-for-search="Infinity" data-control="select2" data-placeholder="Select an option" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif>
                            <option></option>
                            <option value="1" @if(isset($employee->emp_details->work_status) && $employee->emp_details->work_status == 1) selected @endif>Full-Time</option>
                            <option value="2" @if(isset($employee->emp_details->work_status) && $employee->emp_details->work_status == 2) selected @endif>Part-Time</option>
                        </select>
                    </div>
                </div> --}}
                {{--
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Salary Value</label>
                    <div class="col-lg-8 fv-row">
                        <input class="form-control" name="salary_value" value="@if(isset($employee->salary_value)) {{number_format($employee->salary_value,2)}} @endif" disabled>
                    </div>
                </div> --}}
                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Date Employed</label>
                    <div class="col-lg-8 fv-row">
                        <input  type="text" class="form-control flatpickr" input-control="date-picker" name="date_employed"
                        value="@if(isset($employee->emp_details->date_employed)) {{ date('m-d-Y',strtotime($employee->emp_details->date_employed)) }} @else{{ date('m-d-Y') }} @endif" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif/>
                    </div>
                </div> --}}

                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Employment Status</label>
                    <div class="col-lg-8 fv-row">
                        <select class="form-select-sm form-select" name="is_active" @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif
                                data-minimum-results-for-search="Infinity" data-control="select2" data-placeholder="Select an option">
                            <option value="" selected disabled>Select a status</option>
                            <option value="1" @if(isset($employee->emp_details->is_active) && $employee->emp_details->is_active == 1) selected @endif>Hired</option>
                            <option value="2" @if(isset($employee->emp_details->is_active) && $employee->emp_details->is_active == 2) selected @endif>Resigned</option>
                            <option value="3" @if(isset($employee->emp_details->is_active) && $employee->emp_details->is_active == 3) selected @endif>Terminated</option>
                        </select>
                    </div>
                </div> --}}
            </div>
        </div>
    </form>
</div>
