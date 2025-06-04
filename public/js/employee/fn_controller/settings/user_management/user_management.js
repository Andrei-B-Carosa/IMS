'use strict';
import { data_bs_components, modal_state } from "../../../../global.js";
import { Alert } from "../../../../global/alert.js";
import { RequestHandler } from "../../../../global/request.js";
import { dtEnrollUser, dtUserList } from "../../../dt_controller/settings/user_management/user_management.js";

export var UserManagementController =  function (page,param) {

    const _page = $('.page-role-list');
    const _request = new RequestHandler;

    function loadRoles()
    {
        _request.get('/user-management/role-list/list',).then((res) => {
            if(res.status == 'success'){
                let payload = JSON.parse(window.atob(res.payload));
                let html='';
                $.each(payload, function(index, item) {
                    html +=`<div class="col-md-4">
                    <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                        <h2 class="text-role-tile-list">${item.role}</h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="fw-bold text-gray-600 mb-5">Total users with this role: ${item.user_roles_count}</div>
                        <div class="d-flex flex-column text-gray-600">`;

                        $.each(item.access, function(index, access) {
                            html += `<div class="d-flex align-items-center py-2">
                                        <span class="bullet bg-primary me-3">
                                        </span>
                                        <span class="${access.status==2 ?`text-danger`:``}">
                                            ${access.name}
                                        </span>
                                    </div>`;
                        });

                    html +=`</div>
                            </div>
                            <div class="card-footer flex-wrap pt-0">
                                <button type="button" data-id="${item.encrypted_id}" class="btn btn-light btn-active-success my-1 list">User List</button>
                                <button type="button" data-id="${item.encrypted_id}" class="btn btn-light btn-active-success my-1 add">Add User</button>
                                <button type="button" data-id="${item.encrypted_id}" class="btn btn-light btn-active-primary my-1 edit">Edit Role</button>
                                </div>
                            </div>
                        </div>`;
                });
                $('.role-list').empty().append(html);
            }
        })
        .catch((error) => {
            console.log(error)
            Alert.alert('error',"Something went wrong. Try again later", false);
        })
        .finally(() => {

        });
    }

    function loadRoleDetails(role_id)
    {
        _request.get(`/user-management/role-list/list?role_id=${encodeURIComponent(role_id)}`).then((res) => {
            if(res.status == 'success'){
                if(res.status == 'success'){
                    let payload = JSON.parse(window.atob(res.payload));
                    let html='';

                    html+= `<tr>
                        <td class="text-gray-800">
                            Administrator Access
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Allows a full access to the system"></i>
                        </td>
                        <td>
                            <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                <input class="form-check-input" type="checkbox" value="" name="select_all" />
                                <span class="form-check-label" for="select_all">Select all</span>
                            </label>
                        </td>
                    </tr>`;

                    $.each(payload, function (index, item) {
                        $('input[name="role_name"]').val(item.role).attr('data-id',item.encrypted_id);
                        $.each(item.access, function (accessIndex, accessItem) {
                            html +=`<tr>
                            <td>
                                <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="system_file"
                                        data-id="${accessItem.encrypted_id}" ${accessItem.status==1?'checked':''}/>
                                    <label class="form-check-label text-gray-800" for="permission_${accessItem.encrypted_id}">${accessItem.name}</label>
                                </div>
                            </td><td>`;
                            $.each(accessItem.file_layer, function (fileIndex, fileItem) {
                                html +=`<tr class="check-all permission_access_${fileItem.encrypted_id}">
                                <td class="text-end">${fileItem.name}</td><td><div class="d-flex">`;

                                html += `<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                <input class="form-check-input" type="checkbox" name="file_layer" data-id="${fileItem.encrypted_id}"
                                        data-role="${fileItem.name}" ${fileItem.status==1?'checked':'1'}/>
                                <span class="form-check-label">View</span>
                                    </label>`;

                                html +=`</div></td></tr>`;
                            });
                        });
                        html +='</td></tr>';
                    });
                    $('#role_details_table > tbody').empty().append(html)
                }
            }
        }).catch((error) => {
            console.log(error)
            Alert.alert('error',"Something went wrong. Try again later", false);
        })
        .finally(() => {
            modal_state('#modal_edit_role','show');
        });


        $(`#role_details_table`).ready(function() {

            $(`#role_details_table`).on('click','input[name="system_file"]',function(e){
                e.stopImmediatePropagation();
                // e.preventDefault();

                let _this = $(this);
                let id    =_this.attr('data-id');

                let formData = new FormData;
                formData.append('file_id',id);
                formData.append('role_id',role_id);
                formData.append('status',$(this).is(":checked")?1:2);
                _request.post('/hris/admin/user_management/role_list/update_system_file',formData)
                .then((res) => {
                    Alert.toast(res.status,res.message);
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                });
            });

            $(`#role_details_table`).on('click','input[name="file_layer"]',function(e){
                e.stopImmediatePropagation();
                // e.preventDefault();

                let _this = $(this);
                let id    =_this.attr('data-id');

                let formData = new FormData;
                formData.append('layer_id',id);
                formData.append('role_id',role_id);
                formData.append('status',$(this).is(":checked")?1:2);
                _request.post('/hris/admin/user_management/role_list/update_file_layer',formData)
                .then((res) => {
                    Alert.toast(res.status,res.message);
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                });
            });

            $(`#role_details_table`).on('click','input[name="select_all"]',function(e){
                e.stopImmediatePropagation();

            });

        })
    }

    _page.on('click','button.add',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        let _this = $(this).attr('data-id');
        dtEnrollUser(_this).init();

        modal_state('#modal_add_user','show');
    });

    _page.on('click','button.edit',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        let _this = $(this).attr('data-id');
        loadRoleDetails(_this)
    });

    _page.on('click','button.list',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        let _this = $(this).attr('data-id');
        dtUserList(_this).init();

        modal_state('#modal_user_list','show');
    });

    $(async function () {
        if (!page_block.isBlocked()) {
            page_block.block();
        }
        loadRoles();

        setTimeout(() => {
            page_block.release();
        }, 300);

    });

}
