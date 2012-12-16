
<?
require_once("lib.php");
require_once("data.php");
?>

<head>
	<meta charset=utf8>
	<style>

		a {
			color: #aaa;
		}
		a:hover {
			background: yellow;
		}
		body {
			font: 13px sans-serif;
			color: #444;
		}
		.main {
			margin:0 auto;
			margin-top: 40px;
			width: 500px;
		}
		th {
			border-bottom: 1px solid;
		}
		td span {
			margin-left: 10px;
			line-height: 1.4em;
		}
		tr {
		}
		table {
			margin-bottom: 50px;
		}
		.todo_thleft {
			width: 320px;
		}
		.todo_thright {
			width: 420px;
		}
		.todo_hold {
			height: 60px; 
		}
		.todo_table th {
			border-right: 1px solid;
		}
		.todo_table td {
			border-right: 1px solid;
		}
		.todo_ch {
		}
		.todo_str_right {
			float: right;
		}
		.todo_str_left {
			float: left;
		}
		.outplan_th {
			width: 500px;
		}
		.alist_th {
			width: 500px;
		}
		.nav {
			margin:0 auto;
			height: 40px;
			width: 500px;
		}
		.nav span {
			float: right;
			margin: 5px;
		}
		.tip_div { 
			position: absolute; 
			width: 600px;
			height: 200px;
			display: none;
		}
		.tip_div h1 {
			margin: 0;
			font-size: 20px;
			font-weight: normal;
		}
		.tip_div h6 {
			margin: 0;
			margin-top: 10px;
			font-size: 13px;
			font-weight: normal;
		}
		.tip_div h2 {
			margin: 0;
			font-size: 15px;
			font-weight: normal;
		}
		.tip_top {
			margin:0 auto;
			width: 400px;
			font-size: 25px;
		}

	</style>
	<script src=jquery.js></script>
	<script src=index.js></script>
</head>

<body>

	<div id=tip1 class=tip_div>
		<h1> 在这里新建一个任务 -></h1>
		<h6><a href=# class=tip_div_close>我知道了</a></h6>
	</div>
	<div id=tip2 class=tip_div>
		<h1> <- 点击按钮记录相应的符号 </h1>
		<h6><a href=# class=tip_div_close>我知道了</a></h6>
	</div>
	<div id=tip3 class=tip_div>
		<h1> 注意：</h1>
		<h2>1. 记录添加以后无法更改</h2>
		<h2>2. 昨天的记录无法更改</h2>
		<h6><a href=# class=tip_div_close>开始使用</a></h6>
	</div>

	<?
		if ($_GET[firstuse] == '1') {
			?>
			<div class=tip_top>
				欢迎第一次使用 ManyTomato ！
			</div>
			<?
		}
	?>

	<div class=nav>
		<span><a href="data.php?exit=1">退出登陆</a></span>
	<?
		$date = $_GET["date"] ? date('Y-m-d', strtotime($_GET["date"])) : date('Y-m-d');
		$date_md = date("m-d", strtotime($date));
		$yesterday = date('Y-m-d', strtotime('-1 day', strtotime($date)));
		$tomorrow = date('Y-m-d', strtotime('+1 day', strtotime($date)));
		?><span><a href="index.php?date=<?=$tomorrow?>">-></a></span><?
		?><span><a href="index.php?date=<?=$yesterday?>"><-</a></span><?
		?><span><a href="index.php">今天</a></span><?
	?>
	</div>
	<div class=main>
		<table class=todo_table rules=none>
			<tr>
				<th class=todo_thleft>今日任务 <?=$date_md?></th>
				<th class=todo_thright></th>
			</tr>
			<tr id=todo_blank>
				<td><input type=text placeholder="新建任务"></input></td>
				<td></td>
			</tr>
			<tr class=todo_hold>
				<td></td><td></td>
			</tr>
		</table>

		<table class=outplan_table rules=none>
			<tr>
				<th class=outplan_th>计划外紧急事件</th>
			</tr>
			<tr id=outplan_blank>
				<td><input type=text placeholder="新建计划外事件"></input></td>
			</tr>
		</table>

		<table class=alist_table rules=none>
			<tr>
			<th class=alist_th>活动清单（留着以后完成的任务）</th>
			</tr>
			<tr id=alist_blank>
				<td><input type=text placeholder="新建活动清单"></input></td>
			</tr>
		</table>

	</div>
</body>

