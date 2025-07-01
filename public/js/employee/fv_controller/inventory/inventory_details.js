"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../global.js"

export function fvSystemUnitDetails(_table=false,param=false){

    var init_fvSystemUnitDetails = (function () {

        var fvSystemUnitDetails;

        var _handlefvSystemUnitDetails = function(){
            const _request = new RequestHandler;

            let form = document.querySelector("#form-item-details");
            if(!form){ return; }
            let url = form.getAttribute('action');

            let card_id = '#card-item-details';
            let cardContent = document.querySelector(`${card_id}`);

            let blockUI = new KTBlockUI(cardContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvSystemUnitDetails = FormValidation.formValidation(form, {
                    fields: {
                        'cpu':fv_validator(),
                        'device_name':fv_validator(),
                        'windows_version':fv_validator(),
                        'os_installed_date':fv_validator(),
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
                handleRamRepeater(fvSystemUnitDetails);
                handleStorageRepeater(fvSystemUnitDetails);
                handleGpuRepeater(fvSystemUnitDetails);
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

                fvSystemUnitDetails && fvSystemUnitDetails.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);

                                let formData = handleConstructForm(form);
                                formData.append('encrypted_id',param);
                                _request.post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);

                                    if(res.status == 'success'){
                                        fvSystemUnitDetails.resetForm();
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

        }

        function handleRamRepeater(fvSystemUnitDetails)
        {
            $('.repeater-ram').repeater({
                initEmpty: false,
                defaultValues: { 'text-input': 'foo' },
                // isFirstItemUndeletable: true,
                show: async function () {
                    const repeaterList = $(this).closest('[data-repeater-list]');
                    const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));
                    const ram = `ram[${currentIndex}][ram]`;
                    fvSystemUnitDetails.addField(ram,fv_validator())
                    $(this).slideDown();
                },

                hide: async function () {
                    let formData = new FormData();
                    let _request = new RequestHandler;
                    let _this = $(this);

                    if(_this.find('[name]').val() == '' || _this.find('[name]').val()==null){
                        _this.slideUp();
                        return;
                    }

                    Alert.input('info','Remove ram ?',{
                    isRequired: true,
                    inputPlaceholder: "Put your reason for removing this item",
                        onConfirm: function(remarks) {
                            formData.append('encrypted_id',param);
                            formData.append('remarks',remarks);
                            formData.append('ram_id',_this.find('[name]').val());
                            _request.post('/inventory-details/update-ram',formData)
                            .then((res) => {
                                Alert.toast(res.status,res.message);
                                if(res.status =='success'){
                                    _this.find('[name]').each(function () {
                                    const fieldName = _this.attr('name');
                                    if (fvSystemUnitDetails.getElements(fieldName)) {
                                        fvSystemUnitDetails.removeField(fieldName);
                                    }
                                    });
                                    _this.slideUp();
                                }

                            })
                            .catch((error) => {
                                Alert.alert('error', "Something went wrong. Try again later", false);
                            })
                            .finally((error) => {
                            });
                        }
                    });
                },
            });
        }

        function handleStorageRepeater(fvSystemUnitDetails)
        {
            $('.repeater-storage').repeater({
                initEmpty: false,
                defaultValues: { 'text-input': 'foo' },
                // isFirstItemUndeletable: true,
                show: async function () {
                    const repeaterList = $(this).closest('[data-repeater-list]');
                    const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));
                    const storage = `storage[${currentIndex}][storage]`;
                    fvSystemUnitDetails.addField(storage,fv_validator())
                    $(this).slideDown();
                },

                hide: async function () {
                    let formData = new FormData();
                    let _request = new RequestHandler;
                    let _this = $(this);

                    if(_this.find('[name]').val() == '' || _this.find('[name]').val()==null){
                        _this.slideUp();
                        return;
                    }

                    Alert.input('info','Remove Storage ?',{
                    isRequired: true,
                    inputPlaceholder: "Put your reason for removing this item",
                        onConfirm: function(remarks) {
                            formData.append('encrypted_id',param);
                            formData.append('remarks',remarks);
                            formData.append('storage_id',_this.find('[name]').val());
                            _request.post('/inventory-details/update-storage',formData)
                            .then((res) => {
                                Alert.toast(res.status,res.message);
                                if(res.status =='success'){
                                    _this.find('[name]').each(function () {
                                    const fieldName = _this.attr('name');
                                    if (fvSystemUnitDetails.getElements(fieldName)) {
                                        fvSystemUnitDetails.removeField(fieldName);
                                    }
                                    });
                                    _this.slideUp();
                                }

                            })
                            .catch((error) => {
                                Alert.alert('error', "Something went wrong. Try again later", false);
                            })
                            .finally((error) => {
                            });
                        }
                    });
                },
            });

        }

        function handleGpuRepeater(fvSystemUnitDetails)
        {
            $('.repeater-gpu').repeater({
                initEmpty: false,
                defaultValues: { 'text-input': 'foo' },
                // isFirstItemUndeletable: true,
                show: async function () {
                    const repeaterList = $(this).closest('[data-repeater-list]');
                    const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));
                    const gpu = `gpu[${currentIndex}][gpu]`;
                    fvSystemUnitDetails.addField(gpu,fv_validator())
                    $(this).slideDown();
                },

                hide: async function () {
                    let formData = new FormData();
                    let _request = new RequestHandler;
                    let _this = $(this);

                    if(_this.find('[name]').val() == '' || _this.find('[name]').val()==null){
                        _this.slideUp();
                        return;
                    }

                    Alert.input('info','Remove Video Card ?',{
                    isRequired: true,
                    inputPlaceholder: "Put your reason for removing this item",
                        onConfirm: function(remarks) {
                            formData.append('encrypted_id',param);
                            formData.append('remarks',remarks);
                            formData.append('gpu_id',_this.find('[name]').val());
                            _request.post('/inventory-details/update-gpu',formData)
                            .then((res) => {
                                Alert.toast(res.status,res.message);
                                if(res.status =='success'){
                                    _this.find('[name]').each(function () {
                                    const fieldName = _this.attr('name');
                                    if (fvSystemUnitDetails.getElements(fieldName)) {
                                        fvSystemUnitDetails.removeField(fieldName);
                                    }
                                    });
                                    _this.slideUp();
                                }

                            })
                            .catch((error) => {
                                Alert.alert('error', "Something went wrong. Try again later", false);
                            })
                            .finally((error) => {
                            });
                        }
                    });
                },
            });
        }

        function handleConstructForm(form)
        {
            const combinedFormData = new FormData(form);

            const ram = [];
            $('[data-repeater-list="ram"] [data-repeater-item]:visible').each(function () {
                const id = $(this).find('[name$="[ram]"]').val();
                const serial_number = $(this).find('[name$="[serial_number]"]').val();

                ram.push({
                    id: id,
                    serial_number: serial_number
                });
            });
            combinedFormData.append('ram', JSON.stringify(ram));

            const storage = [];
            $('[data-repeater-list="storage"] [data-repeater-item]:visible').each(function () {
                const id = $(this).find('[name$="[storage]"]').val();
                const serial_number = $(this).find('[name$="[serial_number]"]').val();

                storage.push({
                    id: id,
                    serial_number: serial_number
                });
            });
            combinedFormData.append('storage', JSON.stringify(storage));

            const gpu = [];
            $('[data-repeater-list="gpu"] [data-repeater-item]:visible').each(function () {
                const id = $(this).find('[name$="[gpu]"]').val();
                const serial_number = $(this).find('[name$="[serial_number]"]').val();

                gpu.push({
                    id: id,
                    serial_number: serial_number
                });
            });
            combinedFormData.append('gpu', JSON.stringify(gpu));

            return combinedFormData;
        }


        return {
            init: function () {
                _handlefvSystemUnitDetails();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvSystemUnitDetails.init();
    });

}


