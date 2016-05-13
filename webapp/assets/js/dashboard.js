var compare_options = [];
var compare_option_assigned = $('#compare-option-assigned');
var compare_option_not_assigned = $('#compare-option-not-assigned');
var compare_option_guest_num = $('#compare-option-guest-num');
compare_options.push(compare_option_assigned);
compare_options.push(compare_option_not_assigned);
compare_options.push(compare_option_guest_num);

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

var gen_query_preview = function () {
	var real_compare_options = .map(o => o.prop('checked')).filter(o => o);

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
	var real_between_period_from;
	var real_between_period_to;
	var real_between_period_step;
	if (real_between_option === '各時期') {
		real_between_period_from = between_period_from.val();
		real_between_period_to = between_period_to.val();
		real_between_period_step = between_period_step.val();
	}
};
