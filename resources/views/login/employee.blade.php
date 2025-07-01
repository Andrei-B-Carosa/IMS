@extends('login.layout.app')
@section('title', "IMS | Login")
@section('login-form')
<form class="form w-100" id="form" novalidate="novalidate" action="{{ route('employee.login') }}">
    <div class="text-center mb-15">
        <h1 class="text-dark fw-bolder mb-3">
            Sign In your Account
        </h1>
        <div class="text-gray-500 fw-semibold fs-6">
            Employee Portal
        </div>
    </div>
    <div class="fv-row mb-8">
        <label for="" class="form-label">Username</label>
        <input type="text" placeholder="Username" name="username" autocomplete="off"
            class="form-control bg-transparent" />
    </div>
    <div class="fv-row mb-3">
        <label for="" class="form-label">Password</label>
        <input type="password" placeholder="Password" name="password" autocomplete="off"
            class="form-control bg-transparent" />
    </div>
    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
        <div></div>
        {{-- <a href="javascript:;" class="link-primary">
            Forgot Password ?
        </a> --}}
    </div>
    <div class="d-grid mb-10">
        <button type="button" class="btn btn-primary submit">
            <span class="indicator-label"> Sign In</span>
            <span class="indicator-progress">
                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>
@endsection
