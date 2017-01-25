/**
 * 
 */
;
(function($){
	
	$.widget( "hosh.manager_leftmenu", $.hosh.plugin_system_form, {	
		
		options: {		
			url: '',
			url_action: '',
			container: '.container-list',
		},

		_create: function() {
			
		},
		
		_init: function() {		
			var that = this;
			var options = this.options;
			
			this._tooltip();
			
			this.element.on('change','.pagination select',function(e){
				var val = $(this).val();
				that._request('page='+val);
				return false;
			}).on('click','[data-task=action_object]',function(e){
				var action = $(this).attr('data-action');
				var target = $(this).attr('data-target');
				that._confirm('Выполнить действие "'+$(this).text()+'" ?',
					function(){
                    that._requestAction(action,'target='+target);
				});
				return false;
			});
		},
		
		_request: function(data){
			var that = this;
			var options = this.options;
			this._setAjax({
				type:'get',
				url:options.url,
				data:data,
				done:function(response){
					that.element.find(options.container).html(response);
					that._tooltip();
				},
				
			});
			return false;
		},		
		
		_requestAction: function(action,data){
			var get_data = '';
			if (this.element.find('.pagination select').length > 0){
				get_data += '&page='+this.element.find('.pagination select').val();
			}
			if ($('.leftmenu form').length>0){
				data += '&'+$('.leftmenu form').serialize();
			}
			
			var that = this;
			var options = this.options;			
			this._setAjax({
				type:'post',
				url:options.url_action+'&action='+action+get_data,
				data:data,
				done:function(response){
					that.element.find(options.container).html(response);
					that._tooltip();
				},
				
			});
			return false;
		},		
	});	
	
})(jQuery);