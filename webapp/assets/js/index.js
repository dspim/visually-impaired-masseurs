var render_zone = $('#render-zone');
var pydraw = function (__arguments) {
	// render_zone.html('<p>載入中...</p>');
	$.ajax({
			url: '../api/py/index.php?arg=' + __arguments,
			type: 'GET'
		})
		.done(function (data) {
			var dom_data = data.split('<script type="text/javascript">')[0];
			var script_data = data.split('<script type="text/javascript">')[1].split('</script>')[0];
			render_zone.html(dom_data);
			setTimeout(() => {
				eval(script_data);
			}, 10);
		});
};
pydraw('--compare assigned,not_assigned --fromDate 2015-05-01 --toDate 2015-06-01 --between shop --by sum');

var table_masseur = function () {
	$.ajax({
			url: '../api/worklog',
			type: 'GET'
		})
		.done(function (data) {
			var masseurs = _(data)
				.groupBy('mname')
				.mapValues(v => {
					var tmp = {};
					tmp.assigned = 0;
					tmp.not_assigned = 0;
					tmp.guest_num = 0;
					tmp.mname = v[0].mname;
					tmp.income = 0;
					tmp.days = 0;
					v.forEach(w => {
						tmp.assigned += w.assigned;
						tmp.not_assigned += w.not_assigned;
						tmp.guest_num += w.guest_num;
						tmp.days++;
					});
					tmp.income = 100 * (tmp.assigned + tmp.not_assigned);
					tmp.average = parseInt(tmp.income / tmp.days, 10);
					return tmp;
				})
				.values()
				.sortBy('income')
				.reverse()
				.value();

			var table_dom = '<table class="table table-striped"><thead>\
			<tr>\
				<th>收入</th>\
				<th>平均收入</th>\
				<th>名字</th>\
				<th>指定節數</th>\
				<th>未指定節數</th>\
				<th>來客數</th>\
				<th>天數<th/>\
			</tr>\
			</thead>\
			<tbody>' + (() => masseurs
				.map(m => {
					return '<tr>\
						<th>' + m.income + '</th>\
						<th>' + m.average + '</th>\
						<th>' + m.mname + '</th>\
						<th>' + m.assigned + '</th>\
						<th>' + m.not_assigned + '</th>\
						<th>' + m.guest_num + '</th>\
						<th>' + m.days + '</th>\
					</tr>';
				})
				.join(''))() + '</tbody></table>';

			$('#table-masseur').html(table_dom);
		});
};
table_masseur();
