"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../global.js"
import { dtAccountabilityController } from "../../dt_controller/accountability.js";

export function fvAccountabilityController(_table=false,param=false){

    var init_fvAccountability = (function () {

        var _handlefvAccountability = function(){

            const _request = new RequestHandler;

            let formValidation;

            let form = document.querySelector("#form_accountability");
            let url = form.getAttribute('action');

            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {

                const validationRules = {};
                const fields = form.querySelectorAll('input, select, textarea');

                fields.forEach(function (field) {
                    const fieldName = field.name;

                    validationRules[fieldName] = {
                        validators: {}
                    };

                    if (field.hasAttribute('data-required') && field.getAttribute('data-required') === 'false') {
                        return;
                    }

                    // if (field.hasAttribute('remote-validation') && field.getAttribute('remote-validation') === 'true') {
                    //     validationRules[fieldName].validators.remote = {
                    //         url: '/hris/admin/settings/employee_details/' + form_id + '/validate_request',
                    //         method: 'POST',
                    //         headers: {
                    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //         },
                    //         data: function () {
                    //             let data_id = document.querySelector(card_id + ' button.submit').getAttribute('data-id');
                    //             return {
                    //                 id: data_id
                    //             };
                    //         }
                    //     };
                    // }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                formValidation = initFormValidation(form,validationRules);
                form.setAttribute('data-fv-initialized', 'true');
            }

            $(modal_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        _handleResetForm(formValidation,form);
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id =_this.attr('data-id') ?? '';
                let modalState = id?'hide':false;

                formValidation && formValidation.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {

                                //block page and disable submit button
                                _handleBlockPage(_this,blockUI,true,['on',true]);

                                let formData = new FormData(form);
                                formData.append('id',id);
                                _request.post(url,formData)
                                .then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        _handleResetForm(formValidation,form,modal_id,modalState);
                                    }
                                })
                                .catch((error) => {
                                    Alert.alert();
                                    console.log(error)
                                })
                                .finally((event) => {
                                    _handleBlockPage(_this,blockUI);
                                    if($(_table).length){
                                        _table ?$(_table).DataTable().ajax.reload(null, false) :'';
                                    }else{
                                        dtAccountabilityController();
                                    }
                                });
                            },
                        });
                    }
                })
            })
        }

        var _handleBlockPage = function(_this,blockUI,blockPage=false,disableButton=['off',false])
        {
            blockPage ? blockUI.block() : blockUI.release();

            _this.attr("data-kt-indicator",disableButton[0]);
            _this.attr("disabled",disableButton[1]);
        }

        var _handleResetForm = function(formValidation,form,modal_id,modalState=false)
        {
            form.reset();
            formValidation.resetForm();
            $(modal_id).find('.submit').attr('data-id','');
            modalState !=false ? modal_state(modal_id,modalState) :'';
        }


        return {
            init: function () {
                _handlefvAccountability();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvAccountability.init();
    });

}
