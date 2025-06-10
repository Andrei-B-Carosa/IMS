'use strict';

import { data_bs_components } from "../../../../global.js";
import { Alert } from "../../../../global/alert.js";
import { RequestHandler } from "../../../../global/request.js";

var stepper = (function () {

    const _page = $('.page-employee-registration');
    const _request = new RequestHandler;
    const fvInstances  = {};

    let stepperElement = document.querySelector("#kt_create_account_stepper");
    let blockTarget = document.querySelector("#kt_block_ui_1_target");

    let stepper = new KTStepper(stepperElement);
    let blockUI = new KTBlockUI(blockTarget, {
        overlayClass: "bg-white",
    });

    var _handleStepper = async function () {

        const previousButton = $('button[data-kt-stepper-action="previous"]');
        const nextButton = $('button[data-kt-stepper-action="next"]');
        const registerAgain = $('button.reset-registration');

        // $(_page).on("change","input[name='data_privacy_act']", function () {
        //     if ($(this).is(":checked")) {
        //         $('[data-kt-stepper-action="next"]').prop("disabled", false);
        //         localStorage.setItem("data_privacy_act", true);
        //     } else {
        //         $('[data-kt-stepper-action="next"]').prop("disabled", true);
        //         localStorage.setItem("data_privacy_act", false);
        //     }
        // });

        // Handle on change
        stepper.on("kt.stepper.changed", function (stepper) {
            blockUI.block();

            const currentStepIndex = stepper.getCurrentStepIndex();
            localStorage.setItem("current_step", currentStepIndex);

            if (currentStepIndex >= 1) {
                previousButton.removeClass('d-none');

            } else {
                previousButton.addClass('d-none');
            }

            if (currentStepIndex === 3) {
                _handleReset();
                previousButton.addClass('d-none');
                nextButton.addClass('d-none');
                registerAgain.removeClass('d-none');
            } else {
                nextButton.removeClass('d-none');
            }

            setTimeout(() => {
                blockUI.release();
            }, 300);
        });

        // Handle next step
        stepper.on("kt.stepper.next", async function (stepper) {
            let currentStepIndex = stepper.getCurrentStepIndex();
            let nextStepIndex = currentStepIndex+1;
            var emp_id = localStorage.getItem("emp_id") || '';

            // if (!$('input[name="data_privacy_act"]').is(':checked')){
            //     Alert.alert("info","You must read and agree to the Privacy Consent to proceed.",false);
            //     return;
            // }

            //Submit Form
            if([1, 2].includes(currentStepIndex)){
                let fvInstance = fvInstances[`form${currentStepIndex}`];
                const status = await fvInstance.validate();
                if (status === "Valid") {
                    let formData = new FormData(fvInstance.form);
                    formData.append("form", currentStepIndex);
                    emp_id && formData.append("emp_id", emp_id);
                    try {
                        const res = await _request.post('/employee-list/employee-registration/update', formData);
                        if (res.status === 'success') {
                            if (!localStorage.hasOwnProperty('emp_id')) {
                                localStorage.setItem("emp_id", res.payload);
                            }
                            if (currentStepIndex == 2) {
                                await _handleReset();
                                $("#employeeID").html(res.payload);
                            }
                        }
                    } catch (error) {
                        console.error(error);
                        Alert.alert('error', "Something went wrong. Try again later.", false);
                        return; // Prevent progression on error
                    }
                }else{
                    return;
                }
            }

            if (![1,3].includes(nextStepIndex)) {
                await _handleStepperForm(localStorage.getItem("emp_id")||'',nextStepIndex);
            }
            stepper.goNext();
        });

        // Handle previous step
        stepper.on("kt.stepper.previous", async function (stepper) {
            const currentStepIndex = stepper.getCurrentStepIndex();

            // if (currentStepIndex === 1) {  return; }
            const previousStepIndex = currentStepIndex - 1;
            if ([1, 2].includes(previousStepIndex)) {
                const emp_id = localStorage.getItem("emp_id") || '';
                try {
                    await _handleStepperForm(emp_id, previousStepIndex);
                } catch (error) {
                    console.error("Error handling the previous form:", error);
                    Alert.alert('error', "Unable to load the previous step. Please try again.", false);
                    return;
                }
            }
            stepper.goPrevious();
        });

        registerAgain.click(function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            const currentStepIndex = stepper.getCurrentStepIndex();
            if (currentStepIndex === 3) {
                $('#form1')[0].reset();
                $('#form2')[0].reset();
                $('#form2').find('select.select2').val(null).trigger('change');
                stepper.goFirst();
                $(this).addClass('d-none');
            }
        })
    };

    var _handleStepperForm = async function (emp_id,form) {

        let element = $(`.form${form}`);
        if (element.children().length > 0) {
            return;
        }
        console.log(form)
        let _formData = new FormData();
        _formData.append("emp_id", emp_id);
        _formData.append("form", form);
        _request.post('/employee-list/employee-registration/form',_formData).then((res) => {
            if(res.status == 'success'){
                let view = window.atob(res.payload);
                $(_page).find(`.form${form}`).html(view);
                element.find('select[data-control="select2"]').select2();
            }
        })
        .catch((error) => {
            console.log(error)
            Alert.alert('error',"Something went wrong. Try again later", false);
        })
        .finally(() => {
            _handleStepperFormValidation(emp_id,form);
            data_bs_components();
        });
    }

    var _handleStepperFormValidation = function(emp_id,form_id){
        let fvStepper;
        let form = document.querySelector('.form'+form_id+' form');
        if ( form && !form.hasAttribute('data-fv-initialized')) {
            const validationRules = {};
            const fields = form.querySelectorAll('input, select');

            fields.forEach(function (field) {
                const fieldName = field.name;
                validationRules[fieldName] = {   validators: {} };

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

                validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
            });

            fvStepper = FormValidation.formValidation(form, {
                fields: validationRules,
                plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                    eleInvalidClass: "",
                    eleValidClass: "",
                }),
                },
            }).on("core.form.invalid", function () {
                KTUtil.scrollTop();
            });

            $(`#kt_create_account_stepper`).on("select2:close", function (e) {
                fvStepper.validate();
            });
            form.setAttribute('data-fv-initialized', 'true');
            fvInstances[`form${form_id}`] = fvStepper;
        }
    }

    var _handleRefresh = async function (event, block) {
        let currentStep = localStorage.getItem("current_step") || '1';

        if (currentStep >= 1) {
            let emp_id = localStorage.getItem("emp_id") || '';
            await _handleStepperForm(emp_id, parseInt(currentStep));
            event.goTo(currentStep);
        }
        // let isAgree = localStorage.getItem("data_privacy_act");
        // $('input[name="data_privacy_act"]').prop('checked',isAgree);
    }

    var _handleReset = async function()
    {
        localStorage.removeItem("current_step");
        localStorage.removeItem("data_privacy_act");
        localStorage.removeItem("emp_id");
    }

    return {
        init: async function () {
            await _handleStepper();
            _handleRefresh(stepper);
        },
        reset: _handleReset
    };
})();

$(document).ready(function () {
    // const navigationType = performance.getEntriesByType("navigation")[0]?.type;
    // if (navigationType === 'navigate') {
    //     stepper.reset();
    // }

    // Initialize the stepper
    KTUtil.onDOMContentLoaded(function () {
        stepper.init();
    });
});
