'use strict';
import { data_bs_components, modal_state } from "../../../global.js";
import { fvNewMaterialIssuanceController } from "../../fv_controller/material_issuance/new_material_issuance.js";

export var NewMaterialIssuanceController = function (page, param) {

    fvNewMaterialIssuanceController();

    $(async function () {

        page_block.block();
        data_bs_components();


        page_block.release();


    });
}
