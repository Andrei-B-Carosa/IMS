"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js";
import {RequestHandler} from "../../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js";

export var dtEmployeeMasterlist = function (param=false) {

    const _page = $('.page-employee-masterlist-settings');
    const _table = 'employee_masterlist';
    const _card = $(`.card-employee-masterlist`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    const _link = 'employee-list/employee_masterlist/';

    function initTable(){

        dataTableHelper.initTable(
            _link+'dt',
            {
                filter_status:$('input[name="filter_status"]:checked').val(),
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: "No.",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "employee_name", name: "employee_name", title: "Employee",
                    sortable:false,
                    className:'',
                    render(data,type,row)
                    {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Inactive"],
                            null: ["danger", "Inactive"],
                            0: ["danger", "Inactive"],

                        };
                        return `<div class="position-relative ps-6 pe-3 py-2">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-${status[row.is_active][0]}"></div>
                                    <span class="mb-1 text-dark text-hover-primary fw-bolder">
                                        ${data} <span class="badge badge-outline badge-primary ">${row.emp_no??'No Employee Number'}</span>
                                    </span>
                                    <div class="fs-7 text-muted fw-bolder">${row.c_email??'No Email Address'}</div>
                                </div>`;
                    }
                },
                {
                    data: "emp_no", name: "emp_no", title: "Employee No.",
                    sortable:false,
                    visible:false
                },
                {
                    data: "c_email", name: "c_email", title: "Corporation Email",
                    sortable:false,
                    visible:false
                },
                {
                    data: "department_name", name: "department_name", title: "Department",
                    sortable:false,
                    searchable:false,
                    render(data,type,row){
                        return`<div class="d-flex flex-column">
                                    <span>${data}</span>
                                    ${row.position_name ?`<span class="text-muted">${row.position_name}</span>`: ``}
                                </div>`;
                    }
                },
                {
                    data: "position_name", name: "position_name", title: "Position",
                    sortable:false,
                    searchable:false,
                    className:'text-center',
                    visible:false,
                },
                // {
                //     data: "date_employed", name: "date_employed", title: "Date Employed",
                //     sortable:false,
                // },
                {
                    data: "is_active", name: "is_active", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Inactive"],
                            null: ["danger", "Inactive"],
                            0: ["danger", "Inactive"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    className: "text-center",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return `<div class="d-flex justify-content-center flex-shrink-0">
                            <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="More Actions">
                                <i class="ki-duotone ki-pencil fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
                                <div class="menu-item px-3 text-start">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                        More Actions
                                    </div>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="employee-details/${data}" data-id="${data}" class="menu-link px-3">
                                        View Details
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" data-id="${data}" class="menu-link px-3 archive">
                                        Archive Employee
                                    </a>
                                </div>
                            </div>

                            <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
                             data-bs-toggle="tooltip" title="Delete this record">
                                <i class="ki-duotone ki-trash fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                        </div>`;
                    },
                },
            ],
            null,
        );

        $(`#${_table}_table`).ready(function() {
            _card.off();

            _card.on('change','select.filter_table',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            _card.on('keyup','.search',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let searchTerm = $(this).val();
                if (e.key === 'Enter' || e.keyCode === 13) {
                    dataTableHelper.search(searchTerm);
                } else if (e.keyCode === 8 || e.key === 'Backspace') {
                    setTimeout(() => {
                        let updatedSearchTerm = $(this).val();
                        if (updatedSearchTerm === '') {
                            dataTableHelper.search('');
                        }
                    }, 0);
                }
            })

            $(`#${_table}_table`).on('click','.archive',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;
                formData.append('id',id);

                _request.post('/'+_link+'emp_details',formData)
                .then((res) => {
                    if(res.status =='success'){
                        let payload = JSON.parse(window.atob(res.payload));
                        Alert.confirm('question','Do you want to archive the employee record <br> of <b>"'+payload.name+'"</b> ?',{
                            onConfirm: function() {
                                formData.append('is_active',2);
                                _request.post('/'+_link+'archive',formData)
                                .then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.payload ==0){
                                        initTable();
                                    }else{
                                        $(`#${_table}_table`).DataTable().ajax.reload(null, false);
                                    }
                                })
                                .catch((error) => {
                                    Alert.alert('error', "Something went wrong. Try again later", false);
                                })
                                .finally((error) => {

                                });
                            }
                        });
                    }
                })
                .catch((error) => {
                    console.log(error)
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    //code here
                });
            })

            $(`#${_table}_table`).on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;
                formData.append('id',id);

                _request.post('/'+_link+'emp_details',formData)
                .then((res) => {
                    if(res.status =='success'){
                        let payload = JSON.parse(window.atob(res.payload));

                        Alert.confirm('question','Do you want to delete the employee record <br> of <b>"'+payload.name+'"</b> ?',{
                            onConfirm: function() {
                                _request.post('/'+_link+'delete',formData)
                                .then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.payload ==0){
                                        initTable();
                                    }else{
                                        $(`#${_table}_table`).DataTable().ajax.reload(null, false);
                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error', "Something went wrong. Try again later", false);
                                })
                                .finally((error) => {
                                    //code here
                                });
                            }
                        });
                    }

                })
                .catch((error) => {
                    console.log(error)
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    //code here
                });
            })

            _card.on('click','button.filter_status', function(e) {
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            });
        })
    }
    return {
        init: function () {
            initTable();
        }
    }

}

