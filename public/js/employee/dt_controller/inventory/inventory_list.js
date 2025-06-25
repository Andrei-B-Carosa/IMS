
"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";


export var dtInventoryList = function (table,param='') {

    const _page = '.page-inventory-list';
    const _card = $('.card-inventory-list');
    const _url = 'inventory/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt',
            {
                filter_item:$('select[name="filter_item"]').val(),
                filter_status:$('select[name="filter_status"]').val(),
                id:param,
            },
            [
                // {
                //     data: "count",
                //     name: "count",
                //     title: " ",
                //     responsivePriority: -3,
                //     searchable:false,
                //     className:'text-muted',
                // },
                {
                    data: "tag_number", name: "tag_number", title: "Tag Number",
                    className:'text-muted',
                    sortable:false,
                },
                {
                    data: "name", name: "name", title: "Item",
                    sortable:false,
                    className:'text-start',
                    render: function (data, type, row) {
                        if(row.serial_number){
                            return `
                            <div class="d-flex flex-column">
                                <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                    ${data}
                                </a>
                                <span class="text-muted">S/N : ${row.serial_number}</span>
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
                    data: "item_name", name: "item_name", title: "Item Type",
                    className:'',
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "enable_quick_actions", name: "enable_quick_actions", title: "Enable Quick Action",
                    className:'',
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "description", name: "description", title: "Description",
                    className:'',
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "location", name: "location", title: "Location",
                    className:'',
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "serial_number", name: "serial_number", title: "Serial Number",
                    sortable:false,
                    className:'text-center',
                    visible:false,
                },
                {
                    data: "status", name: "status", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            0: ["warning", "Disposed"],
                            1: ["info", "Available"],
                            2: ["success", "Issued"],
                            3: ["secondary", "Temporary Issued"],
                            4: ["danger", "Under Repair"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                // {
                //     data: "received_date", name: "received_date", title: "Received At",
                //     sortable:false,
                //     searchable:false,
                //     className:'text-muted text-start min-w-100px',
                //     render: function (data, type, row) {
                //         if(!data){
                //             return '--';
                //         }
                //         return data;
                //     },
                // },
                // {
                //     data: "received_by", name: "received_by", title: "Received By",
                //     sortable:false,
                //     searchable:false,
                //     className:'text-start text-muted',
                //     render: function (data, type, row) {
                //         if(!data){
                //             return '--';
                //         }
                //         return data;
                //     },
                // },
                {
                    data: "remarks", name: "remarks", title: "Remarks",
                    sortable:false,
                    searchable:false,
                    className:'text-start text-muted',
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
                    className: "text-start",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return `${
                            row.is_deleted == 1 ? ``
                            :
                            `<div class="d-flex">
                            ${row.enable_quick_actions ? `
                                <button class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up q-action"
                                        data-kt-menu-trigger="click"
                                        data-kt-menu-placement="bottom-end"
                                        data-kt-menu-overflow="true" data-bs-toggle="tooltip" title="Quick Actions">
                                        <i class="ki-duotone ki-pencil fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </button>

                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">
                                                Quick Actions
                                            </div>
                                        </div>
                                        <div class="separator mb-3 opacity-75"></div>
                                        <div class="menu-item px-3">
                                            <a href="/inventory-details/${data}" class="menu-link px-3">
                                                View Details
                                            </a>
                                        </div>
                                        ${row.status!=4 && row.status!=0 ?`
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" class="menu-link px-3 request-repair" data-id="${data}">
                                                    Request Repair
                                                </a>
                                            </div>`:``}
                                        ${row.status==4 && row.status!=0? `<div class="menu-item px-3">
                                            <a href="javascript:;" class="menu-link px-3 update-repair" data-id="${data}">
                                                Update Repair
                                            </a>
                                        </div>`:``}
                                        <div class="separator mt-3 opacity-75"></div>
                                        <div class="menu-item px-3">
                                            <div class="menu-content px-3 py-3">
                                                <a class="btn btn-primary btn-sm px-4 generate-report" href="#">
                                                    Generate Reports
                                                </a>
                                            </div>
                                        </div>
                                    </div> `
                                    :
                                    `
                                    <a href="/inventory-details/${data}" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                                        data-bs-toggle="tooltip" title="View Details">
                                        <i class="ki-duotone ki-pencil fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </a>
                                    `
                                    }

                                <button class="btn btn-icon btn-icon btn-light-dark btn-sm me-1 hover-elevate-up download-qr" data-id="${data}"
                                    data-bs-toggle="tooltip" title="Download QR Code">
                                    <i class="bi bi-qr-code fs-2 ">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </button>
                                <button class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up remove" data-id="${data}"
                                data-bs-toggle="tooltip" title="Remove item from inventory">
                                    <i class="ki-duotone ki-trash fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </button>
                            </div>`}
                        `;
                    },
                }
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

            _card.on('click','button.filter',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            $(`#${table}_table`).on('click','.remove',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.input('info','Remove this item on inventory ?',{
                    isRequired: true,
                    inputPlaceholder: "Put your reason",
                    onConfirm: function(remarks) {
                        formData.append('encrypted_id',id);
                        formData.append('remarks',remarks);
                        _request.post('/'+_url+'delete',formData)
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

            $(`#${table}_table`).on('click','.download-qr',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;
                formData.append('encrypted_id',id);

                _request.postBlob('/' + _url + 'download-qr', formData, true)
                .then(response => {
                    const blob = response.data;
                    const disposition = response.headers['content-disposition'];

                    let filename = 'qr_code.png'; // fallback
                    if (disposition) {
                        const match = disposition.match(/filename\*?=(?:UTF-8'')?"?([^";\n]*)"?/);
                        if (match && match[1]) {
                            filename = decodeURIComponent(match[1].replace(/['"]/g, ''));
                        }
                    }

                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                });
            })

            $(document).on('click','.request-repair',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');

                let modal_id = '#modal-request-repair';
                let form = $('#form-request-repair');
                modal_state(modal_id,'show');
                $(modal_id).find('button.submit').attr('data-inventory-id',id);
            })

            $(document).on('click','.update-repair',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let modal_id = '#modal-request-repair';
                let form = $('#form-request-repair');

                let formData = new FormData;

                formData.append('id',id);
                _request.post('/inventory/repair-info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));

                    $('input[name="start_at"]')[0]._flatpickr.setDate(payload.start_at, true);
                    $('input[name="end_at"]')[0]._flatpickr.setDate(payload.end_at, true);

                    form.find('select[name="status"]').val(payload.status).trigger('change');
                    form.find('select[name="repair_type"]').val(payload.repair_type).trigger('change');

                    form.find('textarea[name="description"]').val(payload.description);
                    $(modal_id).find('button.submit').attr('data-id',payload.encrypted_id);
                    $(modal_id).find('button.submit').attr('data-inventory-id',id);

                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    modal_state(modal_id,'show');
                });

            })

            $(`#${table}_table`).on('click','.generate-report',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;
                formData.append('encrypted_id',id);

                _request.postBlob('/' + _url + 'generate-report', formData, true)
                .then(response => {
                    const blob = response.data;
                    const disposition = response.headers['content-disposition'];

                    let filename = 'qr_code.png'; // fallback
                    if (disposition) {
                        const match = disposition.match(/filename\*?=(?:UTF-8'')?"?([^";\n]*)"?/);
                        if (match && match[1]) {
                            filename = decodeURIComponent(match[1].replace(/['"]/g, ''));
                        }
                    }

                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
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

