(function(App) {
	'use strict';
	App.module("Utilities", {
		initialize: function(moduleName, app, options) {
			App.Util = options;
			App.Util.encode = options.Base64.encode;
			App.Util.decode = options.Base64.decode;
			App.browserPrefix = options.browserPrefix();
			window.debugHXC = function() {
				document.location.href = '?debug=1';
			};
			/* 			var combo = false;
			$(document).on('keydown', function(e) {

				if (e.altKey && e.keyCode === 91 && !combo) { //keyboard shortcut to open dev 
					console.log('has pressed al and command');
					combo = true;
				}

				if (combo && e.keyCode === 73) {
					document.location.href = '?debug=1';
				}
				$(document).on('keyup', function(e) {
					combo = false;
				});
			}); */
		},
		/* Get url parameters */
		getURLParameter: function(name) {
			return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
		},
		/**
		 * Conserve aspect ratio of the orignal region. Useful when shrinking/enlarging
		 * images to fit into a certain area.
		 *
		 * @param {Number} srcWidth Source area width
		 * @param {Number} srcHeight Source area height
		 * @param {Number} maxWidth Fittable area maximum available width
		 * @param {Number} maxHeight Fittable area maximum available height
		 * @return {Object} { width, heigth }
		 */
		calculateAspectRatioFit: function(srcWidth, srcHeight, maxWidth, maxHeight) {

			var ratio = Math.min(maxWidth / srcWidth, maxHeight / srcHeight);

			return {
				width: srcWidth * ratio,
				height: srcHeight * ratio
			};
		},
		browserPrefix: function() {
			var styles = window.getComputedStyle(document.documentElement, ''),
				pre = (Array.prototype.slice
					.call(styles)
					.join('')
					.match(/-(moz|webkit|ms)-/) || (styles.OLink === '' && ['', 'o'])
				)[1],
				dom = ('WebKit|Moz|MS|O').match(new RegExp('(' + pre + ')', 'i'))[1];
			return {
				dom: dom,
				lowercase: pre,
				css: '-' + pre + '-',
				js: pre[0].toUpperCase() + pre.substr(1)
			};
		},
		waitForModel: function(model, callback) {
			var checkReady = function() {
				setTimeout(function() {
					if (typeof App.Model[model] !== 'function') {
						callback();
					} else {
						checkReady();
					}
				}, 500);
			};
			checkReady();
		},
		capitalizeWords: function(str) {
			var me = this;
			return str.replace(/\w\S*/g, function(txt) {
				return me.removeDashes(txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase());
			});
		},
		waitTillReady: function(element, callback) {
			var wait = $.Deferred();
			var checkSelector = setInterval(function() {
				if ($(element).length) {
					wait.resolve();
					clearInterval(checkSelector);
				}
			}, 100);

			wait.done(function() {
				//callback();
				callback.call($(element)); //set context to element
			});
		},
		contains: function(string, key) {
			return string.indexOf(key) > -1;
		},
		getRandomInt: function(min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
		},
		waitTillDefined: function(variable, callback) {
			var wait = $.Deferred();
			var checkDefined = setInterval(function() {
				if (typeof window[variable] !== 'undefined') {
					wait.resolve();
					clearInterval(checkDefined);
				}
			}, 100);

			wait.done(function() {
				callback();
			});
		},
		afterPageRender: function(callback) {
			this.waitTillReady('#explore_content_sotd', function() {
				callback();
			});
		},
		seoFriendlyDashes: function(string) {
			//string = string.replace(/\W+/g, " ");
			string = string.split('%20').join(' ').trim();
			string = string.replace(/[^a-zA-Z0-9 ]/g, "");
			string = string.split(' ').join('-');
			return string;
		},
		removeDashes: function(string) {
			string = string.split('-').join(' ');
			return string.split('+').join(' ');
		},
		absolutePath: function(href) {
			var link = document.createElement("a");
			link.href = href;
			return (link.protocol + "//" + link.host + link.pathname + link.search + link.hash);
		},
		fixMixedUni: function(x){
			//found at http://stackoverflow.com/questions/7885096/how-do-i-decode-a-string-with-escaped-unicode
			// COOL HACK ALSO WORKS: unescape(JSON.parse('"http\\u00253A\\u00252F\\u00252Fexample.com"'));
			var r = /\\u([\d\w]{4})/gi;
			x = x.replace(r, function (match, grp) {
				return String.fromCharCode(parseInt(grp, 16));
			});
			x = unescape(x);
			return x;
		},
		stripAfter: function(string, key){
			var n = string.indexOf(key);
			return string.substring(0, n !== -1 ? n : string.length);
		},
		getFilename: function(string){
			return string.replace(/^.*(\\|\/|\:)/, '');
		},
		Base64: {
			/* Usage:
				// Encode the String
				var encodedString = Base64.encode(string);
				console.log(encodedString); // Outputs: "SGVsbG8gV29ybGQh"

				// Decode the String
				var decodedString = Base64.decode(encodedString);
				console.log(decodedString); // Outputs: "Hello World!"
			*/

			/* jshint ignore:start */
			_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
			encode: function(e) {
				var t = "";
				var n, r, i, s, o, u, a;
				var f = 0;
				e = this.Base64._utf8_encode(e);
				while (f < e.length) {
					n = e.charCodeAt(f++);
					r = e.charCodeAt(f++);
					i = e.charCodeAt(f++);
					s = n >> 2;
					o = (n & 3) << 4 | r >> 4;
					u = (r & 15) << 2 | i >> 6;
					a = i & 63;
					if (isNaN(r)) {
						u = a = 64
					} else if (isNaN(i)) {
						a = 64
					}
					t = t + this.Base64._keyStr.charAt(s) + this.Base64._keyStr.charAt(o) + this.Base64._keyStr.charAt(u) + this.Base64._keyStr.charAt(a)
				}
				return t
			},
			decode: function(e) {
				var t = "";
				var n, r, i;
				var s, o, u, a;
				var f = 0;
				e = e.replace(/[^A-Za-z0-9\+\/\=]/g, "");
				while (f < e.length) {
					s = this.Base64._keyStr.indexOf(e.charAt(f++));
					o = this.Base64._keyStr.indexOf(e.charAt(f++));
					u = this.Base64._keyStr.indexOf(e.charAt(f++));
					a = this.Base64._keyStr.indexOf(e.charAt(f++));
					n = s << 2 | o >> 4;
					r = (o & 15) << 4 | u >> 2;
					i = (u & 3) << 6 | a;
					t = t + String.fromCharCode(n);
					if (u != 64) {
						t = t + String.fromCharCode(r)
					}
					if (a != 64) {
						t = t + String.fromCharCode(i)
					}
				}
				t = this.Base64._utf8_decode(t);
				return t
			},
			_utf8_encode: function(e) {
				e = e.replace(/\r\n/g, "\n");
				var t = "";
				for (var n = 0; n < e.length; n++) {
					var r = e.charCodeAt(n);
					if (r < 128) {
						t += String.fromCharCode(r)
					} else if (r > 127 && r < 2048) {
						t += String.fromCharCode(r >> 6 | 192);
						t += String.fromCharCode(r & 63 | 128)
					} else {
						t += String.fromCharCode(r >> 12 | 224);
						t += String.fromCharCode(r >> 6 & 63 | 128);
						t += String.fromCharCode(r & 63 | 128)
					}
				}
				return t
			},
			_utf8_decode: function(e) {
				var t = "",
					n = 0,
					c1 = 0,
					c2 = 0,
					c3 = 0,
					r = 0;
				//var r = c1 = c2 = 0; //caused error c2 undefined
				while (n < e.length) {
					r = e.charCodeAt(n);
					if (r < 128) {
						t += String.fromCharCode(r);
						n++
					} else if (r > 191 && r < 224) {
						c2 = e.charCodeAt(n + 1);
						t += String.fromCharCode((r & 31) << 6 | c2 & 63);
						n += 2
					} else {
						c2 = e.charCodeAt(n + 1);
						c3 = e.charCodeAt(n + 2);
						t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
						n += 3
					}
				}
				return t
			}
			/* jshint ignore:end */
		},
		isInViewport: function(el, win) {
			if(typeof el === 'undefined'){
				return;
			}
			var top = el.offsetTop,
				left = el.offsetLeft,
				width = el.offsetWidth,
				height = el.offsetHeight;

			while (el.offsetParent) {
				el = el.offsetParent;
				top += el.offsetTop;
				left += el.offsetLeft;
			}
			if(typeof win === 'undefined'){
				return (top >= window.pageYOffset && left >= window.pageXOffset && (top + height) <= (window.pageYOffset + window.innerHeight) && (left + width) <= (window.pageXOffset + window.innerWidth));
			}else{
				return (top >= win.scrollTop() && left >= win.scrollLeft() && (top + height) <= (win.scrollTop() + win.innerHeight()) && (left + width) <= (win.scrollLeft() + win.innerWidth()));
			}
		}
	});
})(window.App);