"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { dtEducationalBackground } from "../../dt_controller/settings/employee_list/employee_details/dt_educational_background.js";
import { dtWorkExperience } from "../../dt_controller/settings/employee_list/employee_details/dt_work_experience.js";
import { dtDocumentAttachments } from "../../dt_controller/settings/employee_list/employee_details/dt_document_attachments.js";
import { dtReferences } from "../../dt_controller/settings/employee_list/employee_details/dt_references.js";

export function fvEmployeeDetails(_table=false,form_id,param){

    var init_fvEmployeeDetails = (function () {

        var _handlefvEmployeeDetails = function(){
            let fvEmployeeDetails;
            let form = document.querySelector('#form'+form_id+'');
            let card_id = form.getAttribute('card-id');
            let card = form.closest('.card');

            let blockUI = new KTBlockUI(card, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {

                const validationRules = {};
                const fields = form.querySelectorAll('input, select, textarea');

                fields.forEach(function (field) {
                    const fieldName = field.name;
                    validationRules[fieldName] = {  validators: {} };

                    // Telephone Validation
                    if (field.hasAttribute('fv-telephone') && field.getAttribute('fv-telephone') === 'true') {
                        validationRules[fieldName].validators.regexp = {
                            regexp: /^\(0\d{2}\)\d{3,4}-\d{4}$/,
                            message: 'Enter a valid telephone number (e.g., (02)123-4567 or (032)123-4567)',
                        };
                    }

                    // Mobile Validation
                    if (field.hasAttribute('fv-mobile') && field.getAttribute('fv-mobile') === 'true') {
                        validationRules[fieldName].validators.regexp = {
                            regexp: /^[0-9]{11}$/,
                            message: 'Mobile number must be exactly 11 digits',
                        };
                    }

                    if (field.hasAttribute('data-required') && field.getAttribute('data-required') === 'false') {
                        return;
                    }

                    if (field.hasAttribute('remote-validation') && field.getAttribute('remote-validation') === 'true') {
                        validationRules[fieldName].validators.remote = {
                            url: '/hris/admin/settings/employee_details/' + form_id + '/validate_request',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: function () {
                                let data_id = document.querySelector(card_id + ' button.submit').getAttribute('data-id');
                                return {
                                    id: data_id
                                };
                            }
                        };
                    }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                fvEmployeeDetails = FormValidation.formValidation(form, {
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

            $(card).on('click','button.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                fvEmployeeDetails.resetForm();
                // form.reset();
                $(form).find('input, select').prop('disabled', true);

                $(this).siblings('.edit').removeClass('d-none');
                $(this).siblings('.save').addClass('d-none');
                $(this).addClass('d-none');
            })

            $(card).on('click','button.save',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvEmployeeDetails && fvEmployeeDetails.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('tab',form_id);
                                formData.append('id',param);
                                (new RequestHandler).post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvEmployeeDetails.resetForm();
                                        _this.addClass('d-none');
                                        _this.siblings('.cancel').addClass('d-none');
                                        _this.siblings('.edit').removeClass('d-none');
                                        $(form).find('input, select').prop('disabled', true);
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

            $(card).on('click','button.edit',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                const form = $(this).closest('.card').find('form');
                form.find('input, select').prop('disabled', false);
                $(this).addClass('d-none');
                $(this).siblings('.cancel, .save').removeClass('d-none');
            });
        }

        return {
            init: function () {
                _handlefvEmployeeDetails();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvEmployeeDetails.init();
    });

}

export function fvEducationalBackground(_table=false,form_id,param){

    var init_EducationalBackground = (function () {

        var _handleEducationalBackground = function(){
            let EducationalBackground;
            let form = document.querySelector(`#form_add_education`);
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

                    if (field.hasAttribute('data-accepted') && field.getAttribute('data-accepted') === 'pdf') {
                        validationRules[fieldName].validators ={
                            file: {
                                extension: 'pdf',
                                type: 'application/pdf',
                                message: 'Please upload a valid PDF file'
                            },
                            fileSize: {
                                maxSize: 20480 * 1024,
                                message: 'The file is too large. Maximum size allowed is 20 MB.'
                            },
                        };
                    }

                    if (field.hasAttribute('data-required') && field.getAttribute('data-required') === 'false') {
                        return;
                    }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                EducationalBackground = FormValidation.formValidation(form, {
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

            $(modal_id).on('click','button.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        EducationalBackground.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','button.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');

                EducationalBackground && EducationalBackground.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('tab',form_id);
                                formData.append('emp_id',param);
                                formData.append('id',_this.attr('data-id')??'');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        if(_this.attr('data-id')){
                                            _this.attr('data-id','');
                                            modal_state(modal_id);
                                        }
                                        EducationalBackground.resetForm();
                                        form.reset();
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
                                        dtEducationalBackground(param).init()
                                    }
                                    blockUI.release();
                                });
                            },
                        });
                    }
                })
            })

            $(modal_id).on('click','button.edit',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                const form = $(this).closest('.card').find('form');
                form.find('input, select').prop('disabled', false);
                $(this).addClass('d-none');
                $(this).siblings('.cancel, .save').removeClass('d-none');
            });
        }

        return {
            init: function () {
                _handleEducationalBackground();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_EducationalBackground.init();
    });

}

