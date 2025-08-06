"use strict";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,fv_validator} from "../../../../global.js"
import { dtCompanyLocation } from "../../../dt_controller/settings/file_maintenance/company_location.js";

export function fvCompanyLocation(_table=false,form_id){

    var init_fvCompanyLocation = (function () {

        var _handlefvCompanyLocation = function(){
            let fvCompanyLocation;
            let form = document.querySelector('#form-add-'+form_id+'');
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

                    if (field.hasAttribute('remote-validation') && field.getAttribute('remote-validation') === 'true') {
                        validationRules[fieldName].validators.remote = {
                            url: '/file-maintenance/' + form_id + '/validate',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: function () {
                                let data_id = document.querySelector(modal_id + ' button.submit').getAttribute('data-id');
                                return {
                                    id: data_id
                                };
                            }
                        };
                    }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                fvCompanyLocation = FormValidation.formValidation(form, {
                    fields: validationRules,
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
                        fvCompanyLocation.resetForm();
                        form.reset();
                        $(modal_id).find('select[name="is_active"]').val('').trigger('change');
                        $(modal_id).find('.submit').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvCompanyLocation && fvCompanyLocation.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',_this.attr('data-id') ?? '');
                                (new RequestHandler).post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvCompanyLocation.resetForm();
                                        form.reset();
                                        if(_this.attr('data-id')){
                                            modal_state(modal_id);
                                            $(modal_id).find('select[name="is_active"]').val('').trigger('change');
                                            $(modal_id).find('.submit').attr('data-id','');
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
                                    if($(_table).length && _table){
                                        $(_table).DataTable().ajax.reload();
                                    }else{
                                        dtCompanyLocation().init()
                                    }
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
                _handlefvCompanyLocation();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvCompanyLocation.init();
    });

}
