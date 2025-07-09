
"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";


export var dtInventoryConsumable = function (table,param='',is_consumable) {

    const _page = '.page-inventory-list';
    const _card = $('.card-consumable-list');
    const _url = 'inventory/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt',
            {
                filter_status:_card.find('select[name="filter_status"]').val(),
                filter_location:_card.find('select[name="filter_location"]').val(),
                filter_year:_card.find('select[name="filter_year"]').val(),
                id:param,
                is_consumable:is_consumable,
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
                },
                {
                    data: "location", name: "location", title: "Location",
                    className:'',
                    sortable:false,
                    searchable:false,
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

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "received_date", name: "received_date", title: "Received At",
                    // sortable:false,
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
                                <a href="/inventory-details/${data}" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                                    data-bs-toggle="tooltip" title="View Details">
                                    <i class="ki-duotone ki-pencil fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </a>

                                <button class="btn btn-icon btn-icon btn-sm me-1 hover-elevate-up
                                ${row.status!=2 && row.status!=4 && row.status!=3 && row.status!=0 ?`remove btn-light-danger`:`btn-secondary`}
                                    " data-id="${data}"
                                    data-bs-toggle="tooltip" title="${row.status!=2 && row.status!=4 && row.status!=3 && row.status!=0 ?
                                        `Remove item from inventory`
                                        :`Item is currently issued and cannot be remove from the inventory list`
                                    }">
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

            _card.on('change',`select.sfilter`,function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

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

        })
    }

    return {
        init: function () {
            initTable();
        }
    }

}

