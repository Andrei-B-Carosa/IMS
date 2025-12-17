"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";

export var dtItemLogs = function (table,param='') {

    const _page = '.page-inventory-list';
    const _card = $('.card-item-logs');
    const _url = 'inventory-details/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt-item-logs',
            {
                filter_item:$('select[name="filter_item"]').val(),
                filter_status:$('select[name="filter_status"]').val(),
                id:param,
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: " ",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "emp_fullname", name: "emp_fullname", title: "Employee",
                    sortable:false,
                    searchable:false,
                    className:'',
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }
                        return `<div class="d-flex align-items-center">

                                <div class="d-flex flex-column">
                                    <span class="mb-1">
                                        ${data}
                                    </span>
                                </div>
                            </div>
                        `
                    }
                },
                {
                    data: "activity_table", name: "activity_table", title: "Table Activity",
                    sortable:false,
                    searchable:false,
                    className:'text-muted',
                    // render: function (data, type, row) {
                    //     let status = {
                    //         1: ["info", "INSERT"],
                    //         2: ["success", "UPDATE"],

                    //     };
                    //     return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    // },
                },
                {
                    data: "activity_type", name: "activity_type", title: "Activity Type",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["info", "INSERT"],
                            2: ["success", "UPDATE"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "activity_log", name: "activity_log", title: "Activity log",
                    className:'',
                    sortable:false,
                },
                {
                    data: "last_update_at", name: "last_update_at", title: "Created At",
                    className:'',
                    sortable:false,
                },
            ],
            null,
        );

        $(`#${table}_table`).ready(function() {

            _card.off();

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

        })
    }

    return {
        init: function () {
            initTable();
        }
    }

}

export var dtAccountabilityHistory = function (table,param='') {

    const _page = '.page-inventory-details';
    // const _card = $('.card-item-logs');
    const _url = 'inventory-details/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt-'+table,
            {
                // filter_item:$('select[name="filter_item"]').val(),
                // filter_status:$('select[name="filter_status"]').val(),
                id:param,
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: " ",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "form_no", name: "form_no", title: "Form No.",
                    className:'',
                    render: function (data, type, row) {
                        return `<span class=" fw-bold">${data}</span>`;
                    },
                },
                {
                    data: "issued_to", name: "issued_to", title: "Issued To",
                    className:'min-w-125px',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="">${data}</span>`;
                    },
                },
                {
                    data: "issued_by", name: "issued_by", title: "Issued By",
                    className:'',
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        return `<span class="">${data}</span>`;
                    },
                },
                {
                    data: "tag_number", name: "tag_number", title: "Tag Number",
                    className:'',
                    sortable:false,
                    visible:false,
                },
                {
                    data: "issued_at", name: "issued_at", title: "Issued At",
                    className:'min-w-125px',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="">${data}</span>`;
                    },
                },
                {
                    data: "returned_at", name: "returned_at", title: "Returned At",
                    className:'min-w-125px',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="">${data}</span>`;
                    },
                },
                {
                    data: "status", name: "status", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Active"],
                            2: ["secondary", "Inactive"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    className: "text-start",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return `<div class="d-flex">
                                    <a href="/accountability-details/${data}" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up q-action"
                                    data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end"
                                    data-kt-menu-overflow="true" data-bs-toggle="tooltip" title="View Accountability">
                                    <i class="ki-duotone ki-pencil fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </a>
                                </div>
                        `;
                    },
                }
            ],
            null,
        );

        // $(`#${table}_table`).ready(function() {

        //     _card.off();

        //     _card.on('keyup','.search',function(e){
        //         e.preventDefault()
        //         e.stopImmediatePropagation()
        //         let searchTerm = $(this).val();
        //         if (e.key === 'Enter' || e.keyCode === 13) {
        //             dataTableHelper.search(searchTerm);
        //         } else if (e.keyCode === 8 || e.key === 'Backspace') {
        //             setTimeout(() => {
        //                 let updatedSearchTerm = $(this).val();
        //                 if (updatedSearchTerm === '') {
        //                     dataTableHelper.search('');
        //                 }
        //             }, 0);
        //         }
        //     })

        // })
    }

    return {
        init: function () {
            initTable();
        }
    }

}

