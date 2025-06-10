'use strict';
import { data_bs_components } from "../../../../../global.js";
import { Alert } from "../../../../../global/alert.js";
import { RequestHandler } from "../../../../../global/request.js";
import { fvAccountSecurity } from "../../../../fv_controller/employee_list/fv_employee_details.js";

export var AccountSecurityController =  function (page,param) {


    let tabLoaded = [];
    const _request = new RequestHandler;
    const _page = $('.page-employee-details');

    const _handlers = {
        1:(tab,param) => AccountSecurityHandler(tab,param),
    };

    function loadActiveTab(tab=1){
        // tab = (tab == false ? (localStorage.getItem("account_security_tab") || '1') : tab);
        return new Promise(async (resolve, reject) => {
            if (tab) {
                let _formData = new FormData();
                _formData.append("emp_id", param);
                _formData.append("tab", tab);
                _request.post('/employee-list/employee-details/account_security/tab',_formData).then((res) => {
                    if(res.status == 'success'){
                        let view = window.atob(res.payload);
                        $(_page).find(`#card${tab}`).html(view);
                        resolve(tab);
                    }
                })
                .catch((error) => {
                    console.log(error)
                    Alert.alert('error',"Something went wrong. Try again later", false);
                })
                .finally(() => {
                    const handler = _handlers[tab];
                    if (handler) { handler(tab,param);  }
                    data_bs_components();
                    KTMenu.createInstances();
                });
            } else {
                resolve(false);
            }
        });
    }

    function AccountSecurityHandler(tab,param){
        var d = function () {
            e.classList.toggle("d-none"),
            s.classList.toggle("d-none"),
            n.classList.toggle("d-none");
        },
        c = function () {
            o.classList.toggle("d-none"),
            a.classList.toggle("d-none"),
            i.classList.toggle("d-none");
        };
        let s = document.getElementById("kt_signin_email_button");
        let e = document.getElementById("kt_signin_email");
        let n = document.getElementById("kt_signin_email_edit") ;
        let o = document.getElementById("kt_signin_password");
        let i = document.getElementById("kt_signin_password_edit");
        let a = document.getElementById("kt_signin_password_button");
        let r = document.getElementById("kt_signin_cancel");
        let l = document.getElementById("kt_password_cancel");

        let z = document.getElementById('reveal-newpassword');
        let w = document.getElementById('reveal-confirmnewpassword');

        let x = document.getElementById('newpassword');
        let y = document.getElementById('confirmpassword');


        s.querySelector("button").addEventListener("click", function () {
            d();
        });

        a.querySelector("button").addEventListener("click", function () {
            c();
        })

        r.addEventListener("click", function () {
            d();
            loadActiveTab(tab);
        });
        l.addEventListener("click", function () {
            c();
        });

        z.addEventListener('click',function(){
            if(x.getAttribute('type') == 'password' && x.value.length > 0){
                x.setAttribute('type', 'text');
                x.blur();
            }else if(x.getAttribute('type')=='text' && x.value.length > 0){
                x.setAttribute('type', 'password');
            }
        })

        w.addEventListener('click',function(){
            if(y.getAttribute('type') == 'password' && y.value.length > 0){
                y.setAttribute('type', 'text');
                y.blur();
            }else if(y.getAttribute('type')=='text' && y.value.length > 0){
                y.setAttribute('type', 'password');
            }
        })

        x.addEventListener('mousedown',function(){
            if(x.getAttribute('type') == 'text' && x.value.length > 0){
                let value = this.value; // Store current value

                this.setAttribute('type', 'password'); // Change type to password
                setTimeout(() => {
                    this.value = ''; // Temporarily clear the value
                    this.value = value; // Restore value to reset caret position
                    this.setSelectionRange(value.length, value.length); // Move cursor to end
                    this.focus(); // Ensure focus stays on input
                }, 1); // Delay to let the browser update the input type
            }
            if(y.getAttribute('type') == 'text'){
                let value = y.value; // Store current value

                y.setAttribute('type', 'password'); // Change type to password
                setTimeout(() => {
                    y.value = ''; // Temporarily clear the value
                    y.value = value; // Restore value to reset caret position
                    y.setSelectionRange(value.length, value.length); // Move cursor to end
                    y.focus(); // Ensure focus stays on input
                }, 1); // Delay to let the browser update the input type
            }
        })

        y.addEventListener('mousedown',function(){
            if(x.getAttribute('type') == 'text'){
                let value = x.value; // Store current value

                x.setAttribute('type', 'password'); // Change type to password
                setTimeout(() => {
                    x.value = ''; // Temporarily clear the value
                    x.value = value; // Restore value to reset caret position
                    x.setSelectionRange(value.length, value.length); // Move cursor to end
                    x.focus(); // Ensure focus stays on input
                }, 1); // Delay to let the browser update the input type
            }
            if(y.getAttribute('type') == 'text'){
                let value = y.value; // Store current value

                y.setAttribute('type', 'password'); // Change type to password
                setTimeout(() => {
                    y.value = ''; // Temporarily clear the value
                    y.value = value; // Restore value to reset caret position
                    y.setSelectionRange(value.length, value.length); // Move cursor to end
                    y.focus(); // Ensure focus stays on input
                }, 1); // Delay to let the browser update the input type
            }
        })


        $(_page).find('#kt_signin_change_password, #kt_signin_change_email').attr('action','/employee-list/employee-details/account_security/update');
        fvAccountSecurity(false,3,param);
    }


    $(async function () {

        loadActiveTab().then((tab) => {
            if(tab != false){
                let _this = $('a[data-tab='+tab+']');
                _this.addClass('active');
                $(_this.attr('href')).find('.tab_title').text(_this.attr('data-tab-title'));
                $(`.tab${tab}`).addClass('show active');
                tabLoaded.push(tab);
            }
        })

    });
}
