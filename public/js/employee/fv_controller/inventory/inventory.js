"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../global.js"
import { dtInventoryList } from "../../dt_controller/inventory/inventory_list.js";

export function fvRepairRequest(_table='#inventory-list_table',param=false){

    var init_RepairRequest = (function () {

        var _handleRepairRequest = function(){

            let fv;
            const _request = new RequestHandler;

            let form = document.querySelector("#form-request-repair");
            let url = form.getAttribute('action');

            let modal_id = '#modal-request-repair';
            let modalBody = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalBody, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fv = FormValidation.formValidation(form, {
                    fields: {
                        'start_at':fv_validator(),
                        'repair_type':fv_validator(),
                        'status':fv_validator(),
                        'description':fv_validator(),
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
                        $(modal_id).find('select[name="status"]').val('').trigger('change');
                        $(modal_id).find('.submit').attr('data-id','');
                        $(modal_id).find('.submit').attr('data-inventory-id','');
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
                                formData.append('item_inventory_id',_this.attr('data-inventory-id') ?? '');
                                (new RequestHandler).post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fv.resetForm();
                                        form.reset();
                                        modal_state(modal_id);
                                        $(modal_id).find('select[name="is_active"]').val('').trigger('change');
                                        $(modal_id).find('.submit').attr('data-id','');
                                        $(modal_id).find('.submit').attr('data-inventory-id','');
                                        if($(_table).length){
                                            $(_table).DataTable().ajax.reload(null,false);
                                        }else{
                                            dtInventoryList('inventory-list').init();
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

            $(modal_id).on('change','select[name="status"]',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                if($(this).val() == 1){
                    if (fv.getFields().hasOwnProperty('end_at')) {
                        fv.removeField('end_at');
                    }
                }
                if($(this).val() == 2 || $(this).val() ==3){
                    if (!fv.getFields().hasOwnProperty('end_at')) {
                        fv.addField('end_at',fv_validator());
                    }
                }

            })


        }

        return {
            init: function () {
                _handleRepairRequest();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_RepairRequest.init();
    });


}
