'use strict';
import { modal_state, page_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";

export var AccountabilityListController = function (page, param) {

    let _page = $('.page-accountability');

    async function loadAccountabilityList(paginate_page=1)
    {
        let request = new RequestHandler;
        let formData = new FormData;

        let array_search = (window.btoa(JSON.stringify(
            {
                search:$('.input-search').val(),
            }))
        );

        let filter_status = $('input[name="filter_status"]:checked').val();
        let search = $('input[name="search"]').val();

        formData.append('page',paginate_page);
        formData.append('filter_status',filter_status);
        formData.append('search',search);

        formData.append('array_search',array_search);

        request.post('/accountability/list',formData)
        .then((res) => {

            let div = $('.accountability-list').empty();
            $('#empty_state_wrapper').remove();
            $('.pagination').empty();

            let html = '';
            if(res.status != 'success'){
                div.parent().append(
                    `<div id="empty_state_wrapper" >
                        <div class="card-px text-center pt-15 pb-15">
                            <h2 class="fs-2x fw-bold mb-0" id="empty_state_title">Nothing in here</h2>
                            <p class="text-gray-400 fs-4 fw-semibold py-7" id="empty_state_subtitle">
                                No results found
                            </p>

                        </div>
                        <div class="text-center pb-15 px-5">
                            <img src="${asset_url+'/media/illustrations/sketchy-1/16.png'}" alt="" class="mw-100 h-200px h-sm-325px">
                        </div>
                    </div>`
                );
                return;
            }

            let payload = JSON.parse(window.atob(res.payload));
            if(payload.data.length <= 0){
                div.parent().append(
                    `<div id="empty_state_wrapper">
                        <div class="card-px text-center pt-15 pb-15">
                            <h2 class="fs-2x fw-bold mb-0" id="empty_state_title">Nothing in here</h2>
                            <p class="text-gray-400 fs-4 fw-semibold py-7" id="empty_state_subtitle">
                                No results found
                            </p>

                        </div>
                        <div class="text-center pb-15 px-5">
                            <img src="${asset_url+'/media/illustrations/sketchy-1/16.png'}" alt="" class="mw-100 h-200px h-sm-325px">
                        </div>
                    </div>`
                );
                return;
            }

            payload.data.forEach(item => {
                let issued_item = item.issued_item;
                let issued_item_html = '';
                let limit = 4;

                for (let i = 0; i < issued_item.length; i++) {
                    if (i < limit) {
                        issued_item_html += `
                            <div class="d-flex align-items-center py-2">
                                <span class="bullet bg-primary me-3"></span>
                                ${issued_item[i].name ?? issued_item[i].description}
                            </div>`;
                    } else {
                        let remaining = issued_item.length - i;
                        issued_item_html += `
                            <div class="d-flex align-items-center py-2">
                                <span class="bullet bg-primary me-3"></span>
                                <em>and ${remaining} more...</em>
                            </div>`;
                        break;
                    }
                }

                let issued_to = item.issued_to;
                let issued_to_html = issued_to.map(emp => emp.fullname).join(', ');

                html+=`
                <div class="col-md-3">
                    <a href="/accountability-details/${item.encrypted_id}" class="card card-flush h-md-100 border border-2 border-${item.status ==1?`success`:`danger`} border-hover shadow-sm">
                        <div class="card-header border-0 pt-9">
                            <div class="card-title">
                                <h2>Accountability No. ${item.form_no ??'--'}</h2>
                            </div>
                            <div class="card-toolbar">
                                <span class="badge badge-light-${item.status ==1?`success`:`danger`} fw-bold me-auto px-4 py-3">${item.status ==1?`Active`:`Inactive`}</span>
                            </div>
                        </div>
                        <div class="card-body pt-2">
                            <div class="fs-6 mb-2">
                                Date: ${item.issued_at}
                            </div>
                            <div class="fs-6 mb-7">
                                Issued By: ${item.issued_by}
                            </div>
                            <div class="fs-6">
                                <span class="fw-bolder text-gray-800 d-block mb-2">Issued Items: </span>
                                <div class="d-flex flex-column">
                                    ${issued_item_html}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer flex-wrap pt-0">
                            <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Accountable To: </span>
                            <span class="fs-6">${issued_to_html}</span>
                        </div>
                    </a>
                </div>
                `;
            });

            div.append(html);
            updatePagination(payload.pagination);
        })

        .catch((error) => {
            console.log(error);
            Alert.alert('error', "Something went wrong. Try again later", false);
        })
        .finally((error) => {
            //code here
        });
    }

    async function updatePagination(pagination) {
        // Clear existing pagination
        $('.pagination').empty();
        // Previous button
        if (pagination.current_page > 1) {
            $('.pagination').append('<li class="page-item previous"><a href="javascript:;" class="page-link" data-page="' + (pagination.current_page - 1) + '"><i class="previous"></i></a></li>');
        }

        // Page numbers
        for (let i = 1; i <= pagination.last_page; i++) {
            $('.pagination').append('<li class="page-item ' + (i === pagination.current_page ? 'active' : '') + '"><a href="javascript:;" class="page-link" data-page="' + i + '">' + i + '</a></li>');
        }

        // Next button
        if (pagination.current_page < pagination.last_page) {
            $('.pagination').append('<li class="page-item next"><a href="javascript:;" class="page-link" data-page="' + (pagination.current_page + 1) + '"><i class="next"></i></a></li>');
        }

        $('.pagination').parent().removeClass('d-none');

    }

    $(async function () {

        if (!page_block.isBlocked()) {
            page_block.block();
        }
        await loadAccountabilityList(1,'all');

        setTimeout(() => {
            page_block.release();
        }, 500);

        _page.on('click','.filter',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            let filter_status = $('input[name="filter_status"]:checked').val();

            page_block.block();
            loadAccountabilityList();
            setTimeout(() => {
                page_block.release();
            }, 500);
        })

        _page.on('keyup','.input-search',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let searchTerm = $(this).val().trim();
            if ((e.key === 'Enter' || e.keyCode === 13) && searchTerm !== '') {
                loadAccountabilityList();
            } else if ((e.keyCode === 8 || e.key === 'Backspace')) {
                setTimeout(() => {
                    let updatedSearchTerm = $(this).val().trim();
                    if (updatedSearchTerm === '') {
                        loadAccountabilityList();
                    }
                }, 0);
            }
        })

        _page.on('click','.page-link', function (e) {
            e.preventDefault()
            e.stopImmediatePropagation()
            let paginate_page =$(this).attr('data-page')
            loadAccountabilityList(paginate_page);
        });

    });
}
