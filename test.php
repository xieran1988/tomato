<?

require_once("tomato.php");

$p = new Tomato(array("email"=>"a"));
$p->mysql_init();
$p->check_prizes();

?>

