'use strict';

import {page_state,construct_url,draw_table,data_bs_components} from "../global.js";

export class DataTableHelper {

    constructor(id,wrapper) {
        this.tableId = id;
        this.wrapper = wrapper;
        this.table = null;
    }
    initTable(url, parameters, columns, columnDefs, orderByColumn) {

        $.fn.dataTable.Api.register('column().title()', function() {
            return $(this.header()).text().trim();
        });
        $.fn.dataTable.ext.errMode = 'throw';
        let container = $(`#${this.wrapper}`);
        draw_table(this.tableId,container);

        if ($.fn.DataTable.isDataTable(`#${this.tableId}`)) {
            $(`#${this.tableId}`).DataTable().clear().destroy();
        }

        this.table = $(`#${this.tableId}`).DataTable({
            responsive: true,
            responsiveMode: 'collapsed',
            processing: true,
            serverSide: true,
            // order: [[orderByColumn, 'desc']],
            ajax: {
                url: construct_url(`${url}`),
                type: "POST",
                data: function (d) {
                    if (parameters != null) {
                        let keys = Object.keys(parameters);
                        for (let i = 0; i < keys.length; i++) {
                            let key = keys[i];
                            let value = parameters[key];
                            d[key] = value;
                        }
                    }
                },
                // success: function(r){
                //     console.log(r)
                // }
            },
            columns: columns,
            destroy: true,
            // order: [[0, "asc"]],
            searchDelay: 500,
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i>',
                search: "_INPUT_",
                searchPlaceholder: "Search...",
            }, // change the search
            initComplete: function (settings, json) {
                if (settings._iRecordsTotal == 0) {
                    page_state(
                        container,
                        "empty",
                        null,
                        "No collection made yet!"
                    );
                }
                var ths = $(this.api().table().header()).find("th");
                ths.addClass(  "text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0" );
                data_bs_components()
                KTMenu.createInstances();
            },
        });
        this.table.on('draw', function () {
            data_bs_components()
            KTMenu.createInstances();
            KTComponents.init();
        });
        if (columnDefs) {
            this.table.columnDefs().push(columnDefs);
            this.table.draw();
        }
    }

    search(term) {
        this.table.search(term).draw();
    }

    draw(){
        this.table.draw();
    }
}
