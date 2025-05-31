"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../global.js"

export function fvOtherMaterialIssuanceDetails(_table=false,param=false){

    var init_fvOtherMaterialIssuanceDetails = (function () {

        var _handlefvAccountabilityDetails = function(){

            let fv;
            const _request = new RequestHandler;

            let form = document.querySelector("#form-material-issuance-details");

            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fv = FormValidation.formValidation(form, {
                    fields: {
                        'mrs_no':fv_validator(),
                        'form_no':fv_validator(),
                        'date_issued':fv_validator(),
                        'status':fv_validator(),
                        'issued_by':fv_validator(),
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
                        form.reset();
                        fv.resetForm();
                        $(modal_id).find('button.submit').attr('data-id','');
                        $('.other-details').empty();
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = '/material-issuance-details/update-material-issuance';
                fv && fv.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);

                                let formData = new FormData(form);
                                formData.append('encrypted_id',_this.attr('data-id'));
                                _request.post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fv.resetForm();
                                        form.reset();
                                        modal_state(modal_id);
                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    _this.attr("data-kt-indicator","off");
                                    _this.attr("disabled",false);
                                    _this.attr('data-id','');
                                    blockUI.release();
                                });
                            },
                        });
                    }
                })
            })


        }

        // var _handlefvOtherMaterialIssuanceDetails = function(){

        //     let fv;
        //     const _request = new RequestHandler;

        //     let form = document.querySelector("#form-edit-other-details");
        //     let url = form.getAttribute('action');

        //     let modal_id = form.getAttribute('modal-id');
        //     let modalContent = document.querySelector(`${modal_id} .modal-content`);

        //     let blockUI = new KTBlockUI(modalContent, {
        //         message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        //     });

        //     if (!form.hasAttribute('data-fv-initialized')) {
        //         fv = FormValidation.formValidation(form, {
        //             fields: {
        //                 'issued_at':fv_validator(),
        //                 'status':fv_validator(),
        //             },
        //             plugins: {
        //             trigger: new FormValidation.plugins.Trigger(),
        //             bootstrap: new FormValidation.plugins.Bootstrap5({
        //                 rowSelector: ".fv-row",
        //                 eleInvalidClass: "",
        //                 eleValidClass: "",
        //             }),
        //             },
        //         })
        //         form.setAttribute('data-fv-initialized', 'true');
        //     }

        //     $(modal_id).on('click','.cancel',function(e){
        //         e.preventDefault()
        //         e.stopImmediatePropagation()
        //         Alert.confirm('question',"Close this form ?",{
        //             onConfirm: () => {
        //                 modal_state(modal_id);
        //                 form.reset();
        //                 fv.resetForm();
        //                 $(modal_id).find('button.submit').attr('data-id','');
        //                 $('.other-details').empty();
        //             }
        //         })
        //     })

        //     $(modal_id).on('click','.submit',function(e){
        //         e.preventDefault()
        //         e.stopImmediatePropagation()

        //         let _this = $(this);
        //         let action = form.getAttribute('action');
        //         let url = '/accountability-details/'+'update-'+action;
        //         fv && fv.validate().then(function (v) {
        //             if(v == "Valid"){
        //                 Alert.confirm("question","Submit this form?", {
        //                     onConfirm: function() {
        //                         blockUI.block();
        //                         _this.attr("data-kt-indicator","on");
        //                         _this.attr("disabled",true);

        //                         let formData = new FormData(form);
        //                         formData.append('encrypted_id',_this.attr('data-id'));
        //                         _request.post(url,formData).then((res) => {
        //                             Alert.toast(res.status,res.message);
        //                             if(res.status == 'success'){
        //                                 fv.resetForm();
        //                                 form.reset();
        //                                 modal_state(modal_id);
        //                                 form.setAttribute('action','')
        //                                 $('.other-details').empty();
        //                             }
        //                             if(action === 'issued-items'){
        //                                 dtIssuedItems('issued-item',param).init();
        //                             }else if(action === 'issued-to'){
        //                                 dtIssuedTo('issued-to',param).init();
        //                             }
        //                         })
        //                         .catch((error) => {
        //                             console.log(error)
        //                             Alert.alert('error',"Something went wrong. Try again later", false);
        //                         })
        //                         .finally(() => {
        //                             _this.attr("data-kt-indicator","off");
        //                             _this.attr("disabled",false);
        //                             _this.attr('data-id','');
        //                             blockUI.release();
        //                         });
        //                     },
        //                 });
        //             }
        //         })
        //     })


        // }

        return {
            init: function () {
                _handlefvAccountabilityDetails();
                // _handlefvOtherMaterialIssuanceDetails();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvOtherMaterialIssuanceDetails.init();
    });


}
