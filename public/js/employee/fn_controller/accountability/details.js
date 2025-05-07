'use strict';
import { modal_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import { dtAccountabilityController } from "../../dt_controller/accountability.js";
import { dtIssuedTo } from "../../dt_controller/accountability/accountable_to.js";
import { dtIssuedItems } from "../../dt_controller/accountability/issued_items.js";
import { fvAccountabilityController } from "../../fv_controller/accountability/accountability.js";

export var AccountabilityDetailsController = function (page, param) {

    $(async function () {

        page_block.block();

        dtIssuedItems('issued-item',param).init();

        dtIssuedTo('issued-to',param).init();

        page_block.release();


    });
}
