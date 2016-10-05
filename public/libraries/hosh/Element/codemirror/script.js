/**
 * 
 */
;
(function($){


$.fn.Ex_Lib_Hosh_Element_Codemirror = function(options){
	var object = this;
	
	options = $.extend({}, {		
		mode:[],
	}, {
		
	}, options);
	
	$(object).on('change','[name=mode_name]',function(){
		var valuemode = $(this).val();
		_setModeType(valuemode);
	});
	
	function _setModeType(valuemode)
	{		
		$(object).find('[name=mode_type] option').remove();
		$(object).find('[name=mode_type]').append('<option value="">-- --</option>');
		if (options.mode[valuemode]){
			$(object).find('[name=mode_type] option').remove();
			for (var key in options.mode[valuemode]) {				
				$(object).find('[name=mode_type]').append('<option value="'+options.mode[valuemode][key]+'">'+options.mode[valuemode][key]+'</option>');
			}
		}
	}
	
};

})(jQuery);		