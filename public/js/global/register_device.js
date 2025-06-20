'use strict';

import { RequestHandler } from "./request.js";
import { get_company_location, get_department, get_employee, get_item, get_mis_personnel, get_position } from "./select.js";
import {fv_validator} from "../global.js";
import {Alert} from "../global/alert.js"

export var RegisterDeviceController =  function (page,param) {

    const _page = $('.page-device-registration');
    const _request = new RequestHandler;

    var fvEmployeeDetails='';
    var fvOtherAccessories ='';
    var fvIssuedBy ='';

    // var formSystemUnitDetails = document.querySelector(".form_system_unit_details");
    // var formMonitorDetails = document.querySelector(".repeater-monitor-details");

    var formOtherAccessories = document.querySelector(".repeater-other-accessories");
    var formEmployeeDetails = document.querySelector(".repeater-issued-to");
    var formIssuedBy = document.querySelector(".issued-by");

    var submitForm = $('.submit-device-registration');

    var _url = new URL(window.location.href);
    var dataParam = _url.searchParams.get('data');

    async function _fvOtherAccessories()
    {
        $('.repeater-other-accessories').repeater({
            initEmpty: false,
            defaultValues: { 'text-input': 'foo' },
            isFirstItemUndeletable: true,
            show: async function () {
                const repeaterList = $(this).closest('[data-repeater-list]');
                const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));

                const acccessories = `other-accessories[${currentIndex}][accessories]`;
                // const serial_number = `other-accessories[${currentIndex}][serial_number]`;

                fvOtherAccessories.addField(acccessories,fv_validator())
                // fvOtherAccessories.addField(serial_number,fv_validator())

                // await get_item(`select[name="${acccessories}"]`,``,`accessories`,1);
                $(`select[name="${acccessories}"]`).select2({
                    // minimumInputLength: 3,
                    width: '100%',
                });

                repeaterList.find('[data-repeater-delete]').parent().removeClass('d-none');
                $(this).slideDown();
            },

            hide: async function () {
                $(this).find('[name]').each(function () {
                    const fieldName = $(this).attr('name');
                    if (fvOtherAccessories.getElements(fieldName)) {
                        fvOtherAccessories.removeField(fieldName);
                    }
                });
                $(this).slideUp();

            },
        });

        let selectAccessories = $(`select[name="other-accessories[0][accessories]"]`);
        selectAccessories.select2({
            // minimumInputLength: 3,
            width: '100%',
        });

        if (formOtherAccessories.hasAttribute('data-fv-initialized')) {
            return;
        }

        fvOtherAccessories = FormValidation.formValidation(formOtherAccessories, {
            fields: {
                'other-accessories[0][accessories]':fv_validator(),
                // 'other-accessories[0][serial_number]':fv_validator(),
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
        formOtherAccessories.setAttribute('data-fv-initialized', 'true');
    }

    async function _fvEmployeeDetails()
    {
        $('.repeater-issued-to').repeater({
            initEmpty: false,
            defaultValues: { 'text-input': 'foo' },
            isFirstItemUndeletable: true,
            show: async function () {
                const repeaterList = $(this).closest('[data-repeater-list]');
                const currentIndex = repeaterList.find('[data-repeater-item]').index($(this));

                const employee = `issued-to[${currentIndex}][employee]`;
                const position = `issued-to[${currentIndex}][position]`;
                const department = `issued-to[${currentIndex}][department]`;

                fvEmployeeDetails.addField(employee,fv_validator())
                fvEmployeeDetails.addField(position,fv_validator())
                fvEmployeeDetails.addField(department,fv_validator())

                await get_employee(`select[name="${employee}"]`,``,1);
                await get_position(`select[name="${position}"]`,``,1);
                await get_department(`select[name="${department}"]`,``,1);

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
        // await get_position(`select[name="issued-to[0][position]"]`,``,1);
        // await get_department(`select[name="issued-to[0][department]"]`,``,1);

        if (formEmployeeDetails.hasAttribute('data-fv-initialized')) {
            return;
        }

        fvEmployeeDetails = FormValidation.formValidation(formEmployeeDetails, {
            fields: {
                'issued-to[0][employee]':fv_validator(),
                // 'issued-to[0][department]':fv_validator(),
                // 'issued-to[0][position]':fv_validator(),
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

    async function _fvIssuedBy()
    {
        await get_mis_personnel(`select[name="issued_by"]`,``,1);

        if (formIssuedBy.hasAttribute('data-fv-initialized')) {
            return;
        }

        fvIssuedBy = FormValidation.formValidation(formIssuedBy, {
            fields: {
                'issued_by':fv_validator(),
                'acknowledgement':fv_validator(),
                'company_location':fv_validator(),
                'form_no':fv_validator(),
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
        formIssuedBy.setAttribute('data-fv-initialized', 'true');
    }

    async function formValidation() {
        const results = await Promise.all([
            fvOtherAccessories.validate(),
            fvEmployeeDetails.validate(),
            fvIssuedBy.validate(),
        ]);
        return results.every(result => result === 'Valid');
    }

    function buildFormData()
    {
        const combinedFormData = new FormData(formIssuedBy);
        combinedFormData.append('dataParam',dataParam);

        const otherAccessories = [];
        $('[data-repeater-list="other-accessories"] [data-repeater-item]:visible').each(function () {
            const accessories = $(this).find('[name$="[accessories]"]').val();
            const serial = $(this).find('[name$="[serial_number]"]').val();

            otherAccessories.push({
                id: accessories,
                serial_number: serial
            });
        });
        combinedFormData.append('other_accessories', JSON.stringify(otherAccessories));

        const issuedTo = [];
        $('[data-repeater-list="issued-to"] [data-repeater-item]:visible').each(function () {
            const employee = $(this).find('[name$="[employee]"]').val();
            const position = $(this).find('[name$="[position]"]').val();
            const department = $(this).find('[name$="[department]"]').val();

            issuedTo.push({
                employee: employee,
                position: position,
                department: department
            });
        });
        combinedFormData.append('issued_to', JSON.stringify(issuedTo));

        return combinedFormData;
    }

    $(async function () {

        await _fvOtherAccessories();
        await _fvEmployeeDetails();
        await _fvIssuedBy();
        await get_company_location('select[name="company_location"]','','options',1);


        submitForm.click(async function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            //validate data in url
            if (!dataParam && dataParam.trim() == "") {
                Alert.alert('error',"Missing data in the URL. Close this tab and run the program again", false);
                return;
            }

            //validate form
            let isValid = await formValidation();
            if (!isValid) {
                return;
            }

            let _this = $(this);
            _this.attr("disabled",true);

            Alert.confirm("question","Do you want to submit the registration form ?", {
                onConfirm:()=>{

                    blockUI.block();

                    let formData = buildFormData();
                    let _request = new RequestHandler;

                    _request.post('/device/update',formData).then((res) => {
                        if(res.status == 'success'){
                            Alert.loading(res.status,res.message+'<br>Thank you for your cooperation.', {
                                didOpen: ()=>{
                                    setTimeout(() => {
                                        window.location.replace('/');
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
                        blockUI.release();
                    });
                },
                onCancel:()=>{
                    _this.attr("disabled",false);
                }
            });

        });

    });


}

RegisterDeviceController();
