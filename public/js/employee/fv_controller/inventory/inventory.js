"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../global.js"
import { dtInventoryList } from "../../dt_controller/inventory/inventory_list.js";
import { dtInventoryRepair } from "../../dt_controller/inventory/inventory_repair.js";
import { get_inventory } from "../../../global/select.js";

export function fvRepairRequest(_table='#repair-list_table',param=false){

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
                        // 'device':fv_validator(),
                        'start_at':fv_validator(),
                        'repair_type':fv_validator(),
                        'status':{
                            validators:{
                                notEmpty:{
                                    message:'This field is required'
                                },
                                callback: {
                                    callback: function(input) {
                                        if(input.value ==1){ return true; }
                                        const endDate = $(modal_id+' input[name="end_at"]').val(); // or document.querySelector()
                                        if (endDate == '') {
                                            return {
                                                valid: false,
                                                message: 'Enter the "end date" if the status is Resolved or Not Repairable',
                                            };
                                        }
                                        return true;
                                    }
                                }
                            }
                        },
                        'requested_by':fv_validator(),
                        'initial_diagnosis':fv_validator(),
                        'end_at': {
                            validators: {
                                callback: {
                                    callback: function(input) {
                                        const endAtValue = input.value;
                                        const statusValue = $(modal_id+' select[name="status"]').val(); // or document.querySelector()

                                        // If no end date, don't validate this rule
                                        if (endAtValue === '') {
                                            return true;
                                        }
                                        // If status is still "In Progress" (value = 1)
                                        if (statusValue === '1') {
                                            return {
                                                valid: false,
                                                message: 'Please update the status to "Resolved" or "Not Repairable" if you entered an end date.',
                                            };
                                        }

                                        return true;
                                    }
                                }
                            }
                        },
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
                        $(modal_id).find('.other-details').empty().addClass('d-none');
                        $(modal_id).find('select[name="device"]').parent().removeClass('d-none');
                        $(modal_id).find('select[name="device"]').removeAttr('disabled');
                        $(modal_id).find('select[name="requested_by"]').val('').trigger('change');
                        $(modal_id).find('button.submit').attr('disabled',false);
                        $(modal_id).find('input, textarea, select').attr('disabled',false);
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
                                        $(modal_id).find('.other-details').empty().addClass('d-none');
                                        get_inventory(`select[name="device"]`,'','repair_items','all');
                                        if($(_table).length){
                                            $(_table).DataTable().ajax.reload(null,false);
                                        }else{
                                            dtInventoryRepair('repair-list','').init();
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

            // $(modal_id).on('change','select[name="status"]',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()

            //     if($(this).val() == 1){
            //         if (fv.getFields().hasOwnProperty('end_at') && $(modal_id+' input[name="end_at"]').val() == '') {
            //             fv.removeField('end_at');
            //         }
            //         if (fv.getFields().hasOwnProperty('end_at') && $(modal_id+' input[name="end_at"]').val() == '') {
            //             fv.removeField('work_to_be_done');
            //         }
            //     }
            //     if($(this).val() == 2 || $(this).val() ==3){
            //         if (!fv.getFields().hasOwnProperty('end_at')) {
            //             fv.addField('end_at',fv_validator());
            //         }
            //         if (!fv.getFields().hasOwnProperty('work_to_be_done')) {
            //             fv.addField('work_to_be_done',fv_validator());
            //         }
            //     }
            // })

            $(modal_id).on('change','select[name="device"]',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id = _this.val();

                if(!id){
                    setTimeout(() => {
                        $(modal_id).find('.other-details').empty().addClass('d-none');
                    }, 100)
                    return;
                }

                let formData = new FormData(form);
                formData.append('encrypted_id',id);
                (new RequestHandler).post('repair/get-item-details',formData).then((res) => {
                    if(res.status == 'success'){
                        let payload = JSON.parse(window.atob(res.payload));
                        let html_other_details = `
                        <div class="fw-bold fs-5">Device: </div>
                        <div class="mb-7 text-gray-800 fs-6">
                            ${payload.name}
                        </div>

                        <div class="fw-bold fs-5">Description:</div>
                        <div class="mb-7 text-gray-800 fs-6">
                            ${payload.description}
                        </div>

                        ${payload.serial_number?`
                            <div class="fw-bold fs-5">Serial Number: </div>
                            <div class="text-gray-800 fs-6 mb-7">
                                ${payload.serial_number}
                            </div> `:``}

                        ${payload.form_no && payload.accountable_to?`
                        <div class="fw-bold fs-5">Issued To: </div>
                        <div class="d-flex flex-column text-gray-800 fs-6">
                                <span class="">
                                    ${payload.accountable_to}
                                </span>
                                <span class="">Accountability No : ${payload.form_no}</span>
                            </div>
                            `:``}
                    `;
                        $(modal_id).find('.other-details').empty().html(html_other_details).removeClass('d-none');
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
