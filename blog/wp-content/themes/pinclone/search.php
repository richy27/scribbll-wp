<?php get_header(); global $user_ID; ?>

<div class="container-fluid">
	<div class="row-fluid">
		<div id="userbar" class="navbar">
			<div class="navbar-inner">
				<ul class="nav">
					<li<?php if (!isset($_GET['q'])) { echo ' class="active"'; } ?>><a href="<?php echo home_url('/?s=') . str_replace(' ','+',get_search_query()); ?>"><strong><?php _e('Pins', 'pinclone'); ?></strong></a></li>
					<?php if ($user_ID) { ?>
					<li<?php if (isset($_GET['q']) && $_GET['q'] == 'ownpins') { echo ' class="active"'; } ?>><a href="<?php echo home_url('/?s=') . str_replace(' ','+',get_search_query()); ?>&q=ownpins"><strong><?php _e('My Own Pins', 'pinclone'); ?></strong></a></li>
					<?php } ?>
					<li<?php if (isset($_GET['q']) && $_GET['q'] == 'boards') { echo ' class="active"'; } ?>><a href="<?php echo home_url('/?s=') . str_replace(' ','+',get_search_query()); ?>&q=boards"><strong><?php _e('Boards', 'pinclone'); ?></strong></a></li>
					<?php if (of_get_option('posttags') != 'disable') { ?>
					<li<?php if (isset($_GET['q']) && $_GET['q'] == 'tags') { echo ' class="active"'; } ?>><a href="<?php echo home_url('/?s=') . str_replace(' ','+',get_search_query()); ?>&q=tags"><strong><?php _e('Tags', 'pinclone'); ?></strong></a></li>
					<?php } ?>
					<li<?php if (isset($_GET['q']) && $_GET['q'] == 'users') { echo ' class="active"'; } ?>><a href="<?php echo home_url('/?s=') . str_replace(' ','+',get_search_query()); ?>&q=users"><strong><?php _e('Users', 'pinclone'); ?></strong></a></li>
					<li>
				</ul>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>

	<?php
	if (isset($_GET['q']) && $_GET['q'] == 'boards') {
		//exclude the parent boards
	    $board_exclude = get_transient('search_board_exclude');
	    if ($board_exclude === false) {
			$board_exclude = $wpdb->get_col(
				"SELECT meta_value 
				FROM $wpdb->usermeta
				WHERE meta_key = '_Board Parent ID'
				"
			);

	        set_transient('search_board_exclude', $board_exclude, 10800);
		}
		
		$boards = get_terms('board', array('search' => get_search_query(), 'hide_empty' => false, 'exclude' => $board_exclude));
		$boards_count = count($boards);
		
		if ($boards_count > 0) {
		?>
		<div id="user-profile-boards">
		<?php	
			$pnum = intval($_GET['pnum']) ? $_GET['pnum'] : 1;
			$boards_per_page = 24;
			$maxpage = ceil($boards_count/$boards_per_page);
			$boards_paginated = get_terms('board', array('search' => get_search_query(), 'hide_empty' => false, 'orderby' => 'name', 'exclude' => $board_exclude, 'number' => $boards_per_page, 'offset' => ($pnum - 1) * $boards_per_page));
			
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
						<?php if ($board_count > 0) { ?>
						</a>
						<?php } ?>
					</h4>
					<p><?php echo $board_count ?> <?php if ($board_count == 1) { _e('pin', 'pinclone'); } else { _e('pins', 'pinclone'); } ?></p>
					
					<div class="board-photo-frame">
						<?php if ($board_count > 0) { ?>
						<a href="<?php echo home_url('/board/' . $board_id . '/'); ?>">
						<?php } ?>
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
						<a href="<?php echo home_url('/?s=') . str_replace(' ','+',get_search_query()); ?>&q=boards&pnum=<?php echo $pnum-1; ?>"><?php _e('&laquo; Previous', 'pinclone') ?></a>
					</li>
					<?php } ?>
					
					<?php if ($maxpage != 1 && $maxpage != $pnum) { ?>
					<li id="navigation-next">
						<a href="<?php echo home_url('/?s=') . str_replace(' ','+',get_search_query()); ?>&q=boards&pnum=<?php echo $pnum+1; ?>"><?php _e('Next &raquo;', 'pinclone') ?></a>
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
			
	} else if (isset($_GET['q']) && $_GET['q'] == 'users') {
		$pnum = isset($_GET['pnum']) ? intval($_GET['pnum']) : 1;
		$args = array(
			'search' => '*' . get_search_query() . '*',
			'search_columns' => array('user_login'),
			'orderby' => 'display_name',
			'number' => get_option('posts_per_page'),
			'offset' => ($pnum-1) * get_option('posts_per_page')
		 );
	
		$search_user_query = new WP_User_Query($args);
		$maxpage = ceil($search_user_query->total_users/get_option('posts_per_page'));
		$user_info = get_user_by('id', $user_ID);
	
		if ($search_user_query->total_users > 0) {
			echo '<div id="user-profile-follow" class="row-fluid">';
			foreach ($search_user_query->results as $search_user) {
				?>
				<div class="follow-wrapper">
					<div class="post-content">
					<?php
					if ($search_user->ID != $user_info->ID) {
					?>
					<button class="btn follow pinclone-follow<?php $parent_board = get_user_meta($search_user->ID, '_Board Parent ID', true); if ($followed = pinclone_followed($parent_board)) { echo ' disabled'; } ?>" data-author_id="<?php echo $search_user->ID; ?>" data-board_id="<?php echo $parent_board; ?>" data-board_parent_id="0" data-disable_others="no" type="button"><?php if (!$followed) { _e('Follow', 'pinclone'); } else { _e('Unfollow', 'pinclone'); } ?></button>
					<?php } else { ?>
					<a class="btn follow disabled"><?php _e('Myself!', 'pinclone'); ?></a>
					<?php } ?>
						<div class="user-avatar">
							<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $search_user->user_nicename; ?>/"><?php echo get_avatar($search_user->ID , '32'); ?></a>
						</div>
						
						<div class="user-name">
							<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $search_user->user_nicename; ?>/">
								<h4><?php echo $search_user->display_name; ?></h4>
								<p><?php echo count_user_posts($search_user->ID); ?> <?php _e('Pins', 'pinclone'); ?> - <?php if ('' == $followers_count = get_user_meta($search_user->ID, '_Followers Count', true)) echo '0'; else echo $followers_count; ?> <?php _e('Followers', 'pinclone'); ?></p>
							</a>
						</div>
					</div>
				</div>
			<?php 
			}
			
			if ($maxpage != 0) { ?>
			<div id="navigation">
				<ul class="pager">				
					<?php if ($pnum != 1 && $maxpage >= $pnum) { ?>
					<li id="navigation-previous">
						<a href="<?php echo home_url('/?s=') . str_replace(' ','+',get_search_query()); ?>&q=users&pnum=<?php echo $pnum-1; ?>"><?php _e('&laquo; Previous', 'pinclone') ?></a>
					</li>
					<?php } ?>
					
					<?php if ($maxpage != 1 && $maxpage != $pnum) { ?>
					<li id="navigation-next">
						<a href="<?php echo home_url('/?s=') . str_replace(' ','+',get_search_query()); ?>&q=users&pnum=<?php echo $pnum+1; ?>"><?php _e('Next &raquo;', 'pinclone') ?></a>
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
							<h2><?php _e('Nobody yet.', 'pinclone'); ?></h2>
					</div>
				</div>
			</div>
		</div>
		<?php
		}
		
	} else if (isset($_GET['q']) && $_GET['q'] == 'tags') {
		$args = array(
			'search' => get_search_query(),
			'orderby' => 'count',
			'order' => 'DESC',
			'number' => '100',
		 );
	
		$search_tags = get_tags($args);

		if (!empty($search_tags)) {
			echo '<div id="search-tags" class="row-fluid">';
	
			foreach ($search_tags as $tag) {
				echo '<a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . ' (' . $tag->count . ')</a>';
			}
	
			echo '</div><div class="clearfix"></div></div>';
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
		
	} else if (isset($_GET['q']) && $_GET['q'] == 'ownpins') {
		?>
			<div class="row-fluid">		
				<div class="span12 text-center">
					<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="form-inline">
						<input type="text" name="s" class="input-small" value="<?php the_search_query(); ?>" />
						
						<?php
						echo pinclone_dropdown_categories(__('All categories', 'pinclone'), 'category', intval($_GET['category']));
						?>

						<select name="sort">
							<option<?php if ($_GET['sort'] == 'recent') echo ' selected'; ?> value="recent"><?php _e('Most recent', 'pinclone'); ?></option>
							<option<?php if ($_GET['sort'] == 'popular') echo ' selected'; ?> value="popular"><?php _e('Most popular', 'pinclone'); ?></option>
							<?php if (of_get_option('price_currency') != '') { ?>
								<option<?php if ($_GET['sort'] == 'pricelowest') echo ' selected'; ?> value="pricelowest"><?php _e('Price lowest', 'pinclone'); ?></option>
								<option<?php if ($_GET['sort'] == 'pricehighest') echo ' selected'; ?> value="pricehighest"><?php _e('Price highest', 'pinclone'); ?></option>
							<?php } ?>
						</select>
						
						<?php if (of_get_option('price_currency') != '') { ?>
							<input type="text" class="input-mini" name="minprice" placeholder="<?php _e('Min Price', 'pinclone'); ?>" value="<?php if (is_numeric($_GET['minprice']) && $_GET['minprice'] >= 0) echo $_GET['minprice']; else echo ''; ?>" />
							<input type="text" class="input-mini" name="maxprice" placeholder="<?php _e('Max Price', 'pinclone'); ?>" value="<?php if (is_numeric($_GET['maxprice']) && $_GET['maxprice'] >= $_GET['minprice'] && $_GET['maxprice'] >= 0) echo $_GET['maxprice']; else echo ''; ?>" />
						<?php } ?>
						
						<input type="hidden" name="q" value="ownpins" />
						<input type="hidden" name="filter" value="1" />
						<input type="submit" class="btn" value="<?php _e('Search', 'pinclone'); ?>" style="height:30px" />
					</form>
				</div>
			</div>
		<?php
		if (isset($_GET['filter']) && $_GET['filter'] == '1') {
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			
			if (isset($_GET['category']) && $_GET['category'] != '-1') {
				$args_category = array(
					'category__in' => intval($_GET['category'])
				);
			} else {
				$args_category = array();
			}
			
			switch($_GET['sort']) {
			case "recent":
				$order = '';
				$orderby = '';
				$meta_key = '';
			break;
			case "popular":
				if ('likes' == $popularity = of_get_option('popularity')) {
					$order = 'desc';
					$orderby = 'meta_value_num';
					$meta_key = '_Likes Count';
				} else if ($popularity == 'repins') {
					$order = 'desc';
					$orderby = 'meta_value_num';
					$meta_key = '_Repin Count';
				} else if ($popularity == 'comments') {
					$order = 'desc';
					$orderby = 'comment_count';
					$meta_key = '';
				} else {
					$order = 'desc';
					$orderby = 'comment_count';
					$meta_key = '';
				}
			break;
			case "pricelowest":
				$order = 'asc';
				$orderby = 'meta_value_num';
				$meta_key = '_Price';
			break;
			case "pricehighest":
				$order = 'desc';
				$orderby = 'meta_value_num';
				$meta_key = '_Price';
			break;
			default:
				$order = '';
				$orderby = '';
				$meta_key = '';
			}
			
			if (isset($_GET['category']) && $_GET['category'] != '-1') {
				$args_category = array(
					'category__in' => intval($_GET['category'])
				);
			} else {
				$args_category = array();
			}

			if (!is_numeric($_GET['minprice']) || $_GET['minprice'] < 0)
				$_GET['minprice'] = '';

			if (!is_numeric($_GET['maxprice']) || $_GET['maxprice'] < $_GET['minprice'] || $_GET['maxprice'] < 0)
				$_GET['maxprice'] = '';

			if ($_GET['minprice'] != '' && $_GET['maxprice'] == '') {
				$args_price = array(
					'meta_query' => array(
						array(
							'key' => '_Price',
							'value' => $_GET['minprice'],
							'type' => 'numeric',
							'compare' => '>='
						)
					)
				);
			} else if ($_GET['minprice'] == '' && $_GET['maxprice'] != '') {
				$args_price = array(
					'meta_query' => array(
						array(
							'key' => '_Price',
							'value' => $_GET['maxprice'],
							'type' => 'numeric',
							'compare' => '<='
						)
					)
				);
			} else if ($_GET['minprice'] != '' && $_GET['maxprice'] != '') {
				$args_price = array(
					'meta_query' => array(
						array(
							'key' => '_Price',
							'value' => array($_GET['minprice'], $_GET['maxprice']),
							'type' => 'numeric',
							'compare' => 'BETWEEN'
						)
					)
				);
			} else {
				$args_price = array();
			}
			
			$args = array(
				's' => get_search_query(),
				'author' => $user_ID,
				'orderby' => $orderby,
				'order' => $order,
				'meta_key' => $meta_key,
				'paged' => $paged
			);

            $args = array_merge($args_category, $args_price, $args);
		} else {
			$args = array(
				'author' => $user_ID,
				's' => get_search_query()
			);
		}
		if ($orderby == 'meta_value_num')
			add_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
			
		if ($orderby == 'meta_value_num')
			add_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
	
		query_posts($args);
		
		if ($orderby == 'meta_value_num')
			remove_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
			
		if ($orderby == 'meta_value_num')
			remove_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
		
		get_template_part('index', 'masonry');
	} else {
		?>
			<div class="row-fluid">		
				<div class="span12 text-center">
					<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="form-inline">
						<input type="text" name="s" class="input-small" value="<?php the_search_query(); ?>" />
						
						<?php echo pinclone_dropdown_categories(__('All categories', 'pinclone'), 'category', intval($_GET['category'])); ?>

						<select name="sort">
							<option<?php if ($_GET['sort'] == 'recent') echo ' selected'; ?> value="recent"><?php _e('Most recent', 'pinclone'); ?></option>
							<option<?php if ($_GET['sort'] == 'popular') echo ' selected'; ?> value="popular"><?php _e('Most popular', 'pinclone'); ?></option>
							<?php if (of_get_option('price_currency') != '') { ?>
								<option<?php if ($_GET['sort'] == 'pricelowest') echo ' selected'; ?> value="pricelowest"><?php _e('Price lowest', 'pinclone'); ?></option>
								<option<?php if ($_GET['sort'] == 'pricehighest') echo ' selected'; ?> value="pricehighest"><?php _e('Price highest', 'pinclone'); ?></option>
							<?php } ?>
						</select>
						
						<?php if (of_get_option('price_currency') != '') { ?>
							<input type="text" class="input-mini" name="minprice" placeholder="<?php _e('Min Price', 'pinclone'); ?>" value="<?php if (is_numeric($_GET['minprice']) && $_GET['minprice'] >= 0) echo $_GET['minprice']; else echo ''; ?>" />
							<input type="text" class="input-mini" name="maxprice" placeholder="<?php _e('Max Price', 'pinclone'); ?>" value="<?php if (is_numeric($_GET['maxprice']) && $_GET['maxprice'] >= $_GET['minprice'] && $_GET['maxprice'] >= 0) echo $_GET['maxprice']; else echo ''; ?>" />
						<?php } ?>
						
						<input type="hidden" name="filter" value="1" />
						<input type="submit" class="btn" value="<?php _e('Search', 'pinclone'); ?>" style="height:30px" />
					</form>
				</div>
			</div>
		<?php
		if (isset($_GET['filter']) && $_GET['filter'] == '1') {
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			
			if (isset($_GET['category']) && $_GET['category'] != '-1') {
				$args_category = array(
					'category__in' => intval($_GET['category'])
				);
			} else {
				$args_category = array();
			}
			
			switch($_GET['sort']) {
			case "recent":
				$order = '';
				$orderby = '';
				$meta_key = '';
			break;
			case "popular":
				if ('likes' == $popularity = of_get_option('popularity')) {
					$order = 'desc';
					$orderby = 'meta_value_num';
					$meta_key = '_Likes Count';
				} else if ($popularity == 'repins') {
					$order = 'desc';
					$orderby = 'meta_value_num';
					$meta_key = '_Repin Count';
				} else if ($popularity == 'comments') {
					$order = 'desc';
					$orderby = 'comment_count';
					$meta_key = '';
				} else {
					$order = 'desc';
					$orderby = 'comment_count';
					$meta_key = '';
				}
			break;
			case "pricelowest":
				$order = 'asc';
				$orderby = 'meta_value_num';
				$meta_key = '_Price';
			break;
			case "pricehighest":
				$order = 'desc';
				$orderby = 'meta_value_num';
				$meta_key = '_Price';
			break;
			default:
				$order = '';
				$orderby = '';
				$meta_key = '';
			}
			
			if (isset($_GET['category']) && $_GET['category'] != '-1') {
				$args_category = array(
					'category__in' => intval($_GET['category'])
				);
			} else {
				$args_category = array();
			}

			if (!is_numeric($_GET['minprice']) || $_GET['minprice'] < 0)
				$_GET['minprice'] = '';

			if (!is_numeric($_GET['maxprice']) || $_GET['maxprice'] < $_GET['minprice'] || $_GET['maxprice'] < 0)
				$_GET['maxprice'] = '';

			if ($_GET['minprice'] != '' && $_GET['maxprice'] == '') {
				$args_price = array(
					'meta_query' => array(
						array(
							'key' => '_Price',
							'value' => $_GET['minprice'],
							'type' => 'numeric',
							'compare' => '>='
						)
					)
				);
			} else if ($_GET['minprice'] == '' && $_GET['maxprice'] != '') {
				$args_price = array(
					'meta_query' => array(
						array(
							'key' => '_Price',
							'value' => $_GET['maxprice'],
							'type' => 'numeric',
							'compare' => '<='
						)
					)
				);
			} else if ($_GET['minprice'] != '' && $_GET['maxprice'] != '') {
				$args_price = array(
					'meta_query' => array(
						array(
							'key' => '_Price',
							'value' => array($_GET['minprice'], $_GET['maxprice']),
							'type' => 'numeric',
							'compare' => 'BETWEEN'
						)
					)
				);
			} else {
				$args_price = array();
			}
			
			$args = array(
				's' => get_search_query(),
				'orderby' => $orderby,
				'order' => $order,
				'meta_key' => $meta_key,
				'paged' => $paged
			);

            $args = array_merge($args_category, $args_price, $args);
			
			if ($orderby == 'meta_value_num')
				add_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
				
			if ($orderby == 'comment_count')
				add_filter('posts_orderby', 'pinclone_comments_orderby');
			
			query_posts($args);
			
			if ($orderby == 'meta_value_num')
				remove_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
				
			if ($orderby == 'comment_count')
				remove_filter('posts_orderby', 'pinclone_comments_orderby');
		}
	
		get_template_part('index', 'masonry');
	}
	?>

<?php get_footer(); ?>