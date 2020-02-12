!function (t) {
	var e = {};

	function r(n) {
		if (e[n]) return e[n].exports;
		var a = e[n] = {i: n, l: !1, exports: {}};
		return t[n].call(a.exports, a, a.exports, r), a.l = !0, a.exports
	}

	r.m = t, r.c = e, r.d = function (t, e, n) {
		r.o(t, e) || Object.defineProperty(t, e, {enumerable: !0, get: n})
	}, r.r = function (t) {
		"undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(t, "__esModule", {value: !0})
	}, r.t = function (t, e) {
		if (1 & e && (t = r(t)), 8 & e) return t;
		if (4 & e && "object" == typeof t && t && t.__esModule) return t;
		var n = Object.create(null);
		if (r.r(n), Object.defineProperty(n, "default", {enumerable: !0, value: t}), 2 & e && "string" != typeof t) for (var a in t) r.d(n, a, function (e) {
			return t[e]
		}.bind(null, a));
		return n
	}, r.n = function (t) {
		var e = t && t.__esModule ? function () {
			return t.default
		} : function () {
			return t
		};
		return r.d(e, "a", e), e
	}, r.o = function (t, e) {
		return Object.prototype.hasOwnProperty.call(t, e)
	}, r.p = "", r(r.s = 0)
}([function (t, e) {
	/**
	 * @preserve
	 * html-duration-picker.js
	 *
	 * @description Turn an html input box to a duration picker, without jQuery
	 * @version 1.0.0
	 * @author Chif <nadchif@gmail.com>
	 * @license MIT
	 *
	 */
	!function (t, e) {
		const r = ["Backspace", "ArrowDown", "ArrowUp", "Tab"], n = t => {
			const e = t.target.selectionStart, r = t.target.value.indexOf(":"), n = t.target.value.lastIndexOf(":");
			if (!(r < 0 || n < 0)) {
				if (e < r) return t.target.setAttribute("data-adjustment-mode", 3600), t.target.selectionStart = 0, void (t.target.selectionEnd = r);
				if (e > r && e < n) return t.target.setAttribute("data-adjustment-mode", 60), t.target.selectionStart = r + 1, void (t.target.selectionEnd = n);
				if (e > n) return t.target.setAttribute("data-adjustment-mode", 1), t.target.selectionStart = n + 1, void (t.target.selectionEnd = n + 3);
				t.target.setAttribute("data-adjustment-mode", "ss"), t.target.selectionStart = n + 1, t.target.selectionEnd = n + 3
			}
		}, a = (t, e) => {
			let r = Math.floor(e / 3600);
			e %= 3600;
			let n = Math.floor(e / 60), a = e % 60;
			n = String(n).padStart(2, "0"), r = String(r).padStart(2, "0"), a = String(a).padStart(2, "0"), t.value = r + ":" + n + ":" + a
		}, i = (t, e) => {
			const r = t.value.indexOf(":"), n = t.value.lastIndexOf(":");
			return t.focus(), t.select(), e >= 3600 ? (t.selectionStart = 0, void (t.selectionEnd = r)) : e >= 60 ? (t.selectionStart = r + 1, void (t.selectionEnd = n)) : (t.selectionStart = n + 1, void (t.selectionEnd = n + 3))
		}, o = t => {
			const e = t.value.split(":");
			let r = 1;
			Number(t.getAttribute("data-adjustment-mode")) > 0 && (r = Number(t.getAttribute("data-adjustment-mode")));
			let n = 0;
			3 === e.length && (n = Number(e[2]) + Number(60 * e[1]) + Number(60 * e[0] * 60)), a(t, n += r), i(t, r)
		}, d = t => {
			const e = t.value.split(":");
			let r = 1;
			Number(t.getAttribute("data-adjustment-mode")) > 0 && (r = Number(t.getAttribute("data-adjustment-mode")));
			let n = 0;
			3 === e.length && (n = Number(e[2]) + Number(60 * e[1]) + Number(60 * e[0] * 60)), (n -= r) < 0 && (n = 0), a(t, n), i(t, r)
		}, s = t => {
			const e = t.target.value.split(":");
			3 === e.length ? (isNaN(e[0]) && (e[0] = "00"), (isNaN(e[1]) || e[1] < 0) && (e[1] = "00"), (e[1] > 59 || e[1].length > 2) && (e[1] = "59"), (isNaN(e[2]) || e[2] < 0) && (e[2] = "00"), (e[2] > 59 || e[2].length > 2) && (e[2] = "59"), t.target.value = e.join(":")) : t.target.value = "00:00:00"
		}, l = t => {
			if ("ArrowDown" != t.key && "ArrowUp" != t.key || ("ArrowDown" == t.key && d(t.target), "ArrowUp" == t.key && o(t.target), t.preventDefault()), isNaN(t.key) && !r.includes(t.key)) return t.preventDefault(), !1
		};
		t.addEventListener("DOMContentLoaded", () => {
			e.querySelectorAll("input[html-duration-picker]").forEach(t => {
				if ("true" == t.getAttribute("data-upgraded")) return;
				const r = t.offsetWidth;
				t.setAttribute("data-upgraded", !0), t.value = "00:00:00", t.style.textAlign = "right", t.style.width = `${r}px`, t.style.paddingRight = "20px", t.setAttribute("aria-label", "Duration Picker"), t.addEventListener("keydown", l), t.addEventListener("select", n), t.addEventListener("click", n), t.addEventListener("change", s), t.addEventListener("blur", s), t.addEventListener("keyup", s), t.addEventListener("drop", t => t.preventDefault());
				const a = e.createElement("div"), i = e.createElement("div");
				a.style.cssText = "width:0;height:0;\n      border-style:solid;border-width:0 4px 5px 4px; border-color:transparent transparent #000 transparent", i.style.cssText = "width:0;height:0;\n      border-style:solid;border-width:5px 4px 0 4px; border-color:#000 transparent transparent transparent";
				const u = e.createElement("button"), p = e.createElement("button");
				u.setAttribute("aria-label", "Increase duration"), p.setAttribute("aria-label", "Decrease duration"), u.style.cssText = `text-align:center; width: 16px;padding: 0px 4px; border:none;\n      height:${t.offsetHeight / 2 - 1}px !important; position:absolute; top: 1px;`, p.style.cssText = `text-align:center; width: 16px;padding: 0px 4px; border:none;\n      height:${t.offsetHeight / 2 - 1}px !important; position:absolute; top: ${t.offsetHeight / 2}px; `, p.appendChild(i), u.appendChild(a), u.addEventListener("mousedown", e => {
					e.preventDefault(), o(t)
				}), p.addEventListener("mousedown", e => {
					e.preventDefault(), d(t)
				});
				const c = e.createElement("div");
				c.style.cssText = `display:inline-block; position: absolute;top:0px;right: 18px;\n      height:${t.offsetHeight}px; padding:2px 0`, c.appendChild(u), c.appendChild(p);
				const g = e.createElement("div");
				g.style.padding = "0px", g.style.display = "inline-block", g.style.background = "transparent", g.style.width = `${r}px`, g.style.position = "relative", t.parentNode.insertBefore(g, t), g.appendChild(t), g.append(c)
			})
		})
	}(window, document)
}]);