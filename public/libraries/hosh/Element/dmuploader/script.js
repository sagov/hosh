(function( $, window, undefined ) {
  $.Ex_Lib_Hosh_Element_Dmuploader = $.extend( {}, {
	  
	islog: false,
	islog_view:false,
    
    addLog: function(id, status, str){
    	if (!Boolean(this.islog)){
    		return;
    	}
      var d = new Date();
      
       
      var message = '[' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds() + '] ';
      
      message += str;
      console.log(message); 
      if (Boolean(this.islog_view)){
    	  var li = $('<li />', {'class': 'demo-' + status});
    	  li.html(message);
    	  $(id).prepend(li);
      }      
           
    },
    
    addFile: function(id, i, file){
		var template = '<div id="demo-file' + i + '">' +
		                   '<span class="demo-file-id">#' + i + '</span> - ' + file.name + ' <span class="demo-file-size">(' + $.Ex_Lib_Hosh_Element_Dmuploader.humanizeSize(file.size) + ')</span> - Status: <span class="demo-file-status">Waiting to upload</span>'+
		                   '<div class="progress progress-striped active">'+
		                       '<div class="progress-bar" role="progressbar" style="width: 0%;">'+
		                           '<span class="sr-only">0% Complete</span>'+
		                       '</div>'+
		                   '</div>'+
		               '</div>';
		               
		var i = $(id).attr('file-counter');
		if (!i){
			$(id).empty();
			
			i = 0;
		}
		
		i++;
		
		$(id).attr('file-counter', i);
		
		$(id).prepend(template);
		
		if (parent.$('.modal-iframe').length>0){
			var height = parseInt(parent.$('.modal-iframe').contents().find('html').height()) + 20;
			parent.$('.modal-iframe').height(height);
		}
	},
	
	updateFileStatus: function(i, status, message){
		$('#demo-file' + i).find('span.demo-file-status').html(message).addClass('demo-file-status-' + status);
		if (status == 'success'){
			$('#demo-file' + i).find('div.progress-bar').css('background-image','none');
		}
	},
	
	updateFileProgress: function(i, percent){
		$('#demo-file' + i).find('div.progress-bar').width(percent);		
		$('#demo-file' + i).find('span.sr-only').html(percent + ' Complete');
	},
	
	humanizeSize: function(size) {
      var i = Math.floor( Math.log(size) / Math.log(1024) );
      return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    }

  }, $.Ex_Lib_Hosh_Element_Dmuploader);
})(jQuery, this);

