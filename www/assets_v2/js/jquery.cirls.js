/*
*Name:ChangYuan.Liu
*Date:2015-10-28
*/

//准时到课
(function($) {
	$.fn.svgCircle = function(i) {
		i = $.extend({
			parent: null,
			w: 75,
			R: 30,
			sW: 20,
			color: ["#000", "#000"],
			perent: [64, 64],
			speed: 0,
			delay: 1000
		}, i);
		return this.each(function() {
			var e = i.parent;
			if (!e) return false;
			var w = i.w;
			var r = Raphael(e, w, w),
				R = i.R,
				init = true,
				param = {
					stroke: "#0088cc"
				},
				hash = document.location.hash,
				marksAttr = {
					fill: hash || "#444",
					stroke: "none"
				};
			r.customAttributes.arc = function(b, c, R) {
				var d = 360 / c * b,
					a = (90 - d) * Math.PI / 180,
					x = w / 2 + R * Math.cos(a),
					y = w / 2 - R * Math.sin(a),
					color = i.color,
					path;
				if (c == b) {
					path = [
						["M", w / 2, w / 2 - R],
						["A", R, R, 0, 1, 1, w / 2 - 0.01, w / 2 - R]
					]
				} else {
					path = [
						["M", w / 2, w / 2 - R],
						["A", R, R, 0, +(d > 180), 1, x, y]
					]
				}
				return {
					path: path
				}
			};
			var f = r.path().attr({
				stroke: "#5ca280",
				"stroke-width": i.sW
			}).attr({
				arc: [110, 110, R]
			});
			var g = r.path().attr({
				stroke: "#ff0000",
				"stroke-width": i.sW
			}).attr(param).attr({
				arc: [0.01, i.speed, R]
			});
			var h;
			if (i.perent[1] > 0) {
				setTimeout(function() {
					g.animate({
						stroke: i.color[1],
						arc: [i.perent[1], 100, R]
					}, 900, ">")
				}, i.delay)
			} else {
				g.hide()
			}
		})
	}
})(jQuery);
$(function() {
	var c = $('.statistics-cirl-solid-one');
	animateEle();
	$(window).scroll(function() {
		animateEle()
	});

	function animateEle() {
		var b = {
			top: $(window).scrollTop(),
			bottom: $(window).scrollTop() + $(window).height()
		};
		c.each(function() {
			if (b.top <= $(this).offset().top && b.bottom >= $(this).offset().top && !$(this).data('bPlay')) {
				$(this).data('bPlay', true);
				var a = $(this).find('font').text().replace(/\%/, '');
				if ($(this).find("font").text() !== "0%") {
					$(this).svgCircle({
						parent: $(this)[0],
						w: 64,
						R: 30,
						sW: 5,
						color: ["#d7ffe6", "#d7ffe6", "#d7ffe6"],
						perent: [100, a],
						speed: 150,
						delay: 400
					})
				}
				if ($(this).find("font").text() == "0%") {
					$(this).find("font").css("color", "#a9a9a9");
					$(this).svgCircle({
						parent: $(this)[0],
						w: 64,
						R: 30,
						sW: 5,
						color: ["#d1d1d1", "#d1d1d1", "#d1d1d1"],
						perent: [100, a],
						speed: 150,
						delay: 400
					})
				}
			}
		})
	}
});

