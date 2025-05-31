"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../global.js"
import { dtIssuedItems } from "../../dt_controller/accountability/issued_items.js";
import { dtIssuedTo } from "../../dt_controller/accountability/accountable_to.js";

// export function fvSystemUnitDetails(_table=false,param=false){

//     var init_fvSystemUnitDetails = (function () {

//         var fvSystemUnitDetails;

//         var _handlefvAccountability = function(){
//             const _request = new RequestHandler;

//             let form = document.querySelector("#form-edit-system-unit");
//             let url = form.getAttribute('action');

//             let modal_id = form.getAttribute('modal-id');
//             let modalContent = document.querySelector(`${modal_id} .modal-content`);

//             let blockUI = new KTBlockUI(modalContent, {
//                 message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
//             });

//             if (!form.hasAttribute('data-fv-initialized')) {
//                 fvSystemUnitDetails = FormValidation.formValidation(form, {
//                     fields: {
//                         'item':fv_validator(),
//                         'cpu':fv_validator(),
//                         'device_name':fv_validator(),
//                         'windows_version':fv_validator(),
//                         'os_installed_date':fv_validator(),
//                     },
//                     plugins: {
//                     trigger: new FormValidation.plugins.Trigger(),
//                     bootstrap: new FormValidation.plugins.Bootstrap5({
//                         rowSelector: ".fv-row",
//                         eleInvalidClass: "",
//                         eleValidClass: "",
//                     }),
//                     },
//                 })
//                 handleRamRepeater(fvSystemUnitDetails);
//                 handleStorageRepeater(fvSystemUnitDetails);
//                 handleGpuRepeater(fvSystemUnitDetails);
//                 form.setAttribute('data-fv-initialized', 'true');
//             }

//             $(modal_id).on('click','.cancel',function(e){
//                 e.preventDefault()
//                 e.stopImmediatePropagation()
//                 Alert.confirm('question',"Close this form ?",{
//                     onConfirm: () => {
//                         modal_state(modal_id);
//                         $(modal_id).remove();
//                     }
//                 })
//             })

//             $(modal_id).on('click','.submit',function(e){
//                 e.preventDefault()
//                 e.stopImmediatePropagation()

//                 let _this = $(this);
//                 let url = form.getAttribute('action');

//                 fvSystemUnitDetails && fvSystemUnitDetails.validate().then(function (v) {
//                     if(v == "Valid"){
//                         Alert.confirm("question","Submit this form?", {
//                             onConfirm: function() {
//                                 blockUI.block();
//                                 _this.attr("data-kt-indicator","on");
//                                 _this.attr("disabled",true);

//                                 let formData = handleConstructForm(form);
//                                 formData.append('encrypted_id',_this.attr('data-id'));
//                                 formData.append('update_type','system unit');
//                                 _request.post(url,formData).then((res) => {
//                                     Alert.toast(res.status,res.message);
//                                     if(res.status == 'success'){
//                                         fvSystemUnitDetails.resetForm();
//                                         modal_state(modal_id);
//                                     }
//                                 })
//                                 .catch((error) => {
//                                     console.log(error)
//                                     Alert.alert('error',"Something went wrong. Try again later", false);
//                                 })
//                                 .finally(() => {
//                                     _this.attr("data-kt-indicator","off");
//                                     _this.attr("disabled",false);
//                                     blockUI.release();
//                                     $(modal_id).remove();
//                                     dtIssuedItems('issued-item',param).init();
//                                 });
//                             },
//                         });
//                     }
//                 })
//             })

//         }

//         function handleRamRepeater(fvSystemUnitDetails)
//         {
//             $('.repeater-ram').repeater({
//                 initEmpty: false,
//                 defaultValues: { 'text-input': 'foo' },
//                 // isFirstItemUndeletable: true,
//                 show: async function () {
//                     const repeaterList = $(this).closest('[data-repeater-list]');
//                     const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));
//                     const ram = `ram[${currentIndex}][ram]`;
//                     fvSystemUnitDetails.addField(ram,fv_validator())
//                     $(this).slideDown();
//                 },

