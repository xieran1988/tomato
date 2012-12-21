<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<script src=jquery.js></script>
	<script src=jquery.json-2.4.min.js></script>
	<script src=index.js></script>
	<style>
		#main {
			margin:0 auto;
			margin-top: 200px;
			width: 700px;
		}
		#left {
			float:left;
			border-right: 1px solid;
			padding-right: 20px;
			height: 100px;
		}
		#left h2 {
			margin-top: 10px;
			margin-bottom: 10px;
		}
		#left a {
			color: #aaa;
			font-size: 14px;
		}
		input {
			width: 150px;
		}
		#right {
			float: left;
			margin-left: 20px;
			padding-top: 10px;
		}
		.err {
			font-size: 13px;
			color: red;
		}
	</style>
	<script src=jquery.js></script>
	<script src=login.js></script>
</head>

<body>
	<div id=main>
		<div id=left >
			<h2> 克服拖延症只需要 25 分钟</h2>
			<a target=_blank href=about.html> 什么是番茄工作法？</a>
		</div>
		<div id=right >
			<form method=post >
				<input name=email type=text placeholder="邮箱"> </input>
				<?
					if ($_GET[email_used]) {
						?> <span class=err>此邮箱已被使用</span> <?
					} 
					if ($_GET["empty"]) {
						?> <span class=err>邮箱或密码不能为空</span> <?
					}
				?>
				<br>
				<input name=pass type=password placeholder="密码"> </input>
				<?
					if ($_GET[login_failed]) {
						?> <span class=err>登录失败</span> <?
					}
				?>
				<br>
				<button post="data.php?reg=1">加入</button>
				<button post="data.php?login=1">登录</button>
			</form>
		</div>
	</div>
</body>

