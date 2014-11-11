<?php get_header(); global $user_ID, $wp_rewrite; ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span3 hidden-phone"></div>
		<div class="span6 user-wrapper">
			<div class="post-content">
				<div class="user-avatar">
					<?php $user_info = get_user_by('id', $wp_query->query_vars['author']); echo get_avatar($user_info->ID, '96'); ?> 
				</div>
				
				<div class="user-profile">
					<h1><?php echo $user_info->display_name; ?></h1>

					<?php
					$blog_cat_id = of_get_option('blog_cat_id');
					if ($blog_cat_id) {
						$blog_post_count = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT COUNT(*) FROM $wpdb->posts
								LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
								LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
								WHERE $wpdb->term_taxonomy.term_id = %d
								AND $wpdb->term_taxonomy.taxonomy = 'category'
								AND $wpdb->posts.post_status = 'publish'
								AND post_author = %d
								"
								, $blog_cat_id, $user_info->ID
							)
						);
					}
					$pins_count = count_user_posts($user_info->ID) - $blog_post_count;
					$parent_board_id = get_user_meta($user_info->ID, '_Board Parent ID', true);
					$parent_board = get_term_by('id', $parent_board_id, 'board', ARRAY_A);
					$boards = get_terms('board', array('parent' => $parent_board_id, 'hide_empty' => false, 'orderby' => 'id', 'order' => 'DESC'));
					$boards_count = count($boards);
					$likes_count = get_user_meta($user_info->ID, '_Likes Count', true);
					$likes_count = $likes_count ? $likes_count : 0;
					$followers_count = get_user_meta($user_info->ID, '_Followers Count', true);
					$followers_count = $followers_count ? $followers_count : 0;
					$following_count = get_user_meta($user_info->ID, '_Following Count', true);
					$following_count = $following_count ? $following_count : 0;
					?>
					
					<?php if ($top_user_followers_pos = pinclone_top_user_by_followers($user_info->ID)) {	?>
						<a href="<?php echo home_url('/top-users/'); ?>"><span class="label label-warning top-user-count-alt1"><?php _e('Most Followers', 'pinclone'); ?> #<?php echo $top_user_followers_pos; ?></span></a> 
					<?php } ?>					
	
					<?php if ($top_user_pins_pos = pinclone_top_user_by_pins($user_info->ID)) { ?>
						<a href="<?php echo home_url('/top-users/'); ?>"><span class="label label-warning top-user-count-alt2"><?php _e('Most Pins', 'pinclone'); ?> #<?php echo $top_user_pins_pos; ?></span></a>
					<?php } ?>

					<p><?php echo $user_info->description; ?></p>

					<div class="user-profile-icons">
						<?php if ($user_info->user_url) { ?>
						<a href="<?php echo esc_url($user_info->user_url); ?>" target="_blank"><i class="fa fa-globe"></i></a> 
						<?php } ?>
	
						<?php if ($user_info->pinclone_user_facebook) { ?>
						<a href="http://www.facebook.com/<?php echo esc_attr($user_info->pinclone_user_facebook); ?>" target="_blank"><i class="fa fa-facebook-sign"></i></a> 
						<?php } ?>
	
						<?php if ($user_info->pinclone_user_twitter) { ?>
						<a href="http://twitter.com/<?php echo esc_attr($user_info->pinclone_user_twitter); ?>" target="_blank"><i class="fa fa-twitter"></i></a> 
						<?php } ?>
	
						<?php if ($user_info->pinclone_user_pinterest) { ?>
						<a href="http://pinterest.com/<?php echo esc_attr($user_info->pinclone_user_pinterest); ?>" target="_blank"><i class="fa fa-pinterest"></i></a> 
						<?php } ?>
	
						<?php if ($user_info->pinclone_user_googleplus) { ?>
						<a href="http://plus.google.com/<?php echo esc_attr($user_info->pinclone_user_googleplus); ?>" target="_blank"><i class="fa fa-google-plus"></i></a> 
						<?php } ?>
	
						<?php if ($user_info->pinclone_user_location) { ?>
						<a href="http://maps.google.com/?q=<?php echo rawurlencode($user_info->pinclone_user_location); ?>" target="_blank"><i class="fa fa-map-marker"></i> <small><?php echo esc_attr($user_info->pinclone_user_location); ?></small></a> 
						<?php } ?>
					</div>
				</div>
				
				<div class="clearfix"></div>
			</div>
		</div>

		<div class="span3 hidden-phone"></div>

		<div class="clearfix"></div>

		<div class="post-share-horizontal text-center" style="background: none;">
			<?php
			$user_avatar = get_avatar($user_info->ID, '96');
			preg_match('/src="(.*?)"/i', $user_avatar, $user_avatar_imgsrc);
			 ?>
			<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo rawurlencode(home_url('/') . $wp_rewrite->author_base . '/' . $user_info->user_nicename . '/'); ?>&amp;layout=button_count&amp;width=135&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;share=true<?php if (get_option('wsl_settings_Facebook_app_id')) echo '&amp;appId=' . get_option('wsl_settings_Facebook_app_id'); ?>" style="border:none; overflow:hidden; width:135px; height:21px;"></iframe>
			
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo home_url('/') . $wp_rewrite->author_base . '/' . $user_info->user_nicename . '/'; ?>" data-text="<?php echo esc_attr($user_info->display_name . ' (' . $user_info->user_nicename . ') | ' . get_bloginfo('name')); ?>">Tweet</a>

			<div class="g-plusone" data-size="medium" data-href="<?php echo home_url('/') . $wp_rewrite->author_base . '/' . $user_info->user_nicename . '/'; ?>"></div>
			<script>(function() {var po=document.createElement('script');po.type='text/javascript';po.async=true;po.src='https://apis.google.com/js/plusone.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(po,s);})();</script>
	
			<a class="pinterest" data-pin-config="beside" href="//pinterest.com/pin/create/button/?url=<?php echo rawurlencode(home_url('/') . $wp_rewrite->author_base . '/' . $user_info->user_nicename . '/'); ?>&amp;media=<?php echo rawurlencode($user_avatar_imgsrc[1]); ?>&amp;description=<?php echo rawurlencode($user_info->display_name . ' (' . $user_info->user_nicename . ') | ' . get_bloginfo('name')); ?>" data-pin-do="buttonPin"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="Pinterest Pin It" /></a>
		</div>
	</div>
	
	<div class="row-fluid">
		<div id="userbar" class="navbar">
			<div class="navbar-inner">
				<ul class="nav">
					<li<?php if (!isset($_GET['view'])) { echo ' class="active"'; } ?>><a href="<?php echo get_author_posts_url($user_info->ID); ?>"><strong><?php echo $boards_count; ?></strong> <?php if ($boards_count == 1) { _e('Board', 'pinclone'); } else { _e('Boards', 'pinclone'); } ?></a></li>
					<li<?php if (isset($_GET['view']) && $_GET['view'] == 'pins') { echo ' class="active"'; } ?>><a href="<?php echo get_author_posts_url($user_info->ID); ?>?view=pins"><strong><?php echo $pins_count; ?></strong> <?php if ($pins_count == 1) { _e('Pin', 'pinclone'); } else { _e('Pins', 'pinclone'); } ?></a></li>
					<li<?php if (isset($_GET['view']) && $_GET['view'] == 'likes') { echo ' class="active"'; } ?>><a href="<?php echo get_author_posts_url($user_info->ID); ?>?view=likes"><strong><?php echo $likes_count; ?></strong> <?php if ($likes_count == 1) { _e('Like', 'pinclone'); } else { _e('Likes', 'pinclone'); } ?></a></li>
					<li<?php if (isset($_GET['view']) && $_GET['view'] == 'followers') { echo ' class="active"'; } ?>><a href="<?php echo get_author_posts_url($user_info->ID); ?>?view=followers"><strong id="ajax-follower-count"><?php echo $followers_count; ?></strong> <?php if ($followers_count == 1) { _e('Follower', 'pinclone'); } else { _e('Followers', 'pinclone'); } ?></a></li>
					<li<?php if (isset($_GET['view']) && $_GET['view'] == 'following') { echo ' class="active"'; } ?>><a href="<?php echo get_author_posts_url($user_info->ID); ?>?view=following"><strong><?php echo $following_count; ?></strong> <?php _e('Following', 'pinclone'); ?></a></li>
					<li>
					<?php if ($user_info->ID != $user_ID) {	?>
						<button class="btn follow pinclone-follow<?php if ($followed = pinclone_followed($parent_board['term_id'])) { echo ' disabled'; } ?>" data-author_id="<?php echo $user_info->ID ?>" data-board_id="<?php echo $parent_board['term_id'];  ?>" data-board_parent_id="<?php echo $parent_board['parent']; ?>" data-disable_others="no" type="button"><?php if (!$followed) { _e('Follow', 'pinclone'); } else { _e('Unfollow', 'pinclone'); } ?></button>
					<?php } else { ?>
						<a class="btn follow" href="<?php echo home_url('/settings/'); ?>"><strong><?php _e('Edit Profile', 'pinclone'); ?></strong></a>
					<?php } ?>
					</li>
					<?php if ((current_user_can('administrator') || current_user_can('editor')) && $user_info->ID != $user_ID) { ?>
						<li><a class="btn follow" href="<?php echo home_url('/settings/?user=') . $user_info->ID ; ?>"><strong><?php _e('Edit User', 'pinclone'); ?></strong></a></li>
					<?php } ?>
				</ul>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>

<?php 
if (isset($_GET['view']) && $_GET['view'] == 'pins') {
	if ($user_ID == $user_info->ID || current_user_can('administrator') || current_user_can('editor')) {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array(
			'author' => $user_info->ID,
			'post_status' => array('pending', 'publish'),
			'paged' => $paged
		);
		query_posts($args);
	}
	get_template_part('index', 'masonry');


} else if (isset($_GET['view']) && $_GET['view'] == 'likes') {
	$post_likes = get_user_meta($user_info->ID, '_Likes Post ID');

	if (!empty($post_likes[0])) {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array(
			'post__in' => $post_likes[0],
			'orderby' => 'post__in',
			'paged' => $paged
		);
		
		query_posts($args);
		get_template_part('index', 'masonry');
	} else {
	?>
		<div class="row-fluid">
			<div class="span12">
				<div class="bigmsg">
					<h2><?php _e('Nothing yet.', 'pinclone'); ?></h2>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
	
	
} else if (isset($_GET['view']) && ($_GET['view'] == 'followers' || $_GET['view'] == 'following')) {
	if ($_GET['view'] == 'followers') {
		$followers = get_user_meta($user_info->ID, '_Followers User ID');
	} else if ($_GET['view'] == 'following') {
		$followers = get_user_meta($user_info->ID, '_Following User ID');
	}

	if (!empty($followers[0])) {
		$pnum = isset($_GET['pnum']) ? intval($_GET['pnum']) : 1;
		$followers_per_page = get_option('posts_per_page');
		$maxpage = ceil(count($followers[0])/$followers_per_page);
		$followers[0] = array_slice($followers[0], ($followers_per_page * ($pnum-1)), $followers_per_page);
		echo '<div id="user-profile-follow" class="row-fluid">';
		foreach ($followers[0] as $follower) {
			$follower_info = get_user_by('id', $follower);
			if ($follower_info) {
			?>
			<div class="follow-wrapper">
				<div class="post-content">
				<?php
				if ($follower != $user_ID) {
				?>
				<button class="btn follow pinclone-follow<?php $parent_board = get_user_meta($follower, '_Board Parent ID', true); if ($followed = pinclone_followed($parent_board)) { echo ' disabled'; } ?>" data-author_id="<?php echo $follower; ?>" data-board_id="<?php echo $parent_board; ?>" data-board_parent_id="0" data-disable_others="no" type="button"><?php if (!$followed) { _e('Follow', 'pinclone'); } else { _e('Unfollow', 'pinclone'); } ?></button>
				<?php } else { ?>
				<a class="btn follow disabled"><?php _e('Myself!', 'pinclone'); ?></a>
				<?php } ?>
					<div class="user-avatar">
						<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $follower_info->user_nicename; ?>/"><?php echo get_avatar($follower_info->ID , '32'); ?></a>
					</div>
					
					<div class="user-name">
						<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $follower_info->user_nicename; ?>/">
							<h4><?php echo $follower_info->display_name; ?></h4>
							<p><?php echo count_user_posts($follower_info->ID); ?> <?php _e('Pins', 'pinclone'); ?> - <?php if ('' == $followers_count = get_user_meta($follower_info->ID, '_Followers Count', true)) echo '0'; else echo $followers_count; ?> <?php _e('Followers', 'pinclone'); ?></p>
						</a>
					</div>
				</div>
			</div>
			<?php
			}
		}
		
		if ($maxpage != 0) { ?>
		<div id="navigation">
			<ul class="pager">				
				<?php if ($pnum != 1 && $maxpage >= $pnum) { ?>
				<li id="navigation-previous">
					<?php if ($_GET['view'] == 'followers') { ?>
					<a href="<?php echo get_author_posts_url($user_info->ID); ?>?view=followers&pnum=<?php echo $pnum-1; ?>"><?php _e('&laquo; Previous', 'pinclone') ?></a>
					<?php } else if ($_GET['view'] == 'following') { ?>
					<a href="<?php echo get_author_posts_url($user_info->ID); ?>?view=following&pnum=<?php echo $pnum-1; ?>"><?php _e('&laquo; Previous', 'pinclone') ?></a>
					<?php } ?>
				</li>
				<?php } ?>
				
				<?php if ($maxpage != 1 && $maxpage != $pnum) { ?>
				<li id="navigation-next">
					<?php if ($_GET['view'] == 'followers') { ?>
					<a href="<?php echo get_author_posts_url($user_info->ID); ?>?view=followers&pnum=<?php echo $pnum+1; ?>"><?php _e('Next &raquo;', 'pinclone') ?></a>
					<?php } else if ($_GET['view'] == 'following') { ?>
					<a href="<?php echo get_author_posts_url($user_info->ID); ?>?view=following&pnum=<?php echo $pnum+1; ?>"><?php _e('Next &raquo;', 'pinclone') ?></a>
					<?php } ?>
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php }
		echo '</div><div class="clearfix"></div></div>';
	} else {
	?>
		<div class="row-fluid">		
			<div class="span12">
				<div class="bigmsg">
					<?php if ($_GET['view'] == 'followers') { ?>
						<h2><?php _e('No one following yet.', 'pinclone'); ?></h2>
					<?php } else if ($_GET['view'] == 'following') { ?>
						<h2><?php _e('Not following anyone yet.', 'pinclone'); ?></h2>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
} else { //default to boards page 
	if ($boards_count > 0) {
	?>
	<div id="user-profile-boards">
	<?php	
		$pnum = isset($_GET['pnum']) ? intval($_GET['pnum']) : 1;
		$boards_per_page = 24;
		$maxpage = ceil($boards_count/$boards_per_page);
		$boards_paginated = get_terms('board', array('parent' => $parent_board_id, 'hide_empty' => false, 'orderby' => 'id', 'order' => 'DESC', 'number' => $boards_per_page, 'offset' => ($pnum - 1) * $boards_per_page));
		
		foreach ($boards_paginated as $board) {
			$board_id = $board->term_id;
			$board_parent_id = $board->parent;
			$board_name = $board->name;
			$board_count = $board->count;
			$board_slug = $board->slug;
			
			$board_thumbnail_ids = $wpdb->get_col($wpdb->prepare(
				"
				SELECT v.meta_value
				FROM $wpdb->postmeta AS v
				INNER JOIN (				
					SELECT object_id
					FROM $wpdb->term_taxonomy, $wpdb->term_relationships
					WHERE $wpdb->term_taxonomy.term_id = %d
					AND $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
					AND $wpdb->term_taxonomy.taxonomy = 'board'
					ORDER BY $wpdb->term_relationships.object_id DESC
					LIMIT 0, 5
					) AS v2 ON v.post_id = v2.object_id
					AND v.meta_key = '_thumbnail_id'
				",
				$board_id
			));
			?>
			<div class="board-mini">
				<h4>
					<a href="<?php echo home_url('/board/' . $board_id . '/'); ?>">
						<?php echo $board_name; ?>
					</a>
				</h4>
				<p><?php echo $board_count ?> <?php if ($board_count == 1) { _e('pin', 'pinclone'); } else { _e('pins', 'pinclone'); } ?></p>
				
				<div class="board-photo-frame">
					<a href="<?php echo home_url('/board/' . $board_id . '/'); ?>">
					<?php
					$count= 1;
					$post_array = array();
					foreach ($board_thumbnail_ids as $board_thumbnail_id) {
						if ($count == 1) {
							$imgsrc = wp_get_attachment_image_src($board_thumbnail_id, 'medium');
							$imgsrc = $imgsrc[0];
							array_unshift($post_array, $imgsrc);
						} else {
							$imgsrc = wp_get_attachment_image_src($board_thumbnail_id, 'thumbnail');
							$imgsrc = $imgsrc[0];
							array_unshift($post_array, $imgsrc);
						}
						$count++;
					}
					
					$count = 1;
			
					$post_array_final = array_fill(0, 5, '');
					
					foreach ($post_array as $post_imgsrc) {
						array_unshift($post_array_final, $post_imgsrc);
						array_pop($post_array_final);
					}
					
					foreach ($post_array_final as $post_final) {
						if ($count == 1) {
							if ($post_final !=='') {
							?>
							<div class="board-main-photo-wrapper">
								<img src="<?php echo $post_final; ?>" class="board-main-photo" alt="" />
							</div>
							<?php
							} else {
							?>
							<div class="board-main-photo-wrapper">
							</div>
							<?php 
							}
						} else if ($post_final !=='') {
							?>
							<div class="board-photo-wrapper">
							<img src="<?php echo $post_final; ?>" class="board-photo" alt="" />
							</div>
							<?php
						} else {
							?>
							<div class="board-photo-wrapper">
							</div>
							<?php
						}
						$count++;
					}
					?>
					</a>
					
					<?php if ($user_info->ID != $user_ID) { ?>
						<button class="btn follow pinclone-follow<?php if ($followed = pinclone_followed($board_id)) { echo ' disabled'; } ?>" data-author_id="<?php echo $user_info->ID; ?>" data-board_id="<?php echo $board_id;  ?>" data-board_parent_id="<?php echo $board_parent_id; ?>" type="button"><?php if (!$followed) { _e('Follow', 'pinclone'); } else { _e('Unfollow', 'pinclone'); } ?></button>
					<?php } else { ?>
						<a class="btn edit-board" href="<?php echo home_url('/boards-settings/?i=') . $board_id; ?>"><?php _e('Edit Board', 'pinclone'); ?></a>
					<?php } ?>
				</div>
			</div>
		<?php } //end foreach	?>
		
		<?php if ($maxpage != 0) { ?>
		<div id="navigation">
			<ul class="pager">				
				<?php if ($pnum != 1 && $maxpage >= $pnum) { ?>
				<li id="navigation-previous">
					<a href="<?php echo get_author_posts_url($user_info->ID); ?>?pnum=<?php echo $pnum-1; ?>"><?php _e('&laquo; Previous', 'pinclone') ?></a>
				</li>
				<?php } ?>
				
				<?php if ($maxpage != 1 && $maxpage != $pnum) { ?>
				<li id="navigation-next">
					<a href="<?php echo get_author_posts_url($user_info->ID); ?>?pnum=<?php echo $pnum+1; ?>"><?php _e('Next &raquo;', 'pinclone') ?></a>
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
		
		<div class="clearfix"></div>
	</div></div>

	<?php } else { ?>
		<div class="bigmsg">
			<h2><?php _e('Nothing yet.', 'pinclone'); ?></h2>
		</div>
	</div>
	<?php }
}
get_footer();
?>