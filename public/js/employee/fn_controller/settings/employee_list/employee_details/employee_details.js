'use strict';
import { data_bs_components } from "../../../../../global.js";
import { Alert } from "../../../../../global/alert.js";
import { RequestHandler } from "../../../../../global/request.js";
import { AccountSecurityController } from "./account_security.js";
import { EmploymentDetailsDataHandler } from "./employment_details.js";
import { PersonalDataHandler } from "./personal_data.js";

export var EmployeeDetailsController =  function (page,param) {

    const _page = $('.page-employee-details');
    const _request = new RequestHandler;

    const _handlers = {
        'personal_data':(tab,param) => PersonalDataHandler(tab,param),
        'employment_details':(tab,param) => EmploymentDetailsDataHandler(tab,param),
        'account_security': (tab,param)=> AccountSecurityController(tab,param),
    };

    async function loadActiveMainTab(tab=false)
    {
        tab = (tab == false ? (localStorage.getItem("employee_details_maintab") || 'personal_data') : tab);
        
        if (page_block.isBlocked() === false) {  page_block.block(); }
        return new Promise(async (resolve, reject) => {
            if (tab) {
                let _formData = new FormData();
                _formData.append("emp_id", param);
                _formData.append("tab", tab);
                _request.post('/employee-list/employee-details/tab',_formData).then((res) => {
                    if(res.status == 'success'){
                        let view = window.atob(res.payload);
                        $(_page).find(`.main-content`).html(view);

                        const handler = _handlers[tab];
                        if (handler) { handler(tab,param);  }

                        let _this = $('.main-tab[data-tab='+tab+']');
                        $('.main-tab').removeClass('active');
                        _this.addClass('active');

                        page_block.release();
                        resolve(tab);
                    }
                })
                .catch((error) => {
                    console.log(error)
                    Alert.alert('error',"Something went wrong. Try again later", false);
                })
                .finally(() => {
                    data_bs_components();
                    KTMenu.createInstances();
                });
            } else {
                resolve(false);
            }
        });
    }

    _page.on('click','a.main-tab',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        let _this = $(this);
        if (_this.data("processing") || _this.hasClass('active')) {
            return;
        }

        _this.data("processing", true);
        _this.attr('disabled',true);

        let tab = $(this).attr('data-tab');
        localStorage.setItem("employee_details_maintab",tab);

        loadActiveMainTab(tab)
        .then((res) => {
            if (res) {
                // $(_this.attr('href')).find('.tab_title').text(_this.attr('data-tab-title'));
                setTimeout(() => {
                    _this.attr('disabled',false);
                    _this.data("processing", false);
                },500);

            }
        })
    });

    $(async function () {
        page_block.block();

        await loadActiveMainTab().then((tab) => {
        })

        setTimeout(() => {
            page_block.release();
        }, 300);
    });

}
