"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js";
import {trigger_select} from "../../../global/select.js";

export var dtItemLogs = function (table,param='') {

    const _page = '.page-inventory-list';
    const _card = $('.card-item-logs');
    const _url = 'inventory-details/';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${table}_table`,`${table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            _url+'dt-item-logs',
            {
                filter_item:$('select[name="filter_item"]').val(),
                filter_status:$('select[name="filter_status"]').val(),
                id:param,
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: " ",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "emp_fullname", name: "emp_fullname", title: "Employee",
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
                                </div>
                            </div>
                        `
                    }
                },
                {
                    data: "activity_table", name: "activity_table", title: "Table Activity",
                    sortable:false,
                    searchable:false,
                    className:'text-muted',
                    // render: function (data, type, row) {
                    //     let status = {
                    //         1: ["info", "INSERT"],
                    //         2: ["success", "UPDATE"],

                    //     };
                    //     return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    // },
                },
                {
                    data: "activity_type", name: "activity_type", title: "Activity Type",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["info", "INSERT"],
                            2: ["success", "UPDATE"],

                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "activity_log", name: "activity_log", title: "Activity log",
                    className:'',
                    sortable:false,
                },
                {
                    data: "last_update_at", name: "last_update_at", title: "Created At",
                    className:'',
                    sortable:false,
                },
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

        })
    }

    return {
        init: function () {
            initTable();
        }
    }

}
