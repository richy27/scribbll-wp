jQuery(document).ready(function($) {
	//set body width for IE8
	if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
		var ieversion = new Number(RegExp.$1);
		if (ieversion == 8) {
			$('body').css('max-width', $(window).width());
		}
		if (ieversion < 10) {
			$('.usercp-pins input, .usercp-pins textarea, .usercp-wrapper input, .usercp-wrapper textarea').placeholder();	
		}
	}

	//masonry
	var $masonry = $('#masonry');
	var user_profile_follow = $('#user-profile-follow');
	var user_profile_boards = $('#user-profile-boards');
	var user_notifications = $('#user-notifications');

	if (obj_pinclone.infinitescroll != 'disable') {
		$('#navigation').css({'visibility':'hidden', 'height':'1px'});
	}
		
	if ($masonry.length) {
		$('#ajax-loader-masonry').hide();
		if ($(document).width() <= 480) {
			$masonry.imagesLoaded(function() {
				$masonry.masonry({
					itemSelector : '.thumb',
					isFitWidth: false,
					transitionDuration: 0
				}).css('visibility', 'visible');
			});
		} else {
			$masonry.masonry({
				itemSelector : '.thumb',
				isFitWidth: true,
				transitionDuration: 0
			}).css('visibility', 'visible');
		}
	}
	
	$(window).resize(function() {
		if ($masonry.length && $(document).width() <= 480) {
			$masonry.masonry('reloadItems').masonry('layout');
		}
		
		if (user_profile_follow.length && $(document).width() <= 480) {
			$masonry.masonry('reloadItems').masonry('layout');
		}
	});

	//tooltip
	$(document).tooltip({
		selector: '[rel=tooltip]'
	});
	
	//login box popup
	//append form to loginbox such that wsl also works
	if (obj_pinclone.u == '0') {
		$('#loginbox').data('content', $('#loginbox').data('content') + 
			'<small><div class="error-msg-loginbox"></div></small>\
			<form name="loginform_header" id="loginform_header" method="post">\
				<label>' + obj_pinclone.__Username + '<br />\
				<input type="text" name="log" id="log" value="" tabindex="0" /></label>\
				<label>' + obj_pinclone.__Password + '\
				(<a href="' + obj_pinclone.home_url + '/login-lpw/" tabindex="-1">' + obj_pinclone.__Forgot + '</a>)\
				<input type="password" name="pwd" id="pwd" value="" tabindex="0" /></label>\
				<input type="submit" class="pull-left btn btn-primary" name="wp-submit" id="wp-submit" value="' + obj_pinclone.__Login + '" tabindex="0" />\
				<div class="ajax-loader-loginbox pull-left ajax-loader hide"></div>\
				<span id="loginbox-register" class="pull-left">' + obj_pinclone.__or + ' <a href="' + obj_pinclone.home_url + '/register/" tabindex="0">' + obj_pinclone.__RegisterAccount + '</a></span>' + obj_pinclone.__LoginForm + '\
			</form>\
		');
		
		$('#loginbox').popover({
			html: 'true',
			placement: 'bottom',
			title: obj_pinclone.__Welcome + '<button class="close" type="button" id="loginbox-close">x</button>'
		});
		
		$('#loginbox').on('click', function() {
			return false;
		});
		
		$(document).on('click', '#loginbox-close', function() {
			$('#loginbox').popover('hide');
		});
		
		//login box hide when click outside
		$(document).on('click', function(e) {
			if(!$(e.target).closest('.popover').length) {
				$('#loginbox').popover('hide');
			}
		});
		
		//login box process
		$(document).on('submit', '#loginform_header, #popup-login-form', function() {
			$('#loginform_header .ajax-loader-loginbox').show();
			
			$('.error-msg-loginbox').hide();
			if ($('#log').val() == '' || $('#pwd').val() == '') {
				$('.error-msg-loginbox').html('<div class="alert"><strong>' + obj_pinclone.__pleaseenterbothusernameandpassword  + '</strong></div>').fadeIn();
				$('#loginform_header .ajax-loader-loginbox').hide();
				return false;
			}
			
			var data = {
				action: 'pinclone-ajax-login',
				nonce: obj_pinclone.nonce,
				log: $('#loginform_header #log').val(),
				pwd: $('#loginform_header #pwd').val()
			};
		
			$.ajax({
				type: 'post',
				url: obj_pinclone.ajaxurl,
				data: data,
				success: function(data) {
					if (data == 'error') {
						$('.error-msg-loginbox').html('<div class="alert"><strong>' + obj_pinclone.__incorrectusernamepassword  + '</strong></div>').fadeIn();
						$('#loginform_header .ajax-loader-loginbox').hide();
						return false;			
					} else {
						window.location.reload();
					}
				}
			});
		
			return false;
		});
		
		//login box (popup version)
		$(document).on('click', '#popup-login-close', function() {
			$('#popup-login-overlay').hide();
			$('#popup-login-box').modal('hide');
		});
		
		$(document).on('click', '#popup-login-overlay', function() {
			$('#popup-login-overlay').hide();
			$('#popup-login-box').modal('hide');
		});
	}	

	//notification popup	
	/*$('#user-notifications-count').on('click', function() {
		if (!$(this).next('div.popover').length) {
			$('#user-notifications-count')
			.popover({
				content: '<div class="ajax-loader"></div>',
				html: 'true',
				placement: 'bottom',
				trigger: 'manual',
				title: '<strong>' + obj_pinclone.__NotificationsLatest30 + ' (<a href="' + obj_pinclone.home_url + '/notifications/">' + obj_pinclone.__SeeAll + '</a>)</strong> <button class="close" type="button" id="user-notifications-count-close">x</button>'
			})
			.popover('show');
			
			$('<div>').load(obj_pinclone.home_url + '/notifications/ #user-notifications-table', function() {
				$('#user-notifications-count').next('.popover').children('.popover-content').html($(this).html());
			});
		} else {
			$('#user-notifications-count').popover('hide');
			$('#user-notifications-count a').text('0').removeClass('user-notifications-count-nth');
		}
		return false;
	});
	
	$(document).on('click', '#user-notifications-count-close', function() {
		$('#user-notifications-count').popover('hide');
		$('#user-notifications-count a').text('0').removeClass('user-notifications-count-nth');
	});
	
	//notification hide when click outside
	$(document).on('click', function(e) {
		if(!$(e.target).closest('.popover').length) {
			$('#user-notifications-count').popover('hide');
			$('#user-notifications-count a').text('0').removeClass('user-notifications-count-nth');
		}
	});
	*/
	
	//scroll to top
	var $scrolltotop = $('#scrolltotop');

	$(window).scroll(function() {
		var iOS = /(iPad|iPhone|iPod)/g.test(navigator.userAgent);
		
		if (!iOS) {
			if ($(this).scrollTop() > 100) {
				$scrolltotop.slideDown('fast');
			} else {
				$scrolltotop.slideUp('fast');
			}
		}
	});

	$scrolltotop.click(function() {
		$('body,html').animate({
			scrollTop: 0
		}, 'fast');
		return false;
	});

	//likes for frontpage, lightbox, posts
	$(document).on('click', '.pinclone-like', function() {
		if (obj_pinclone.u != '0') {
			var like = $(this);
			var	post_id = like.data('post_id');

			like.attr('disabled', 'disabled');

			if (!like.hasClass('disabled')) {
				var data = {
					action: 'pinclone-like',
					nonce: obj_pinclone.nonce,
					post_id: post_id,
					post_author: like.data('post_author'),
					pinclone_like: 'like'
				};

				$.ajax({
					type: 'post',
					url: obj_pinclone.ajaxurl,
					data: data,
					success: function(count) {
						like.addClass('disabled').removeAttr('disabled');
						$('#post-' + post_id + ' .pinclone-like').addClass('disabled');
						
						if (count == 1) {
							if ($('#post-repins').length) {
								$('#post-repins').before('<div class="post-likes"><div class="post-likes-wrapper"><h4>' + obj_pinclone.__Likes + '</h4><div class="post-likes-avatar"><a href="' + obj_pinclone.home_url + '/' + obj_pinclone.user_rewrite + '/' + obj_pinclone.ul + '/" rel="tooltip" title="' + obj_pinclone.ui + '">' + obj_pinclone.avatar48 + '</a></div></div></div>');
							} else {
								$('#post-embed-overlay').before('<div class="post-likes"><div class="post-likes-wrapper"><h4>' + obj_pinclone.__Likes + '</h4><div class="post-likes-avatar"><a href="' + obj_pinclone.home_url + '/' + obj_pinclone.user_rewrite + '/' + obj_pinclone.ul + '/" rel="tooltip" title="' + obj_pinclone.ui + '">' + obj_pinclone.avatar48 + '</a></div></div></div>');
							}
							$('#likes-count-'+post_id).removeClass('hide').text('1 ' + obj_pinclone.__like);
							if($('#masonry').length) {
								$('#masonry').masonry('reloadItems').masonry('layout');
							}
						} else {
							$('.post-likes-avatar').append('<a id=likes-' + obj_pinclone.u +  ' href="' + obj_pinclone.home_url + '/' + obj_pinclone.user_rewrite + '/' + obj_pinclone.ul + '/" rel="tooltip" title="' + obj_pinclone.ui + '">' + obj_pinclone.avatar48 + '</a>');
							$('#likes-count-'+post_id).text(count + ' ' + obj_pinclone.__likes);
						}
					}
				});
			} else {
				var data = {
					action: 'pinclone-like',
					nonce: obj_pinclone.nonce,
					post_id: post_id,
					pinclone_like: 'unlike'
				};

				$.ajax({
					type: 'post',
					url: obj_pinclone.ajaxurl,
					data: data,
					success: function(count) {
						like.removeClass('disabled').removeAttr('disabled');
						$('#post-' + post_id + ' .pinclone-like').removeClass('disabled');

						if (count == 0) {
							$('.post-likes').remove();
							$('#likes-count-'+post_id).addClass('hide').text('');
							if($('#masonry').length) {
								$('#masonry').masonry('reloadItems').masonry('layout');
							}
						} else if (count == 1) {
							$('#likes-' + obj_pinclone.u).remove();
							$('#likes-count-'+post_id).text('1 ' + obj_pinclone.__like);
						} else {
							$('#likes-' + obj_pinclone.u).remove();
							$('#likes-count-'+post_id).text(count + ' ' + obj_pinclone.__likes);
						}
					}
				});
			}
			return false;
		} else {
			if ($(document).width() <= 480) {
				topAlertMsg('<a href="' + obj_pinclone.login_url + '">' + obj_pinclone.__Pleaseloginorregisterhere + '</a>');
			} else {
				if ($('#loginbox-wrapper .popover').length) {
					$('#loginbox').popover('hide');
				}
				$('#popup-login-overlay').show();
				$('#popup-login-box').modal({backdrop:false, keyboard:false});
			}
			return false;
		}
	});
	
	//repin show form for frontpage, lightbox, posts
	$(document).on('click', '.pinclone-repin', function() {
		if (obj_pinclone.u != '0') {
			var repin = $(this);
			var post_id = repin.data('post_id');
			var post_description = $('#masonry #post-' + post_id + ' .post-title').data('title');
			var post_content = $('#masonry #post-' + post_id + ' .post-title').data('content');
			var post_tags = $('#post-' + post_id + ' .post-title').data('tags');
			var post_price = $('#post-' + post_id + ' .post-title').data('price');
			
			if ($('#post-lightbox').css('display') == 'block') {				
				if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
					var ieversion = new Number(RegExp.$1);
					if (ieversion > 10) {
						window.history.back();
					}
				} else {
						window.history.back();
				}
			}
			
			//use ajax fetch boards if user created a new board
			if ($('#newboard').length) {
				//populate board field
				var data = {
					action: 'pinclone-repin-board-populate',
					nonce: obj_pinclone.nonce
				};
				
				$.ajax({
					type: 'post',
					url: obj_pinclone.ajaxurl,
					data: data,
					success: function(data) {
						$('#board').remove();
						$('#repinform-add-new-board').after(data);

						//when in single-pin.php
						if (!post_description) {
							post_description = $('#post-' + post_id + ' .post-title').data('title');
							post_content = $('#post-' + post_id + ' .post-title').data('content');
						}
						
						$('body').css('overflow', 'auto');
						$('#post-lightbox').modal('hide');
						$('#post-repin-box .post-repin-box-photo').html('<img src="' + $('#post-' + post_id + ' .featured-thumb').attr('src') + '" />');
						$('#post-repin-box textarea#pin-title').val(post_description);
						$('#post-repin-box textarea#pin-content').val(post_content);
						$('#post-repin-box #tags').val(post_tags);
						$('#post-repin-box #price').val(post_price);
						$('#repinform-add-new-board').text(obj_pinclone.__addnewboard);
						$('#repinform #board-add-new').val('').hide();
						$('#repinform #board-add-new-category').val('-1').hide();
						$('#repinform #board').show();
						$('#repinform #repin-post-id').val(post_id);
						$('#repinnedmsg, .repinnedmsg-share').hide();
						$('#repinform').show();
						
						scrolltop = $(window).scrollTop();
						$('#post-repin-box').css('top', scrolltop+10);
						$(window).scrollTop(scrolltop);
						$('#post-repin-box').modal();
						$('#post-repin-box textarea#pin-title').focus().select();
					}
				});
				
				$('#newboard').remove();
			} else {
				//when in single-pin.php
				if (!post_description) {
					post_description = $('#post-' + post_id + ' .post-title').data('title');
					post_content = $('#post-' + post_id + ' .post-title').data('content');
				} else {
					$('#video-embed').remove(); //hide youtube player if not in single-pin.php
				}
				
				$('body').css('overflow', 'auto');
				$('#post-lightbox').modal('hide');
				
				//ajax fetch boards for first time
				if ($('#post-repin-box').length == 0){
					//populate board field
					var data = {
						action: 'pinclone-repin-board-populate',
						nonce: obj_pinclone.nonce
					};
					
					$.ajax({
						type: 'post',
						url: obj_pinclone.ajaxurl,
						data: data,
						success: function(data) {
							$('body').append('\
								<div class="modal hide" id="post-repin-box" role="dialog" aria-hidden="true">\
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>\
									<div class="clearfix"></div>\
									<div class="post-repin-box-photo"></div>\
									<form id="repinform">\
										' + obj_pinclone.description_fields + '\
										' + obj_pinclone.description_instructions + '\
										' + obj_pinclone.tags_html + '\
										' + obj_pinclone.price_html + '\
										<a href="#" id="repinform-add-new-board" tabindex="-1" class="btn btn-mini pull-right">' + obj_pinclone.__addnewboard + '</a>\
										' + data + '\
										<input type="text" class="board-add-new" id="board-add-new" placeholder="' + obj_pinclone.__enternewboardtitle + '" />\
										' + obj_pinclone.categories + '\
										<input type="hidden" value="" name="repin-post-id" id="repin-post-id" />\
										<div class="clearfix"></div>\
										<input class="btn btn-primary" type="submit" name="pinit" id="pinit" value="' + obj_pinclone.__pinit + '" /> \
										<span id="repin-status"></span>\
									</form>\
								</div>\
							');
							
							if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
								var ieversion = new Number(RegExp.$1);
								if (ieversion < 10) {
									$('#post-repin-box input').placeholder();
								}
							}							
							
							$('#post-repin-box .post-repin-box-photo').html('<img src="' + $('#post-' + post_id + ' .featured-thumb').attr('src') + '" />');
							$('#post-repin-box textarea#pin-title').val(post_description);
							$('#post-repin-box textarea#pin-content').val(post_content);
							$('#post-repin-box #tags').val(post_tags);
							$('#post-repin-box #price').val(post_price);
							$('#repinform-add-new-board').text(obj_pinclone.__addnewboard);
							$('#repinform #board-add-new').val('').hide();
							$('#repinform #board-add-new-category').val('-1').hide();
							$('#repinform #board').show();
							$('#repinform #repin-post-id').val(post_id);
							$('#repinnedmsg, .repinnedmsg-share').hide();
							$('#repinform').show();

							scrolltop = $(window).scrollTop();
							$('#post-repin-box').css('top', scrolltop+10);
							$(window).scrollTop(scrolltop);
							$('#post-repin-box').modal();
							$('#post-repin-box textarea#pin-title').focus().select();
							
							//autocomplete tags
							$.getScript(obj_pinclone.site_url + '/wp-includes/js/jquery/suggest.min.js', function() {
								$('input#tags').suggest(obj_pinclone.ajaxurl + '?action=ajax-tag-search&tax=post_tag', {minchars: 3, multiple: true});
							});
						}
					});
				} else {
					$('#post-repin-box .post-repin-box-photo').html('<img src="' + $('#post-' + post_id + ' .featured-thumb').attr('src') + '" />');
					$('#post-repin-box textarea#pin-title').val(post_description);
					$('#post-repin-box textarea#pin-content').val(post_content);
					$('#post-repin-box #tags').val(post_tags);
					$('#post-repin-box #price').val(post_price);
					$('#repinform-add-new-board').text(obj_pinclone.__addnewboard);
					$('#repinform #board-add-new').val('').hide();
					$('#repinform #board-add-new-category').val('-1').hide();
					$('#repinform #board').show();
					$('#repinform #repin-post-id').val(post_id);
					$('#repinnedmsg, .repinnedmsg-share').hide();
					$('#repinform').show();
					
					scrolltop = $(window).scrollTop();
					$('#post-repin-box').css('top', scrolltop+10);
					$(window).scrollTop(scrolltop);
					$('#post-repin-box').modal();
					$('#post-repin-box textarea#pin-title').focus().select();
				}
			}
			return false;
		} else {
			if ($(document).width() <= 480) {
				topAlertMsg('<a href="' + obj_pinclone.login_url + '">' + obj_pinclone.__Pleaseloginorregisterhere + '</a>');
			} else {
				if ($('#loginbox-wrapper .popover').length) {
					$('#loginbox').popover('hide');
				}
				$('#popup-login-overlay').show();
				$('#popup-login-box').modal({backdrop:false, keyboard:false});
			}
			return false;
		}
	});
	
	//disable submit button if empty textarea and no board
	$(document).on('focus', '#post-repin-box textarea#pin-title', function() {
		if ($.trim($('#repinform textarea#pin-title').val()) && ($('#repinform #board').val() != '-1' || $.trim($('#repinform #board-add-new').val()))) {
			$('#pinit').removeAttr('disabled');
		} else {
			$('#pinit').attr('disabled', 'disabled');
		}

		$(this).keyup(function() {
		if ($.trim($('#repinform textarea#pin-title').val()) && ($('#repinform #board').val() != '-1' || $.trim($('#repinform #board-add-new').val()))) {
				$('#pinit').removeAttr('disabled');
			} else {
				$('#pinit').attr('disabled', 'disabled');
			}
		});
	});
	
	$(document).on('focus', '#post-repin-box #board-add-new', function() {
		if ($.trim($('#repinform textarea#pin-title').val()) && ($('#repinform #board').val() != '-1' || $.trim($('#repinform #board-add-new').val()))) {
			$('#pinit').removeAttr('disabled');
		} else {
			$('#pinit').attr('disabled', 'disabled');
		}

		$(this).keyup(function() {
		if ($.trim($('#repinform textarea#pin-title').val()) && ($('#repinform #board').val() != '-1' || $.trim($('#repinform #board-add-new').val()))) {
				$('#pinit').removeAttr('disabled');
			} else {
				$('#pinit').attr('disabled', 'disabled');
			}
		});
	});
	
	//prevent form submit on enter key
	$(document).on('keypress', '#board-add-new', function(e) {
		return e.keyCode != 13;
	});
	
	//repin form add new board toggle
	$(document).on('click', '#repinform-add-new-board', function() {
		if ($(this).text() == obj_pinclone.__cancel) {
			if($('#noboard').length) {
				$('#pinit').attr('disabled', 'disabled');
			}
			$(this).text(obj_pinclone.__addnewboard);
			$('#repinform #board-add-new').val('').hide();
			$('#repinform #board-add-new-category').val('-1').hide();
			$('#repinform #board').show().focus();
		} else {
			$(this).text(obj_pinclone.__cancel);
			$('#repinform #board-add-new').show().focus();
			$('#repinform #board-add-new-category').show();
			$('#repinform #board').hide();
		}
		return false;
	});
	
	//repin for frontpage, lightbox, posts
	$(document).on('submit', '#repinform', function() {
		$(this).find('input[type="submit"]').attr('disabled', 'disabled');
		
		var post_id = $('#repinform #repin-post-id').val();
		var price = '';
		if ($('#repinform #price').length)
			price = $('#repinform #price').val().replace(/[^0-9.]/g, '');
		var data = {
			action: 'pinclone-repin',
			nonce: obj_pinclone.nonce,
			repin_title: $('#repinform textarea#pin-title').val(),
			repin_content: $('#repinform textarea#pin-content').val(),
			repin_tags: $('#repinform #tags').val(),
			repin_price: price,
			repin_post_id: post_id,
			repin_board: $('#repinform #board').val(),
			repin_board_add_new: $('#repinform #board-add-new').val(),
			repin_board_add_new_category: $('#repinform #board-add-new-category').val()
		};

		var repin_status = $('#repin-status');
		repin_status.html('');
		repin_status.html(' <div class="ajax-loader ajax-loader-inline"></div>');
		
		//if user create a new board, inject a span to indicate to ajax fetch board next round
		if ($('#repinform #board-add-new').val() != '') {
			repin_status.after('<span id="newboard"></span>');
		}
		
		$.ajax({
			type: 'post',
			url: obj_pinclone.ajaxurl,
			data: data,
			error: function() {
				repin_status.html('<div class="alert pull-right"><strong><small>' + obj_pinclone.__errorpleasetryagain  + '</small></strong></div>');
			},
			success: function(data) {
				repin_status.html('');
				$('#repinform').hide();
				if ($('#repinform #board-add-new').val() == '') {
					board_name = $('#repinform #board option:selected').text();
				} else {
					board_name = $('#repinform #board-add-new').val();
				}
				$('#post-repin-box .post-repin-box-photo').after('<h3 id="repinnedmsg" class="text-center">' + obj_pinclone.__repinnedto + ' ' + board_name + '<br /><a class="btn" href="' + data + '" aria-hidden="true">' + obj_pinclone.__seethispin + '</a> <a class="btn" data-dismiss="modal" aria-hidden="true">' + obj_pinclone.__close + '</a></h3><h5 class="repinnedmsg-share text-center">' + obj_pinclone.__shareitwithyourfriends + '</h5><p class="repinnedmsg-share text-center"><iframe src="//www.facebook.com/plugins/like.php?href=' + encodeURIComponent(data) + '&amp;layout=button_count&amp;width=96&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;share=true' + obj_pinclone.fb_appid + '" style="border:none; overflow:hidden; width:96px; height:21px;"></iframe> <a href="https://twitter.com/share" class="twitter-share-button" data-url="' + data + '" data-count="none" data-text="' + $('#repinform textarea#pin-title').val() + '">Tweet</a> <span class="g-plusone" data-size="medium" data-annotation="none" data-href="' + data + '"></span></p>');
				twttr.widgets.load();
				$.getScript('https://apis.google.com/js/plusone.js');
					
				var newrepin = '<li><a class="post-repins-avatar">' + obj_pinclone.avatar48 + '</a> <a href="' + obj_pinclone.home_url + '/' + obj_pinclone.user_rewrite + '/' + obj_pinclone.ul + '/">' + obj_pinclone.ui + '</a> ' + obj_pinclone.__onto + ' <strong>' + $('#repinform #board option:selected').text() + '</strong></li>';

				if (!$('#post-repins').length) {
					$('.post-wrapper').append('<div id="post-repins"><div class="post-repins-wrapper"><h4>' + obj_pinclone.__Repins + '</h4><ul></ul></div></div>');
				}
				$('#post-repins ul').append(newrepin);

				var repins_countmsg = $('#repins-count-'+post_id);
				var repins_count = repins_countmsg.text();
				repins_count = repins_count.substr(0, repins_count.indexOf(' '));
				
				if (repins_count == '') {
					$('#repins-count-'+post_id).removeClass('hide').text('1 ' + obj_pinclone.__repin);
					if($('#masonry').length) {
						$('#masonry').masonry('reloadItems').masonry('layout');
					}
				} else {
					$('#repins-count-'+post_id).text((parseInt(repins_count,10)+1) + ' ' + obj_pinclone.__repins);
				}
			}
		});
		return false;
	});
	
	//comments for lightbox and posts
	$(document).on('submit', '#commentform', function() {
		var commentform = $(this);
		commentform.find('input[type="submit"]').attr('disabled', 'disabled');

		var post_id = $('#commentform #comment_post_ID').val();
		var formdata = $(this).serialize();
		var formurl = $(this).attr('action');
		var comment_parent = $('#commentform #comment_parent').val();
		
		$('#comment-status').remove();
		$('.form-submit').append(' <div class="comment-status-ajax-loader ajax-loader ajax-loader-inline"></div>');
		
		$.ajax({
			type: 'post',
			url: formurl,
			data: formdata,
			error: function(XMLHttpRequest) {
				var errormsg = XMLHttpRequest.responseText.substr(XMLHttpRequest.responseText.indexOf('<p>')+3);
				errormsg = errormsg.substr(0, errormsg.indexOf('</p>'));
				
				if (errormsg == '') {
					errormsg = obj_pinclone.__errorpleasetryagain;
				}
				
				$('.textarea-wrapper').prepend('<div id="comment-status"></div>');
				$('.comment-status-ajax-loader').remove();
				$('#comment-status').html('<div class="alert"><strong><small>' + errormsg + '</small></strong></div>');
				$('#commentform textarea').focus();
				commentform.find('input[type="submit"]').removeAttr('disabled');
			},
			success: function() {
				var commenttext =  $('#commentform #comment').val();
				var newcomment = '<li><div class="comment-avatar">' + obj_pinclone.avatar48 + '</div><div class="comment-content"><strong><a href="' + obj_pinclone.home_url + '/' + obj_pinclone.user_rewrite + '/' + obj_pinclone.ul + '/">' + obj_pinclone.ui + '</a></strong> / ' + obj_pinclone.current_date + '<p>' + commenttext + '</p></div></li>';

				$('.comment-status-ajax-loader').remove();
				$('#commentform #comment').val('');
				$('#commentform #comment_parent').val('');
				
				if (comment_parent == '0' || comment_parent == '' ) {
					if ($('#comments').find('.commentlist').size() == 0) {
						$('#comments').prepend('<ol class="commentlist"></ol>');
					}
					$('.commentlist').append(newcomment);
				} else {
					if ($('#comment-' + comment_parent).find('>ul.children').size() == 0) {
						$('#comment-' + comment_parent).append('<ul class="children"></ul>');
					}
					$('#comment-' + comment_parent + ' >ul.children').append(newcomment);
				}
				
				var comments_countmsg = $('#comments-count-'+post_id);
				var comments_count = comments_countmsg.text();
				comments_count = comments_count.substr(0, comments_count.indexOf(' '));

				if (comments_count == '') {						
					$('#comments-count-'+post_id).removeClass('hide').text('1 ' + obj_pinclone.__comment);
					if($('#masonry').length) {
						$('#masonry').masonry('reloadItems').masonry('layout');
					}
				} else {
					$('#comments-count-'+post_id).text((parseInt(comments_count,10)+1) + ' ' + obj_pinclone.__comments);
				}
				commentform.find('input[type="submit"]').removeAttr('disabled');
			}
		});
		return false;
	});
	
	//Zoom full size photo
	$(document).on('click', '.pinclone-zoom', function() {
		$('#post-zoom-overlay').show();
		$('#post-fullsize').lightbox({backdrop:false, keyboard:false});
		return false;
	});
	
	$(document).on('click', '#post-fullsize-close', function() {
		$('#post-zoom-overlay').fadeOut();
		$('#post-fullsize').lightbox('hide');
		return false;
	});
	
	$(document).on('click', '#post-zoom-overlay', function() {
		$('#post-zoom-overlay').fadeOut();
		$('#post-fullsize').lightbox('hide');
	});

	//Embed for lightbox & posts
	$(document).on('click', '.post-embed', function() {
		$('#post-embed-overlay').show();
		$('#post-embed-box').modal({backdrop:false, keyboard:false});
		$('#post-embed-box textarea').focus().select();
		return false;
	});
	
	$(document).on('click', '#post-embed-close', function() {
		$('#post-embed-overlay').hide();
		$('#post-embed-box').modal('hide');
	});
	
	$(document).on('click', '#post-embed-overlay', function() {
		$('#post-embed-overlay').hide();
		$('#post-embed-box').modal('hide');
	});

	$(document).on('keydown', '#embed-width', function() {
		old_height = $('#embed-height').val();
		old_width_str = "width=\'" + $(this).val() + "'";
		old_height_str = "height=\'" + $('#embed-height').val() + "'";
		ratio = $('.post-featured-photo img').width() / $('.post-featured-photo img').height();
	}).on('keyup', '#embed-width', function() {
		var embed_code = $('#post-embed-box textarea').val();
		var new_height = Math.ceil($(this).val()/ratio);
		var new_height_str = "height='" + new_height + "'";
		var new_width_str = "width='" + $(this).val() + "'";
		
		$('#embed-height').val(new_height);
		embed_code = embed_code.replace(old_height_str, new_height_str);
		embed_code = embed_code.replace(old_width_str, new_width_str);
		$('#post-embed-box textarea').val(embed_code);
	});
	
	$(document).on('keydown', '#embed-height', function() {
		old_width = $('#embed-width').val();
		old_width_str = "width=\'" + $('#embed-width').val() + "'";
		old_height_str = "height=\'" + $(this).val() + "'";
		ratio = $('.post-featured-photo img').width() / $('.post-featured-photo img').height();
	}).on('keyup', '#embed-height', function() {
		var embed_code = $('#post-embed-box textarea').val();
		var new_width = Math.ceil($(this).val()*ratio);
		var new_width_str = "width='" + new_width + "'";
		var new_height_str = "height='" + $(this).val() + "'";
		
		$('#embed-width').val(new_width);
		embed_code = embed_code.replace(old_height_str, new_height_str);
		embed_code = embed_code.replace(old_width_str, new_width_str);
		$('#post-embed-box textarea').val(embed_code);
	});
	
	//Email friend for lightbox & posts
	$(document).on('click', '.post-email', function() {
		if (obj_pinclone.u != '0') {
			$('#post-email-overlay').show();
			$('#post-email-box').modal({backdrop:false, keyboard:false});
			$('#post-email-box #recipient-name').focus();
			return false;
		} else {
			if ($(document).width() <= 480) {
				topAlertMsg('<a href="' + obj_pinclone.login_url + '">' + obj_pinclone.__Pleaseloginorregisterhere + '</a>');
			} else {
				if ($('#loginbox-wrapper .popover').length) {
					$('#loginbox').popover('hide');
				}
				$('#popup-login-overlay').show();
				$('#popup-login-box').modal({backdrop:false, keyboard:false});
			}
			return false;
		}
	});
	
	$(document).on('click', '#post-email-close', function() {
		$('#post-email-overlay').hide();
		$('#post-email-box').modal('hide');
	});
	
	$(document).on('click', '#post-email-overlay', function() {
		$('#post-email-overlay').hide();
		$('#post-email-box').modal('hide');
	});
	
	$(document).on('click', '#post-email-submit', function() {
		$('#post-email-box .ajax-loader-email-pin').css('display', 'inline-block');
		var data = {
			action: 'pinclone-post-email',
			nonce: obj_pinclone.nonce,
			email_post_id: $('#post-email-box #email-post-id').val(),
			recipient_name: $('#post-email-box #recipient-name').val(),
			recipient_email: $('#post-email-box #recipient-email').val(),
			recipient_message: $('#post-email-box textarea').val()
		};
	
		$.ajax({
			type: 'post',
			url: obj_pinclone.ajaxurl,
			data: data,
			success: function() {
				$('#post-email-box .ajax-loader-email-pin').hide();
				$('#post-email-overlay').hide();
				$('#post-email-box').modal('hide');
			}
		});
		return false;
	});
	
	//Email friend - disable submit button if empty recipient name and email
	$(document).on('focus', '#post-email-box #recipient-name', function() {
		if ($.trim($('#post-email-box #recipient-name').val()) && $.trim($('#post-email-box #recipient-email').val())) {
			$('#post-email-box #post-email-submit').removeAttr('disabled');
		} else {
			$('#post-email-box #post-email-submit').attr('disabled', 'disabled');
		}

		$(this).keyup(function() {
			if ($.trim($('#post-email-box #recipient-name').val()) && $.trim($('#post-email-box #recipient-email').val())) {
				$('#post-email-box #post-email-submit').removeAttr('disabled');
			} else {
			$('#post-email-box #post-email-submit').attr('disabled', 'disabled');
			}
		});
	});
	
	$(document).on('focus', '#post-email-box #recipient-email', function() {
		if ($.trim($('#post-email-box #recipient-name').val()) && $.trim($('#post-email-box #recipient-email').val())) {
			$('#post-email-box #post-email-submit').removeAttr('disabled');
		} else {
			$('#post-email-box #post-email-submit').attr('disabled', 'disabled');
		}

		$(this).keyup(function() {
		if ($.trim($('#post-email-box #recipient-name').val()) && $.trim($('#post-email-box #recipient-email').val())) {
				$('#post-email-box #post-email-submit').removeAttr('disabled');
			} else {
			$('#post-email-box #post-email-submit').attr('disabled', 'disabled');
			}
		});
	});
	
	//Email friend for boards
	$(document).on('click', '#post-email-board', function() {
		if (obj_pinclone.u != '0') {
			$('#post-email-board-overlay').show();
			$('#post-email-board-box').modal({backdrop:false, keyboard:false});
			$('#post-email-board-box #recipient-name').focus();
			return false;
		} else {
			if ($(document).width() <= 480) {
				topAlertMsg('<a href="' + obj_pinclone.login_url + '">' + obj_pinclone.__Pleaseloginorregisterhere + '</a>');
			} else {
				if ($('#loginbox-wrapper .popover').length) {
					$('#loginbox').popover('hide');
				}
				$('#popup-login-overlay').show();
				$('#popup-login-box').modal({backdrop:false, keyboard:false});
			}
			return false;
		}
	});
	
	$(document).on('click', '#post-email-board-close', function() {
		$('#post-email-board-overlay').hide();
		$('#post-email-board-box').modal('hide');
	});
	
	$(document).on('click', '#post-email-board-overlay', function() {
		$('#post-email-board-overlay').hide();
		$('#post-email-board-box').modal('hide');
	});
	
	$(document).on('click', '#post-email-board-submit', function() {
		$('#post-email-board-box .ajax-loader-email-pin').css('display', 'inline-block');
		var data = {
			action: 'pinclone-post-email',
			nonce: obj_pinclone.nonce,
			email_board_id: $('#post-email-board-box #email-board-id').val(),
			recipient_name: $('#post-email-board-box #recipient-name').val(),
			recipient_email: $('#post-email-board-box #recipient-email').val(),
			recipient_message: $('#post-email-board-box textarea').val()
		};
	
		$.ajax({
			type: 'post',
			url: obj_pinclone.ajaxurl,
			data: data,
			success: function() {
				$('#post-email-board-box .ajax-loader-email-pin').hide();
				$('#post-email-board-overlay').hide();
				$('#post-email-board-box').modal('hide');
			}
		});
		return false;
	});
	
	//Email friend for board - disable submit button if empty recipient name and email
	$(document).on('focus', '#post-email-board-box #recipient-name', function() {
		if ($.trim($('#post-email-board-box #recipient-name').val()) && $.trim($('#post-email-board-box #recipient-email').val())) {
			$('#post-email-board-box #post-email-board-submit').removeAttr('disabled');
		} else {
			$('#post-email-board-box #post-email-board-submit').attr('disabled', 'disabled');
		}

		$(this).keyup(function() {
			if ($.trim($('#post-email-board-box #recipient-name').val()) && $.trim($('#post-email-board-box #recipient-email').val())) {
				$('#post-email-board-box #post-email-board-submit').removeAttr('disabled');
			} else {
			$('#post-email-board-box #post-email-board-submit').attr('disabled', 'disabled');
			}
		});
	});
	
	$(document).on('focus', '#post-email-board-box #recipient-email', function() {
		if ($.trim($('#post-email-board-box #recipient-name').val()) && $.trim($('#post-email-board-box #recipient-email').val())) {
			$('#post-email-board-box #post-email-board-submit').removeAttr('disabled');
		} else {
			$('#post-email-board-box #post-email-board-submit').attr('disabled', 'disabled');
		}

		$(this).keyup(function() {
			if ($.trim($('#post-email-board-box #recipient-name').val()) && $.trim($('#post-email-board-box #recipient-email').val())) {
				$('#post-email-board-box #post-email-board-submit').removeAttr('disabled');
			} else {
			$('#post-email-board-box #post-email-board-submit').attr('disabled', 'disabled');
			}
		});
	});
	
	//Report pin for lightbox & posts
	$(document).on('click', '.post-report', function() {
		$('#post-report-overlay').show();
		$('#post-report-box').modal({backdrop:false, keyboard:false});
		$('#post-report-box textarea').focus();
		return false;
	});
	
	$(document).on('click', '#post-report-close', function() {
		$('#post-report-overlay').hide();
		$('#post-report-box').modal('hide');
	});
	
	$(document).on('click', '#post-report-overlay', function() {
		$('#post-report-overlay').hide();
		$('#post-report-box').modal('hide');
	});
	
	$(document).on('click', '#post-report-submit', function() {
		$('#post-report-box .ajax-loader-report-pin').css('display', 'inline-block');
		
		var data = {
			action: 'pinclone-post-report',
			nonce: obj_pinclone.nonce,
			report_post_id: $('#post-report-box #report-post-id').val(),
			report_message: $('#post-report-box textarea').val()
		};
	
		$.ajax({
			type: 'post',
			url: obj_pinclone.ajaxurl,
			data: data,
			success: function() {
				$('#post-report-box .ajax-loader-report-pin').hide();
				$('#post-report-overlay').hide();
				$('#post-report-box').modal('hide');
			}
		});
		return false;
	});
	
	//Report pin - disable submit button if empty message
	$(document).on('focus', '#post-report-box textarea', function() {
		if ($.trim($('#post-report-box textarea').val())) {
			$('#post-report-box #post-report-submit').removeAttr('disabled');
		} else {
			$('#post-report-box #post-report-submit').attr('disabled', 'disabled');
		}

		$(this).keyup(function() {
			if ($.trim($('#post-report-box textarea').val())) {
				$('#post-report-box #post-report-submit').removeAttr('disabled');
			} else {
			$('#post-report-box #post-report-submit').attr('disabled', 'disabled');
			}
		});
	});
	
	//follow for lightbox, posts, author
	$(document).on('click', '.pinclone-follow', function() {
		if (obj_pinclone.u != '0') {
			var follow = $(this);
			var	board_parent_id = follow.data('board_parent_id');
			var	board_id = follow.data('board_id');
			var	author_id = follow.data('author_id');
			var	disable_others = follow.data('disable_others');
			follow.attr('disabled', 'disabled');
								
			if (!follow.hasClass('disabled')) {
				var data = {
					action: 'pinclone-follow',
					nonce: obj_pinclone.nonce,
					pinclone_follow: 'follow',
					board_parent_id: board_parent_id,
					board_id: board_id,
					author_id: author_id,
					disable_others: disable_others
				};

				$.ajax({
					type: 'post',
					url: obj_pinclone.ajaxurl,
					data: data,
					success: function() {
						follow.addClass('disabled').text('Unfollow').removeAttr('disabled');
						
						//increase followers count in author.php
						if ($('#ajax-follower-count') && follow.parent().parent().parent().parent().attr('id') == 'userbar') {
							$('#ajax-follower-count').html(parseInt($('#ajax-follower-count').html(), 10)+1);
						}
						
						//disable other follow button
						if (board_parent_id == '0' && (disable_others != 'no' || $('#userbar .nav li:first').hasClass('active'))) {
							$('.pinclone-follow').addClass('disabled').text('Unfollow');
						}
					}
				});
			} else {						
				var data = {
					action: 'pinclone-follow',
					nonce: obj_pinclone.nonce,
					pinclone_follow: 'unfollow',
					board_parent_id: board_parent_id,
					board_id: board_id,
					author_id: author_id
				};

				$.ajax({
					type: 'post',
					url: obj_pinclone.ajaxurl,
					data: data,
					success: function(data) {								
						follow.removeClass('disabled').text('Follow').removeAttr('disabled');
						
						//decrease followers count in author.php
						if ($('#ajax-follower-count') && follow.parent().parent().parent().parent().attr('id') == 'userbar') {
							$('#ajax-follower-count').html(parseInt($('#ajax-follower-count').html(), 10)-1);
						}
						
						//enable other follow button
						if (data == 'unfollow_all' && (disable_others != 'no' || $('#userbar .nav li:first').hasClass('active'))) {
							$('.pinclone-follow').removeClass('disabled').text('Follow');
						}
					}
				});
			}
			return false;
		} else {
			if ($(document).width() <= 480) {
				topAlertMsg('<a href="' + obj_pinclone.login_url + '">' + obj_pinclone.__Pleaseloginorregisterhere + '</a>');
			} else {
				if ($('#loginbox-wrapper .popover').length) {
					$('#loginbox').popover('hide');
				}
				$('#popup-login-overlay').show();
				$('#popup-login-box').modal({backdrop:false, keyboard:false});
			}
			return false;
		}
	});
	
	//infinite scroll
	if ($masonry.length && obj_pinclone.infinitescroll != 'disable') {
		nextSelector = obj_pinclone.nextselector;
		if (document.URL.indexOf('/source/') != -1) {
			nextSelector = '#navigation #navigation-next a';
		}
		
		$masonry.infinitescroll({
			navSelector : '#navigation',
			nextSelector : nextSelector,
			itemSelector : '.thumb',
			bufferPx : 300,
			loading: {
				msgText: '',
				finishedMsg: obj_pinclone.__allitemsloaded,
				img: obj_pinclone.stylesheet_directory_uri + '/img/ajax-loader.gif',
				finished: function() {}
			}
		}, function(newElements) {
			if ($(document).width() <= 480) {
				var $newElems = $(newElements).hide();
	
				$newElems.imagesLoaded(function() {
					$('#infscr-loading').fadeOut('normal');
					$newElems.show();
					$masonry.masonry('appended', $newElems, true);
				});
			} else {
				var $newElems = $(newElements);
				$('#infscr-loading').fadeOut('normal');
				$masonry.masonry('appended', $newElems, true);
			}
		});
	}
	
	//infinite scroll for user profile - boards
	if (user_profile_boards.length && obj_pinclone.infinitescroll != 'disable') {
		user_profile_boards.infinitescroll({
			navSelector : '#navigation',
			nextSelector : '#navigation #navigation-next a',
			itemSelector : '.board-mini',
			bufferPx : 300,
			loading: {
				msgText: '',
				finishedMsg: obj_pinclone.__allitemsloaded,
				img: obj_pinclone.stylesheet_directory_uri + '/img/ajax-loader.gif'
			}
		});
	}
	
	//infinite scroll for user profile - followers & following
	if (user_profile_follow.length && obj_pinclone.infinitescroll != 'disable') {
		user_profile_follow.infinitescroll({
			navSelector : '#navigation',
			nextSelector : '#navigation #navigation-next a',
			itemSelector : '.follow-wrapper',
			bufferPx : 300,
			loading: {
				msgText: '',
				finishedMsg: obj_pinclone.__allitemsloaded,
				img: obj_pinclone.stylesheet_directory_uri + '/img/ajax-loader.gif',
				finished: function() {}
			}
		}, function(newElements) {
			var $newElems = $(newElements).hide();

			$newElems.imagesLoaded(function() {
				$('#infscr-loading').fadeOut('normal');
				$newElems.show();
				user_profile_follow.masonry('appended', $newElems, true);
			});
		});
	}
	
	//infinite scroll for notifications
	if (user_notifications.length && obj_pinclone.infinitescroll != 'disable') {
		user_notifications.infinitescroll({
			navSelector : '#navigation',
			nextSelector : '#navigation #navigation-next a',
			itemSelector : '.notifications-wrapper',
			bufferPx : 300,
			loading: {
				msgText: '',
				finishedMsg: obj_pinclone.__allitemsloaded,
				img: obj_pinclone.stylesheet_directory_uri + '/img/ajax-loader.gif',
				finished: function() {}
			}
		}, function(newElements) {
			var $newElems = $(newElements).hide();
			$newElems.appendTo($('.table'));
			$('#infscr-loading').fadeOut('normal');
			$newElems.show();
		});
	}
	
	//actionbar
	$masonry.on('mouseenter', '.thumb-holder', function() {
		if ($(document).width() > 480  && navigator.userAgent.match(/iPad/i) == null) {
			$(this).children('.masonry-actionbar').show();
		}
	});
	
	$masonry.on('mouseleave', '.thumb-holder', function() {
		if ($(document).width() > 480 && navigator.userAgent.match(/iPad/i) == null) {
			$(this).children('.masonry-actionbar').hide();
		}
	});
	
	//comments for frontpage
	$masonry.on('submit', '.masonry-meta form', function() {
		var commentform = $(this);
		var formdata = $(this).serialize();
		var formurl = $(this).attr('action');
		var post_id = $(this).attr('id').substr(12);
		
		commentform.find('input[type="submit"]').attr('disabled', 'disabled');
		
		$('#comment-status').remove();
		$('#commentform-' + post_id + ' .form-submit .comment-status-ajax-loader').remove();
		$('#commentform-' + post_id + ' .form-submit').append(' <div class="comment-status-ajax-loader ajax-loader ajax-loader-inline"></div>');

		$.ajax({
			type: 'post',
			url: formurl,
			data: formdata,
			error: function(XMLHttpRequest) {
				var errormsg = XMLHttpRequest.responseText.substr(XMLHttpRequest.responseText.indexOf('<p>')+3);
				errormsg = errormsg.substr(0, errormsg.indexOf('</p>'));
				
				if (errormsg == '') {
					errormsg = obj_pinclone.__errorpleasetryagain;
				}
				
				$('#commentform-' + post_id).prepend('<div id="comment-status"></div>');
				$('#comment-status').html('<div class="alert"><strong>' + errormsg + '</strong></div>');
				$('#commentform-' + post_id).find('textarea').focus();
				$('#commentform-' + post_id + ' .form-submit .comment-status-ajax-loader').remove();
				if($('#masonry').length) {
					$('#masonry').masonry('reloadItems').masonry('layout');
				}
		
				commentform.find('input[type="submit"]').removeAttr('disabled');
			},
			success: function() {
				$('#commentform-' + post_id + ' .form-submit .comment-status-ajax-loader').remove();
				var commenttext =  $('#commentform-' + post_id + ' textarea').val();
				var newcomment = '<div id="masonry-meta-comment-wrapper-' + post_id + '" class="masonry-meta"><div class="masonry-meta-avatar">' + obj_pinclone.avatar30 + '</div><div class="masonry-meta-comment"><span class="masonry-meta-author">' + obj_pinclone.ui + '</span><span class="masonry-meta-comment-content"> ' + commenttext + '</span></div></div>';
				
				$('#masonry-meta-comment-wrapper-' + post_id).append(newcomment);
				$('#commentform-' + post_id + ' #comment').val('');
				$('#masonry-meta-commentform-' + post_id).hide();
				$('#post-' + post_id + ' .pinclone-comment').removeClass('disabled');
				
				var comments_countmsg = $('#comments-count-'+post_id);
				var comments_count = comments_countmsg.text();
				comments_count = comments_count.substr(0, comments_count.indexOf(' '));

				if (comments_count == '') {						
					$('#comments-count-'+post_id).removeClass('hide').text('1 ' + obj_pinclone.__comment);
				} else {
					$('#comments-count-'+post_id).text((parseInt(comments_count,10)+1) + ' ' + obj_pinclone.__comments);
				}
				
				if($('#masonry').length) {
					$('#masonry').masonry('reloadItems').masonry('layout');
				}
				
				$('#commentform-' + post_id + ' textarea').val('');
				commentform.find('input[type="submit"]').removeAttr('disabled');
			}
		});
		return false;
	});
	
	//comments toggle frontpage comments form
	$masonry.on('click', '.pinclone-comment', function() {
		if (obj_pinclone.u != '0') {
			var commentsform = $(this);
			if (!commentsform.hasClass('disabled')) {
				commentsform.addClass('disabled');
			} else {
				commentsform.removeClass('disabled');
			}
			$('#masonry-meta-commentform-' + commentsform.data('post_id')).slideToggle('fast', function() {
				if($('#masonry').length) {
					$('#masonry').masonry('reloadItems').masonry('layout');
				}
			}).find('textarea').focus();
			return false;
		} else {
			if ($(document).width() <= 480) {
				topAlertMsg('<a href="' + obj_pinclone.login_url + '">' + obj_pinclone.__Pleaseloginorregisterhere + '</a>');
			} else {
				if ($('#loginbox-wrapper .popover').length) {
					$('#loginbox').popover('hide');
				}
				$('#popup-login-overlay').show();
				$('#popup-login-box').modal({backdrop:false, keyboard:false});
			}
			return false;
		}
	});

	//lightbox
