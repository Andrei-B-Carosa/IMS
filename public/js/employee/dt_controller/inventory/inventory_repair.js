
"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";


export var dtInventoryRepair = function (table,param='') {

    const _page = '.page-inventory-list';
    const _card = $('.card-repair-list');
    const _url = 'repair/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt',
            {
                filter_category:$('select[name="filter_category"]').val(),
                filter_status:$('select[name="filter_status"]').val(),
                filter_location:$('select[name="filter_location"]').val(),
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
                    data: "tag_number", name: "tag_number", title: "Tag Number",
                    className:'text-muted',
                    sortable:false,
                    render: function (data, type, row) {
                        return `<span class="text-muted fw-bold">${data}</span>`;
                    },
                },
                {
                    data: "item_inventory_id", name: "item_inventory_id", title: "Inventory ID",
                    className:'',
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "name", name: "name", title: "Item",
                    sortable:false,
                    className:'text-start',
                    render: function (data, type, row) {
                        if(row.item_type_id == 1 || row.item_type_id == 8){
                            return `
                            <div class="d-flex flex-column">
                                <a href="/inventory-details/${row.item_inventory_id}" class="text-gray-800 text-hover-primary mb-2 fw-bold">
                                    ${data}
                                </a>
                                <span class="text-muted fw-bold">${row.description}</span>
                                ${row.serial_number ? `<span class="text-muted fw-bold">S/N : ${row.serial_number}</span>`:``}
                            </div>
                            `;
                        }
                        return `
                        <div class="d-flex flex-column">
                            <a href="/inventory-details/${row.item_inventory_id}" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                ${data}
                            </a>
                            ${row.serial_number ? `<span class="text-muted fw-bold">S/N : ${row.serial_number}</span>`:``}
                        </div>
                        `;
                    }
                },
                // {
                //     data: "repair_type", name: "repair_type", title: "Repair Type",
                //     className:'',
                //     sortable:false,
                //     searchable:false,
                //     render: function (data, type, row) {
                //         let status = {
                //             1: ["info", "Hardware"],
                //             2: ["success", "Software"],

                //         };
                //         return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                //     },

                // },
                {
                    data: "serial_number", name: "serial_number", title: "Serial Number",
                    className:'text-center',
                    visible:false,
                    sortable:false,
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
                    visible:false,
                },
                {
                    data: "initial_diagnosis", name: "initial_diagnosis", title: "Repair Notes",
                    className:'',
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "work_to_be_done", name: "work_to_be_done", title: "Action Taken",
                    className:'',
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "accountability_form_no", name: "accountability_form_no", title: "Form No.",
                    sortable:false,
                    visible:false,
                },
                {
                    data: "accountability_id", name: "accountability_id", title: "Form No.",
                    sortable:false,
                    visible:false,
                },
                {
                    data: "start_at", name: "start_at", title: "Start",
                    className:'',
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "end_at", name: "end_at", title: "End",
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
                            1: ["info", "In Progress"],
                            2: ["success", "Resolved"],
                            3: ["danger", "Not Repairable"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "created_by_name", name: "created_by_name", title: "Repaired By",
                    sortable:false,
                    render: function (data, type, row) {
                        if(!data){  return '--';  }
                        return `
                        <span class="text-hover-primary">
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold">
                                    ${data}
                                </span>
                            </div>
                        </span>
                        `;
                    }
                },
                {
                    data: "last_accountable_to", name: "last_accountable_to", title: "Requested By",
                    sortable:false,
                    render: function (data, type, row) {
                        if(!data){  return '--';  }
                        return `
                        <a  href="/accountability-details/${row.accountability_id}" class="text-hover-primary">
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold">
                                    ${data}
                                </span>
                                <span class="text-muted fw-bold">Accountability No : ${row.accountability_form_no}</span>
                            </div>
                        </a>
                        `;
                    }
                },
                {
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    className: "text-start",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return`<div class="d-flex">
                        ${row.status ==1 ?
                            `<button class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up view" data-bs-toggle="tooltip" title="View Details" data-id="${data}">
                                <i class="ki-duotone ki-pencil fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </button>`:``}
                            <button class="btn btn-icon btn-icon btn-sm me-1 hover-elevate-up btn-light-danger remove" data-id="${data}"
                                data-bs-toggle="tooltip" title="Delete repair request form">
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

                Alert.input('info','Remove this repair request ?',{
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

            $(`#${table}_table`).on('click','.view',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let _this = $(this);
                let id    =_this.attr('data-id');
                let modal_id = '#modal-request-repair';
                let form = $('#form-request-repair');
                let formData = new FormData;
                formData.append('id',id);
                _request.post('/repair/info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));
                    $('input[name="start_at"]')[0]._flatpickr.setDate(payload.start_at, true);
                    $('input[name="end_at"]')[0]._flatpickr.setDate(payload.end_at, true);
                    form.find('select[name="status"]').val(payload.status).trigger('change');
                    form.find('select[name="repair_type"]').val(payload.repair_type).trigger('change');
                    form.find('textarea[name="initial_diagnosis"]').val(payload.initial_diagnosis);
                    form.find('textarea[name="work_to_be_done"]').val(payload.work_to_be_done);
                    $(modal_id).find('button.submit').attr('data-id',payload.encrypted_id);

                    let html_other_details = `
                        <div class="fw-bold fs-5">Device: </div>
                        <div class="mb-7 text-gray-800 fs-6">
                            ${payload.name}
                        </div>

                        <div class="fw-bold fs-5">Description:</div>
                        <div class="mb-7 text-gray-800 fs-6">
                            ${payload.description}
                        </div>

                        ${payload.serial_number?`
                            <div class="fw-bold fs-5">Serial Number: </div>
                            <div class="text-gray-800 fs-6 mb-7">
                                ${payload.serial_number}
                            </div> `:``}

                        ${payload.form_no && payload.accountable_to?`
                        <div class="fw-bold fs-5">Issued To: </div>
                        <div class="d-flex flex-column text-gray-800 fs-6">
                                <span class="">
                                    ${payload.accountable_to}
                                </span>
                                <span class="">Accountability No : ${payload.form_no}</span>
                            </div>
                            `:``}
                    `;
                    $(modal_id).find('.other-details').empty().html(html_other_details).removeClass('d-none');
                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    $(`select[name="device"]`).attr('disabled',true);
                    $(`select[name="device"]`).parent().addClass('d-none');
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

