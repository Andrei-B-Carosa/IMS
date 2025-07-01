"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../global.js"


export function fvNewInventory(_table=false,param=false){

    var init_fvNewInventory = (function () {

        var _handlefvNewInventory = function(){

            let fv;
            const _request = new RequestHandler;

            let form = document.querySelector("#form-new-inventory");

            let card_id = '#card-new-inventory';
            let cardContent = document.querySelector(`${card_id}`);

            let blockUI = new KTBlockUI(cardContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fv = FormValidation.formValidation(form, {
                    fields: {
                        'item':fv_validator(),
                        // 'tag_number':fv_validator(),
                        'received_by':fv_validator(),
                        'received_at':fv_validator(),
                        'supplier':fv_validator(),
                        'status':fv_validator(),
                        'company_location':fv_validator(),
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

            $(card_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
            })

            $(card_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fv && fv.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Save Changes ?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);

                                let formData = new FormData(form);

                                _request.post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fv.resetForm();
                                    }
                                })

                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    _this.attr("data-kt-indicator","off");
                                    _this.attr("disabled",false);
                                    setTimeout(() => {
                                        blockUI.release();
                                    }, 500);
                                });
                            },
                        });
                    }
                })
            })

            // $(card_id).on('change','select[name="company_location"]',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()

            //     let _this = $(this);
            //     let item_id = $('select[name="item"]').val();
            //     $('input[name="tag_number"]').val('');

            //     if(item_id == ''){
            //         Alert.alert('info',"Please select an item first !", false);
            //         _this.val('').trigger('change.select2');;
            //         return;
            //     }

            //     if(_this.val() != ''){
            //         let formData = new FormData();
            //         formData.append('location_id',_this.val());
            //         formData.append('item_id',item_id);
            //         _request.post('/inventory/check-item-tag',formData)
            //         .then((res) => {
            //             $('input[name="tag_number"]').val(res.payload);
            //         })
            //         .catch((error) => {
            //             console.log(error)
            //             Alert.alert('error',"Something went wrong. Try again later", false);
            //         })
            //         .finally(() => {
            //         });
            //     }
            // })

        }

        return {
            init: function () {
                _handlefvNewInventory();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvNewInventory.init();
    });


}
