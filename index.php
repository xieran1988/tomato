
<?
	require_once("lib.php");
	require_once("tomato.php");
	if ($_GET[uitest] == 1) {
		require_once("index.html");
		exit();
	}
	$p = new Tomato();
	$p->need_login();
	$data = $p->handle_getdata();
	$dateprev = date("Y-m-d", strtotime("$p->date -1 day"));
	$datenext = date("Y-m-d", strtotime("$p->date +1 day"));
	$is_today = ($p->date == date("Y-m-d"));
	$cfg = json_encode(array(
		"is_today" => $is_today,
		"dateprev" => $dateprev,
		"datenext" => $datenext,
		"pdate" => $p->date,
		"today" => date("Y-m-d")
	));
	require_once("_index.html");
?>

