<?

require_once("tomato.php");

error_reporting(E_ALL ^ (E_NOTICE|E_WARNING));
ini_set('error_log', '');
$p = new Tomato(array("myarg"=>"1"));
$p->handle();
echo json_encode($p->d)."\n";
echo json_encode($p->r)."\n";

?>

