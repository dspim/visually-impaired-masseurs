var compare_options = [];
var compare_option_assigned = $('#compare-option-assigned');
var compare_option_not_assigned = $('#compare-option-not-assigned');
var compare_option_guest_num = $('#compare-option-guest-num');
compare_options.push({
	dom: compare_option_assigned,
	name: 'assigned'
});
compare_options.push({
	dom: compare_option_not_assigned,
	name: 'not_assigned'
});
compare_options.push({
	dom: compare_option_guest_num,
	name: 'guest_num'
});

var target_shop_option = $('#target-shop-option');
var target_masseur_option = $('#target-masseur-option');
var target_helper_option = $('#target-helper-option');
var target_period_option = $('#target-period-option');
var target_period_from = $('#target-period-from');
var target_period_to = $('#target-period-to');
var if_target_period_option_is_not_all = $('#if-target-period-option-is-not-all');

var between_option = $('#between-option');
var if_between_option_is_period = $('#if-between-option-is-period');

var between_period_from = $('#between-period-from');
var between_period_to = $('#between-period-to');
var between_period_step = $('#between-period-step');

var query_preview = $('#query-preview');

var render_zone = $('#render-zone');


target_period_option.on('change', function () {
	if (target_period_option.val() === '一段時期') {
		if_target_period_option_is_not_all.show(500);
	} else {
		if_target_period_option_is_not_all.hide(500);
	}
});

between_option.on('change', function () {
	if (between_option.val() === '各時期') {
		if_between_option_is_period.show(500);
	} else {
		if_between_option_is_period.hide(500);
	}
});


var gen_query_preview = function () {
	var real_compare_options = compare_options.filter(o => o.dom.prop('checked')).map(o => o.name);

	var real_target_shop_option = target_shop_option.val().includes('全部') ? undefined : target_shop_option.val();
	var real_target_masseur_option = target_masseur_option.val().includes('全部') ? undefined : target_masseur_option.val();
	var real_target_helper_option = target_helper_option.val().includes('全部') ? undefined : target_helper_option.val();
	var real_target_period_option = target_period_option.val().includes('全部') ? undefined : target_period_option.val();
	var real_target_period_from = undefined;
	var real_target_period_to = undefined;
	if (real_target_period_option === '一段時期') {
		real_target_period_from = target_period_from.val();
		real_target_period_to = target_period_to.val();
	}

	var real_between_option = between_option.val();
	var real_between_period_from = undefined;
	var real_between_period_to = undefined;
	var real_between_period_step = undefined;
	if (real_between_option === '各時期') {
		real_between_period_from = between_period_from.val();
		real_between_period_to = between_period_to.val();
		real_between_period_step = between_period_step.val();
	}

	// usage: analyze.py [-h] [--compare COMPARE] [--fromDate FROMDATE]
	//               [--toDate TODATE] [--step STEP] [--between BETWEEN]
	//               [--masseur MASSEUR] [--helper HELPER] [--shop SHOP]
	//               [--by BY] [--barMode BARMODE]
	//
	// Analyze
	//
	// optional _arguments:
	//   -h, --help           show this help message and exit
	//   --compare COMPARE    Compare attributes, including assigned, not_assigned,
	//                        guest. ex.: --compare assigned,not_assigned
	//   --fromDate FROMDATE  From date for target's period or between's period. ex.:
	//                        --from 2015-04-01
	//   --toDate TODATE      Last date for target's period. ex.: --to 2015-04-01
	//   --step STEP          Select one time step format for between's period,
	//                        including week, month, season. ex.: --step season
	//   --between BETWEEN    Select one between attributes, including masseur,
	//                        helper, shop, period. ex.: --between masseur
	//   --masseur MASSEUR    Target masseur Name
	//   --helper HELPER      Target helper Name
	//   --shop SHOP          Target shop Name
	//   --by BY              Select one aggregate argument, including sum, count,
	//                        average. ex.: --by sum
	//   --barMode BARMODE    Select one bar char mode , including stack,group. ex.:
	//                        --by sum

	var _arguments = '';
	if (real_compare_options.length > 0) {
		_arguments += ' --compare ' + real_compare_options.join(',');
	}
	if (real_target_shop_option !== undefined) {
		_arguments += ' --shop ' + real_target_shop_option;
	}
	if (real_target_masseur_option !== undefined) {
		_arguments += ' --masseur ' + real_target_masseur_option;
	}
	if (real_target_helper_option !== undefined) {
		_arguments += ' --helper ' + real_target_helper_option;
	}
	if (real_target_period_option !== undefined) {
		_arguments += ' --fromDate ' + real_target_period_from + ' --toDate ' + real_target_period_to;
	}
	if (real_between_option !== undefined) {
		_arguments += ' --between ' +
			(real_between_option === '各小站' ? 'shop' :
				real_between_option === '各按摩師' ? 'masseur' :
				real_between_option === '各時期' ? 'period' :
				'helper');
		if (real_between_period_from !== undefined) {
			_arguments += ' --fromDate ' + real_between_period_from;
		}
		if (real_between_period_to !== undefined) {
			_arguments += ' --toDate ' + real_between_period_to;
		}
		if (real_between_period_from !== undefined) {
			var cname;
			switch (real_between_period_step) {
				case '每週':
					cname = 'week';
					break;
				case '每月':
					cname = 'month';
					break;
				case '每季':
					cname = 'season';
					break;
				default:
					alert('請選擇週期');
			}
			_arguments += ' --step ' + cname;
		}
	}

	console.log(_arguments);
};
