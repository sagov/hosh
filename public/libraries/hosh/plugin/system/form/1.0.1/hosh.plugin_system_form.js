/**
 * 
 */
;
(function($){
	
	
$.widget( "hosh.plugin_system_form", {
	version: "1.0.1",
	_jqxhr: '',
	options:{
		
	},
	_create:function(){	
		
	},
	_init:function(){
		
	},
	_setAjax: function(param){
		var that = this;
		param = $.extend({}, {
			type:'post',
			url:'',
			data:'',
			done:function(response){},
			fail:function(response){},
			always:function(response){},			
		}, {
			
		}, param);
		
		if (this._jqxhr != '') this._jqxhr.abort();
		this._startLoading();
		this._jqxhr = $.ajax( {
			url: param.url,
			type: param.type,
			data: param.data
			} )
			.done(function(response) {
				param.done(response);				
			})
			.fail(function(response) { 
				param.fail(response);
			})
			.always(function(response) {
				that._removeLoading();
				param.always(response);				
			});
	},
	_startLoading: function(){
		if (this.element.find('.hosh-loading').length  == 0)
		{
			this.element.append('<div class="hosh-loading"><i class="fa fa-spinner fa-pulse"></i></div>');			
		}
	},
	
	_removeLoading: function(){
		this.element.find('.hosh-loading').remove();
	},
	_tooltip:function(){
		this.element.find('input,a,textarea,select,button').bootstrapTooltip({
			placement:'auto',
			trigger:'hover focus',
		});
	}
	

});	

})(jQuery);