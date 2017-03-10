/**
 * 
 */
;
(function($){
	
	
	$.widget( "hosh.plugin_system_formelement_default", $.hosh.plugin_system_form, {
		
		options: {
			url_submit: '',
			url_task: '',
			elementmodal:'ElementFormModal',
			data_elements:'',
			url_loadelements: '',
		},
		_create: function(){
			this._refresh();
			this._setAffix();
			var that = this;
			var options = this.options;
			this.element.find('[data-task=saveform]').click(function(){
				$('#'+options.elementmodal).animate({scrollTop:0}, 'fast');
				that.submitForm();
				return false;
			});
			
			
			
			var texttype = '';
			this.element.find('input[name=type]').blur(function(){
				if (texttype != $(this).val()){
					that.element.find('input[name=actionpost_system_formelement]').val('');
					that.submitForm();
					return false;
				}
			}).focus(function(){
				texttype = $(this).val();
				that._addDropdownMenu(this);
			});
			
						
			this.element.find( "input[name=norder]" ).spinner({
				 min:0				 
			});
		},
		_init: function(){
			this._refresh();
			this._tooltip();
		},
		_refresh:function(){
			var that = this;
			var options = this.options;
			$('#'+options.elementmodal+'Affix').html('<div class="menu-navaffix"><div class="container">'+this.element.find('.menu-nav').html()+'</div></div>');		
			$('#'+options.elementmodal).scrollspy({ target: '.menu-navaffix',offset:40 });
			$('#'+options.elementmodal+'Affix').find('*').bootstrapTooltip({
				placement:'auto',
				trigger:'hover',
			});
			
		},
		_setAffix: function(){
			var that = this;
			var options = this.options;		
			
			$('#'+options.elementmodal+'Affix').on('click','li a',function(){
				var href = $(this).attr('href');
				var offset = that.element.find(href).position();
				if (offset.top > 0){
					$('#'+options.elementmodal).animate({scrollTop:offset.top - 20}, 'fast');
				}
				return false;
			});
			
			this.element.on('click','.menu-nav li a',function(){
				var href = $(this).attr('href');
				var offset = that.element.find(href).position();
				if (offset.top > 0){
					$('#'+options.elementmodal).animate({scrollTop:offset.top - 20}, 'fast');
				}			
				return false;
			});
			
			$('#'+options.elementmodal).on('scroll',function(){
				if (that.element.find('.menu-nav').length == 0) {return false;}
				var pos_menu = that.element.find('.menu-nav').position();
				if (pos_menu.top > 0){
				var toppos = pos_menu.top + 40;
				if ($(this).scrollTop() > toppos){
					if (that.element.find('.menu-nav').css('visibility') != 'hidden'){
						$('#'+options.elementmodal+'Affix').fadeIn(300).show();
						that.element.find('.menu-nav').css('visibility','hidden');	
					}				
				}else{				
					if (that.element.find('.menu-nav').css('visibility') != 'visible'){
						$('#'+options.elementmodal+'Affix').slideUp(300).hide();
						that.element.find('.menu-nav').css('visibility','visible');
					}
				}
				}
			});		
			
			
			$('#'+options.elementmodal+'Affix').on('click','[data-task=saveform]',function(){
				$('#'+options.elementmodal).find('button[data-task=saveform]').eq(0).trigger('click');
				return false;
			}).on('click','[data-task=close]',function(){
				$('#'+options.elementmodal).find('button[data-task=close]').eq(0).trigger('click');
			});
		},
		_addDropdownMenu: function(e){
			
			if ($(e).parent().find('.element-dropdown-menu').length > 0){
				return;
			}
			
			$(e).attr("data-toggle","dropdown");
			$(e).parent().addClass('dropdown');
			$(e).parent().append('<div class="dropdown-menu element-dropdown-menu"><div class="element-dropdown-content"></div></div>');
			$(e).parent().find('[data-toggle=dropdown]').dropdown();
			
			if (this.options.data_elements.length  == 0){
				this._loadElements(e);
			}else{
				this._setViewElementMenu(e);			
			}
			
			$(e).parent().on('click','.element-dropdown-content a[data-target=elementvalue]',function(){
				var value = $(this).attr('data-value');
				$(e).val(value);
				$(e).trigger('blur');
				
			});
			
		},
		_loadElements:function(e)
		{
			var that = this;
			$(e).parent().find('.dropdown-menu .element-dropdown-content').html('<div style="text-align:center"><i class="fa fa-refresh fa-spin"></i></div>');
			$.ajax( {
				url: that.options.url_loadelements, 
				type: "post",
				data: ''
				} )
				.done(function(response) {
					that.options.data_elements = response;
					if (e){
						that._setViewElementMenu(e);
					}
				})
				.fail(function(response) {
					$(e).parent().find('.dropdown-menu .element-dropdown-content').html("");
				})
				.always(function(response) {
					
				});
		},
		_setViewElementMenu:function(e)
		{
			$(e).parent().find('.dropdown-menu .element-dropdown-content').html(this.options.data_elements);
            $('[data-toggle="popover"]').popover();
		},
		submitForm: function(){
			var that = this;
			var options = this.options;	
			that.element.find('form').trigger('submit');
			this._setAjax({
				type:'post',
				url:options.url_submit,
				data:that.element.find('form').serialize(),
				done:function(response){
					$('#'+options.elementmodal).find('.modal-body').html(response);
					that.UpdateListElementForm();
				},
				
			});
			
		},
		
				
		UpdateListElementForm: function(){
			$("#hoshform_system_form").plugin_system_form_default();
			$("#hoshform_system_form").plugin_system_form_default("option","url_task",this.options.url_task);
			$("#hoshform_system_form").plugin_system_form_default("UpdateListElement");			
		}
	});


})(jQuery);