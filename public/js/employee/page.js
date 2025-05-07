'use strict';

import { RequestHandler } from "../global/request.js";
import {construct_url} from "../global.js";

export function page_content(page, param=null) {
    return new Promise((resolve, reject) => {
        let formData = new FormData();

        formData.append("page", page);
        if (param !== false) {  formData.append("id", param);  }
        (new RequestHandler()).post(construct_url("setup-page"), formData)
            .then((response) => {
                if (response.page) {
                    let tt = page.replace(/[^A-Z0-9]+/ig, " ");
                    let url = window.location.pathname;
                    $("head > title").empty().append("IMS | " + tt.split('/')[0].replace(/\b\w/g, char => char.toUpperCase()));
                    $('#page-heading').text(
                        tt.split('/')[0].replace(/\b\w/g, char => char.toUpperCase())
                    );
                    if (param !== null && param !== false) {
                        if (url.split('/')[1] !== page) {
                            window.history.pushState(null, null, page + '/' + param);
                        }
                    } else {
                        if (url.split('/')[2] !== null && typeof url.split('/')[2] !== 'undefined') {
                            window.history.pushState(null, null, '../' + page);
                        } else {
                            window.history.pushState(null, null, page);
                        }
                    }
                    app.empty().append(response.page);
                    resolve(true);
                } else {
                    // app.empty().append(pageNotFound());
                    resolve(false);
                }
            })
            .catch((err) => {
                console.log(err);
                reject(err);
            })
            .finally(() => {
                $("#main_modal").modal("hide");
                $(document.body).removeClass("modal-open").css({ overflow: "", "padding-right": "" });
                $(".modal-backdrop").remove();
                KTComponents.init();
            });
    });
}
