<?

require_once("lib.php");
require_once("KLogger.php");

class Tomato {
	var $email;
	var $pass;
	var $date;
	var $postdata;
	var $ret;
	var $logger;
	var $d;
	var $r;

	function __construct () {
		$this->logger = new KLogger('/tmp/', KLogger::INFO);
		if (!$_GET["date"]) 
			$this->date = date('Y-m-d');
		else
			$this->date = date('Y-m-d', strtotime($_GET["date"]));
		$post = file_get_contents("php://input");
		$this->d = array_merge($_GET, $_POST, json_decode($post));
		$this->r = array();
	}

	function do_fastcgi() {

		if ($this->d[postdata]) {
			$this->need_login();
			$this->handle_postdata();
		}

		if ($this->d[getdata]) {
			$this->need_login();
			$this->handle_getdata();
		}

		if ($this->d[reg]) {
			if (!$this->handle_reg()) {
				$this->r[ret] = 'ok';
			} else {
				$this->r[ret] = 'fail';
			}
			$this->r[jmp] = '';
		}

		if ($_GET[login]) {
			$this->email = $_POST[email];
			$this->pass = $_POST[pass];
			if (!$this->handle_login()) {
				$this->set_cookie();
			} 
			jmp($this->ret);
		}

		if ($_GET["exit"]) {
			setcookie('email', '');
			jmp("login.php");
		}
	}

	function log($s) {
		$this->logger->logInfo($s);
	}

	function need_login() {
		$this->email = $_COOKIE[email];
		if (!$this->email) 
			jmp("login.php");
	}

	function set_cookie() {
		setcookie('email', "$this->email", time()+60*60*24*3);
	}

	function handle_postdata() {
		$this->mysql_init();
		$r = mysql_query("insert into entry(email, time, val) values('$this->email', '$this->date', '$this->postdata') ".
			"on duplicate key update val = '$this->postdata'");
		$this->log("handle_postdata: $this->postdata");
	}

	function handle_getdata() {
		$this->mysql_init();
		$r = mysql_query("select * from entry where email = '$this->email' and time = '$this->date'");
		$r = mysql_fetch_assoc($r);
		$val = $r[val];
		$this->log("handle_getdata: $this->email,$this->date $val");
		return $r[val];
	}

	function handle_reg() {
		$this->mysql_init();
		$r = mysql_query("select * from user where email = '$this->email'");
		$r = mysql_fetch_assoc($r);
		if (!$r) {
			$this->log("handle_reg: new $this->email");
			mysql_query("insert into user(email, pass) values('$this->email', '$this->pass')");
			$this->ret = "index.php?firstuse=1";
		} else {
			$this->log("handle_reg: exists $this->email");
			$this->ret = "login.php?email_used=1";
		}
	}

	function handle_login() {
		$this->mysql_init();
		$r = mysql_query("select * from user where email = '$this->email' and pass = '$this->pass'");
		$r = mysql_fetch_assoc($r);
		if ($r) {
			$this->log("handle_login: ok $this->email");
			$this->ret = "index.php";
		} else {
			$this->log("handle_login: fail $this->email $this->pass");
			$this->ret = "login.php?login_failed=1";
		}
	}

	function mysql_init() {
		if (!mysql_connect("localhost", "yjwt", "52550501")) {
			$this->log("mysql_init: fail");
			exit();
		}
		mysql_query("use tomato");
	}
}

?>

