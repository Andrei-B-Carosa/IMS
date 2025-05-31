"use strict";

import {Alert} from "../../../../global/alert.js";
import {RequestHandler} from "../../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js";
import {trigger_select} from "../../../../global/select.js";
import { DataTableHelper } from "../../../../global/datatable.js";


export var dtItems = function (table,param=false) {

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
                        </div>
                        `;
                    }
                },
                // {
                //     data: "item_type", name: "item_type", title: "Item Type",
                //     sortable:false,
                //     render: function (data, type, row) {
                //         return `
                //         <div class="d-flex flex-column">
                //             <a href="javascript:;" class="text-muted text-hover-primary mb-1">
                //                 ${data??'--'}
                //             </a>
                //         </div>
                //         `;
                //     }
                // },
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
                // {
                //     data: "remarks", name: "remarks", title: "Remarks",
                //     sortable:false,
                //     searchable:false,
                //     render: function (data, type, row) {
                //         if(!data){
                //             return '--';
                //         }
                //         return data;
                //     },
                // },
                // {
                //     data: "last_updated_by", name: "last_updated_by", title: "Last Updated By",
                //     sortable:false,
                //     searchable:false,
                //     className:'',
                //     render(data,type,row)
                //     {
                //         if(!data){
                //             return '--';
                //         }
                //         return `<div class="d-flex align-items-center">
                //                 <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                //                     <span>
                //                         <div class="symbol-label fs-3 bg-light-info text-info">
                //                             ${data[0]}
                //                         </div>
                //                     </span>
                //                 </div>
                //                 <div class="d-flex flex-column">
                //                     <span class="text-gray-800 text-hover-primary mb-1">
                //                         ${data}
                //                     </span>
                //                 </div>
                //             </div>
                //         `
                //     }
                // },
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
                                    <a href="item-details/${data}" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up view" data-id="${data}"
                                    data-bs-toggle="tooltip" title="View Details">
                                        <i class="ki-duotone ki-pencil fs-2x">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
                                    data-bs-toggle="tooltip" title="Delete this record">
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

            $(`#${table}_table`).on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.input('info','Remove this item ?',{
                    isRequired: true,
                    inputPlaceholder: "Put your reason for removing this item",
                    onConfirm: function(remarks) {
                        formData.append('encrypted_id',id);
                        formData.append('remarks',remarks);
                        formData.append('status',0);
                        _request.post('file-maintenance/'+table+'/delete',formData)
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
