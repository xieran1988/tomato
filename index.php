
<?
	require_once("lib.php");
	require_once("tomato.php");
	$p = new Tomato(array("getdata"=>"1"));
	$p->handle();
	if ($p->r[jmp])
		js_jmp($p->r[jmp]);
#	$p->r[prize] = array("成就1", "成就2");
	$data = json_encode($p->r);
	require_once("index.tmpl.html");
?>

