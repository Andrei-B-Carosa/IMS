"use strict";

import { DataTableHelper } from "../../../../../global/datatable.js";
import {Alert} from "../../../../../global/alert.js"
import {RequestHandler} from "../../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../../../global.js"
import {trigger_select} from "../../../../../global/select.js"


export var dtWorkExperience = function (param) {

    const _page = $('.page-file-maintenance-settings');
    const _table = 'work_experience';
    const _tab = $(`#tab_content4`);
    const _url = 'hris/admin/201_employee/employee_details/personal_data/work_experience/';
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
                    data: "position", name: "position", title: "Company",
                    sortable:false,
                    visible:false,
                    searchable:false,
                },

                {
                    data: "is_government", name: "is_government", title: "Is Government",
                    sortable:false,
                    visible:false,
                    searchable:false,
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
                                ${is_government==1?'Government':'Private Company'}
                                </span>
                            </div>
                        `;
                    }
                },

                {
                    data: "department", name: "department", title: "Department",
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
                    data: "date_from",
                    name: "date_from",
                    title: "Date From",
                    searchable:false,
                    visible:false,
                },

                {
                    data: "date_to",
                    name: "date_to",
                    title: "From - To",
                    searchable:false,
                    render: function (data, type, row) {
                        return row.date_from+' - '+data;
                    }
                },

                {
                    data: "salary", name: "salary", title: "Salary",
                    sortable:false,
                    searchable:false,
                },

                {
                    data: "supporting_document",
                    name: "supporting_document",
                    title: "Supporting Document",
                    searchable:false,
                    sortable:false,
                    render(data,type,row){
                        if(!data){
                            return '--';
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
                                <div class="menu-item px-3">
                                    <a href="javascript:;" data-id="${data}" class="menu-link px-3 delete text-danger" data-title="Delete supporting document" data-action="delete-document">
                                        Remove Document
                                    </a>
                                </div>
                            </div>

                            <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
                             data-bs-toggle="tooltip" title="Delete employee education" data-title="Delete employee education" data-action="delete-work-experience">
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
                let modal_id = '#modal_add_work_experience';
                let form = $('#form_add_work_experience');

                let formData = new FormData;

                formData.append('id',id);
                _request.post('/'+_url+'info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));

                    form.find('input[name="company"]').val(payload.company);
                    form.find('input[name="salary"]').val(payload.salary);
                    form.find('input[name="department"]').val(payload.department);
                    form.find('input[name="position"]').val(payload.position);

                    $('input[name="date_from"]')[0]._flatpickr.setDate(payload.date_from, true);
                    $('input[name="date_to"]')[0]._flatpickr.setDate(payload.date_to, true);

                    form.find('select[name="is_government"]').val(payload.is_government).trigger('change');

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

                if(action=='delete-document'){
                    try {
                        let res = await _request.post('/'+_url+'check_document', formData);
                        if(res.status == 'invalid'){
                            Alert.alert('info',res.message, false);
                            return;
                        }
                    } catch (err) {
                        console.error(error);
                        Alert.alert('error','Something went wrong. Try again later', false);
                    }
                }

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
