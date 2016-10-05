/**
 * 
 */
;
(function($){
	var bootstrapTooltip = $.fn.tooltip.noConflict() // return $.fn.tooltip to previously assigned value
	$.fn.bootstrapTooltip = bootstrapTooltip            // give $().bootstrapTooltip the Bootstrap functionality
})(jQuery);