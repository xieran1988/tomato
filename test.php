<?

require_once("tomato.php");

$p = new Tomato(array("email"=>"a","date"=>"2012-12-09"));
$p->mysql_init();
$p->check_prizes();

?>

