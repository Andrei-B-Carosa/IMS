"use strict";

import { DataTableHelper } from "../../../../../global/datatable.js";
import {Alert} from "../../../../../global/alert.js"
import {RequestHandler} from "../../../../../global/request.js"

export var dtDocumentAttachments = function (param) {

    const _page = $('.page-file-maintenance-settings');
    const _table = 'documents';
    const _tab = $(`#tab_content5`);
    const _url = 'hris/admin/201_employee/employee_details/personal_data/document_attachment/';
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
                {
                    data: "count",
                    name: "count",
                    title: "No.",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "filename", name: "filename", title: "Filename",
                    sortable:false,
                    searchable:false,
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
                    data: "file_type", name: "file_type", title: "File Type",
                    sortable:false,
                    searchable:false,
                    className:'text-uppercase text-muted',
                },
                {
                    data: "last_updated_at", name: "last_updated_at", title: "Last Updated At",
                    sortable:false,
                    visible:false,
                    searchable:false,
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
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <span>
                                        <div class="symbol-label fs-3 bg-light-info text-info">
                                            ${data[0]}
                                        </div>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 text-hover-primary mb-1">
                                        ${data}
                                    </span>
                                    <span class="text-muted fs-8">${row.last_updated_at}</span>
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
                        return `
                            <div class="d-flex justify-content-center flex-shrink-0">
                                <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm menu-dropdown"
                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="#" target ="_blank" class="menu-link px-3 view-file" data-id="${data}">
                                                View File
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 download-file" data-id="${data}">
                                                Download File
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" class="menu-link text-danger px-3 delete-file" data-id="${data}" data-title="Remove this document">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                            </div>
                        `;
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

            // $(`#${_table}_table`).on('click','.view-details',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()

            //     let _this = $(this);
            //     let url   =_this.attr('rq-url');
            //     let id    =_this.attr('data-id');
            //     let modal_id = '#modal_add_work_experience';
            //     let form = $('#form_add_work_experience');

            //     let formData = new FormData;

            //     formData.append('id',id);
            //     _request.post('/'+_url+'info',formData)
            //     .then((res) => {
            //         let payload = JSON.parse(window.atob(res.payload));

            //         form.find('input[name="company"]').val(payload.company);
            //         form.find('input[name="salary"]').val(payload.salary);
            //         form.find('input[name="department"]').val(payload.department);
            //         form.find('input[name="position"]').val(payload.position);

            //         $('input[name="date_from"]')[0]._flatpickr.setDate(payload.date_from, true);
            //         $('input[name="date_to"]')[0]._flatpickr.setDate(payload.date_to, true);

            //         form.find('select[name="file_type"]').val(payload.file_type).trigger('change');

            //         $(modal_id).find('button.submit').attr('data-id',id);
            //     })
            //     .catch((error) => {
            //         console.log(error);
            //         Alert.alert('error', "Something went wrong. Try again later", false);
            //     })
            //     .finally((error) => {
            //         modal_state(modal_id,'show');
            //     });

            // })

            $(`#${_table}_table`).on('click','.delete-file',async function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;
                formData.append('id',id);
                Alert.confirm('question',_this.attr('data-title')+' ?',{
                    onConfirm: function() {
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

            $(`#${_table}_table`).on('click','.download-file',async function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;
                formData.append('id',id);

                _request.post('/'+_url+'download_document',formData)
                .then((res) => {
                    if (res.status === 'success') {
                        let link = document.createElement('a');
                        link.href = res.payload;
                        link.download = ''; // You can set the filename here if needed
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                })
                .catch((error) => {
                    console.error(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {

                })
            })

            $(`#${_table}_table`).on('click','.view-file',async function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;
                formData.append('id',id);

                _request.post('/'+_url+'view_document',formData)
                .then((res) => {
                    if (res.status === 'success') {
                        window.open(res.payload, '_blank');
                    }
                })
                .catch((error) => {
                    console.error(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {

                })
            })

        })

    }

    return {
        init: function () {
            initTable();
        }
    }

}
