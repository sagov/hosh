//<script type="text/javascript">
/**
 * 
 */
;
(function($){
	
	$.BootstrapModal = function(options){
		
		options = $.extend({}, {
			id: 'BootstrapModal',
			close:true,
			title: '&nbsp;',
			content: '',
			iframe: false,
			url: '',
			loading_content: '<i class="fa fa-spinner fa-spin"></i>',
			append_load:function(object){},
			dialog_size: 'modal-lg',
			footer:false,
			
		}, {			
		}, options);		
		
		var object = '#'+options.id;
		
		init();
		
		
		
		 function iframe(param){
			
			if ($(object).find('.modal-body .modal-iframe').length === 0){
				$(object).find('.modal-body').html('<iframe class="modal-iframe" src="'+options.url+'"></iframe>');
			}else {
				$(object).find('.modal-iframe').eq(0).attr('src',options.url);
			}
			
			$(document).ready(function(){			
				$(object).find('.modal-iframe').eq(0).load(function(){
					removeLoading();
					var height = parseInt($(this) .contents().find('html').height()) + 20;
					$(this).height(height);
					$(this).animate({opacity:1},500);
					options.append_load(object);
					
					if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
						
						$(object)
						.css({
						position: 'absolute',
						marginTop: $(window).scrollTop() + 'px',
						bottom: 'auto'
						});
						
						setTimeout( function() {
							$('.modal-backdrop').css({
							position: 'absolute',
							top: 0,
							left: 0,
							width: '100%',
							height: Math.max(
							document.body.scrollHeight, 
							document.body.offsetHeight, 
							document.body.clientHeight
							) + 'px'
							});
						}, 0);
					}
					
					$(window).trigger('resize');
					
				});
				
				$(window).resize(function(){
					autosize();
				});
				
			});			
			
			$(object).find('.modal-iframe').eq(0).contents().find('html').html('');
			$(object).find('.modal-iframe').eq(0).height('auto');
			$(object).find('.modal-iframe').eq(0).css('opacity',0);
			startLoading();
			//$(object).find('.modal-iframe').eq(0).attr('src',options.url);
		};
		
		function autosize(){
			if ($(object).find('.modal-iframe').length>0){
				var height = parseInt($(object).find('.modal-iframe').contents().find('html').height()) + 20;
				$(object).find('.modal-iframe').height(height);
			}
		}
		
		function Content(){
			startLoading();
			jqxhr = $.ajax( {
				url: options.url				
				} )
				.done(function(response) {										
					options.content = response;	
					setContent();
				})
				.fail(function(response) { 
					removeLoading();
				})
				.always(function(response) { 
					removeLoading();
				});
		}
		
		function setContent(){
			$(object).find('.modal-body').html(options.content);
		}
		
		function setClose(){
			if ($(object).find('.modal-header .close').length == 0){
			var text_close = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			setHead();
			$(object).find('.modal-header').prepend(text_close);	
			}
		}
		
		function removeClose(){
			$(object).find('.modal-header .close').remove();
		}
		
		function setHead(){
			if ($(object).find('.modal-header').length == 0){
				$(object).find('.modal-content').prepend('<div class="modal-header"></div>');
			}
		}
		
		function removeHead(){
			$(object).find('.modal-header').remove();
		}
		
				
		function setFooter(){
			if (!options.footer){
				removeFooter();
				return;
			}
			if ($(object).find('.modal-footer').length == 0){
			  $(object).find('.modal-content').append('<div class="modal-footer"></div>');	
			}
			
			$(object).find('.modal-footer').html(options.footer);
		}
		
		function removeFooter(){
			$(object).find('.modal-footer').remove();
		}
		
		function setTitle(){			
			if(options.title.length>0){				
				setHead();
				if ($(object).find('.modal-title').length == 0){ 
					$(object).find('.modal-header').append('<h4 class="modal-title">'+options.title+'</h4>');
				}else{
					var title = options.title;
					if (title.length == 0) title = '&nbsp;';
					$(object).find('.modal-title').html(title);
				}
			}else if (!options.close){
				removeHead();
			}			
		}
		
		function startLoading(){
			if ($(object).find('.modal-body .modal-loading').length == 0){
				$(object).find('.modal-body').prepend('<div class="modal-loading"><div class="modal-loading-content">'+options.loading_content+'</div></div>');				
			}			
		}
		
		function removeLoading(){
			$(object).find('.modal-body .modal-loading').remove();
		}
		
		function init(){			
			if ($(object).length == 0){
				var text = '<div class="modal fade" id="'+options.id+'" tabindex="-1" role="dialog" aria-labelledby="'+options.id+'Label" aria-hidden="true"><div class="modal-dialog '+options.dialog_size+'"><div class="modal-content"><div class="modal-body"></div></div></div></div>';
				$('body').append(text);
			}
			
			if (options.close){
				setClose();
			}else{
				removeClose();
			}
			
			setTitle();
			setContent();
			setFooter();
			
		};
		
		
		
		this.modal = function(param){
			if (!param){ param = {}; }
			if (options.url){
				if (options.iframe){
					iframe(param);
				}else{
					Content();										
				}	
			}
			
			$(object).modal(param);
		}
		
		this.hide = function(){
			$(object).modal('hide');
		}
		
		this.autosize = function(){
			autosize();
		}
	};
	
})(jQuery);
