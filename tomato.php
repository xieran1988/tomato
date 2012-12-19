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

	function __construct ($myarg = array()) {
		global $argv;
		$this->logger = new KLogger('/tmp/', KLogger::INFO);
		if (!$_GET["date"]) 
			$this->date = date('Y-m-d');
		else
			$this->date = date('Y-m-d', strtotime($_GET["date"]));

		$rawpost = file_get_contents("php://input");
		$_postjs = json_decode($rawpost, true);

		$get = count($_GET) ? $_GET : array();
		$post = count($_POST) ? $_POST : array();
		$cookie = count($_COOKIE) ? $_COOKIE : array();
		$this->d = array_merge($cookie, $get, $post, $myarg);

		foreach ($_postjs as $k=>$v) {
			$this->d[$k] = $v;
		}

		foreach (array_slice($argv, 1) as $v) {
			$a = explode("=", $v);
			if (count($a) > 1)
				$this->d[$a[0]] = $a[1];
			else {
				foreach (json_decode($v, true) as $k=>$v) {
					$this->d[$k] = $v;
				}
			}
		}
		$this->r = array();
	}

	function handle() {
		if ($this->d[postdata]) 
			$this->handle_postdata();
		if ($this->d[getdata]) 
			$this->handle_getdata();
		if ($this->d[reg]) 
			$this->handle_reg();
		if ($this->d[login]) 
			$this->handle_login();
	}

	function log($s) {
		$this->logger->logInfo($s);
	}

	function check_login() {
		if (!$this->d[email]) {
			$this->r[ret] = 'fail';
			$this->r[jmp] = "login.php";
			return 1;
		}
		return 0;
	}

	function query() {
		$args = func_get_args();
		if(($num = func_num_args()) > 1)
			$sql = call_user_func_array("sprintf", $args);
#		echo "query: $sql\n";
		$r = mysql_query($sql);
		$r = mysql_fetch_assoc($r);
#		echo "query-ret: ";
#		var_dump($r);


	function handle_postdata() {
		if ($this->mysql_init())
			return ;
		if ($this->check_login())
			return ;
		$val = json_encode($this->d[val]);
		if (!$val) {
			$this->r[ret] = 'fail';
			return ;
		}
		$r = $this->query("insert into entry(email, time, val) ".
							 				"values('%s', '%s', '%s') ".
							 				"on duplicate key update val = '%s'",
							 				$this->d[email], $this->date, $val, $val
						  				);
		$this->log("handle_postdata: $val");
		$this->r[ret] = 'ok';
	}

	function handle_getdata() {
		if ($this->mysql_init())
			return ;
		if ($this->check_login())
			return ;
		$r = $this->query("select * from entry where email = '%s' and time = '%s'",
							 				$this->d[email], $this->date);
		if ($r[val]) {
			$this->r[ret] = 'ok';
			$this->r[val] = json_decode($r[val], true);
		} else {
			$this->r[ret] = 'fail';
		}
		$this->r[today] = date("Y-m-d");
		$this->r[date] = $this->date;
		$this->r[dateprev] = date("Y-m-d", strtotime("$this->date -1 day"));
		$this->r[datenext] = date("Y-m-d", strtotime("$p->date +1 day"));
		$this->r[is_today] = ($this->date == date("Y-m-d"));
	}

	function handle_reg() {
		if ($this->mysql_init())
			return ;
		if ($this->d[email] == "" || $this->d[pass] == "") {
			$this->r[ret] = "fail";
			$this->r[jmp] = "login.php?empty=1";
			return ;
		}
		$r = $this->query("select * from user where email = '%s'", $this->d[email]);
		if (!$r) {
			$this->query("insert into user(email, pass) values('%s', '%s')", 
									 $this->d[email], $this->d[pass]);
			$this->log("handle_reg: new $this->d[email]");
			$this->r[ret] = 'ok';
			$this->r[jmp] = "index.php?firstuse=1";
		} else {
			$this->log("handle_reg: exists $this->d[email]");
			$this->r[ret] = 'fail';
			$this->r[err] = 'email_used';
			$this->r[jmp] = "login.php?email_used=1";
		}
	}

	function handle_login() {
		if ($this->mysql_init())
			return ;
		$r = $this->query("select * from user where email = '%s' and pass = '%s'",
											$this->d[email], $this->d[pass]);
		if ($r) {
			$this->log("handle_login: ok $this->d[email]");
			$this->r[ret] = 'ok';
			$this->r[jmp] = "index.php";
		} else {
			$this->log("handle_login: fail $this->d[email] $this->d[pass]");
			$this->r[ret] = 'fail';
			$this->r[jmp] = "login.php?login_failed=1";
		}
	}

	function mysql_init() {
		if (!mysql_connect("localhost", "yjwt", "52550501")) {
			$this->log("mysql_init: fail");
			return 1;
		}
		mysql_query("use tomato");
		return 0;
	}
}

?>

