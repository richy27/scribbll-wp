<div id="sidebar-left" class="sidebar">
<?php if (is_single() && !in_category(pinclone_blog_cats())) { ?>
	<div class="sidebar-left-single">
	<?php //start board section
		if (pinclone_get_post_board()) {
			$board_id = pinclone_get_post_board()->term_id;
			$board_parent_id = pinclone_get_post_board()->parent;
			$board_name = pinclone_get_post_board()->name;
			$board_count = pinclone_get_post_board()->count;
			$board_slug = pinclone_get_post_board()->slug;
			$board_link = get_term_link($board_slug, 'board');
					
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
				<h4><a href="<?php echo $board_link; ?>"><?php echo $board_name; ?></a></h4>
				<p><?php echo $board_count ?> <?php if ($board_count == 1) { _e('pin', 'pinclone'); } else { _e('pins', 'pinclone'); } ?></p>
				
				<div class="board-photo-frame">
					<a href="<?php echo $board_link; ?>">
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
							?>
							<div class="board-main-photo-wrapper">
								<img src="<?php echo $post_final; ?>" class="board-main-photo" alt="" />
							</div>
							<?php
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
					
					<?php global $user_ID; if ($post->post_author != $user_ID) { ?>
						<button class="btn follow pinclone-follow<?php if ($followed = pinclone_followed($board_id)) { echo ' disabled'; } ?>" data-author_id="<?php echo $post->post_author; ?>" data-board_id="<?php echo $board_id;  ?>" data-board_parent_id="<?php echo $board_parent_id; ?>" type="button"><?php if (!$followed) { _e('Follow', 'pinclone'); } else { _e('Unfollow', 'pinclone'); } ?></button>
					<?php } else { ?>
						<a class="btn edit-board" href="<?php echo home_url('/boards-settings/?i=') . $board_id; ?>"><?php _e('Edit Board', 'pinclone'); ?></a>
					<?php } ?>
				</div>
			</div>
		<?php } //end board section ?>
		
		<?php
		//start also from section
		$photo_source_domain = get_post_meta($post->ID, '_Photo Source Domain', true);
		if ($photo_source_domain != '' ) {
			$loop_domain_args = array(
				'posts_per_page' => 4,
				'meta_key' => '_Photo Source Domain',
				'meta_value' => $photo_source_domain,
				'post__not_in' => array($post->ID),
				'meta_query' => array(
					array(
					'key' => '_Original Post ID',
					'compare' => 'NOT EXISTS'
					)
				)
			);
			
			$loop_domain = new WP_Query($loop_domain_args);
			if ($loop_domain->post_count > 0) {
			?>
			<div class="board-domain">
				<p><?php _e('Also from', 'pinclone'); ?> <a href="<?php echo home_url('/source/') . $photo_source_domain; ?>/"><?php echo $photo_source_domain; ?></a></p>
				<a href="<?php echo home_url('/source/') . $photo_source_domain; ?>/">
				<?php
				$post_domain_array = array();
				while ($loop_domain->have_posts()) : $loop_domain->the_post();
					$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($loop_board->ID),'thumbnail');
					$imgsrc = $imgsrc[0];
					array_unshift($post_domain_array, $imgsrc);
				endwhile;
				wp_reset_query();
		
				$post_domain_array_final = array_fill(0, 4, '');
				
				foreach ($post_domain_array as $post_imgsrc) {
					array_unshift($post_domain_array_final, $post_imgsrc);
					array_pop($post_domain_array_final);
				}
				
				foreach ($post_domain_array_final as $post_final) {
					if ($post_final !=='') {
					?>
						<div class="board-domain-wrapper">
							<img src="<?php echo $post_final; ?>" alt="" />
						</div>
					<?php
					} else {
						?>
						<div class="board-domain-wrapper">
						</div>
						<?php
					}
				}
				?>
					<div class="clearfix"></div>
				</a>
			</div>
		<?php }
		} //end also from section ?>
	</div>
	
	<div class="clearfix"></div>
<?php 
}

if (!dynamic_sidebar('sidebar-left')) :
endif; ?>
</div>