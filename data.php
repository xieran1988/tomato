<?

require_once("tomato.php");

$p = new Tomato();
$j = $p->do_fastcgi();

if ($j[jmp]) {
	do_jmp($j[jmp]);
}

?>

