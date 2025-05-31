'use strict';

import { data_bs_components } from "../../../../global.js";
import { fvNewItemGeneralDetails } from "../../../fv_controller/settings/file_maintenance/new_item.js";

export var NewItemController = function (page,param) {

    fvNewItemGeneralDetails('','');
    data_bs_components();

    $(async function () {

        page_block.block();

        setTimeout(() => {
            page_block.release();
        }, 300);


    });


}

