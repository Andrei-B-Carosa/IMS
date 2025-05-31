'use strict';

import { data_bs_components } from "../../../../global.js";
import { dtItems } from "../../../dt_controller/settings/file_maintenance/item.js";
import { fvGeneralDetails, fvSystemUnitDetails } from "../../../fv_controller/settings/file_maintenance/item_details.js";

export var ItemDetailsController = function (page,param) {

    fvGeneralDetails('',param);
    fvSystemUnitDetails('',param);
    data_bs_components();

    $(async function () {

        page_block.block();

        setTimeout(() => {
            page_block.release();
        }, 300);


    });


}

