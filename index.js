
$.urlParam = function(name){
	var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (!results) { return 0; }
	return results[1] || 0;
}

function table_update(j, entry, blank, entry_set) {
	var e = $(entry);
	if (e.length >= j.length) {
		for (var i = j.length; i < e.length; i++) {
			$(e[i]).remove();
		}
	}
	if (e.length < j.length) {
		for (var i = e.length; i < j.length; i++) {
			var ne = entry_set(j[i]);
			ne.insertBefore($(blank));
		}
	}
	e = $(entry);
	for (var i = 0; i < j.length; i++) {
		var ne = entry_set(j[i]);
		$(e[i]).replaceWith(entry_set(j[i]));
	}
}

function get_all_entries(entry, entry_get) {
	return $.map($(entry), function(e) {
		return entry_get(e);
	});
}

function post_alldata(act) {
	if ($.is_firstuse)
		return ;
	var data = {
		'todo' : get_all_entries('.todo_entry', todo_entry_get), 
		'outplan' : get_all_entries('.outplan_entry', outplan_entry_get),
	 	'alist' : get_all_entries('.alist_entry', alist_entry_get)
	};
	var s = $.toJSON({"val":data, "act":act});
	$.post("data.php?postdata=1", s, function (e) {
//		console.log('ok', e);
	});
}

function get_alldata(func) {
	var date = $.urlParam('date');
	$.get('data.php?getdata=1&date=' + date, function(data) {
		func(data.val);
	});
}

function get_target(e) {
	if ($.browser.msie) {
		return window.event.srcElement;
	}
	return (e.target);
}

function todo_entry_title_mouseover(e) {
	var t = $(get_target(e));
	var t1 = t.closest('.todo_entry');
	var t2 = t1.find('.todo_title_right');
	t2.show();
}

function todo_entry_title_mouseout(e) {
	var t = $(get_target(e));
	t.closest('.todo_entry').find('.todo_title_right').hide();
}

function todo_entry_str_mouseover(e) {
	var t = $(get_target(e));
	var t1 = t.closest('.todo_entry');
	var t2 = t1.find('.todo_str_right');
	var t3 = t1.find('.todo_title_left');
	t3.addClass('todo_left_selected');
	t2.show();
}

function todo_entry_str_mouseout(e) {
	var t = $(get_target(e));
	var t1 = t.closest('.todo_entry');
	var t2 = t1.find('.todo_str_right');
	var t3 = t1.find('.todo_title_left');
	t3.removeClass('todo_left_selected');
	t2.hide();
}

function todo_title_btn_click(e) {
	var t = $(get_target(e));
	var l = t.closest('.todo_entry');
	var l2 = l.find('.todo_title_left');
	l2.html('<strike>' + l2.html() + '</strike>');
	l.attr('del', '1');
	l.find('td').unbind('hover');
	t.hide();
	post_alldata('done');
}

function todo_entry_set(j) {
	var title = j.title;
	if (j.del == undefined) {
		j.del = '';
	}
	if (j.str == undefined) {
		j.str = '';
	}
	var strtitle = j.title;
	if (j.del == '1') {
		strtitle = '<strike>' + j.title + '</strike>';
	}
	var strnode = $('<div class=todo_str_left>');
	for (var i = 0; i < j.str.length; i++) {
		var ch = j.str.substr(i, 1);
		strnode.append('<span class=todo_ch>' + ch + '</span>');
	}
	var strbtn = $('<div class=todo_str_right>');
	if ($.can_edit) {
		strbtn.append($('<a href=#>').html('*').click(todo_str_btn_click))
		strbtn.append($('<a href=#>').html('-').click(todo_str_btn_click));
		strbtn.append($('<a href=#>').html(',').click(todo_str_btn_click));
	}
	var titleleft = $('<div class=todo_title_left>').html(strtitle);
//	var titleright_btn = $('<a href=#>').html('完成').click(todo_title_btn_click);
	titleright_btn = '<a href=# onclick="todo_title_btn_click(event)" >完成</a>';
	var titleright = $('<div class=todo_title_right>').append(titleright_btn);
	var tdtitle = $('<td>').append(titleleft).append(titleright);
	if (j.del != '1') {
		tdtitle.hover(todo_entry_title_mouseover, todo_entry_title_mouseout); 
	}
	var tdstr = $('<td>').append(strnode).append(strbtn);
	if (j.del != '1') {
		tdstr.hover(todo_entry_str_mouseover, todo_entry_str_mouseout);
	}

	var tr = $('<tr>').addClass('todo_entry')
			.attr('str', j.str).attr('title', j.title).attr('del', j.del)
			.append(tdtitle, tdstr);
	return tr;
}

function todo_entry_get(o) {
	var title = $(o).attr('title');
	var str = $(o).attr('str');
	var del = $(o).attr('del');
	return {'title':title, 'str':str, 'del':del};
}

