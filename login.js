

$(document).ready(function() {
	$('[post]').each(function() {
		$(this).click(function() {
			var form = $(this).closest('form');
			form.attr('action', $(this).attr('post'));
			form.submit();
		});
	});
	$('input').bind('keydown', function (e) {
		if (e.keyCode == 13) {
			$('form').attr('action', 'data.php?login=1');
			e.preventDefault(); // for safari, need test IE
			$('form').submit();
		}
	});
});

