
<?
	require_once("lib.php");
	require_once("tomato.php");
	$p = new Tomato(array("getdata"=>"1"));
	$p->handle();
	if ($p->r[jmp])
		js_jmp($p->r[jmp]);
	$data = json_decode($p->r);
	require_once("index.tmpl.html");
?>

