!function (t) {
	"use strict";
	"function" == typeof define && define.amd ? define(["jquery"], t) : "object" == typeof exports && "object" == typeof module ? module.exports = t(require("jquery")) : t(jQuery)
}(function (t, r) {
	"use strict";
	t.fn.percircle = function (r) {
		var s = {animate: !0};
		r || (r = {}), t.extend(r, s);
		var o = 3.6;
		return this.each(function () {
			var s = t(this), n = "", d = function (t, r) {
				s.on("mouseover", function () {
					t.children("span").css("color", r)
				}), s.on("mouseleave", function () {
					t.children("span").attr("style", "")
				})
			};
			s.hasClass("percircle") || s.addClass("percircle"), "undefined" != typeof s.attr("data-animate") && (r.animate = "true" == s.attr("data-animate")), r.animate && s.addClass("animate"), "undefined" != typeof s.attr("data-progressBarColor") ? (r.progressBarColor = s.attr("data-progressBarColor"), n = "style='border-color: " + r.progressBarColor + "'", d(t(this), r.progressBarColor)) : "undefined" != typeof r.progressBarColor && (n = "style='border-color: " + r.progressBarColor + "'", d(t(this), r.progressBarColor));
			var i = s.attr("data-percent") || r.percent || 0, c = s.attr("data-perclock") || r.perclock || 0, l = s.attr("data-perdown") || r.perdown || 0;
			if (i) {
				i > 50 && s.addClass("gt50");
				var f = s.attr("data-text") || r.text || i + "%";
				s.html("<span>" + f + "</span>"), t('<div class="slice"><div class="bar" ' + n + '></div><div class="fill" ' + n + "></div></div>").appendTo(s), i > 50 && t(".bar", s).css({"-webkit-transform": "rotate(180deg)", "-moz-transform": "rotate(180deg)", "-ms-transform": "rotate(180deg)", "-o-transform": "rotate(180deg)", transform: "rotate(180deg)"});
				var m = o * i;
				setTimeout(function () {
					t(".bar", s).css({"-webkit-transform": "rotate(" + m + "deg)", "-moz-transform": "rotate(" + m + "deg)", "-ms-transform": "rotate(" + m + "deg)", "-o-transform": "rotate(" + m + "deg)", transform: "rotate(" + m + "deg)"})
				}, 0)
			} else c ? (s.hasClass("perclock") || s.addClass("perclock"), setInterval(function () {
				var r = new Date, e = a(r.getHours()) + ":" + a(r.getMinutes()) + ":" + a(r.getSeconds());
				s.html("<span>" + e + "</span>"), t('<div class="slice"><div class="bar" ' + n + '></div><div class="fill" ' + n + "></div></div>").appendTo(s);
				var o = r.getSeconds();
				0 === o && s.removeClass("gt50"), o > 30 && (s.addClass("gt50"), t(".bar", s).css({"-webkit-transform": "rotate(180deg);scale(1,3)", "-moz-transform": "rotate(180deg);scale(1,3)", "-ms-transform": "rotate(180deg);scale(1,3)", "-o-transform": "rotate(180deg);scale(1,3)", transform: "rotate(180deg);scale(1,3)"}));
				var d = 6 * o;
				t(".bar", s).css({"-webkit-transform": "rotate(" + d + "deg)", "-moz-transform": "rotate(" + d + "deg)", "-ms-transform": "rotate(" + d + "deg)", "-o-transform": "rotate(" + d + "deg)", transform: "rotate(" + d + "deg)"})
			}, 1e3)) : l && e(s, r, n)
		})
	};
	var e = function (r, e, a) {
		function s() {
			return c -= 1, c > 30 && r.addClass("gt50"), 30 > c && r.removeClass("gt50"), i(), 0 >= c ? (n(), void r.html("<span>" + l + "</span>")) : void 0
		}

		function o() {
			m = setInterval(s, 1e3)
		}

		function n() {
			clearInterval(m)
		}

		function d() {
			n(), c = e.secs, i(), o()
		}

		function i() {
			r.html("<span>" + c + "</span>"), t('<div class="slice"><div class="bar" ' + a + '></div><div class="fill" ' + a + "></div></div>").appendTo(r);
			var e = 6 * c;
			t(".bar", r).css({"-webkit-transform": "rotate(" + e + "deg)", "-moz-transform": "rotate(" + e + "deg)", "-ms-transform": "rotate(" + e + "deg)", "-o-transform": "rotate(" + e + "deg)", transform: "rotate(" + e + "deg)"})
		}

		var c = r.attr("data-secs") || e.secs, l = r.attr("data-timeUpText") || e.timeUpText, f = r[0].hasAttribute("data-reset") || e.reset;
		l.length > 8 && (l = "the end");
		var m;
		f && r.on("click", d), o()
	}, a = function (t) {
		return 10 > t ? "0" + t : t
	}
});