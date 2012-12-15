
function todo_entry_set(j) {
	var title = j.title;
	var str = j.str;
	if (str == undefined) {
		str = '';
	}
	var strnode = $('<div class=todo_str_left>');
	for (var i = 0; i < str.length; i++) {
		var ch = str.substr(i, 1);
		strnode.append('<span class=todo_ch>' + ch + '</span>');
	}
	var strbtn = $('<div class=todo_str_right>');
	strbtn.append($('<button>').html('*').click(todo_str_btn_click))
	strbtn.append($('<button>').html(',').click(todo_str_btn_click));
	strbtn.append($('<button>').html('-').click(todo_str_btn_click));
	return $('<tr>')
			.attr('str', str)
			.addClass('todo_entry').append(
			$('<td>').html(title),
			$('<td>').append(strnode).append(strbtn)
	);
}

function todo_entry_get(o) {
	var td = $(o).find('td');
	var title = $(td[0]).html();
	var spans = $(td[1]).find('span[class=todo_ch]');
	var str = $(o).attr('str');
	return {'title':title, 'str':str};
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

function todo_str_btn_click() {
	var ch = $(this).html();
	var span = $('<span class=todo_ch>').html(ch);
	var l = $(this).parent().parent().find('.todo_str_left');
	l.append(span);
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

function bind_key(blank, entry_set) {
	$(blank + ' input').bind('keyup', function (e) {
		if (e.keyCode == 13) {
			var v = $(this).val();
			v = $.trim(v);
			if (v != '') {
				var ne = entry_set({'title':v});
				ne.css('display', 'none');
				ne.insertBefore($(blank));
				ne.show('slow');
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
		move_tip_div('#tip3', 'right', '.todo_table tr:eq(1)');
		break;
	case 'tip3':
		div.hide();
		$('.tip_top').hide('slow');
		$('.nav').show();
		tip_stat = 'tip_done';
		todo_update([]);
		break;
	}
}

$(document).ready(function() {
	todo_update([
		{'title':'Task 1', 'str':'*,-'},
	]);
	$('.nav').hide();
	move_tip_div('#tip1', 'left', '#todo_blank');
	$('.tip_div_close').click(tip_div_close_click);
//	todo_update([
//		{'title':'Task 1', 'str':'***---'},
//		{'title':'Task 2', 'str':'***-*-'},
//	]);
	bind_key('#todo_blank', todo_entry_set);
	bind_key('#outplan_blank', outplan_entry_set);
	bind_key('#alist_blank', alist_entry_set);
});

