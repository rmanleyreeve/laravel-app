if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/serviceworker.js', {
    scope: '/'
  });
}

$(document).ready(function(){
	"use strict";

  $('.navbar-nav>li>a').on('click', function(){
		$('.navbar-collapse').collapse('hide');
  });

	// set BS modal content
	$('#bsModal').on('show.bs.modal', function (e) {
		var button = $(e.relatedTarget);
		var modal = $(this);
		modal.find('.modal-content').load(button.attr("href"));
	});	
	// set BS modal title
	$('#bsModal').on('shown.bs.modal', function(e){
		$('#modalTitle').text( $('#dynamicModalTitle').text() );	
	});
	// clear BS modal cache
	$('#bsModal').on('hidden.bs.modal', function(){
		$('#bsModal').removeData('bs.modal');	
	});

	//Init switch buttons
	var $switchButtons = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	$switchButtons.forEach(function (e) {
		var size = $(e).data('size');
		var options = {
			color:'#007bff',
			secondaryColor:'#DDD'
		};
		if (size !== undefined) options['size'] = size;
		var switchery = new Switchery(e, options);
	});

	// button alerts
	$('.delete-record').on('click',function(e) {
		var href = $(this).attr('href');
		e.preventDefault();
		if(confirm('Are you absolutely sure you wish to delete this record?')) {
			window.location = href;
		}
	});

});
