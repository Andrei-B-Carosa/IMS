"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js";
import {RequestHandler} from "../../../../global/request.js";

export var dtEnrollUser = function (param) {
    const _table = 'employee_list';
    const _modal = $(`#modal_add_user`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'user-management/role-list/employee-list',
            {
                filter_status:false,
                role_id:param,
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
                    data: "emp_no", name: "emp_no", title: "Employee No.",
                    sortable:false,
                    visible:false
                },
                {
                    data: "emp_name", name: "emp_name", title: "Employee",
                    sortable:false,
                    className:'',
                    render(data,type,row)
                    {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Inactive"],
                        };
                        return `<div class="position-relative ps-6 pe-3 py-2">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-${status[row.is_active][0]}"></div>
                                    <span class="mb-1 text-dark text-hover-primary fw-bolder">
                                        ${data} <br><span class="badge badge-outline badge-primary ">${row.emp_no??'No Employee Number'}</span>
                                    </span>
                                </div>`;
                    }
                },
                {
                    data: "emp_department", name: "emp_department", title: "Department",
                    sortable:false,
                    render(data,type,row)
                    {
                        return `${data ?? 'No Department'}<br> <span class="text-muted fs-6">${row.emp_position ?? 'No Position'}</span>`
                    }
                },
                {
                    data: "emp_position", name: "emp_position", title: "Position",
                    sortable:false,
                    visible:false,
                },
                {
                    data: "is_active", name: "is_active", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Inactive"],
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
                        <div class="form-check form-check-sm d-inline-block form-check-custom form-check-solid">
                                <input class="form-check-input enroll" type="checkbox" value="1" data-id="${data}"">
                            </div>`;
                    },
                },
            ],
            null,
        );

        $(`#${_table}_table`).ready(function() {

            _modal.off('keyup','.search').on('keyup','.search',function(e){
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

            $(`#${_table}_table`).on('click','.enroll',function(e){
                e.stopImmediatePropagation();
                // e.preventDefault();

                let _this = $(this);
                let id    =_this.attr('data-id');

                let formData = new FormData;
                formData.append('id',id);
                formData.append('role_id',param);
                formData.append('is_active',$(this).is(":checked")?1:2);
                _request.post('/user-management/role-list/update',formData)
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

export var dtUserList = function (param) {
    const _table = 'user_list';
    const _modal = $(`#modal_user_list`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'user-management/role-list/user-list',
            {
                filter_status:false,
                role_id:param,
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
                    data: "emp_no", name: "emp_no", title: "Employee No.",
                    sortable:false,
                    visible:false
                },
                {
                    data: "emp_name", name: "emp_name", title: "Employee",
                    sortable:false,
                    className:'',
                    render(data,type,row)
                    {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Inactive"],
                        };
                        return `<div class="position-relative ps-6 pe-3 py-2">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-${status[row.is_active][0]}"></div>
                                    <span class="mb-1 text-dark text-hover-primary fw-bolder">
                                        ${data} <br><span class="badge badge-outline badge-primary ">${row.emp_no??'No Employee Number'}</span>
                                    </span>
                                </div>`;
                    }
                },
                {
                    data: "emp_department", name: "emp_department", title: "Department",
                    sortable:false,
                    render(data,type,row)
                    {
                        return `${data}<br> <span class="text-muted fs-6">${row.emp_position}</span>`
                    }
                },
                {
                    data: "emp_position", name: "emp_position", title: "Position",
                    sortable:false,
                    visible:false,
                },
                {
                    data: "is_active", name: "is_active", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Inactive"],
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
                            <a href="javascript:;" class="btn btn-icon btn-sm btn-icon btn-light-danger btn-sm hover-elevate-up delete" data-id="${data}"
                             data-bs-toggle="tooltip" title="Remove Member">
                                <i class="ki-duotone ki-cross fs-2">
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

            _modal.off('keyup','.search').on('keyup','.search',function(e){
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

            $(`#${_table}_table`).on('click','.delete',function(e){
                e.stopImmediatePropagation();
                // e.preventDefault();

                let _this = $(this);
                let id    =_this.attr('data-id');

                let formData = new FormData;
                formData.append('emp_id',id);
                formData.append('role_id',param);
                formData.append('is_active',2);
                _request.post('/user-management/role-list/delete',formData)
                .then((res) => {
                    Alert.toast(res.status,res.message);
                    if(res.status == 'info')
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

// export var dtPermission = function (param) {

//     const _page = $('.page-permissions');
//     const _table = 'permissions';
//     const _tab = $(`.${_table}`);
//     const _request = new RequestHandler;
//     const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

//     function initTable(){

//         dataTableHelper.initTable(
//             'hris/admin/user_management/permission/dt',
//             {
//                 filter_status:false,
//             },
//             [
//                 {
//                     data: "count",
//                     name: "count",
//                     title: "No.",
//                     responsivePriority: -3,
//                     searchable:false,
//                 },
//                 {
//                     data: "name", name: "name", title: "Module",
//                     sortable:false,
//                 },
//                 {
//                     data: "folder", name: "folder", title: "Folder",
//                     sortable:false,
//                     className:'text-muted',
//                     render: function (data, type, row) {
//                         return data ?? '--';
//                     },
//                 },
//                 {
//                     data: "assigned_to", name: "assigned_to", title: "Assigned To",
//                     sortable:false,
//                     searchable:false,
//                     render: function (data, type, row) {
//                         if (Array.isArray(data) && data.length > 0) {
//                             // Generate Bootstrap badges for each name
//                             return data.map(item =>
//                                 `<span class="badge badge-light-primary fs-7 m-1">${item.name}</span>`
//                             ).join(" ");
//                         } else {
//                             // Return a placeholder if no roles are assigned
//                             return `<span class="badge badge-light-info fs-7 m-1">No roles assigned</span>`;
//                         }
//                     },
//                 },
//                 {
//                     data: "last_updated_by", name: "last_updated_by", title: "Last Updated By",
//                     sortable:false,
//                     searchable:false,
//                     className:'',
//                     render(data,type,row)
//                     {
//                         if(!data){
//                             return '--';
//                         }
//                         return `<div class="d-flex align-items-center">
//                                 <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
//                                     <span>
//                                         <div class="symbol-label fs-3 bg-light-info text-info">
//                                             ${data[0]}
//                                         </div>
//                                     </span>
//                                 </div>
//                                 <div class="d-flex flex-column">
//                                     <span class="text-gray-800 text-hover-primary mb-1">
//                                         ${data}
//                                     </span>
//                                     <span class="text-muted fs-8">Last Updated</span>
//                                 </div>
//                             </div>
//                         `
//                     }
//                 },
//                 // {
//                 //     data: "encrypted_id",
//                 //     name: "encrypted_id",
//                 //     title: "Action",
//                 //     sortable:false,
//                 //     className: "text-center",
//                 //     responsivePriority: -1,
//                 //     render: function (data, type, row) {
//                 //         return `<div class="d-flex justify-content-center flex-shrink-0">
//                 //             <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
//                 //             data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="More Actions">
//                 //                 <i class="ki-duotone ki-pencil fs-2x">
//                 //                     <span class="path1"></span>
//                 //                     <span class="path2"></span>
//                 //                     <span class="path3"></span>
//                 //                     <span class="path4"></span>
//                 //                 </i>
//                 //             </a>
//                 //             <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
//                 //                 <div class="menu-item px-3 text-start">
//                 //                     <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
//                 //                         More Actions
//                 //                     </div>
//                 //                 </div>
//                 //                 <div class="menu-item px-3">
//                 //                     <a href="javascript:;" data-id="${data}" class="menu-link px-3 view-details">
//                 //                         View Details
//                 //                     </a>
//                 //                 </div>
//                 //             </div>

//                 //             <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
//                 //              data-bs-toggle="tooltip" title="Delete this record">
//                 //                 <i class="ki-duotone ki-trash fs-2x">
//                 //                     <span class="path1"></span>
//                 //                     <span class="path2"></span>
//                 //                     <span class="path3"></span>
//                 //                     <span class="path4"></span>
//                 //                 </i>
//                 //             </a>
//                 //         </div>`;
//                 //     },
//                 // },
//             ],
//             null,
//         );

//         $(`#${_table}_table`).ready(function() {

//             // _tab.off();

//             // _tab.on('change','select.filter_table',function(e){
//             //     e.preventDefault()
//             //     e.stopImmediatePropagation()
//             //     initTable();
//             // })

//             _tab.on('keyup','.search',function(e){
//                 e.preventDefault()
//                 e.stopImmediatePropagation()
//                 let searchTerm = $(this).val();
//                 if (e.key === 'Enter' || e.keyCode === 13) {
//                     dataTableHelper.search(searchTerm);
//                 } else if (e.keyCode === 8 || e.key === 'Backspace') {
//                     setTimeout(() => {
//                         let updatedSearchTerm = $(this).val();
//                         if (updatedSearchTerm === '') {
//                             dataTableHelper.search('');
//                         }
//                     }, 0);
//                 }
//             })

//             // $(`#${_table}_table`).on('click','.view-details',function(e){
//             //     e.preventDefault()
//             //     e.stopImmediatePropagation()

//             //     let _this = $(this);
//             //     let url   =_this.attr('rq-url');
//             //     let id    =_this.attr('data-id');
//             //     let modal_id = '#modal_add_department';
//             //     let form = $('#form_add_department');

//             //     let formData = new FormData;

//             //     formData.append('id',id);
//             //     _request.post('/hris/admin/settings/file_maintenance/department/info',formData)
//             //     .then((res) => {
//             //         let payload = JSON.parse(window.atob(res.payload));
//             //         form.find('input[name="name"]').val(payload.name);
//             //         form.find('input[name="code"]').val(payload.code);
//             //         form.find('select[name="is_active"]').val(payload.is_active).trigger('change');
//             //         form.find('textarea[name="description"]').val(payload.description);
//             //         $(modal_id).find('button.submit').attr('data-id',id);
//             //     })
//             //     .catch((error) => {
//             //         console.log(error);
//             //         Alert.alert('error', "Something went wrong. Try again later", false);
//             //     })
//             //     .finally((error) => {
//             //         modal_state(modal_id,'show');
//             //     });

//             // })

//             // $(`#${_table}_table`).on('click','.delete',function(e){
//             //     e.preventDefault()
//             //     e.stopImmediatePropagation()

//             //     let _this = $(this);
//             //     let id    =_this.attr('data-id');
//             //     let formData = new FormData;

//             //     Alert.confirm('question','Delete this record ?',{
//             //         onConfirm: function() {
//             //             formData.append('id',id);
//             //             _request.post('/hris/admin/settings/file_maintenance/department/delete',formData)
//             //             .then((res) => {
//             //                 Alert.toast(res.status,res.message);
//             //                 if(res.payload ==0){
//             //                     initTable();
//             //                 }else{
//             //                     $(`#${_table}_table`).DataTable().ajax.reload(null, false);
//             //                 }
//             //             })
//             //             .catch((error) => {
//             //                 Alert.alert('error', "Something went wrong. Try again later", false);
//             //             })
//             //             .finally((error) => {

//             //             });
//             //         }
//             //     });


//             // })

//         })

//     }

//     return {
//         init: function () {
//             initTable();
//         }
//     }

// }
