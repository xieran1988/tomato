
function clock_timer() {
	if (!$.clock_tm) 
		return ;
	var tm = (new Date()).getTime();
	var diff = (tm - $.clock_tm) / 1000;
	var minute = Math.floor(diff / 60);
	var second = Math.floor(diff) % 60;
	var s;

	if (diff >= 1000*25*60) {
		alert('时间到！');
		return ;
	}

	if (second < 10) 
		s = minute + ':0' + second;
	else
		s = minute + ':' + second;
	$('#clock').html(s);

	setTimeout(clock_timer, 500);
}

function update() {
	$('#left div').hide('slow');
	$($.cur).show('slow');
}

function start() {
	$.clock_tm = (new Date()).getTime();
	clock_timer();
}

function btn_click(act) {
	if ($.cur == '#ask1') {
		if (act == 'ok') {
			var todo = $('#todo').val();
			$('.task1').html(todo);
			$.cur = '#show1';
			update();
		}
		return ;
	}
	if ($.cur == '#show1') {
		if (act == 'ok') {
			$.cur = '#start1';
			start();
			update();
		}
		if (act == 'cancel') {
			$.cur = '#why1';
			update();
		}
		return ;
	}
	if ($.cur == '#why1') {
		if (act == 'ok') {
			$.cur = '#start1';
			start();
			update();
		}
		return ;
	}
	if ($.cur == '#start1') {
	}
}

$(document).ready(function() {
	$.cur = '#ask1';
	$($.cur).show();
});

