'use strict';

import { dtCompanyLocation } from "../../../dt_controller/settings/file_maintenance/company_location.js";
import { dtItems } from "../../../dt_controller/settings/file_maintenance/item.js";
import { dtItemBrand } from "../../../dt_controller/settings/file_maintenance/item_brand.js";
import { dtItemSuppliers } from "../../../dt_controller/settings/file_maintenance/item_suppliers.js";
import { dtItemType } from "../../../dt_controller/settings/file_maintenance/item_type.js";
import { fvCompanyLocation } from "../../../fv_controller/settings/file_maintenance/company_location.js";
import { fvItemBrand } from "../../../fv_controller/settings/file_maintenance/item_brand.js";
import { fvItemSuppliers } from "../../../fv_controller/settings/file_maintenance/item_suppliers.js";
import { fvItemType } from "../../../fv_controller/settings/file_maintenance/item_type.js";

export var FileMaintenanceController = function (page,param) {

    const _page = $('.page-file-maintenance-settings');
    let tabLoaded = [];

    function loadActiveTab(tab=false){
        tab = (tab == false ? (localStorage.getItem("file_maintenance_tab") || 'item') : tab);

        const _tab = {
            "item": itemTab,
            "item-type": itemTypeTab,
            "item-brand": itemBrandTab,
            "item-suppliers": itemSuppliersTab,
            "company-location": CompanyLocationTab,
        };

        return new Promise((resolve, reject) => {
            if (_tab[tab]) {
                _tab[tab](tab).then(() =>
                    resolve(tab)
                )
                .catch(reject)
                .finally(()=>{
                    $(`#modal_add_${tab}`).find('select[name="is_active"]').select2()
                });
            } else {
                resolve(false);
            }
        });
    }

    async function itemTab()
    {
        return new Promise((resolve, reject) => {
            try {
                $(_page).ready(function () {
                    dtItems('item').init();
                    resolve(true);
                });
            } catch (error) {
                resolve(false);
            }
        });
    }

    async function itemTypeTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                $(_page).ready(function () {
                    dtItemType('item-type').init();
                    fvItemType('#'+tab+'_table',tab);
                    resolve(true);
                });
            } catch (error) {
                resolve(false);
            }
        });
    }


    async function itemBrandTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                $(_page).ready(function () {
                    dtItemBrand('item-brand').init();
                    fvItemBrand('#'+tab+'_table',tab);
                    resolve(true);
                });
            } catch (error) {
                resolve(false);
            }
        });
    }

    async function itemSuppliersTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                $(_page).ready(function () {
                    dtItemSuppliers('item-suppliers').init();
                    fvItemSuppliers('#'+tab+'_table',tab);
                    resolve(true);
                });
            } catch (error) {
                resolve(false);
            }
        });
    }

    async function CompanyLocationTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                $(_page).ready(function () {
                    dtCompanyLocation('company-location').init();
                    fvCompanyLocation('#'+tab+'_table',tab);
                    resolve(true);
                });
            } catch (error) {
                resolve(false);
            }
        });
    }


    $(async function () {

        page_block.block();

        loadActiveTab().then((tab) => {
            if(tab != false){
                $('a[data-tab='+tab+']').addClass('active');
                $(`.${tab}`).addClass('show active');
                tabLoaded.push(tab);
            }
        })

        _page.on('click','a.tab',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            let _this = $(this);
            let tab = $(this).attr('data-tab');
            _this.attr('disabled',true);

            localStorage.setItem("file_maintenance_tab",tab);
            if(tabLoaded.includes(tab)){
                _this.attr('disabled',false);
                return;
            }

            page_block.block();
            tabLoaded.push(tab);
            loadActiveTab(tab).then((res) => {
                if (res) {
                    setTimeout(() => {
                        page_block.release();
                        _this.attr('disabled',false);
                    },500);
                }
            })
        });

        setTimeout(() => {
            page_block.release();
        }, 300);


    });


}
