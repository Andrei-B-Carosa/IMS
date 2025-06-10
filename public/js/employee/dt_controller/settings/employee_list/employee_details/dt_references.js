"use strict";

import { DataTableHelper } from "../../../../../global/datatable.js";
import {Alert} from "../../../../../global/alert.js"
import {RequestHandler} from "../../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../../../global.js"
import {trigger_select} from "../../../../../global/select.js"


export var dtReferences = function (param) {

    const _page = $('.page-file-maintenance-settings');
    const _table = 'references';
    const _tab = $(`#tab_content6`);
    const _url = 'hris/admin/201_employee/employee_details/personal_data/references/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt',
            {
                filter_status:false,
                emp_id:param
            },
            [
                // {
                //     data: "count",
                //     name: "count",
                //     title: "No.",
                //     responsivePriority: -3,
                //     searchable:false,
                // },
                {
                    data: "name", name: "name", title: "Name",
                    sortable:false,
                    render(data,type,row){
                        return `
                            <div class="d-flex flex-column">
                                <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                    ${data}
                                </a>
                                <span class="text-muted">
                                    ${row.position}
                                </span>
                            </div>
                        `;
                    }
                },
                {
                    data: "position", name: "position", title: "Name",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "address", name: "address", title: "Address",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "company", name: "company", title: "Company",
                    sortable:false,
                    render(data,type,row){

                        return `
                            <div class="d-flex flex-column">
                                <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                    ${data}
                                </a>
                                <span class="text-muted">
                                    ${row.address}
                                </span>
                            </div>
                        `;
                    }
                },
                {
                    data: "mobile_number", name: "mobile_number", title: "Contact Details",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        if(data){
                            return data
                        }

                        if(row.email)
                        {
                            return row.email;
                        }
                        return '--';
                    },
                },

                {
                    data: "email", name: "email", title: "Email",
                    sortable:false,
                    searchable:false,
                    visible:false
                },

                {
                    data: "relation", name: "relation", title: "Relation",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        if(data){
                            return data
                        }
                        return '--';
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
                        return `<div class="d-flex justify-content-center flex-shrink-0">
                            <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="More Actions">
                                <i class="ki-duotone ki-pencil fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-175px py-4" data-kt-menu="true">
                                <div class="menu-item px-3 text-start">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                        More Actions
                                    </div>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" data-id="${data}" class="menu-link px-3 view-details">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
                             data-bs-toggle="tooltip" title="Delete this reference" data-title="Delete this reference" data-action="delete">
                                <i class="ki-duotone ki-trash fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                        </div>`;
                    },
                },
            ],
            null,
        );

        $(`#${_table}_table`).ready(function() {

            _tab.off();

            _tab.on('change','select.filter_table',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

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

            $(`#${_table}_table`).on('click','.view-details',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url   =_this.attr('rq-url');
                let id    =_this.attr('data-id');
                let modal_id = '#modal_add_references';
                let form = $('#form_add_references');

                let formData = new FormData;

                formData.append('id',id);
                _request.post('/'+_url+'info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));

                    form.find('input[name="name"]').val(payload.name);
                    form.find('input[name="address"]').val(payload.address);
                    form.find('input[name="email"]').val(payload.email);
                    form.find('input[name="mobile_number"]').val(payload.mobile_number);
                    form.find('input[name="company"]').val(payload.company);
                    form.find('input[name="position"]').val(payload.position);
                    form.find('input[name="relation_to_reference"]').val(payload.relation_to_reference);

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

            $(`#${_table}_table`).on('click','.delete',async function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let action = _this.attr('data-action');
                let formData = new FormData;
                formData.append('id',id);

                Alert.confirm('question',_this.attr('data-title')+' ?',{
                    onConfirm: function() {
                        formData.append('action',action);
                        _request.post('/'+_url+'delete',formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            if(res.payload ==0){
                                initTable();
                            }else{
                                $(`#${_table}_table`).DataTable().ajax.reload(null, false);
                            }
                        })
                        .catch((error) => {
                            console.error(error);
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
