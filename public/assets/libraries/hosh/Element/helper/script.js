/**
 * 
 */
;
(function($){


$.fn.Ex_Lib_Hosh_Element_Helper = function(options){
	var object = this;
	
	options = $.extend({}, {
		url:'',
		url_loadhelpers:'',
		helpers:[],
	}, {
		
	}, options);
	
	var texthelper = '';
	$(object).on('focus','[data-type=helper]',function(){
		texthelper = $(this).val();
		addDropdownMenu(this);
	}).on('blur','[data-type=helper]',function(){
		if (texthelper != $(this).val()){
			viewFormHelper($(this).attr('name'),$(this).val());
		}
	});


	
	
	var jqxhr = '';
	function viewFormHelper(name,value){
		if (jqxhr != '') jqxhr.abort();
		startLoading();		
		jqxhr = $.ajax( {
			url: options.url, 
			type: "post",
			data: '&name='+name+'&value='+value
			} )
			.done(function(response) {
				data = eval('('+response+')');
				if (data){
				var xhtmlelements = '';
				if (data.element){
					for (var key in data.element) {
						 xhtmlelements += data.element[key];
					}					
				}
				if (data.css){
					xhtmlelements += data.css;
				}
				if (data.js){
					xhtmlelements += data.js;
				}
				$(object).find('[data-helper='+name+']').html(xhtmlelements);
				}
			})
			.fail(function(response) {				
				removeLoading();
			})
			.always(function(response) {				
				removeLoading();
			});	
	};
	
	function addDropdownMenu(e){
		
		if ($(e).parent().find('.helper-dropdown-menu').length > 0){
			return;
		}
		
		$(e).attr("data-toggle","dropdown");
		$(e).parent().addClass('dropdown');
		$(e).parent().append('<div class="dropdown-menu helper-dropdown-menu"><div class="helper-dropdown-content"></div></div>');
		$(e).parent().find('[data-toggle=dropdown]').dropdown();
		
		if (options.helpers.length  == 0){
			loadHelpers(e);
		}else{
			setViewHelperMenu(e);			
		}
		
		$(e).parent().on('click','.helper-dropdown-content a[data-target=helpvalue]',function(){
			var value = $(this).attr('data-value');
			$(e).val(value);
			$(e).trigger('blur');
			
		});
		
	};
	
	function loadHelpers(e)
	{
		$(e).parent().find('.dropdown-menu .helper-dropdown-content').html('<div style="text-align:center"><i class="fa fa-refresh fa-spin"></i></div>');
		$.ajax( {
			url: options.url_loadhelpers, 
			type: "post",
			data: ''
			} )
			.done(function(response) {
				data = eval('('+response+')');	
				options.helpers = data;
				if (e){
					setViewHelperMenu(e);
				}
			})
			.fail(function(response) {
				$(e).parent().find('.dropdown-menu .helper-dropdown-content').html("");
			})
			.always(function(response) {
				
			});
	};
	
		
	function setViewHelperMenu(e)
	{
		
		var items = [];
		var items_self = [];
		var category = $(e).attr('data-category');
		
		if (options.helpers[category]){
			for (i=0; i< options.helpers[category].length;i++){
				var sname = '';
				var scaption = '';
				var sdesc = '';
				var data_item = '';
				if (options.helpers[category][i]['sname']){
					sname = options.helpers[category][i]['sname'];
                    if (options.helpers[category][i]['scaption']) {
                        scaption = options.helpers[category][i]['scaption'];
                    }
                    if (options.helpers[category][i]['options']['sdesc']){
                    	sdesc = options.helpers[category][i]['options']['sdesc'];
					}
					if (scaption.length > 0 && sdesc.length >0) {
                        data_item = 'data-container="body" data-toggle="popover" data-title="' + scaption + '" data-placement="top" data-content="' + sdesc + '" data-trigger="hover"';
                    }
				}else{
                    sname = scaption = options.helpers[category][i];
				}
				items[i] = '<li><a href="javascript:void(0);" data-target="helpvalue" data-value="'+sname+'" '+data_item+'><span>'+sname+'</span></a></li>';
			}

		}
		
		if (options.helpers.self){
			for (i=0; i< options.helpers['self'].length;i++){
				items_self[i] = '<li><a href="javascript:void(0);" data-target="helpvalue" data-value="'+options.helpers['self'][i]+'"><span>'+options.helpers['self'][i]+'</span></a></li>';
			}
		}
		
		var xhtml = '';
		if (items.length >0 || items_self.length >0){
		xhtml += '<div class="container-fluid"><div class="row">';
		if (items.length >0){
			xhtml += '<div class="col-sm-6"><ul class="category-helpers">'+items.join ( '' )+'</ul></div>';
		}
		if (items_self.length >0){
			xhtml += '<div class="col-sm-6"><ul class="self-helpers">'+items_self.join ( '' )+'</ul></div>';
		}
		xhtml += '</div></div>';
		}else{
			xhtml += '<div class="container-fluid-empty">Data empty</div>';
		}
		$(e).parent().find('.dropdown-menu .helper-dropdown-content').html(xhtml);
        $('[data-toggle="popover"]').popover();
	}
	
	function startLoading(){
		if ($(object).find('.hosh-loading').length  == 0)
		{
			$(object).append('<div class="hosh-loading"><i class="fa fa-spinner fa-pulse"></i></div>');			
		}
	};
	
	function removeLoading(){
		$(object).find('.hosh-loading').remove();
	};
	
};
	
})(jQuery);	