export function fvGeneralDetails(_table=false,param=false){

    var init_fvGeneralDetails = (function () {

        var _handlefvGeneralDetails = function(){

            let fv;
            const _request = new RequestHandler;

            let form = document.querySelector("#form-general-details");
            // let url = form.getAttribute('action');

            let card_id = '#card-general-details';
            let cardContent = document.querySelector(`${card_id}`);

            let blockUI = new KTBlockUI(cardContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fv = FormValidation.formValidation(form, {
                    fields: {
                        'name':fv_validator(),
                        // 'item_type':fv_validator(),
                        // 'item_brand':fv_validator(),
                        // 'serial_number':fv_validator(),
                        // 'received_at':fv_validator(),
                        // 'price':fv_validator(),
                        'received_by':fv_validator(),
                        'company_location':fv_validator(),
                        // 'supplier':fv_validator(),
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

                if ($('textarea[name="description"]').length) {
                    // Only add the field if it exists and not yet added
                    if (!fv.getFields().hasOwnProperty('description')) {
                        fv.addField('description',fv_validator());
                    }
                }
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
                                formData.append('encrypted_id',param);

                                _request.post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fv.resetForm();
                                        if($('select[name="status"]').val() == '0'){
                                            form.querySelectorAll("input, select, textarea").forEach(el => {
                                                el.disabled = true;
                                            });
                                            const formSystemUnit = document.querySelector("#form-item-details");
                                            if (formSystemUnit) {
                                                formSystemUnit.querySelectorAll("input, select, textarea").forEach(el => {
                                                    el.disabled = true;
                                                });
                                                formSystemUnit.querySelectorAll("button[data-repeater-create], button[data-repeater-delete]").forEach(btn => {
                                                    btn.classList.add("d-none");
                                                });
                                            }
                                            $('button.submit').addClass('d-none');
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
                                    setTimeout(() => {
                                        blockUI.release();
                                    }, 500);
                                });
                            },
                        });
                    }
                })
            })


        }

        return {
            init: function () {
                _handlefvGeneralDetails();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvGeneralDetails.init();
    });


}
