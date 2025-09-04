'use strict';

import {RequestHandler} from './request.js';

export async function trigger_select(select, text) {
    return new Promise((resolve) => {
        $(select).each(function() {
            let val = null;
            $(this).find('option').each(function() {
                if ($(this).text().trim().toLowerCase() === text.trim().toLowerCase()) {
                    val = $(this).val();
                    return false; // Break out of the loop
                }
            });
            $(this).val(val).trigger('change'); // Set the value and trigger change
        });
        resolve(); // Resolve the promise after processing
    });
}

export async function get_department(_element,param,view='all') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'department');
        formData.append('view',view);

        (new RequestHandler).post("/select/department",formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_position(_element,param,view='all') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'position');
        formData.append('view',view);

        (new RequestHandler).post("/select/position", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_employee(_element,param,view='all') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'employee');
        formData.append('view',view);

        (new RequestHandler).post("/select/employee", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_mis_personnel(_element,param,view='all') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'mis_personnel');
        formData.append('view',view);

        (new RequestHandler).post("/select/employee", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_item(_element,param='',type,view='all') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type',type);
        formData.append('view',view);

        (new RequestHandler).post("/select/item", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_company_location(_element,param='',type,view='all') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type',type);
        formData.append('view',view);


        (new RequestHandler).post("/select/company-location", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_item_type(_element,param='',type,view='all') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type',type);
        formData.append('view',view);


        (new RequestHandler).post("/select/item-type", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_filter_inventory_year(_element,param='',type,view='all') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type',type);
        formData.append('view',view);


        (new RequestHandler).post("/select/filter-year", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_tag_number(_element,param='',type,view='all') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        element.select2({
            dropdownParent: modal.length ? '#' + modal.attr('id') : null,
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: "/select/tag-number",
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    let formData = {
                        search: params.term,
                        id: param,
                        type: type,
                        view: view
                    };
                    return formData;
                },
                processResults: function (data) {
                    data.unshift({ id: 'all', text: 'Show all' });
                    return { results: data };
                },
                cache: true
            }
        })
        .on('select2:open', function() {
            element.attr('data-select2-initialized', true);
        })
        .on('select2:select', function() {
            resolve(true);
        });

        element.attr('disabled', false);
        resolve(true);
    });

        // let formData = new FormData();
        // formData.append('id', param);
        // formData.append('type',type);
        // formData.append('view',view);


        // (new RequestHandler).post("/select/tag-number", formData)
        //     .then((res) => {
        //         element.empty().append(res);
        //         element.attr('data-select2-initialized',true);
        //         element.select2({
        //             dropdownParent: modal.length ? '#'+modal.attr('id') : null,
        //             width: '100%',
        //         });
        //         resolve(true);
        //     })
        //     .catch((error) => {
        //         console.error(error);
        //         resolve(false);
        //     })
        //     .finally(() => {
        //         element.attr('disabled', false);
        //     });
}

export async function get_inventory(_element,param='',type,view='all') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type',type);
        formData.append('view',view);

        (new RequestHandler).post("/select/inventory", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}
