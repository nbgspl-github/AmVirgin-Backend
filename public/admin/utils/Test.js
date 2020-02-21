let values = {
	'yearly': 0,
	'rate': 0,
	'install': 0,
	'custom': 0,
	'fees': 0,
};

window.onload = () => {
	values = {
		'yearly': convert($('#yearly').val()),
		'rate': convert($('#rate').val()),
		'install': convert($('#install').val()),
		'custom': convert($('#custom').val()),
		'fees': convert($('#fees').val()),
	};
};

handleValuesUpdated = (value, field) => {
	values[field] = convert(value);
	const total = (values.yearly * values.rate) + (values.fees + values.custom + values.install);
	$('#sum').val(total);
};

convert = (value) => {
	if (isNaN(value)) return 0;
	else return Number.parseInt(value);
};