var menu_summary = $('#menu_summary');
var menu_idmt = $('#menu_idmt');
var menu_log = $('#menu_log');

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
}

$(function () {
	vimInit();
});
