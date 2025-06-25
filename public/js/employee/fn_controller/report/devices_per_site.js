'use strict';
import { page_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";

export var DevicePerSite = function (page, param) {

    const _page = $('.page-devices-per-site');
    const _request = new RequestHandler;

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

        request.post('/reports/devices-per-site/dt',formData)
        .then((res) => {
            let element = $('.card-body-devices-per-site').empty();
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

        _page.on('click','.export-devices-per-site',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let _this = $(this);
            let id    =_this.attr('data-id');
            let formData = new FormData;
            formData.append('encrypted_id','');

            _request.postBlob('/reports/devices-per-site/export', formData, true)
            .then(response => {
                const blob = response.data;
                const disposition = response.headers['content-disposition'];

                let filename = 'report.pdf'; // fallback
                if (disposition) {
                    const match = disposition.match(/filename\*?=(?:UTF-8'')?"?([^";\n]*)"?/);
                    if (match && match[1]) {
                        filename = decodeURIComponent(match[1].replace(/['"]/g, ''));
                    }
                }

                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            })
            .catch((error) => {
                Alert.alert('error', "Something went wrong. Try again later", false);
            });
        })

        loadDataTable();

        setTimeout(() => {
            page_block.release();
        }, 500);

    });
}
