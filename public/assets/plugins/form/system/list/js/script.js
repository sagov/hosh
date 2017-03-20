/**
 *
 */
;
(function ($) {


    $.widget("hosh.plugin_system_list_default", $.hosh.plugin_system_form, {

        options: {
            url_task: '',
            menulevel: '.menu-nav',
            elementmodal: 'ElementFormModal',
            url_element: '',
            url_displaygroup: '',
            url_preview: ''
        },

        _create: function () {
        },

        _init: function () {
            this._tooltip();
            this.refresh();
        },

        init: function () {
            var that = this;
            var options = this.options;

            if (this.element.find(options.menulevel).length) {
                var offset_menu = this.element.find(options.menulevel).eq(0).offset();

                $(window).on('resize', function () {
                    offset_menu = that.element.find(options.menulevel).eq(0).offset();
                });

                this.element.find(options.menulevel).affix({
                    offset: {
                        top: offset_menu.top
                    }
                });
            }

            $('body').scrollspy({
                target: options.menulevel,
                offset: 70
            });

            $('[data-toggle=dropdown]').dropdown();

            this.element.on('click', options.menulevel + ' li a', function (e) {
                var href = $(this).attr('href');
                var offset = $(href).offset();
                var toppos = offset.top;
                var height_nav = parseInt(that.element.find('.menu-nav').height());
                if (that.element.find(options.menulevel).hasClass('affix')) {
                    height_nav = height_nav + 10;
                } else {
                    toppos = toppos - height_nav;
                }
                $('html, body').animate({scrollTop: toppos - height_nav}, 'fast');
                e.stopPropagation();
                return false;
            });


            this.element.on('click', '[data-task=new_elementform]', function (e) {
                $(this).stop();
                var target = $(this).attr('data-target');
                that._NewElementForm(target);
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=edit_elementform]', function (e) {
                $(this).stop();
                var target = $(this).attr('data-target');
                that._EditElemenForm(target);
                e.stopPropagation();
                return false;

            }).on('click', '[data-task=delete_elementform]', function (e) {
                $(this).stop();
                var target = $(this).attr('data-target');
                that._DeleteElementForm(target);
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=recovery_elementform]', function (e) {
                $(this).stop();
                var target = $(this).attr('data-target');
                that._RecoveryElementForm(target);
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=public_elementform]', function (e) {
                $(this).stop();
                var target = $(this).attr('data-target');
                that._PublicElementForm(target);
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=unpublic_elementform]', function (e) {
                $(this).stop();
                var target = $(this).attr('data-target');
                that._UnPublicElementForm(target);
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=remove_elementform]', function (e) {
                $(this).stop();
                var target = $(this).attr('data-target');
                that._RemoveElementForm(target);
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=export-xml]', function (e) {
                $(this).stop();
                that._Export('xml');
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=copy]', function (e) {
                $(this).stop();
                that._Copy();
                e.stopPropagation();
                return false;

            }).on('click', '[data-task=form-lock]', function (e) {
                $(this).stop();
                that._SetLock();
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=form-unlock]', function (e) {
                $(this).stop();
                that._SetUnLock();
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=form-lock-remove]', function (e) {
                $(this).stop();
                that._LockRemove();
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=export-xml-commit]', function (e) {
                $(this).stop();
                that._ExportCommit('xml');
                e.stopPropagation();
                return false;
            }).on('click', '[data-task=form-preview]', function (e) {
                $(this).stop();
                var title = $(this).attr('data-title');
                that._Preview({title: title});
                e.stopPropagation();
                return false;
            });

            if ($('.menuform-level').length > 0) {

                $.each($('.menuform-level [data-task=element-slide]'), function (n, val) {
                    var target = $(val).attr('data-target');
                    if (that.element.find(target).length == 0) {
                        $(val).parent().parent().hide();
                    }
                });

                $('.menuform-level').on('click', '[data-task=element-slide]', function (e) {
                    var target = $(this).attr('data-target');

                    var offset = that.element.find(target).eq(0).offset();
                    if (offset.top > 0) {
                        $('html,body').animate({scrollTop: offset.top - 50}, 'fast');
                    }
                    return false;
                }).on('click', '[data-task=new_elementform]', function (e) {
                    that.element.find('[data-task=new_elementform]').trigger('click');
                    return false;
                });

                $('.menuform-level').affix({
                    offset: {
                        top: function () {
                            var offset_menuform = $('.menuform-level').eq(0).offset();
                            return (this.top = offset_menuform.top)
                        },
                        bottom: function () {
                            return (this.bottom = $('.footer').outerHeight(true) + 30)
                        }
                    }
                });
            }

        },

        _EditElemenForm: function (id) {
            var param = [];
            param.url = this.options.url_element + '&id=' + id;
            this._ElementForm(param);

        },

        _NewElementForm: function (idowner) {
            var param = [];
            param.url = this.options.url_element + '&idowner=' + idowner;
            this._ElementForm(param);
        },

        _ElementForm: function (param) {
            var options = this.options;
            param = $.extend({}, {
                id: options.elementmodal,
                close: false,
                title: false,
                iframe: false,
                url: ''
            }, {}, param);
            var BootstrapModal = new $.BootstrapModal(param);
            BootstrapModal.modal({backdrop: 'static'});
            $('#' + options.elementmodal).on('hidden.bs.modal', function (e) {
                $('#' + options.elementmodal + 'Affix').remove();
                $('.dropdown-toggle').dropdown();
            }).on('shown.bs.modal', function (e) {
                if ($('#' + options.elementmodal + 'Affix').length == 0) {
                    $('body').append('<div id="' + options.elementmodal + 'Affix"></div>');
                }
            });
        },

        _Preview: function (param) {
            var options = this.options;
            param = $.extend({}, {
                id: 'FormPreviewModal',
                close: true,
                title: '&nbsp;',
                iframe: true,
                url: options.url_preview
            }, {}, param);
            var BootstrapModal = new $.BootstrapModal(param);
            BootstrapModal.modal();
        },


        _DeleteElementForm: function (id) {
            var that = this;
            that._confirm('Удалить элемент ' + that.element.find('#item-element-' + id + ' .elementform_name').text() + ' ?',
                function () {
                    that._setTask({
                        data: '&task=Task_DeleteElement&format=json&idelement=' + id,
                        done: function (response) {
                            var data = $.parseJSON(response);
                            if (data.result) {
                                setTimeout(function () {
                                    that.UpdateListElement();
                                }, 1);
                            } else if (data.error) {
                                that._alert(data.error);
                            } else {
                                that._alert('Ошибка при выполнении действия');
                            }
                        },
                    });
                });
        },

        _RemoveElementForm: function (id) {
            var that = this;
            that._confirm('Удалить элемент безвозвратно ' + that.element.find('#item-element-' + id + ' .elementform_name').text() + ' ?',
                function () {
                    that._setTask({
                        data: '&task=Task_RemoveElement&format=json&idelement=' + id,
                        done: function (response) {
                            var data = $.parseJSON(response);
                            if (data.result) {
                                setTimeout(function () {
                                    that.UpdateListElement();
                                }, 1);
                            } else if (data.error) {
                                that._alert(data.error);
                            } else {
                                that._alert('Ошибка при выполнении действия');
                            }
                        },
                    });
                });
        },

        _RecoveryElementForm: function (id) {
            var that = this;
            that._confirm('Восстановить элемент ' + that.element.find('#item-element-' + id + ' .elementform_name').text() + ' ?',
                function () {
                    that._setTask({
                        data: '&task=Task_RecoveryElement&format=json&idelement=' + id,
                        done: function (response) {
                            var data = $.parseJSON(response);
                            if (data.result) {
                                setTimeout(function () {
                                    that.UpdateListElement();
                                }, 1);
                            } else if (data.error) {
                                that._alert(data.error);
                            } else {
                                that._alert('Ошибка при выполнении действия');
                            }
                        },
                    });
                });
        },

        _PublicElementForm: function (id) {
            var that = this;
            that._confirm('Опубликовать элемент ' + that.element.find('#item-element-' + id + ' .elementform_name').text() + ' ?',
                function () {
                    that._setTask({
                        data: '&task=Task_PublicElement&format=json&idelement=' + id,
                        done: function (response) {
                            var data = $.parseJSON(response);
                            if (data.result) {
                                setTimeout(function () {
                                    that.UpdateListElement();
                                }, 1);
                            } else if (data.error) {
                                that._alert(data.error);
                            } else {
                                that._alert('Ошибка при выполнении действия');
                            }

                        },
                    });
                });
        },

        _UnPublicElementForm: function (id) {
            var that = this;
            that._confirm('Снять с публикации элемент ' + that.element.find('#item-element-' + id + ' .elementform_name').text() + ' ?',
                function () {
                    that._setTask({
                        data: '&task=Task_UnPublicElement&format=json&idelement=' + id,
                        done: function (response) {
                            var data = $.parseJSON(response);
                            if (data.result) {
                                setTimeout(function () {
                                    that.UpdateListElement();
                                }, 1);
                            } else if (data.error) {
                                that._alert(data.error);
                            } else {
                                that._alert('Ошибка при выполнении действия');
                            }
                        },
                    });
                });
        },



        _Export: function (type) {
            document.location = this.options.url_task + '&task=Task_Export';
        },

        _ExportCommit: function (type) {
            var that = this;
            that._confirm('Выполнить commit?',
                function () {
                    that._setTask({
                        data: '&task=Task_ExportCommit&format=json',
                        done: function (response) {
                            var data = $.parseJSON(response);
                            if (data.result) {
                                var BootstrapModal = new $.BootstrapModal({
                                    id: 'BootstrapModalForm',
                                    title: 'Commit',
                                    content: 'Commit прошел успешно!',
                                    dialog_size: '',
                                    footer: '<button class="btn btn-primary" aria-hidden="true" data-dismiss="modal">Закрыть</button>',
                                });
                                BootstrapModal.modal();
                            } else if (data.error) {
                                that._alert(data.error);
                            } else {
                                that._alert('Ошибка при выполнении действия');
                            }
                        },
                    });
                });
        },

        _Copy: function () {
            var that = this;
            that._confirm('Подтвердите действие копирования',
                function () {
                    that._setTask({
                        data: '&task=Task_Copy&format=json',
                        done: function (response) {
                            var data = $.parseJSON(response);
                            if (data.result) {
                                var BootstrapModal = new $.BootstrapModal({
                                    id: 'BootstrapModalForm',
                                    title: 'Копирование формы',
                                    content: 'Копирование прошло успешно! Создана новая форма # ' + data.result,
                                    dialog_size: '',
                                    footer: '<a href="?controller=ref_list&action=view&id=' + data.result + '" class="btn btn-primary">Перейти к новой форме #' + data.result + '</a> &nbsp; <button class="btn btn-primary" aria-hidden="true" data-dismiss="modal">Закрыть</button>',
                                });
                                BootstrapModal.modal();
                            } else if (data.error) {
                                that._alert(data.error);
                            } else {
                                that._alert('Ошибка при выполнении действия');
                            }
                        },
                    });
                });
        },



        _SetLock: function () {
            var that = this;
            that._setTask({
                data: '&task=Task_Edit&format=json',
                done: function (response) {
                    var data = $.parseJSON(response);
                    if (data.result) {
                        document.location.reload();
                    } else if (data.error) {
                        that._alert(data.error);
                    } else {
                        that._alert('Ошибка при выполнении действия');
                    }
                },
            });

        },

        _SetUnLock: function () {
            var that = this;
            that._setTask({
                data: '&task=Task_EndEdit&format=json',
                done: function (response) {
                    var data = $.parseJSON(response);
                    if (data.result) {
                        document.location.reload();
                    } else if (data.error) {
                        that._alert(data.error);
                    } else {
                        that._alert('Ошибка при выполнении действия');
                    }
                },
            });

        },

        _LockRemove: function () {
            var that = this;
            that._setTask({
                data: '&task=Task_LockRemove&format=json',
                done: function (response) {
                    var data = $.parseJSON(response);
                    if (data.result) {
                        document.location.reload();
                    } else if (data.error) {
                        that._alert(data.error);
                    } else {
                        that._alert('Ошибка при выполнении действия');
                    }
                },
            });

        },

        refresh: function () {
            var that = this;
            this._tooltip();
            //$('.dropdown-toggle').dropdown();


            this.element.find(".displaygroups-element .displaygroup-element table").sortable({
                placeholder: "ui-state-highlight",
                items: "tr",
                handle: "td.setorder",
                opacity: 0.6,
                cursor: "move",
                revert: false,
                stop: function (event, ui) {
                    var that2 = this;
                    that._confirm("Изменить позицию?",function(){
                        that._setChangeOrderElement(that2);
                    },function(){
                        $(that2).sortable("cancel");
                    });
                },
            });

            this.element.find('[data-toggle=tooltip]').bootstrapTooltip({
                placement: 'auto',
                trigger: 'hover focus',
            });
        },

        _setTask: function (param) {
            var that = this;
            param = $.extend({}, {
                url: this.options.url_task,
            }, {}, param);
            this._setAjax(param);
        },

        _setChangeOrderElement: function (obj) {
            var elements = [];
            $.each($(obj).find("tr[data-item=sortable]"), function (n, val) {
                var idgroup = val.id;
                elements[n] = idgroup.replace('item-element-', '');
            });
            this._setTask({
                data: '&task=Task_SetChangeOrderElement&format=json&rows=' + elements.join(','),
                done: function (response) {
                    var data = $.parseJSON(response);
                    if (!data.result) {
                        $(obj).sortable("cancel");
                        if (data.error) {
                            that._alert(data.error);
                        } else {
                            that._alert('Ошибка при выполнении действия');
                        }
                    }
                },
            });
        },

        _setChangeOrderDisplayGroup: function (obj) {
            var elements = [];
            $.each($(obj).find("div[data-item=sortable]"), function (n, val) {
                var idgroup = val.id;
                elements[n] = idgroup.replace('displaygroup-', '');
            });
            this._setTask({
                data: '&task=Task_SetChangeOrderDisplayGroup&format=json&rows=' + elements.join(','),
                done: function (response) {
                    var data = $.parseJSON(response);
                    if (!data.result) {
                        $(obj).sortable("cancel");
                        if (data.error) {
                            that._alert(data.error);
                        } else {
                            that._alert('Ошибка при выполнении действия');
                        }
                    }
                },
            });

        },

        UpdateListElement: function () {
            var that = this;
            this._setTask({
                data: '&task=ListElements&format=json',
                done: function (response) {
                    var data = $.parseJSON(response);
                    if (data.result) {
                        that.element.find("#elements .element-group").html(data.result);
                        that.refresh();
                    } else if (data.error) {
                        that._alert(data.error);
                    } else {
                        that._alert('Ошибка при выполнении действия');
                    }
                },
            });
        }

    });

})(jQuery);