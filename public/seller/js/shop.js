var target_date = new Date().getTime() + (1000 * 3600 * 48); // set the countdown date
var days, hours, minutes, seconds; // variables for time units

var countdown = document.getElementById("tiles"); // get tag element

getCountdown();

setInterval(function () {
	getCountdown();
}, 1000);

function getCountdown() {

	// find the amount of "seconds" between now and target
	var current_date = new Date().getTime();
	var seconds_left = (target_date - current_date) / 1000;

	days = pad(parseInt(seconds_left / 86400));
	seconds_left = seconds_left % 86400;

	hours = pad(parseInt(seconds_left / 3600));
	seconds_left = seconds_left % 3600;

	minutes = pad(parseInt(seconds_left / 60));
	seconds = pad(parseInt(seconds_left % 60));

	// format countdown string + set tag value
	countdown.innerHTML = "<span>" + days + "</span><span>" + hours + "</span><span>" + minutes + "</span><span>" + seconds + "</span>";
}

function pad(n) {
	return (n < 10 ? '0' : '') + n;
}


$('.slidershop').slick({
	infinite: false,
	speed: 1000,
	slidesToShow: 1,
	slidesToScroll: 1,
	arrows: false,
	dots: true,
	autoplay: true,
	autoplaySpeed: 5000,
	responsive: [{
		breakpoint: 600,
		settings: {
			slidesToShow: 2,
			slidesToScroll: 2
		}
	},
		{
			breakpoint: 400,
			settings: {
				arrows: true,
				slidesToShow: 2,
				slidesToScroll: 2
			}
		}]
});

$('.slidervideo').slick({
	infinite: false,
	speed: 1000,
	slidesToShow: 6,
	slidesToScroll: 1,
	arrows: true,
	dots: false,
	autoplay: false,
	autoplaySpeed: 5000,
	responsive: [{
		breakpoint: 600,
		settings: {
			slidesToShow: 2,
			slidesToScroll: 2
		}
	},
		{
			breakpoint: 400,
			settings: {
				arrows: true,
				slidesToShow: 2,
				slidesToScroll: 2
			}
		}]
});


var rangeslider = document.getElementById("myRange");
var output = document.getElementById("demo");
output.innerHTML = rangeslider.value;

rangeslider.oninput = function () {
	output.innerHTML = this.value;
}


$('.dropdown-el').click(function (e) {
	e.preventDefault();
	e.stopPropagation();
	$(this).toggleClass('expanded');
	$('#' + $(e.target).attr('for')).prop('checked', true);
});
$(document).click(function () {
	$('.dropdown-el').removeClass('expanded');
});


function openNavdesk() {
	document.getElementById("mySidenav").style.width = "20%";
}

function closeNavdesk() {
	document.getElementById("mySidenav").style.width = "0";
}