function todo_str_btn_click() {
	var ch = $(this).html();
	var span = $('<span class=todo_ch>').html(ch);
	var l = $(this).parent().parent().find('.todo_str_left');
	var tr = $(this).closest('tr');
	var str = tr.attr('str');
	tr.attr('str', str + ch);
	l.append(span);
	post_alldata('add' + ch);
}

function todo_update(j) {
	table_update(j, '.todo_entry', '#todo_blank', todo_entry_set);
}

function outplan_entry_set(j) {
	return $('<tr>')
			.addClass('outplan_entry').append(
			$('<td>').html(j.title)
	);
}

function outplan_entry_get(o) {
	var td = $(o).find('td');
	var title = $(td[0]).html();
	return {'title':title};
}

function outplan_update(j) {
	table_update(j, '.outplan_entry', '#outplan_blank', outplan_entry_set);
}

function alist_entry_set(j) {
	return $('<tr>')
			.addClass('alist_entry').append(
			$('<td>').html(j.title)
	);
}

function alist_entry_get(o) {
	var td = $(o).find('td');
	var title = $(td[0]).html();
	return {'title':title};
}

function alist_update(j) {
	table_update(j, '.alist_entry', '#alist_blank', alist_entry_set);
}

function update_alldata(j) {
	try {
		todo_update(j.todo);
		outplan_update(j.outplan);
		alist_update(j.alist);
	} catch (e) {}
}

function bind_key(blank, entry_set) {
	$(blank + ' input').bind('keydown', function (e) {
		if (e.keyCode == 13) {
			var v = $(this).val();
			v = $.trim(v);
			if (v != '') {
				var ne = entry_set({'title':v});
				ne.css('display', 'none');
				ne.insertBefore($(blank));
				ne.show();
				post_alldata('new' + blank);
				$(this).val('');
			}
		}
	});
}

function move_tip_div(div, pos, ele) {
	var e = $(ele);
	var eleft = e.offset().left;
	var etop = e.offset().top;
	var ewidth = e.width();
	var tip = $(div);
	var twidth = tip.width();
	var text_align;
	var tleft;
	var ttop = etop;
	if (pos == 'left') {
		text_align = 'right';
		tleft = eleft - twidth - 13;
	} else {
		text_align = 'left';
		tleft = eleft + ewidth + 13;
	}
	tip.css('text-align', text_align).css('left', tleft+'px').css('top', ttop+'px');
	tip.animate({ height: 'show', opacity: 'show' }, 'slow');
}

var tip_stat = 'tip1';

function tip_div_close_click() {
	var div = $(this).closest('div');
	switch (tip_stat) {
	case 'tip1':
		div.hide();
		tip_stat = 'tip2';
		move_tip_div('#tip2', 'right', '.todo_table tr:eq(1)');
		break;
	case 'tip2':
		div.hide();
		tip_stat = 'tip3';
		move_tip_div('#tip3', 'left', '.todo_table tr:eq(1)');
		break;
	case 'tip3':
		div.hide();
		$('.tip_top').hide('slow', function() {
			window.location.href = '?';
		});
		break;
	}
}

function first_use() {
	todo_update([
		{'title':'Task 1', 'str':'*,-'},
	]);
	$('.nav').hide();
	$('.tip_top').show();
	move_tip_div('#tip1', 'left', '#todo_blank');
	$('.tip_div_close').click(tip_div_close_click);
}

function clock_timer() {
	if (!$.clock_tm) 
		return ;
	var tm = (new Date()).getTime();
	var diff = ($.clock_tm - tm) / 1000;
	var minute = Math.floor(diff / 60);
	var second = Math.floor(diff) % 60;
	var s;

	if (diff <= 0) {
		alert('番茄时间到');
		$('.clock').html("秒表");
		return false;
	}

	if (second < 10) 
		s = minute + ':0' + second;
	else
		s = minute + ':' + second;
	$('.clock').html(s);

	setTimeout(clock_timer, 500);
}

function clock_click(e) {
	if ($.clock_tm) {
		if (confirm("放弃番茄？")) {
			$('.clock').html("秒表");
			delete $.clock_tm;
		}
		return ;
	}
	$.clock_tm = (new Date()).getTime() + 1000*30;
	clock_timer();
}

$(document).ready(function() {
	$('.clock').click(clock_click);
	$.is_today = $.r.is_today;
	$.is_test = ($.urlParam('test') == '1');
	$.is_firstuse = ($.urlParam('firstuse') == '1');
	$.can_edit = ($.is_today || $.is_test || $.is_firstuse);
	if ($.can_edit) {
		$('.blank_entry').show();
	}
	if ($.is_test) {
		todo_update([
			/*
			{'title':'设计 mysql 表头', 'str':'**'},
			{'title':'写完 index.html', 'str':'**--'},
			{'title':'写完 login.html', 'str':'**,,'},
			*/
		]);
	}
	bind_key('#todo_blank', todo_entry_set);
	bind_key('#outplan_blank', outplan_entry_set);
	bind_key('#alist_blank', alist_entry_set);
	if ($.is_firstuse) {
		first_use();
	} else {
		update_alldata($.r.val);
	}
});

