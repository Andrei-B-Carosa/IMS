"use strict";

import {Alert} from "../../../../global/alert.js";
import {RequestHandler} from "../../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js";
import {trigger_select} from "../../../../global/select.js";
import { DataTableHelper } from "../../../../global/datatable.js";


export var dtItemType = function (table,param=false) {

    const _page = $('.page-file-maintenance-settings');
    const _tab = $(`.${table}`);

    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'file-maintenance/'+table+'/dt',
            {
                filter_status:'all',
                id:'',
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
                        return `
                        <div class="d-flex flex-column">
                            <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                ${data??'--'}
                            </a>
                            ${row.item_number ? `<span class="text-muted"># ${row.item_number}</span>` : ``}
                        </div>
                        `;
                    }
                },
                {
                    data: "item_number", name: "item_number", title: "Item Number",
                    sortable:false,
                    visible:false,
                    searchable:false,
                },
                {
                    data: "description", name: "description", title: "Description",
                    sortable:false,
                    className:'text-muted',
                    render:function(data, type, row){
                        return `${data??'--'}`;
                    }
                },
                {
                    data: "item_code", name: "item_code", title: "Item Code",
                    sortable:false,
                    className:'text-muted',
                    render:function(data, type, row){
                        return `${data??'--'}`;
                    }
                },
                {
                    data: "display_to", name: "display_to", title: "Display To",
                    sortable:false,
                    className:'text-muted',
                    render:function(data, type, row){
                        let status = {
                            1: 'Accountability',
                            2: 'Material Issuance',
                            3: 'Both',

                        };
                        return `${status[data]}`;
                    }
                },
                {
                    data: "last_update_at", name: "last_update_at", title: "Last Update At",
                    className:'',
                    sortable:false,
                    searchable:false,
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
                    data: "last_updated_by", name: "last_updated_by", title: "Last Updated By",
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
                                    <span class="text-muted">${row.last_update_at}</span>
                                </div>
                            </div>
                        `
                    }
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
                            <button class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up view-details" data-id="${data}"
                            data-bs-toggle="tooltip" title="View Details">
                                <i class="ki-duotone ki-pencil fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </button>
                            <button class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
                            data-bs-toggle="tooltip" title="Delete this record">
                                <i class="ki-duotone ki-cross fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </button>
                        </div>`;
                    },
                },
            ],
            null,
        );

        $(`#${table}_table`).ready(function() {

            _tab.off();

            _tab.on('keyup','.search',function(e){
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

            $(`#${table}_table`).on('click','.view-details',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url   =_this.attr('rq-url');
                let id    =_this.attr('data-id');
                let modal_id = '#modal-add-item-type';
                let form = $('#form-add-item-type');

                let formData = new FormData;

                formData.append('id',id);
                _request.post('/file-maintenance/item-type/info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));
                    form.find('input[name="name"]').val(payload.name);
                    form.find('input[name="item_code"]').val(payload.item_code);
                    form.find('input[name="item_number"]').val(payload.item_number);
                    form.find('select[name="is_active"]').val(payload.is_active).trigger('change');
                    form.find('select[name="display_to"]').val(payload.display_to).trigger('change');
                    form.find('textarea[name="description"]').val(payload.description);
                    $(modal_id).find('button.submit').attr('data-id',id);
                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    modal_state(modal_id,'show');
                });

            })

            $(`#${table}_table`).on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.confirm('question','Remove this item type ?',{
                    onConfirm: function() {
                        formData.append('encrypted_id',id);
                        _request.post('/file-maintenance/'+table+'/delete',formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            if(res.payload ==0){
                                initTable();
                            }else{
                                $(`#${table}_table`).DataTable().ajax.reload(null, false);
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

}
