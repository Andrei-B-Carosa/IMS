
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
                    className:'min-w-125px',
                    render: function (data, type, row) {
                        return `<span class=" fw-bold">${data}</span>`;
                    },
                },
                {
                    data: "issued_items", name: "issued_items", title: "Issued Devices",
                    className:'min-w-150px',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="fw-bold">${data}</span>`;
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
                // {
                //     data: "issued_by", name: "issued_by", title: "Issued By",
                //     className:'',
                //     sortable:false,
                //     render: function (data, type, row) {
                //         return `<span class="">${data}</span>`;
                //     },
                // },
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
                    data: "a_remarks", name: "a_remarks", title: "Remarks",
                    className:'',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="">${data??'--'}</span>`;
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
                        ${row.status==1?`
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
                                    <div class="menu-item px-3 ">
                                        <a target="_blank" href="/accountability-details/${data}" class="menu-link px-3">
                                            View Accountability
                                        </a>
                                    </div>
                                    <div class="menu-item px-3 mb-3">
                                        <a href="javascript:;"  data-id="${data}" class="menu-link px-3 transfer">
                                            Quick Transfer
                                        </a>
                                    </div>
                                </div>
                            `:`<a href="/accountability-details/${data}" class="btn btn-icon btn-icon btn-sm me-1 btn-light-primary hover-elevate-up"
                                data-bs-toggle="tooltip" title="View Accountability">
                                    <i class="ki-duotone ki-pencil fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </a>
                            `}
                            <button class="btn btn-icon btn-icon btn-sm me-1 hover-elevate-up
                            ${row.status!=1?`remove btn-light-danger`:`btn-secondary`} " data-id="${data}"
                                data-bs-toggle="tooltip" title="${row.status!=1 ?
                                    `Delete accountability`
                                    :`You cannot delete an active accountability`
                                }">
                                    <i class="ki-duotone ki-trash fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                            </button>
                        </div>
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

           $(document).on('click','.transfer',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let modal_id = '#modal-transfer-accountability';

                let formData = new FormData;
                formData.append('id',id);
                _request.post('/accountability/info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));
                    let items_html = payload.items.map(item => {
                        return `
                            <div class="d-flex align-items-center py-1">
                                <span class="bullet bg-primary me-3"></span>
                                ${item}
                            </div>
                        `;
                    }).join('');
                    let html_other_details = `<div class="fw-bold fs-5">Accountability No. : </div>
                        <div class="mb-7 text-gray-800 fs-6">
                            ${payload.form_no}
                        </div>
                        <div class="fw-bold fs-5">Issued To: </div>
                            <div class="mb-7 text-gray-800 fs-6">${payload.issued_to}</div>
                        </div>
                        <div class="fw-bold fs-5">Items: </div>
                        <div class="text-gray-800 fs-6">
                            ${items_html}
                        </div>
                        `;

                    $(modal_id).find('.other-details').empty().html(html_other_details).removeClass('d-none');
                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    $(modal_id).find('button.submit').attr('data-id',id);
                    modal_state(modal_id,'show');
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

