'use strict';

export function page_state(container, status,val=false){
    var html = "";
    var initial_state = container[0].innerHTML;

    switch (status) {
        case 'empty':
        html = `<div id="empty_state_wrapper" >
                <div class="card-px text-center pt-15 pb-15">
                    <h2 class="fs-2x fw-bold mb-0" id="empty_state_title">Nothing in here</h2>
                    <p class="text-gray-400 fs-4 fw-semibold py-7" id="empty_state_subtitle">
                        No results found
                    </p>

                </div>
                <div class="text-center pb-15 px-5">
                    <img src="${asset_url+'/media/illustrations/sketchy-1/16.png'}" alt="" class="mw-100 h-200px h-sm-325px">
                </div>
            </div>`;
            container.html(html)
        break;

        case 'loading':
            html = `<div id="load_state_wrapper" class=" d-flex justify-content-center flex-column" style="position: relative">
                        <div class="card-px text-center pt-15 pb-15">
                            <h2 class="fs-2x fw-bold mb-0" id="load_state_title" style="position: absolute; left: 40%;">Searching ...</h2>
                            <p class="text-gray-400 fs-4 fw-semibold py-7 mt-5" id="load_state_subtitle">
                                This may take time please wait.
                            </p>

                        </div>
                        <div class="text-center pb-15 px-5">
                            <img src="${asset_url+'/media/illustrations/sketchy-1/5.png'}" alt="" class="mw-100 h-200px h-sm-325px">
                        </div>
                    </div>`;
            container.html(html)
        break;

        case 'initial':
            html = `<div id="initial_state_wrapper" class="d-flex justify-content-center flex-column">
                        <div class="card-px text-center pt-15 pb-15">
                            <h2 class="fs-2x fw-bold mb-0" id="initial_state_title">Search</h2>
                            <p class="text-gray-400 fs-4 fw-semibold py-7" id="initial_state_subtitle">
                                Please enter a keyword or phrase to search for.
                            </p>

                        </div>
                        <div class="text-center pb-15 px-5">
                            <img src="${asset_url+'/media/illustrations/sketchy-1/5.png'}" alt="" class="mw-100 h-200px h-sm-325px">
                        </div>
                    </div>`;
            container.html(html);
        break;

        case 'empty-table':
            html = `<div id="initial_state_wrapper" class="d-flex justify-content-center flex-column">
                        <div class="card-px text-center pt-15 pb-15">
                            <h2 class="fs-2x fw-bold mb-0" id="initial_state_title">${val}</h2>
                            <p class="text-gray-400 fs-4 fw-semibold py-7" id="initial_state_subtitle">
                                Please select a keyword or phrase to search for.
                            </p>

                        </div>
                    </div>`;
            container.html(html);
         break;

        case 'not_found':
            html = `<div class="container d-flex justify-content-center align-items-center flex-column" style="width: 100%; height: 70vh;">
                        <h1 style="font-size: 250px; font-weight: 900; color: rgba(151, 151, 151, 0.395)">404</h1>
                        <h6 style="font-size: 30px; color: rgba(151, 151, 151, 0.395)">Page not found</h6>
                    </div>`;
            container.html(html);
        break;

        default:
            container.html(initial_state);
        break;
    }
}

export async function modal_state(modal_id,action='hide'){

    let modal = bootstrap.Modal.getOrCreateInstance(document.querySelector(modal_id));

    if(action == 'show'){
        modal.show();
    }else if(action == 'hide'){
        modal.hide();
        $('.modal-backdrop').remove()
    }
}

export function draw_table(id, container){
    //dt-container dt-bootstrap5 dt-empty-footer
    //
    var table = `<div class="dt-container dt-bootstrap5 dt-empty-footer" id="table_wrapper">
                    <table class="table  align-middle table-row-dashed fs-6 gy-3 dataTable" id="${id}" style="width:100%;"></table>
                </div>`;
    container.html(table);
}

export function construct_url(url) {
    var root = window.location.protocol + "//" + window.location.host;
    return root + "/" + url;
}

export function data_bs_components()
{
    let formSelect = $('.form-select').not('.modal-select, .ajax-select');
    if (formSelect.length > 0) {
        formSelect.select2();
    }

    // let select2Modal = $('.modal-select[data-control="select2"]:not([data-select2-initialized])');
    // if (select2Modal.length > 0) {
    //     select2Modal.select2({
    //         dropdownParent: $('.modal')
    //     });
    // }
    // let tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    // if (tooltipTriggerList.length > 0) {
    //     [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    // }

    // let popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    // if (popoverTriggerList.length > 0) {
    //     [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
    // }

    let timePicker = document.querySelectorAll('input[input-control="time-picker"]');
    if (timePicker.length > 0) {
        timePicker.forEach(input => {
            $(input).flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: input.getAttribute('default-format')==="24",
                defaultDate: input.getAttribute('default-date') === 'current'
                    ? new Date().toTimeString().slice(0, 5)
                    : null,
            });
        });
    }

    let datePickers = document.querySelectorAll('input[input-control="date-picker"]');
    if (datePickers.length > 0) {
        datePickers.forEach((input) => {
            let inputValue = input.value;
            let isValidDate = !isNaN(Date.parse(inputValue));
            $(input).flatpickr({
                defaultDate: isValidDate ? inputValue : (input.getAttribute('default-date') === 'current'?new Date():''),
                dateFormat: 'm-d-Y',
            });
        });
    }

    let monthDayPicker = document.querySelectorAll('input[input-control="month-day-picker"]');
    if (monthDayPicker.length > 0) {
        $(monthDayPicker).flatpickr({
            shorthand: true,
            dateFormat: "m-d", // Format for the input value (e.g., 11-11)
            altInput: true,
            altFormat: "F j", // Display format (e.g., November 11)
            allowInput: true,
            defaultDate: "01-01", // Set default month and day
        });
    }

}

export function fv_validator(){
    return {validators:{notEmpty:{message:'This field is required'}}};
}

export function fv_numeric(){
    return {
        validators: {
            notEmpty: {
                message: 'Year is required'
            },
            numeric: {
                message: 'Year must be a number'
            },
            regexp: {
                regexp: /^[0-9]+(\.[0-9]+)?$/,
                message: 'Year must be a valid number or float'
            }
        }};
}

export function createBlockUI(selector, message) {
    const element = document.querySelector(selector);
    return new KTBlockUI(element, {
        message: `<div class="blockui-message"><span class="spinner-border text-primary"></span> ${message}</div>`,
    });
}

export function createDateRangePicker(selector) {
    var start = moment().subtract(29, "days");
    var end = moment();

    function cb(start, end) {
        $(`.${selector}`).html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
    }

    $(`.${selector}`).daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
        "Today": [moment(), moment()],
        "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
        "Last 7 Days": [moment().subtract(6, "days"), moment()],
        "Last 30 Days": [moment().subtract(29, "days"), moment()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
        }
    }, cb);
}

export function initFormValidation(form,validationRules)
{
    let validation;

    validation = FormValidation.formValidation(form, {
        fields: validationRules,
        plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap: new FormValidation.plugins.Bootstrap5({
            rowSelector: ".fv-row",
            eleInvalidClass: "",
            eleValidClass: "",
        }),
        },
    });

    return validation;
}
