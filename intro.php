<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link href="index.css" rel="stylesheet" type="text/css"/>
	<style>
		#main {
			margin:0 auto;
			margin-top: 100px;
			width: 700px;
		}
		#left {
			float:left;
			padding-right: 20px;
			height: 100px;
		}
		#left h2 {
			margin-top: 10px;
			margin-bottom: 10px;
		}
		#left p {
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
		#left div {
			display: none;
		}
		#footer {
			height: 200px;
		}
	</style>
	<script src=jquery.js></script>
	<script src=intro.js></script>
</head>

<body>
	<div id=main>
		<div id=left >
			<h2> Step1. 一个崭新的开始</h2>
			<div id=ask1>
			<p>接下来的 25 分钟你要做什么？</p>
			<input type=text id=todo></input>
			<button onclick="btn_click('ok');">确定</button>
			</div>
			<div id=show1>
				<p>现在，请专心。你的任务是 <strong class=task1>dd</strong>。</p>
				<p>你能够忘掉眼前的一切事情，专注于这个任务吗？</p>
				<p>忘记身边的人，忘记过去的事情，忘记你原本在做的事情。</p>
				<p>现在是一个崭新的开始，如果你能珍惜这 25 分钟。</p>
				<p>你要与你旧的习惯告别。你愿意开始吗？</p>
				<button onclick="btn_click('ok');">我愿意</button>
				<button onclick="btn_click('cancel');">我真的可以吗？</button>
			</div>
			<div id=why1>
				<p>你愿意你的将来，是像你今天这个样子？</p>
				<p>你曾经有梦想，因为过于远大，或者是现实太残酷。你放弃了。</p>
				<p>你以为你做不到。</p>
				<p>如果你将来是一个什么样的人，那你今天就是。</p>
				<p>相信自己的潜能，总有一天你得实现你的梦想，你得用事实告诉那些不服气的人。</p>
				<p>那为什么不让这一天来得早一点，或者说，就是从现在开始呢。</p>
				<p>现在就做出选择吧。</p>
				<button onclick="btn_click('ok');">我从现在开始实现我的梦想</button>
			</div>
			<div id=start1>
				<p><strong class=task1>测试</strong></p>
				<p>时间过去了 <strong id=clock></strong></p>
			</div>
			<div id=end1>
				<p>时间到了。你做到了想做的事情了吗？</p>
				<button onclick="btn_click('yes');">做到了！</button>
				<button onclick="btn_click('no');">没有</button>
			</div>
		</div>
	</div>
	<div id=footer> </div>
</body>

