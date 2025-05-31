"use strict";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../../global.js"


export function fvNewItemGeneralDetails(_table=false,param=false){

    var init_fvNewItemGeneralDetails = (function () {

        var fvNewItemComputingDeviceDetails;
        var fvNewItemGeneralDetails;

        var _page = '.page-new-item';
        let cardContent = document.querySelector(`${_page}`);

        let blockUI = new KTBlockUI(cardContent, {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        });

        let _handlefvNewItemGeneralDetails = function(){
            let form = document.querySelector("#form-new-item-general-details");
            let card_id = '#card-new-item-general-details';

            if (!form.hasAttribute('data-fv-initialized')) {
                fvNewItemGeneralDetails = FormValidation.formValidation(form, {
                    fields: {
                        'name':fv_validator(),
                        'item_type':fv_validator(),
                        // 'item_brand':fv_validator(),
                        'price':fv_validator(),
                        'is_active':fv_validator(),
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

            $(card_id).on('change','select[name="item_type"]',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let request = new RequestHandler;
                let formData = new FormData;

                formData.append('id',$(this).val());
                request.post('file-maintenance/item/check-item-type',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));
                    if(payload.is_computing_device){
                        if(payload.type == 'laptop'){
                            fvNewItemComputingDeviceDetails.addField('laptop_model',fv_validator());
                            $('.laptop-details').removeClass('d-none');
                        }else{
                            if(fvNewItemComputingDeviceDetails.getFields().hasOwnProperty('laptop_model')){
                                fvNewItemComputingDeviceDetails.removeField('laptop_model');
                            }
                            $('.laptop-details').addClass('d-none');
                        }
                        $('textarea[name="description"]').parent().addClass('d-none');
                        $('#card-new-item-details').removeClass('d-none').find('.card-header .card-title').text(payload.name+' Details');
                        $('#form-new-item-details').attr('data-include-fv',true);
                        if(fvNewItemGeneralDetails.getFields().hasOwnProperty('description')){
                            fvNewItemGeneralDetails.removeField('description');
                        }
                    }else {
                        $('textarea[name="description"]').parent().removeClass('d-none');
                        $('#form-new-item-details').attr('data-include-fv',false);
                        $('#card-new-item-details').addClass('d-none').find('.card-header .card-title').text('Item Details');
                        fvNewItemGeneralDetails.addField('description',fv_validator());
                    }
                })

                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    //code here
                });

            })
        }

        let _handlefvNewItemComputingDeviceDetails = function(){
            let form = document.querySelector("#form-new-item-details");
            if(!form){ return; }

            if (!form.hasAttribute('data-fv-initialized')) {
                fvNewItemComputingDeviceDetails = FormValidation.formValidation(form, {
                    fields: {
                        'cpu':fv_validator(),
                        'device_name':fv_validator(),
                        'windows_version':fv_validator(),
                        'os_installed_date':fv_validator(),
                        'ram[0][ram]':fv_validator(),
                        'storage[0][storage]':fv_validator(),
                        'gpu[0][gpu]':fv_validator(),
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
                handleRamRepeater(fvNewItemComputingDeviceDetails);
                handleStorageRepeater(fvNewItemComputingDeviceDetails);
                handleGpuRepeater(fvNewItemComputingDeviceDetails);
                form.setAttribute('data-fv-initialized', 'true');
            }

        }

        function handleRamRepeater(fvNewItemComputingDeviceDetails)
        {
            $('.repeater-ram').repeater({
                initEmpty: false,
                defaultValues: { 'text-input': 'foo' },
                isFirstItemUndeletable: true,
                show: async function () {
                    const repeaterList = $(this).closest('[data-repeater-list]');
                    const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));
                    const ram = `ram[${currentIndex}][ram]`;
                    fvNewItemComputingDeviceDetails.addField(ram,fv_validator())
                    $(`select[name="${ram}"]`).select2();
                    $(this).slideDown();
                },

                hide: async function () {
                    $(this).find('[name]').each(function () {
                        const fieldName = $(this).attr('name');
                        if (fvNewItemComputingDeviceDetails.getElements(fieldName)) {
                            fvNewItemComputingDeviceDetails.removeField(fieldName);
                        }
                    });
                    $(this).slideUp();
                },
            });
            fvNewItemComputingDeviceDetails.addField('ram[0][ram]',fv_validator())
        }

        function handleStorageRepeater(fvNewItemComputingDeviceDetails)
        {
            $('.repeater-storage').repeater({
                initEmpty: false,
                defaultValues: { 'text-input': 'foo' },
                // isFirstItemUndeletable: true,
                show: async function () {
                    const repeaterList = $(this).closest('[data-repeater-list]');
                    const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));
                    const storage = `storage[${currentIndex}][storage]`;
                    fvNewItemComputingDeviceDetails.addField(storage,fv_validator())
                    $(`select[name="${storage}"]`).select2();

                    $(this).slideDown();
                },

                hide: async function () {
                    $(this).find('[name]').each(function () {
                        const fieldName = $(this).attr('name');
                        if (fvNewItemComputingDeviceDetails.getElements(fieldName)) {
                            fvNewItemComputingDeviceDetails.removeField(fieldName);
                        }
                    });
                    $(this).slideUp();
                },
            });
            fvNewItemComputingDeviceDetails.addField('storage[0][storage]',fv_validator())
        }

        function handleGpuRepeater(fvNewItemComputingDeviceDetails)
        {
            $('.repeater-gpu').repeater({
                initEmpty: false,
                defaultValues: { 'text-input': 'foo' },
                // isFirstItemUndeletable: true,
                show: async function () {
                    const repeaterList = $(this).closest('[data-repeater-list]');
                    const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));
                    const gpu = `gpu[${currentIndex}][gpu]`;
                    fvNewItemComputingDeviceDetails.addField(gpu,fv_validator())
                    $(`select[name="${gpu}"]`).select2();

                    $(this).slideDown();
                },

                hide: async function () {
                    $(this).find('[name]').each(function () {
                        const fieldName = $(this).attr('name');
                        if (fvNewItemComputingDeviceDetails.getElements(fieldName)) {
                            fvNewItemComputingDeviceDetails.removeField(fieldName);
                        }
                    });
                    $(this).slideUp();
                },
            });
            // fvNewItemComputingDeviceDetails.addField('gpu[0][gpu]',fv_validator())
        }

        async function formValidation() {
            let cardComputingDetails = $('#card-new-item-details');
            const includefvComputingDetails = (fvNewItemComputingDeviceDetails && typeof fvNewItemComputingDeviceDetails.validate === 'function')
                                           && (!cardComputingDetails.hasClass('d-none') && cardComputingDetails.find('#form-new-item-details').attr('data-fv-include')!=false);

            const results = await Promise.all([
                includefvComputingDetails ?fvNewItemComputingDeviceDetails.validate() : 'Valid',
                fvNewItemGeneralDetails.validate(),
            ]);
            return results.every(result => result === 'Valid');
        }

        function handleConstructForm()
        {
            let formGeneralDetails = document.querySelector("#form-new-item-general-details");
            let includefvComputingDetails = (fvNewItemComputingDeviceDetails && typeof fvNewItemComputingDeviceDetails.validate === 'function') && (!$('#card-new-item-details').hasClass('d-none'));

            const combinedFormData = new FormData(formGeneralDetails);

            if(includefvComputingDetails){
                let formComputingDeviceDetails =  document.querySelector("#form-new-item-details");
                const ram = [];
                $('[data-repeater-list="ram"] [data-repeater-item]:visible').each(function () {
                    const id = $(this).find('[name$="[ram]"]').val();
                    ram.push({
                        id: id,
                    });
                });
                combinedFormData.append('ram', JSON.stringify(ram));

                const storage = [];
                $('[data-repeater-list="storage"] [data-repeater-item]:visible').each(function () {
                    const id = $(this).find('[name$="[storage]"]').val();
                    storage.push({
                        id: id,
                    });
                });
                combinedFormData.append('storage', JSON.stringify(storage));

                const gpu = [];
                $('[data-repeater-list="gpu"] [data-repeater-item]:visible').each(function () {
                    const id = $(this).find('[name$="[gpu]"]').val() ?? '';
                    gpu.push({
                        id: id,
                    });
                });
                combinedFormData.append('gpu', JSON.stringify(gpu));
                combinedFormData.append('cpu', formComputingDeviceDetails.querySelector("input[name='cpu']").value);
                combinedFormData.append('laptop_model', formComputingDeviceDetails.querySelector("input[name='laptop_model']").value);
                combinedFormData.append('device_name', formComputingDeviceDetails.querySelector("input[name='device_name']").value);
                combinedFormData.append('os_installed_date', formComputingDeviceDetails.querySelector("input[name='os_installed_date']").value);
                combinedFormData.append('windows_version', formComputingDeviceDetails.querySelector("input[name='windows_version']").value);
            }

            return combinedFormData;
        }

        $(_page).on('click','.submit-new-item',async function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let _this = $(this);

            let isValid = await formValidation();
            if (!isValid) {
                return;
            }

            _this.attr("disabled",true);

            Alert.confirm("question","Do you want to submit the new item ?", {
                onConfirm:()=>{

                    blockUI.block();

                    let formData = handleConstructForm();
                    let _request = new RequestHandler;
                    _request.post('file-maintenance/item/new-item',formData).then((res) => {
                        if(res.status == 'success'){
                            Alert.toast(res.status,res.message);
                        }else{
                            Alert.alert('error',"Something went wrong. Try again later", false);
                        }
                    })
                    .catch((error) => {
                        console.log(error)
                        Alert.alert('error',"Something went wrong. Try again later", false);
                    })
                    .finally(() => {
                        _this.attr("disabled",false);
                        blockUI.release();
                    });

                },
                onCancel:()=>{
                    _this.attr("disabled",false);
                }
            });

        })

        return {
            init: function () {
                _handlefvNewItemGeneralDetails();
                _handlefvNewItemComputingDeviceDetails();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {

        init_fvNewItemGeneralDetails.init();

    });


}
