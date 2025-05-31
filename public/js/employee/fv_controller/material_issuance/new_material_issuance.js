"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../global.js"
import { get_employee } from "../../../global/select.js";

export var fvNewMaterialIssuanceController =  function (page,param) {

    const _page = $('.page-new-material-issuance');
    const _request = new RequestHandler;

    var fvEmployeeDetails='';
    var fvIssuedItem ='';
    var fvAccountabilityDetails ='';

    var formIssuedItem = document.querySelector(".repeater-issued-item");
    var formEmployeeDetails = document.querySelector(".repeater-issued-to");
    var formAccountabilityDetails = document.querySelector(".material-issuance-details");

    var submitForm = $('.submit');

    async function _fvMaterialIssuanceDetails()
    {

        if (formAccountabilityDetails.hasAttribute('data-fv-initialized')) {
            return;
        }

        fvAccountabilityDetails = FormValidation.formValidation(formAccountabilityDetails, {
            fields: {
                'mrs_no':fv_validator(),
                'form_no':fv_validator(),
                'issued_by':fv_validator(),
                'issued_at':fv_validator(),
                'received_by':fv_validator(),
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
        formAccountabilityDetails.setAttribute('data-fv-initialized', 'true');
    }

    async function _fvIssuedItem()
    {
        $('.repeater-issued-item').repeater({
            initEmpty: false,
            defaultValues: { 'text-input': 'foo' },
            isFirstItemUndeletable: true,
            show: async function () {

                const repeaterList = $(this).closest('[data-repeater-list]');
                const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));
                const acccessories = `issued-item[${currentIndex}][accessories]`;
                const quantity = `issued-item[${currentIndex}][quantity]`;

                fvIssuedItem.addField(acccessories, {validators: {
                            notEmpty:{
                                message:'This field is required'
                            },
                            callback: {
                                message: 'Each item must be unique',
                                callback: function(input) {
                                    const $current = $(input.element);
                                    const currentValue = input.value;

                                    // collect all accessory <select>s
                                    const allSelects = formIssuedItem.querySelectorAll('select[name*="[accessories]"]');
                                    const values = Array.from(allSelects, el => el.value);
                                    const count = values.filter(v => v === currentValue).length;

                                    if(currentValue == ''){
                                        return true;
                                    }
                                    return count === 1;
                                }
                            }
                        },
                    }
                )
                fvIssuedItem.addField(quantity,{validators: {
                            notEmpty:{
                                message:'This field is required'
                            },
                            integer: {
                                message: 'Quantity must be a number',
                            },
                            greaterThan: {
                                message: 'Must be at least 1',
                                min: 1
                            },
                            callback: {
                                message: 'Please select an item first',
                                callback: function(input) {
                                    // `input.element` is the actual <input> DOM node
                                    const $quantity = $(input.element);

                                    // 1) Find the repeater row
                                    const $repeaterRow = $quantity.closest('[data-repeater-item]');

                                    // 2) Within that row, find the accessory <select> (use its real name or class)
                                    const itemId = $repeaterRow
                                        .find('select[name*="[accessories]"], .form-accessories')
                                        .val() || '';

                                    // 3) If no accessory is selected yet, fail validation
                                    if (! itemId) {
                                        return false;
                                    }

                                    // 4) Otherwise it’s valid—allow other validators (e.g. remote) to run
                                    return true;
                                }
                            },
                            remote: {
                                url: '/material-issuance/check-item-quantity',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: function(validator, $field, value) {
                                    const $quantity = $(validator.element);

                                    // 1) Find the repeater row
                                    const $repeaterRow = $quantity.closest('[data-repeater-item]');

                                    // 2) Within that row, find the accessory <select> (use its real name or class)
                                    const itemId = $repeaterRow
                                        .find('select[name*="[accessories]"], .form-accessories')
                                        .val() || '';

                                    if (! itemId) {
                                        $(`input[name="${validator.field}"]`).val('');111
                                        return false;
                                    }

                                    return {
                                        item_id: itemId,
                                        quantity: validator.value,
                                    };
                                }
                            }
                        },
                    }
                )

                $(`select[name="${acccessories}"]`).select2({
                    width: '100%',
                });

                repeaterList.find('[data-repeater-delete]').parent().removeClass('d-none');
                $(this).slideDown();
            },

            hide: async function () {
                $(this).find('[name]').each(function () {
                    const fieldName = $(this).attr('name');
                    if (fvIssuedItem.getElements(fieldName)) {
                        fvIssuedItem.removeField(fieldName);
                    }
                });
                $(this).slideUp();

            },
        });

        let selectAccessories = $(`select[name="issued-item[0][accessories]"]`);
        selectAccessories.select2();

        if (formIssuedItem.hasAttribute('data-fv-initialized')) {
            return;
        }

        fvIssuedItem = FormValidation.formValidation(formIssuedItem, {
            fields: {
                'issued-item[0][accessories]':{
                    validators: {
                        notEmpty:{
                            message:'This field is required'
                        },
                        callback: {
                            message: 'Each item must be unique',
                            callback: function(input) {
                                const $current = $(input.element);
                                const currentValue = $current.val();

                                const allSelects = formIssuedItem.querySelectorAll('select[name*="[accessories]"]');
                                const values = Array.from(allSelects, el => el.value);
                                const count = values.filter(v => v === currentValue).length;

                                if(currentValue == ''){
                                    return true;
                                }

                                return count === 1;
                            }
                        }
                    },
                },
                'issued-item[0][quantity]':{
                    validators: {
                        notEmpty:{
                            message:'This field is required'
                        },
                        integer: {
                            message: 'Quantity must be a number',
                        },
                        greaterThan: {
                            message: 'Must be at least 1',
                            min: 1
                        },
                        callback: {
                            message: 'Please select an item first',
                            callback: function(input) {
                                // `input.element` is the actual <input> DOM node
                                const $quantity = $(input.element);

                                // 1) Find the repeater row
                                const $repeaterRow = $quantity.closest('[data-repeater-item]');

                                // 2) Within that row, find the accessory <select> (use its real name or class)
                                const itemId = $repeaterRow
                                    .find('select[name*="[accessories]"], .form-accessories')
                                    .val() || '';

                                // 3) If no accessory is selected yet, fail validation
                                if (! itemId) {
                                    return false;
                                }

                                // 4) Otherwise it’s valid—allow other validators (e.g. remote) to run
                                return true;
                            }
                        },
                        remote: {
                            url: '/material-issuance/check-item-quantity',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: function(validator, $field, value) {
                                const $quantity = $(validator.element);

                                // 1) Find the repeater row
                                const $repeaterRow = $quantity.closest('[data-repeater-item]');

                                // 2) Within that row, find the accessory <select> (use its real name or class)
                                const itemId = $repeaterRow
                                    .find('select[name*="[accessories]"], .form-accessories')
                                    .val() || '';

                                if (! itemId) {
                                    $(`input[name="${validator.field}"]`).val('');111
                                    return false;
                                }

                                return {
                                    item_id: itemId,
                                    quantity: validator.value,
                                };
                            }
                        }
                    },
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
        formIssuedItem.setAttribute('data-fv-initialized', 'true');
    }

    async function _fvIssuedTo()
    {
        $('.repeater-issued-to').repeater({
            initEmpty: false,
            defaultValues: { 'text-input': 'foo' },
            isFirstItemUndeletable: true,
            show: async function () {
                const repeaterList = $(this).closest('[data-repeater-list]');
                const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));

                const employee = `issued-to[${currentIndex}][employee]`;

                fvEmployeeDetails.addField(employee,
                    {
                        validators: {
                            notEmpty:{
                                message:'This field is required'
                            },
                            callback: {
                                message: 'Duplicate employee found',
                                callback: function(input) {
                                    const $current = $(input.element);
                                    const currentValue = input.value;

                                    // collect all accessory <select>s
                                    const allSelects = formEmployeeDetails.querySelectorAll('select[name*="[employee]"]');
                                    const values = Array.from(allSelects, el => el.value);
                                    const count = values.filter(v => v === currentValue).length;

                                    if(currentValue == ''){
                                        return true;
                                    }

                                    return count === 1;
                                }
                            }
                        },
                    }
                )

                // await get_employee(`select[name="${employee}"]`,``,1);
                $(`select[name="${employee}"]`).select2({
                    width: '100%',
                });

                repeaterList.find('[data-repeater-delete]').parent().removeClass('d-none');
                $(this).slideDown();
            },

            hide: async function () {
                $(this).find('[name]').each(function () {
                    const fieldName = $(this).attr('name');
                    if (fvEmployeeDetails.getElements(fieldName)) {
                        fvEmployeeDetails.removeField(fieldName);
                    }
                });
                $(this).slideUp();

            },
        });

        // await get_employee(`select[name="issued-to[0][employee]"]`,``,1);

        if (formEmployeeDetails.hasAttribute('data-fv-initialized')) {
            return;
        }

        fvEmployeeDetails = FormValidation.formValidation(formEmployeeDetails, {
            fields: {
                'issued-to[0][employee]':{
                    validators: {
                        notEmpty:{
                            message:'This field is required'
                        },
                        callback: {
                            message: 'Duplicate employee found',
                            callback: function(input) {
                                const $current = $(input.element);
                                const currentValue = input.value;

                                const allSelects = formEmployeeDetails.querySelectorAll('select[name*="[employee]"]');
                                const values = Array.from(allSelects, el => el.value);
                                const count = values.filter(v => v === currentValue).length;

                                if(currentValue == ''){
                                    return true;
                                }

                                return count === 1;
                            }
                        }
                    },
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

        formEmployeeDetails.setAttribute('data-fv-initialized', 'true');
    }

    async function formValidation() {
        const results = await Promise.all([
            fvIssuedItem.validate(),
            fvEmployeeDetails.validate(),
            fvAccountabilityDetails.validate(),
        ]);
        return results.every(result => result === 'Valid');
    }

    function buildFormData()
    {
        const combinedFormData = new FormData(formAccountabilityDetails);

        const issuedItem = [];
        $('[data-repeater-list="issued-item"] [data-repeater-item]:visible').each(function () {
            const accessories = $(this).find('[name$="[accessories]"]').val();
            const quantity = $(this).find('[name$="[quantity]"]').val();

            issuedItem.push({
                id: accessories,
                quantity: quantity
            });
        });
        combinedFormData.append('issued_item', JSON.stringify(issuedItem));

        const issuedTo = [];
        $('[data-repeater-list="issued-to"] [data-repeater-item]:visible').each(function () {
            const employee = $(this).find('[name$="[employee]"]').val();

            issuedTo.push({
                id: employee,
            });
        });
        combinedFormData.append('issued_to', JSON.stringify(issuedTo));

        return combinedFormData;
    }

    $(async function () {

        await _fvMaterialIssuanceDetails();
        await _fvIssuedItem();
        await _fvIssuedTo();

        submitForm.click(async function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            //validate form
            let isValid = await formValidation();
            if (!isValid) {
                return;
            }

            let _this = $(this);
            _this.attr("disabled",true);

            Alert.confirm("question","Do you want to submit this Material Issuance Form ?", {
                onConfirm:()=>{
                    page_block.block();
                    let formData = buildFormData();
                    _request.post('/material-issuance/update',formData).then((res) => {
                        if(res.status == 'success'){
                            Alert.loading(res.status,res.message+'<br> Website is reloading ...', {
                                didOpen: ()=>{
                                    setTimeout(() => {
                                        location.reload();
                                    }, 2500);
                                }
                            });
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
                        page_block.release();
                    });
                },
                onCancel:()=>{
                    _this.attr("disabled",false);
                }
            });

        });

    });

}
