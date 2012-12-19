<?

require_once("tomato.php");

$p = new Tomato();
$p->handle();
if (($p->d[login] == '1' || $p->d[reg] == '1') && $p->r[ret] == 'ok') {
	set_cookie("email", $p->d[email], 3);
}
if ($_COOKIE[email]) {
	set_cookie("email", $_COOKIE[email], 3);
}
if ($p->d["exit"] == '1') {
	set_cookie("email", "", -3);
	js_jmp("login.php");
}
if ($p->r[jmp]) {
	js_jmp($p->r[jmp]);
}
echo json_encode($p->r);
#echo json_encode($p->d)."\n";
#echo json_encode($p->r)."\n";

?>