export function fvWorkExperience(_table=false,form_id,param){

    var init_WorkExperience = (function () {

        var _handleWorkExperience = function(){
            let WorkExperience;
            let form = document.querySelector(`#form_add_work_experience`);
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {

                const validationRules = {};
                const fields = form.querySelectorAll('input, select, textarea, checkbox');

                fields.forEach(function (field) {
                    const fieldName = field.name;

                    validationRules[fieldName] = {
                        validators: {}
                    };

                    if (field.hasAttribute('data-accepted') && field.getAttribute('data-accepted') === 'pdf') {
                        validationRules[fieldName].validators ={
                            file: {
                                extension: 'pdf',
                                type: 'application/pdf',
                                message: 'Please upload a valid PDF file'
                            },
                            fileSize: {
                                maxSize: 20480 * 1024,
                                message: 'The file is too large. Maximum size allowed is 20 MB.'
                            },
                        };
                    }

                    if (field.hasAttribute('data-required') && field.getAttribute('data-required') === 'false') {
                        return;
                    }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                WorkExperience = FormValidation.formValidation(form, {
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

            $(modal_id).on('click','button.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        WorkExperience.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','button.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');

                WorkExperience && WorkExperience.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('tab',form_id);
                                formData.append('emp_id',param);
                                formData.append('id',_this.attr('data-id')??'');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        if(_this.attr('data-id')){
                                            _this.attr('data-id','');
                                            modal_state(modal_id);
                                        }
                                        WorkExperience.resetForm();
                                        form.reset();
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
                                        dtWorkExperience(param).init()
                                    }
                                    blockUI.release();
                                });
                            },
                        });
                    }
                })
            })

            $(modal_id).on('click','button.edit',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                const form = $(this).closest('.card').find('form');
                form.find('input, select').prop('disabled', false);
                $(this).addClass('d-none');
                $(this).siblings('.cancel, .save').removeClass('d-none');
            });
        }

        return {
            init: function () {
                _handleWorkExperience();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_WorkExperience.init();
    });

}

export function fvDocumentAttachments(_table=false,form_id,param){

    var init_fvDocumentAttachments = (function () {

        var _handlefvDocumentAttachments = function(){
            let fvDocumentAttachments;
            let form = document.querySelector('#form_add_documents');

            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvDocumentAttachments = FormValidation.formValidation(form, {
                    fields: {
                        files: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                file: {
                                    extension: 'pdf',
                                    type: 'application/pdf',
                                    message: 'Please upload a valid PDF file'
                                },
                                fileSize: {
                                    maxSize: 20480 * 1024,
                                    message: 'The file is too large. Maximum size allowed is 20 MB.'
                                },
                            }
                        },
                        file_type:fv_validator(),
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

            $(modal_id).on('click','button.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvDocumentAttachments.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','button.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');

                fvDocumentAttachments && fvDocumentAttachments.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('tab',form_id);
                                formData.append('emp_id',param);
                                formData.append('id',_this.attr('data-id')??'');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        if(_this.attr('data-id')){
                                            _this.attr('data-id','');
                                            modal_state(modal_id);
                                        }
                                        fvDocumentAttachments.resetForm();
                                        form.reset();
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
                                        dtDocumentAttachments(param).init()
                                    }
                                    blockUI.release();
                                });
                            },
                        });
                    }
                })
            })

            $(modal_id).on('change','select[name="file_type"]',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let value = _this.val();

                if(value == 'others'){
                    fvDocumentAttachments.addField('others', fv_validator());
                    $('input[name="others"]').attr('disabled',false);
                    $('.others').removeClass('d-none');
                }else{
                    if(fvDocumentAttachments.getElements('others')){
                        $('.others').addClass('d-none');
                        $('input[name="others"]').attr('disabled',true);
                        fvDocumentAttachments.removeField('others');
                    }
                }
            })

        }

        return {
            init: function () {
                _handlefvDocumentAttachments();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvDocumentAttachments.init();
    });

}

