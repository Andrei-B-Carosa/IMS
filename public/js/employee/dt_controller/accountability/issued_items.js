"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";


export var dtIssuedItems = function (table,param=false) {

    const _page = '.page-accountability-details';
    const _card = $('.card-issued-items');
    const _url = 'accountability-details/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt-issued-items',
            {
                filter_status:'all',
                id:param,
            },
            [
                // {
                //     data: "count",
                //     name: "count",
                //     title: "#",
                //     responsivePriority: -3,
                //     searchable:false,
                // },
                {
                    data: "tag_number",
                    name: "tag_number",
                    title: "Tag Number",
                    sortable:false,
                    className:'text-muted',
                },
                // {
                //     data: "name", name: "name", title: "Item",
                //     sortable:false,
                //     render: function (data, type, row) {
                //         if(row.serial_number){
                //             return `
                //             <div class="d-flex flex-column">
                //                 <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                //                     ${data}
                //                 </a>
                //                 ${row.serial_number?`<span class="text-muted">S/N: ${row.serial_number}</span>`:``}
                //             </div>
                //             `;
                //         }
                //         return `
                //         <div class="d-flex flex-column">
                //             <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                //                 ${data}
                //             </a>
                //         </div>
                //         `;
                //     }
                // },
                {
                    data: "name", name: "name", title: "Item",
                    sortable:false,
                    className:'text-start',
                    render: function (data, type, row) {
                        if(row.item_type_id == 1 || row.item_type_id == 8){
                            return `
                            <div class="d-flex flex-column">
                                <a href="javascript:;" class="text-gray-800 text-hover-primary mb-2 fw-bold">
                                    ${data}
                                </a>
                                <span class="text-muted fw-bold">${row.description}</span>
                                ${row.serial_number ? `<span class="text-muted fw-bold">S/N : ${row.serial_number}</span>`:``}
                            </div>
                            `;
                        }
                        return `
                        <div class="d-flex flex-column">
                            <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                ${data}
                            </a>
                            ${row.serial_number ? `<span class="text-muted fw-bold">S/N : ${row.serial_number}</span>`:``}
                        </div>
                        `;
                    }
                },
                {
                    data: "description", name: "description", title: "Description",
                    className:'',
                    sortable:false,
                    visible:false,
                },
                {
                    data: "item_type_id", name: "item_type_id", title: "Item Type",
                    className:'',
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "serial_number", name: "serial_number", title: "Serial Number",
                    sortable:false,
                    visible:false,
                },
                {
                    data: "status", name: "status", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Issued"],
                            2: ["info", "Returned"],
                            3: ["secondary", "Temporary Issued"],
                            4: ["danger", "Under Repair"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "accountability_status", name: "accountability_status", title: "Accountability Status",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "issued_at", name: "issued_at", title: "Issued At",
                    sortable:false,
                    searchable:false,
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }

                        return data;
                    }
                },
                {
                    data: "removed_at", name: "removed_at", title: "Returned At",
                    sortable:false,
                    searchable:false,
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }

                        return data;
                    }
                },
                {
                    data: "remarks", name: "remarks", title: "Remarks",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        if(!data){
                            return '--';
                        }
                        return data;
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
                        return `${
                                row.status ==2 || row.status==3 || row.status==4 || row.accountability_status == 2 ?
                                ``
                                :`<div class="d-flex justify-content-center flex-shrink-0">
                                    <a href="javascript:;" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up view" data-id="${data}"
                                    data-bs-toggle="tooltip" title="View Details">
                                        <i class="ki-duotone ki-pencil fs-2x">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up remove" data-id="${data}"
                                    data-bs-toggle="tooltip" title="Remove item from accountability">
                                        <i class="ki-duotone ki-cross fs-2x">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                </div>`
                            }
                        `;
                    },
                },
            ],
            null,
        );

        $(`#${table}_table`).ready(function() {

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

            _card.on('click','.add-item',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                dtAvailableItems('available-items',$(this).attr('data-id')).init();
                modal_state('#modal-available-items','show');
            })

            $(`#${table}_table`).on('click','.view',function(e){
                let _this = $(this);
                let id    =_this.attr('data-id');
                let modal_id = '#modal-edit-other-details';
                let form = $('#form-edit-other-details');

                let formData = new FormData;
                formData.append('encrypted_id',id);

                _request.post('/'+_url+'info-issued-items',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));

                    $('input[name="issued_at"]')[0]._flatpickr.setDate(payload.issued_at, true);
                    $('input[name="issued_at"]').prev('label').text('Issued At');
                    console.log(payload.removed_at)
                    // if(payload.removed_at){
                    //     $('input[name="returned_at"]')[0]._flatpickr.setDate(payload.removed_at, true);
                    // }
                    $('input[name="returned_at"]').prev('label').text('Returned At');

                    $('textarea[name="remarks"]').val(payload.remarks);
                    $('select[name="status"]').val(payload.status).trigger('change');

                    let html_other_details = `
                        <div class="fw-bold fs-5">Item: </div>
                        <div class="mb-7 text-gray-800 fs-6">
                            ${payload.name}
                        </div>

                        <div class="fw-bold fs-5">Description:</div>
                        <div class="mb-7 text-gray-800 fs-6">
                            ${payload.description}
                        </div>

                        ${payload.serial_number?`
                            <div class="fw-bold fs-5">Serial Number: </div>
                            <div class="text-gray-800 fs-6 serial-number">
                                ${payload.serial_number}
                            </div> `:``}
                    `;

                    $(modal_id).find('.other-details').empty().html(html_other_details);
                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    $(modal_id).find('.modal-title').text('');
                    $(modal_id).find('button.submit').attr('data-id',id);
                    $(modal_id).find('.modal-title').text('Issued Item Details');
                    form.attr('action','issued-items');
                    modal_state(modal_id,'show');
                });
            })

            $(`#${table}_table`).on('click','.remove',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.input('info','Remove this item on accountability ?',{
                    isRequired: true,
                    inputPlaceholder: "Put your reason for removing this item",
                    onConfirm: function(remarks) {
                        formData.append('encrypted_id',id);
                        formData.append('remarks',remarks);
                        formData.append('status',2);
                        _request.post('/'+_url+'remove-issued-item',formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            initTable();
                        })
                        .catch((error) => {
                            Alert.alert('error', "Something went wrong. Try again later", false);
                        })
                        .finally((error) => {
                        });
                    }
                });
            })

        })

    }

    return {
        init: function () {
            initTable();
        }
    }

}


