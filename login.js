

$(document).ready(function() {
	$('[post]').each(function() {
		$(this).click(function() {
			$(this).closest('form').attr('action', $(this).attr('post'));
		});
	});

	$('input').bind('keydown', function (e) {
		if (e.keyCode == 13) {
			$('form').attr('action', 'data.php?login=1');
			console.log($('form'));
			e.preventDefault();
			$('form').submit();
		}
	});
});