export var dtEmployeeArchiveMasterlist = function (param=false) {

    const _page = $('.page-employee-masterlist-settings');
    const _table = 'employee_archive_masterlist';
    const _modal = $(`#modal_archive_employee`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    const _link = 'employee-list/employee_masterlist/';

    function initTable(){

        dataTableHelper.initTable(
            _link+'dt',
            {
                filter_status:2,
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: "No.",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "employee_name", name: "employee_name", title: "Employee",
                    sortable:false,
                    className:'',
                    render(data,type,row)
                    {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Inactive"],
                        };
                        return `<div class="position-relative ps-6 pe-3 py-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Date Hired : ${row.date_employed}">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-${status[row.is_active][0]}"></div>
                                    <span class="mb-1 text-dark text-hover-primary fw-bolder">
                                        ${data} <span class="badge badge-outline badge-primary ">${row.emp_no??'No Employee Number'}</span>
                                    </span>
                                    <div class="fs-7 text-muted fw-bolder">${row.p_email??'No Email Address'}</div>
                                </div>`;
                    }
                },
                {
                    data: "emp_no", name: "emp_no", title: "Employee No.",
                    sortable:false,
                    visible:false
                },
                {
                    data: "p_email", name: "p_email", title: "Personal Email",
                    sortable:false,
                    visible:false
                },
                // {
                //     data: "date_employed", name: "date_employed", title: "Date Employed",
                //     sortable:false,
                //     visible:false
                // },
                {
                    data: "department_name", name: "department_name", title: "Department",
                    sortable:false,
                    searchable:false,
                    render(data,type,row){
                        return`<div class="d-flex flex-column">
                                    <span>${data}</span>
                                    ${row.section_name ?`<span class="text-muted">${row.section_name}</span>`: ``}
                                </div>`;
                    }
                },
                {
                    data: "section_name", name: "section_name", title: "Section",
                    sortable:false,
                    searchable:false,
                    className:'text-center',
                    visible:false,
                },
                {
                    data: "is_active", name: "is_active", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Archive"],
                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    className: "text-center",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return `
                            <a href="javascript:;" class="btn btn-icon btn-icon btn-light-success btn-sm me-1 hover-elevate-up restore" data-id="${data}"
                             data-bs-toggle="tooltip" title="Restore this record">
                                <i class="ki-duotone ki-check fs-2x">

                                </i>
                            </a>
                        </div>`;
                    },
                },
            ],
            null,
        );

        $(`#${_table}_table`).ready(function() {
            _modal.off();

            _modal.on('keyup','.search',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let searchTerm = $(this).val();
                if (e.key === 'Enter' || e.keyCode === 13) {
                    dataTableHelper.search(searchTerm);
                } else if (e.keyCode === 8 || e.key === 'Backspace') {
                    setTimeout(() => {
                        let updatedSearchTerm = $(this).val();
                        if (updatedSearchTerm === '') {
                            dataTableHelper.search('');
                        }
                    }, 0);
                }
            })

            $(`#${_table}_table`).on('click','.restore',function(e){
                e.stopImmediatePropagation();
                // e.preventDefault();

                let _this = $(this);
                let id    =_this.attr('data-id');

                let formData = new FormData;
                formData.append('id',id);
                formData.append('is_active',1);
                _request.post('/'+_link+'restore',formData)
                .then((res) => {
                    Alert.toast(res.status,res.message);
                    if(res.status == 'success')
                    {
                        if($(`#${_table}_table`).length && _table){
                            $(`#${_table}_table`).DataTable().ajax.reload(null,false);
                        }else{
                            initTable();
                       }
                    }
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    dtEmployeeMasterlist().init()
                });
            });
        })

    }

    return {
        init: function () {
            initTable();
        }
    }

}
