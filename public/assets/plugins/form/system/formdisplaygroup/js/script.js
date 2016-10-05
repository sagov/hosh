/**
 * 
 */
;
(function($){
	
	
$.widget( "hosh.plugin_system_formdisplaygroup_default", $.hosh.plugin_system_form, {	
	
	options: {
		url_submit: '',
		url_task: '',
		elementmodal:'DisplayGroupFormModal',
	},

	_create: function() {
		var that = this;
		var options = this.options;
		this.element.find('[data-task=saveform]').click(function(){
			$('#'+options.elementmodal).animate({scrollTop:0}, 'fast');
			that.submitForm();
			return false;
		});
		
		this.element.find( "input[name=norder]" ).spinner({
			 min:0							
			});
	},
	
	_init: function() {		
		this._tooltip();		
	},
	
	submitForm: function(){
		var that = this;
		var options = this.options;	
		
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