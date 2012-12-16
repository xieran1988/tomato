

$(document).ready(function() {
	if (!$.browser.chrome && !$.browser.safari) {
		alert('请使用 Chrome 或者 Safari 浏览器访问');
	}
	$('[post]').each(function() {
		$(this).click(function() {
			$(this).closest('form').attr('action', $(this).attr('post'));
		});
	});
});

