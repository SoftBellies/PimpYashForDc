jsToolBar.prototype.elements.yash3Space = {type: 'space',
	format: {
		wysiwyg: true,
		wiki: true,
		xhtml: true,
		markdown: true
	}
};

jsToolBar.prototype.elements.yash3 = {
	type: 'button',
	title: 'Highlighted Code',
	context: 'post',
	icon: 'index.php?pf=yash3/icon.png',
	fn:{},
	fncall:{},
	open_url:'plugin.php?p=yash3&popup=1',
	data:{},
	popup: function() {
		window.the_toolbar = this;
		this.elements.yash3.data = {};

		var p_win = window.open(this.elements.yash3.open_url,'dc_popup',
		'alwaysRaised=yes,dependent=yes,toolbar=yes,height=240,width=480,'+
		'menubar=no,resizable=yes,scrollbars=yes,status=no');
	}
};

jsToolBar.prototype.elements.yash3.fn.wiki = function() {
	this.elements.yash3.popup.call(this);
};
jsToolBar.prototype.elements.yash3.fn.xhtml = function() {
	this.elements.yash3.popup.call(this);
};
jsToolBar.prototype.elements.yash3.fn.markdown = function() {
	this.elements.yash3.popup.call(this);
};

jsToolBar.prototype.elements.yash3.fncall.wiki = function() {
	var stag = '\n///yash3 ' + this.elements.yash.data.syntax + '\n';
	var etag = '\n///\n';
	this.encloseSelection(stag,etag);
};
jsToolBar.prototype.elements.yash3.fncall.xhtml = function() {
	var stag = '<pre class="brush: ' + this.elements.yash3.data.syntax + '">\n';
	var etag = '\n</pre>\n';
	this.encloseSelection(stag,etag);
};
jsToolBar.prototype.elements.yash3.fncall.markdown = function() {
	var stag = '<pre class="brush: ' + this.elements.yash3.data.syntax + '">\n';
	var etag = '\n</pre>\n';
	this.encloseSelection(stag,etag);
};
