(function($){
	$('table.posts #the-list1').sortable({
		'items': 'tr',
		'axis': 'y',
		'helper': fixHelper,
		'update' : function(e, ui) {
			$.post( ajaxurl, {
				action: 'update-menu-jmorder',
				order: $('#the-list1').sortable('serialize'),
			});
		}
	});  

	$('table.posts #the-list2').sortable({
		'items': 'tr',
		'axis': 'y',
		'helper': fixHelper,
		'update' : function(e, ui) {
			$.post( ajaxurl, {
				action: 'update-menu-jmorder',
				order: $('#the-list2').sortable('serialize'),
			});
		}
	});


	var fixHelper = function(e, ui) {
		ui.children().children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};
	
})(jQuery) 
