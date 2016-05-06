var menu_summary = $('#menu_summary');
var menu_idmt = $('#menu_idmt');
var menu_log = $('#menu_log');

var hnames = [];
var mnames = [];
var snames = [];

function vimInit() {
	menu_summary.on('click', function () {
		toSummary();
	});

	menu_idmt.on('click', function () {
		toIdmt();
	});

	menu_log.on('click', function () {
		toLog();
	});

	$('.ui.checkbox').checkbox();
	$('.ui.dropdown').dropdown();
}

function getMeta() {
	$.get('http://localhost:8000/api/worklog/', function (data) {
		hnames = _(data)
			.uniqBy('hname')
			.map(function (w) {
				return w.hname;
			})
			.value();

		mnames = _(data)
			.uniqBy('mname')
			.map(function (w) {
				return w.mname;
			})
			.value();

		snames = _(data)
			.uniqBy('sname')
			.map(function (w) {
				return w.sname;
			})
			.value();

		console.log(hnames, mnames, snames);
	});
}

$(function () {
	vimInit();
	getMeta();
});
