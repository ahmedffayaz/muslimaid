! function(e) {
	e.fn.jsonSuggest = function(t) {
		var a, i = {
			url: "",
			data: [],
			minCharacters: 2,
			maxResults: void 0,
			wildCard: "%",
			caseSensitive: !1,
			notCharacter: "!",
			maxHeight: 350,
			highlightMatches: !0,
			onSelect: void 0,
			width: 367
		};
		return t = e.extend(i, t), this.each(function() {
			function i(e, t) {
				var a = ["/", ".", "*", "+", "?", "|", "(", ")", "[", "]", "{", "}", "\\"];
				if (t)
					for (var i = 0; i < a.length; i++) a[i] === t && a.splice(i, 1);
				var n = new RegExp("(\\" + a.join("|\\") + ")", "g");
				return e.replace(n, "\\$1")
			}

			function n(a) {
				d.val(a.text), e(f).html("").hide(), "function" == typeof t.onSelect && t.onSelect(a)
			}

			function r(t) {
				e("li a", f).removeClass("ui-state-hover"), t && e("a", t).addClass("ui-state-hover"), u = t
			}

			function o(a, i) {
				i = "(" + i + ")";
				var o, l = !0,
					s = 0,
					c = t.caseSensitive ? new RegExp(i, "g") : new RegExp(i, "ig");
				for (e(f).html("").hide(), o = 0; o < a.length; o += 1) {
					var h = e("<li />"),
						d = a[o].text;
					if (t.highlightMatches === !0 && (d = d.replace(c, "<strong>$1</strong>")), e(h).append('<a class="ui-corner-all_new">' + d + "</a>"), "string" == typeof a[o].image && e(">a", h).prepend('<img src="' + a[o].image + '" />'), "string" == typeof a[o].extra && e(">a", h).append("<small>" + a[o].extra + "</small>"), e(h).addClass("ui-menu-item").addClass(l ? "odd" : "even").attr("role", "menuitem").click(function(e) {
							return function() {
								n(a[e])
							}
						}(o)).mouseover(function(e) {
							return function() {
								r(e)
							}
						}(h)), e(f).append(h), l = !l, s += 1, "number" == typeof t.maxResults && s >= t.maxResults) break
				}
				e("li", f).length > 0 && (u = void 0, e(f).show().css("height", "auto"), e(f).height() > t.maxHeight && e(f).css({
					overflow: "auto",
					height: t.maxHeight + "px"
				}))
			}

			function l() {
				var n = function(a) {
					if (this.value.length < t.minCharacters) return e(f).html("").hide(), !1;
					var n, r, l = [],
						s = t.wildCard ? i(this.value, t.wildCard).replace(g, ".*") : i(this.value),
						u = !0;
					for (t.notCharacter && 0 === s.indexOf(t.notCharacter) && (s = s.substr(t.notCharacter.length, s.length), s.length > 0 && (u = !1)), s = s || ".*", s = t.wildCard ? "^" + s : s, n = t.caseSensitive ? new RegExp(s) : new RegExp(s, "i"), r = 0; r < a.length; r += 1) n.test(a[r].text) === u && l.push(a[r]);
					o(l, s)
				};
				if (t.data && t.data.length) n.apply(this, [t.data]);
				else if (t.url && "string" == typeof t.url) {
					var r = this.value;
					e(f).html('<li class="ui-menu-item ajaxSearching"><a class="ui-corner-all_new">' + "Searching..." + '</a></li>').show().css("height", "auto"), a = window.clearTimeout(a), a = window.setTimeout(function() {
						e.getJSON(t.url, {
							search_text: r,
							c: "store",
							m: "search_stores"
						}, function(t) {
							t ? o(t, r) : e(f).html("").hide()
						})
					}, 500)
				}
			}

			function s(t) {
				switch (t.keyCode) {
					case 13:
						return e(u).trigger("click"), !1;
					case 40:
						return u = "undefined" == typeof u ? e("li:first", f).get(0) : e(u).next().get(0), r(u), u && e(f).scrollTop(u.offsetTop), !1;
					case 38:
						return u = "undefined" == typeof u ? e("li:last", f).get(0) : e(u).prev().get(0), r(u), u && e(f).scrollTop(u.offsetTop), !1;
					default:
						l.apply(this, [t])
				}
			}
			var u, c, h, d = e(this),
				g = new RegExp(i(t.wildCard || ""), "g"),
				f = e("<ul />");
			e(f).addClass("jsonSuggest ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all_new").attr("role", "listbox").css({
				left: "auto",
				width: "409px",
				top: "204px",
				left: "609px",
				"z-index": 1001
			}).hide(), d.after(f).keyup(s).keydown(function(t) {
				return 9 === t.keyCode && u ? (e(u).trigger("click"), !0) : void 0
			}).blur(function() {
				var t = e(f).offset();
				t.bottom = t.top + e(f).height(), t.right = t.left + e(f).width(), (h < t.top || h > t.bottom || c < t.left || c > t.right) && e(f).hide()
			}).focus(function() {
				e(f).css({
					left: "auto",
					width: "409px",
					top: "204px",
					left: "609px",
					"z-index": 1001
				}), e("li", f).length > 0 && e(f).show()
			}).attr("autocomplete", "off"), e(window).mousemove(function(e) {
				c = e.pageX, h = e.pageY
			}), t.notCharacter = i(t.notCharacter || ""), t.data && "string" == typeof t.data && (t.data = e.parseJSON(t.data))
		})
	}
}(jQuery);