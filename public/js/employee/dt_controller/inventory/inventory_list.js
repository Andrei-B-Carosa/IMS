
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
                    data: "description", name: "description", title: "Description",
                    className:'',
                    sortable:false,
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
                            1: ["info", "Available"],
                            2: ["success", "Issued"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "received_date", name: "received_date", title: "Received At",
                    sortable:false,
                    searchable:false,
                    className:'text-muted text-start min-w-100px',
                    render: function (data, type, row) {
                        if(!data){
                            return '--';
                        }
                        return data;
                    },
                },
                {
                    data: "received_by", name: "received_by", title: "Received By",
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
                                <a href="/inventory-details/${data}" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up view" data-id="${data}"
                                    data-bs-toggle="tooltip" title="View item details">
                                    <i class="ki-duotone ki-pencil fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </a>
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
                                data-bs-toggle="tooltip" title="Remove item on inventory">
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
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = "qr_code.png";
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
