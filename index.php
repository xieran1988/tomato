
<?
	require_once("lib.php");
	require_once("tomato.php");
	if ($_GET[test] == 1) {
		require_once("index.html");
		exit();
	}
	$p = new Tomato();
	$p->need_login();
	require_once("index.html");
?>