export function fvReferences(_table=false,form_id,param){

    var init_fvReferences = (function () {

        var _handlefvReferences = function(){
            let fvReferences;
            let form = document.querySelector('#form_add_references');

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

                    if (field.hasAttribute('data-accepted') && field.getAttribute('data-accepted') === 'pdf') {
                        validationRules[fieldName].validators ={
                            file: {
                                extension: 'pdf',
                                type: 'application/pdf',
                                message: 'Please upload a valid PDF file'
                            },
                            fileSize: {
                                maxSize: 20480 * 1024,
                                message: 'The file is too large. Maximum size allowed is 20 MB.'
                            },
                        };
                    }

                    if (field.hasAttribute('data-required') && field.getAttribute('data-required') === 'false') {
                        return;
                    }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                fvReferences = FormValidation.formValidation(form, {
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

            $(modal_id).on('click','button.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvReferences.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','button.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');

                fvReferences && fvReferences.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('tab',form_id);
                                formData.append('emp_id',param);
                                formData.append('id',_this.attr('data-id')??'');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        if(_this.attr('data-id')){
                                            _this.attr('data-id','');
                                            modal_state(modal_id);
                                        }
                                        fvReferences.resetForm();
                                        form.reset();
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
                                        dtReferences(param).init()
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
                _handlefvReferences();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvReferences.init();
    });

}

export function fvAccountSecurity(_table=false,form_id,param){

    var init_fvEmailAddress = (function () {

        var _handlefvEmailAddress = function(){
            let fvEmailAddress;
            let form = document.querySelector('#kt_signin_change_email');

            let card_id = '#card8';
            let cardContent = document.querySelector(`${card_id}`);

            let blockUI = KTBlockUI.getInstance(cardContent) ??new KTBlockUI(cardContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {

                fvEmailAddress = FormValidation.formValidation(form, {
                    fields: {
                        emailaddress: {
                            validators: {
                                notEmpty: {
                                    message: 'The email address is required'
                                },
                                emailAddress: {
                                    message: 'The input is not a valid email address'
                                }
                            }
                        },
                        // confirmemailpassword: {
                        //     validators: {
                        //         notEmpty: {
                        //             message: 'The password is required'
                        //         },
                        //         stringLength: {
                        //             min: 8,
                        //             message: 'The password must be at least 8 characters long'
                        //         },
                        //         regexp: {
                        //              regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/,
                        //             message: 'The password must contain uppercase, lowercase, a number, and a special character'
                        //         }
                        //     }
                        // }
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

            $(form).on('click','button.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvEmailAddress && fvEmailAddress.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("warning","Are you sure you want to update the email?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',param);
                                formData.append('tab',form_id);
                                formData.append('column','c_email');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvEmailAddress.resetForm();
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
                                    $('#kt_signin_cancel').click();
                                });
                            },
                        });
                    }
                })
            })
        }

        var _handlefvPassword = function(){
            let fvEmailAddress;
            let form = document.querySelector('#kt_signin_change_password');

            let card_id = '#card8';
            let cardContent = document.querySelector(`${card_id}`);

            let blockUI = KTBlockUI.getInstance(cardContent) ??new KTBlockUI(cardContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {

                fvEmailAddress = FormValidation.formValidation(form, {
                    fields: {
                        newpassword: {
                            validators: {
                                notEmpty: {
                                    message: 'The password is required'
                                },
                                stringLength: {
                                    min: 8,
                                    message: 'The password must be at least 8 characters long',
                                },
                                regexp: {
                                    regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
                                    message: 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
                                },
                            }
                        },
                        confirmpassword: {
                            validators: {
                                notEmpty: {
                                    message: 'The password is required'
                                },
                                identical: {
                                    compare: function () {
                                        return document.querySelector('[name="newpassword"]').value;
                                    },
                                    message: 'The password and its confirm password must match',
                                },
                            }
                        }
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

            $(form).on('click','button.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvEmailAddress && fvEmailAddress.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("warning","Are you sure you want to update the password?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',param);
                                formData.append('tab',form_id);
                                formData.append('column','password');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvEmailAddress.resetForm();
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
                                    $('#kt_password_cancel').click();
                                });
                            },
                        });
                    }
                })
            })
        }

        return {
            init: function () {
                _handlefvEmailAddress();
                _handlefvPassword();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvEmailAddress.init();
    });

}

