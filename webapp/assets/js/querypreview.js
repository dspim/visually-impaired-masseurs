var query_preview_body = {
	compares: ['?'],
	targets: [{
		k: '在',
		v: '所有小站'
	}, {
		k: '按摩師為',
		v: '所有按摩師'
	}, {
		k: '管理員為',
		v: '所有管理員'
	}, {
		k: '在',
		v: '所有時間'
	}],
	between: '',
	by: '',
	chart: '',
	bar: '',
	pie: ''
};

var collectInfo = function () {
	query_preview_body.compares = compare_options.filter(o => o.dom.prop('checked')).map(o => o.dom.val());
	if (query_preview_body.compares.length === 0) query_preview_body.compares = ['?'];
	query_preview_body.targets[0].v = target_shop_option.val();
	query_preview_body.targets[1].v = target_masseur_option.val();
	query_preview_body.targets[2].v = target_helper_option.val();
	query_preview_body.targets[3].v = target_period_option.val() + '從' + target_period_from.val() + '到' + target_period_to.val();
	query_preview_body.between = between_option.val();
	query_preview_body.by = $('#rep-by-option').val().replace('以', '各');
	query_preview_body.chart = $('#rep-chart-option').val();
};

var query_preview_func = function () {
	console.log('query_preview_func!');
	collectInfo();
	// targets, between 之間的 compares, 並以 chart 加上 options, 數值為 by
	var output = '';
	output += query_preview_body.targets.filter(t => !t.v.includes('全部')).map(t => t.k + t.v).join(' 且 ');
	if (output !== '') output += '，';
	if (output === '') output += '在';
	output += query_preview_body.between + ' 之間比較 ';
	output += query_preview_body.compares.join(' 與 ');
	output += '的 ' + query_preview_body.by;
	output += '，';
	output += '並以 ' + query_preview_body.chart;
	// if (query_preview_body.chart === '長條圖') output += ' 加上 ' + query_preview_body.bar;
	// if (query_preview_body.chart === '餅圖') output += ' 加上 ' + query_preview_body.pie;
	output += '顯示';

	query_preview.val(output);
};

var detectzone = $('.row')[0];
console.log(detectzone);
detectzone.onclick = query_preview_func;

query_preview.val('在各小站 之間比較 指定節數 與 未指定節數的 各加總，並以 長條圖顯示');
$('#render-title').text('在各小站 之間比較 指定節數 與 未指定節數的 各加總，並以 長條圖顯示');
