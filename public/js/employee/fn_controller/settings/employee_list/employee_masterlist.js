'use strict';

import { modal_state } from "../../../../global.js";
import { dtEmployeeMasterlist,dtEmployeeArchiveMasterlist } from "../../../dt_controller/settings/employee_list/dt_employee_masterlist.js";

export var EmployeeMasterlistController = function (page,param) {

    const _page = $('.page-employee-masterlist-settings');

    $(async function () {
        page_block.block();

        dtEmployeeMasterlist().init();

        _page.on('click','button.view-archive',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            dtEmployeeArchiveMasterlist().init();

            setTimeout(() => {
                modal_state('#modal_archive_employee','show');
            }, 100);
        })

        setTimeout(() => {
            page_block.release();
            localStorage.removeItem("employee_details_maintab");
        }, 300);

        // test test
    });

}
