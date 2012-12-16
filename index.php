
<?
	require_once("lib.php");
	require_once("tomato.php");
	$p = new Tomato();
	$p->need_login();
	require_once("index.html");
?>

