'use strict';
import { data_bs_components, modal_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import { dtAccountabilityController } from "../../dt_controller/accountability.js";
import { dtIssuedTo } from "../../dt_controller/accountability/accountable_to.js";
import { dtIssuedItems } from "../../dt_controller/accountability/issued_items.js";
import { fvAccountabilityController } from "../../fv_controller/accountability/accountability.js";
import { fvNewAccountabilityController } from "../../fv_controller/accountability/new_accountability.js";

export var NewAccountabilityController = function (page, param) {

    fvNewAccountabilityController();

    $(async function () {

        page_block.block();
        data_bs_components();


        page_block.release();


    });
}
