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
	name: 'guest'
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

var between_period_step = $('#between-period-step');

var rep_chart_option = $('#rep-chart-option');

var query_preview = $('#query-preview');

var render_button = $('#render-button');
var render_zone = $('#render-zone');

target_shop_option.on('change', function () {
	if (!target_shop_option.val().includes('全部')) {
		$('#between-option-shop').hide(0);
	} else {
		$('#between-option-shop').show(0);
	}
});

target_masseur_option.on('change', function () {
	if (!target_masseur_option.val().includes('全部')) {
		$('#between-option-masseur').hide(0);
	} else {
		$('#between-option-masseur').show(0);
	}
});

target_helper_option.on('change', function () {
	if (!target_helper_option.val().includes('全部')) {
		$('#between-option-helper').hide(0);
	} else {
		$('#between-option-helper').show(0);
	}
});

target_period_option.on('change', function () {
	if (target_period_option.val() === '一段時期') {
		$('#between-option-period').hide(0);
		if_target_period_option_is_not_all.show(500);
		// $('#rep-chart-option').html('<option style="padding-left:12px">線圖</option>');
	} else {
		$('#between-option-period').show(0);
		if_target_period_option_is_not_all.hide(500);
		// $('#rep-chart-option').html('<option style="padding-left:12px">長條圖</option><option style="padding-left:12px">餅圖</option>');
	}
});

between_option.on('change', function () {
	if (between_option.val() === '各時期') {
		if_between_option_is_period.show(500);
	} else {
		if_between_option_is_period.hide(500);
	}
});

$('#rep-pie-option').attr('disabled', true);
rep_chart_option.on('change', function () {
	if (rep_chart_option.val() === '長條圖') {
		$('#rep-bar-option').attr('disabled', false);
		$('#rep-pie-option').attr('disabled', true);
	} else if (rep_chart_option.val() === '餅圖') {
		$('#rep-bar-option').attr('disabled', true);
		$('#rep-pie-option').attr('disabled', false);
	} else {
		$('#rep-bar-option').attr('disabled', true);
		$('#rep-pie-option').attr('disabled', true);
	}
});