//答题正确率
(function($) {
	$.fn.svgCircle = function(i) {
		i = $.extend({
			parent: null,
			w: 75,
			R: 30,
			sW: 20,
			color: ["#000", "#000"],
			perent: [64, 64],
			speed: 0,
			delay: 1000
		}, i);
		return this.each(function() {
			var e = i.parent;
			if (!e) return false;
			var w = i.w;
			var r = Raphael(e, w, w),
				R = i.R,
				init = true,
				param = {
					stroke: "#0088cc"
				},
				hash = document.location.hash,
				marksAttr = {
					fill: hash || "#444",
					stroke: "none"
				};
			r.customAttributes.arc = function(b, c, R) {
				var d = 360 / c * b,
					a = (90 - d) * Math.PI / 180,
					x = w / 2 + R * Math.cos(a),
					y = w / 2 - R * Math.sin(a),
					color = i.color,
					path;
				if (c == b) {
					path = [
						["M", w / 2, w / 2 - R],
						["A", R, R, 0, 1, 1, w / 2 - 0.01, w / 2 - R]
					]
				} else {
					path = [
						["M", w / 2, w / 2 - R],
						["A", R, R, 0, +(d > 180), 1, x, y]
					]
				}
				return {
					path: path
				}
			};
			var f = r.path().attr({
				stroke: "#bea039",
				"stroke-width": i.sW
			}).attr({
				arc: [110, 110, R]
			});
			var g = r.path().attr({
				stroke: "#ff0000",
				"stroke-width": i.sW
			}).attr(param).attr({
				arc: [0.01, i.speed, R]
			});
			var h;
			if (i.perent[1] > 0) {
				setTimeout(function() {
					g.animate({
						stroke: i.color[1],
						arc: [i.perent[1], 100, R]
					}, 900, ">")
				}, i.delay)
			} else {
				g.hide()
			}
		})
	}
})(jQuery);
$(function() {
	var c = $('.statistics-cirl-solid-two');
	animateEle();
	$(window).scroll(function() {
		animateEle()
	});

	function animateEle() {
		var b = {
			top: $(window).scrollTop(),
			bottom: $(window).scrollTop() + $(window).height()
		};
		c.each(function() {
			if (b.top <= $(this).offset().top && b.bottom >= $(this).offset().top && !$(this).data('bPlay')) {
				$(this).data('bPlay', true);
				var a = $(this).find('font').text().replace(/\%/, '');
				if ($(this).find("font").text() !== "0%") {
					$(this).svgCircle({
						parent: $(this)[0],
						w: 64,
						R: 30,
						sW: 5,
						color: ["#ebf265", "#ebf265", "#ebf265"],
						perent: [100, a],
						speed: 150,
						delay: 400
					})
				}
				if ($(this).find("font").text() == "0%") {
					$(this).find("font").css("color", "#a9a9a9");
					$(this).svgCircle({
						parent: $(this)[0],
						w: 64,
						R: 30,
						sW: 5,
						color: ["#d1d1d1", "#d1d1d1", "#d1d1d1"],
						perent: [100, a],
						speed: 150,
						delay: 400
					})
				}
			}
		})
	}
});

//作业完成率
(function($) {
	$.fn.svgCircle = function(i) {
		i = $.extend({
			parent: null,
			w: 75,
			R: 30,
			sW: 20,
			color: ["#000", "#000"],
			perent: [64, 64],
			speed: 0,
			delay: 1000
		}, i);
		return this.each(function() {
			var e = i.parent;
			if (!e) return false;
			var w = i.w;
			var r = Raphael(e, w, w),
				R = i.R,
				init = true,
				param = {
					stroke: "#0088cc"
				},
				hash = document.location.hash,
				marksAttr = {
					fill: hash || "#444",
					stroke: "none"
				};
			r.customAttributes.arc = function(b, c, R) {
				var d = 360 / c * b,
					a = (90 - d) * Math.PI / 180,
					x = w / 2 + R * Math.cos(a),
					y = w / 2 - R * Math.sin(a),
					color = i.color,
					path;
				if (c == b) {
					path = [
						["M", w / 2, w / 2 - R],
						["A", R, R, 0, 1, 1, w / 2 - 0.01, w / 2 - R]
					]
				} else {
					path = [
						["M", w / 2, w / 2 - R],
						["A", R, R, 0, +(d > 180), 1, x, y]
					]
				}
				return {
					path: path
				}
			};
			var f = r.path().attr({
				stroke: "#bea039",
				"stroke-width": i.sW
			}).attr({
				arc: [110, 110, R]
			});
			var g = r.path().attr({
				stroke: "#ff0000",
				"stroke-width": i.sW
			}).attr(param).attr({
				arc: [0.01, i.speed, R]
			});
			var h;
			if (i.perent[1] > 0) {
				setTimeout(function() {
					g.animate({
						stroke: i.color[1],
						arc: [i.perent[1], 100, R]
					}, 900, ">")
				}, i.delay)
			} else {
				g.hide()
			}
		})
	}
})(jQuery);
$(function() {
	var c = $('.statistics-cirl-solid-third');
	animateEle();
	$(window).scroll(function() {
		animateEle()
	});

	function animateEle() {
		var b = {
			top: $(window).scrollTop(),
			bottom: $(window).scrollTop() + $(window).height()
		};
		c.each(function() {
			if (b.top <= $(this).offset().top && b.bottom >= $(this).offset().top && !$(this).data('bPlay')) {
				$(this).data('bPlay', true);
				var a = $(this).find('font').text().replace(/\%/, '');
				if ($(this).find("font").text() !== "0%") {
					$(this).svgCircle({
						parent: $(this)[0],
						w: 64,
						R: 30,
						sW: 5,
						color: ["#a8ffc7", "#a8ffc7", "#a8ffc7"],
						perent: [100, a],
						speed: 150,
						delay: 400
					})
				}
				if ($(this).find("font").text() == "0%") {
					$(this).find("font").css("color", "#a9a9a9");
					$(this).svgCircle({
						parent: $(this)[0],
						w: 64,
						R: 30,
						sW: 5,
						color: ["#d1d1d1", "#d1d1d1", "#d1d1d1"],
						perent: [100, a],
						speed: 150,
						delay: 400
					})
				}
			}
		})
	}
});


	//作业完成率
