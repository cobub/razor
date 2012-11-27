/**
 * easyDialog v2.2 Url : http://stylechen.com/easydialog-v2.0.html Author :
 * chenmnkken@gmail.com Date : 2012-04-22
 */
(function(o, v) {
	var g = o.document, q = g.documentElement, J = function() {
		var p = g.body, w = !-[ 1, ], r = w
				&& /msie 6/.test(navigator.userAgent.toLowerCase()), I = 1, y = "cache"
				+ (+new Date + "").slice(-8), u = {}, d = function() {
		};
		d.prototype = {
			getOptions : function(a) {
				var b, c = {}, e = {
					container : null,
					overlay : true,
					drag : true,
					fixed : true,
					follow : null,
					followX : 0,
					followY : 0,
					autoClose : 0,
					lock : false,
					callback : null
				};
				for (b in e)
					c[b] = a[b] !== v ? a[b] : e[b];
				d.data("options", c);
				return c
			},
			setBodyBg : function() {
				if (p.currentStyle.backgroundAttachment !== "fixed") {
					p.style.backgroundImage = "url(about:blank)";
					p.style.backgroundAttachment = "fixed"
				}
			},
			appendIframe : function(a) {
				a.innerHTML = '<iframe style="position:absolute;left:0;top:0;width:100%;height:100%;z-index:-1;border:0 none;filter:alpha(opacity=0)"></iframe>'
			},
			setFollow : function(a, b, c, e) {
				b = typeof b === "string" ? g.getElementById(b) : b;
				a = a.style;
				a.position = "absolute";
				a.left = d.getOffset(b, "left") + c + "px";
				a.top = d.getOffset(b, "top") + e + "px"
			},
			setPosition : function(a, b) {
				var c = a.style;
				c.position = r ? "absolute" : b ? "fixed" : "absolute";
				if (b) {
					if (r)
						c
								.setExpression(
										"top",
										'fuckIE6=document.documentElement.scrollTop+document.documentElement.clientHeight/2+"px"');
					else
						c.top = "50%";
					c.left = "50%"
				} else {
					r && c.removeExpression("top");
					c.top = q.clientHeight / 2 + d.getScroll("top") + "px";
					c.left = q.clientWidth / 2 + d.getScroll("left") + "px"
				}
			},
			createOverlay : function() {
				var a = g.createElement("div"), b = a.style;
				b.cssText = "margin:0;padding:0;border:none;width:100%;height:100%;background:#333;opacity:0.6;filter:alpha(opacity=60);z-index:9999;position:fixed;top:0;left:0;";
				if (r) {
					p.style.height = "100%";
					b.position = "absolute";
					b.setExpression("top",
							'fuckIE6=document.documentElement.scrollTop+"px"')
				}
				a.id = "overlay";
				return a
			},
			createDialogBox : function() {
				var a = g.createElement("div");
				a.style.cssText = "margin:0;padding:0;border:none;z-index:10000;";
				a.id = "easyDialogBox";
				return a
			},
			createDialogWrap : function(a) {
				var b = typeof a.yesFn === "function" ? '<button class="btn_highlight" id="easyDialogYesBtn">'
						+ (typeof a.yesText === "string" ? a.yesText
								: "\u786e\u5b9a") + "</button>"
						: "", c = typeof a.noFn === "function"
						|| a.noFn === true ? '<button class="btn_normal" id="easyDialogNoBtn">'
						+ (typeof a.noText === "string" ? a.noText
								: "\u53d6\u6d88") + "</button>"
						: "";
				a = [
						'<div class="easyDialog_content">',
						a.header ? '<h4 class="easyDialog_title" id="easyDialogTitle"><a href="javascript:void(0)" title="\u5173\u95ed\u7a97\u53e3" class="close_btn" id="closeBtn">&times;</a>'
								+ a.header + "</h4>"
								: "",
						'<div class="easyDialog_text">' + a.content + "</div>",
						b === "" && c === "" ? ""
								: '<div class="easyDialog_footer">' + c + b
										+ "</div>", "</div>" ].join("");
				b = g.getElementById("easyDialogWrapper");
				if (!b) {
					b = g.createElement("div");
					b.id = "easyDialogWrapper";
					b.className = "easyDialog_wrapper"
				}
				b.innerHTML = a.replace(/<[\/]*script[\s\S]*?>/ig, "");
				return b
			}
		};
		d.data = function(a, b, c) {
			if (typeof a === "string") {
				if (b !== v)
					u[a] = b;
				return u[a]
			} else if (typeof a === "object") {
				a = a === o ? 0 : a.nodeType === 9 ? 1 : a[y] ? a[y]
						: a[y] = ++I;
				a = u[a] ? u[a] : u[a] = {};
				if (c !== v)
					a[b] = c;
				return a[b]
			}
		};
		d.removeData = function(a, b) {
			if (typeof a === "string")
				delete u[a];
			else if (typeof a === "object") {
				var c = a === o ? 0 : a.nodeType === 9 ? 1 : a[y];
				if (c !== v) {
					var e = function(m) {
						for ( var n in m)
							return false;
						return true
					}, f = function() {
						delete u[c];
						if (!(c <= 1))
							try {
								delete a[y]
							} catch (m) {
								a.removeAttribute(y)
							}
					};
					if (b) {
						delete u[c][b];
						e(u[c]) && f()
					} else
						f()
				}
			}
		};
		d.event = {
			bind : function(a, b, c) {
				var e = d.data(a, "e" + b) || d.data(a, "e" + b, []);
				e.push(c);
				if (e.length === 1) {
					c = this.eventHandler(a);
					d.data(a, b + "Handler", c);
					if (a.addEventListener)
						a.addEventListener(b, c, false);
					else
						a.attachEvent && a.attachEvent("on" + b, c)
				}
			},
			unbind : function(a, b, c) {
				var e = d.data(a, "e" + b);
				if (e) {
					if (c)
						for ( var f = e.length - 1, m = e[f]; f >= 0; f--)
							m === c && e.splice(f, 1);
					else
						e = v;
					if (!e || !e.length) {
						c = d.data(a, b + "Handler");
						if (a.addEventListener)
							a.removeEventListener(b, c, false);
						else
							a.attachEvent && a.detachEvent("on" + b, c);
						d.removeData(a, b + "Handler");
						d.removeData(a, "e" + b)
					}
				}
			},
			eventHandler : function(a) {
				return function(b) {
					b = d.event.fixEvent(b || o.event);
					for ( var c = d.data(a, "e" + b.type), e = 0, f; f = c[e++];)
						if (f.call(a, b) === false) {
							b.preventDefault();
							b.stopPropagation()
						}
				}
			},
			fixEvent : function(a) {
				if (a.target)
					return a;
				var b = {}, c;
				b.target = a.srcElement || document;
				b.preventDefault = function() {
					a.returnValue = false
				};
				b.stopPropagation = function() {
					a.cancelBubble = true
				};
				for (c in a)
					b[c] = a[c];
				return b
			}
		};
		d.capitalize = function(a) {
			var b = a.charAt(0);
			return b.toUpperCase() + a.replace(b, "")
		};
		d.getScroll = function(a) {
			a = this.capitalize(a);
			return q["scroll" + a] || p["scroll" + a]
		};
		d.getOffset = function(a, b) {
			var c = this.capitalize(b);
			c = q["client" + c] || p["client" + c] || 0;
			var e = this.getScroll(b), f = a.getBoundingClientRect();
			return Math.round(f[b]) + e - c
		};
		d.drag = function(a, b) {
			var c = "getSelection" in o ? function() {
				o.getSelection().removeAllRanges()
			} : function() {
				try {
					g.selection.empty()
				} catch (i) {
				}
			}, e = this, f = e.event, m = false, n = w ? a : g, h = b.style.position === "fixed", j = d
					.data("options").fixed;
			f.bind(a, "mousedown", function(i) {
				m = true;
				var k = e.getScroll("top"), s = e.getScroll("left"), z = h ? 0
						: s, B = h ? 0 : k;
				d.data("dragData", {
					x : i.clientX - e.getOffset(b, "left") + (h ? s : 0),
					y : i.clientY - e.getOffset(b, "top") + (h ? k : 0),
					el : z,
					et : B,
					er : z + q.clientWidth - b.offsetWidth,
					eb : B + q.clientHeight - b.offsetHeight
				});
				if (w) {
					r && j && b.style.removeExpression("top");
					a.setCapture()
				}
				f.bind(n, "mousemove", l);
				f.bind(n, "mouseup", t);
				w && f.bind(a, "losecapture", t);
				i.stopPropagation();
				i.preventDefault()
			});
			var l = function(i) {
				if (m) {
					c();
					var k = d.data("dragData"), s = i.clientX - k.x, z = i.clientY
							- k.y, B = k.et, E = k.er, F = k.eb;
					k = k.el;
					var C = b.style;
					C.marginLeft = C.marginTop = "0px";
					C.left = (s <= k ? k : s >= E ? E : s) + "px";
					C.top = (z <= B ? B : z >= F ? F : z) + "px";
					i.stopPropagation()
				}
			}, t = function(i) {
				m = false;
				w && f.unbind(a, "losecapture", arguments.callee);
				f.unbind(n, "mousemove", l);
				f.unbind(n, "mouseup", arguments.callee);
				if (w) {
					a.releaseCapture();
					if (r && j) {
						var k = parseInt(b.style.top) - e.getScroll("top");
						b.style.setExpression("top",
								"fuckIE6=document.documentElement.scrollTop+"
										+ k + '+"px"')
					}
				}
				i.stopPropagation()
			}
		};
		var x, G = function(a) {
			a.keyCode === 27 && D.close()
		}, D = {
			open : function(a) {
				var b = new d, c = b.getOptions(a || {});
				a = d.event;
				var e = q.clientWidth, f = q.clientHeight, m = this, n, h, j, l;
				if (x) {
					clearTimeout(x);
					x = v
				}
				if (c.overlay) {
					n = g.getElementById("overlay");
					if (!n) {
						n = b.createOverlay();
						p.appendChild(n);
						r && b.appendIframe(n)
					}
					n.style.display = "block"
				}
				r && b.setBodyBg();
				h = g.getElementById("easyDialogBox");
				if (!h) {
					h = b.createDialogBox();
					p.appendChild(h)
				}
				if (c.follow) {
					l = function() {
						b.setFollow(h, c.follow, c.followX, c.followY)
					};
					l();
					a.bind(o, "resize", l);
					d.data("follow", l);
					if (n)
						n.style.display = "none";
					c.fixed = false
				} else
					b.setPosition(h, c.fixed);
				h.style.display = "block";
				j = typeof c.container === "string" ? g
						.getElementById(c.container) : b
						.createDialogWrap(c.container);
				if (l = h.getElementsByTagName("*")[0]) {
					if (l && j !== l) {
						l.style.display = "none";
						p.appendChild(l);
						h.appendChild(j)
					}
				} else
					h.appendChild(j);
				j.style.display = "block";
				var t = j.offsetWidth, i = j.offsetHeight;
				l = t > e;
				var k = i > f;
				j.style.marginTop = j.style.marginRight = j.style.marginBottom = j.style.marginLeft = "0px";
				if (c.follow)
					h.style.marginLeft = h.style.marginTop = "0px";
				else {
					h.style.marginLeft = "-" + (l ? e / 2 : t / 2) + "px";
					h.style.marginTop = "-" + (k ? f / 2 : i / 2) + "px"
				}
				if (r && !c.overlay) {
					h.style.width = t + "px";
					h.style.height = i + "px"
				}
				e = g.getElementById("closeBtn");
				f = g.getElementById("easyDialogTitle");
				j = g.getElementById("easyDialogYesBtn");
				t = g.getElementById("easyDialogNoBtn");
				j && a.bind(j, "click", function(s) {
					c.container.yesFn.call(m, s) !== false && m.close()
				});
				if (t) {
					i = function(s) {
						if (c.container.noFn === true
								|| c.container.noFn.call(m, s) !== false)
							m.close()
					};
					a.bind(t, "click", i);
					e && a.bind(e, "click", i)
				} else
					e && a.bind(e, "click", m.close);
				c.lock || a.bind(g, "keyup", G);
				if (c.autoClose && typeof c.autoClose === "number")
					x = setTimeout(m.close, c.autoClose);
				if (c.drag && f && !l && !k) {
					f.style.cursor = "move";
					d.drag(f, h)
				}
				if (!c.follow && !c.fixed) {
					i = function() {
						b.setPosition(h, false)
					};
					!l && !k && a.bind(o, "resize", i);
					d.data("resize", i)
				}
				d.data("dialogElements", {
					overlay : n,
					dialogBox : h,
					closeBtn : e,
					dialogTitle : f,
					dialogYesBtn : j,
					dialogNoBtn : t
				})
			},
			close : function() {
				var a = d.data("options"), b = d.data("dialogElements"), c = d.event;
				if (x) {
					clearTimeout(x);
					x = v
				}
				if (a.overlay && b.overlay)
					b.overlay.style.display = "none";
				b.dialogBox.style.display = "none";
				r && b.dialogBox.style.removeExpression("top");
				b.closeBtn && c.unbind(b.closeBtn, "click");
				b.dialogTitle && c.unbind(b.dialogTitle, "mousedown");
				b.dialogYesBtn && c.unbind(b.dialogYesBtn, "click");
				b.dialogNoBtn && c.unbind(b.dialogNoBtn, "click");
				if (!a.follow && !a.fixed) {
					c.unbind(o, "resize", d.data("resize"));
					d.removeData("resize")
				}
				if (a.follow) {
					c.unbind(o, "resize", d.data("follow"));
					d.removeData("follow")
				}
				a.lock || c.unbind(g, "keyup", G);
				typeof a.callback === "function" && a.callback.call(D);
				d.removeData("options");
				d.removeData("dialogElements")
			}
		};
		return D
	}, A = function() {
		o.easyDialog = J()
	}, H = function() {
		if (!g.body) {
			try {
				q.doScroll("left")
			} catch (p) {
				setTimeout(H, 1);
				return
			}
			A()
		}
	};
	(function() {
		if (g.body)
			A();
		else if (g.addEventListener) {
			g.addEventListener("DOMContentLoaded", function() {
				g.removeEventListener("DOMContentLoaded", arguments.callee,
						false);
				A()
			}, false);
			o.addEventListener("load", A, false)
		} else if (g.attachEvent) {
			g.attachEvent("onreadystatechange", function() {
				if (g.readyState === "complete") {
					g.detachEvent("onreadystatechange", arguments.callee);
					A()
				}
			});
			o.attachEvent("onload", A);
			var p = false;
			try {
				p = o.frameElement == null
			} catch (w) {
			}
			q.doScroll && p && H()
		}
	})()
})(window, undefined);
