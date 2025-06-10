<div class="page-employee-details">
    <div class="card mb-5">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap">
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <div class="symbol-label fs-5x bg-light-primary text-primary">
                            {{ $data['fullname'][0] }}
                        </div>
                        <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-{{ $data['is_active']==1 ?'success':'danger' }}
                                    rounded-circle border border-4 border-body h-20px w-20px">
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                                    {{ $data['fullname'] }}
                                </a>
                            </div>
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <a href="#"
                                    class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="ki-duotone ki-profile-circle fs-4 me-1"><span
                                            class="path1"></span><span
                                            class="path2"></span><span
                                            class="path3"></span></i> Employee
                                </a>
                                <a href="#"
                                    class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="ki-duotone ki-geolocation fs-4 me-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span></i>
                                        {{ $data['dept_code'] }} ({{ $data['position'] }})
                                </a>
                                <a href="#"
                                    class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <i class="ki-duotone ki-sms fs-4"><span
                                            class="path1"></span><span
                                            class="path2"></span></i>  {{ $data['c_email']!== false ? $data['c_email'] : 'No Corporate Email' }}
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="d-flex flex-wrap flex-stack">
                        <div class="d-flex flex-column flex-grow-1 pe-8">
                            <div class="d-flex flex-wrap">
                                <div
                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-2 fw-bold">{{ $data['date_employed'] }}</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-400">Date Hired
                                    </div>
                                </div>
                                <div
                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-2 fw-bold">{{ $data['tenure'] }}</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-400">Tenure
                                    </div>
                                </div>
                                <div
                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-3 fw-bold" >{{ $data['employment_type'] }}</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-400">Employment
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 main-tab" data-tab="personal_data"
                        href="javascript:;">
                        Personal Data
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 main-tab" data-tab="employment_details"
                        href="javascript:;">
                        Employment Details
                    </a>
                </li>
                @if($data['c_email'] !== false)
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 main-tab" data-tab="account_security"
                            href="javascript:;">
                            Account Security
                        </a>
                    </li>
                @endif
                {{-- <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 main-tab" data-tab="activity_logs"
                        href="javascript:;">
                        Activity Logs
                    </a>
                </li> --}}
            </ul>
        </div>
    </div>

    <div class="main-content">

    </div>
</div>
