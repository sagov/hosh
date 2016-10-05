/**
 * 
 */
;
(function($){
	
	$.widget( "hosh.manager_filtermenu_single", $.hosh.plugin_system_form, {	
		
		options: {		
			url_search:'',	
		},

		_create: function() {		
		},
		
		_init: function() {		
			var that = this;
			var options = this.options;
			
			this.element.on('submit','form',function(e){
				
				that._setAjax({
					type:'post',
					url:options.url_search,
					data:that.element.find('form').serialize(),
					done:function(response){
						$('.menucontent').html(response);
					},
					
				});
				return false;
			});
		}	
			
	});	
	
})(jQuery);
