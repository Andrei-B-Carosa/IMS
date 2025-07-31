
"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";


export var dtAccountability = function (table,param='') {

    const _page = '.page-accountability';
    const _card = $('.card-accountability-list');
    const _url = 'accountability/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt',
            {
                filter_status:$('select[name="filter_status"]').val(),
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
                    data: "form_no", name: "form_no", title: "Form No.",
                    className:'',
                    render: function (data, type, row) {
                        return `<span class="">${data}</span>`;
                    },
                },
                {
                    data: "issued_to", name: "issued_to", title: "Issued To",
                    className:'',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="=">${data}</span>`;
                    },
                },
                {
                    data: "issued_by", name: "issued_by", title: "Issued By",
                    className:'',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="=">${data}</span>`;
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
                    className:'',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="fw-bold">${data}</span>`;
                    },
                },
                {
                    data: "returned_at", name: "returned_at", title: "Returned At",
                    className:'',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="fw-bold">${data}</span>`;
                    },
                },
                // {
                //     data: "a_remarks", name: "a_remarks", title: "Remarks",
                //     className:'',
                //     sortable:false,
                //     render: function (data, type, row) {
                //         return `<span class="text-muted">${data??'--'}</span>`;
                //     },
                // },
                {
                    data: "issued_items", name: "issued_items", title: "Issued Devices",
                    className:'',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="fw-bold">${data}</span>`;
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
                            <a href="/accountability-details/${data}" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                                data-bs-toggle="tooltip" title="View Accountability">
                                <i class="ki-duotone ki-pencil fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                            <button class="btn btn-icon btn-icon btn-sm me-1 hover-elevate-up
                            ${row.status!=1?`remove btn-light-danger`:`btn-secondary`} " data-id="${data}"
                                data-bs-toggle="tooltip" title="${row.status!=1 ?
                                    `Delete accountability`
                                    :`Accountability is currently active`
                                }">
                                    <i class="ki-duotone ki-trash fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                            </button>
                        </div>`;
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

                Alert.input('info','Delete this accountability ?',{
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