//                 hide: async function () {
//                     $(this).find('[name]').each(function () {
//                         const fieldName = $(this).attr('name');
//                         if (fvSystemUnitDetails.getElements(fieldName)) {
//                             fvSystemUnitDetails.removeField(fieldName);
//                         }
//                     });
//                     $(this).slideUp();
//                 },
//             });
//         }

//         function handleStorageRepeater(fvSystemUnitDetails)
//         {
//             console.log(123)
//             $('.repeater-storage').repeater({
//                 initEmpty: false,
//                 defaultValues: { 'text-input': 'foo' },
//                 // isFirstItemUndeletable: true,
//                 show: async function () {
//                     const repeaterList = $(this).closest('[data-repeater-list]');
//                     const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));
//                     const storage = `storage[${currentIndex}][storage]`;
//                     fvSystemUnitDetails.addField(storage,fv_validator())
//                     $(this).slideDown();
//                 },

//                 hide: async function () {
//                     $(this).find('[name]').each(function () {
//                         const fieldName = $(this).attr('name');
//                         if (fvSystemUnitDetails.getElements(fieldName)) {
//                             fvSystemUnitDetails.removeField(fieldName);
//                         }
//                     });
//                     $(this).slideUp();
//                 },
//             });

//         }

//         function handleGpuRepeater(fvSystemUnitDetails)
//         {
//             $('.repeater-gpu').repeater({
//                 initEmpty: false,
//                 defaultValues: { 'text-input': 'foo' },
//                 // isFirstItemUndeletable: true,
//                 show: async function () {
//                     const repeaterList = $(this).closest('[data-repeater-list]');
//                     const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));
//                     const gpu = `gpu[${currentIndex}][gpu]`;
//                     fvSystemUnitDetails.addField(gpu,fv_validator())
//                     $(this).slideDown();
//                 },

//                 hide: async function () {
//                     $(this).find('[name]').each(function () {
//                         const fieldName = $(this).attr('name');
//                         if (fvSystemUnitDetails.getElements(fieldName)) {
//                             fvSystemUnitDetails.removeField(fieldName);
//                         }
//                     });
//                     $(this).slideUp();
//                 },
//             });
//         }

//         function handleConstructForm(form)
//         {
//             const combinedFormData = new FormData(form);

//             const ram = [];
//             $('[data-repeater-list="ram"] [data-repeater-item]:visible').each(function () {
//                 const id = $(this).find('[name$="[ram]"]').val();
//                 const serial_number = $(this).find('[name$="[serial_number]"]').val();

//                 ram.push({
//                     id: id,
//                     serial_number: serial_number
//                 });
//             });
//             combinedFormData.append('ram', JSON.stringify(ram));

//             const storage = [];
//             $('[data-repeater-list="storage"] [data-repeater-item]:visible').each(function () {
//                 const id = $(this).find('[name$="[storage]"]').val();
//                 const serial_number = $(this).find('[name$="[serial_number]"]').val();

//                 storage.push({
//                     id: id,
//                     serial_number: serial_number
//                 });
//             });
//             combinedFormData.append('storage', JSON.stringify(storage));

//             const gpu = [];
//             $('[data-repeater-list="gpu"] [data-repeater-item]:visible').each(function () {
//                 const id = $(this).find('[name$="[gpu]"]').val();
//                 const serial_number = $(this).find('[name$="[serial_number]"]').val();

//                 gpu.push({
//                     id: id,
//                     serial_number: serial_number
//                 });
//             });
//             combinedFormData.append('gpu', JSON.stringify(gpu));

//             return combinedFormData;
//         }


//         return {
//             init: function () {
//                 _handlefvAccountability();
//             },
//         };

//     })();

//     KTUtil.onDOMContentLoaded(function () {
//         init_fvSystemUnitDetails.init();
//     });

// }


// export function fvOtherItemDetails(_table=false,param=false){

//     var init_fvOtherItemDetails = (function () {

//         var _handlefvOtherItemDetails = function(){

//             let fv;
//             const _request = new RequestHandler;

//             let form = document.querySelector("#form-edit-other-item");
//             let url = form.getAttribute('action');

//             let modal_id = form.getAttribute('modal-id');
//             let modalContent = document.querySelector(`${modal_id} .modal-content`);

