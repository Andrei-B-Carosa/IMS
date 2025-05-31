<div id="kt_app_header" class="app-header " data-kt-sticky="true"
        data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky"
        data-kt-sticky-offset="{default: false, lg: '300px'}">
           <div class="app-container  container-xxl d-flex align-items-stretch justify-content-between "
               id="kt_app_header_container">
               <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
                   <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_header_menu_toggle">
                       <i class="ki-outline ki-abstract-14 fs-2"></i>
                   </div>
               </div>
               <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-18">
                <div class="symbol symbol-label fs-3 bg-primary text-white">
                </div>
               </div>
               <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1"
                   id="kt_app_header_wrapper">
                   <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
                       data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
                       data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
                       data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
                       data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
                       data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                       <div class=" menu
                               menu-rounded
                               menu-active-bg
                               menu-state-primary
                               menu-column
                               menu-lg-row
                               menu-title-gray-700
                               menu-icon-gray-500
                               menu-arrow-gray-500
                               menu-bullet-gray-500
                               my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">


              @foreach ($result as $data)
                    @if(count($data['file_layer']) == 0)
                        <div class="menu-item navbar me-0 me-lg-3" id="{{$data['href']}}" data-page="{{$data['href']}}" data-link="employee/{{$data['href']}}">
                            <span class="menu-link py-3">
                                <span class="menu-title">{{ $data['name'] }}</span>
                            </span>
                        </div>
                    @else
                        <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                            data-kt-menu-placement="bottom-start"
                            class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                            <span class="menu-link py-3">
                                <span class="menu-title">{{ $data['name'] }}</span>
                                <span class="menu-arrow d-lg-none"></span>
                            </span>

                            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-0 py-lg-4 w-lg-200px">
                                @foreach ($data['file_layer'] as $layer)
                                    <div class="menu-item">
                                        <a class="menu-link navbar py-3 sub-menu" id="{{$layer['href']}}" data-page="{{$layer['href']}}" data-link="{{$layer['href']}}" href="javascript:;">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ $layer['name'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
                       </div>
                   </div>

                   <div class="app-navbar flex-shrink-0">
                       <div class="app-navbar-item ms-5" id="kt_header_user_menu_toggle">
                        <div class="cursor-pointer symbol  symbol-35px symbol-md-40px"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end">
                        <div class="symbol symbol-label fs-3 bg-primary text-white">
                            {{ Auth::user()->employee->fname[0] }}
                        </div>
                    </div>
                           <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                               data-kt-menu="true">
                               <div class="menu-item px-3">
                                   <div class="menu-content d-flex align-items-center px-3">
                                    <div class="symbol symbol-50px me-5">
                                    </div>
                                       <div class="d-flex flex-column">
                                            <div class="fw-bold d-flex align-items-center fs-5">
                                                {{ Auth::user()->employee->fullname() }}
                                                <span class="badge badge-success fw-bold fs-8 px-2 py-1 ms-2">
                                                    Employee
                                                </span>
                                            </div>

                                           <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                            {{ $user = Auth::user()->username }}
                                            </a>
                                       </div>
                                   </div>
                               </div>

                               <div class="separator my-2"></div>

                               <div class="menu-item px-5">
                                   <a href="account/overview.html" class="menu-link px-5">
                                       My Profile
                                   </a>
                               </div>
                               <div class="menu-item px-5">
                                <a target="_blank" class="menu-link px-5 text-danger" onclick="event.preventDefault();
                                            document.getElementById('logout').submit();">
                                                {{ __('Sign Out') }}
                                </a>
                                <form id="logout" action="{{ route('employee.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
