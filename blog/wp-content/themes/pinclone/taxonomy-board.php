<?php get_header(); global $user_ID, $wp_rewrite; ?>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span3 hidden-phone"></div>
		<div class="span6 grand-title-wrapper">
			<?php 
			$board_info = $wp_query->get_queried_object();
			$board_user = $post->post_author;
			if (!isset($board_user)) {
				$board_user = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT user_id FROM $wpdb->usermeta
						WHERE meta_key ='_Board Parent ID'
						AND meta_value = %d LIMIT 1
						"
						, $board_info->parent
					)
				);
			}
			$user_info = get_user_by('id', $board_user);
			?>
			<h1>
			<?php 
				if ($board_info->parent == 0) {
					echo __('Pins From All', 'pinclone') . ' ' . $user_info->display_name . '&#39;s ' . __('Boards', 'pinclone');
				} else {
					echo $board_info->name;
					$category = get_category($board_info->description);
					echo '<br /><span style="font-size:0.6em;">' . __('Category:', 'pinclone') . ' <a href="' . get_category_link($category->cat_ID) . '">' . $category->name . '</a></span>';
				}
			?>
			</h1>

			<div class="grand-title-subheader">
				<div class="pull-right">
					<?php 
					if ($board_user != $user_ID) {
					?>
					<button class="btn follow pinclone-follow<?php if ($followed = pinclone_followed($board_info->term_id)) { echo ' disabled'; } ?>" data-author_id="<?php echo $board_user; ?>" data-board_id="<?php echo $board_info->term_id;  ?>" data-board_parent_id="<?php echo $board_info->parent; ?>" type="button"><?php if (!$followed) { _e('Follow', 'pinclone'); } else { _e('Unfollow', 'pinclone'); } ?></button>
					<?php 
					} 
					if ($board_info->parent && ($board_user == $user_ID || current_user_can('edit_others_posts'))) { ?>
					<a class="btn edit-board" href="<?php echo home_url('/boards-settings/?i=') . $board_info->term_id; ?>"><?php _e('Edit Board' , 'pinclone'); ?></a>
					<?php } ?>
				</div>
			
				<div class="pull-left">
					<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $user_info->user_nicename; ?>/"><?php echo get_avatar($user_info->ID, '32'); ?></a> 
					<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $user_info->user_nicename; ?>/"><?php echo $user_info->display_name; ?></a>
				</div>
				
				<div class="clearfix"></div>
			</div>
			
			<div class="post-share-horizontal" style="background: none;">
				<?php $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large'); ?>
				<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo rawurlencode(home_url('/board/') . $wp_query->query['board']. '/'); ?>&amp;layout=button_count&amp;width=135&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;share=true<?php if (get_option('wsl_settings_Facebook_app_id')) echo '&amp;appId=' . get_option('wsl_settings_Facebook_app_id'); ?>" style="border:none; overflow:hidden; width:135px; height:21px;"></iframe>

				<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo home_url('/board/') . $wp_query->query['board'] . '/'; ?>" data-text="<?php echo esc_attr($board_info->name); ?>">Tweet</a>

				<div class="g-plusone" data-size="medium" data-href="<?php echo home_url('/board/') . $wp_query->query['board'] . '/'; ?>"></div>
				<script>(function() {var po=document.createElement('script');po.type='text/javascript';po.async=true;po.src='https://apis.google.com/js/plusone.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(po,s);})();</script>

				<a class="pinterest" data-pin-config="beside" href="//pinterest.com/pin/create/button/?url=<?php echo rawurlencode(home_url('/board/') . $wp_query->query['board']. '/'); ?>&amp;media=<?php echo rawurlencode($imgsrc[0]); ?>&amp;description=<?php echo rawurlencode($board_info->name); ?>" data-pin-do="buttonPin"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="Pinterest Pin It" /></a>
			</div>

		</div>

		<div class="span3 hidden-phone"></div>
	</div>
	
	<div id="post-email-board-overlay"></div>
	
	<div class="modal hide" id="post-email-board-box" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-header">
			<button id="post-email-board-close" type="button" class="close" aria-hidden="true">x</button>
			<div><?php _e('Email This Board', 'pinclone'); ?></div>
		</div>
		
		<div class="modal-footer">
			<input type="text" id="recipient-name" /><span class="help-inline"> <?php _e('Recipient Name', 'pinclone'); ?></span>
			<input type="email" id="recipient-email" /><span class="help-inline"> <?php _e('Recipient Email', 'pinclone'); ?></span>
			<input type="hidden" id="email-board-id" value="<?php echo $board_info->term_id; ?>" />
			<textarea placeholder="<?php _e('Message (optional)', 'pinclone'); ?>"></textarea>
			<input class="btn btn-primary" type="submit" disabled="disabled" value="<?php _e('Send Email', 'pinclone'); ?>" id="post-email-board-submit" name="post-email-board-submit">
			<div class="ajax-loader-email-pin ajax-loader hide"></div>
		</div>
	</div>
	
<?php 
get_template_part('index', 'masonry');
get_footer();
?>