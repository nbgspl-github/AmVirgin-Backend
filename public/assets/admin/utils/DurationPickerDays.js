let config = {
	modalId: null,
	durationId: null,
};

let lastDays = '00';
let lastHours = '00';
let lastMinutes = '00';
let lastSeconds = '00';

setupDurationPicker = (config) => {
	window.config = config;
	idify(config.durationId).click(function () {
		handleInvokeDurationPicker();
	});
	idify(config.durationId).prop('readonly', true);
	idify(config.durationId).addClass('bg-white');
};

/**
 * Called when a duration is chosen in picker, and done is clicked.
 * @param days
 * @param hours
 * @param minutes
 * @param seconds
 */
handleDurationChosen = (days, hours, minutes, seconds) => {
	idify(window.config.modalId).modal('hide');
	const element = idify(window.config.durationId);
	if (element.parent().children().length >= 3)
		element.parent().children()[2].remove();
	element.removeClass('parsley-error');
	let duration = days + ":" + hours + ":" + minutes + ":" + seconds;
	if (duration === '00:00:00:00') {
		alertify.log('Minimum expected duration is 1 minute.');
		duration = '00:01:00';
	}
	element.val(duration);
};

/**
 * Invokes duration picker
 */
handleInvokeDurationPicker = () => {
	const duration = idify(window.config.durationId);
	if (duration.val().length === 11) {
		const segments = duration.val().split(':');
		if (segments.length === 4) {
			$('#days').val(lastDays = segments[0]);
			$('#hours').val(lastHours = segments[1]);
			$('#minutes').val(lastMinutes = segments[2]);
			$('#seconds').val(lastSeconds = segments[3]);
		}
	}
	idify(window.config.modalId).modal('show');
};

handleDays = (days) => {
	lastDays = days;
	handleTimeExceptions();
};

handleHours = (hours) => {
	lastHours = hours;
	handleTimeExceptions();
};

handleMinutes = (minutes) => {
	lastMinutes = minutes;
	handleTimeExceptions();
};

handleTimeExceptions = () => {
	const element = $('#seconds');
	if (lastHours === '23' && lastMinutes === '59' || lastMinutes === '59') {
		if (element.val() == '60') {
			element.val('59');
		}
		element.children('option[value="60"]').attr('disabled', true);
	} else {
		element.children('option[value="60"]').attr('disabled', false);
	}
};

idify = (id) => {
	return $('#' + id);
};