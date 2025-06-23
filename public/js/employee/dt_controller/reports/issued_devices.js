"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";


export var dtIssuedDevices = function (table,param=false) {

    const _page = '.page-issued-devices';
    const _card = $('.card-issued-devices');
    const _url = 'reports/issued-devices/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt',
            {
                filter_status:'all',
                id:param,
            },
            [
                // {
                //     data: "count",
                //     name: "count",
                //     title: "#",
                //     responsivePriority: -3,
                //     searchable:false,
                // },
                {
                    data: "tag_number",
                    name: "tag_number",
                    title: "Tag Number",
                    className:"text-muted",
                },
                {
                    data: "name", name: "name", title: "Item",
                    sortable:false,
                    render: function (data, type, row) {
                        if(row.serial_number){
                            return `
                            <div class="d-flex flex-column">
                                <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                    ${data}
                                </a>
                                ${row.serial_number?`<span class="text-muted">S/N: ${row.serial_number}</span>`:``}
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
                    searchable:false,
                    render: function (data, type, row) {
                        if(!data){
                            return '--';
                        }
                        return data;
                    },
                },
                {
                    data: "serial_number", name: "serial_number", title: "Serial Number",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "accountability_status", name: "accountability_status", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Issued"],
                            2: ["info", "Returned"],
                            3: ["secondary", "Temporary Issued"],
                            4: ["danger", "Under Repair"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "issued_at", name: "issued_at", title: "Issued At",
                    sortable:false,
                    searchable:false,
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }

                        return data;
                    }
                },
                {
                    data: "returned_at", name: "returned_at", title: "Returned At",
                    sortable:false,
                    searchable:false,
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }

                        return data;
                    }
                },
                {
                    data: "accountable_to", name: "accountable_to", title: "Issued To",
                    // sortable:false,
                    // searchable:false,
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
                // {
                //     data: "encrypted_id",
                //     name: "encrypted_id",
                //     title: "Action",
                //     sortable:false,
                //     className: "text-center",
                //     responsivePriority: -1,
                //     render: function (data, type, row) {
                //         return `${
                //                 row.status ==2 || row.status==3 || row.status==4 ?
                //                 `
                //                 `
                //                 :`<div class="d-flex justify-content-center flex-shrink-0">
                //                     <a href="javascript:;" class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up view" data-id="${data}"
                //                     data-bs-toggle="tooltip" title="View Details">
                //                         <i class="ki-duotone ki-pencil fs-2x">
                //                             <span class="path1"></span>
                //                             <span class="path2"></span>
                //                             <span class="path3"></span>
                //                             <span class="path4"></span>
                //                         </i>
                //                     </a>
                //                     <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up remove" data-id="${data}"
                //                     data-bs-toggle="tooltip" title="Remove item from accountability">
                //                         <i class="ki-duotone ki-cross fs-2x">
                //                             <span class="path1"></span>
                //                             <span class="path2"></span>
                //                         </i>
                //                     </a>
                //                 </div>`
                //             }
                //         `;
                //     },
                // },
            ],
            null,
        );

        $(`#${table}_table`).ready(function() {

            _card.off();

            // _card.on('change','select.filter_table',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()
            //     initTable();
            // })

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

            _card.on('click','.export-issued-devices',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;
                formData.append('encrypted_id',id);

                _request.postBlob('/' + _url + 'export', formData, true)
                .then(response => {
                    const blob = response.data;
                    const disposition = response.headers['content-disposition'];

                    let filename = 'report.pdf'; // fallback
                    if (disposition) {
                        const match = disposition.match(/filename\*?=(?:UTF-8'')?"?([^";\n]*)"?/);
                        if (match && match[1]) {
                            filename = decodeURIComponent(match[1].replace(/['"]/g, ''));
                        }
                    }

                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
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
