let config = {
	modalId: null,
	durationId: null,
};

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
 * @param hours
 * @param minutes
 * @param seconds
 */
handleDurationChosen = (hours, minutes, seconds) => {
	idify(window.config.modalId).modal('hide');
	const element = idify(window.config.durationId);
	if (element.parent().children().length >= 3)
		element.parent().children()[2].remove();
	element.removeClass('parsley-error');
	let duration = hours + ":" + minutes + ":" + seconds;
	if (duration === '00:00:00') {
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
	if (duration.val().length === 8) {
		const segments = duration.val().split(':');
		if (segments.length === 3) {
			$('#hours').val(segments[0]);
			$('#minutes').val(segments[1]);
			$('#seconds').val(segments[2]);
		}
	}
	idify(window.config.modalId).modal('show');
};

idify = (id) => {
	return $('#' + id);
};