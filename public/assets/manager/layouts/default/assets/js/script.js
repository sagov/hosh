/**
 * 
 */
;
(function($){
	
	$.HoshManagerLayouts_Default = function(object,options)
	{
		
		options = $.extend({}, {
			text:[],			
		}, {			
		}, options);
		
		$(document).ready(function(){
			init();
		});
		function init()
		{
			setAddButton();
			setBtnLeftContent();
			setBtnBar();
			
			$(object).on('click','.hosh-modal',function(){				
				var href = $(this).attr('href');
				var title = $(this).attr('title');
				var rel = {};
				var relvalue = $(this).attr('rel');
				if (relvalue){
					rel = eval('('+relvalue+')');
				}			
				if (href) {rel.url = href;}
				if (title) {rel.title = title;}
				
				var BootstrapModal  = new $.BootstrapModal(rel);
				BootstrapModal.modal();
				return false;
			});
		}
		
		CreateNavLevel = function (){
			if ($(object).find('.header .nav-level').length == 0){
				$(object).find('.header').append('<div class="nav-level visible-sm visible-xs"><div class="container nav-level-content"></div></div>');
			}
		};
		
		setAddButton = function (){
			if ($(object).find('.content-left .addbutton-level').length >0){
				CreateNavLevel();				
				$(object).find('.header .nav-level-content').append($(object).find('.content-left .addbutton-level').html());				
			}
		};
		
		setBtnLeftContent = function (){
			if ($(object).find('.content-left .leftmenu').length >0){
				CreateNavLevel();				
				$(object).find('.header .nav-level-content').append('<a href="javascript:void(0);" class="btn btn-default view-leftmenu">&nbsp;<i class="fa fa-list"></i>&nbsp;<span class="hidden-xs"> '+options.text.list+'</span></a>');
				
				$(object).on('click','.header .nav-level-content .view-leftmenu',function(){
					$(object).find('.content .content-left').toggleClass('on');										
				});
			}
		};
		
		setBtnBar = function(){
			if ($(object).find('.content-left .left-controller-content').length == 0 ){
				return;
			}
			if ($(object).find('.header .logo .btnbar').length > 0){
				return;
			}
			$(object).find('.header .logo').prepend('<span class="btnbar hidden-lg hidden-md"><i class="fa fa-bars"></i></span>');
			
			$(object).on('click','.header .logo .btnbar',function(){
				$('body').toggleClass('barswipe');
				createBar();
			});
		};
		
		createBar = function (){
			if ($(object).find('.bar-swipe').length >0){
				return;
			}			
			$(object).append('<div class="bar-swipe-shadow hidden-lg hidden-md"></div><div class="bar-swipe hidden-lg hidden-md"><div class="bar-swipe-content"></div></div>');
			
			if ($(object).find('.content-left .left-controller-content ul.controller-menu-main li').length>0){
				$(object).find('.bar-swipe .bar-swipe-content').append('<ul class="bar-swipe-content-main"></ul>');
			}
			
			$.each($(object).find('.content-left .left-controller-content ul.controller-menu-main li'), function (n, val) {
				$(object).find('.bar-swipe .bar-swipe-content-main').append('<li>'+$(val).html()+'</li>');
			});
			
			if ($(object).find('.content-left .left-controller-content ul.controller-menu-parent li').length>0){
				$(object).find('.bar-swipe .bar-swipe-content').append('<hr /><ul class="bar-swipe-content-parent"></ul>');
			}
			
			$.each($(object).find('.content-left .left-controller-content ul.controller-menu-parent li'), function (n, val) {
				$(object).find('.bar-swipe .bar-swipe-content-parent').append('<li>'+$(val).html()+'</li>');
			});
			
			$(object).on('click','.bar-swipe-shadow',function(){
				$('body').removeClass('barswipe');				
			});
		};
	};
	
})(jQuery);