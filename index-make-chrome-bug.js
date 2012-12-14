
function todo_get(j) {
	$.post('data.php?getdata=1', function (d) {
	});
}

function todo_entry_set(title, str) {
	var strhtml = '';
	for (var i = 0; i < str.length; i++) {
		var ch = str[i];
		strhtml += '<span class=ch>' + ch + '</span>';
	}
	strhtml += '<span>';
	strhtml += '<button class=todo_str_btn>*</button>';
	strhtml += '<button class=todo_str_btn>-</button>';
	strhtml += '</span>';
	return $('<tr>').addClass('todo_entry').append(
			$('<td>').html(title),
			$('<td>').html(strhtml)
	);
}

function todo_del(id) {
	$('#'+id).remove();
}

function todo_str_btn_click() {
	var ch = $(this).html();
	$('<span>').html(ch).insertBefore($(this).parent());
}

function todo_update(j) {
	var e = $('.todo_entry');
	var len = Math.max(j.length, e.length);
	for (var i = 0; i < len; i++) {
		if (i >= e.length) {
			console.log(j[i]);
			var ne = todo_entry_set(j[i].title, j[i].str);
			ne.insertBefore($('#todo_blank'));
		}
	}
	$('.todo_str_btn').click(todo_str_btn_click);
}

function todo_entry_get(o) {
	var td = $(o).find('td');
	var title = $(td[0]).html();
	var spans = $(td[1]).find('span[class=ch]');
	var strhtml = '';
	for (var i in spans) {
		strhtml += spans[i].innerHTML;
	}
	console.log(title, strhtml, spans[0].innerHTML);
}

$(document).ready(function() {
	todo_update([{'title':'Task 1', 'str':'***---'}]);
	todo_entry_get($('.todo_entry'));
	$('#todo_blank input').bind('keyup', function (e) {
		if (e.keyCode == 13) {
			var v = $(this).val();
			v = $.trim(v);
			console.log(v);
			if (v != '') {
				var ne = todo_entry_set(v, '');
				ne.insertBefore($('#todo_blank'));
			}
		}
	});
});

