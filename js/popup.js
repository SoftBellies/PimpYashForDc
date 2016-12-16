$(function() {
	$('#yash3-cancel').click(function() {
		window.close();
		return false;
	});

	$('#yash3-ok').click(function() {
		sendClose();
		window.close();
		return false;
	});

	function sendClose() {
		var insert_form = $('#yash3-form').get(0);
		if (insert_form == undefined) { return; }
		var tb = window.opener.the_toolbar;
		var data = tb.elements.yash3.data;
		data.syntax = insert_form.syntax.value;
		tb.elements.yash3.fncall[tb.mode].call(tb);
	};
});
