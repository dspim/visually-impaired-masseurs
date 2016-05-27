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
	between: '?',
	by: '加總',
	chart: '長條圖',
	bar: '',
	pie: ''
};
var query_preview_func = function () {
	// 比較 targets, between 之間的 compares, 並以 chart 加上 options, 數值為 by
	var output = '';
	output += '比較 ';
	output += query_preview_body.targets.map(t => t.k + t.v).join(' 且 ');
	output += '的時候，';
	output += query_preview_body.between + ' 之間的 ';
	output += query_preview_body.compares.join(' 及 ');
	output += '，';
	output += '並以 ' + query_preview_body.chart +
		(query_preview_body.chart === '長條圖' ? ' 加上 ' + query_preview_body.bar : ' 加上 ' + query_preview_body.pie);
	output += '，';
	output += '所有數值為 ' + query_preview_body.by;

	query_preview.val(output);
};
