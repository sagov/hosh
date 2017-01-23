/**
 *
 */

;
(function ($) {


    $.widget("hosh.plugin_system_acl_value", $.hosh.plugin_system_form, {

        options: {
            url_task: '',
            text: {"update_list": "Update"},
        },

        _create: function () {
        },

        _init: function () {
            this._tooltip();

            var that = this;
            var options = this.options;

            this.element.on('click', '[data-task=setaclrolevalue]', function (e) {
                var role = $(this).attr('data-target');
                var row = that.element.find('.item-role-' + role);
                var rel = eval('(' + row.attr('rel') + ')');
                if (rel.bdeny) {
                    $('#setRoleModal').find('select[name=bdeny]').val(rel.bdeny);
                } else {
                    $('#setRoleModal').find('select[name=bdeny]').val(0);
                }
                if (rel.dtfrom) {
                    $('#setRoleModal').find('input[name=dtfrom]').val(rel.dtfrom);
                } else {
                    $('#setRoleModal').find('input[name=dtfrom]').val('');
                }
                if (rel.dttill) {
                    $('#setRoleModal').find('input[name=dttill]').val(rel.dttill);
                } else {
                    $('#setRoleModal').find('input[name=dttill]').val('');
                }
                $('#setRoleModal').find('.valuerole').html('"' + row.find('.caption-role').html() + '"');
                $('#setRoleModal').find('input[name=idrole]').val(role);

                $('#setRoleModal').modal('show');
                return false;
            }).on('click', '[data-task=saveaclrolevalue]', function (e) {
                var idrole = $('#setRoleModal').find('input[name=idrole]').val();
                var bdeny = $('#setRoleModal').find('select[name=bdeny]').val();
                var dtfrom = $('#setRoleModal').find('input[name=dtfrom]').val();
                var dttill = $('#setRoleModal').find('input[name=dttill]').val();
                that._setTask({
                    data: '&task=Task_AddAclRole&format=json&idowner=' + idrole + '&bdeny=' + bdeny + '&dtfrom=' + dtfrom + '&dttill=' + dttill,
                    done: function (response) {
                        var data = $.parseJSON(response);
                        if (data.result) {
                            $('#setRoleModal').modal('hide');
                            that._UpdateAclRole();
                        } else if (data.error) {
                            that._alert(data.error);
                        } else {
                            that._alert('Ошибка при выполнении действия');
                        }
                    },
                    always: function (response) {
                    },
                });
                return false;
            }).on('click', '[data-task=removeaclrole]', function (e) {
                var idowner = $(this).attr('data-target');
                that._confirm('Удалить настройку доступа ?', function () {
                    that._setTask({
                        data: '&task=Task_RemoveAclRole&format=json&idowner=' + idowner,
                        done: function (response) {
                            var data = $.parseJSON(response);
                            if (data.result) {
                                that._UpdateAclRole();
                            } else if (data.error) {
                                that._alert(data.error);
                            } else {
                                that._alert('Ошибка при выполнении действия');
                            }
                        },
                        always: function (response) {
                        },
                    });
                });
                return false;
            }).on('click', '[data-task=setacluservalue]', function (e) {
                var idowner = $(this).attr('data-target');
                var suser = '';
                var bdeny = 0;
                var dtfrom = '';
                var dttill = '';
                if (idowner) {
                    var row = that.element.find('.item-user-' + idowner);
                    var rel = eval('(' + row.attr('rel') + ')');
                    suser = row.find('.caption-user').html();
                    if (rel.bdeny) {
                        bdeny = rel.bdeny;
                    }
                    if (rel.dtfrom) {
                        dtfrom = rel.dtfrom;
                    }
                    if (rel.dttill) {
                        dttill = rel.dttill;
                    }
                }
                $('#setUserModal').find('input[name=dttill]').val(dttill);
                $('#setUserModal').find('input[name=dtfrom]').val(dtfrom);
                $('#setUserModal').find('select[name=bdeny]').val(bdeny);
                $('#setUserModal').find('#searchuser').val(suser);
                $('#setUserModal').find('input[name=iduser]').val(idowner);

                $('#setUserModal').modal('show');
                return false;
            }).on('click', '[data-task=saveacluservalue]', function (e) {
                var idowner = $('#setUserModal').find('input[name=iduser]').val();
                var bdeny = $('#setUserModal').find('select[name=bdeny]').val();
                var dtfrom = $('#setUserModal').find('input[name=dtfrom]').val();
                var dttill = $('#setUserModal').find('input[name=dttill]').val();
                that._setTask({
                    data: '&task=Task_AddAclUser&format=json&idowner=' + idowner + '&bdeny=' + bdeny + '&dtfrom=' + dtfrom + '&dttill=' + dttill,
                    done: function (response) {
                        var data = $.parseJSON(response);
                        if (data.result) {
                            $('#setUserModal').modal('hide');
                            that._UpdateAclUser();
                        } else if (data.error) {
                            that._alert(data.error);
                        } else {
                            that._alert('Ошибка при выполнении действия');
                        }
                    },
                    always: function (response) {
                    },
                });
                return false;
            }).on('click', '[data-task=removeacluser]', function (e) {
                var idowner = $(this).attr('data-target');
                that._confirm('Удалить настройку доступа ?',function(){
                    that._setTask({
                        data: '&task=Task_RemoveAclUser&format=json&idowner=' + idowner,
                        done: function (response) {
                            var data = $.parseJSON(response);
                            if (data.result) {
                                that._UpdateAclUser();
                            } else if (data.error) {
                                that._alert(data.error);
                            } else {
                                that._alert('Ошибка при выполнении действия');
                            }
                        },
                        always: function (response) {
                        },
                    });
                });
                return false;
            }).on('keyup', 'input[name=usersearch]', function (e) {
                var value = $.trim($(this).val());

                if (value.length == 0) {
                    that.element.find('#aclvalueuser-table table.items-user tr').show();
                    return;
                }
                $('#aclvalueuser-table').prepend('<div class="hosh-loading-table"><span class="hosh-loading-table-content"><i class="fa fa-spinner fa-pulse"></i> Поиск</div></div>');
                var regexp = eval('/^' + value + '/i');
                $.each(that.element.find('#aclvalueuser-table table.items-user tr'), function (key, val) {
                    if (regexp.test($(val).find('.caption-user').html()) || regexp.test($(val).find('.caption-username').html())) {
                        $(val).show();
                    } else {
                        $(val).hide();
                    }
                });
                $('#aclvalueuser-table .hosh-loading-table').remove();
            });

            this.element.find("#searchuser").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        dataType: "json",
                        data: {
                            term: request.term,
                        },
                        type: 'Get',
                        contentType: 'application/json; charset=utf-8',
                        xhrFields: {
                            withCredentials: true
                        },
                        crossDomain: true,
                        cache: true,
                        url: options.url_task + "&task=Task_SearchUser&format=json",
                        success: function (data) {

                            //call the filter here
                            response($.ui.autocomplete.filter(data.result, request.term));
                        },
                        error: function (data) {

                        }
                    });

                },
                minLength: 2,
                delay: 800,
                select: function (event, ui) {
                    $('#setUserModal').find('input[name=iduser]').val(ui.item.id);
                }
            });


            $('#setRoleModal').find('input[name=dtfrom]').datetimepicker({
                format: 'Y-m-d H:i:s',
                onShow: function (ct) {
                    this.setOptions({
                        maxDate: $('#setRoleModal').find('input[name=dttill]').val() ? $('#setRoleModal').find('input[name=dttill]').val() : false
                    })
                }
            });
            $('#setRoleModal').find('input[name=dttill]').datetimepicker({
                format: 'Y-m-d H:i:s',
                onShow: function (ct) {
                    this.setOptions({
                        minDate: $('#setRoleModal').find('input[name=dtfrom]').val() ? $('#setRoleModal').find('input[name=dtfrom]').val() : false
                    })
                }
            });

            $('#setUserModal').find('input[name=dtfrom]').datetimepicker({
                format: 'Y-m-d H:i:s',
                onShow: function (ct) {
                    this.setOptions({
                        maxDate: $('#setUserModal').find('input[name=dttill]').val() ? $('#setUserModal').find('input[name=dttill]').val() : false
                    })
                }
            });
            $('#setUserModal').find('input[name=dttill]').datetimepicker({
                format: 'Y-m-d H:i:s',
                onShow: function (ct) {
                    this.setOptions({
                        minDate: $('#setUserModal').find('input[name=dtfrom]').val() ? $('#setUserModal').find('input[name=dtfrom]').val() : false
                    })
                }
            });

            this._refresh();

        },

        _refresh: function () {
            this.element.find('[data-toggle=tooltip]').bootstrapTooltip();
        },

        _setTask: function (param) {
            var that = this;
            param = $.extend({}, {
                url: this.options.url_task,
            }, {}, param);
            this._setAjax(param);
        },

        _UpdateAclRole: function () {
            var that = this;
            $('#aclvaluerole-table').prepend('<div class="hosh-loading-table"><span class="hosh-loading-table-content"><i class="fa fa-spinner fa-pulse"></i> ' + this.options.text.update_list + '</div></div>');
            this._setTask({
                data: '&task=Task_ViewAclRole&format=json',
                done: function (response) {
                    var data = $.parseJSON(response);
                    if (data.result) {
                        $('#aclvaluerole-table').html(data.result);
                        that._refresh();
                    }
                },
            });
        },
        _UpdateAclUser: function () {
            var that = this;
            $('#aclvalueuser-table').prepend('<div class="hosh-loading-table"><span class="hosh-loading-table-content"><i class="fa fa-spinner fa-pulse"></i> ' + this.options.text.update_list + '</div></div>');
            this._setTask({
                data: '&task=Task_ViewAclUser&format=json',
                done: function (response) {
                    var data = $.parseJSON(response);
                    if (data.result) {
                        $('#aclvalueuser-table').html(data.result);
                        that._refresh();
                    }
                },
            });
        }
    });

})(jQuery);	