$(document).on('mouseenter', '#masonry .featured-thumb-link, .post-wrapper .post-nav-next a, .post-wrapper .post-nav-prev a',  function(){
$(this).animate({'opacity':'0.7'}, 300);
}).on('mouseleave', '#masonry .featured-thumb-link, .post-wrapper .post-nav-next a, .post-wrapper .post-nav-prev a', function() {
$(this).animate({'opacity':'1'}, 300);
});
	$(document).on('click', '#masonry .featured-thumb-link, .post-wrapper .post-nav-next a, .post-wrapper .post-nav-prev a', function() {
		if ($masonry.length && !$('#single-pin').length && obj_pinclone.lightbox != 'disable' && $(document).width() > 590 && navigator.userAgent.match(/iPad/i) == null) {
			var lightbox = $('#post-lightbox');
			var href = $(this).closest('a').attr('href');

			$('body').css('overflow', 'hidden');
			
			if (!$('.post-wrapper').length || $('.post-wrapper').height() == 0) {
				post_wrapper_height = 45;
				ajax_loader_style = '';
				
				if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
					var ieversion = new Number(RegExp.$1);
					if (ieversion > 10) {
						window.history.pushState('', '', href);
					}
				} else {
					window.history.pushState('', '', href);
				}
			} else {
				post_wrapper_height = $('.post-wrapper').height();
				ajax_loader_style = ' style="position: absolute; left: 50%; margin-left:-12px; top: 50%; margin-top: -12px;"';
				
				if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
					var ieversion = new Number(RegExp.$1);
					if (ieversion > 10) {
						window.history.replaceState('', '', href);
					}
				} else {
					window.history.replaceState('', '', href);
				}
			}
			
			lightbox.html('<div class="post-wrapper text-center" style="height:' + post_wrapper_height + 'px"><p></p><p><div class="ajax-loader"' + ajax_loader_style + '></div></p></div>')
				.modal().load(href + '?lightbox .post-wrapper', function() {
					$('.post-wrapper .post-nav-next').addClass('post-nav-next-lightbox').css('right', $(window).width() - ($('.post-wrapper').offset().left + $('.post-wrapper').outerWidth()) - 38);
					$('.post-wrapper .post-nav-prev').addClass('post-nav-prev-lightbox').css('left', $('.post-wrapper').offset().left - 38);
					$('.post-wrapper .post-nav-next a, .post-wrapper .post-nav-prev a').addClass('post-nav-link-lightbox');
					$('.post-wrapper .post-share').css('position', 'fixed');
					$('.post-wrapper .post-board, .post-wrapper .post-close').show();
					twttr.widgets.load();
					$.getScript('https://apis.google.com/js/plusone.js');
					$.getScript('//assets.pinterest.com/js/pinit.js');
					FB.XFBML.parse();
				});

			return false;
		}
	});

	//hide lightbox when click outside
	$('#post-lightbox').click(function(e) {
		var lightbox = $('#post-lightbox');

		if (lightbox.has(e.target).length === 0 && e.pageX < ($(window).width() - 22)) { //second condition for firefox-scrollbar-onclick-close-lightbox fix
			$('body').css('overflow', 'auto');
			$('#video-embed').remove();
			$('.brand').focus().blur();
			lightbox.modal('hide');
			
			if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
				var ieversion = new Number(RegExp.$1);
				if (ieversion > 10) {
					window.history.back();
				}
			} else {
				window.history.back();
			}			
		}
	});
	
    //hide lightbox when esc key is pressed. must use keydown not keyup.
	$(document).keydown(function(e) {
		if ($('#post-lightbox').css('display') == 'block' && e.keyCode == 27) {
			if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
				var ieversion = new Number(RegExp.$1)
				if (ieversion > 10) {
					window.history.back();
				}
			} else {
				window.history.back();
			}
		}
	});
	
    //hide lightbox when sidebar close button is clicked
	$(document).on('click', '.post-close', function(e) {
			if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
				var ieversion = new Number(RegExp.$1)
				if (ieversion > 10) {
					window.history.back();
				}
			} else {
				window.history.back();
			}
	});
	
    //hide lightbox when back button is pressed
	$(window).on('popstate', function(e){
		var lightbox = $('#post-lightbox');

		if (lightbox.length) {
			$('body').css('overflow', 'auto');
			$('#video-embed').remove();
			$('.brand').focus().blur();
			lightbox.modal('hide');
		}
    });
	
	//Manipulate history for links in lightbox
	$(document).on('click', '#post-lightbox a', function(e) {
		href = $(this).attr('href');

		if ($(this).hasClass('pinclone-edit') || (!$(this).hasClass('post-nav-link-lightbox') && !$(this).hasClass('btn') && href != '' && $(this).attr('target') != '_blank')) {
			if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
				var ieversion = new Number(RegExp.$1);
				if (ieversion > 10) {
					window.location.replace(href);
					return false;
				}
			} else if (/Chrome[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
				setTimeout(function() { //have to use setTimeout for chrome?
					window.location.replace(href);
					return false;
				}, 0);
			} else {
				window.location.replace(href);
				return false;
			}
		}
	});
	
	//Add board
	$('#add_board_form').submit(function() {
		var addboardform = $(this);
		var errormsg = $('.error-msg');
		var ajaxloader = $('.ajax-loader');

		addboardform.find('input[type="submit"]').attr('disabled', 'disabled');
		errormsg.hide();
		ajaxloader.show();
		
		if ($('#board-title').val() == '') {
			errormsg.html('<div class="alert"><strong>' + obj_pinclone.__pleaseenteratitle  + '</strong></div>').fadeIn();
			$('#board-title').focus();
			$('.ajax-loader').hide();
			addboardform.find('input[type="submit"]').removeAttr('disabled');
		} else {
			var data = {
				action: 'pinclone-add-board',
				nonce: obj_pinclone.nonce,
				board_title: $('#board-title').val(),
				category_id: $('#category-id').val(),
				term_id: $('#term-id').val(),
				mode: $('#mode').val()
			};
			
			$.ajax({
				type: 'post',
				url: obj_pinclone.ajaxurl,
				data: data,
				error: function() {
					ajaxloader.hide();
					errormsg.html(obj_pinclone.__errorpleasetryagain).fadeIn();
					addboardform.find('input[type="submit"]').removeAttr('disabled');
				},
				success: function(data) {
					ajaxloader.hide();
					if ($('#add_board_form #mode').val() == 'add' || $('#add_board_form #mode').val() == 'edit' ) {
						if (data == 'error') {
							errormsg.html('<div class="alert"><strong>' + obj_pinclone.__boardalreadyexists  + '</strong></div>').fadeIn();
							$('#board-title').focus();
							addboardform.find('input[type="submit"]').removeAttr('disabled');
						} else {
							window.location = data;
							console.log(data);
						}
					}
				}
			});
		}
		return false;
	});

	//delete board confirmation
	$(document).on('click', '.pinclone-delete-board', function() {
		$('#delete-board-modal').modal();
		return false;
	});
	
	//delete board
	$(document).on('click', '#pinclone-delete-board-confirmed', function() {
		var ajaxloader = $('.ajax-loader-delete-board');
		var delete_btn = $(this);
		var	board_id = delete_btn.data('board_id');

		delete_btn.attr('disabled', 'disabled');
		ajaxloader.css('display', 'inline-block');
									
		var data = {
			target: '.ajax-loader-add-pin',
			action: 'pinclone-delete-board',
			nonce: obj_pinclone.nonce,
			board_id: board_id
		};

		$.ajax({
			type: 'post',
			url: obj_pinclone.ajaxurl,
			data: data,
			success: function(data) {
				window.location = data;
			}
		});
	});

	//add pin from computer
	$('#pin_upload_file').change(function() { 
		$('.error-msg').hide();
		$('#pin_upload_form').submit();
	});
	
	if ($('#pin_upload_form').length) {
		var options = {
			beforeSubmit: showRequest,
			uploadProgress: function(event, position, total, percentComplete) {
				if (window.FormData !== undefined) {
					$('#pin-upload-progress').show();
					$('#pin-upload-progress .bar').css('width', percentComplete + '%');
				}
			},
			success: showResponse,
			url: obj_pinclone.ajaxurl
		}; 
		$('#pin_upload_form').ajaxForm(options);
	}
	
	function showRequest(formData, jqForm, options) {
		$('#pin-upload-from-web-wrapper, #browser-addon, #bookmarklet, #pinitbutton').slideUp();
		if (window.FormData === undefined) {
			$('#pin-upload-from-computer-wrapper .ajax-loader-add-pin').show();
		}

		var ext = $('#pin_upload_file').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			$('#pin-upload-from-computer-wrapper .ajax-loader-add-pin, #pin-upload-progress').hide();
			$('.error-msg').html('<div class="alert"><strong>' + obj_pinclone.__invalidimagefile  + '</strong></div>').fadeIn();
			return false;
		}
	}
	
	function showResponse(responseText, statusText, xhr, $form) {
		if (responseText == 'error') {
			$('.ajax-loader-add-pin, #pin-upload-progress').hide();
			$('.error-msg').html('<div class="alert"><strong>' + obj_pinclone.__invalidimagefile  + '</strong></div>').fadeIn();
		} else if (responseText == 'errorsize') {
			$('.ajax-loader-add-pin, #pin-upload-progress').hide();
			$('.error-msg').html('<div class="alert"><strong>' + obj_pinclone.__imagetoosmall  + '</strong></div>').fadeIn();
		} else {
			var data = $.parseJSON(responseText);
			$('#thumbnail').attr('src', data.thumbnail);
			$('#attachment-id').val(data.id);
			$('.ajax-loader-add-pin, .error-msg, #pin-upload-progress').hide();
			$('#pin-upload-from-computer-wrapper').slideUp(function() {
				$('#pin-upload-postdata-wrapper').slideDown();
				$('#pin-postdata-form textarea#pin-title').focus();
			});
		}
	}
	
	//add pin from web
	if ($('#pin_upload_web_form').length) {
		var options_web = {
			beforeSubmit: showRequest_web,
			success: showResponse_web,
			url: obj_pinclone.ajaxurl
		};
		$('#pin_upload_web_form').ajaxForm(options_web);
	}
	
	function showRequest_web(formData, jqForm, options) {
		$('#fetch').attr('disabled', 'disabled');
		$('#pin-upload-from-computer-wrapper, #browser-addon, #bookmarklet, #pinitbutton').slideUp();
		$('#photo_data_source').val($('#pin_upload_web').val());
		$('#pin-upload-from-web-wrapper .ajax-loader-add-pin').show();
		$('.error-msg').hide();
		
		var input_url = $('#pin_upload_web').val();

		if (input_url == '') {
			$('.ajax-loader-add-pin').hide();
			$('.error-msg').html('<div class="alert"><strong>' + obj_pinclone.__pleaseenterurl  + '</strong></div>').fadeIn();
			$('#fetch').removeAttr('disabled');
			return false();
		}
		
		//append http:// if missing
		if (input_url.indexOf('http://') == -1 && input_url.indexOf('https://') == -1) {
			input_url = 'http://' + input_url;
		}
		
		//strip https for youtube & vimeo
		if (input_url.indexOf('youtube.com/watch') != -1 || input_url.match(/vimeo.com\/(\d+)($|\/)/)) {
			input_url = input_url.replace('https://', 'http://');
		}
		
		var ext = input_url.split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			if (input_url.indexOf('youtu.be/') != -1) {
				input_url = input_url.replace(/youtu.be\//, 'www.youtube.com/watch?v=');
			}
			
			$.get(obj_pinclone.stylesheet_directory_uri + '/pinclone_fetch.php?url=' + encodeURIComponent(input_url.replace('http','')) + '&nonce=' + obj_pinclone.nonce, function(data){
				if (data.substr(0, 5) == 'error') {
					$('.ajax-loader-add-pin').hide();
					$('#fetch').removeAttr('disabled');
					$('.error-msg').html('<div class="alert"><strong>' + data.substr(5)  + '</strong></div>').fadeIn();
				} else {
					$('.ajax-loader-add-pin').hide();
					$('#fetch').removeAttr('disabled');
					$('body').css('overflow', 'hidden')
					.append("\
					<div id='pincloneframe'>\
						<div id='pincloneframebg'><p>" + obj_pinclone.__loading + "</p></div>\
						<div id='pincloneheader'><p id='pincloneclose'>X</p><p id='pinclonelogo'>" + obj_pinclone.blogname + "</p></div>\
						<div id='pincloneimages'></div>\
						<style type='text/css'>\
							#pincloneframe {color: #333;}\
							#pincloneframebg {background: #f2f2f2; display: none; position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 2147483646;}\
							#pincloneframebg p {background: #999; border-radius: 8px; color: white; font: normal normal bold 16px\/16px Helvetica, Arial, sans-serif; margin: -2em auto 0 -9.5em; padding: 12px; position: absolute; top: 50%; left: 50%; text-align: center; width: 15em;}\
							#pincloneframe #pincloneheader {background: white; border-bottom: 1px solid #d4d4d4; border-top: 3px solid #2f2f2f; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); color: white; height: 40px; margin: 0; padding: 0; position: fixed; top: 0; left: 0; text-align: center; width: 100%; z-index: 2147483647;}\
							#pincloneframe #pincloneheader #pinclonelogo {color: black; font: normal normal bold 20px\/20px Helvetica, Arial, sans-serif; margin: 0; padding: 12px 15px 13px 20px;}\
							#pincloneframe #pincloneheader #pincloneclose {background: #f33; color: white; cursor: pointer; float: right; font: normal normal bold 16px\/16px Helvetica, Arial, sans-serif; margin: 0; padding: 12px 15px 13px 20px;}\
							#pincloneimages {position: fixed; top: 60px; left: 0; width: 100%; height: 94%; overflow-x: auto; overflow-y: scroll; text-align: center; z-index: 2147483647;}\
							#pincloneimages .pincloneimgwrapper {border: 1px solid #aaa; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2); cursor: pointer; display: inline-block; height: 200px; margin: 15px; overflow: hidden; position: relative; width: 200px;}\
							#pincloneimages .pinclonebutton {background: rgba(0, 0, 0, 0.5); border-radius: 8px; color: white; font: normal normal bold 36px/36px Helvetica, Arial, sans-serif; padding: 8px 16px; display: none; margin-left: -24px; margin-top: -24px; position: absolute; top: 50%; left:50%;}\
							#pincloneimages .pinclonedimension {background: rgba(255, 255, 255, 0.9); font: normal normal normal 12px/12px Helvetica, Arial, sans-serif; padding: 3px 0; position: absolute; right: 0; bottom: 0; left: 0;}\
							#pincloneimages img {width: 100%; height: auto;}\
						</style>\
					</div>");
					
					$('#pincloneframebg').fadeIn(200);

					function display_thumbnails(imgarr, videoflag) {
						if (!imgarr.length) {
							$('#pincloneframebg').html('<p>' + obj_pinclone.__sorryunbaletofindanypinnableitems + '</p>');
						} else {
							if ($(data).filter('pinclonetitle').text()) {
								page_title = encodeURIComponent($(data).filter('pinclonetitle').text().trim());
							} else {
								page_title = '';
							}
							
							var imgstr = '';
							for (var i = 0; i < imgarr.length; i++) {
								if (videoflag == '0') {
									imgstr += '<div class="pincloneimgwrapper" data-href="' + obj_pinclone.home_url + '/pins-settings/?m=bm&imgsrc=' + encodeURIComponent(imgarr[i][0].replace('http','')) + '&source=' + encodeURIComponent(input_url.replace('http','')) + '&title=' + page_title + '&video=' + videoflag + '"><div class="pinclonebutton">+</div><img src="' + imgarr[i][0] + '" /></div>';
								} else {
									imgstr += '<div class="pincloneimgwrapper" data-href="' + obj_pinclone.home_url + '/pins-settings/?m=bm&imgsrc=' + encodeURIComponent(imgarr[i][0].replace('http','')) + '&source=' + encodeURIComponent(input_url.replace('http','')) + '&title=' + page_title + '&video=' + videoflag + '"><div class="pinclonebutton">+</div><div class="pinclonedimension">' + obj_pinclone.__Video + '</div><img src="' + imgarr[i][0] + '" /></div>';
								}
							}

							$('#pincloneimages').css('height',$(window).height()-$('#pincloneheader').height()-20)
												.html(imgstr + "<div style='height:40px;clear:both;'><br /></div>")
							if ((navigator.appVersion.indexOf('Chrome/') != -1 || navigator.appVersion.indexOf('Safari/')) && videoflag != '1') {
								$('#pincloneimages .pincloneimgwrapper').css('float','left');
							}
							
							if (videoflag == '0') { 
								$('#pincloneimages').hide().imagesLoaded(function() {
									var images_hidden_count = 0;
									
									$('#pincloneimages img').each(function() {
										var imgwidth = this.naturalWidth;
										if (!imgwidth) {
											imgwidth = jQuery(this).width();
										}
										
										var imgheight = this.naturalHeight;
										if (!imgheight) {
											imgheight = jQuery(this).height();
										}
										
										if (imgwidth < 125) {
											$(this).parent().hide();
											images_hidden_count++;
										} else {
											$(this).before('<div class="pinclonedimension">' + parseInt(imgwidth,10) + ' x ' + parseInt(imgheight,10) + '</div>');	
										}
									});
									
									if (images_hidden_count == imgarr.length) {
										$('#pincloneframebg').html('<p>' + obj_pinclone.__sorryunbaletofindanypinnableitems + '</p>');
									} else {
										$('#pincloneframebg p').fadeOut(200);
										$('#pincloneimages').show();
									}
								});
							} else {
								$('#pincloneframebg p').fadeOut(200);	
							}
						}	
					}
					
					var imgarr = [];
					var videoflag = '0';
					
					if (input_url.indexOf('youtube.com/watch') != -1) {
						video_id = input_url.match('[\\?&]v=([^&#]*)');
						imgsrc = 'http://img.youtube.com/vi/' + video_id[1] + '/0.jpg';
						imgarr.unshift([imgsrc,480,360]);
						videoflag = '1';
						display_thumbnails(imgarr, videoflag);
					} else if (input_url.match(/vimeo.com\/(\d+)($|\/)/)) {
						video_id = input_url.split('/')[3];
						
						$.getJSON('http://www.vimeo.com/api/v2/video/' + video_id + '.json?callback=?', {format: "json"}, function(data) {
							imgsrc = data[0].thumbnail_large;
							imgarr.unshift([imgsrc,640,360]);
							videoflag = '1';
							display_thumbnails(imgarr, videoflag);
						});
					} else {
						$('img', data).each(function() {
							var imgsrc = $(this).prop('src');
							imgarr.push([imgsrc,0,0]);
						});
						
						display_thumbnails(imgarr, videoflag);
					}
					
					$('#pincloneheader').on('click', '#pincloneclose', function() {
						$('body').css('overflow', 'visible');
						$('#pincloneframe').fadeOut(200, function() {
							$(this).remove();
							$('#pin_upload_web').focus().select();
						});
					});
					
					$('#pincloneimages').on('click', '.pincloneimgwrapper', function() {
						window.open($(this).data('href'), "pinclonewindow", "width=400,height=760,left=0,top=0,resizable=1,scrollbars=1");
						$('body').css('overflow', 'visible');
						$('#pincloneframe').remove();
						$('#pin_upload_web').focus().select();
					});
					
					$('#pincloneimages').on('mouseover', '.pincloneimgwrapper', function() {
						$(this).find('.pinclonebutton').show();
					}).on('mouseout', '.pincloneimgwrapper', function() {
						$(this).find('.pinclonebutton').hide();
					});
					
					$(document).keyup(function(e) {
						if (e.keyCode == 27) {
						$('body').css('overflow', 'visible');
						$('#pincloneframe').fadeOut(200, function() {
							$(this).remove();
							$('#pin_upload_web').focus().select();
						});
						}
					});
				}
			});
	
			return false;
		}
	}
	
	function showResponse_web(responseText, statusText, xhr, $form) {
		if (responseText == 'error') {
			$('.ajax-loader-add-pin').hide();
			$('.error-msg').html('<div class="alert"><strong>' + obj_pinclone.__invalidimagefile  + '</strong></div>').fadeIn();
			$('#fetch').removeAttr('disabled');
		} else if (responseText == 'errorsize') {
			$('.ajax-loader-add-pin').hide();
			$('.error-msg').html('<div class="alert"><strong>' + obj_pinclone.__imagetoosmall  + '</strong></div>').fadeIn();
			$('#fetch').removeAttr('disabled');
		} else if (responseText.indexOf('Error Fetching Image') != -1) {
			$('#pin-upload-from-web-wrapper .ajax-loader-add-pin').hide();
			$('.error-msg').html('<div class="alert"><strong>' + responseText  + '</strong></div>').fadeIn();
			$('#pin_upload_web').focus();
			$('#fetch').removeAttr('disabled');
		} else {
			var data = $.parseJSON(responseText);
			$('#thumbnail').attr('src', data.thumbnail);
			$('#attachment-id').val(data.id);
			$('.ajax-loader-add-pin, .error-msg').hide();
			$('#pin-upload-from-web-wrapper').slideUp(function() { 
				$('#pin-upload-postdata-wrapper').slideDown();
				$('#pin-postdata-form textarea#pin-title').focus();
			});
		}
	}
	
	//add new board toggle
	$(document).on('click', '#pin-postdata-form #pin-postdata-add-new-board', function() {
		if ($(this).text() == obj_pinclone.__cancel) {
			if($('#noboard').length) {
				$('#pinit').attr('disabled', 'disabled');
			}
			$(this).text(obj_pinclone.__addnewboard);
			$('.usercp-pins #board-add-new').val('').hide();
			$('.usercp-pins #board-add-new-category').val('-1').hide();
			$('.usercp-pins #board').show().focus();
		} else {
			$(this).text(obj_pinclone.__cancel);
			$('.usercp-pins #board-add-new').show().focus();
			$('.usercp-pins #board-add-new-category').show();
			$('.usercp-pins #board').hide();
		}
		return false;
	});
	
	//disable submit button if empty textarea (from web and computer)
	$('#pin-postdata-form textarea#pin-title').focus(function() {
		$(this).keyup(function() {
			if ($.trim($('#pin-postdata-form textarea#pin-title').val()) && ($('#pin-postdata-form #board').val() != '-1' || $.trim($('#pin-postdata-form #board-add-new').val()))) {
				$('#pinit').removeAttr('disabled');
			} else {
				$('#pinit').attr('disabled', 'disabled');
			}
		});
	});

	//disable submit button if empty textarea (bookmarklet mode)
	if ($('#pin-postdata-form textarea#pin-title').is(':focus')) {
		$(this).keyup(function() {
			if ($.trim($('#pin-postdata-form textarea#pin-title').val()) && ($('#pin-postdata-form #board').val() != '-1' || $.trim($('#pin-postdata-form #board-add-new').val()))) {
				$('#pinit').removeAttr('disabled');
			} else {
				$('#pinit').attr('disabled', 'disabled');
			}
		});
	}
		
	$('#pin-postdata-form #board-add-new').focus(function() {
		$(this).keyup(function() {
			if ($.trim($('#pin-postdata-form textarea#pin-title').val()) && ($('#pin-postdata-form #board').val() != '-1' || $.trim($('#pin-postdata-form #board-add-new').val()))) {
				$('#pinit').removeAttr('disabled');
			} else {
				$('#pinit').attr('disabled', 'disabled');
			}
		});
	});
	
	//autocomplete tags
	if ('function' == typeof $.suggest) {
		$('input#tags').suggest(obj_pinclone.ajaxurl + '?action=ajax-tag-search&tax=post_tag', {minchars: 3, multiple: true});
	}
	
	//insert new pin
	$('#pin-postdata-form').submit(function() {
		var postdataform = $(this);
		var errormsg = $('.error-msg');
		var ajaxloader = $('.ajax-loader-add-pin');
		
		var postdata_photo_source;
		
		if ($('#photo_data_source').val()) {
			postdata_photo_source = $('#photo_data_source').val();
		}
		
		var price = '';
		if ($('#pin-postdata-form #price').length)
			price = $('#pin-postdata-form #price').val().replace(/[^0-9.]/g, '');
		
		var data = {
			action: 'pinclone-postdata',
			nonce: obj_pinclone.nonce,
			postdata_title: $('#pin-postdata-form textarea#pin-title').val(),
			postdata_content: $('#pin-postdata-form textarea#pin-content').val(),
			postdata_attachment_id: $('#attachment-id').val(),
			postdata_board: $('#pin-postdata-form #board').val(),
			postdata_board_add_new: $('#pin-postdata-form #board-add-new').val(),
			postdata_board_add_new_category: $('#pin-postdata-form #board-add-new-category').val(),
			postdata_tags: $('#pin-postdata-form #tags').val(),
			postdata_price: price,
			postdata_photo_source: postdata_photo_source
		};

		postdataform.find('input[type="submit"]').attr('disabled', 'disabled');
		ajaxloader.show();
		errormsg.hide();
		
		$.ajax({
			type: 'post',
			url: obj_pinclone.ajaxurl,
			data: data,
			error: function() {
				ajaxloader.hide();
				errormsg.html('<div class="alert"><strong>' + obj_pinclone.__errorpleasetryagain  + '</strong></div>').fadeIn();
				postdataform.find('input[type="submit"]').removeAttr('disabled');
			},
			success: function(data) {
				ajaxloader.hide();
				errormsg.hide();
				$('#pin-postdata-form').hide();
				
				var board_name;
				if ($('#pin-postdata-form #board-add-new').val() == '') {
					board_name = $('#pin-postdata-form #board option:selected').text();
				} else {
					board_name = $('#pin-postdata-form #board-add-new').val();
				}
								
				var pin_status ='<br />';
				if (data.indexOf('/?p=') != -1) {
					pin_status = '<small style="display:block;clear:both"><span class="label label-warning">* Moderation might be needed before it goes live</span></small>';
				}
				
				if (window.location.search.indexOf('m=bm') != -1) //via bookmarklet
					$('.postdata-box-photo').after('<h3 id="repinnedmsg" class="text-center">' + obj_pinclone.__pinnedto + ' ' + board_name + pin_status + '<a class="btn" href="javascript:window.open(\'' + data + '\');window.close();">' + obj_pinclone.__seethispin + '</a> <a href="javascript:window.close()" class="btn" aria-hidden="true">' + obj_pinclone.__close + '</a></h3><h5 class="repinnedmsg-share text-center">' + obj_pinclone.__shareitwithyourfriends + '</h5><p class="repinnedmsg-share text-center"><iframe src="//www.facebook.com/plugins/like.php?href=' + encodeURIComponent(data) + '&amp;layout=button_count&amp;width=96&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;share=true' + obj_pinclone.fb_appid + '" style="border:none; overflow:hidden; width:96px; height:21px;"></iframe> <a href="https://twitter.com/share" class="twitter-share-button" data-url="' + data + '" data-count="none" data-text="' + $('#pin-postdata-form textarea#pin-title').val() + '">Tweet</a> <span class="g-plusone" data-size="medium" data-annotation="none" data-href="' + data + '"></span></p>');
				else {
					$('.postdata-box-photo').after('<h3 id="repinnedmsg" class="text-center">' + obj_pinclone.__pinnedto + ' ' + board_name + pin_status + '<a class="btn" href="' + data + '">' + obj_pinclone.__seethispin + '</a> <a href="" class="btn" aria-hidden="true">' + obj_pinclone.__addanotherpin + '</a></h3><h5 class="repinnedmsg-share text-center">' + obj_pinclone.__shareitwithyourfriends + '</h5><p class="repinnedmsg-share text-center"><iframe src="//www.facebook.com/plugins/like.php?href=' + encodeURIComponent(data) + '&amp;layout=button_count&amp;width=96&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;share=true' + obj_pinclone.fb_appid + '" style="border:none; overflow:hidden; width:96px; height:21px;"></iframe> <a href="https://twitter.com/share" class="twitter-share-button" data-url="' + data + '" data-count="none" data-text="' + $('#pin-postdata-form textarea#pin-title').val() + '">Tweet</a> <span class="g-plusone" data-size="medium" data-annotation="none" data-href="' + data + '"></span></p>');
				}
				twttr.widgets.load();
				$.getScript('https://apis.google.com/js/plusone.js');
			}
		});
		return false;
	});
	
	//edit pin
	//add new board toggle
	$(document).on('click', '#pin-edit-form #pin-postdata-add-new-board', function() {
		if ($(this).text() == obj_pinclone.__cancel) {
			$(this).text(obj_pinclone.__addnewboard);
			$('.usercp-pins #board-add-new').val('').hide();
			$('.usercp-pins #board-add-new-category').val('-1').hide();
			$('.usercp-pins #board').show().focus();
		} else {
			$(this).text(obj_pinclone.__cancel);
			$('.usercp-pins #board-add-new').show().focus();
			$('.usercp-pins #board-add-new-category').show();
			$('.usercp-pins #board').hide();
		}
		return false;
	});
	
	//disable submit button if empty textarea
	if ($('#pin-edit-form textarea#pin-title').is(":focus")) {
		$(this).keyup(function() {
			if ($.trim($('#pin-edit-form textarea#pin-title').val())) {
				$('#pinit').removeAttr('disabled');
			} else {
				$('#pinit').attr('disabled', 'disabled');
			}
		});
	}
	
	$('#pin-edit-form textarea#pin-title').focus(function() {
		$(this).keyup(function() {
			if ($.trim($('#pin-edit-form textarea#pin-title').val())) {
				$('#pinit').removeAttr('disabled');
			} else {
				$('#pinit').attr('disabled', 'disabled');
			}
		});
	});
	
	$('#pin-edit-form').submit(function() {
		var editform = $(this);
		var errormsg = $('.error-msg');
		var ajaxloader = $('.ajax-loader-add-pin');
		
		var price = '';
		if ($('#pin-edit-form #price').length)
			price = $('#pin-edit-form #price').val().replace(/[^0-9.]/g, '');
		
		var data = {
			action: 'pinclone-pin-edit',
			nonce: obj_pinclone.nonce,
			postdata_pid: $('#pin-edit-form #pid').val(),
			postdata_title: $('#pin-edit-form textarea#pin-title').val(),
			postdata_content: $('#pin-edit-form textarea#pin-content').val(),
			postdata_board: $('#pin-edit-form #board').val(),
			postdata_board_add_new: $('#pin-edit-form #board-add-new').val(),
			postdata_board_add_new_category: $('#pin-edit-form #board-add-new-category').val(),
			postdata_tags: $('#pin-edit-form #tags').val(),
			postdata_price: price,
			postdata_source: $('#pin-edit-form #source').val()
		};

		editform.find('input[type="submit"]').attr('disabled', 'disabled');
		ajaxloader.show();
		errormsg.hide();
		
		$.ajax({
			type: 'post',
			url: obj_pinclone.ajaxurl,
			data: data,
			error: function() {
				ajaxloader.hide();
				errormsg.html('<div class="alert"><strong>' + obj_pinclone.__errorpleasetryagain  + '</strong></div>').fadeIn();
				editform.find('input[type="submit"]').removeAttr('disabled');
			},
			success: function(data) {
				window.location = data;
			}
		});
		return false;
	});
	
	//delete pin confirmation
	$(document).on('click', '.pinclone-delete-pin', function() {
		$('#delete-pin-modal').modal();
		return false;
	});
	
	//delete pin
	$(document).on('click', '#pinclone-delete-pin-confirmed', function() {
		var ajaxloader = $('.ajax-loader-delete-pin');
		var delete_btn = $(this);
		var	pin_id = delete_btn.data('pin_id');
		var	pin_author = delete_btn.data('pin_author');

		delete_btn.attr('disabled', 'disabled');
		ajaxloader.css('display', 'inline-block');

		var data = {
			action: 'pinclone-delete-pin',
			nonce: obj_pinclone.nonce,
			pin_id: pin_id,
			pin_author: pin_author
		};

		$.ajax({
			type: 'post',
			url: obj_pinclone.ajaxurl,
			data: data,
			success: function(data) {
				window.location = data;
			}
		});
	});
	
	//delete account confirmation
	$(document).on('click', '#pinclone-delete-account', function() {
		$('#delete-account-modal').modal();
		return false;
	});
	
	//delete account
	$(document).on('click', '#pinclone-delete-account-confirmed', function() {
		var ajaxloader = $('.ajax-loader-delete-account');
		var delete_btn = $(this);
		var	user_id = delete_btn.data('user_id');

		delete_btn.attr('disabled', 'disabled');
		ajaxloader.css('display', 'inline-block');

		var data = {
			action: 'pinclone-delete-account',
			nonce: obj_pinclone.nonce,
			user_id: user_id
		};

		$.ajax({
			type: 'post',
			url: obj_pinclone.ajaxurl,
			data: data,
			success: function(data) {
				window.location = data;
			}
		});
	});	
	
	//login form check
	$(document).on('submit', '#loginform', function() {
		$('.error-msg-incorrect').hide();
		if ($('#log').val() == '' || $('#pwd').val() == '') {
			$('.error-msg-blank').html('<div class="alert"><strong>' + obj_pinclone.__pleaseenterbothusernameandpassword  + '</strong></div>').fadeIn();
			return false;
		}
	});
	
	//ajax upload avatar
	$('#pinclone_user_avatar').change(function() {
		$('.error-msg-avatar').hide();
		$('#avatarform').submit();
	});
	
	if ($('#avatarform').length) {
		var options = {
			beforeSubmit: showRequest_avatar,
			success: showResponse_avatar,
			url: obj_pinclone.ajaxurl
		};
		$('#avatarform').ajaxForm(options);
	}

	function showRequest_avatar(formData, jqForm, options) {
		$('#avatarform .ajax-loader-avatar').show();

		var ext = $('#pinclone_user_avatar').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			$('#avatarform .ajax-loader-avatar').hide();
			$('.error-msg-avatar').html('<div class="alert"><strong>' + obj_pinclone.__invalidimagefile  + '</strong></div>').fadeIn();
			return false;
		}
	}

	function showResponse_avatar(responseText, statusText, xhr, $form) {
		if (responseText == 'error') {
			$('#avatarform .ajax-loader-avatar').hide();
			$('.error-msg-avatar').html('<div class="alert"><strong>' + obj_pinclone.__invalidimagefile  + '</strong></div>').fadeIn();
		} else {
			var data = $.parseJSON(responseText);
			$('#avatar-wrapper').fadeOut(function() {
				$('#avatar-wrapper .img-polaroid').attr('src', data.thumbnail);
				$('#avatar-delete').removeAttr('disabled');
				$('#avatarform .ajax-loader-avatar').hide();
				$('#avatar-anchor').css('margin-bottom', $('#avatarform').height()+200);
				$('#avatar-wrapper').slideDown();
			});
		}
	}

	//delete avatar
	$('#avatar-delete').on('mouseup', function() { 
		var ajaxloader = $('.ajax-loader-avatar');
		var delete_btn = $(this);
		var id = delete_btn.data('id');
		delete_btn.attr('disabled', 'disabled');
		ajaxloader.show();
	
		var data = {
			action: 'pinclone-delete-avatar',
			nonce: obj_pinclone.nonce,
			id: id
		};

		$.ajax({
			type: 'post',
			url: obj_pinclone.ajaxurl,
			data: data,
			success: function() {
				ajaxloader.hide();
				$('#avatar-wrapper').fadeOut(function() {
					$('#avatar-anchor').css('margin-bottom', 100);
				});
			}
		});
		return false;
	});
	
	//kiv: animated gif mouseover
	//slow to load if animated gif filesize is large
	/* $(document).on('mouseover', '.featured-thumb-gif-class', function() {
		var preload = new Image();
		preload.src = $(this).data('animated-gif-src-full');
		$(this).attr('src', preload.src)
			.prev('.featured-thumb-gif').hide();
	});
	
	$(document).on('mouseout', '.featured-thumb-gif-class', function() {
		$(this).attr('src', $(this).data('animated-gif-src-medium'))
			.prev('.featured-thumb-gif').show();
	});
	*/
	
	function topAlertMsg(message) {
	    if ($('#top-alert-msg').length) {
			$('#top-alert-msg').hide();
		}

		$("<div />", { 
				id: 'top-alert-msg',
				html: message + '<div id="top-alert-msg-close">X</div>'
			})
			.hide()
			.prependTo("body")
			.slideDown('fast')
			.delay(5000)
			.slideUp(function() { 
				$(this).remove(); 
		});

		$(document).on('click', '#top-alert-msg-close', function() {
			$('#top-alert-msg').remove();
		});
	}

	//facebook like with comment
	$(document).on('mouseenter', '.fb-like', function() {
		$(this).css('overflow', 'visible');
	});
	
	$(document).on('mouseleave', '.fb-like', function() {
		$(this).css('overflow', 'hidden');
	});
});

