

$(document).ready(function() {
	$('[post]').each(function() {
		$(this).click(function() {
			$(this).closest('form').attr('action', $(this).attr('post'));
		});
	});
});

