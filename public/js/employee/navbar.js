'use strict';

import { AccountabilityListController } from './fn_controller/accountability/accountability.js';
import { AccountabilityDetailsController } from './fn_controller/accountability/details.js';
import { NewAccountabilityController } from './fn_controller/accountability/new_accountability.js';
import { page_content } from './page.js';

async function init_page(_default) {
    let pathname = window.location.pathname;
    let page = pathname.split("/")[1] || _default;
    let param = false;
    let url = window.location.pathname;
    if(url.split('/')[2] !== null && typeof url.split('/')[2] !== 'undefined'){
        param =  pathname.split("/")[2];
    }

    load_page(page, param).then((res) => {
        if (res) {
            $(`.navbar[data-page='${page}']`).addClass('here');
        }
    })
}

export async function load_page(page, param=null){
    try {
        const pageContent = await page_content(page, param);
        if (pageContent) {
            $('.current-directory').text(page);
            await page_handler(page, param);
            return true;
        } else {
            return false;
        }
    } catch (error) {
        console.error('Error in load_page:', error);
        return false;
    }
}

export async function page_handler(page,param=null){
    page = page.replace(/-/g, '_');
    const handler = _handlers[page];
    if (handler) {
        handler(page, param);
    } else {
        console.log("No handler found for this page");
    }
}

const _handlers = {
    // dashboard: (page, param) => HomeController(page, param),
    accountability: (page, param) => AccountabilityListController(page, param),
    accountability_details: (page, param) => AccountabilityDetailsController(page, param),
    new_accountability:(page,param) => NewAccountabilityController(page,param,)
};

jQuery(document).ready(function() {
    init_page('dashboard');
    $(".navbar").on("click", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        let page = $(this).data('page');
        let link = $(this).data('link');
        let title = $(this).find('.menu-title').text();
        let _this = $(this);
        load_page(page).then((res) => {
            if (res) {
                if(_this.hasClass('sub-menu')){
                    $('.navbar ,.menu-sub').removeClass('here');
                    _this.parent().parent().addClass('here');
                }else{
                    $('.navbar').removeClass('here');
                    _this.addClass('here');
                }
            }
        });
    });
})
