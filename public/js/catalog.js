/*! npm.im/object-fit-images 3.2.4 */
var objectFitImages = (function() {
  "use strict";
  function t(t, e) {
    return (
      "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='" +
      t +
      "' height='" +
      e +
      "'%3E%3C/svg%3E"
    );
  }
  function e(t) {
    if (t.srcset && !p && window.picturefill) {
      var e = window.picturefill._;
      (t[e.ns] && t[e.ns].evaled) || e.fillImg(t, { reselect: !0 }),
        t[e.ns].curSrc ||
          ((t[e.ns].supported = !1), e.fillImg(t, { reselect: !0 })),
        (t.currentSrc = t[e.ns].curSrc || t.src);
    }
  }
  function i(t) {
    for (
      var e, i = getComputedStyle(t).fontFamily, r = {};
      null !== (e = u.exec(i));

    )
      r[e[1]] = e[2];
    return r;
  }
  function r(e, i, r) {
    var n = t(i || 1, r || 0);
    b.call(e, "src") !== n && h.call(e, "src", n);
  }
  function n(t, e) {
    t.naturalWidth ? e(t) : setTimeout(n, 100, t, e);
  }
  function c(t) {
    var c = i(t),
      o = t[l];
    if (((c["object-fit"] = c["object-fit"] || "fill"), !o.img)) {
      if ("fill" === c["object-fit"]) return;
      if (!o.skipTest && f && !c["object-position"]) return;
    }
    if (!o.img) {
      (o.img = new Image(t.width, t.height)),
        (o.img.srcset = b.call(t, "data-ofi-srcset") || t.srcset),
        (o.img.src = b.call(t, "data-ofi-src") || t.src),
        h.call(t, "data-ofi-src", t.src),
        t.srcset && h.call(t, "data-ofi-srcset", t.srcset),
        r(t, t.naturalWidth || t.width, t.naturalHeight || t.height),
        t.srcset && (t.srcset = "");
      try {
        s(t);
      } catch (t) {
        window.console && console.warn("https://bit.ly/ofi-old-browser");
      }
    }
    e(o.img),
      (t.style.backgroundImage =
        'url("' + (o.img.currentSrc || o.img.src).replace(/"/g, '\\"') + '")'),
      (t.style.backgroundPosition = c["object-position"] || "center"),
      (t.style.backgroundRepeat = "no-repeat"),
      (t.style.backgroundOrigin = "content-box"),
      /scale-down/.test(c["object-fit"])
        ? n(o.img, function() {
            o.img.naturalWidth > t.width || o.img.naturalHeight > t.height
              ? (t.style.backgroundSize = "contain")
              : (t.style.backgroundSize = "auto");
          })
        : (t.style.backgroundSize = c["object-fit"]
            .replace("none", "auto")
            .replace("fill", "100% 100%")),
      n(o.img, function(e) {
        r(t, e.naturalWidth, e.naturalHeight);
      });
  }
  function s(t) {
    var e = {
      get: function(e) {
        return t[l].img[e ? e : "src"];
      },
      set: function(e, i) {
        return (
          (t[l].img[i ? i : "src"] = e), h.call(t, "data-ofi-" + i, e), c(t), e
        );
      }
    };
    Object.defineProperty(t, "src", e),
      Object.defineProperty(t, "currentSrc", {
        get: function() {
          return e.get("currentSrc");
        }
      }),
      Object.defineProperty(t, "srcset", {
        get: function() {
          return e.get("srcset");
        },
        set: function(t) {
          return e.set(t, "srcset");
        }
      });
  }
  function o() {
    function t(t, e) {
      return t[l] && t[l].img && ("src" === e || "srcset" === e) ? t[l].img : t;
    }
    d ||
      ((HTMLImageElement.prototype.getAttribute = function(e) {
        return b.call(t(this, e), e);
      }),
      (HTMLImageElement.prototype.setAttribute = function(e, i) {
        return h.call(t(this, e), e, String(i));
      }));
  }
  function a(t, e) {
    var i = !y && !t;
    if (((e = e || {}), (t = t || "img"), (d && !e.skipTest) || !m)) return !1;
    "img" === t
      ? (t = document.getElementsByTagName("img"))
      : "string" == typeof t
      ? (t = document.querySelectorAll(t))
      : "length" in t || (t = [t]);
    for (var r = 0; r < t.length; r++)
      (t[r][l] = t[r][l] || { skipTest: e.skipTest }), c(t[r]);
    i &&
      (document.body.addEventListener(
        "load",
        function(t) {
          "IMG" === t.target.tagName && a(t.target, { skipTest: e.skipTest });
        },
        !0
      ),
      (y = !0),
      (t = "img")),
      e.watchMQ &&
        window.addEventListener(
          "resize",
          a.bind(null, t, { skipTest: e.skipTest })
        );
  }
  var l = "fregante:object-fit-images",
    u = /(object-fit|object-position)\s*:\s*([-.\w\s%]+)/g,
    g =
      "undefined" == typeof Image
        ? { style: { "object-position": 1 } }
        : new Image(),
    f = "object-fit" in g.style,
    d = "object-position" in g.style,
    m = "background-size" in g.style,
    p = "string" == typeof g.currentSrc,
    b = g.getAttribute,
    h = g.setAttribute,
    y = !1;
  return (a.supportsObjectFit = f), (a.supportsObjectPosition = d), o(), a;
})();
(function() {
  var t = jQuery(".gallery__thumbnails");
  t.slick({
    autoplay: !0,
    autoplaySpeed: 3e3,
    dots: !1,
    arrows: !1,
    infinite: !1,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [{ breakpoint: 991, settings: { slidesToShow: 1, dots: !0 } }]
  }),
    t.on("beforeChange", function(t, e, i, s) {
      var n = jQuery(this).siblings(".gallery__main"),
        a = jQuery(this)
          .find(".thumbnail")
          .eq(s)
          .attr("href"),
        o = n.find("img");
      o.fadeOut(200, function() {
        jQuery(this).attr("src", a);
      }).fadeIn(200);
    });
})();

(function ($) {
    jQuery(document).ready(function() {
        jQuery('.rentsyst_reserve').on('click', function() {
            var id = jQuery(this).attr('data-id');
            bookCar(id);
            rentsystOpenBooking();
        });

        jQuery('.rentsyst-booking').on('click', function() {
            var id = jQuery(this).attr('data-id');
            if(!id) {
                id = jQuery(this).parents('.rentsyst-catalog-item').attr('data-id');
            }
            if(!id) {
                return false;
            }
            if(window.rsBokingPageUrl) {
                if(window.rsBokingPageUrl.indexOf('?') === -1) {
                    window.location.href = window.rsBokingPageUrl + '?rentsyst-book-by-vehicle-id=' + id;
                } else {
                    window.location.href = window.rsBokingPageUrl + '&rentsyst-book-by-vehicle-id=' + id;
                }
                return  false;
            }
            bookCar(id);
            rentsystOpenBooking();
            return  false;
        });
    });


    (function(t) {
        "function" == typeof define && define.amd
            ? define(["jquery"], t)
            : t(jQuery);
    })(function(t) {
        function e(t) {
            for (var e = t.css("visibility"); "inherit" === e; )
                (t = t.parent()), (e = t.css("visibility"));
            return "hidden" !== e;
        }
        (t.ui = t.ui || {}), (t.ui.version = "1.12.1");
        var i = 0,
            s = Array.prototype.slice;
        (t.cleanData = (function(e) {
            return function(i) {
                var s, n, a;
                for (a = 0; null != (n = i[a]); a++)
                    try {
                        (s = t._data(n, "events")),
                        s && s.remove && t(n).triggerHandler("remove");
                    } catch (o) {}
                e(i);
            };
        })(t.cleanData)),
            (t.widget = function(e, i, s) {
                var n,
                    a,
                    o,
                    r = {},
                    l = e.split(".")[0];
                e = e.split(".")[1];
                var c = l + "-" + e;
                return (
                    s || ((s = i), (i = t.Widget)),
                    t.isArray(s) && (s = t.extend.apply(null, [{}].concat(s))),
                        (t.expr[":"][c.toLowerCase()] = function(e) {
                            return !!t.data(e, c);
                        }),
                        (t[l] = t[l] || {}),
                        (n = t[l][e]),
                        (a = t[l][e] = function(t, e) {
                            return this._createWidget
                                ? void (arguments.length && this._createWidget(t, e))
                                : new a(t, e);
                        }),
                        t.extend(a, n, {
                            version: s.version,
                            _proto: t.extend({}, s),
                            _childConstructors: []
                        }),
                        (o = new i()),
                        (o.options = t.widget.extend({}, o.options)),
                        t.each(s, function(e, s) {
                            return t.isFunction(s)
                                ? void (r[e] = (function() {
                                    function t() {
                                        return i.prototype[e].apply(this, arguments);
                                    }
                                    function n(t) {
                                        return i.prototype[e].apply(this, t);
                                    }
                                    return function() {
                                        var e,
                                            i = this._super,
                                            a = this._superApply;
                                        return (
                                            (this._super = t),
                                                (this._superApply = n),
                                                (e = s.apply(this, arguments)),
                                                (this._super = i),
                                                (this._superApply = a),
                                                e
                                        );
                                    };
                                })())
                                : void (r[e] = s);
                        }),
                        (a.prototype = t.widget.extend(
                            o,
                            { widgetEventPrefix: n ? o.widgetEventPrefix || e : e },
                            r,
                            { constructor: a, namespace: l, widgetName: e, widgetFullName: c }
                        )),
                        n
                            ? (t.each(n._childConstructors, function(e, i) {
                                var s = i.prototype;
                                t.widget(s.namespace + "." + s.widgetName, a, i._proto);
                            }),
                                delete n._childConstructors)
                            : i._childConstructors.push(a),
                        t.widget.bridge(e, a),
                        a
                );
            }),
            (t.widget.extend = function(e) {
                for (
                    var i, n, a = s.call(arguments, 1), o = 0, r = a.length;
                    r > o;
                    o++
                )
                    for (i in a[o])
                        (n = a[o][i]),
                        a[o].hasOwnProperty(i) &&
                        void 0 !== n &&
                        (e[i] = t.isPlainObject(n)
                            ? t.isPlainObject(e[i])
                                ? t.widget.extend({}, e[i], n)
                                : t.widget.extend({}, n)
                            : n);
                return e;
            }),
            (t.widget.bridge = function(e, i) {
                var n = i.prototype.widgetFullName || e;
                t.fn[e] = function(a) {
                    var o = "string" == typeof a,
                        r = s.call(arguments, 1),
                        l = this;
                    return (
                        o
                            ? this.length || "instance" !== a
                            ? this.each(function() {
                                var i,
                                    s = t.data(this, n);
                                return "instance" === a
                                    ? ((l = s), !1)
                                    : s
                                        ? t.isFunction(s[a]) && "_" !== a.charAt(0)
                                            ? ((i = s[a].apply(s, r)),
                                                i !== s && void 0 !== i
                                                    ? ((l = i && i.jquery ? l.pushStack(i.get()) : i),
                                                        !1)
                                                    : void 0)
                                            : t.error(
                                                "no such method '" +
                                                a +
                                                "' for " +
                                                e +
                                                " widget instance"
                                            )
                                        : t.error(
                                            "cannot call methods on " +
                                            e +
                                            " prior to initialization; attempted to call method '" +
                                            a +
                                            "'"
                                        );
                            })
                            : (l = void 0)
                            : (r.length && (a = t.widget.extend.apply(null, [a].concat(r))),
                                this.each(function() {
                                    var e = t.data(this, n);
                                    e
                                        ? (e.option(a || {}), e._init && e._init())
                                        : t.data(this, n, new i(a, this));
                                })),
                            l
                    );
                };
            }),
            (t.Widget = function() {}),
            (t.Widget._childConstructors = []),
            (t.Widget.prototype = {
                widgetName: "widget",
                widgetEventPrefix: "",
                defaultElement: "<div>",
                options: { classes: {}, disabled: !1, create: null },
                _createWidget: function(e, s) {
                    (s = t(s || this.defaultElement || this)[0]),
                        (this.element = t(s)),
                        (this.uuid = i++),
                        (this.eventNamespace = "." + this.widgetName + this.uuid),
                        (this.bindings = t()),
                        (this.hoverable = t()),
                        (this.focusable = t()),
                        (this.classesElementLookup = {}),
                    s !== this &&
                    (t.data(s, this.widgetFullName, this),
                        this._on(!0, this.element, {
                            remove: function(t) {
                                t.target === s && this.destroy();
                            }
                        }),
                        (this.document = t(s.style ? s.ownerDocument : s.document || s)),
                        (this.window = t(
                            this.document[0].defaultView || this.document[0].parentWindow
                        ))),
                        (this.options = t.widget.extend(
                            {},
                            this.options,
                            this._getCreateOptions(),
                            e
                        )),
                        this._create(),
                    this.options.disabled &&
                    this._setOptionDisabled(this.options.disabled),
                        this._trigger("create", null, this._getCreateEventData()),
                        this._init();
                },
                _getCreateOptions: function() {
                    return {};
                },
                _getCreateEventData: t.noop,
                _create: t.noop,
                _init: t.noop,
                destroy: function() {
                    var e = this;
                    this._destroy(),
                        t.each(this.classesElementLookup, function(t, i) {
                            e._removeClass(i, t);
                        }),
                        this.element
                            .off(this.eventNamespace)
                            .removeData(this.widgetFullName),
                        this.widget()
                            .off(this.eventNamespace)
                            .removeAttr("aria-disabled"),
                        this.bindings.off(this.eventNamespace);
                },
                _destroy: t.noop,
                widget: function() {
                    return this.element;
                },
                option: function(e, i) {
                    var s,
                        n,
                        a,
                        o = e;
                    if (0 === arguments.length) return t.widget.extend({}, this.options);
                    if ("string" == typeof e)
                        if (((o = {}), (s = e.split(".")), (e = s.shift()), s.length)) {
                            for (
                                n = o[e] = t.widget.extend({}, this.options[e]), a = 0;
                                s.length - 1 > a;
                                a++
                            )
                                (n[s[a]] = n[s[a]] || {}), (n = n[s[a]]);
                            if (((e = s.pop()), 1 === arguments.length))
                                return void 0 === n[e] ? null : n[e];
                            n[e] = i;
                        } else {
                            if (1 === arguments.length)
                                return void 0 === this.options[e] ? null : this.options[e];
                            o[e] = i;
                        }
                    return this._setOptions(o), this;
                },
                _setOptions: function(t) {
                    var e;
                    for (e in t) this._setOption(e, t[e]);
                    return this;
                },
                _setOption: function(t, e) {
                    return (
                        "classes" === t && this._setOptionClasses(e),
                            (this.options[t] = e),
                        "disabled" === t && this._setOptionDisabled(e),
                            this
                    );
                },
                _setOptionClasses: function(e) {
                    var i, s, n;
                    for (i in e)
                        (n = this.classesElementLookup[i]),
                        e[i] !== this.options.classes[i] &&
                        n &&
                        n.length &&
                        ((s = t(n.get())),
                            this._removeClass(n, i),
                            s.addClass(
                                this._classes({ element: s, keys: i, classes: e, add: !0 })
                            ));
                },
                _setOptionDisabled: function(t) {
                    this._toggleClass(
                        this.widget(),
                        this.widgetFullName + "-disabled",
                        null,
                        !!t
                    ),
                    t &&
                    (this._removeClass(this.hoverable, null, "ui-state-hover"),
                        this._removeClass(this.focusable, null, "ui-state-focus"));
                },
                enable: function() {
                    return this._setOptions({ disabled: !1 });
                },
                disable: function() {
                    return this._setOptions({ disabled: !0 });
                },
                _classes: function(e) {
                    function i(i, a) {
                        var o, r;
                        for (r = 0; i.length > r; r++)
                            (o = n.classesElementLookup[i[r]] || t()),
                                (o = t(
                                    e.add
                                        ? t.unique(o.get().concat(e.element.get()))
                                        : o.not(e.element).get()
                                )),
                                (n.classesElementLookup[i[r]] = o),
                                s.push(i[r]),
                            a && e.classes[i[r]] && s.push(e.classes[i[r]]);
                    }
                    var s = [],
                        n = this;
                    return (
                        (e = t.extend(
                            { element: this.element, classes: this.options.classes || {} },
                            e
                        )),
                            this._on(e.element, { remove: "_untrackClassesElement" }),
                        e.keys && i(e.keys.match(/\S+/g) || [], !0),
                        e.extra && i(e.extra.match(/\S+/g) || []),
                            s.join(" ")
                    );
                },
                _untrackClassesElement: function(e) {
                    var i = this;
                    t.each(i.classesElementLookup, function(s, n) {
                        -1 !== t.inArray(e.target, n) &&
                        (i.classesElementLookup[s] = t(n.not(e.target).get()));
                    });
                },
                _removeClass: function(t, e, i) {
                    return this._toggleClass(t, e, i, !1);
                },
                _addClass: function(t, e, i) {
                    return this._toggleClass(t, e, i, !0);
                },
                _toggleClass: function(t, e, i, s) {
                    s = "boolean" == typeof s ? s : i;
                    var n = "string" == typeof t || null === t,
                        a = {
                            extra: n ? e : i,
                            keys: n ? t : e,
                            element: n ? this.element : t,
                            add: s
                        };
                    return a.element.toggleClass(this._classes(a), s), this;
                },
                _on: function(e, i, s) {
                    var n,
                        a = this;
                    "boolean" != typeof e && ((s = i), (i = e), (e = !1)),
                        s
                            ? ((i = n = t(i)), (this.bindings = this.bindings.add(i)))
                            : ((s = i), (i = this.element), (n = this.widget())),
                        t.each(s, function(s, o) {
                            function r() {
                                return e ||
                                (a.options.disabled !== !0 &&
                                    !t(this).hasClass("ui-state-disabled"))
                                    ? ("string" == typeof o ? a[o] : o).apply(a, arguments)
                                    : void 0;
                            }
                            "string" != typeof o &&
                            (r.guid = o.guid = o.guid || r.guid || t.guid++);
                            var l = s.match(/^([\w:-]*)\s*(.*)$/),
                                c = l[1] + a.eventNamespace,
                                d = l[2];
                            d ? n.on(c, d, r) : i.on(c, r);
                        });
                },
                _off: function(e, i) {
                    (i =
                        (i || "").split(" ").join(this.eventNamespace + " ") +
                        this.eventNamespace),
                        e.off(i).off(i),
                        (this.bindings = t(this.bindings.not(e).get())),
                        (this.focusable = t(this.focusable.not(e).get())),
                        (this.hoverable = t(this.hoverable.not(e).get()));
                },
                _delay: function(t, e) {
                    function i() {
                        return ("string" == typeof t ? s[t] : t).apply(s, arguments);
                    }
                    var s = this;
                    return setTimeout(i, e || 0);
                },
                _hoverable: function(e) {
                    (this.hoverable = this.hoverable.add(e)),
                        this._on(e, {
                            mouseenter: function(e) {
                                this._addClass(t(e.currentTarget), null, "ui-state-hover");
                            },
                            mouseleave: function(e) {
                                this._removeClass(t(e.currentTarget), null, "ui-state-hover");
                            }
                        });
                },
                _focusable: function(e) {
                    (this.focusable = this.focusable.add(e)),
                        this._on(e, {
                            focusin: function(e) {
                                this._addClass(t(e.currentTarget), null, "ui-state-focus");
                            },
                            focusout: function(e) {
                                this._removeClass(t(e.currentTarget), null, "ui-state-focus");
                            }
                        });
                },
                _trigger: function(e, i, s) {
                    var n,
                        a,
                        o = this.options[e];
                    if (
                        ((s = s || {}),
                            (i = t.Event(i)),
                            (i.type = (e === this.widgetEventPrefix
                                    ? e
                                    : this.widgetEventPrefix + e
                            ).toLowerCase()),
                            (i.target = this.element[0]),
                            (a = i.originalEvent))
                    )
                        for (n in a) n in i || (i[n] = a[n]);
                    return (
                        this.element.trigger(i, s),
                            !(
                                (t.isFunction(o) &&
                                    o.apply(this.element[0], [i].concat(s)) === !1) ||
                                i.isDefaultPrevented()
                            )
                    );
                }
            }),
            t.each({ show: "fadeIn", hide: "fadeOut" }, function(e, i) {
                t.Widget.prototype["_" + e] = function(s, n, a) {
                    "string" == typeof n && (n = { effect: n });
                    var o,
                        r = n ? (n === !0 || "number" == typeof n ? i : n.effect || i) : e;
                    (n = n || {}),
                    "number" == typeof n && (n = { duration: n }),
                        (o = !t.isEmptyObject(n)),
                        (n.complete = a),
                    n.delay && s.delay(n.delay),
                        o && t.effects && t.effects.effect[r]
                            ? s[e](n)
                            : r !== e && s[r]
                            ? s[r](n.duration, n.easing, a)
                            : s.queue(function(i) {
                                t(this)[e](), a && a.call(s[0]), i();
                            });
                };
            }),
            t.widget,
            (function() {
                function e(t, e, i) {
                    return [
                        parseFloat(t[0]) * (u.test(t[0]) ? e / 100 : 1),
                        parseFloat(t[1]) * (u.test(t[1]) ? i / 100 : 1)
                    ];
                }
                function i(e, i) {
                    return parseInt(t.css(e, i), 10) || 0;
                }
                function s(e) {
                    var i = e[0];
                    return 9 === i.nodeType
                        ? {
                            width: e.width(),
                            height: e.height(),
                            offset: { top: 0, left: 0 }
                        }
                        : t.isWindow(i)
                            ? {
                                width: e.width(),
                                height: e.height(),
                                offset: { top: e.scrollTop(), left: e.scrollLeft() }
                            }
                            : i.preventDefault
                                ? { width: 0, height: 0, offset: { top: i.pageY, left: i.pageX } }
                                : {
                                    width: e.outerWidth(),
                                    height: e.outerHeight(),
                                    offset: e.offset()
                                };
                }
                var n,
                    a = Math.max,
                    o = Math.abs,
                    r = /left|center|right/,
                    l = /top|center|bottom/,
                    c = /[\+\-]\d+(\.[\d]+)?%?/,
                    d = /^\w+/,
                    u = /%$/,
                    h = t.fn.position;
                (t.position = {
                    scrollbarWidth: function() {
                        if (void 0 !== n) return n;
                        var e,
                            i,
                            s = t(
                                "<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"
                            ),
                            a = s.children()[0];
                        return (
                            t("body").append(s),
                                (e = a.offsetWidth),
                                s.css("overflow", "scroll"),
                                (i = a.offsetWidth),
                            e === i && (i = s[0].clientWidth),
                                s.remove(),
                                (n = e - i)
                        );
                    },
                    getScrollInfo: function(e) {
                        var i =
                                e.isWindow || e.isDocument ? "" : e.element.css("overflow-x"),
                            s = e.isWindow || e.isDocument ? "" : e.element.css("overflow-y"),
                            n =
                                "scroll" === i ||
                                ("auto" === i && e.width < e.element[0].scrollWidth),
                            a =
                                "scroll" === s ||
                                ("auto" === s && e.height < e.element[0].scrollHeight);
                        return {
                            width: a ? t.position.scrollbarWidth() : 0,
                            height: n ? t.position.scrollbarWidth() : 0
                        };
                    },
                    getWithinInfo: function(e) {
                        var i = t(e || window),
                            s = t.isWindow(i[0]),
                            n = !!i[0] && 9 === i[0].nodeType,
                            a = !s && !n;
                        return {
                            element: i,
                            isWindow: s,
                            isDocument: n,
                            offset: a ? t(e).offset() : { left: 0, top: 0 },
                            scrollLeft: i.scrollLeft(),
                            scrollTop: i.scrollTop(),
                            width: i.outerWidth(),
                            height: i.outerHeight()
                        };
                    }
                }),
                    (t.fn.position = function(n) {
                        if (!n || !n.of) return h.apply(this, arguments);
                        n = t.extend({}, n);
                        var u,
                            p,
                            f,
                            v,
                            g,
                            m,
                            y = t(n.of),
                            _ = t.position.getWithinInfo(n.within),
                            k = t.position.getScrollInfo(_),
                            w = (n.collision || "flip").split(" "),
                            b = {};
                        return (
                            (m = s(y)),
                            y[0].preventDefault && (n.at = "left top"),
                                (p = m.width),
                                (f = m.height),
                                (v = m.offset),
                                (g = t.extend({}, v)),
                                t.each(["my", "at"], function() {
                                    var t,
                                        e,
                                        i = (n[this] || "").split(" ");
                                    1 === i.length &&
                                    (i = r.test(i[0])
                                        ? i.concat(["center"])
                                        : l.test(i[0])
                                            ? ["center"].concat(i)
                                            : ["center", "center"]),
                                        (i[0] = r.test(i[0]) ? i[0] : "center"),
                                        (i[1] = l.test(i[1]) ? i[1] : "center"),
                                        (t = c.exec(i[0])),
                                        (e = c.exec(i[1])),
                                        (b[this] = [t ? t[0] : 0, e ? e[0] : 0]),
                                        (n[this] = [d.exec(i[0])[0], d.exec(i[1])[0]]);
                                }),
                            1 === w.length && (w[1] = w[0]),
                                "right" === n.at[0]
                                    ? (g.left += p)
                                    : "center" === n.at[0] && (g.left += p / 2),
                                "bottom" === n.at[1]
                                    ? (g.top += f)
                                    : "center" === n.at[1] && (g.top += f / 2),
                                (u = e(b.at, p, f)),
                                (g.left += u[0]),
                                (g.top += u[1]),
                                this.each(function() {
                                    var s,
                                        r,
                                        l = t(this),
                                        c = l.outerWidth(),
                                        d = l.outerHeight(),
                                        h = i(this, "marginLeft"),
                                        m = i(this, "marginTop"),
                                        C = c + h + i(this, "marginRight") + k.width,
                                        $ = d + m + i(this, "marginBottom") + k.height,
                                        D = t.extend({}, g),
                                        S = e(b.my, l.outerWidth(), l.outerHeight());
                                    "right" === n.my[0]
                                        ? (D.left -= c)
                                        : "center" === n.my[0] && (D.left -= c / 2),
                                        "bottom" === n.my[1]
                                            ? (D.top -= d)
                                            : "center" === n.my[1] && (D.top -= d / 2),
                                        (D.left += S[0]),
                                        (D.top += S[1]),
                                        (s = { marginLeft: h, marginTop: m }),
                                        t.each(["left", "top"], function(e, i) {
                                            t.ui.position[w[e]] &&
                                            t.ui.position[w[e]][i](D, {
                                                targetWidth: p,
                                                targetHeight: f,
                                                elemWidth: c,
                                                elemHeight: d,
                                                collisionPosition: s,
                                                collisionWidth: C,
                                                collisionHeight: $,
                                                offset: [u[0] + S[0], u[1] + S[1]],
                                                my: n.my,
                                                at: n.at,
                                                within: _,
                                                elem: l
                                            });
                                        }),
                                    n.using &&
                                    (r = function x(t) {
                                        var e = v.left - D.left,
                                            i = e + p - c,
                                            s = v.top - D.top,
                                            x = s + f - d,
                                            r = {
                                                target: {
                                                    element: y,
                                                    left: v.left,
                                                    top: v.top,
                                                    width: p,
                                                    height: f
                                                },
                                                element: {
                                                    element: l,
                                                    left: D.left,
                                                    top: D.top,
                                                    width: c,
                                                    height: d
                                                },
                                                horizontal:
                                                    0 > i ? "left" : e > 0 ? "right" : "center",
                                                vertical: 0 > x ? "top" : s > 0 ? "bottom" : "middle"
                                            };
                                        c > p && p > o(e + i) && (r.horizontal = "center"),
                                        d > f && f > o(s + x) && (r.vertical = "middle"),
                                            (r.important =
                                                a(o(e), o(i)) > a(o(s), o(x))
                                                    ? "horizontal"
                                                    : "vertical"),
                                            n.using.call(this, t, r);
                                    }),
                                        l.offset(t.extend(D, { using: r }));
                                })
                        );
                    }),
                    (t.ui.position = {
                        fit: {
                            left: function(t, e) {
                                var i,
                                    s = e.within,
                                    n = s.isWindow ? s.scrollLeft : s.offset.left,
                                    o = s.width,
                                    r = t.left - e.collisionPosition.marginLeft,
                                    l = n - r,
                                    c = r + e.collisionWidth - o - n;
                                e.collisionWidth > o
                                    ? l > 0 && 0 >= c
                                    ? ((i = t.left + l + e.collisionWidth - o - n),
                                        (t.left += l - i))
                                    : (t.left =
                                        c > 0 && 0 >= l
                                            ? n
                                            : l > c
                                            ? n + o - e.collisionWidth
                                            : n)
                                    : l > 0
                                    ? (t.left += l)
                                    : c > 0
                                        ? (t.left -= c)
                                        : (t.left = a(t.left - r, t.left));
                            },
                            top: function(t, e) {
                                var i,
                                    s = e.within,
                                    n = s.isWindow ? s.scrollTop : s.offset.top,
                                    o = e.within.height,
                                    r = t.top - e.collisionPosition.marginTop,
                                    l = n - r,
                                    c = r + e.collisionHeight - o - n;
                                e.collisionHeight > o
                                    ? l > 0 && 0 >= c
                                    ? ((i = t.top + l + e.collisionHeight - o - n),
                                        (t.top += l - i))
                                    : (t.top =
                                        c > 0 && 0 >= l
                                            ? n
                                            : l > c
                                            ? n + o - e.collisionHeight
                                            : n)
                                    : l > 0
                                    ? (t.top += l)
                                    : c > 0
                                        ? (t.top -= c)
                                        : (t.top = a(t.top - r, t.top));
                            }
                        },
                        flip: {
                            left: function(t, e) {
                                var i,
                                    s,
                                    n = e.within,
                                    a = n.offset.left + n.scrollLeft,
                                    r = n.width,
                                    l = n.isWindow ? n.scrollLeft : n.offset.left,
                                    c = t.left - e.collisionPosition.marginLeft,
                                    d = c - l,
                                    u = c + e.collisionWidth - r - l,
                                    h =
                                        "left" === e.my[0]
                                            ? -e.elemWidth
                                            : "right" === e.my[0]
                                            ? e.elemWidth
                                            : 0,
                                    p =
                                        "left" === e.at[0]
                                            ? e.targetWidth
                                            : "right" === e.at[0]
                                            ? -e.targetWidth
                                            : 0,
                                    f = -2 * e.offset[0];
                                0 > d
                                    ? ((i = t.left + h + p + f + e.collisionWidth - r - a),
                                    (0 > i || o(d) > i) && (t.left += h + p + f))
                                    : u > 0 &&
                                    ((s =
                                        t.left - e.collisionPosition.marginLeft + h + p + f - l),
                                    (s > 0 || u > o(s)) && (t.left += h + p + f));
                            },
                            top: function(t, e) {
                                var i,
                                    s,
                                    n = e.within,
                                    a = n.offset.top + n.scrollTop,
                                    r = n.height,
                                    l = n.isWindow ? n.scrollTop : n.offset.top,
                                    c = t.top - e.collisionPosition.marginTop,
                                    d = c - l,
                                    u = c + e.collisionHeight - r - l,
                                    h = "top" === e.my[1],
                                    p = h
                                        ? -e.elemHeight
                                        : "bottom" === e.my[1]
                                            ? e.elemHeight
                                            : 0,
                                    f =
                                        "top" === e.at[1]
                                            ? e.targetHeight
                                            : "bottom" === e.at[1]
                                            ? -e.targetHeight
                                            : 0,
                                    v = -2 * e.offset[1];
                                0 > d
                                    ? ((s = t.top + p + f + v + e.collisionHeight - r - a),
                                    (0 > s || o(d) > s) && (t.top += p + f + v))
                                    : u > 0 &&
                                    ((i =
                                        t.top - e.collisionPosition.marginTop + p + f + v - l),
                                    (i > 0 || u > o(i)) && (t.top += p + f + v));
                            }
                        },
                        flipfit: {
                            left: function() {
                                t.ui.position.flip.left.apply(this, arguments),
                                    t.ui.position.fit.left.apply(this, arguments);
                            },
                            top: function() {
                                t.ui.position.flip.top.apply(this, arguments),
                                    t.ui.position.fit.top.apply(this, arguments);
                            }
                        }
                    });
            })(),
            t.ui.position,
            t.extend(t.expr[":"], {
                data: t.expr.createPseudo
                    ? t.expr.createPseudo(function(e) {
                        return function(i) {
                            return !!t.data(i, e);
                        };
                    })
                    : function(e, i, s) {
                        return !!t.data(e, s[3]);
                    }
            }),
            t.fn.extend({
                disableSelection: (function() {
                    var t =
                        "onselectstart" in document.createElement("div")
                            ? "selectstart"
                            : "mousedown";
                    return function() {
                        return this.on(t + ".ui-disableSelection", function(t) {
                            t.preventDefault();
                        });
                    };
                })(),
                enableSelection: function() {
                    return this.off(".ui-disableSelection");
                }
            }),
            (t.ui.focusable = function(i, s) {
                var n,
                    a,
                    o,
                    r,
                    l,
                    c = i.nodeName.toLowerCase();
                return "area" === c
                    ? ((n = i.parentNode),
                        (a = n.name),
                    !(!i.href || !a || "map" !== n.nodeName.toLowerCase()) &&
                    ((o = t("img[usemap='#" + a + "']")),
                    o.length > 0 && o.is(":visible")))
                    : (/^(input|select|textarea|button|object)$/.test(c)
                        ? ((r = !i.disabled),
                        r &&
                        ((l = t(i).closest("fieldset")[0]), l && (r = !l.disabled)))
                        : (r = "a" === c ? i.href || s : s),
                    r && t(i).is(":visible") && e(t(i)));
            }),
            t.extend(t.expr[":"], {
                focusable: function(e) {
                    return t.ui.focusable(e, null != t.attr(e, "tabindex"));
                }
            }),
            t.ui.focusable,
            (t.fn.form = function() {
                return "string" == typeof this[0].form
                    ? this.closest("form")
                    : t(this[0].form);
            }),
            (t.ui.formResetMixin = {
                _formResetHandler: function() {
                    var e = t(this);
                    setTimeout(function() {
                        var i = e.data("ui-form-reset-instances");
                        t.each(i, function() {
                            this.refresh();
                        });
                    });
                },
                _bindFormResetHandler: function() {
                    if (((this.form = this.element.form()), this.form.length)) {
                        var t = this.form.data("ui-form-reset-instances") || [];
                        t.length ||
                        this.form.on("reset.ui-form-reset", this._formResetHandler),
                            t.push(this),
                            this.form.data("ui-form-reset-instances", t);
                    }
                },
                _unbindFormResetHandler: function() {
                    if (this.form.length) {
                        var e = this.form.data("ui-form-reset-instances");
                        e.splice(t.inArray(this, e), 1),
                            e.length
                                ? this.form.data("ui-form-reset-instances", e)
                                : this.form
                                    .removeData("ui-form-reset-instances")
                                    .off("reset.ui-form-reset");
                    }
                }
            }),
        "1.7" === t.fn.jquery.substring(0, 3) &&
        (t.each(["Width", "Height"], function(e, i) {
            function s(e, i, s, a) {
                return (
                    t.each(n, function() {
                        (i -= parseFloat(t.css(e, "padding" + this)) || 0),
                        s &&
                        (i -= parseFloat(t.css(e, "border" + this + "Width")) || 0),
                        a && (i -= parseFloat(t.css(e, "margin" + this)) || 0);
                    }),
                        i
                );
            }
            var n = "Width" === i ? ["Left", "Right"] : ["Top", "Bottom"],
                a = i.toLowerCase(),
                o = {
                    innerWidth: t.fn.innerWidth,
                    innerHeight: t.fn.innerHeight,
                    outerWidth: t.fn.outerWidth,
                    outerHeight: t.fn.outerHeight
                };
            (t.fn["inner" + i] = function(e) {
                return void 0 === e
                    ? o["inner" + i].call(this)
                    : this.each(function() {
                        t(this).css(a, s(this, e) + "px");
                    });
            }),
                (t.fn["outer" + i] = function(e, n) {
                    return "number" != typeof e
                        ? o["outer" + i].call(this, e)
                        : this.each(function() {
                            t(this).css(a, s(this, e, !0, n) + "px");
                        });
                });
        }),
            (t.fn.addBack = function(t) {
                return this.add(
                    null == t ? this.prevObject : this.prevObject.filter(t)
                );
            })),
            (t.ui.keyCode = {
                BACKSPACE: 8,
                COMMA: 188,
                DELETE: 46,
                DOWN: 40,
                END: 35,
                ENTER: 13,
                ESCAPE: 27,
                HOME: 36,
                LEFT: 37,
                PAGE_DOWN: 34,
                PAGE_UP: 33,
                PERIOD: 190,
                RIGHT: 39,
                SPACE: 32,
                TAB: 9,
                UP: 38
            }),
            (t.ui.escapeSelector = (function() {
                var t = /([!"#$%&'()*+,.\/:;<=>?@[\]^`{|}~])/g;
                return function(e) {
                    return e.replace(t, "\\$1");
                };
            })()),
            (t.fn.labels = function() {
                var e, i, s, n, a;
                return this[0].labels && this[0].labels.length
                    ? this.pushStack(this[0].labels)
                    : ((n = this.eq(0).parents("label")),
                        (s = this.attr("id")),
                    s &&
                    ((e = this.eq(0)
                        .parents()
                        .last()),
                        (a = e.add(e.length ? e.siblings() : this.siblings())),
                        (i = "label[for='" + t.ui.escapeSelector(s) + "']"),
                        (n = n.add(a.find(i).addBack(i)))),
                        this.pushStack(n));
            }),
            (t.fn.scrollParent = function(e) {
                var i = this.css("position"),
                    s = "absolute" === i,
                    n = e ? /(auto|scroll|hidden)/ : /(auto|scroll)/,
                    a = this.parents()
                        .filter(function() {
                            var e = t(this);
                            return (
                                (!s || "static" !== e.css("position")) &&
                                n.test(
                                    e.css("overflow") + e.css("overflow-y") + e.css("overflow-x")
                                )
                            );
                        })
                        .eq(0);
                return "fixed" !== i && a.length
                    ? a
                    : t(this[0].ownerDocument || document);
            }),
            t.extend(t.expr[":"], {
                tabbable: function(e) {
                    var i = t.attr(e, "tabindex"),
                        s = null != i;
                    return (!s || i >= 0) && t.ui.focusable(e, s);
                }
            }),
            t.fn.extend({
                uniqueId: (function() {
                    var t = 0;
                    return function() {
                        return this.each(function() {
                            this.id || (this.id = "ui-id-" + ++t);
                        });
                    };
                })(),
                removeUniqueId: function() {
                    return this.each(function() {
                        /^ui-id-\d+$/.test(this.id) && t(this).removeAttr("id");
                    });
                }
            }),
            (t.ui.ie = !!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()));
        var n = !1;
        t(document).on("mouseup", function() {
            n = !1;
        }),
            t.widget("ui.mouse", {
                version: "1.12.1",
                options: {
                    cancel: "input, textarea, button, select, option",
                    distance: 1,
                    delay: 0
                },
                _mouseInit: function() {
                    var e = this;
                    this.element
                        .on("mousedown." + this.widgetName, function(t) {
                            return e._mouseDown(t);
                        })
                        .on("click." + this.widgetName, function(i) {
                            return !0 ===
                            t.data(i.target, e.widgetName + ".preventClickEvent")
                                ? (t.removeData(i.target, e.widgetName + ".preventClickEvent"),
                                    i.stopImmediatePropagation(),
                                    !1)
                                : void 0;
                        }),
                        (this.started = !1);
                },
                _mouseDestroy: function() {
                    this.element.off("." + this.widgetName),
                    this._mouseMoveDelegate &&
                    this.document
                        .off("mousemove." + this.widgetName, this._mouseMoveDelegate)
                        .off("mouseup." + this.widgetName, this._mouseUpDelegate);
                },
                _mouseDown: function(e) {
                    if (!n) {
                        (this._mouseMoved = !1),
                        this._mouseStarted && this._mouseUp(e),
                            (this._mouseDownEvent = e);
                        var i = this,
                            s = 1 === e.which,
                            a =
                                !(
                                    "string" != typeof this.options.cancel || !e.target.nodeName
                                ) && t(e.target).closest(this.options.cancel).length;
                        return (
                            !(s && !a && this._mouseCapture(e)) ||
                            ((this.mouseDelayMet = !this.options.delay),
                            this.mouseDelayMet ||
                            (this._mouseDelayTimer = setTimeout(function() {
                                i.mouseDelayMet = !0;
                            }, this.options.delay)),
                                this._mouseDistanceMet(e) &&
                                this._mouseDelayMet(e) &&
                                ((this._mouseStarted = this._mouseStart(e) !== !1),
                                    !this._mouseStarted)
                                    ? (e.preventDefault(), !0)
                                    : (!0 ===
                                    t.data(e.target, this.widgetName + ".preventClickEvent") &&
                                    t.removeData(
                                        e.target,
                                        this.widgetName + ".preventClickEvent"
                                    ),
                                        (this._mouseMoveDelegate = function(t) {
                                            return i._mouseMove(t);
                                        }),
                                        (this._mouseUpDelegate = function(t) {
                                            return i._mouseUp(t);
                                        }),
                                        this.document
                                            .on("mousemove." + this.widgetName, this._mouseMoveDelegate)
                                            .on("mouseup." + this.widgetName, this._mouseUpDelegate),
                                        e.preventDefault(),
                                        (n = !0),
                                        !0))
                        );
                    }
                },
                _mouseMove: function(e) {
                    if (this._mouseMoved) {
                        if (
                            t.ui.ie &&
                            (!document.documentMode || 9 > document.documentMode) &&
                            !e.button
                        )
                            return this._mouseUp(e);
                        if (!e.which)
                            if (
                                e.originalEvent.altKey ||
                                e.originalEvent.ctrlKey ||
                                e.originalEvent.metaKey ||
                                e.originalEvent.shiftKey
                            )
                                this.ignoreMissingWhich = !0;
                            else if (!this.ignoreMissingWhich) return this._mouseUp(e);
                    }
                    return (
                        (e.which || e.button) && (this._mouseMoved = !0),
                            this._mouseStarted
                                ? (this._mouseDrag(e), e.preventDefault())
                                : (this._mouseDistanceMet(e) &&
                                this._mouseDelayMet(e) &&
                                ((this._mouseStarted =
                                    this._mouseStart(this._mouseDownEvent, e) !== !1),
                                    this._mouseStarted ? this._mouseDrag(e) : this._mouseUp(e)),
                                    !this._mouseStarted)
                    );
                },
                _mouseUp: function(e) {
                    this.document
                        .off("mousemove." + this.widgetName, this._mouseMoveDelegate)
                        .off("mouseup." + this.widgetName, this._mouseUpDelegate),
                    this._mouseStarted &&
                    ((this._mouseStarted = !1),
                    e.target === this._mouseDownEvent.target &&
                    t.data(e.target, this.widgetName + ".preventClickEvent", !0),
                        this._mouseStop(e)),
                    this._mouseDelayTimer &&
                    (clearTimeout(this._mouseDelayTimer),
                        delete this._mouseDelayTimer),
                        (this.ignoreMissingWhich = !1),
                        (n = !1),
                        e.preventDefault();
                },
                _mouseDistanceMet: function(t) {
                    return (
                        Math.max(
                            Math.abs(this._mouseDownEvent.pageX - t.pageX),
                            Math.abs(this._mouseDownEvent.pageY - t.pageY)
                        ) >= this.options.distance
                    );
                },
                _mouseDelayMet: function() {
                    return this.mouseDelayMet;
                },
                _mouseStart: function() {},
                _mouseDrag: function() {},
                _mouseStop: function() {},
                _mouseCapture: function() {
                    return !0;
                }
            }),
            t.widget("ui.slider", t.ui.mouse, {
                version: "1.12.1",
                widgetEventPrefix: "slide",
                options: {
                    animate: !1,
                    classes: {
                        "ui-slider": "ui-corner-all",
                        "ui-slider-handle": "ui-corner-all",
                        "ui-slider-range": "ui-corner-all ui-widget-header"
                    },
                    distance: 0,
                    max: 100,
                    min: 0,
                    orientation: "horizontal",
                    range: !1,
                    step: 1,
                    value: 0,
                    values: null,
                    change: null,
                    slide: null,
                    start: null,
                    stop: null
                },
                numPages: 5,
                _create: function() {
                    (this._keySliding = !1),
                        (this._mouseSliding = !1),
                        (this._animateOff = !0),
                        (this._handleIndex = null),
                        this._detectOrientation(),
                        this._mouseInit(),
                        this._calculateNewMax(),
                        this._addClass(
                            "ui-slider ui-slider-" + this.orientation,
                            "ui-widget ui-widget-content"
                        ),
                        this._refresh(),
                        (this._animateOff = !1);
                },
                _refresh: function() {
                    this._createRange(),
                        this._createHandles(),
                        this._setupEvents(),
                        this._refreshValue();
                },
                _createHandles: function() {
                    var e,
                        i,
                        s = this.options,
                        n = this.element.find(".ui-slider-handle"),
                        a = "<span tabindex='0'></span>",
                        o = [];
                    for (
                        i = (s.values && s.values.length) || 1,
                        n.length > i && (n.slice(i).remove(), (n = n.slice(0, i))),
                            e = n.length;
                        i > e;
                        e++
                    )
                        o.push(a);
                    (this.handles = n.add(t(o.join("")).appendTo(this.element))),
                        this._addClass(
                            this.handles,
                            "ui-slider-handle",
                            "ui-state-default"
                        ),
                        (this.handle = this.handles.eq(0)),
                        this.handles.each(function(e) {
                            t(this)
                                .data("ui-slider-handle-index", e)
                                .attr("tabIndex", 0);
                        });
                },
                _createRange: function() {
                    var e = this.options;
                    e.range
                        ? (e.range === !0 &&
                        (e.values
                            ? e.values.length && 2 !== e.values.length
                                ? (e.values = [e.values[0], e.values[0]])
                                : t.isArray(e.values) && (e.values = e.values.slice(0))
                            : (e.values = [this._valueMin(), this._valueMin()])),
                            this.range && this.range.length
                                ? (this._removeClass(
                                this.range,
                                "ui-slider-range-min ui-slider-range-max"
                                ),
                                    this.range.css({ left: "", bottom: "" }))
                                : ((this.range = t("<div>").appendTo(this.element)),
                                    this._addClass(this.range, "ui-slider-range")),
                        ("min" === e.range || "max" === e.range) &&
                        this._addClass(this.range, "ui-slider-range-" + e.range))
                        : (this.range && this.range.remove(), (this.range = null));
                },
                _setupEvents: function() {
                    this._off(this.handles),
                        this._on(this.handles, this._handleEvents),
                        this._hoverable(this.handles),
                        this._focusable(this.handles);
                },
                _destroy: function() {
                    this.handles.remove(),
                    this.range && this.range.remove(),
                        this._mouseDestroy();
                },
                _mouseCapture: function(e) {
                    var i,
                        s,
                        n,
                        a,
                        o,
                        r,
                        l,
                        c,
                        d = this,
                        u = this.options;
                    return (
                        !u.disabled &&
                        ((this.elementSize = {
                            width: this.element.outerWidth(),
                            height: this.element.outerHeight()
                        }),
                            (this.elementOffset = this.element.offset()),
                            (i = { x: e.pageX, y: e.pageY }),
                            (s = this._normValueFromMouse(i)),
                            (n = this._valueMax() - this._valueMin() + 1),
                            this.handles.each(function(e) {
                                var i = Math.abs(s - d.values(e));
                                (n > i ||
                                    (n === i &&
                                        (e === d._lastChangedValue || d.values(e) === u.min))) &&
                                ((n = i), (a = t(this)), (o = e));
                            }),
                            (r = this._start(e, o)),
                        r !== !1 &&
                        ((this._mouseSliding = !0),
                            (this._handleIndex = o),
                            this._addClass(a, null, "ui-state-active"),
                            a.trigger("focus"),
                            (l = a.offset()),
                            (c = !t(e.target)
                                .parents()
                                .addBack()
                                .is(".ui-slider-handle")),
                            (this._clickOffset = c
                                ? { left: 0, top: 0 }
                                : {
                                    left: e.pageX - l.left - a.width() / 2,
                                    top:
                                        e.pageY -
                                        l.top -
                                        a.height() / 2 -
                                        (parseInt(a.css("borderTopWidth"), 10) || 0) -
                                        (parseInt(a.css("borderBottomWidth"), 10) || 0) +
                                        (parseInt(a.css("marginTop"), 10) || 0)
                                }),
                        this.handles.hasClass("ui-state-hover") || this._slide(e, o, s),
                            (this._animateOff = !0),
                            !0))
                    );
                },
                _mouseStart: function() {
                    return !0;
                },
                _mouseDrag: function(t) {
                    var e = { x: t.pageX, y: t.pageY },
                        i = this._normValueFromMouse(e);
                    return this._slide(t, this._handleIndex, i), !1;
                },
                _mouseStop: function(t) {
                    return (
                        this._removeClass(this.handles, null, "ui-state-active"),
                            (this._mouseSliding = !1),
                            this._stop(t, this._handleIndex),
                            this._change(t, this._handleIndex),
                            (this._handleIndex = null),
                            (this._clickOffset = null),
                            (this._animateOff = !1),
                            !1
                    );
                },
                _detectOrientation: function() {
                    this.orientation =
                        "vertical" === this.options.orientation ? "vertical" : "horizontal";
                },
                _normValueFromMouse: function(t) {
                    var e, i, s, n, a;
                    return (
                        "horizontal" === this.orientation
                            ? ((e = this.elementSize.width),
                                (i =
                                    t.x -
                                    this.elementOffset.left -
                                    (this._clickOffset ? this._clickOffset.left : 0)))
                            : ((e = this.elementSize.height),
                                (i =
                                    t.y -
                                    this.elementOffset.top -
                                    (this._clickOffset ? this._clickOffset.top : 0))),
                            (s = i / e),
                        s > 1 && (s = 1),
                        0 > s && (s = 0),
                        "vertical" === this.orientation && (s = 1 - s),
                            (n = this._valueMax() - this._valueMin()),
                            (a = this._valueMin() + s * n),
                            this._trimAlignValue(a)
                    );
                },
                _uiHash: function(t, e, i) {
                    var s = {
                        handle: this.handles[t],
                        handleIndex: t,
                        value: void 0 !== e ? e : this.value()
                    };
                    return (
                        this._hasMultipleValues() &&
                        ((s.value = void 0 !== e ? e : this.values(t)),
                            (s.values = i || this.values())),
                            s
                    );
                },
                _hasMultipleValues: function() {
                    return this.options.values && this.options.values.length;
                },
                _start: function(t, e) {
                    return this._trigger("start", t, this._uiHash(e));
                },
                _slide: function(t, e, i) {
                    var s,
                        n,
                        a = this.value(),
                        o = this.values();
                    this._hasMultipleValues() &&
                    ((n = this.values(e ? 0 : 1)),
                        (a = this.values(e)),
                    2 === this.options.values.length &&
                    this.options.range === !0 &&
                    (i = 0 === e ? Math.min(n, i) : Math.max(n, i)),
                        (o[e] = i)),
                    i !== a &&
                    ((s = this._trigger("slide", t, this._uiHash(e, i, o))),
                    s !== !1 &&
                    (this._hasMultipleValues()
                        ? this.values(e, i)
                        : this.value(i)));
                },
                _stop: function(t, e) {
                    this._trigger("stop", t, this._uiHash(e));
                },
                _change: function(t, e) {
                    this._keySliding ||
                    this._mouseSliding ||
                    ((this._lastChangedValue = e),
                        this._trigger("change", t, this._uiHash(e)));
                },
                value: function(t) {
                    return arguments.length
                        ? ((this.options.value = this._trimAlignValue(t)),
                            this._refreshValue(),
                            void this._change(null, 0))
                        : this._value();
                },
                values: function(e, i) {
                    var s, n, a;
                    if (arguments.length > 1)
                        return (
                            (this.options.values[e] = this._trimAlignValue(i)),
                                this._refreshValue(),
                                void this._change(null, e)
                        );
                    if (!arguments.length) return this._values();
                    if (!t.isArray(arguments[0]))
                        return this._hasMultipleValues() ? this._values(e) : this.value();
                    for (
                        s = this.options.values, n = arguments[0], a = 0;
                        s.length > a;
                        a += 1
                    )
                        (s[a] = this._trimAlignValue(n[a])), this._change(null, a);
                    this._refreshValue();
                },
                _setOption: function(e, i) {
                    var s,
                        n = 0;
                    switch (
                        ("range" === e &&
                        this.options.range === !0 &&
                        ("min" === i
                            ? ((this.options.value = this._values(0)),
                                (this.options.values = null))
                            : "max" === i &&
                            ((this.options.value = this._values(
                                this.options.values.length - 1
                            )),
                                (this.options.values = null))),
                        t.isArray(this.options.values) && (n = this.options.values.length),
                            this._super(e, i),
                            e)
                        ) {
                        case "orientation":
                            this._detectOrientation(),
                                this._removeClass(
                                    "ui-slider-horizontal ui-slider-vertical"
                                )._addClass("ui-slider-" + this.orientation),
                                this._refreshValue(),
                            this.options.range && this._refreshRange(i),
                                this.handles.css("horizontal" === i ? "bottom" : "left", "");
                            break;
                        case "value":
                            (this._animateOff = !0),
                                this._refreshValue(),
                                this._change(null, 0),
                                (this._animateOff = !1);
                            break;
                        case "values":
                            for (
                                this._animateOff = !0, this._refreshValue(), s = n - 1;
                                s >= 0;
                                s--
                            )
                                this._change(null, s);
                            this._animateOff = !1;
                            break;
                        case "step":
                        case "min":
                        case "max":
                            (this._animateOff = !0),
                                this._calculateNewMax(),
                                this._refreshValue(),
                                (this._animateOff = !1);
                            break;
                        case "range":
                            (this._animateOff = !0), this._refresh(), (this._animateOff = !1);
                    }
                },
                _setOptionDisabled: function(t) {
                    this._super(t), this._toggleClass(null, "ui-state-disabled", !!t);
                },
                _value: function() {
                    var t = this.options.value;
                    return (t = this._trimAlignValue(t));
                },
                _values: function(t) {
                    var e, i, s;
                    if (arguments.length)
                        return (e = this.options.values[t]), (e = this._trimAlignValue(e));
                    if (this._hasMultipleValues()) {
                        for (i = this.options.values.slice(), s = 0; i.length > s; s += 1)
                            i[s] = this._trimAlignValue(i[s]);
                        return i;
                    }
                    return [];
                },
                _trimAlignValue: function(t) {
                    if (this._valueMin() >= t) return this._valueMin();
                    if (t >= this._valueMax()) return this._valueMax();
                    var e = this.options.step > 0 ? this.options.step : 1,
                        i = (t - this._valueMin()) % e,
                        s = t - i;
                    return (
                        2 * Math.abs(i) >= e && (s += i > 0 ? e : -e),
                            parseFloat(s.toFixed(5))
                    );
                },
                _calculateNewMax: function() {
                    var t = this.options.max,
                        e = this._valueMin(),
                        i = this.options.step,
                        s = Math.round((t - e) / i) * i;
                    (t = s + e),
                    t > this.options.max && (t -= i),
                        (this.max = parseFloat(t.toFixed(this._precision())));
                },
                _precision: function() {
                    var t = this._precisionOf(this.options.step);
                    return (
                        null !== this.options.min &&
                        (t = Math.max(t, this._precisionOf(this.options.min))),
                            t
                    );
                },
                _precisionOf: function(t) {
                    var e = "" + t,
                        i = e.indexOf(".");
                    return -1 === i ? 0 : e.length - i - 1;
                },
                _valueMin: function() {
                    return this.options.min;
                },
                _valueMax: function() {
                    return this.max;
                },
                _refreshRange: function(t) {
                    "vertical" === t && this.range.css({ width: "", left: "" }),
                    "horizontal" === t && this.range.css({ height: "", bottom: "" });
                },
                _refreshValue: function() {
                    var e,
                        i,
                        s,
                        n,
                        a,
                        o = this.options.range,
                        r = this.options,
                        l = this,
                        c = !this._animateOff && r.animate,
                        d = {};
                    this._hasMultipleValues()
                        ? this.handles.each(function(s) {
                            (i =
                                100 *
                                ((l.values(s) - l._valueMin()) /
                                    (l._valueMax() - l._valueMin()))),
                                (d["horizontal" === l.orientation ? "left" : "bottom"] =
                                    i + "%"),
                                t(this)
                                    .stop(1, 1)
                                    [c ? "animate" : "css"](d, r.animate),
                            l.options.range === !0 &&
                            ("horizontal" === l.orientation
                                ? (0 === s &&
                                l.range
                                    .stop(1, 1)
                                    [c ? "animate" : "css"](
                                    { left: i + "%" },
                                    r.animate
                                ),
                                1 === s &&
                                l.range[c ? "animate" : "css"](
                                    { width: i - e + "%" },
                                    { queue: !1, duration: r.animate }
                                ))
                                : (0 === s &&
                                l.range
                                    .stop(1, 1)
                                    [c ? "animate" : "css"](
                                    { bottom: i + "%" },
                                    r.animate
                                ),
                                1 === s &&
                                l.range[c ? "animate" : "css"](
                                    { height: i - e + "%" },
                                    { queue: !1, duration: r.animate }
                                ))),
                                (e = i);
                        })
                        : ((s = this.value()),
                            (n = this._valueMin()),
                            (a = this._valueMax()),
                            (i = a !== n ? 100 * ((s - n) / (a - n)) : 0),
                            (d["horizontal" === this.orientation ? "left" : "bottom"] =
                                i + "%"),
                            this.handle.stop(1, 1)[c ? "animate" : "css"](d, r.animate),
                        "min" === o &&
                        "horizontal" === this.orientation &&
                        this.range
                            .stop(1, 1)
                            [c ? "animate" : "css"]({ width: i + "%" }, r.animate),
                        "max" === o &&
                        "horizontal" === this.orientation &&
                        this.range
                            .stop(1, 1)
                            [c ? "animate" : "css"]({ width: 100 - i + "%" }, r.animate),
                        "min" === o &&
                        "vertical" === this.orientation &&
                        this.range
                            .stop(1, 1)
                            [c ? "animate" : "css"]({ height: i + "%" }, r.animate),
                        "max" === o &&
                        "vertical" === this.orientation &&
                        this.range
                            .stop(1, 1)
                            [c ? "animate" : "css"](
                            { height: 100 - i + "%" },
                            r.animate
                        ));
                },
                _handleEvents: {
                    keydown: function(e) {
                        var i,
                            s,
                            n,
                            a,
                            o = t(e.target).data("ui-slider-handle-index");
                        switch (e.keyCode) {
                            case t.ui.keyCode.HOME:
                            case t.ui.keyCode.END:
                            case t.ui.keyCode.PAGE_UP:
                            case t.ui.keyCode.PAGE_DOWN:
                            case t.ui.keyCode.UP:
                            case t.ui.keyCode.RIGHT:
                            case t.ui.keyCode.DOWN:
                            case t.ui.keyCode.LEFT:
                                if (
                                    (e.preventDefault(),
                                    !this._keySliding &&
                                    ((this._keySliding = !0),
                                        this._addClass(t(e.target), null, "ui-state-active"),
                                        (i = this._start(e, o)),
                                    i === !1))
                                )
                                    return;
                        }
                        switch (
                            ((a = this.options.step),
                                (s = n = this._hasMultipleValues()
                                    ? this.values(o)
                                    : this.value()),
                                e.keyCode)
                            ) {
                            case t.ui.keyCode.HOME:
                                n = this._valueMin();
                                break;
                            case t.ui.keyCode.END:
                                n = this._valueMax();
                                break;
                            case t.ui.keyCode.PAGE_UP:
                                n = this._trimAlignValue(
                                    s + (this._valueMax() - this._valueMin()) / this.numPages
                                );
                                break;
                            case t.ui.keyCode.PAGE_DOWN:
                                n = this._trimAlignValue(
                                    s - (this._valueMax() - this._valueMin()) / this.numPages
                                );
                                break;
                            case t.ui.keyCode.UP:
                            case t.ui.keyCode.RIGHT:
                                if (s === this._valueMax()) return;
                                n = this._trimAlignValue(s + a);
                                break;
                            case t.ui.keyCode.DOWN:
                            case t.ui.keyCode.LEFT:
                                if (s === this._valueMin()) return;
                                n = this._trimAlignValue(s - a);
                        }
                        this._slide(e, o, n);
                    },
                    keyup: function(e) {
                        var i = t(e.target).data("ui-slider-handle-index");
                        this._keySliding &&
                        ((this._keySliding = !1),
                            this._stop(e, i),
                            this._change(e, i),
                            this._removeClass(t(e.target), null, "ui-state-active"));
                    }
                }
            });
    });

    var Filters = (function() {
            var t = $(".js_fixed-btn"),
                e = !1,
                i = !1,
                s = void 0,
                n = 0,
                a = $(".js_filter-caret"),
                o = $(".js_location-input"),
                r = $(".js_show-filters"),
                l = $(".js_range-input"),
                c = $(".js_slider-input"),
                d = $(".js_filters-reset"),
                u = $("#js_filter-form");
            return {
                initRangeInputs: function(t) {
                    var e = this;
                    $.each(t, function(t, i) {
                        var s = this,
                            n = $(i),
                            a = n.data(),
                            o = a.min,
                            r = a.max,
                            l = a.valuestart,
                            c = a.valueend,
                            d = a.step,
                            u = a.time,
                            h = a.input,
                            p = n.siblings(".range-out");
                        if (u) {
                            var f = e.getHoursMinutes(l),
                                v = e.getHoursMinutes(c);
                            e.updateSlidersOutNodes(p, f, v),
                                $(h)
                                    .val(f + "-" + v)
                                    .trigger("change");
                        } else
                            e.updateSlidersOutNodes(p, l, c),
                                $(h)
                                    .val(l + "-" + c)
                                    .trigger("change");
                        n.slider({
                            range: !0,
                            min: o,
                            max: r,
                            step: d || 1,
                            values: [l, c],
                            slide: function(t, i) {
                                if (u) {
                                    var n = e.getHoursMinutes(i.values[0]),
                                        a = e.getHoursMinutes(i.values[1]);
                                    $(h)
                                        .val(n + "-" + a)
                                        .trigger("change"),
                                        e.updateSlidersOutNodes(p, n, a);
                                    var o = $(s).parents(".js_schedule");
                                    o.trigger("scheduleChange");
                                } else
                                    $(h)
                                        .val(i.values[0] + "-" + i.values[1])
                                        .trigger("change"),
                                        e.updateSlidersOutNodes(p, i.values[0], i.values[1]);
                            }
                        });
                    });
                },
                getHoursMinutes: function(t) {
                    var e = Math.floor(t),
                        i = 60 * (t - e);
                    return e < 10 && (e = "0" + e), i < 10 && (i = "0" + i), e + ":" + i;
                },
                initSliderInputs: function(t) {
                    var e = this;
                    $.each(t, function(t, i) {
                        var s = $(i),
                            n = s.data(),
                            a = "",
                            o = "",
                            r = "",
                            l = "";
                        (a = n.max),
                            (o = n.valueend),
                            (r = n.currency),
                            (l = n.input),
                        r || (r = "");
                        var c = s.siblings(".range-out");
                        $(l).val(r + o),
                            e.updateSliderInputOutNode(c, r + " " + o),
                            s.slider({
                                range: "min",
                                max: a,
                                value: o,
                                slide: function(t, i) {
                                    $(l)
                                        .val(r + i.value)
                                        .trigger("change"),
                                        e.updateSliderInputOutNode(c, r + " " + i.value);
                                }
                            });
                    });
                },
                updateSliderInputOutNode: function(t, e) {
                    t.length && t.find(".range-out__value").text(e);
                },
                updateSlidersOutNodes: function(t, e, i) {
                    t.length &&
                    (t.find(".range-out__min").text(e),
                        t.find(".range-out__max").text(i));
                },
                getBtnOffsets: function(t) {
                    var e = void 0,
                        i = void 0;
                    return (
                        t.length &&
                        ((e = t.offset().top - 70), (i = t.offset().top + t.height())),
                            { btnTopOffset: e, btnBtmOffset: i }
                    );
                },
                changeShowFilterListBtnClass: function(t, e, i, s) {
                    return e >= i
                        ? (t.addClass("is-fixed"), !0)
                        : e <= s
                            ? (t.removeClass("is-fixed"), !1)
                            : void 0;
                },
                filterCaretClickEvent: function() {
                    a.click(function(t) {
                        t.preventDefault();
                        var e = $(this),
                            i = e.closest(".js_filter"),
                            s = i.find(".js_list");
                        i.hasClass("is-open")
                            ? s.slideUp(300, function() {
                                return i.removeClass("is-open");
                            })
                            : s.slideDown(300, function() {
                                return i.addClass("is-open");
                            });
                    });
                },
                fixedFilterBtnClickEvent: function() {
                    var e = this;
                    t.click(function(t) {
                        t.preventDefault();
                        var i = $(this);
                        e.toggleFiltersAside(i);
                    });
                },
                toggleFiltersAside: function(t) {
                    (i = !i),
                        $("html, body")
                            .animate({ scrollTop: i ? t.offset().top - 100 : 0 }, 100)
                            .promise()
                            .done(function() {
                                var i = t.find(".js_text");
                                t.toggleDataAltText(i);
                                var s = e
                                    ? t.position().top + t.height()
                                    : t.offset().top + t.outerHeight() - $(document).scrollTop(),
                                    n = t.siblings(".js_drop-list");
                                n.css("top", s),
                                    n.slideToggle(),
                                    t.toggleClass("is-active"),
                                    noScroll.toggle(),
                                    $("html").toggleClass("is-fixed");
                            });
                },
                locationFocusEvent: function() {
                    var e = this;
                    o.on("focus", function() {
                        var i = e.getBtnOffsets(t);
                        (s = i.btnTopOffset), (n = i.btnBtmOffset);
                    });
                },
                showMoreFiltersClickEvent: function() {
                    r.click(function(t) {
                        t.preventDefault();
                        var e = $(this),
                            i = e.siblings(".filter__list");
                        e.toggleDataAltText(), i.toggleClass("is-minimized");
                    });
                },
                changeFilterFormEvent: function() {
                    u.change(function(t) {
                        t.preventDefault(), d.addClass("is-active");
                    });
                },
                documentScrollEvent: function(a) {
                    i || (e = this.changeShowFilterListBtnClass(t, a, s, n));
                },
                init: function(e) {
                    var i = this;
                    if ((this.initRangeInputs(l), this.initSliderInputs(c), e)) {
                        var a = this.getBtnOffsets(t);
                        (s = a.btnTopOffset), (n = a.btnBtmOffset);
                    }
                    this.filterCaretClickEvent(),
                        this.fixedFilterBtnClickEvent(),
                        this.locationFocusEvent(),
                        this.changeFilterFormEvent(),
                        $(window).resize(function() {
                            var e = i.getBtnOffsets(t);
                            (s = e.btnTopOffset), (n = e.btnBtmOffset);
                        });
                }
            };
        })();
    Filters.init();

    var filterContainer = $('#rentsyst-filter');
	var catalog = $('.rentsyst-container-catalog');
    if(filterContainer.hasClass('rentsyst-enable-fix-position') && catalog) {
        $(window).on('scroll', function () {
            if (window.innerWidth > 980) {
                var scrollPosition = $(document).scrollTop();
                var bottom = catalog.position().top + catalog.outerHeight(true) - filterContainer.outerHeight(true) - 153;

                if (scrollPosition > catalog.offset().top && scrollPosition < bottom) {
                    filterContainer.addClass('rentsyst-fixed-filter');
                    filterContainer.css('top', 50);
                } else if (scrollPosition > bottom) {
                    filterContainer.css('top', bottom - scrollPosition);
                } else {
                    filterContainer.removeClass('rentsyst-fixed-filter');
                }
            }
        });
        $(window).on('resize', function () {
            $('#rentsyst-filter').removeClass('rentsyst-fixed-filter');
        });
    }

})(jQuery);
