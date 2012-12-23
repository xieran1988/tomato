
$(document).ready(function() {
	$('input[type=text]').bind('keydown', function (e) {
		if (e.keyCode == 13) {
			$('form').attr('action', 'data.php?login=1');
			e.preventDefault(); // for safari, need test IE
			$('form').submit();
		}
	});
	$.placeholder();
});

