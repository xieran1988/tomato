<?

require_once("lib.php");

$email = $_COOKIE[email];
if (!$_GET[reg] && !$_GET[login] && !$email) {
	jmp("login.php");
}

mysql_connect("localhost", "yjwt", "52550501");
mysql_query("use tomato");

if ($_GET[postdata]) {
	$p = file_get_contents("php://input");
	$date = date('Y-m-d');
	$r = mysql_query("insert into entry(email, time, val) values('$email', '$date', '$p') ".
									 "on duplicate key update val = '$p'");
	syslog("tomato_postdata: $p result: $r");
}

if ($_GET[getdata]) {
	$date = date('Y-m-d');
	$r = mysql_query("select * from entry where email = '$email' and time = '$date'");
	$r = mysql_fetch_assoc($r);
	syslog("tomato_getdata: $r");
	echo "$r[val]";
}

$email = $_POST[email];
$pass = $_POST[pass];

if ($_GET[reg]) {
	$r = mysql_query("select * from user where email = '$email'");
	$r = mysql_fetch_assoc($r);
	if (!$r) {
		mysql_query("insert into user(email, pass) values('$email', '$pass')");
		setcookie('email', "$email", time()+60*60*24*3);
		jmp("index.php?firstuse=1");
	} else {
		jmp("login.php?email_used=1");
	}
	error_log("tomato_reg: $email");
}

if ($_GET[login]) {
	$r = mysql_query("select * from user where email = '$email' and pass = '$pass'");
	$r = mysql_fetch_assoc($r);
	if ($r) {
		setcookie('email', "$email", time()+60*60*24*3);
		jmp("index.php");
	} else {
		jmp("login.php?login_failed=1");
	}
}

if ($_GET["exit"]) {
	setcookie('email', '');
	jmp("login.php");
}

?>

