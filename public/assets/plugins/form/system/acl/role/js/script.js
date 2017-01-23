/**
 *
 */

;
(function($){


    $.widget( "hosh.plugin_system_acl_role", $.hosh.plugin_system_form, {

        options: {
            url_task:'',
            text:{},
        },

        _create: function() {
        },

        _init: function() {

            var that = this;
            var options = this.options;

            this.element.on('change','.filter_aclvalues input[name=bdeny]',function(e){
                that._findAclValues();
            }).on('keyup','.filter_aclvalues input[name=search]',function(e){
                that._findAclValues();
            }).on('click','[data-task=setaclrolevalue]',function(e){
                var target = $(this).attr('data-target');
                var row = that.element.find('.item-acl-'+target);
                var rel = eval('('+row.attr('rel')+')');
                if (rel.bdeny){
                    $('#setRoleModal').find('select[name=bdeny]').val(rel.bdeny);
                }else{
                    $('#setRoleModal').find('select[name=bdeny]').val(0);
                }
                if (rel.dtfrom){
                    $('#setRoleModal').find('input[name=dtfrom]').val(rel.dtfrom);
                }else{
                    $('#setRoleModal').find('input[name=dtfrom]').val('');
                }
                if (rel.dttill){
                    $('#setRoleModal').find('input[name=dttill]').val(rel.dttill);
                }else{
                    $('#setRoleModal').find('input[name=dttill]').val('');
                }
                $('#setRoleModal').find('.captionacl').html('"'+row.find('.scaption-item a').html()+'"');
                $('#setRoleModal').find('input[name=idaclvalue]').val(target);

                $('#setRoleModal').modal('show');
                return false;
            }).on('click','[data-task=saveaclrolevalue]',function(e){
                var idaclvalue = $('#setRoleModal').find('input[name=idaclvalue]').val();
                var bdeny = $('#setRoleModal').find('select[name=bdeny]').val();
                var dtfrom = $('#setRoleModal').find('input[name=dtfrom]').val();
                var dttill = $('#setRoleModal').find('input[name=dttill]').val();
                that._setTask({
                    data: '&task=Task_SetAcl&format=json&idaclvalue='+idaclvalue+'&bdeny='+bdeny+'&dtfrom='+dtfrom+'&dttill='+dttill,
                    done:function(response){
                        var data = $.parseJSON(response);
                        if (data.result){
                            that.element.find('.list_aclvalues').html(data.result.list);
                            $('#setRoleModal').modal('hide');
                            that._findAclValues();
                            that._refresh();
                        }else if (data.error){
                            that._alert(data.error);
                        }else{
                            that._alert('Ошибка при выполнении действия');
                        }
                    },
                    always:function(response) {
                    },
                });
                return false;
            }).on('click','[data-task=removeaclrole]',function(e){
                var target = $(this).attr('data-target');
                    that._confirm('Удалить настройку доступа ?', function () {

                                that._setTask({
                                    data: '&task=Task_RemoveAcl&format=json&idaclvalue=' + target,
                                    done: function (response) {
                                        var data = $.parseJSON(response);
                                        if (data.result) {
                                            that.element.find('.list_aclvalues').html(data.result.list);
                                            that._findAclValues();
                                            that._refresh();
                                        } else if (data.error) {
                                            that._alert(data.error);
                                        } else {
                                            that._alert('Ошибка при выполнении действия');
                                        }
                                    },
                                    always: function (response) {
                                    },
                                });
                            }

                    );


                return false;
            });

            this.element.find('#setRoleModal input[name=dtfrom]').datetimepicker({
                format:'Y-m-d H:i:s',
                onShow:function( ct ){
                    this.setOptions({
                        maxDate:that.element.find('#setRoleModal input[name=dttill]').val()?that.element.find('#setRoleModal input[name=dttill]').val():false
                    })
                }
            });
            that.element.find('#setRoleModal input[name=dttill]').datetimepicker({
                format:'Y-m-d H:i:s',
                onShow:function( ct ){
                    this.setOptions({
                        minDate:that.element.find('#setRoleModal input[name=dtfrom]').val()?that.element.find('#setRoleModal input[name=dtfrom]').val():false
                    })
                }
            });

            this._refresh();

        },

        _refresh: function(){
            this.element.find('[data-toggle=tooltip]').bootstrapTooltip();
        },

        _findAclValues: function(){
            var bdeny = [];
            var filter = [];
            $.each(this.element.find('.filter_aclvalues input[name=bdeny]'), function (n, val) {
                if ($(val).prop('checked') == true){
                    var item_object = {'isshow': true, 'value': $(val).val()};
                    bdeny.push(item_object);
                    filter.isbdeny = true;
                }else{
                    var item_object = {'isshow': false, 'value': $(val).val()};
                    bdeny.push(item_object);
                }
            });
            if (filter.isbdeny) {
                for (var i in bdeny) {
                    if (bdeny[i].isshow) {
                        this.element.find('.list_aclvalues .item-value[data-bdeny=' + bdeny[i].value + ']').show().addClass('showvalue');
                    }else{
                        this.element.find('.list_aclvalues .item-value[data-bdeny=' + bdeny[i].value + ']').hide().removeClass('showvalue');
                    }
                }
            }else{
                this.element.find('.list_aclvalues .item-value').show().addClass('showvalue');
            }

            var search = this.element.find('.filter_aclvalues input[name=search]').val();
            if (search.length >0 ) {
                var regexp = eval('/' + search + '/i');
                $.each(this.element.find('.list_aclvalues .item-value.showvalue'), function (key, val) {
                    if (regexp.test($(val).find('.scaption-item').html())) {

                    }else{
                        $(val).hide();
                    }
                });
            }
        },

        _setTask: function(param){
            var that = this;
            param = $.extend({}, {
                url:this.options.url_task,
            }, {

            }, param);
            this._setAjax(param);
        },



    });

})(jQuery);

