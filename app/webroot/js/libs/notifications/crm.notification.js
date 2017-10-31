(function($) {
	var l = {
		c : null,
		opts : [],
		easings : {
			jswing : 'jswing',
			easeInQuad : 'easeInQuad',
			easeOutQuad : 'easeOutQuad',
			easeInOutQuad : 'easeInOutQuad',
			easeInCubic : 'easeInCubic',
			easeOutCubic : 'easeOutCubic',
			easeInOutCubic : 'easeInOutCubic',
			easeInQuart : 'easeInQuart',
			easeOutQuart : 'easeOutQuart',
			easeInOutQuart : 'easeInOutQuart',
			easeInQuint : 'easeInQuint',
			easeOutQuint : 'easeOutQuint',
			easeInOutQuint : 'easeInOutQuint',
			easeInSine : 'easeInSine',
			easeOutSine : 'easeOutSine',
			easeInOutSine : 'easeInOutSine',
			easeInExpo : 'easeInExpo',
			easeOutExpo : 'easeOutExpo',
			easeInOutExpo : 'easeInOutExpo',
			easeInCirc : 'easeInCirc',
			easeOutCirc : 'easeOutCirc',
			easeInOutCirc : 'easeInOutCirc',
			easeInElastic : 'easeInElastic',
			easeOutElastic : 'easeOutElastic',
			easeInOutElastic : 'easeInOutElastic',
			easeInBack : 'easeInBack',
			easeOutBack : 'easeOutBack',
			easeInOutBack : 'easeInOutBack',
			easeInBounce : 'easeInBounce',
			easeOutBounce : 'easeOutBounce',
			easeInOutBounce : 'easeInOutBounce'
		},
		animates : {
			flipX : {
				i : 'flipInX',
				o : 'flipOutX'
			},
			flipY : {
				i : 'flipInY',
				o : 'flipOutY'
			},
			fadeUp : {
				i : 'fadeInUp',
				o : 'fadeOutUp'
			},
			fadeDown : {
				i : 'fadeInDown',
				o : 'fadeOutDown'
			},
			fadeLeft : {
				i : 'fadeInLeft',
				o : 'fadeOutLeft'
			},
			fadeRight : {
				i : 'fadeInRight',
				o : 'fadeOutRight'
			},
			fadeUpBig : {
				i : 'fadeInUpBig',
				o : 'fadeOutUpBig'
			},
			fadeDownBig : {
				i : 'fadeInDownBig',
				o : 'fadeOutDownBig'
			},
			fadeLeftBig : {
				i : 'fadeInLeftBig',
				o : 'fadeOutLeftBig'
			},
			fadeRightBig : {
				i : 'fadeInRightBig',
				o : 'fadeOutRightBig'
			},
			bounce : {
				i : 'bounceIn',
				o : 'bounceOut'
			},
			bounceUp : {
				i : 'bounceInUp',
				o : 'bounceOutUp'
			},
			bounceDown : {
				i : 'bounceInDown',
				o : 'bounceOutDown'
			},
			bounceLeft : {
				i : 'bounceInLeft',
				o : 'bounceOutLeft'
			},
			bounceRight : {
				i : 'bounceInRight',
				o : 'bounceOutRight'
			},
			rotate : {
				i : 'rotateIn',
				o : 'rotateOut'
			},
			rotateUpLeft : {
				i : 'rotateInUpLeft',
				o : 'rotateOutUpLeft'
			},
			rotateUpRight : {
				i : 'rotateInUpRight',
				o : 'rotateOutUpRight'
			},
			rotateDownLeft : {
				i : 'rotateInDownLeft',
				o : 'rotateOutDownLeft'
			},
			rotateDownRight : {
				i : 'rotateInDownRight',
				o : 'rotateOutDownRight'
			},
			lightSpeed : {
				i : 'lightSpeedIn',
				o : 'lightSpeedOut'
			},
			roll : {
				i : 'rollIn',
				o : 'rollOut'
			}
		},
		types : {
			info : 'info',
			success : 'success',
			error : 'error'
		},
		effects : {
			slide : 'slide',
			fade : 'fade'
		},
		timer : [],
		init : function(b) {
			var c = l.guid();
			b = (b != undefined) ? b : {};
			b = $.extend(true, {
				img : '',
				type : 'success',
				content : '&nbsp;',
				html : true,
				autoClose : true,
				timeOut : 3000,
				position : 'topRight',
				effect : 'slide',
				animate : '',
				easing : 'jswing',
				duration : 300,
				width : 400,
				buttons : [],
				onStart : function(a) {
				},
				onShow : function(a) {
				},
				onClose : function(a) {
				}
			}, b);
			if (l.easings[b.easing] == undefined) {
				b.easing = 'jswing'
			}
			if (l.types[b.type] == undefined) {
				b.type = ''
			}
			if (l.effects[b.effect] == undefined) {
				b.effect = 'slide'
			}
			if (l.c == null) {
				l.c = $('<div></div>').css({
					position : 'fixed',
					zIndex : 999999,
					maxWidth: 400
				});
				switch (b.position) {
				case 'topLeft':
					l.c.css({
						top : '0px',
						left : '0px'
					});
					break;
				case 'topRight':
					l.c.css({
						top : '0px',
						right : '0px'
					});
					break;
				case 'bottomLeft':
					l.c.css({
						bottom : '0px',
						left : '0px'
					});
					break;
				case 'bottomRight':
					l.c.css({
						bottom : '0px',
						right : '0px'
					});
					break
				}
				l.c.width(b.width).appendTo('body')
			} else {
				l.c.attr({
					style : ''
				});
				l.c.css({
					position : 'fixed',
					zIndex : 999999,
					maxWidth: 400
				});
				switch (b.position) {
				case 'topLeft':
					l.c.css({
						top : '0px',
						left : '0px'
					});
					break;
				case 'topRight':
					l.c.css({
						top : '0px',
						right : '0px'
					});
					break;
				case 'bottomLeft':
					l.c.css({
						bottom : '0px',
						left : '0px'
					});
					break;
				case 'bottomRight':
					l.c.css({
						bottom : '0px',
						right : '0px'
					});
					break
				}
				l.c.width(b.width)
			}
			l.opts[c] = b;
			l.create(c);
			return c
		},
		create : function(b) {
			var o, btn_close;
			var c = l.opts[b];
			o = $('<div></div>').attr({
				id : b
			});
			o.css({
				margin : '10px',
				paddingRight : '8px',
				fontSize: '12px',
				display : 'none',
				'box-shadow' : '0 2px 2px rgba(0, 0, 0, 0.4)'
			}).addClass('alert');
			btn_close = $('<button class="close ntf-close" type="button">×</button>');
			switch (c.type) {
			case 'error':
				btn_close.addClass('alert-danger');
				o.addClass('alert-danger');
				break;
			case 'success':
				btn_close.addClass('alert-success');
				o.addClass('alert-success');
				break;
			case 'info':
				btn_close.addClass('alert-info');
				o.addClass('alert-info');
				break
			}
			btn_close.css({
				top : '-5px',
				right : '0px'
			});
			btn_close.data('parent_id', b);
			btn_close.click(function() {
				var a = $(this).data('parent_id');
				clearTimeout(l.timer[a]);
				l.close(a)
			});
			o.append(btn_close);
			var d = $('<table></table>');
			var e = $('<tr></tr>');
			d.append(e);
			if ($.trim(c.img) != '') {
				var f = $('<img />').attr({
					src : c.img
				}).css({
					border : '0px',
					marginRight : '10px',
					marginBottom : '10px'
				});
				e.append($('<td></td>').attr({
					valign : 'top'
				}).append(f))
			}
			var g = $('<td></td>').attr({
				valign : 'top'
			});
			e.append(g);
			if (c.html == true) {
				g.append(c.content)
			} else {
				g.append(jQuery('<div />').html(c.content).text())
			}
			var h = $('<div></div>');
			h.css({
				paddingRight : '20px',
				textAlign: 'left',
				textShadow: 'none'
			}).append(d);
			o.append(h);
			if (c.buttons.length > 0) {
				var j = $('<div></div>');
				j.css({
					textAlign : 'right'
				});
				for ( var i = 0; i < c.buttons.length; i++) {
					var k = $('<input type="button" class="btn"/>');
					if (c.buttons[i].text != undefined) {
						k.attr({
							value : c.buttons[i].text
						})
					}
					if (c.buttons[i].addClass != undefined) {
						k.addClass(c.buttons[i].addClass)
					}
					if (c.buttons[i].click != undefined) {
						k.data('i', i);
						k.click(function() {
							var a = parseInt($(this).data('i'));
							c.buttons[a].click(b)
						})
					}
					j.append(k).append(' ')
				}
				o.append(j)
			}
			o.appendTo(l.c);
			c.onStart(b);
			if (l.animates[c.animate] != undefined) {
				o.addClass('animated ' + l.animates[c.animate].i)
			}
			switch (c.effect) {
			case 'slide':
				o.slideDown(c.duration, c.easing);
				break;
			case 'fade':
				o.fadeIn(c.duration, c.easing);
				break
			}
			c.onShow(b);
			l.timer[b] = setTimeout(function() {
				l.remove(b)
			}, c.timeOut)
		},
		remove : function(a) {
			var b = l.opts[a];
			var o = $('#' + a);
			if (b.autoClose == true && b.buttons.length <= 0) {
				l.close(a)
			}
		},
		close : function(a) {
			var b = l.opts[a];
			var o = $('#' + a);
			b.onClose(a);
			if (l.animates[b.animate] != undefined) {
				o.addClass('animated ' + l.animates[b.animate].o)
			}
			switch (b.effect) {
			case 'slide':
				o.slideUp(b.duration, b.easing, function() {
					$(this).remove()
				});
				break;
			case 'fade':
				o.fadeOut(b.duration, b.easing, function() {
					$(this).remove()
				});
				break
			}
		},
		guid : function() {
			var a = function() {
				return Math.floor(Math.random() * 0x10000).toString(16)
			};
			return (a() + a() + "-" + a() + "-" + a() + "-" + a() + "-" + a()
					+ a() + a())
		}
	};
	$.notification = function(o, a) {
		a = (a != undefined) ? a : '';
		switch (typeof o) {
		case 'object':
			return l.init(o);
			break;
		case 'string':
			switch (o) {
			case 'close':
				l.close(a);
				break
			}
			break
		}
	}
})(jQuery);