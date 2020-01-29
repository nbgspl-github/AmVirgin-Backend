<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<link rel="icon" href="/app/img/logo.png"/>
	<meta name="viewport" content="width=device-width,initial-scale=1"/>
	<meta name="theme-color" content="#000000"/>
	<meta name="description" content="Web site created using create-react-app"/>
	<link rel="stylesheet" href="/app/assets/style2.css"/>
	<link rel="stylesheet" href="/app/assets/css/vendor/line-awesome.css"/>
	<link rel="stylesheet" href="/app/assets/css/vendor/themify.css"/>
	<link rel="stylesheet" href="/app/assets/css/swiper.min.css"/>
	<link rel="stylesheet" href="/app/bootstrap/bootstrap.min.css"/>
	<link rel="stylesheet" href="/app/slider/slick.css"/>
	<link rel="apple-touch-icon" href="/app/img/logo.png"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
	<script defer="defer" src="https://kit.fontawesome.com/961bdbfd19.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" charset="UTF-8" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css"/>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"/>
	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet"/>
	<link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"/>
	<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet"/>
	<link rel="stylesheet" href="https://video-react.github.io/assets/video-react.css"/>
	<link rel="stylesheet" href="/app/assets/appcss/style.css"/>
	<title>
		Amvirgin</title>
	<link href="/app/static/css/main.921498ef.chunk.css" rel="stylesheet">
</head>
<body>
<a href="#0" id="scroll" style="display:none"><span></span></a>
<noscript>
	You
	need
	to
	enable
	JavaScript
	to
	run
	this
	app.
</noscript>
<div id="root"></div>
<script src="/app/js/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="/app/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="/app/slider/slick.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.5.7/slick.min.js"></script>
<script src="/app/assets/sli.js"></script>
<script src="/app/assets/shop.js"></script>
<script type="text/javascript" src="/app/assets/sticky-sidebar.js"></script>
<script type="text/javascript" src="/app/assets/jqzoom.js"></script>
<script src="https://unpkg.com/video.js/dist/video.js"></script>
<script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>
<script>$(document).ready(function () {
		$(window).scroll(function () {
			100 < $(this).scrollTop() ? $("#scroll").fadeIn() : $("#scroll").fadeOut()
		}), $("#scroll").click(function () {
			return $("html, body").animate({scrollTop: 0}, 600), !1
		})
	})</script>
<script>!function (l) {
		function e(e) {
			for (var r, t, n = e[0], o = e[1], u = e[2], a = 0, p = []; a < n.length; a++) t = n[a], Object.prototype.hasOwnProperty.call(f, t) && f[t] && p.push(f[t][0]), f[t] = 0;
			for (r in o) Object.prototype.hasOwnProperty.call(o, r) && (l[r] = o[r]);
			for (s && s(e); p.length;) p.shift()();
			return i.push.apply(i, u || []), c()
		}

		function c() {
			for (var e, r = 0; r < i.length; r++) {
				for (var t = i[r], n = !0, o = 1; o < t.length; o++) {
					var u = t[o];
					0 !== f[u] && (n = !1)
				}
				n && (i.splice(r--, 1), e = a(a.s = t[0]))
			}
			return e
		}

		var t = {},
			f = {1: 0},
			i = [];

		function a(e) {
			if (t[e]) return t[e].exports;
			var r = t[e] = {
				i: e,
				l: !1,
				exports: {}
			};
			return l[e].call(r.exports, r, r.exports, a), r.l = !0, r.exports
		}

		a.m = l, a.c = t, a.d = function (e, r, t) {
			a.o(e, r) || Object.defineProperty(e, r, {
				enumerable: !0,
				get: t
			})
		}, a.r = function (e) {
			"undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(e, "__esModule", {value: !0})
		}, a.t = function (r, e) {
			if (1 & e && (r = a(r)), 8 & e) return r;
			if (4 & e && "object" == typeof r && r && r.__esModule) return r;
			var t = Object.create(null);
			if (a.r(t), Object.defineProperty(t, "default", {
				enumerable: !0,
				value: r
			}), 2 & e && "string" != typeof r) for (var n in r) a.d(t, n, function (e) {
				return r[e]
			}.bind(null, n));
			return t
		}, a.n = function (e) {
			var r = e && e.__esModule ? function () {
				return e.default
			} : function () {
				return e
			};
			return a.d(r, "a", r), r
		}, a.o = function (e, r) {
			return Object.prototype.hasOwnProperty.call(e, r)
		}, a.p = "/";
		var r = this["webpackJsonpcreate-react-app-example"] = this["webpackJsonpcreate-react-app-example"] || [],
			n = r.push.bind(r);
		r.push = e, r = r.slice();
		for (var o = 0; o < r.length; o++) e(r[o]);
		var s = n;
		c()
	}([])</script>
<script src="/app/static/js/2.ed8d8ca9.chunk.js"></script>
<script src="/app/static/js/main.48143131.chunk.js"></script>
</body>
</html>