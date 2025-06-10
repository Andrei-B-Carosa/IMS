{{--
<div class="w-100">
    <form id="form2" card-id="#card2" action="">
        <div class="fv-row">
            <div class="row">
                <div class="col-xl-12 mb-7">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="lname" placeholder="Last Name"
                            value="{{ isset($employee) ? $employee->lname : '' }}" name="lname"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                        <label for="lname">Last Name</label>
                    </div>
                </div>
                <div class="col-xl-12 mb-7">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="fname"placeholder="First Name"
                            value="{{ isset($employee) ? $employee->fname : '' }}" name="fname"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                        <label for="fname">First Name</label>
                    </div>
                </div>
                <div class="col-xl-6 mb-7">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="mname"placeholder="Midlle Name"
                            value="{{ isset($employee) ? $employee->mname : '' }}" name="mname"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                        <label for="mname">Midlle Name</label>
                    </div>
                </div>
                <div class="col-xl-6 mb-7">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="ext" placeholder="Name Extension"
                        value="{{ isset($employee) ? $employee->ext : '' }}" name="ext"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                        <label for="ext">Name Extension (Jr., Sr) </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 mb-7">
                    <div class="form-floating">
                        <input type="text" class="form-control flatpickr" input-control="date-picker" id="birthdate" placeholder="(mm/dd/yyyy)"
                        value="{{ isset($employee) ? date('m-d-Y',strtotime($employee->birthday)) : '' }}"  name="birthdate"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                        <label for="birthdate">Birthdate (mm-dd-yyyy)</label>
                    </div>
                </div>
                <div class="col-xl-8 mb-7">
                    <div class="form-floating ">
                        <input type="text" class="form-control " id="birthplace" placeholder="Place of Birth"
                            value="{{ isset($employee) ? $employee->birthplace : '' }}" name="birthplace"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                        <label for="birthplace">Place of Birth</label>
                    </div>
                </div>
                <div class="col-xl-6 mb-7">
                    <div class="form-floating">
                        <select class="form-select" id="sex" name="sex"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif>
                            <option value="" selected disabled>Open this select menu</option>
                            @if ($employee)
                                <option value="0"
                                    @if ($employee->sex == 2) @selected(true) @endif>Female
                                </option>
                                <option value="1"
                                    @if ($employee->sex == 1) @selected(true) @endif>Male
                                </option>
                            @else
                                <option value="0">Female</option>
                                <option value="1">Male</option>
                            @endif
                        </select>
                        <label for="sex">Sex</label>
                    </div>
                </div>
                <div class="col-xl-6 mb-7">
                    <div class="form-floating ">
                        <select class="form-select" id="civil_status" name="civil_status"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif>
                            <option value="" selected disabled>Open this select menu</option>
                            @if (isset($employee))
                                <option value="1"
                                    @if ($employee->civil_status == 1) @selected(true) @endif>Single
                                </option>
                                <option value="2"
                                    @if ($employee->civil_status == 2) @selected(true) @endif>Married
                                </option>
                                <option value="3"
                                    @if ($employee->civil_status == 3) @selected(true) @endif>Widowed
                                </option>
                                <option value="4"
                                    @if ($employee->civil_status == 4) @selected(true) @endif>
                                    Separated</option>
                                <option value="5"
                                    @if ($employee->civil_status == 5) @selected(true) @endif>Other/s
                                </option>
                            @else
                                <option value="1">Single</option>
                                <option value="2">Married</option>
                                <option value="3">Widowed</option>
                                <option value="4">Separated</option>
                                <option value="5">Other/s</option>
                            @endif
                        </select>
                        <label for="civil_status">Civil Status</label>
                    </div>
                </div>
                <div class="col-xl-6 mb-7">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="telephone" placeholder="Telephone No."
                            name="telephone" value="{{ isset($employee) ? $employee->telephone_number : '' }}"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif  data-required="false"/>
                        <label for="telephone">Telephone No.</label>
                    </div>
                </div>
                <div class="col-xl-6 mb-7">
                    <div class="form-floating ">
                        <input type="text" class="form-control" id="mobile" placeholder="Mobile No." name="mobile"
                            value="{{ isset($employee) ? $employee->mobile_number : '' }}"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                        <label for="mobile">Mobile No.</label>
                    </div>
                </div>
                <div class="col-xl-12 mb-7">
                    <div class="form-floating ">
                        <input type="text" class="form-control" id="email" placeholder="Personal Email (if any)"
                            name="email" value="{{ isset($employee) ? $employee->p_email : '' }}"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif  data-required="false"/>
                        <label for="email">Personal Email (if any)</label>
                    </div>
                </div>
            </div>
            <div class="row d-flex flex-column mb-5 fv-row rounded-3 p-7  border-dashed border-success">
                <div class="col-xl-8 mb-7">
                    <label class="mb-3">CITIZENSHIP</label>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-check">
                                @if (isset($employee))
                                    <input class="form-check-input" type="radio" value="FILIPINO" id="filipino"
                                        name="citizenship" @if ($employee->citizenship == 'FILIPINO') checked @endif disabled />
                                    <input class="form-check-input" type="radio" value="FILIPINO" id="filipino"
                                        name="citizenship" @if ($employee->citizenship == 'FILIPINO') checked @endif disabled />
                                @else
                                    <input class="form-check-input" type="radio" value="FILIPINO" id="filipino"
                                        name="citizenship" />
                                    <input class="form-check-input" type="radio" value="FILIPINO" id="filipino"
                                        name="citizenship" />
                                @endif
                                <label class="form-check-label" for="filipino">
                                    Filipino
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-check">
                                @if (isset($employee))
                                    <input class="form-check-input" type="radio" value="OTHER" id="dual"
                                        name="citizenship" @if ($employee->citizenship == 'OTHER') checked @endif disabled />
                                @else
                                    <input class="form-check-input" type="radio" value="OTHER" id="dual"
                                        name="citizenship" />
                                @endif
                                <label class="form-check-label" for="dual">
                                    Dual Citizenship
                                </label>
                            </div>
                            <div class="row mt-5 ms-3">
                                <div class="col-xl-6">
                                    <div class="form-check">
                                        @if (isset($employee))
                                            <input class="form-check-input" type="radio" value="1" id="birth"
                                                name="citizen" @if ($employee->dual_citizenship['citizen'] == 1) checked @endif
                                                disabled />
                                            <input class="form-check-input" type="radio" value="1" id="birth"
                                                name="citizen" @if ($employee->dual_citizenship['citizen'] == 1) checked @endif
                                                disabled />
                                        @else
                                            <input class="form-check-input" type="radio" value="1" id="birth"
                                                name="citizen" />
                                            <input class="form-check-input" type="radio" value="1" id="birth"
                                                name="citizen" />
                                        @endif
                                        <label class="form-check-label" for="birth"> by birth</label>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-check">
                                        @if (isset($employee))
                                            <input class="form-check-input" type="radio" value="2"
                                                id="naturalization" name="citizen"
                                                @if ($employee->dual_citizenship['citizen'] == 2) checked @endif disabled />
                                            <input class="form-check-input" type="radio" value="2"
                                                id="naturalization" name="citizen"
                                                @if ($employee->dual_citizenship['citizen'] == 2) checked @endif disabled />
                                        @else
                                            <input class="form-check-input" type="radio" value="2"
                                                id="naturalization" name="citizen" />
                                            <input class="form-check-input" type="radio" value="2"
                                                id="naturalization" name="citizen" />
                                        @endif
                                        <label class="form-check-label" for="naturalization"> by
                                            naturalization</label>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="form-floating ">
                                        <input type="text" class="form-control form-control-flush"
                                            id="citizen_country" placeholder="Pls. indicate country"
                                            name="citizen_country"
                                            value="{{ isset($employee) ? $employee->dual_citizenship['country'] : '' }}"
                                            disabled />
                                        <label for="citizen_country">Pls. indicate country :</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator border-1 my-7"></div>
            <div class="row">
                <div class="col-xl-4 mb-7">
                    <div class="form-floating ">
                        <input type="number" class="form-control" id="floatingInput" placeholder="Height (m)"
                            name="height" value="{{ isset($employee) ? $employee->height : '' }}"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                        <label for="floatingInput">Height (m)</label>
                    </div>
                </div>
                <div class="col-xl-4 mb-7">
                    <div class="form-floating ">
                        <input type="text" class="form-control" id="weight" placeholder="Weight (kg)"
                            name="weight" value="{{ isset($employee) ? $employee->weight : '' }}"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                        <label for="weight">Weight (kg)</label>
                    </div>
                </div>
                <div class="col-xl-4 mb-7">
                    <div class="form-floating ">
                        <input type="text" class="form-control" id="blood_type" placeholder="Blood Type"
                            name="blood_type" value="{{ isset($employee) ? $employee->blood_type : '' }}"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                        <label for="blood_type">Blood Type</label>
                    </div>
                </div>
                <div class="col-xl-6 mb-7">
                    <div class="form-floating ">
                        <input type="text" class="form-control" id="gsis" placeholder="GSIS ID No."
                            name="gsis" value="{{ isset($employee) ? $employee->gsis : '' }}"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                        <label for="gsis">GSIS ID No.</label>
                    </div>
                </div>
                <div class="col-xl-6 mb-7">
                    <div class="form-floating ">
                        <input type="text" class="form-control" id="pagibig" placeholder="Pagibig No."
                            name="pagibig" value="{{ isset($employee) ? $employee->pagibig : '' }}"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                        <label for="pagibig">Pagibig No.</label>
                    </div>
                </div>
                <div class="col-xl-6 mb-7">
                    <div class="form-floating ">
                        <input type="text" class="form-control" id="philhealth" placeholder="Philhealth No."
                            name="philhealth" value="{{ isset($employee) ? $employee->philhealth : '' }}"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                        <label for="philhealth">Philhealth No.</label>
                    </div>
                </div>
                <div class="col-xl-6 mb-7">
                    <div class="form-floating ">
                        <input type="text" class="form-control" id="sss" placeholder="SSS No." name="sss"
                            value="{{ isset($employee) ? $employee->sss : '' }}"
                            @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                        <label for="sss">SSS No.</label>
                    </div>
                </div>
                <div class="col-xl-12 mb-7">
                    <div class="form-floating ">
                        <input type="text" class="form-control" id="tin" placeholder="TIN No." name="tin"
                            value="{{ isset($employee) ? $employee->tin : '' }}"
                            @if($employee  && !$isRegisterEmployee)
                                @disabled(true)
                            @endif  data-required="false"/>
                        <label for="tin">TIN No.</label>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div> --}}


