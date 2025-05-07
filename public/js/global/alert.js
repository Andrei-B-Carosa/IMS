import {modal_state} from "../global.js";

export class Alert {
    static toast(status, message) {
        Swal.fire({
            toast: true,
            position: "top-end",
            icon: status,
            text: message,
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });
    }

    static alert(status="error", message="Something went wrong. Try again later", option=false) {
        Swal.fire({
            icon: status,
            title:'Oops',
            text: message,
            // timer: 2000,
            // showConfirmButton: false,
            didRender: () => {
                if (typeof option === "function") {
                    option.didRender();
                }
            },
        });
    }

    static confirm(icon,message, option) {
        Swal.fire({
            html: message,
            icon: icon,
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-danger",
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed && typeof option.onConfirm === "function") {
                option.onConfirm();
            } else if (result.isDismissed && typeof option.onCancel === "function") {
                option.onCancel();
            } else {
                Swal.close();
            }
        });
    }

    // Add input dialog method
    // Input dialog method with text input
    static input(icon, message, option) {
        const isRequired = option.isRequired ?? true;
        const inputPlaceholder = option.inputPlaceholder ?? "Enter your response";
        Swal.fire({
            html: message,
            icon: icon,
            input: 'text', // Input type
            inputPlaceholder: inputPlaceholder, // Placeholder text
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
            inputAttributes: {
                autocapitalize: 'off' // Disable auto-capitalization for input
            },
            inputValidator: (value) => {
                if (isRequired && !value) {
                    return 'You need to write something!'; // Validation message
                }
            },
            allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
            if (result.isConfirmed && typeof option.onConfirm === "function") {
                option.onConfirm(result.value); // Pass input value to callback
            } else if (result.isDismissed && typeof option.onCancel === "function") {
                option.onCancel();
            }
        });
    }

    static select(icon, title, option, config) {
        let selectHTML = '';

        config.forEach(selectConfig => {
            let { name, options } = selectConfig;
            let selectElement = `<select name="${name}" class="form-select swal-select2" data-control="select2"
                                    data-allow-clear="true" data-placeholder="Select an option">
                                  <option></option>`;

            // Loop through the options for each select
            if (Array.isArray(options) && options.length > 0) {
                options.forEach(option => {
                    selectElement += `<option value="${option.value}">${option.text}</option>`;
                });
            }

            selectElement += `</select>`;
            selectHTML += selectElement;
        });

        Swal.fire({
            icon: icon,
            title: title,
            html: selectHTML,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-danger",
            },
            didOpen: () => {
                // Initialize select2 on all select elements
                $('.swal-select2').select2({
                    width: '100%',
                    dropdownParent: $('.swal2-container')  // Ensure dropdown is within SweetAlert
                });

                // Ensure the select2 container has a high z-index
                $('.swal-select2').on('select2:open', function () {
                    let select2Container = $(this).data('select2').$dropdown;
                    select2Container.css('z-index', 99999999);  // Increase z-index
                });
            },
            preConfirm: () => {
                let selectedValues = {};
                let isValid = true;

                // Validate each select and gather their values
                config.forEach(selectConfig => {
                    let { name } = selectConfig;
                    let selectValue = document.querySelector(`[name="${name}"]`).value;

                    // Validation: if a select has no value, display an error
                    if (!selectValue) {
                        Swal.showValidationMessage(`Please select a value for ${name}`);
                        isValid = false;
                    } else {
                        selectedValues[name] = selectValue;
                    }
                });

                // Return selected values if validation passed
                if (isValid) {
                    return selectedValues;
                }
                return false;
            }
        }).then(result => {
            if (result.isConfirmed && typeof option.onConfirm === "function") {
                option.onConfirm(result.value); // Pass selected values to callback
            } else if (result.isDismissed && typeof option.onCancel === "function") {
                option.onCancel();
            }
        });
    }



    static loading(icon,message, option) {
        Swal.fire({
            icon:icon,
            html: message,
            allowOutsideClick: false,
            timer:3500,
            timerProgressBar:true,
            allowOutsideClick: () => !Swal.isLoading(),
            didOpen: () => {
                Swal.showLoading();
                if (typeof option.didOpen === "function") {
                    option.didOpen();
                }
            },
        });
    }

    static close() {
        Swal.close();
    }
}
