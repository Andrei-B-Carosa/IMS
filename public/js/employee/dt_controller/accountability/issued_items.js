"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";
import { fvOtherItemDetails, fvSystemUnitDetails } from "../../fv_controller/accountability/details.js";


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
                    data: "status", name: "status", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Issued"],
                            2: ["primary", "Returned"],
                            3: ['info',"Temporary"],
                            4: ['danger',"Under Repair"]
                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "last_updated_by", name: "last_updated_by", title: "Last Updated By",
                    sortable:false,
                    // searchable:false,
                    className:'',
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }
                        return `<div class="d-flex align-items-center">
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 text-hover-primary mb-0">
                                        ${data}
                                    </span>
                                    <span class="text-muted fs-7">${row.last_update_at}</span>
                                </div>
                            </div>
                        `
                    }
                },
                {
                    data: "type", name: "type", title: "Item Type",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "last_update_at", name: "last_update_at", title: "Last Updated At",
                    sortable:false,
                    searchable:false,
                    visible:false,
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
                                    <a href="javascript:;" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up ${row.type==1 ? 'view-system-unit':(row.type==8?'view-laptop':'view')}" data-id="${data}"
                                    data-bs-toggle="tooltip" title="View Details">
                                        <i class="ki-duotone ki-pencil fs-2x">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
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

            $(`#${table}_table`).on('click','.view-system-unit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let modal_id = '#modal-edit-system-unit';
                let form = $('#form-edit-system-unit');

                // modal_state(modal_id,'show');

                let formData = new FormData;
                formData.append('id',id);
                formData.append('modal_type',1);

                _request.post('/'+_url+'info-issued-items',formData)
                .then((res) => {
                    let payload = window.atob(res.payload);
                    $(modal_id).empty();
                    $(_page).append(payload);
                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    modal_state(modal_id,'show');
                    fvSystemUnitDetails();
                });

            })

            $(`#${table}_table`).on('click','.view',function(e){
                let _this = $(this);
                let id    =_this.attr('data-id');
                let modal_id = '#modal-edit-other-item';
                let form = $('#form-edit-other-item');

                let formData = new FormData;
                formData.append('id',id);
                formData.append('modal_type',0);

                _request.post('/'+_url+'info-issued-items',formData)
                .then((res) => {
                    let payload = window.atob(res.payload);
                    $(modal_id).empty();
                    $(_page).append(payload);
                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    modal_state(modal_id,'show');
                    fvOtherItemDetails();
                });
            })


            $(`#${table}_table`).on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.confirm('question','Remove this item on accountability ?',{
                    onConfirm: function() {
                        formData.append('encrypted_id',id);
                        _request.post('/'+_url+'delete-issued-item',formData)
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
