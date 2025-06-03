"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";


export var dtIssuedItems = function (table,param=false) {

    const _page = '.page-material-issuance-details';
    const _card = $('.card-mrs-issued-items');
    const _url = 'material-issuance-details/';
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
                {
                    data: "count",
                    name: "count",
                    title: "#",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "name", name: "name", title: "Item",
                    sortable:false,
                    render: function (data, type, row) {
                        if(row.serial_number){
                            return `
                            <div class="d-flex flex-column">
                                <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                    ${data}
                                </a>
                                <span>${row.serial_number}</span>
                            </div>
                            `;
                        }
                        return `
                        <div class="d-flex flex-column">
                            <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                ${data}
                            </a>
                        </div>
                        `;
                    }
                },
                {
                    data: "description", name: "description", title: "Description",
                    className:'',
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
                    data: "serial_number", name: "serial_number", title: "Serial Number",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "allowed_edit", name: "allowed_edit", title: "Editable",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "status", name: "status", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Issued"],
                            2: ["danger", "Returned"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
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
                    data: "removed_at", name: "removed_at", title: "Removed At",
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
                                row.allowed_edit ?
                                `<div class="d-flex justify-content-center flex-shrink-0">
                                    <a href="javascript:;" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up view d-none" data-id="${data}"
                                    data-bs-toggle="tooltip" title="View Details">
                                        <i class="ki-duotone ki-pencil fs-2x">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up remove" data-id="${data}"
                                    data-bs-toggle="tooltip" title="Remove item from material issuance">
                                        <i class="ki-duotone ki-cross fs-2x">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                </div>`
                                :``
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

                dtAvailableItems('mrs-available-items',$(this).attr('data-id')).init();
                modal_state('#modal-mrs-available-items','show');
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

                    $('input[name="returned_at"]')[0]._flatpickr.setDate(payload.returned_at, true);
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

                Alert.input('info','Remove this item on material issuance ?',{
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

export var dtIssuedTo = function (table,param=false) {

    const _page = '.page-material-issuance-details';
    const _card = $('.card-issued-to');
    const _url = 'material-issuance-details/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt-issued-to',
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
                },
                {
                    data: "name", name: "name", title: "Employee",
                    sortable:false,
                    className:'',
                    render(data,type,row)
                    {
                        let status = {
                            1: ["success", "Active on material issuance"],
                            2: ["danger", "Removed on material issuance"],
                            3: ["secondary", "Error"],
                        };
                        return `<div class="position-relative ps-6 pe-3 py-2">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-${status[row.status]? status[row.status][0] :status['3'][0]}"></div>
                                    <span class="mb-1 text-dark text-hover-primary fw-bolder">
                                        ${data} <span class="badge badge-outline badge-primary ">${row.emp_no??'No Employee Number'}</span>
                                    </span>
                                    <div class="fs-7 text-muted fw-bold">${status[row.status]? status[row.status][1] :status['3'][1]}</div>

                                </div>`;
                    }
                },
                {
                    data: "emp_no", name: "emp_no", title: "Employee No.",
                    sortable:false,
                    visible:false
                },
                {
                    data: "department", name: "department", title: "Department",
                    sortable:false,
                    searchable:false,
                    render(data,type,row){
                        return`<div class="d-flex flex-column">
                                    <span>${data}</span>
                                    ${row.position ?`<span class="text-muted">${row.position}</span>`: ``}
                                </div>`;
                    }
                },
                {
                    data: "position", name: "position", title: "Position",
                    sortable:false,
                    searchable:false,
                    className:'text-center',
                    visible:false,
                },
                {
                    data: "status", name: "status", title: "Status",
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
                    data: "issued_at", name: "issued_at", title: "Started At",
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
                    data: "removed_at", name: "removed_at", title: "Removed At",
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
                            row.status ==2 ? ``
                            :
                            `<a href="javascript:;" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up view d-none" data-id="${data}"
                             data-bs-toggle="tooltip" title="View accountable details">
                               <i class="ki-duotone ki-pencil fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                            <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up remove d-none" data-id="${data}"
                             data-bs-toggle="tooltip" title="Remove employee on accountability">
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

            // _card.on('click','.add-personnel',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()

            //     dtAvailablePersonnel('available-personnel',$(this).attr('data-id')).init();
            //     modal_state('#modal-available-personnel','show');
            // })

            $(`#${table}_table`).on('click','.view',function(e){
                let _this = $(this);
                let id    =_this.attr('data-id');
                let modal_id = '#modal-edit-other-details';
                let form = $('#form-edit-other-details');

                let formData = new FormData;
                formData.append('encrypted_id',id);

                _request.post('/'+_url+'info-issued-to',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));

                    $('input[name="issued_at"]')[0]._flatpickr.setDate(payload.issued_at, true);
                    $('input[name="issued_at"]').prev('label').text('Started At');

                    $('input[name="returned_at"]')[0]._flatpickr.setDate(payload.returned_at, true);
                    $('input[name="returned_at"]').prev('label').text('Removed At');

                    $('textarea[name="remarks"]').val(payload.remarks);
                    $('select[name="status"]').val(payload.status).trigger('change');

                    let html_other_details = `
                        <div class="position-relative ps-6 pe-3 py-2">
                            <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-success"></div>
                            <span class="mb-1 text-dark text-hover-primary fw-bolder">
                                ${payload.name} <span class="badge badge-outline badge-primary ">${payload.emp_no}</span>
                            </span>
                            <div class="fs-7 text-muted fw-bold">Active on accountability</div>
                        </div>
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
                    $(modal_id).find('.modal-title').text('Accountable Details');
                    form.attr('action','issued-to');
                    modal_state(modal_id,'show');
                });
            })

            $(`#${table}_table`).on('click','.remove',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.input('info','Remove this employee on accountability ?',{
                    isRequired: true,
                    inputPlaceholder: "Put your reason",
                    onConfirm: function(remarks) {
                        formData.append('encrypted_id',id);
                        formData.append('remarks',remarks);
                        formData.append('status',2);
                        _request.post('/'+_url+'remove-issued-to',formData)
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

    const _modal = $('#modal-mrs-available-items .modal-body');
    const _url = 'material-issuance-details/';
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
                },
                {
                    data: "name", name: "name", title: "Item",
                    sortable:false,
                    render: function (data, type, row) {
                        if(row.serial_number){
                            return `
                            <div class="d-flex flex-column">
                                <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                    ${data}
                                </a>
                                <span>${row.serial_number}</span>
                            </div>
                            `;
                        }
                        return `
                        <div class="d-flex flex-column">
                            <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                ${data}
                            </a>
                        </div>
                        `;
                    }
                },
                {
                    data: "description", name: "description", title: "Description",
                    className:'',
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
                    data: "serial_number", name: "serial_number", title: "Serial Number",
                    sortable:false,
                    searchable:false,
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
                                    data-bs-toggle="tooltip" title="Add item to material issuance">
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

                Alert.input('info','Add this item on material issuance ?',{
                    isRequired: true,
                    inputPlaceholder: "Put your reason for adding this item",
                    onConfirm: function(remarks) {
                        formData.append('encrypted_id',id);
                        formData.append('material_issuance_id',param);
                        formData.append('remarks',remarks);
                        formData.append('status',1);
                        _request.post('/'+_url+'update-material-issuance-item',formData)
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

return {
    init: function () {
        initTable();
    }
}

}
