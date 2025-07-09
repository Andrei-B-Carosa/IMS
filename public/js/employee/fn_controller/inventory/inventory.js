'use strict';
import { modal_state, page_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import { get_company_location, get_filter_inventory_year, get_item_type } from "../../../global/select.js";
import { dtInventoryConsumable } from "../../dt_controller/inventory/inventory_consumable.js";
import { dtInventoryList } from "../../dt_controller/inventory/inventory_list.js";
import { fvRepairRequest } from "../../fv_controller/inventory/inventory.js";

export var InventoryListController = async function (page, param) {

    const _page = $('.page-inventory-list');
    let tabLoaded = [];

    async function loadActiveTab(tab=false){
        tab = (tab == false ? (localStorage.getItem("inventory_tab") || 'device') : tab);

        const _tab = {
            "devices": deviceTab,
            "consumables": consumableTab,
        };

        // if (!page_block.isBlocked()) {  page_block.block(); }
        return new Promise((resolve, reject) => {
            if (_tab[tab]) {
                _tab[tab](tab).then(() =>
                    // setTimeout(() => {
                    //     page_block.release();
                    // }, 500),
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

    async function deviceTab(tab){
        dtInventoryList('inventory-list').init();
        fvRepairRequest();
    }

    async function consumableTab(tab){
        dtInventoryConsumable('inventory-consumable-list','',true).init();
    }

    $(async function () {

        loadActiveTab().then((tab) => {
            if(tab != false){
                $('a[data-tab='+tab+']').addClass('active');
                $(`#tab_${tab}`).addClass('show active');
                tabLoaded.push(tab);
            }
        })

        _page.on('click','a.nav-link',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            let _this = $(this);
            let tab = $(this).attr('data-tab');
            _this.attr('disabled',true);

            localStorage.setItem("inventory_tab",tab);
            if(tabLoaded.includes(tab)){
                _this.attr('disabled',false);
                return;
            }

            tabLoaded.push(tab);
            loadActiveTab(tab).then((res) => {
                if (res) {
                    _this.attr('disabled',false);
                }
            })
        });

        get_company_location(`select[name="filter_location"]`,'','filter',1);
        get_item_type('select[name="filter_category"]','','filter_item_type',1);
        get_filter_inventory_year('select[name="filter_year"]','','filter_inventory_year',1);

    });
}
