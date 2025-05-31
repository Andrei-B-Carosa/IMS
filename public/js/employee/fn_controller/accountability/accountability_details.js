'use strict';
import { modal_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import { get_employee, get_mis_personnel, trigger_select } from "../../../global/select.js";
import { dtIssuedTo } from "../../dt_controller/accountability/accountable_to.js";
import { dtIssuedItems } from "../../dt_controller/accountability/issued_items.js";
import { fvOtherAccountabilityDetails } from "../../fv_controller/accountability/accountability_details.js";

export var AccountabilityDetailsController = function (page, param) {

    let _card = $('.card-accountability-details');

    dtIssuedItems('issued-item',param).init();

    dtIssuedTo('issued-to',param).init();

    fvOtherAccountabilityDetails(false,param);

    $(async function () {

        page_block.block();

        setTimeout(() => {

            get_mis_personnel('select[name="issued_by"]','',1);
            get_employee('select[name="received_by"]','',1);

            page_block.release();
        }, 500);


        _card.on('click','.edit-accountability',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let _this = $(this);
            let modal_id = '#modal-accountability-details';
            let form = '#form-accountability-details';

            let formData = new FormData();
            const _request = new RequestHandler;

            page_block.block();
            _this.attr("disabled",true);

            formData.append('encrypted_id',_this.attr('data-id'));
            _request.post('/accountability-details/form',formData).then((res) => {
                if(res.status == 'success'){
                    let payload = JSON.parse(window.atob(res.payload));

                    $(form+' input[name="form_no"]').val(payload.form_no);
                    $(form+' input[name="date_issued"]')[0]._flatpickr.setDate(payload.date_issued, true);
                    $(form+' select[name="accountability_status"]').val(payload.accountability_status).trigger('change');
                    $(form+' textarea[name="remarks"]').val(payload.remarks);

                    trigger_select('select[name="issued_by"]', payload.issued_by)
                    trigger_select('select[name="received_by"]', payload.received_by)
                    modal_state(modal_id,'show');
                }
            })
            .catch((error) => {
                console.log(error)
                Alert.alert('error',"Something went wrong. Try again later", false);
            })
            .finally(() => {
                _this.attr("disabled",false);
                $(modal_id).find('.modal-footer button.submit').attr('data-id',_this.attr('data-id'));
                page_block.release();

            });
        })

    });
}
