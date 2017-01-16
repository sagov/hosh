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
            });

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





    });

})(jQuery);

