<?

require_once("tomato.php");

$p = new Tomato();
$p->handle();
if ($p->r[jmp]) {
	js_jmp($p->r[jmp]);
}
if ($p->d[login] == '1' && $p->r[ret] == 'ok') {
	set_cookie("email", $p->r[email], 3);
}
if ($_COOKIE[email]) {
	set_cookie("email", $_COOKIE[email], 3);
}
if ($p->d["exit"] == '1') {
	set_cookie("email", "", -3);
}
echo json_encode($p->r);
#echo json_encode($p->d)."\n";
#echo json_encode($p->r)."\n";

?>