var _arguments = '';
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
	var real_between_period_step = undefined;
	if (real_between_option === '各時期') {
		real_between_period_step = between_period_step.val();
	}

	// optional arguments:
	//   -h, --help            show this help message and exit
	//   --compare COMPARE     Compare attributes, including assigned, not_assigned,
	//                         guest. ex.: --compare assigned,not_assigned
	//   --fromDate FROMDATE   From date for target's period or between's period.
	//                         ex.: --from 2015-04-01
	//   --toDate TODATE       Last date for target's period. ex.: --to 2015-04-01
	//   --step STEP           Select one time step format for between's period,
	//                         including week, month, season. ex.: --step season
	//   --between BETWEEN     Select one between attributes, including masseur,
	//                         helper, shop. ex.: --between masseur
	//   --masseur MASSEUR     Target masseur Name
	//   --helper HELPER       Target helper Name
	//   --shop SHOP           Target shop Name
	//   --by BY               Select one aggregate argument, including sum, count,
	//                         average. ex.: --by sum
	//   --chartMode CHARTMODE
	//                         Select one chart mode , including bar,pie,line. ex.:
	//                         --chartMode pie
	//   --barMode BARMODE     Select one bar chart mode , including stack,group.
	//                         ex.: --barMode group
	//   --pieMode PIEMODE     Select one bar pie mode , including sum, split. ex.:
	//                         --pieMode sum
	//   --sortBy SORTBY       Sort bar chart by COMPARE, assigned+not_assigned. ex.:
	//                         --sortBy assigned+not_assigned

	if (real_compare_options.length > 0) {
		_arguments += ' --compare ' + real_compare_options.join(',');
	}
	if (real_target_shop_option !== undefined) {
		_arguments += ' --shop ' + '\"' + real_target_shop_option + '\"';
	}
	if (real_target_masseur_option !== undefined) {
		_arguments += ' --masseur ' + '\"' + real_target_masseur_option + '\"';
	}
	if (real_target_helper_option !== undefined) {
		_arguments += ' --helper ' + '\"' + real_target_helper_option + '\"';
	}
	if (real_target_period_option !== undefined) {
		_arguments += ' --fromDate ' + real_target_period_from + ' --toDate ' + real_target_period_to;
	}
	if (real_between_option !== undefined) {
		_arguments += ' --between ' +
			(real_between_option === '各小站' ? 'shop' :
				real_between_option === '各按摩師' ? 'masseur' :
				real_between_option === '各時期' ? 'period' :
				real_between_option === '各管理員' ? 'helper' :
				'');
		if (real_between_option === '各時期') {
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
	} else {
		alert('請選擇比較的對象');
	}

	//   --chartMode CHARTMODE
	//                         Select one chart mode , including bar,pie,line. ex.:
	//                         --chartMode pie
	//   --barMode BARMODE     Select one bar chart mode , including stack,group.
	//                         ex.: --barMode group
	//   --pieMode PIEMODE     Select one bar pie mode , including sum, split. ex.:
	//                         --pieMode sum
	//   --sortBy SORTBY       Sort bar chart by COMPARE, assigned+not_assigned. ex.:
	//                         --sortBy assigned+not_assigned

	// by
	var rep_by = $('#rep-by-option').val();
	var real_rep_by_option = (rep_by === '以加總' ? 'sum' :
		rep_by === '以資料筆數' ? 'count' : 'average');
	// chartMode
	var rep_chart = $('#rep-chart-option').val();
	var real_rep_chart_option =
		(rep_chart === '長條圖' ? 'bar' :
			rep_chart === '線圖' ? 'line' : 'pie');
	// barMode
	var rep_bar = $('#rep-bar-option').val();
	var real_rep_bar_option = (rep_bar === '以分組' ? 'group' : 'stack');
	// pieMode
	var rep_pie = $('#rep-pie-option').val();
	var real_rep_pie_option = (rep_pie === '多個' ? 'sum' : 'split');
	// sortBy
	var rep_sort = $('#rep-sort-option').val();
	var real_rep_sort_option =
		(rep_sort === '不用排序' ? undefined :
			rep_sort === '以指定節數' ? 'assigned' :
			rep_sort === '以未指定節數' ? 'not_assigned' :
			rep_sort === '以指定節數+未指定節數' ? '"assigned_not_assigned"' : 'guest');

	_arguments += ' --by ' + real_rep_by_option;

	_arguments += ' --chartMode ' + real_rep_chart_option;

	if (real_rep_chart_option === 'bar') {
		_arguments += ' --barMode ' + real_rep_bar_option;
	} else if (real_rep_chart_option === 'line') {
		_arguments += ' --pieMode ' + real_rep_pie_option;
	}

	if (real_rep_sort_option !== undefined) {
		_arguments += ' --sortBy ' + real_rep_sort_option;
	}

	console.log(_arguments);

	$(window).scrollTop(0);
};

$.get('../api/shop', function (data) {
	console.log(data);
	data.map(o => o.sname)
		.forEach(n => {
			target_shop_option.append('<option style="padding-left:12px">' + n + '</option>');
		});
});
$.get('../api/masseur', function (data) {
	console.log(data);
	data.map(o => o.mname)
		.forEach(n => {
			target_masseur_option.append('<option style="padding-left:12px">' + n + '</option>');
		});
});
$.get('../api/helper', function (data) {
	console.log(data);
	data.map(o => o.hname)
		.forEach(n => {
			target_helper_option.append('<option style="padding-left:12px">' + n + '</option>');
		});
});

render_button.on('click', function () {
	_arguments = '';
	gen_query_preview();
	pydraw(_arguments);
});

var pydraw = function (__arguments) {
	render_zone.html('<p>載入中...</p>');
	$.ajax({
			url: '../api/py/index.php?arg=' + __arguments,
			type: 'GET'
		})
		.done(function (data) {
			// console.log(data);
			var dom_data = data.split('<script type="text/javascript">')[0];
			var script_data = data.split('<script type="text/javascript">')[1].split('</script>')[0];

			render_zone.html(dom_data);
			setTimeout(() => {
				eval(script_data);
			}, 10);

			$('#render-title').text(query_preview.val());
		});
};

pydraw('--compare assigned,not_assigned --fromDate 2015-01-01 --toDate 2015-06-01 --between shop --by sum');
