// Rivets.js
// version: 0.6.8
// author: Michael Richards
// license: MIT
(function() {
    var a, b, c, d, e = function(a, b) {
        return function() {
            return a.apply(b, arguments)
        }
    }, f = [].indexOf || function(a) {
            for (var b = 0, c = this.length; c > b; b++) if (b in this && this[b] === a) return b;
            return -1
        }, g = [].slice,
        h = {}.hasOwnProperty,
        i = function(a, b) {
            function c() {
                this.constructor = a
            }
            for (var d in b) h.call(b, d) && (a[d] = b[d]);
            return c.prototype = b.prototype, a.prototype = new c, a.__super__ = b.prototype, a
        };
    a = {
        binders: {},
        components: {},
        formatters: {},
        adapters: {},
        config: {
            prefix: "rv",
            templateDelimiters: ["{", "}"],
            rootInterface: ".",
            preloadData: !0,
            handler: function(a, b, c) {
                return this.call(a, b, c.view.models)
            }
        }
    }, "jQuery" in window ? (d = "on" in jQuery.prototype ? ["on", "off"] : ["bind", "unbind"], b = d[0], c = d[1], a.Util = {
        bindEvent: function(a, c, d) {
            return jQuery(a)[b](c, d)
        },
        unbindEvent: function(a, b, d) {
            return jQuery(a)[c](b, d)
        },
        getInputValue: function(a) {
            var b;
            return b = jQuery(a), "checkbox" === b.attr("type") ? b.is(":checked") : b.val()
        }
    }) : a.Util = {
        bindEvent: function() {
            return "addEventListener" in window ? function(a, b, c) {
                return a.addEventListener(b, c, !1)
            } : function(a, b, c) {
                return a.attachEvent("on" + b, c)
            }
        }(),
        unbindEvent: function() {
            return "removeEventListener" in window ? function(a, b, c) {
                return a.removeEventListener(b, c, !1)
            } : function(a, b, c) {
                return a.detachEvent("on" + b, c)
            }
        }(),
        getInputValue: function(a) {
            var b, c, d, e;
            if ("checkbox" === a.type) return a.checked;
            if ("select-multiple" === a.type) {
                for (e = [], c = 0, d = a.length; d > c; c++) b = a[c], b.selected && e.push(b.value);
                return e
            }
            return a.value
        }
    }, a.View = function() {
        function b(b, c, d) {
            var f, g, h, i, j, k, l, m, n;
            for (this.els = b, this.models = c, this.options = null != d ? d : {}, this.update = e(this.update, this), this.publish = e(this.publish, this), this.sync = e(this.sync, this), this.unbind = e(this.unbind, this), this.bind = e(this.bind, this), this.select = e(this.select, this), this.build = e(this.build, this), this.componentRegExp = e(this.componentRegExp, this), this.bindingRegExp = e(this.bindingRegExp, this), this.els.jquery || this.els instanceof Array || (this.els = [this.els]), l = ["config", "binders", "formatters", "adapters"], j = 0, k = l.length; k > j; j++) {
                if (g = l[j], this[g] = {}, this.options[g]) {
                    m = this.options[g];
                    for (f in m) h = m[f], this[g][f] = h
                }
                n = a[g];
                for (f in n) h = n[f], null == (i = this[g])[f] && (i[f] = h)
            }
            this.build()
        }
        return b.prototype.bindingRegExp = function() {
            return new RegExp("^" + this.config.prefix + "-")
        }, b.prototype.componentRegExp = function() {
            return new RegExp("^" + this.config.prefix.toUpperCase() + "-")
        }, b.prototype.build = function() {
            var b, c, d, e, g, h, i, j, k, l = this;
            for (this.bindings = [], h = [], b = this.bindingRegExp(), d = this.componentRegExp(), c = function(b, c, d, e) {
                var f, g, h, i, j, k, m;
                return j = {}, m = function() {
                    var a, b, c, d;
                    for (c = e.split("|"), d = [], a = 0, b = c.length; b > a; a++) k = c[a], d.push(k.trim());
                    return d
                }(), f = function() {
                    var a, b, c, d;
                    for (c = m.shift().split("<"), d = [], a = 0, b = c.length; b > a; a++) g = c[a], d.push(g.trim());
                    return d
                }(), i = f.shift(), j.formatters = m, (h = f.shift()) && (j.dependencies = h.split(/\s+/)), l.bindings.push(new a[b](l, c, d, i, j))
            }, g = function(e) {
                var i, j, k, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F, G, H, I, J, K, L, M;
                if (f.call(h, e) < 0) {
                    if (3 === e.nodeType) {
                        if (q = a.TextTemplateParser, (n = l.config.templateDelimiters) && (u = q.parse(e.data, n)).length && (1 !== u.length || u[0].type !== q.types.text)) {
                            for (x = 0, B = u.length; B > x; x++) t = u[x], s = document.createTextNode(t.value), e.parentNode.insertBefore(s, e), 1 === t.type && c("TextBinding", s, null, t.value);
                            e.parentNode.removeChild(e)
                        }
                    } else if (d.test(e.tagName)) v = e.tagName.replace(d, "").toLowerCase(), l.bindings.push(new a.ComponentBinding(l, e, v));
                    else if (null != e.attributes) {
                        for (H = e.attributes, y = 0, C = H.length; C > y; y++) if (i = H[y], b.test(i.name)) {
                            if (v = i.name.replace(b, ""), !(k = l.binders[v])) {
                                I = l.binders;
                                for (o in I) w = I[o], "*" !== o && -1 !== o.indexOf("*") && (r = new RegExp("^" + o.replace("*", ".+") + "$"), r.test(v) && (k = w))
                            }
                            if (k || (k = l.binders["*"]), k.block) {
                                for (J = e.childNodes, z = 0, D = J.length; D > z; z++) p = J[z], h.push(p);
                                j = [i]
                            }
                        }
                        for (K = j || e.attributes, A = 0, E = K.length; E > A; A++) i = K[A], b.test(i.name) && (v = i.name.replace(b, ""), c("Binding", e, v, i.value))
                    }
                    for (L = function() {
                        var a, b, c, d;
                        for (c = e.childNodes, d = [], b = 0, a = c.length; a > b; b++) p = c[b], d.push(p);
                        return d
                    }(), M = [], G = 0, F = L.length; F > G; G++) m = L[G], M.push(g(m));
                    return M
                }
            }, k = this.els, i = 0, j = k.length; j > i; i++) e = k[i], g(e)
        }, b.prototype.select = function(a) {
            var b, c, d, e, f;
            for (e = this.bindings, f = [], c = 0, d = e.length; d > c; c++) b = e[c], a(b) && f.push(b);
            return f
        }, b.prototype.bind = function() {
            var a, b, c, d, e;
            for (d = this.bindings, e = [], b = 0, c = d.length; c > b; b++) a = d[b], e.push(a.bind());
            return e
        }, b.prototype.unbind = function() {
            var a, b, c, d, e;
            for (d = this.bindings, e = [], b = 0, c = d.length; c > b; b++) a = d[b], e.push(a.unbind());
            return e
        }, b.prototype.sync = function() {
            var a, b, c, d, e;
            for (d = this.bindings, e = [], b = 0, c = d.length; c > b; b++) a = d[b], e.push(a.sync());
            return e
        }, b.prototype.publish = function() {
            var a, b, c, d, e;
            for (d = this.select(function(a) {
                return a.binder.publishes
            }), e = [], b = 0, c = d.length; c > b; b++) a = d[b], e.push(a.publish());
            return e
        }, b.prototype.update = function(a) {
            var b, c, d, e, f, g, h;
            null == a && (a = {});
            for (c in a) d = a[c], this.models[c] = d;
            for (g = this.bindings, h = [], e = 0, f = g.length; f > e; e++) b = g[e], h.push(b.update(a));
            return h
        }, b
    }(), a.Binding = function() {
        function b(a, b, c, d, f) {
            this.view = a, this.el = b, this.type = c, this.keypath = d, this.options = null != f ? f : {}, this.update = e(this.update, this), this.unbind = e(this.unbind, this), this.bind = e(this.bind, this), this.publish = e(this.publish, this), this.sync = e(this.sync, this), this.set = e(this.set, this), this.eventHandler = e(this.eventHandler, this), this.formattedValue = e(this.formattedValue, this), this.setBinder = e(this.setBinder, this), this.formatters = this.options.formatters || [], this.dependencies = [], this.model = void 0, this.setBinder()
        }
        return b.prototype.setBinder = function() {
            var a, b, c, d;
            if (!(this.binder = this.view.binders[this.type])) {
                d = this.view.binders;
                for (a in d) c = d[a], "*" !== a && -1 !== a.indexOf("*") && (b = new RegExp("^" + a.replace("*", ".+") + "$"), b.test(this.type) && (this.binder = c, this.args = new RegExp("^" + a.replace("*", "(.+)") + "$").exec(this.type), this.args.shift()))
            }
            return this.binder || (this.binder = this.view.binders["*"]), this.binder instanceof Function ? this.binder = {
                routine: this.binder
            } : void 0
        }, b.prototype.formattedValue = function(a) {
            var b, c, d, e, f, h;
            for (h = this.formatters, e = 0, f = h.length; f > e; e++) c = h[e], b = c.split(/\s+/), d = b.shift(), c = this.view.formatters[d], (null != c ? c.read : void 0) instanceof Function ? a = c.read.apply(c, [a].concat(g.call(b))) : c instanceof Function && (a = c.apply(null, [a].concat(g.call(b))));
            return a
        }, b.prototype.eventHandler = function(a) {
            var b, c;
            return c = (b = this).view.config.handler,
            function(d) {
                return c.call(a, this, d, b)
            }
        }, b.prototype.set = function(a) {
            var b;
            return a = a instanceof Function && !this.binder["function"] ? this.formattedValue(a.call(this.model)) : this.formattedValue(a), null != (b = this.binder.routine) ? b.call(this, this.el, a) : void 0
        }, b.prototype.sync = function() {
            var b, c, d, e, f, g, h, i, j;
            if (this.model !== this.observer.target) {
                for (h = this.dependencies, d = 0, f = h.length; f > d; d++) c = h[d], c.unobserve();
                if (this.dependencies = [], null != (this.model = this.observer.target) && (null != (i = this.options.dependencies) ? i.length : void 0)) for (j = this.options.dependencies, e = 0, g = j.length; g > e; e++) b = j[e], c = new a.Observer(this.view, this.model, b, this.sync), this.dependencies.push(c)
            }
            return this.set(this.observer.value())
        }, b.prototype.publish = function() {
            var b, c, d, e, f, h, i, j, k;
            for (e = a.Util.getInputValue(this.el), i = this.formatters.slice(0).reverse(), f = 0, h = i.length; h > f; f++) c = i[f], b = c.split(/\s+/), d = b.shift(), (null != (j = this.view.formatters[d]) ? j.publish : void 0) && (e = (k = this.view.formatters[d]).publish.apply(k, [e].concat(g.call(b))));
            return this.observer.publish(e)
        }, b.prototype.bind = function() {
            var b, c, d, e, f, g, h;
            if (null != (f = this.binder.bind) && f.call(this, this.el), this.observer = new a.Observer(this.view, this.view.models, this.keypath, this.sync), this.model = this.observer.target, null != this.model && (null != (g = this.options.dependencies) ? g.length : void 0)) for (h = this.options.dependencies, d = 0, e = h.length; e > d; d++) b = h[d], c = new a.Observer(this.view, this.model, b, this.sync), this.dependencies.push(c);
            return this.view.config.preloadData ? this.sync() : void 0
        }, b.prototype.unbind = function() {
            var a, b, c, d, e;
            for (null != (d = this.binder.unbind) && d.call(this, this.el), this.observer.unobserve(), e = this.dependencies, b = 0, c = e.length; c > b; b++) a = e[b], a.unobserve();
            return this.dependencies = []
        }, b.prototype.update = function(a) {
            var b;
            return null == a && (a = {}), this.model = this.observer.target, null != (b = this.binder.update) ? b.call(this, a) : void 0
        }, b
    }(), a.ComponentBinding = function(b) {
        function c(b, c, d) {
            var g, h, i, j, k;
            for (this.view = b, this.el = c, this.type = d, this.unbind = e(this.unbind, this), this.bind = e(this.bind, this), this.update = e(this.update, this), this.locals = e(this.locals, this), this.component = a.components[this.type], this.attributes = {}, this.inflections = {}, j = this.el.attributes || [], h = 0, i = j.length; i > h; h++) g = j[h], k = g.name, f.call(this.component.attributes, k) >= 0 ? this.attributes[g.name] = g.value : this.inflections[g.name] = g.value
        }
        return i(c, b), c.prototype.sync = function() {}, c.prototype.locals = function(a) {
            var b, c, d, e, f, g, h, i, j;
            null == a && (a = this.view.models), f = {}, i = this.inflections;
            for (c in i) for (b = i[c], j = b.split("."), g = 0, h = j.length; h > g; g++) e = j[g], f[c] = (f[c] || a)[e];
            for (c in a) d = a[c], null == f[c] && (f[c] = d);
            return f
        }, c.prototype.update = function(a) {
            var b;
            return null != (b = this.componentView) ? b.update(this.locals(a)) : void 0
        }, c.prototype.bind = function() {
            var b, c;
            return null != this.componentView ? null != (c = this.componentView) ? c.bind() : void 0 : (b = this.component.build.call(this.attributes), (this.componentView = new a.View(b, this.locals(), this.view.options)).bind(), this.el.parentNode.replaceChild(b, this.el))
        }, c.prototype.unbind = function() {
            var a;
            return null != (a = this.componentView) ? a.unbind() : void 0
        }, c
    }(a.Binding), a.TextBinding = function(a) {
        function b(a, b, c, d, f) {
            this.view = a, this.el = b, this.type = c, this.keypath = d, this.options = null != f ? f : {}, this.sync = e(this.sync, this), this.formatters = this.options.formatters || [], this.dependencies = []
        }
        return i(b, a), b.prototype.binder = {
            routine: function(a, b) {
                return a.data = null != b ? b : ""
            }
        }, b.prototype.sync = function() {
            return b.__super__.sync.apply(this, arguments)
        }, b
    }(a.Binding), a.KeypathParser = function() {
        function a() {}
        return a.parse = function(a, b, c) {
            var d, e, g, h, i, j;
            for (h = [], e = {
                "interface": c,
                path: ""
            }, g = i = 0, j = a.length; j > i; g = i += 1) d = a.charAt(g), f.call(b, d) >= 0 ? (h.push(e), e = {
                "interface": d,
                path: ""
            }) : e.path += d;
            return h.push(e), h
        }, a
    }(), a.TextTemplateParser = function() {
        function a() {}
        return a.types = {
            text: 0,
            binding: 1
        }, a.parse = function(a, b) {
            var c, d, e, f, g, h, i;
            for (h = [], f = a.length, c = 0, d = 0; f > d;) {
                if (c = a.indexOf(b[0], d), 0 > c) {
                    h.push({
                        type: this.types.text,
                        value: a.slice(d)
                    });
                    break
                }
                if (c > 0 && c > d && h.push({
                    type: this.types.text,
                    value: a.slice(d, c)
                }), d = c + b[0].length, c = a.indexOf(b[1], d), 0 > c) {
                    g = a.slice(d - b[1].length), e = h[h.length - 1], (null != e ? e.type : void 0) === this.types.text ? e.value += g : h.push({
                        type: this.types.text,
                        value: g
                    });
                    break
                }
                i = a.slice(d, c).trim(), h.push({
                    type: this.types.binding,
                    value: i
                }), d = c + b[1].length
            }
            return h
        }, a
    }(), a.Observer = function() {
        function b(a, b, c, d) {
            this.view = a, this.model = b, this.keypath = c, this.callback = d, this.unobserve = e(this.unobserve, this), this.realize = e(this.realize, this), this.value = e(this.value, this), this.publish = e(this.publish, this), this.read = e(this.read, this), this.set = e(this.set, this), this.adapter = e(this.adapter, this), this.update = e(this.update, this), this.initialize = e(this.initialize, this), this.parse = e(this.parse, this), this.parse(), this.initialize()
        }
        return b.prototype.parse = function() {
            var b, c, d, e, g, h;
            return b = function() {
                var a, b;
                a = this.view.adapters, b = [];
                for (c in a) g = a[c], b.push(c);
                return b
            }.call(this), h = this.keypath[0], f.call(b, h) >= 0 ? (e = this.keypath[0], d = this.keypath.substr(1)) : (e = this.view.config.rootInterface, d = this.keypath), this.tokens = a.KeypathParser.parse(d, b, e), this.key = this.tokens.pop()
        }, b.prototype.initialize = function() {
            return this.objectPath = [], this.target = this.realize(), null != this.target ? this.set(!0, this.key, this.target, this.callback) : void 0
        }, b.prototype.update = function() {
            var a, b;
            return (a = this.realize()) !== this.target && (null != this.target && this.set(!1, this.key, this.target, this.callback), null != a && this.set(!0, this.key, a, this.callback), b = this.value(), this.target = a, this.value() !== b) ? this.callback() : void 0
        }, b.prototype.adapter = function(a) {
            return this.view.adapters[a["interface"]]
        }, b.prototype.set = function(a, b, c, d) {
            var e;
            return e = a ? "subscribe" : "unsubscribe", this.adapter(b)[e](c, b.path, d)
        }, b.prototype.read = function(a, b) {
            return this.adapter(a).read(b, a.path)
        }, b.prototype.publish = function(a) {
            return null != this.target ? this.adapter(this.key).publish(this.target, this.key.path, a) : void 0
        }, b.prototype.value = function() {
            return null != this.target ? this.read(this.key, this.target) : void 0
        }, b.prototype.realize = function() {
            var a, b, c, d, e, f, g, h;
            for (a = this.model, e = null, h = this.tokens, b = f = 0, g = h.length; g > f; b = ++f) d = h[b], null != a ? (null != this.objectPath[b] ? a !== (c = this.objectPath[b]) && (this.set(!1, d, c, this.update), this.set(!0, d, a, this.update), this.objectPath[b] = a) : (this.set(!0, d, a, this.update), this.objectPath[b] = a), a = this.read(d, a)) : (null == e && (e = b), (c = this.objectPath[b]) && this.set(!1, d, c, this.update));
            return null != e && this.objectPath.splice(e), a
        }, b.prototype.unobserve = function() {
            var a, b, c, d, e, f;
            for (f = this.tokens, a = d = 0, e = f.length; e > d; a = ++d) c = f[a], (b = this.objectPath[a]) && this.set(!1, c, b, this.update);
            return null != this.target ? this.set(!1, this.key, this.target, this.callback) : void 0
        }, b
    }(), a.binders.text = function(a, b) {
        return null != a.textContent ? a.textContent = null != b ? b : "" : a.innerText = null != b ? b : ""
    }, a.binders.html = function(a, b) {
        return a.innerHTML = null != b ? b : ""
    }, a.binders.show = function(a, b) {
        return a.style.display = b ? "" : "none"
    }, a.binders.hide = function(a, b) {
        return a.style.display = b ? "none" : ""
    }, a.binders.enabled = function(a, b) {
        return a.disabled = !b
    }, a.binders.disabled = function(a, b) {
        return a.disabled = !! b
    }, a.binders.checked = {
        publishes: !0,
        bind: function(b) {
            return a.Util.bindEvent(b, "change", this.publish)
        },
        unbind: function(b) {
            return a.Util.unbindEvent(b, "change", this.publish)
        },
        routine: function(a, b) {
            var c;
            return a.checked = "radio" === a.type ? (null != (c = a.value) ? c.toString() : void 0) === (null != b ? b.toString() : void 0) : !! b
        }
    }, a.binders.unchecked = {
        publishes: !0,
        bind: function(b) {
            return a.Util.bindEvent(b, "change", this.publish)
        },
        unbind: function(b) {
            return a.Util.unbindEvent(b, "change", this.publish)
        },
        routine: function(a, b) {
            var c;
            return a.checked = "radio" === a.type ? (null != (c = a.value) ? c.toString() : void 0) !== (null != b ? b.toString() : void 0) : !b
        }
    }, a.binders.value = {
        publishes: !0,
        bind: function(b) {
            return a.Util.bindEvent(b, "change", this.publish)
        },
        unbind: function(b) {
            return a.Util.unbindEvent(b, "change", this.publish)
        },
        routine: function(a, b) {
            var c, d, e, g, h, i, j;
            if (null != window.jQuery) {
                if (a = jQuery(a), (null != b ? b.toString() : void 0) !== (null != (g = a.val()) ? g.toString() : void 0)) return a.val(null != b ? b : "")
            } else if ("select-multiple" === a.type) {
                if (null != b) {
                    for (j = [], d = 0, e = a.length; e > d; d++) c = a[d], j.push(c.selected = (h = c.value, f.call(b, h) >= 0));
                    return j
                }
            } else if ((null != b ? b.toString() : void 0) !== (null != (i = a.value) ? i.toString() : void 0)) return a.value = null != b ? b : ""
        }
    }, a.binders["if"] = {
        block: !0,
        bind: function(a) {
            var b, c;
            return null == this.marker ? (b = [this.view.config.prefix, this.type].join("-").replace("--", "-"), c = a.getAttribute(b), this.marker = document.createComment(" rivets: " + this.type + " " + c + " "), a.removeAttribute(b), a.parentNode.insertBefore(this.marker, a), a.parentNode.removeChild(a)) : void 0
        },
        unbind: function() {
            var a;
            return null != (a = this.nested) ? a.unbind() : void 0
        },
        routine: function(b, c) {
            var d, e, f, g, h;
            if ( !! c == (null == this.nested)) {
                if (c) {
                    f = {}, h = this.view.models;
                    for (d in h) e = h[d], f[d] = e;
                    return g = {
                        binders: this.view.options.binders,
                        formatters: this.view.options.formatters,
                        adapters: this.view.options.adapters,
                        config: this.view.options.config
                    }, (this.nested = new a.View(b, f, g)).bind(), this.marker.parentNode.insertBefore(b, this.marker.nextSibling)
                }
                return b.parentNode.removeChild(b), this.nested.unbind(), delete this.nested
            }
        },
        update: function(a) {
            var b;
            return null != (b = this.nested) ? b.update(a) : void 0
        }
    }, a.binders.unless = {
        block: !0,
        bind: function(b) {
            return a.binders["if"].bind.call(this, b)
        },
        unbind: function() {
            return a.binders["if"].unbind.call(this)
        },
        routine: function(b, c) {
            return a.binders["if"].routine.call(this, b, !c)
        },
        update: function(b) {
            return a.binders["if"].update.call(this, b)
        }
    }, a.binders["on-*"] = {
        "function": !0,
        unbind: function(b) {
            return this.handler ? a.Util.unbindEvent(b, this.args[0], this.handler) : void 0
        },
        routine: function(b, c) {
            return this.handler && a.Util.unbindEvent(b, this.args[0], this.handler), a.Util.bindEvent(b, this.args[0], this.handler = this.eventHandler(c))
        }
    }, a.binders["each-*"] = {
        block: !0,
        bind: function(a) {
            var b;
            return null == this.marker ? (b = [this.view.config.prefix, this.type].join("-").replace("--", "-"), this.marker = document.createComment(" rivets: " + this.type + " "), this.iterated = [], a.removeAttribute(b), a.parentNode.insertBefore(this.marker, a), a.parentNode.removeChild(a)) : void 0
        },
        unbind: function() {
            var a, b, c, d, e;
            if (null != this.iterated) {
                for (d = this.iterated, e = [], b = 0, c = d.length; c > b; b++) a = d[b], e.push(a.unbind());
                return e
            }
        },
        routine: function(b, c) {
            var d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A;
            if (k = this.args[0], c = c || [], this.iterated.length > c.length) for (w = Array(this.iterated.length - c.length), q = 0, t = w.length; t > q; q++) f = w[q], p = this.iterated.pop(), p.unbind(), this.marker.parentNode.removeChild(p.els[0]);
            for (g = r = 0, u = c.length; u > r; g = ++r) if (j = c[g], e = {
                index: g
            }, e[k] = j, null == this.iterated[g]) {
                x = this.view.models;
                for (i in x) j = x[i], null == e[i] && (e[i] = j);
                m = this.iterated.length ? this.iterated[this.iterated.length - 1].els[0] : this.marker, l = {
                    binders: this.view.options.binders,
                    formatters: this.view.options.formatters,
                    adapters: this.view.options.adapters,
                    config: {}
                }, y = this.view.options.config;
                for (h in y) o = y[h], l.config[h] = o;
                l.config.preloadData = !0, n = b.cloneNode(!0), p = new a.View(n, e, l), p.bind(), this.iterated.push(p), this.marker.parentNode.insertBefore(n, m.nextSibling)
            } else this.iterated[g].models[k] !== j && this.iterated[g].update(e);
            if ("OPTION" === b.nodeName) {
                for (z = this.view.bindings, A = [], s = 0, v = z.length; v > s; s++) d = z[s], d.el === this.marker.parentNode && "value" === d.type ? A.push(d.sync()) : A.push(void 0);
                return A
            }
        },
        update: function(a) {
            var b, c, d, e, f, g, h, i;
            b = {};
            for (c in a) d = a[c], c !== this.args[0] && (b[c] = d);
            for (h = this.iterated, i = [], f = 0, g = h.length; g > f; f++) e = h[f], i.push(e.update(b));
            return i
        }
    }, a.binders["class-*"] = function(a, b) {
        var c;
        return c = " " + a.className + " ", !b == (-1 !== c.indexOf(" " + this.args[0] + " ")) ? a.className = b ? "" + a.className + " " + this.args[0] : c.replace(" " + this.args[0] + " ", " ").trim() : void 0
    }, a.binders["*"] = function(a, b) {
        return null != b ? a.setAttribute(this.type, b) : a.removeAttribute(this.type)
    }, a.adapters["."] = {
        id: "_rv",
        counter: 0,
        weakmap: {},
        weakReference: function(a) {
            var b;
            return null == a[this.id] && (b = this.counter++, this.weakmap[b] = {
                callbacks: {}
            }, Object.defineProperty(a, this.id, {
                value: b
            })), this.weakmap[a[this.id]]
        },
        stubFunction: function(a, b) {
            var c, d, e;
            return d = a[b], c = this.weakReference(a), e = this.weakmap, a[b] = function() {
                var b, f, g, h, i, j, k, l, m, n;
                h = d.apply(a, arguments), k = c.pointers;
                for (g in k) for (f = k[g], n = null != (l = null != (m = e[g]) ? m.callbacks[f] : void 0) ? l : [], i = 0, j = n.length; j > i; i++) b = n[i], b();
                return h
            }
        },
        observeMutations: function(a, b, c) {
            var d, e, g, h, i, j;
            if (Array.isArray(a)) {
                if (g = this.weakReference(a), null == g.pointers) for (g.pointers = {}, e = ["push", "pop", "shift", "unshift", "sort", "reverse", "splice"], i = 0, j = e.length; j > i; i++) d = e[i], this.stubFunction(a, d);
                if (null == (h = g.pointers)[b] && (h[b] = []), f.call(g.pointers[b], c) < 0) return g.pointers[b].push(c)
            }
        },
        unobserveMutations: function(a, b, c) {
            var d, e;
            return Array.isArray(a && null != a[this.id]) && (d = null != (e = this.weakReference(a).pointers) ? e[b] : void 0) ? d.splice(d.indexOf(c), 1) : void 0
        },
        subscribe: function(a, b, c) {
            var d, e, g = this;
            return d = this.weakReference(a).callbacks, null == d[b] && (d[b] = [], e = a[b], Object.defineProperty(a, b, {
                enumerable: !0,
                get: function() {
                    return e
                },
                set: function(f) {
                    var h, i, j;
                    if (f !== e) {
                        for (e = f, j = d[b], h = 0, i = j.length; i > h; h++) c = j[h], c();
                        return g.observeMutations(f, a[g.id], b)
                    }
                }
            })), f.call(d[b], c) < 0 && d[b].push(c), this.observeMutations(a[b], a[this.id], b)
        },
        unsubscribe: function(a, b, c) {
            var d;
            return d = this.weakmap[a[this.id]].callbacks[b], d.splice(d.indexOf(c), 1), this.unobserveMutations(a[b], a[this.id], b)
        },
        read: function(a, b) {
            return a[b]
        },
        publish: function(a, b, c) {
            return a[b] = c
        }
    }, a.factory = function(b) {
        return b._ = a, b.binders = a.binders, b.components = a.components, b.formatters = a.formatters, b.adapters = a.adapters, b.config = a.config, b.configure = function(b) {
            var c, d;
            null == b && (b = {});
            for (c in b) d = b[c], a.config[c] = d
        }, b.bind = function(b, c, d) {
            var e;
            return null == c && (c = {}), null == d && (d = {}), e = new a.View(b, c, d), e.bind(), e
        }
    }, "object" == typeof exports ? a.factory(exports) : "function" == typeof define && define.amd ? define(["exports"], function(b) {
        return a.factory(this.rivets = b), b
    }) : a.factory(this.rivets = {})
}).call(this);