function chart_spline(element, data_columns) {
	c3.generate({
		bindto: element,
		title: {
			color: '#fff',
		},
		data: {
			x: 'time',
			xFormat: '%Y-%m-%d',
			json: data_columns,
			type: 'area-spline'
		},
		options: {
			responsive: true
		},
		grid: {
			x: {
				show: true
			},
			y: {
				show: true
			}
		},
		color: {
			pattern: ['#28a745', '#ffc107', '#6c757d', '#ffdf80', '#98e6ab', '#dc3545']
		},
		axis: {
			x: {
				type: 'timeseries',
				tick: {
					format: '%Y-%m-%d'
				},
			},
			y: {
				tick: {
					format: function (d) {
						return (parseInt(d) == d) ? d : null;
					}
				}
			}
		},
		legend: {
			show: false,
		}
	});
}
