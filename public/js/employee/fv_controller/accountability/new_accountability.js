"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator, initFormValidation} from "../../../global.js"
import { get_employee } from "../../../global/select.js";

export var fvNewAccountabilityController =  function (page,param) {

    const _page = $('.page-new-accountability');
    const _request = new RequestHandler;

    var fvEmployeeDetails='';
    var fvIssuedItem ='';
    var fvAccountabilityDetails ='';

    var formIssuedItem = document.querySelector(".repeater-issued-item");
    var formEmployeeDetails = document.querySelector(".repeater-issued-to");
    var formAccountabilityDetails = document.querySelector(".accountability-details");

    var submitForm = $('.submit-new-accountability');

    async function _fvAccountabilityDetails()
    {

        if (formAccountabilityDetails.hasAttribute('data-fv-initialized')) {
            return;
        }

        fvAccountabilityDetails = FormValidation.formValidation(formAccountabilityDetails, {
            fields: {
                'form_no':fv_validator(),
                'issued_by':fv_validator(),
                'issued_at':fv_validator(),
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

                fvIssuedItem.addField(acccessories,fv_validator())

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
                'issued-item[0][accessories]':fv_validator(),
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

                fvEmployeeDetails.addField(employee,fv_validator())

                await get_employee(`select[name="${employee}"]`,``,1);

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

        await get_employee(`select[name="issued-to[0][employee]"]`,``,1);

        if (formEmployeeDetails.hasAttribute('data-fv-initialized')) {
            return;
        }

        fvEmployeeDetails = FormValidation.formValidation(formEmployeeDetails, {
            fields: {
                'issued-to[0][employee]':fv_validator(),
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
            const serial = $(this).find('[name$="[serial_number]"]').val();

            issuedItem.push({
                id: accessories,
                serial_number: serial
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

        await _fvAccountabilityDetails();
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

            Alert.confirm("question","Do you want to submit this Accountability Form ?", {
                onConfirm:()=>{
                    page_block.block();
                    let formData = buildFormData();
                    _request.post('/accountability/update',formData).then((res) => {
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