(function($) {
	$.fn.svgCircle = function(i) {
		i = $.extend({
			parent: null,
			w: 85,
			R: 85,
			sW: 20,
			color: ["#000", "#000"],
			perent: [85, 85],
			speed: 0,
			delay: 1000
		}, i);
		return this.each(function() {
			var e = i.parent;
			if (!e) return false;
			var w = i.w;
			var r = Raphael(e, w, w),
				R = i.R,
				init = true,
				param = {
					stroke: "#0088cc"
				},
				hash = document.location.hash,
				marksAttr = {
					fill: hash || "#444",
					stroke: "none"
				};
			r.customAttributes.arc = function(b, c, R) {
				var d = 360 / c * b,
					a = (90 - d) * Math.PI / 180,
					x = w / 2 + R * Math.cos(a),
					y = w / 2 - R * Math.sin(a),
					color = i.color,
					path;
				if (c == b) {
					path = [
						["M", w / 2, w / 2 - R],
						["A", R, R, 0, 1, 1, w / 2 - 0.01, w / 2 - R]
					]
				} else {
					path = [
						["M", w / 2, w / 2 - R],
						["A", R, R, 0, +(d > 180), 1, x, y]
					]
				}
				return {
					path: path
				}
			};
			var f = r.path().attr({
				stroke: "#a9a9a9",
				"stroke-width": i.sW
			}).attr({
				arc: [110, 110, R]
			});
			var g = r.path().attr({
				stroke: "#ff0000",
				"stroke-width": i.sW
			}).attr(param).attr({
				arc: [0.01, i.speed, R]
			});
			var h;
			if (i.perent[1] > 0) {
				setTimeout(function() {
					g.animate({
						stroke: i.color[1],
						arc: [i.perent[1], 100, R]
					}, 900, ">")
				}, i.delay)
			} else {
				g.hide()
			}
		})
	}
})(jQuery);
$(function() {
	var c = $('.statistics-cirl-solid-third03');
	animateEle();
	$(window).scroll(function() {
		animateEle()
	});

	function animateEle() {
		var b = {
			top: $(window).scrollTop(),
			bottom: $(window).scrollTop() + $(window).height()
		};
		c.each(function() {
			if (b.top <= $(this).offset().top && b.bottom >= $(this).offset().top && !$(this).data('bPlay')) {
				$(this).data('bPlay', true);
				var a = $(this).find('font').text().replace(/\%/, '');
				if ($(this).find("font").text() !== "0%") {
					$(this).svgCircle({
						parent: $(this)[0],
						w: 150,
						R: 65,
						sW: 6,
						color: ["#a8ffc7", "#198fee", "#a8ffc7"],
						perent: [100, a],
						speed: 150,
						delay: 400
					})
				}
				if ($(this).find("font").text() == "0%") {
					$(this).find("font").css("color", "#198fee");
					$(this).svgCircle({
						parent: $(this)[0],
						w: 64,
						R: 30,
						sW: 5,
						color: ["#d1d1d1", "#d1d1d1", "#d1d1d1"],
						perent: [100, a],
						speed: 150,
						delay: 400
					})
				}
			}
		})
	}
});

var pie = {
	run: function(a) {
		if (!a.id) throw new Error("must be canvas id.");
		var b = document.getElementById(a.id),
			ctx;
		if (b && (ctx = b.getContext("2d"))) {
			b.width = b.height = "200";
			var c = function() {};
			var d = a.onBefore || c;
			var e = a.onAfter || c;
			d(ctx);
			ctx.fillStyle = a.color || '#ff0000';
			var f = a.step || 1;
			var g = a.delay || 10;
			var i = 0,
				rage = 360 * (a.percent || 0);
			var h = -Math.PI * 0.5;
			var j = function() {
					i = i + f;
					if (i <= rage) {
						ctx.beginPath();
						ctx.moveTo(100, 100);
						ctx.arc(100, 100, 100, h, Math.PI * 2 * (i / 360) + h);
						ctx.fill();
						setTimeout(j, g)
					} else {
						e(ctx)
					}
				};
			j()
		}
	}
};