<div class="w-100 mb-5">
    <div class="card card-flush h-lg-100" id="">
        <div class="card-header py-7">
            <div class="card-title">
                <h3 class="">Father's Information</h3>
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
        <div class="card-body pt-5">
            <form id="form-father-details" class="form2" action="">
                <div class="fv-row">
                    <div class="row">
                        <div class="col-xl-12 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="lname" placeholder="Last Name"
                                    value="{{ isset($employee) ? $employee->father_lname : '' }}" name="lname"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                                <label for="lname">Last Name</label>
                            </div>
                        </div>
                        <div class="col-xl-12 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fname"placeholder="First Name"
                                    value="{{ isset($employee) ? $employee->father_fname : '' }}" name="fname"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                                <label for="fname">First Name</label>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="mname"placeholder="Midlle Name"
                                    value="{{ isset($employee) ? $employee->father_mname : '' }}" name="mname"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                                <label for="mname">Midlle Name</label>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="ext" placeholder="Name Extension"
                                value="{{ isset($employee) ? $employee->father_ext : '' }}" name="ext"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                                <label for="ext">Name Extension (Jr., Sr) </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="w-100 mb-5">
    <div class="card card-flush h-lg-100" id="">
        <div class="card-header py-7">
            <div class="card-title">
                <h3 class="">Mother's Information</h3>
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
        <div class="card-body pt-5">
            <form id="form-mother-details" class="form2" action="">
                <div class="fv-row">
                    <div class="row">
                        <div class="col-xl-12 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="lname" placeholder="Last Name"
                                    value="{{ isset($employee) ? $employee->mother_lname : '' }}" name="lname"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                                <label for="lname">Last Name</label>
                            </div>
                        </div>
                        <div class="col-xl-12 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fname"placeholder="First Name"
                                    value="{{ isset($employee) ? $employee->mother_fname : '' }}" name="fname"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                                <label for="fname">First Name</label>
                            </div>
                        </div>
                        <div class="col-xl-12 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="mname"placeholder="Midlle Name"
                                    value="{{ isset($employee) ? $employee->mother_mname : '' }}" name="mname"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                                <label for="mname">Midlle Name</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="w-100 mb-5">
    <div class="card card-flush h-lg-100" id="">
        <div class="card-header py-7">
            <div class="card-title">
                <h3 class="">Spouse Information</h3>
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
        <div class="card-body pt-5">
            <form id="form-spouse-details" class="form2" action="">
                <div class="fv-row">
                    <div class="row">
                        <div class="col-xl-12 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" placeholder="Last Name"
                                    value="{{ isset($employee) ? $employee->spouse_lname : '' }}" name="spouse_lname"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                                <label for="lname">Last Name</label>
                            </div>
                        </div>
                        <div class="col-xl-12 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" placeholder="First Name"
                                    value="{{ isset($employee) ? $employee->spouse_fname : '' }}" name="spouse_fname"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif />
                                <label for="fname">First Name</label>
                            </div>
                        </div>
                        <div class="col-xl-12 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" placeholder="Midlle Name"
                                    value="{{ isset($employee) ? $employee->spouse_mname : '' }}" name="spouse_mname"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                                <label for="">Midlle Name</label>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" placeholder="Spouse Employer"
                                    value="{{ isset($employee) ? $employee->spouse_employer : '' }}" name="spouse_employer"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                                <label for="">Spouse Employer</label>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" placeholder="Spouse Occupation"
                                    value="{{ isset($employee) ? $employee->spouse_occupation : '' }}" name="spouse_occupation"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                                <label for="">Spouse Occupation</label>
                            </div>
                        </div>
                        <div class="col-xl-12 mb-7">
                            <div class="form-floating">
                                <input type="text" class="form-control" placeholder="Spouse Business Address"
                                    value="{{ isset($employee) ? $employee->spouse_business_address : '' }}" name="spouse_business_address"
                                    @if (isset($employee) && !$isRegisterEmployee) @disabled(true) @endif data-required="false"/>
                                <label for="">Spouse Business Address</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

