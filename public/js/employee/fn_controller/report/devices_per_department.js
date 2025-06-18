'use strict';
import { page_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";

export var DevicePerDepartment = function (page, param) {

    let _page = $('.page-devices-per-department');

    async function loadDataTable(paginate_page=1)
    {
        let request = new RequestHandler;
        let formData = new FormData;

        let array_search = (window.btoa(JSON.stringify(
            {
                search:$('.search').val(),
            }))
        );

        let search = $('input[name="search"]').val();

        formData.append('page',paginate_page);
        formData.append('search',$('.search').val());
        formData.append('array_search',array_search);

        request.post('/reports/devices-per-department/dt',formData)
        .then((res) => {
            let element = $('.card-body-devices-per-department').empty();
            let payload = JSON.parse(window.atob(res.payload));
            if(res.status != 'success' || payload.length <= 0){
                element.empty();
                page_state(element,"empty",null,"No collection made yet!");
                return;
            }
            element.append(payload);
        })
        .catch((error) => {
            console.log(error);
            Alert.alert('error', "Something went wrong. Try again later", false);
        })
        .finally((error) => {
            //code here
        });
    }

    $(async function () {

        if (!page_block.isBlocked()) {
            page_block.block();
        }

        loadDataTable();

        setTimeout(() => {
            page_block.release();
        }, 500);

    });
}
