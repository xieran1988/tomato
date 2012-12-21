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
	var $sqlqry;
	var $day_prize;
	var $new_prize;
	var $prize_txts;

	function __construct ($myarg = array()) {
		global $argv;
		$this->logger = new KLogger("/tmp/", KLogger::INFO);

		$this->prize_txts = array(
				"break_6_in_a_tomato" => "烂番茄：一个番茄里六次被打断",
				"more_than_6_in_a_tomato" => "大番茄：一个任务超过六个番茄",
				"more_than_16_tomato_a_day" => "饭桶：今天你吃了十六个番茄以上",
				"cont_8_tomatos" => "专注：连续工作八个番茄钟",
			);

		$rawpost = file_get_contents("php://input");
		$postjs = json_decode($rawpost, true);

		$this->d = $myarg;

		if ($_COOKIE[email]) {
			$this->d[email] = $_COOKIE[email];
			$this->d[ck_email] = $_COOKIE[email];
		}

		foreach ($_GET as $k=>$v) 
			$this->d[$k] = $v;
		foreach ($_POST as $k=>$v) 
			$this->d[$k] = $v;
		foreach ($postjs as $k=>$v) 
			$this->d[$k] = $v;

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

		if (!$this->d["date"]) 
			$this->date = date("Y-m-d");
		else
			$this->date = date("Y-m-d", strtotime($this->d["date"]));

		$this->r = array();
		$s = json_encode($this->d);
		$this->log("req $s");
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
		$s = json_encode($this->r);
		$this->log("ret $s");
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

	function fmt($args) {
		$num = count($args);
		$s = $args[0];
		for ($i = 1; $i < $num; $i++) {
			$a = $args[$i];
			$s = str_replace("%$i", $a, $s);
		}
		return $s;
	}

	function query() {
		$args = func_get_args();
		$sql = $this->fmt($args);
		$this->log("query $sql");
		$this->sqlqry = mysql_query($sql);
		if (!$this->sqlqry) {
			$this->log("query_err " . mysql_error());
		}
		$r = mysql_fetch_assoc($this->sqlqry);
		return $r;
	}

	function add_prize($s) {
		if (!in_array($s, $this->day_prize)) {
			$this->log("add prize $s");
			/*
			$this->query("insert into log(email, time, act, val) ".
									 "values('%1', 'now()', 'prize', '%2')",
									 $this->d[email], $s
								 	);
			 */
			array_push($this->day_prize, $s);
			array_push($this->new_prize, $this->prize_txts[$s]);
		}
	}

	function check_prizes() {
		$r = $this->query("select * from entry where ".
											"email = '%1' and date(time) = '%2'", 
											$this->d[email], $this->date
											);
		$this->log("cur_prize = " . $r[prize]);
		$this->day_prize = $r[prize] ? explode(",", $r[prize]) : array();

		$this->new_prize = array();

		/*
		$r = $this->query("select * from user where email = '%1'", $this->d[email]);
		$this->tot_prize = explode(",", $r[prize]);

		$r = $this->query("select count(*) as cnt, time from log ".
											"where email = '%1' and act = 'add*' and ".
											"time >= date_sub('%2', interval 8 day) ".
											"group by time",
											$this->d[email], $this->date
										 );
		$nr = 0;
		while ($r) {
			if ($r[cnt] > 0) 
				$nr++;
			$r = mysql_fetch_assoc($this->sqlqry);
		}
		if ($nr >= 7) {
			$this->add_prize("cont_tomato_a_week");
		}
		 */

		$fmt = "select count(*) as cnt, act, val from log ".
					 "where email = '%1' and ".
					 "date(time) = '%2' ".
					 "group by val, act";
		$r = $this->query($fmt, $this->d[email], $this->date);
		$nr = 0;
		while ($r) {
			if ($r[act] == "add," && $r[cnt] >= 6) 
				$this->add_prize("break_6_in_a_tomato");
			if ($r[act] == "add*" && $r[cnt] >= 6) 
				$this->add_prize("more_than_6_in_a_tomato");
			if ($r[act] == "add*")
				$nr += $r[cnt];
			$r = mysql_fetch_assoc($this->sqlqry);
		}
		if ($nr >= 16)
			$this->add_prize("more_than_16_tomato_a_day");

		$r = $this->query("select * from log where email = '%1' and date(time) = '%2' ".
											"order by time",
											$this->d[email], $this->date
										 );
		$last_ts = strtotime($this->date);
		$nr = 0;
		while ($r) {
			if ($r[act] == "add*") {
				$ts = strtotime($r["time"]);
				if ($ts - $last_ts < 60*32) 
					$nr++;
				else
					$nr = 1;
				$last_ts = $ts;
				if ($nr >= 8) 
					$this->add_prize("cont_8_tomatos");
			}
			$r = mysql_fetch_assoc($this->sqlqry);
		}

		if (count($this->new_prize)) {
			$this->query("update entry set prize = '%1' ".
									 "where email = '%2' and time = '%3' ", 
									 implode(",", $this->day_prize), $this->d[email], $this->date
									);
			$this->r[new_prize] = implode(",", $this->new_prize);
		}
	}

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
		$val = base64_encode($val);
		$r = $this->query("insert into entry(email, time, val) ".
							 				"values('%1', '%2', '%3') ".
							 				"on duplicate key update val = '%3'",
							 				$this->d[email], $this->date, $val, $val
						  				);
		$r = $this->query("insert into log(email, time, act) ".
							 				"values('%1', now(), '%2') ",
							 				$this->d[email], $this->d[act]
						  				);
		$this->check_prizes();
		$this->r[ret] = 'ok';
	}

	function handle_getdata() {
		if ($this->mysql_init())
			return ;
		if ($this->check_login())
			return ;
		$r = $this->query("select * from entry where email = '%1' and time = '%2'",
							 				$this->d[email], $this->date);
		if ($r[val]) {
			$this->r[ret] = 'ok';
			$this->r[val] = json_decode(base64_decode($r[val]), true);
		} else {
			$this->r[ret] = 'fail';
		}
		if ($r[prize]) {
			$this->r[prize] = array();
			foreach (explode(",", $r[prize]) as $p) {
				if ($this->prize_txts[$p]) {
					array_push($this->r[prize], $this->prize_txts[$p]);
				}
			}
		}
		$this->r[today] = date("Y-m-d");
		$this->r[date] = $this->date;
		$this->r[dateshort] = date("m-d", strtotime($this->date));
		$this->r[dateprev] = date("Y-m-d", strtotime("$this->date -1 day"));
		$this->r[datenext] = date("Y-m-d", strtotime("$this->date +1 day"));
		if ($this->r[datenext] > $this->r[today]) 
			$this->r[datenext] = '#';
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
		$r = $this->query("select * from user where email = '%1'", $this->d[email]);
		if (!$r) {
			$this->query("insert into user(email, pass) values('%1', '%2')", 
									 $this->d[email], $this->d[pass]);
			$this->r[ret] = 'ok';
			$this->r[jmp] = "index.php?firstuse=1";
		} else {
			$this->r[ret] = 'fail';
			$this->r[err] = 'email_used';
			$this->r[jmp] = "login.php?email_used=1";
		}
	}

	function handle_login() {
		if ($this->mysql_init())
			return ;
		$r = $this->query("select * from user where email = '%1' and pass = '%2'",
											$this->d[email], $this->d[pass]);
		if ($r) {
			$this->r[ret] = 'ok';
			$this->r[jmp] = "index.php";
		} else {
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

