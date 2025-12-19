"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";


export var dtIssuedDevices = function (table,param=false) {

    const _page = '.page-issued-devices';
    const _card = $('.card-issued-devices');
    const _cardFilter = $('#card-filter');
    const _url = 'reports/issued-devices/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt',
            {
                filter_status:_cardFilter.find('select[name="filter_status"]').val(),
                filter_year:_cardFilter.find('select[name="filter_year"]').val(),
                filter_month:_cardFilter.find('select[name="filter_month"]').val(),
                filter_category:_cardFilter.find('select[name="filter_category"]').val(),
                filter_location:_cardFilter.find('select[name="filter_location"]').val(),
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
                    // sortable:false,
                    render: function (data, type, row) {
                        return `<span class="text-muted fw-bold">${data}</span>`;
                    },
                },
                {
                    data: "name", name: "name", title: "Item",
                    sortable:false,
                    className:'text-start',
                    render: function (data, type, row) {
                        if(row.item_type_id == 1 || row.item_type_id == 8){
                            return `
                            <div class="d-flex flex-column">
                                <a href="javascript:;" class="text-gray-800 text-hover-primary mb-2 fw-bold">
                                    ${data}
                                </a>
                                <span class="text-muted fw-bold">${row.description}</span>
                                ${row.serial_number ? `<span class="text-muted fw-bold">S/N : ${row.serial_number}</span>`:``}
                            </div>
                            `;
                        }
                        return `
                        <div class="d-flex flex-column">
                            <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                ${data}
                            </a>
                            ${row.serial_number ? `<span class="text-muted fw-bold">S/N : ${row.serial_number}</span>`:``}
                        </div>
                        `;
                    }
                },
                {
                    data: "item_name", name: "item_name", title: "Item Name",
                    className:'',
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "item_type_id", name: "item_type_id", title: "Item Type",
                    className:'',
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "enable_quick_actions", name: "enable_quick_actions", title: "Enable Quick Action",
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
                    data: "location", name: "location", title: "Location",
                    className:'',
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "serial_number", name: "serial_number", title: "Serial Number",
                    sortable:false,
                    className:'text-center',
                    visible:false,
                },
                {
                    data: "status", name: "status", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            0: ["warning", "Disposed"],
                            1: ["info", "Available"],
                            2: ["success", "Issued"],
                            3: ["secondary", "Temporary Issued"],
                            4: ["danger", "Under Repair"],
                            5: ["warning", "Under Warranty"],
                            6: ["success", "Deployed"],
                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "form_no", name: "form_no", title: "Form No.",
                    sortable:false,
                    visible:false,
                },
                {
                    data: "received_at", name: "received_at", title: "Purchased Date",
                    searchable:false,
                    className:' text-start min-w-100px',
                    render: function (data, type, row) {
                        if(!data){ return '--'; }
                        return data;
                    },
                },
                {
                    data: "remarks", name: "remarks", title: "Remarks",
                    sortable:false,
                    searchable:false,
                    className:'text-start ',
                    render: function (data, type, row) {
                        if(!data){
                            return '--';
                        }
                        return data;
                    },
                },
                {
                    data: "accountability_id", name: "accountability_id", title: "Accountabiity ID",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "accountable_to", name: "accountable_to", title: "Issued To",
                    sortable:false,
                    render: function (data, type, row) {
                        if(!data){  return '--';  }
                        return `
                        <a  target="_blank" href="/accountability-details/${row.accountability_id}" class="text-hover-primary">
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold">
                                    ${data}
                                </span>
                                <span class="text-muted fw-bold">Accountability No : ${row.form_no}</span>
                            </div>
                        </a>
                        `;
                    }
                },
            ],
            null,
        );

        $(`#${table}_table`).ready(function() {

            _cardFilter.off();

            _cardFilter.on('change','select.sfilter',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            _cardFilter.on('keyup','.search',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let searchTerm = $(this).val();
                if (e.key === 'Enter' || e.keyCode === 13) {
                    dataTableHelper.search(searchTerm);
                } else if (e.keyCode === 8 || e.key === 'Backspace') {
                    setTimeout(() => {
                        let updatedSearchTerm = $(this).val();
                        dataTableHelper.search(updatedSearchTerm);
                    }, 0);
                }
            })

            _cardFilter.on('click','.btn-search',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let searchTerm = _cardFilter.find('input.search').val();
                dataTableHelper.search(searchTerm);
            })

            _card.on('click','.export-issued-devices',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;
                formData.append('filter_status',_cardFilter.find('select[name="filter_status"]').val());
                formData.append('filter_year',_cardFilter.find('select[name="filter_year"]').val());
                formData.append('filter_month',_cardFilter.find('select[name="filter_month"]').val());
                formData.append('filter_category',_cardFilter.find('select[name="filter_category"]').val());
                formData.append('filter_location',_cardFilter.find('select[name="filter_location"]').val());
                formData.append('search',_cardFilter.find('input.search').val());
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