/*! http://mths.be/placeholder v2.0.7 by @mathias */
(function(window, document, $) {

	var isInputSupported = 'placeholder' in document.createElement('input'),
	    isTextareaSupported = 'placeholder' in document.createElement('textarea'),
	    prototype = $.fn,
	    valHooks = $.valHooks,
	    hooks,
	    placeholder;

	if (isInputSupported && isTextareaSupported) {

		placeholder = prototype.placeholder = function() {
			return this;
		};

		placeholder.input = placeholder.textarea = true;

	} else {

		placeholder = prototype.placeholder = function() {
			var $this = this;
			$this
				.filter((isInputSupported ? 'textarea' : ':input') + '[placeholder]')
				.not('.placeholder')
				.bind({
					'focus.placeholder': clearPlaceholder,
					'blur.placeholder': setPlaceholder
				})
				.data('placeholder-enabled', true)
				.trigger('blur.placeholder');
			return $this;
		};

		placeholder.input = isInputSupported;
		placeholder.textarea = isTextareaSupported;

		hooks = {
			'get': function(element) {
				var $element = $(element);
				return $element.data('placeholder-enabled') && $element.hasClass('placeholder') ? '' : element.value;
			},
			'set': function(element, value) {
				var $element = $(element);
				if (!$element.data('placeholder-enabled')) {
					return element.value = value;
				}
				if (value == '') {
					element.value = value;
					// Issue #56: Setting the placeholder causes problems if the element continues to have focus.
					if (element != document.activeElement) {
						// We can't use `triggerHandler` here because of dummy text/password inputs :(
						setPlaceholder.call(element);
					}
				} else if ($element.hasClass('placeholder')) {
					clearPlaceholder.call(element, true, value) || (element.value = value);
				} else {
					element.value = value;
				}
				// `set` can not return `undefined`; see http://jsapi.info/jquery/1.7.1/val#L2363
				return $element;
			}
		};

		isInputSupported || (valHooks.input = hooks);
		isTextareaSupported || (valHooks.textarea = hooks);

		$(function() {
			// Look for forms
			$(document).delegate('form', 'submit.placeholder', function() {
				// Clear the placeholder values so they don't get submitted
				var $inputs = $('.placeholder', this).each(clearPlaceholder);
				setTimeout(function() {
					$inputs.each(setPlaceholder);
				}, 10);
			});
		});

		// Clear placeholder values upon page reload
		$(window).bind('beforeunload.placeholder', function() {
			$('.placeholder').each(function() {
				this.value = '';
			});
		});

	}

	function args(elem) {
		// Return an object of element attributes
		var newAttrs = {},
		    rinlinejQuery = /^jQuery\d+$/;
		$.each(elem.attributes, function(i, attr) {
			if (attr.specified && !rinlinejQuery.test(attr.name)) {
				newAttrs[attr.name] = attr.value;
			}
		});
		return newAttrs;
	}

	function clearPlaceholder(event, value) {
		var input = this,
		    $input = $(input);
		if (input.value == $input.attr('placeholder') && $input.hasClass('placeholder')) {
			if ($input.data('placeholder-password')) {
				$input = $input.hide().next().show().attr('id', $input.removeAttr('id').data('placeholder-id'));
				// If `clearPlaceholder` was called from `$.valHooks.input.set`
				if (event === true) {
					return $input[0].value = value;
				}
				$input.focus();
			} else {
				input.value = '';
				$input.removeClass('placeholder');
				input == document.activeElement && input.select();
			}
		}
	}

	function setPlaceholder() {
		var $replacement,
		    input = this,
		    $input = $(input),
		    $origInput = $input,
		    id = this.id;
		if (input.value == '') {
			if (input.type == 'password') {
				if (!$input.data('placeholder-textinput')) {
					try {
						$replacement = $input.clone().attr({ 'type': 'text' });
					} catch(e) {
						$replacement = $('<input>').attr($.extend(args(this), { 'type': 'text' }));
					}
					$replacement
						.removeAttr('name')
						.data({
							'placeholder-password': true,
							'placeholder-id': id
						})
						.bind('focus.placeholder', clearPlaceholder);
					$input
						.data({
							'placeholder-textinput': $replacement,
							'placeholder-id': id
						})
						.before($replacement);
				}
				$input = $input.removeAttr('id').hide().prev().attr('id', id).show();
				// Note: `$input[0] != input` now!
			}
			$input.addClass('placeholder');
			$input[0].value = $input.attr('placeholder');
		} else {
			$input.removeClass('placeholder');
		}
	}

}(this, document, jQuery));