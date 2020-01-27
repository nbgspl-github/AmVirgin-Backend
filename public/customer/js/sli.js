$('.slider21').slick({
	infinite: false,
	speed: 1000,
	variableWidth: false,
	slidesToShow: 1,
	slidesToScroll: 1,
	arrows: false,
	dots: true,
	autoplay: true,
	autoplaySpeed: 5000,
	responsive: [{
		breakpoint: 600,
		settings: {
			slidesToShow: 1,
			slidesToScroll: 1
		}
	},
		{
			breakpoint: 400,
			settings: {
				arrows: false,
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}]
});


$('.slider22').slick({
	infinite: false,
	speed: 1000,
	slidesToShow: 5.5, // Shows a three slides at a time
	slidesToScroll: 1, // When you click an arrow, it scrolls 1 slide at a time
	arrows: true, // Adds arrows to sides of slider
	dots: false, // Adds the dots on the bottom
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

$('.slider23').slick({
	infinite: false,
	speed: 1000,
	slidesToShow: 5.5, // Shows a three slides at a time
	slidesToScroll: 1, // When you click an arrow, it scrolls 1 slide at a time
	arrows: true, // Adds arrows to sides of slider
	dots: false, // Adds the dots on the bottom
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

$('.slidertrending').slick({
	infinite: false,
	speed: 1000,
	slidesToShow: 2.5, // Shows a three slides at a time
	slidesToScroll: 1, // When you click an arrow, it scrolls 1 slide at a time
	arrows: true, // Adds arrows to sides of slider
	dots: false, // Adds the dots on the bottom
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


window.onscroll = function () {
	scrollFunction()
};

function scrollFunction() {
	if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {

		if (x.matches) {
			document.getElementById("navbar").style.background = "linear-gradient(to bottom, #c00 0%, #900 100%)";
			document.getElementById("logo").style.height = "50px";
			document.getElementById("navbar").style.height = "60px";
		} else {
			document.getElementById("navbar").style.background = "linear-gradient(to bottom, #c00 0%, #900 100%)";
			document.getElementById("logo").style.height = "50px";
			document.getElementById("navbar").style.height = "50px";
		}

		document.getElementById("logo").style.marginTop = "0px";

	} else {

		if (x.matches) {
			document.getElementById("logo").style.height = "50px";
			document.getElementById("navbar").style.height = "60px";
			document.getElementById("logo").style.marginTop = "0px";
		} else {
			document.getElementById("logo").style.height = "60px";
			document.getElementById("navbar").style.height = "90px";
			document.getElementById("logo").style.marginTop = "10px";
		}


		document.getElementById("navbar").style.background = "linear-gradient(to bottom, #00000010 0%, #00000000 100%)";
	}
}

var x = window.matchMedia("(max-width: 700px)")
scrollFunction(x) // Call listener function at run time
x.addListener(scrollFunction)

function openNav() {
	document.getElementById("myNavmob").style.width = "100%";
}

function closeNav() {
	document.getElementById("myNavmob").style.width = "0%";
}


$(document).ready(function () {
	$(window).scroll(function () {
		if ($(this).scrollTop() > 100) {
			$('#scroll').fadeIn();
		} else {
			$('#scroll').fadeOut();
		}
	});
	$('#scroll').click(function () {
		$("html, body").animate({scrollTop: 0}, 600);
		return false;
	});
});


$(document).ready(function () {
	var submitIcon = $('.searchbox-icon');
	var inputBox = $('.searchbox-input');
	var searchBox = $('.searchbox');
	var isOpen = false;
	submitIcon.click(function () {
		if (isOpen == false) {
			searchBox.addClass('searchbox-open');
			inputBox.focus();
			isOpen = true;
		} else {
			searchBox.removeClass('searchbox-open');
			inputBox.focusout();
			isOpen = false;
		}
	});
	submitIcon.mouseup(function () {
		return false;
	});
	searchBox.mouseup(function () {
		return false;
	});
	$(document).mouseup(function () {
		if (isOpen == true) {
			$('.searchbox-icon').css('display', 'block');
			submitIcon.click();
		}
	});
});

function buttonUp() {
	var inputVal = $('.searchbox-input').val();
	inputVal = $.trim(inputVal).length;
	if (inputVal !== 0) {
		$('.searchbox-icon').css('display', 'none');
	} else {
		$('.searchbox-input').val('');
		$('.searchbox-icon').css('display', 'block');
	}
}