//             let blockUI = new KTBlockUI(modalContent, {
//                 message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
//             });

//             if (!form.hasAttribute('data-fv-initialized')) {
//                 fv = FormValidation.formValidation(form, {
//                     fields: {
//                         'item':fv_validator(),
//                         'description':fv_validator(),
//                     },
//                     plugins: {
//                     trigger: new FormValidation.plugins.Trigger(),
//                     bootstrap: new FormValidation.plugins.Bootstrap5({
//                         rowSelector: ".fv-row",
//                         eleInvalidClass: "",
//                         eleValidClass: "",
//                     }),
//                     },
//                 })
//                 form.setAttribute('data-fv-initialized', 'true');
//             }

//             $(modal_id).on('click','.cancel',function(e){
//                 e.preventDefault()
//                 e.stopImmediatePropagation()
//                 Alert.confirm('question',"Close this form ?",{
//                     onConfirm: () => {
//                         modal_state(modal_id);
//                         $(modal_id).remove();
//                     }
//                 })
//             })

//             $(modal_id).on('click','.submit',function(e){
//                 e.preventDefault()
//                 e.stopImmediatePropagation()

//                 let _this = $(this);
//                 let url = form.getAttribute('action');
//                 fv && fv.validate().then(function (v) {
//                     if(v == "Valid"){
//                         Alert.confirm("question","Submit this form?", {
//                             onConfirm: function() {
//                                 blockUI.block();
//                                 _this.attr("data-kt-indicator","on");
//                                 _this.attr("disabled",true);

//                                 let formData = new FormData(form);
//                                 formData.append('encrypted_id',_this.attr('data-id'));
//                                 formData.append('update_type','other_item');
//                                 _request.post(url,formData).then((res) => {
//                                     Alert.toast(res.status,res.message);
//                                     if(res.status == 'success'){
//                                         fv.resetForm();
//                                         modal_state(modal_id);
//                                     }
//                                 })
//                                 .catch((error) => {
//                                     console.log(error)
//                                     Alert.alert('error',"Something went wrong. Try again later", false);
//                                 })
//                                 .finally(() => {
//                                     _this.attr("data-kt-indicator","off");
//                                     _this.attr("disabled",false);
//                                     blockUI.release();
//                                     $(modal_id).remove();
//                                     dtIssuedItems('issued-item',param).init();
//                                 });
//                             },
//                         });
//                     }
//                 })
//             })


//         }

//         return {
//             init: function () {
//                 _handlefvOtherItemDetails();
//             },
//         };

//     })();

//     KTUtil.onDOMContentLoaded(function () {
//         init_fvOtherItemDetails.init();
//     });


// }


export function fvOtherAccountabilityDetails(_table=false,param=false){

    var init_fvOtherAccountabilityDetails = (function () {

        var _handlefvAccountabilityDetails = function(){

            let fv;
            const _request = new RequestHandler;

            let form = document.querySelector("#form-accountability-details");

            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fv = FormValidation.formValidation(form, {
                    fields: {
                        'form_no':fv_validator(),
                        'date_issued':fv_validator(),
                        'accountability_status':fv_validator(),
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
                let url = '/accountability-details/update_accountability';
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

        var _handlefvOtherAccountabilityDetails = function(){

            let fv;
            const _request = new RequestHandler;

            let form = document.querySelector("#form-edit-other-details");
            let url = form.getAttribute('action');

            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fv = FormValidation.formValidation(form, {
                    fields: {
                        'issued_at':fv_validator(),
                        'status':fv_validator(),
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
                let action = form.getAttribute('action');
                let url = '/accountability-details/'+'update-'+action;
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
                                        form.setAttribute('action','')
                                        $('.other-details').empty();
                                    }
                                    if(action === 'issued-items'){
                                        dtIssuedItems('issued-item',param).init();
                                    }else if(action === 'issued-to'){
                                        dtIssuedTo('issued-to',param).init();
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

        return {
            init: function () {
                _handlefvAccountabilityDetails();
                _handlefvOtherAccountabilityDetails();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvOtherAccountabilityDetails.init();
    });


}