export var dtAvailableItems = function (table,param=false) {

    const _modal = $('#modal-available-items .modal-body');
    const _url = 'accountability-details/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt-available-items',
            {
                filter_status:'all',
                id:param,
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: "#",
                    responsivePriority: -3,
                    searchable:false,
                    className:'text-muted',
                },
                {
                    data: "tag_number", name: "tag_number", title: "Tag Number",
                    className:'',
                    sortable:false,
                    className:'text-muted',
                },
                // {
                //     data: "name", name: "name", title: "Item",
                //     sortable:false,
                //     render: function (data, type, row) {
                //         if(row.serial_number){
                //             return `
                //             <div class="d-flex flex-column">
                //                 <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                //                     ${data}
                //                 </a>
                //                 <span>${row.serial_number}</span>
                //             </div>
                //             `;
                //         }
                //         return `
                //         <div class="d-flex flex-column">
                //             <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                //                 ${data}
                //             </a>
                //         </div>
                //         `;
                //     }
                // },
                {
                    data: "name", name: "name", title: "Item",
                    sortable:false,
                    className:'text-start',
                    render: function (data, type, row) {
                        if(row.item_type_id == 1 || row.item_type_id == 8){
                            return `
                            <div class="d-flex flex-column">
                                <a href="javascript:;" class="text-gray-800 text-hover-primary mb-2 fw-bold">
                                    ${data}
                                </a>
                                <span class="text-muted fw-bold">${row.description}</span>
                                ${row.serial_number ? `<span class="text-muted fw-bold">S/N : ${row.serial_number}</span>`:``}
                            </div>
                            `;
                        }
                        return `
                        <div class="d-flex flex-column">
                            <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                ${data}
                            </a>
                            ${row.serial_number ? `<span class="text-muted fw-bold">S/N : ${row.serial_number}</span>`:``}
                        </div>
                        `;
                    }
                },
                {
                    data: "item_type_id", name: "item_type_id", title: "Item Type",
                    className:'',
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "description", name: "description", title: "Description",
                    className:'',
                    sortable:false,
                    // searchable:false,
                    visible:false,
                    // render: function (data, type, row) {
                    //     if(!data){
                    //         return '--';
                    //     }
                    //     return data;
                    // },
                },
                {
                    data: "serial_number", name: "serial_number", title: "Serial Number",
                    sortable:false,
                    // searchable:false,
                    visible:false,
                },
                {
                    data: "status", name: "status", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Available"],
                            2: ["primary", "Issued"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "remarks", name: "remarks", title: "Remarks",
                    sortable:false,
                    searchable:false,
                    className:'text-muted',
                    render: function (data, type, row) {
                        if(!data){
                            return '--';
                        }
                        return data;
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
                        return `${
                                row.status ==2 || row.status==3 || row.status==4 ?
                                `
                                `
                                :`<div class="d-flex justify-content-center flex-shrink-0">
                                    <a href="javascript:;" class="btn btn-icon btn-icon btn-light-success btn-sm me-1 hover-elevate-up add-item" data-id="${data}"
                                    data-bs-toggle="tooltip" title="Add item to accountability">
                                        <i class="ki-duotone ki-check fs-2x"></i>
                                    </a>
                                </div>`
                            }
                        `;
                    },
                },
            ],
            null,
        );

        $(`#${table}_table`).ready(function() {

            _modal.off();

            _modal.on('change','select.filter_table',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

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

            $(`#${table}_table`).on('click','.add-item',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.input('info','Add this item on accountability ?',{
                    isRequired: true,
                    inputPlaceholder: "Put your reason for adding this item",
                    onConfirm: function(remarks) {
                        formData.append('encrypted_id',id);
                        formData.append('accountability_id',param);
                        formData.append('remarks',remarks);
                        formData.append('status',1);
                        _request.post('/'+_url+'add-accountability-item',formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            if(res.status =='success'){
                                initTable();
                                dtIssuedItems('issued-item',param).init();
                            }
                        })
                        .catch((error) => {
                            Alert.alert('error', "Something went wrong. Try again later", false);
                        })
                        .finally((error) => {
                        });
                    }
                });
            })

        })

    }

    return {
        init: function () {
            initTable();
        }
    }

return {
    init: function () {
        initTable();
    }
}

}
