'use strict';
import { data_bs_components, modal_state, page_state } from "../../../global.js";
import { get_company_location } from "../../../global/select.js";
import {RequestHandler} from "../../../global/request.js";
import { fvGeneralDetails, fvSystemUnitDetails } from "../../fv_controller/inventory/inventory_details.js";
import { dtAccountabilityHistory, dtItemLogs, dtRepairHistory } from "../../dt_controller/inventory/inventory_details.js";

export var InventoryDetailsController = function (page, param) {

    let _page = $('.page-inventory-details');

    fvGeneralDetails('',param);
    fvSystemUnitDetails('',param);
    dtItemLogs('item-logs',param).init();
    dtAccountabilityHistory('accountability-history',param).init();
    dtRepairHistory('repair-history',param).init();
    data_bs_components();

    function checkItemStatus()
    {
        let formGeneralDetails = document.querySelector("#form-general-details");
        if($('select[name="status"]').val() == '0'){
            formGeneralDetails.querySelectorAll("input, select, textarea").forEach(el => {
                el.disabled = true;
            });
            let formSystemUnit = document.querySelector("#form-item-details");
            if (formSystemUnit) {
                formSystemUnit.querySelectorAll("input, select, textarea").forEach(el => {
                    el.disabled = true;
                });
                formSystemUnit.querySelectorAll("button[data-repeater-create], button[data-repeater-delete]").forEach(btn => {
                    btn.classList.add("d-none");
                });
            }
            $('button.submit').addClass('d-none');
        }
    }

    $(async function () {

        page_block.block();

        checkItemStatus();

        setTimeout(() => {
            page_block.release();
        }, 500);

    });
}
