'use strict';
import { data_bs_components, modal_state } from "../../../global.js";
import { fvNewAccountabilityController } from "../../fv_controller/accountability/new_accountability.js";

export var NewAccountabilityController = function (page, param) {

    fvNewAccountabilityController();

    $(async function () {

        page_block.block();
        data_bs_components();


        page_block.release();


    });
}