export var dtRepairHistory = function (table,param='') {

    const _page = '.page-inventory-details';
    // const _card = $('.card-item-logs');
    const _url = 'inventory-details/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt-'+table,
            {
                // filter_item:$('select[name="filter_item"]').val(),
                // filter_status:$('select[name="filter_status"]').val(),
                id:param,
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: " ",
                    responsivePriority: -3,
                    searchable:false,
                    className:'text-muted',
                },
                // {
                //     data: "tag_number", name: "tag_number", title: "Tag Number",
                //     className:'text-muted',
                //     sortable:false,
                //     visible:false,
                //     render: function (data, type, row) {
                //         return `<span class="text-muted fw-bold">${data}</span>`;
                //     },
                // },
                {
                    data: "item_inventory_id", name: "item_inventory_id", title: "Inventory ID",
                    className:'',
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                // {
                //     data: "name", name: "name", title: "Device",
                //     sortable:false,
                //     className:'min-w-125px',
                //     render: function (data, type, row) {
                //         if(row.item_type_id == 1 || row.item_type_id == 8){
                //             return `
                //             <div class="d-flex flex-column">
                //                 <a href="/inventory-details/${row.item_inventory_id}" class="text-gray-800 text-hover-primary mb-2 fw-bold">
                //                     ${data}
                //                 </a>
                //                 <span class="text-muted fw-bold">${row.description}</span>
                //                 ${row.serial_number ? `<span class="text-muted fw-bold">S/N : ${row.serial_number}</span>`:``}
                //                 ${row.tag_number ? `<span class="text-muted fw-bold">Tag # : ${row.tag_number}</span>`:``}
                //             </div>
                //             `;
                //         }
                //         return `
                //         <div class="d-flex flex-column">
                //             <a href="/inventory-details/${row.item_inventory_id}" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                //                 ${data}
                //             </a>
                //             ${row.serial_number ? `<span class="text-muted fw-bold">S/N : ${row.serial_number}</span>`:``}
                //             ${row.tag_number ? `<span class="text-muted fw-bold">Tag # : ${row.tag_number}</span>`:``}
                //         </div>
                //         `;
                //     }
                // },
                // {
                //     data: "serial_number", name: "serial_number", title: "Serial Number",
                //     className:'text-center',
                //     visible:false,
                //     sortable:false,
                // },
                // {
                //     data: "item_type_id", name: "item_type_id", title: "Item Type",
                //     className:'',
                //     sortable:false,
                //     searchable:false,
                //     visible:false,
                // },
                // {
                //     data: "description", name: "description", title: "Description",
                //     className:'',
                //     sortable:false,
                //     visible:false,
                // },
                {
                    data: "initial_diagnosis", name: "initial_diagnosis", title: "Initial Diagnosis",
                    className:'',
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "work_to_be_done", name: "work_to_be_done", title: "Action Taken",
                    className:'',
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "start_at", name: "start_at", title: "Start",
                    className:'min-w-100px',
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "end_at", name: "end_at", title: "End",
                    className:'min-w-100px',
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "status", name: "status", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["info", "In Progress"],
                            2: ["success", "Resolved"],
                            3: ["danger", "Not Repairable"],
                            4: ["warning", "Under Warranty"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "created_by_name", name: "created_by_name", title: "Repaired By",
                    sortable:false,
                    // className:'min-w-125px',
                    render: function (data, type, row) {
                        if(!data){  return '--';  }
                        return `
                        <span class="text-hover-primary">
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold">
                                    ${data}
                                </span>
                            </div>
                        </span>
                        `;
                    }
                },
                {
                    data: "last_accountable_to", name: "last_accountable_to", title: "Requested By",
                    sortable:false,
                    // className:'min-w-125px',
                    render: function (data, type, row) {
                        if(!data){  return '--';  }
                        return `
                        <span class="text-hover-primary">
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold">
                                    ${data}
                                </span>
                            </div>
                        </span>
                        `;
                    }
                },
                {
                    data: "is_editable", name: "is_editable", title: "Is Editable",
                    className:'',
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "is_submittable", name: "is_submittable", title: "Is Editable",
                    className:'',
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
            ],
            null,
        );

        // $(`#${table}_table`).ready(function() {

        //     _card.off();

        //     _card.on('keyup','.search',function(e){
        //         e.preventDefault()
        //         e.stopImmediatePropagation()
        //         let searchTerm = $(this).val();
        //         if (e.key === 'Enter' || e.keyCode === 13) {
        //             dataTableHelper.search(searchTerm);
        //         } else if (e.keyCode === 8 || e.key === 'Backspace') {
        //             setTimeout(() => {
        //                 let updatedSearchTerm = $(this).val();
        //                 if (updatedSearchTerm === '') {
        //                     dataTableHelper.search('');
        //                 }
        //             }, 0);
        //         }
        //     })

        // })
    }

    return {
        init: function () {
            initTable();
        }
    }

}