export function fvFamilyBackground(_table=false,form_id,param){

    var init_fvFamilyBackground = (function () {

        var _handleSpouse = function(){
            let fvSpouse;
            let form = document.querySelector(`#form-spouse-details`);
            let card = form.closest('.card');

            let blockUI = new KTBlockUI(card, {
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

                    if (field.hasAttribute('data-accepted') && field.getAttribute('data-accepted') === 'pdf') {
                        validationRules[fieldName].validators ={
                            file: {
                                extension: 'pdf',
                                type: 'application/pdf',
                                message: 'Please upload a valid PDF file'
                            },
                            fileSize: {
                                maxSize: 20480 * 1024,
                                message: 'The file is too large. Maximum size allowed is 20 MB.'
                            },
                        };
                    }

                    if (field.hasAttribute('data-required') && field.getAttribute('data-required') === 'false') {
                        return;
                    }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                fvSpouse = FormValidation.formValidation(form, {
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

            $(card).on('click','button.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                fvSpouse.resetForm();
                $(form).find('input, select').prop('disabled', true);

                $(this).siblings('.edit').removeClass('d-none');
                $(this).siblings('.save').addClass('d-none');
                $(this).addClass('d-none');
            })

            $(card).on('click','button.save',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');

                fvSpouse && fvSpouse.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('tab',form_id);
                                formData.append('emp_id',param);
                                formData.append('column','spouse');
                                formData.append('id',_this.attr('data-id')??'');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        $(form).find('input, select').prop('disabled', true);
                                        _this.addClass('d-none');
                                        _this.siblings('.cancel').addClass('d-none');
                                        _this.siblings('.edit').removeClass('d-none');
                                        fvSpouse.resetForm();
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

            $(card).on('click','button.edit',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                const form = $(this).closest('.card').find('form');
                form.find('input, select').prop('disabled', false);
                $(this).addClass('d-none');
                $(this).siblings('.cancel, .save').removeClass('d-none');
            });
        }

        var _handleFather = function(){
            let fvFather;
            let form = document.querySelector(`#form-father-details`);
            let card = form.closest('.card');

            let blockUI = new KTBlockUI(card, {
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

                    if (field.hasAttribute('data-accepted') && field.getAttribute('data-accepted') === 'pdf') {
                        validationRules[fieldName].validators ={
                            file: {
                                extension: 'pdf',
                                type: 'application/pdf',
                                message: 'Please upload a valid PDF file'
                            },
                            fileSize: {
                                maxSize: 20480 * 1024,
                                message: 'The file is too large. Maximum size allowed is 20 MB.'
                            },
                        };
                    }

                    if (field.hasAttribute('data-required') && field.getAttribute('data-required') === 'false') {
                        return;
                    }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                fvFather = FormValidation.formValidation(form, {
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

            $(card).on('click','button.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                fvFather.resetForm();
                $(form).find('input, select').prop('disabled', true);

                $(this).siblings('.edit').removeClass('d-none');
                $(this).siblings('.save').addClass('d-none');
                $(this).addClass('d-none');
            })

            $(card).on('click','button.save',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');

                fvFather && fvFather.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('tab',form_id);
                                formData.append('emp_id',param);
                                formData.append('column','father');
                                formData.append('id',_this.attr('data-id')??'');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        $(form).find('input, select').prop('disabled', true);
                                        _this.addClass('d-none');
                                        _this.siblings('.cancel').addClass('d-none');
                                        _this.siblings('.edit').removeClass('d-none');
                                        fvFather.resetForm();
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

            $(card).on('click','button.edit',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                const form = $(this).closest('.card').find('form');
                form.find('input, select').prop('disabled', false);
                $(this).addClass('d-none');
                $(this).siblings('.cancel, .save').removeClass('d-none');
            });
        }

        var _handleMother = function(){
            let fvMother;
            let form = document.querySelector(`#form-mother-details`);
            let card = form.closest('.card');

            let blockUI = new KTBlockUI(card, {
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

                    if (field.hasAttribute('data-accepted') && field.getAttribute('data-accepted') === 'pdf') {
                        validationRules[fieldName].validators ={
                            file: {
                                extension: 'pdf',
                                type: 'application/pdf',
                                message: 'Please upload a valid PDF file'
                            },
                            fileSize: {
                                maxSize: 20480 * 1024,
                                message: 'The file is too large. Maximum size allowed is 20 MB.'
                            },
                        };
                    }

                    if (field.hasAttribute('data-required') && field.getAttribute('data-required') === 'false') {
                        return;
                    }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                fvMother = FormValidation.formValidation(form, {
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

            $(card).on('click','button.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                fvMother.resetForm();
                $(form).find('input, select').prop('disabled', true);

                $(this).siblings('.edit').removeClass('d-none');
                $(this).siblings('.save').addClass('d-none');
                $(this).addClass('d-none');
            })

            $(card).on('click','button.save',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');

                fvMother && fvMother.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('tab',form_id);
                                formData.append('emp_id',param);
                                formData.append('column','mother');
                                formData.append('id',_this.attr('data-id')??'');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        $(form).find('input, select').prop('disabled', true);
                                        _this.addClass('d-none');
                                        _this.siblings('.cancel').addClass('d-none');
                                        _this.siblings('.edit').removeClass('d-none');
                                        fvMother.resetForm();
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

            $(card).on('click','button.edit',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                const form = $(this).closest('.card').find('form');
                form.find('input, select').prop('disabled', false);
                $(this).addClass('d-none');
                $(this).siblings('.cancel, .save').removeClass('d-none');
            });
        }

        return {
            init: function () {
                _handleSpouse();
                _handleFather();
                _handleMother();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvFamilyBackground.init();
    });

}
