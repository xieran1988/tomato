<?

function js_jmp($url) {
	echo "<script>window.location.href='$url'</script>";
	exit;
}

function set_cookie($key, $val, $days) {
	setcookie($key, $val, time()+60*60*24*$days);
}

?>
