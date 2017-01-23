/**
 *
 */
;
(function ($) {


    $.widget("hosh.plugin_system_form", {
        version: "1.0.1",
        _jqxhr: '',
        options: {},
        _create: function () {

        },
        _init: function () {

        },
        _setAjax: function (param) {
            var that = this;
            param = $.extend({}, {
                type: 'post',
                url: '',
                data: '',
                done: function (response) {
                },
                fail: function (response) {
                },
                always: function (response) {
                },
            }, {}, param);

            if (this._jqxhr != '') this._jqxhr.abort();
            this._startLoading();
            this._jqxhr = $.ajax({
                url: param.url,
                type: param.type,
                data: param.data
            })
                .done(function (response) {
                    param.done(response);
                })
                .fail(function (response) {
                    param.fail(response);
                })
                .always(function (response) {
                    that._removeLoading();
                    param.always(response);
                });
        },
        _startLoading: function () {
            if (this.element.find('.hosh-loading').length == 0) {
                this.element.append('<div class="hosh-loading"><i class="fa fa-spinner fa-pulse"></i></div>');
            }
        },

        _removeLoading: function () {
            this.element.find('.hosh-loading').remove();
        },
        _tooltip: function () {
            this.element.find('input,a,textarea,select,button').bootstrapTooltip({
                placement: 'auto',
                trigger: 'hover focus',
            });
        },
        _alert: function (text) {
            if ($.BootstrapModal.length == 0) {
                alert(text);
                return;
            }
            var ModalAlert = new $.BootstrapModal({
                id: 'ModalAlert',
                title: false,
                close: false,
                content: text,
                dialog_size: '',
                footer: '<button class="btn btn-primary" aria-hidden="true" data-dismiss="modal">Close</button>',
            });
            ModalAlert.modal();
        },

        _confirm: function (text, callback, fail) {
            if ($.BootstrapModal.length == 0) {
                if (confirm(text)) {
                    callback();
                } else {
                    if (fail) {
                        fail();
                    }
                }
                return;
            }
            var ModalConfirm = new $.BootstrapModal({
                id: 'ModalConfirm',
                title: false,
                close: false,
                content: text,
                dialog_size: '',
                footer: '<button class="btn btn-primary" data-task="callbackconfirm">Ok</button> &nbsp; <button class="btn btn-default" data-task="cancelconfirm" aria-hidden="true" data-dismiss="modal">Close</button>',
            });
            ModalConfirm.modal();

            $('#ModalConfirm button.btn[data-task=callbackconfirm]').click(function () {
                callback();
                ModalConfirm.modal('hide');
            });
            if (fail) {
                $('#ModalConfirm button.btn[data-task=cancelconfirm]').click(function () {
                    fail();
                });
            }
        }


    });

})(jQuery);