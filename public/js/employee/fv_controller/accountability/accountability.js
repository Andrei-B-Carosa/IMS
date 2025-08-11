"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../global.js"
import { dtInventoryList } from "../../dt_controller/inventory/inventory_list.js";
import { dtInventoryRepair } from "../../dt_controller/inventory/inventory_repair.js";
import { get_inventory } from "../../../global/select.js";
import { dtAccountability } from "../../dt_controller/accountability/accountability_list.js";

export function fvTransferAccountability(_table='#accountability-list_table',param=false){

    var init_TransferAccountability = (function () {

        var _handleTransferAccountability = function(){

            let fv;
            const _request = new RequestHandler;

            let form = document.querySelector("#form-transfer-accountability");
            let url = form.getAttribute('action');

            let modal_id = '#modal-transfer-accountability';
            let modalBody = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalBody, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fv = FormValidation.formValidation(form, {
                    fields: {
                        'form_no':fv_validator(),
                        'issued_at':fv_validator(),
                        'received_by':fv_validator(),
                    },
                    plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: "",
                    }),
                    },
                })
                form.setAttribute('data-fv-initialized', 'true');
            }

            $(modal_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fv.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                fv && fv.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',_this.attr('data-id') ?? '');
                                _request.post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        modal_state(modal_id);
                                        fv.resetForm();
                                        form.reset();
                                        $(modal_id).find('.submit').attr('data-id','');
                                        if($(_table).length){
                                            $(_table).DataTable().ajax.reload(null,false);
                                        }else{
                                            dtAccountability('accountability-list').init();
                                        }
                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    _this.attr("data-kt-indicator","off");
                                    _this.attr("disabled",false);
                                    blockUI.release();
                                });
                            },
                        });
                    }
                })
            })
        }

        return {
            init: function () {
                _handleTransferAccountability();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_TransferAccountability.init();
    });


}
