<?php get_header(); global $user_ID, $wp_rewrite; ?>

<div class="container" id="single-pin" itemscope itemtype="http://schema.org/ImageObject">
	<div class="row">
		<div class="span9">
			<div class="row">
				<div id="double-left-column" class="span6 pull-right">
					<?php while (have_posts()) : the_post(); ?>
					<?php
					$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
					$imgsrc_full = $imgsrc;

					//exclude animated gif
					if (substr($imgsrc[0], -3) != 'gif' && intval($imgsrc[1]) > 520) {
						$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
					}
					
					if ($imgsrc[0] == '') {
						$imgsrc[0] = get_template_directory_uri() . '/img/blank.gif';
					}
					
					$earliest_post_id = get_post_meta($post->ID, "_Earliest Post ID", true);
					$original_post_id = get_post_meta($post->ID, "_Original Post ID", true);
					$photo_source = get_post_meta($post->ID, "_Photo Source", true);
					$photo_source_domain = parse_url($photo_source, PHP_URL_HOST);
					$post_video = pinclone_get_post_video($photo_source);
					?>
					<div id="post-<?php the_ID(); ?>" <?php post_class('post-wrapper'); ?>>
						<div class="post-top-wrapper">
							<div class="pull-left">
								<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . get_the_author_meta('user_nicename'); ?>/">
								<?php echo get_avatar($post->post_author, '48'); ?>
								</a>
							</div>
							
							<div class="post-top-wrapper-header">
								<?php if ($post->post_author != $user_ID) { ?> 
								<button class="btn pull-right follow pinclone-follow<?php if ($followed = pinclone_followed(pinclone_get_post_board()->parent)) { echo ' disabled'; } ?>" data-board_parent_id="0" data-author_id="<?php echo $post->post_author; ?>" data-board_id="<?php echo pinclone_get_post_board()->parent; ?>" type="button"><?php if (!$followed) { _e('Follow', 'pinclone'); } else { _e('Unfollow', 'pinclone'); } ?></button>
								<?php } ?>
								<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . get_the_author_meta('user_nicename'); ?>/">
									<div itemprop="author" class="post-top-wrapper-author"><?php echo get_the_author_meta('display_name'); ?></div>
								</a>
								<?php 
								if ($original_post_id != '' && $original_post_id != 'deleted') {
									_e('Repinned', 'pinclone');

								} else {
									_e('Pinned', 'pinclone');
								}
								echo ' ' . pinclone_human_time_diff(get_post_time('U', true));
								?>
								<time itemprop="datePublished" datetime="<?php the_time('Y'); ?>-<?php the_time('m'); ?>-<?php the_time('d'); ?>"></time>
							</div>
						</div>

						<?php if ($post->post_status == 'publish') { ?>
						<div class="post-share">
							<div class="fb-like" data-width="450" data-href="<?php the_permalink(); ?>" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false"></div>
							
							<p></p>
							
							<div class="fb-share-button" data-href="<?php the_permalink(); ?>" data-width="75" data-type="button_count"></div>

							<p></p>
							
							<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-text="<?php echo preg_replace('/[\n\r]/', ' ', mb_strimwidth(the_title_attribute('echo=0'), 0, 100, ' ...')); ?>">Tweet</a>
							
							<p></p>

							<div class="g-plusone" data-size="medium" data-href="<?php the_permalink(); ?>"></div>
							
							<p></p>
							
							<a data-pin-config="beside" href="//pinterest.com/pin/create/button/?url=<?php echo rawurlencode(get_permalink()); ?>&amp;media=<?php echo rawurlencode($imgsrc[0]); ?>&amp;description=<?php echo preg_replace('/[\n\r]/', ' ', rawurlencode(html_entity_decode(mb_strimwidth(the_title_attribute('echo=0'), 0, 255, ' ...'), ENT_QUOTES))); ?>" data-pin-do="buttonPin"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="Pinterest Pin It" /></a>
							
							<p></p>
							
							<a class="post-embed btn btn-mini"><strong>&lt;&gt; <?php _e('Embed', 'pinclone'); ?></strong></a>
							
							<p></p>
							
							<a class="post-email btn btn-mini"><strong>@&nbsp; <?php _e('Email', 'pinclone'); ?></strong></a>
							
							<p></p>
							
							<a class="post-report btn btn-mini"><strong><i class="fa fa-flag"></i>&nbsp; <?php _e('Report', 'pinclone'); ?></strong></a>

							<p></p>					

							<button class="post-close btn btn-mini hide"><i class="fa fa-times"></i></button>
						</div>
						<?php } ?>
						
						<div class="post-top-meta">
							<div class="pull-left">
								<div class="post-actionbar">
									<?php if ($post->post_status == 'publish' && (current_user_can('administrator') || current_user_can('editor') || current_user_can('author') || !is_user_logged_in())) { ?>
									<a class="pinclone-repin btn" data-post_id="<?php echo $post->ID ?>" href="#"><i class="fa fa-thumb-tack"></i> <?php _e('Repin', 'pinclone'); ?></a>
									<?php } ?>
									<?php if ($post->post_status == 'publish' && $post->post_author != $user_ID) { ?> 
									<button class="pinclone-like btn <?php if (pinclone_liked($post->ID)) { echo ' disabled'; } ?>" data-post_id="<?php echo $post->ID ?>" data-post_author="<?php echo $post->post_author; ?>" type="button"><i class="fa fa-heart"></i> <?php _e('Like', 'pinclone'); ?></button>
									<?php } if ($post->post_author == $user_ID || current_user_can('edit_others_posts')) { ?>
									<a class="pinclone-edit btn" href="<?php echo home_url('/pins-settings/'); ?>?i=<?php the_ID(); ?>"><?php _e('Edit', 'pinclone'); ?></a>
									<?php } ?>
									
									<?php									
									if (!$post_video) {
									?>
									<a class="pinclone-zoom btn" href="<?php echo $imgsrc_full[0]; ?>"><i class="fa fa-search-plus"></i> <?php _e('Zoom', 'pinclone'); ?></a>
									<?php } ?>
								</div>
							</div>
							<div class="pull-right">
								<?php if ($photo_source == '') { ?>
								<strong><?php _e('Uploaded by user', 'pinclone'); ?></strong>
								<?php 
								} else { 
									_e('From', 'pinclone'); ?> 
									<a href="<?php echo $photo_source; ?>" target="_blank"><?php echo $photo_source_domain; ?></a>
								<?php } ?>
							</div>
							
							<?php
							if ($post->post_status == 'pending') {
								echo '<div class="clearfix"></div><span class="label label-warning">' . __('Pending Review', 'pinclone') . '</span>';
							}
							?>
						</div>
						
						<div class="clearfix"></div>
						
						<?php if (of_get_option('single_pin_above_ad') != '') { ?>
						<div id="single-pin-above-ad">
							<?php eval('?>' . of_get_option('single_pin_above_ad')); ?>
						</div>
						<?php } ?>
						
						<div class="post-featured-photo">
							<div class="post-nav-next"><?php echo previous_post_link('%link', '<i class="fa fa-chevron-right"></i>', false, pinclone_blog_cats()); ?></div>
							<div class="post-nav-prev"><?php echo next_post_link('%link', '<i class="fa fa-chevron-left"></i>', false, pinclone_blog_cats()); ?></div>
		
						<?php if (of_get_option('price_currency') != '' && pinclone_get_post_price() != '') { ?>
							<div class="pricewrapper"><div class="pricewrapper-inner"><?php echo pinclone_get_post_price(); ?></div></div>
						<?php }	?>
						
						<?php if ($post_video) { ?>
							<div class="video-embed-wrapper">
								<?php echo $post_video; ?>
							</div>
							<img class="featured-thumb hide" src="<?php echo $imgsrc[0]; ?>" width="<?php echo $imgsrc[1]; ?>" height="<?php echo ($imgsrc[1] > 520) ? (round($imgsrc[1]/$imgsrc[2]*520)) : $imgsrc[2]; ?>" alt="<?php echo mb_strimwidth(the_title_attribute('echo=0'), 0, 255, ' ...') ?>" />
						<?php } else {  ?>

							<img class="featured-thumb" src="<?php echo $imgsrc[0]; ?>" width="<?php echo $imgsrc[1]; ?>" height="<?php echo ($imgsrc[1] > 520) ? (round($imgsrc[1]/$imgsrc[2]*520)) : $imgsrc[2]; ?>" alt="<?php echo mb_strimwidth(the_title_attribute('echo=0'), 0, 100, ' ...'); ?>" />
						<?php } ?>
						
						<?php if ($post->post_status == 'publish') { ?>
							<div class="post-share-horizontal visible-phone">
								<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo rawurlencode(get_permalink()); ?>&amp;layout=button_count&amp;width=135&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;share=true<?php if (get_option('wsl_settings_Facebook_app_id')) echo '&amp;appId=' . get_option('wsl_settings_Facebook_app_id'); ?>" style="border:none; overflow:hidden; width:135px; height:21px;"></iframe>

								<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-text="<?php echo preg_replace('/[\n\r]/', ' ', mb_strimwidth(the_title_attribute('echo=0'), 0, 100, ' ...')); ?>">Tweet</a>
	
								<div class="g-plusone" data-size="medium" data-href="<?php the_permalink(); ?>"></div>
								<script>(function() {var po=document.createElement('script');po.type='text/javascript';po.async=true;po.src='https://apis.google.com/js/plusone.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(po,s);})();</script>

								<a class="pinterest" data-pin-config="beside" href="//pinterest.com/pin/create/button/?url=<?php echo rawurlencode(get_permalink()); ?>&amp;media=<?php echo rawurlencode($imgsrc[0]); ?>&amp;description=<?php echo preg_replace('/[\n\r]/', ' ', rawurlencode(html_entity_decode(mb_strimwidth(the_title_attribute('echo=0'), 0, 100, ' ...'), ENT_QUOTES))); ?>" data-pin-do="buttonPin"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="Pinterest Pin It" /></a>
																
								<a class="post-embed btn btn-mini"><strong>&lt;&gt; <?php _e('Embed', 'pinclone'); ?></strong></a>
								
								<a class="post-email btn btn-mini"><strong>@&nbsp; <?php _e('Email', 'pinclone'); ?></strong></a>
								
								<a class="post-report btn btn-mini"><strong><i class="fa fa-flag"></i> <?php _e('Report', 'pinclone'); ?></strong></a>
							</div>
						</div>
						<?php } ?>

						<?php if (of_get_option('single_pin_below_ad') != '') { ?>
						<div id="single-pin-below-ad">
							<?php eval('?>' . of_get_option('single_pin_below_ad')); ?>
						</div>
						<?php } ?>
						
						<?php
						$tags = '';
						if (of_get_option('posttags') == 'enable') {
							$the_tags = get_the_tags();
							if ($the_tags) {
								foreach($the_tags as $the_tag) {
									$tags .= $the_tag->name . ', ';
								}
								$tags = substr($tags, 0, -2);
							}
						}
						?>

						<div class="post-content">
						
							<?php if (of_get_option('form_title_desc') != 'separate') { ?>		
							
								<?php if (mb_strlen(get_the_title()) < 120) { ?>
								
								
									<center><h1 itemprop="name" class="post-title" data-title="<?php echo esc_attr($post->post_title); ?>" data-tags="<?php echo esc_attr($tags); ?>" data-price="<?php echo esc_attr(pinclone_get_post_price(false)); ?>" data-content="<?php echo esc_attr($post->post_content); ?>"><?php echo wpautop(preg_replace_callback('/<a[^>]+/', 'pinclone_nofollow_callback', get_the_title())); ?></h1><center>
								<?php } else { ?>
									<center><div itemprop="name" class="post-title" data-title="<?php echo esc_attr($post->post_title); ?>" data-tags="<?php echo esc_attr($tags); ?>" data-price="<?php echo esc_attr(pinclone_get_post_price(false)); ?>" data-content="<?php echo esc_attr($post->post_content); ?>"><?php echo wpautop(preg_replace_callback('/<a[^>]+/', 'pinclone_nofollow_callback', get_the_title())); ?></div><center>
								<?php } ?>
							<?php } else { ?>
									<center><h1 itemprop="name" class="post-title post-title-large" data-title="<?php echo esc_attr($post->post_title); ?>" data-tags="<?php echo esc_attr($tags); ?>" data-price="<?php echo esc_attr(pinclone_get_post_price(false)); ?>" data-content="<?php echo esc_attr($post->post_content); ?>"><?php the_title(); ?></h1><center>
							<?php } ?>

							<?php
							echo '<div itemprop="description" class="thecontent">' . preg_replace_callback('/<a[^>]+/', 'pinclone_nofollow_callback', apply_filters('the_content', get_the_content()))  . '</div>';

							if ($the_tags) {
								echo '<div itemprop="keywords" class="thetags">';
								
								foreach($the_tags as $the_tag) {
									echo '<a href="' . get_tag_link($the_tag->term_id). '">' . $the_tag->name . '</a> '; 
								}
								
								echo '</div>';
							}
							wp_link_pages( array( 'before' => '<p><strong>' . __('Pages:', 'pinclone') . '</strong>', 'after' => '</p>' ) );
							?>

							<?php if ($original_post_id != '' && $original_post_id != 'deleted') { ?>
								<p class="post-original-author">
								<?php 
								$original_postdata = get_post($original_post_id, 'ARRAY_A');
								$original_author = get_user_by('id', $original_postdata['post_author']);
								$board = wp_get_post_terms($original_post_id, 'board', array("fields" => "all")); 
								?>
								<?php 
								if ($board) {
									_e('Repinned from', 'pinclone');
									?> 
									<a href="<?php echo get_term_link($board[0]->slug, 'board'); ?>"><?php echo $board[0]->name; ?></a> 
									<?php _e('by', 'pinclone'); ?> <a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $original_author->user_nicename; ?>/"><?php echo $original_author->display_name; ?></a> 
								<?php } ?>
								</p>
							<?php }	?>
							
							<?php 
								if ($earliest_post_id != '' && $earliest_post_id != 'deleted') { ?>
								<p class="post-original-author">
								<?php 
								$earliest_postdata = get_post($earliest_post_id, 'ARRAY_A');
								$earliest_author = get_user_by('id', $earliest_postdata['post_author']);
								$earliest_board = wp_get_post_terms($earliest_post_id, 'board', array("fields" => "all")); 
								?>
								<?php 
								if ($earliest_board) {
									_e('Originally pinned onto', 'pinclone'); ?> 
									<a href="<?php echo get_term_link($earliest_board[0]->slug, 'board'); ?>"><?php echo $earliest_board[0]->name; ?></a> 
									<?php _e('by', 'pinclone'); ?> <a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $earliest_author->user_nicename; ?>/"><?php echo $earliest_author->display_name; ?></a>
								<?php } ?>
								</p>
							<?php }	?>
						</div>
						
<?php if(function_exists('the_ratings')) { ?>
<center>
<div style="height:35px;">
<span style="font-size: 0.9em;font-weight:normal;">
<?php the_ratings(); ?>
</span>
</div>
</center><br><br>
<?php } ?>
						
						<div class="post-comments">
							<div class="post-comments-wrapper">
								<?php if ($post->post_status == 'publish') { ?>
									<?php comments_template(); ?>
									<?php if (of_get_option('facebook_comments') != 'disable') { ?>
									<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-num-posts="5"<?php if (of_get_option('color_scheme') == 'dark') { echo ' data-colorscheme="dark"'; } ?> data-width="100%"></div>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
						
						<?php
						if (pinclone_get_post_board()) {
						?>
						<div class="post-board hide">
							<div class="post-board-wrapper">
								<?php if ($post->post_author != $user_ID) { ?>
								<button class="btn btn-mini pull-right follow pinclone-follow<?php if ($followed = pinclone_followed(pinclone_get_post_board()->term_id)) { echo ' disabled'; } ?>" data-author_id="<?php echo $post->post_author; ?>" data-board_id="<?php echo pinclone_get_post_board()->term_id;  ?>" data-board_parent_id="<?php echo pinclone_get_post_board()->parent; ?>" type="button"><?php if (!$followed) { _e('Follow', 'pinclone'); } else { _e('Unfollow', 'pinclone'); } ?></button>
								<?php } ?>
								<h4><?php _e('Pinned onto', 'pinclone') ?> <?php the_terms($post->ID, 'board', '<span>', ', ', '</span>'); ?></h4>
								<?php							
								$board_id = pinclone_get_post_board()->term_id;
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
										LIMIT 0, 10
										) AS v2 ON v.post_id = v2.object_id
										AND v.meta_key = '_thumbnail_id'
									",
									$board_id
								));
								?>
									<a href="<?php echo $board_link; ?>">
									<?php
									$post_array = array();
									foreach ($board_thumbnail_ids as $board_thumbnail_id) {
										$board_imgsrc = wp_get_attachment_image_src($board_thumbnail_id, 'thumbnail');
										$board_imgsrc = $board_imgsrc[0];
										array_unshift($post_array, $board_imgsrc);
									}

									$post_array_final = array_fill(0, 10, '');
									
									foreach ($post_array as $post_imgsrc) {
										array_unshift($post_array_final, $post_imgsrc);
										array_pop($post_array_final);
									}
									
									foreach ($post_array_final as $post_final) {
										if ($post_final !=='') {
											?>
											<div class="post-board-photo">
												<img src="<?php echo $post_final; ?>" alt="" />
											</div>
											<?php
										} else {
											?>
											<div class="post-board-photo">
											</div>
											<?php
										}
									}
									?>
									</a>
							</div>
							
							<div class="clearfix"></div>
						</div>
						<?php } ?>
						
						<?php
						if (isset($photo_source_domain)) {
							$loop_domain_args = array(
								'posts_per_page' => 10,
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
							<div id="post-board-source" class="post-board hide">
								<div class="post-board-wrapper">
									<h4><?php _e('Also from', 'pinclone'); ?> <a href="<?php echo home_url('/source/') . $photo_source_domain; ?>/"><?php echo $photo_source_domain; ?></a></h4>
										<a href="<?php echo home_url('/source/') . $photo_source_domain; ?>/">
										<?php
										$post_domain_array = array();
										while ($loop_domain->have_posts()) : $loop_domain->the_post();
											$domain_imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($loop_board->ID),'thumbnail');
											$domain_imgsrc = $domain_imgsrc[0];
											array_unshift($post_domain_array, $domain_imgsrc);
										endwhile;
										wp_reset_query();
										
										$post_domain_array_final = array_fill(0, 10, '');
										
										foreach ($post_domain_array as $post_imgsrc) {
											array_unshift($post_domain_array_final, $post_imgsrc);
											array_pop($post_domain_array_final);
										}
										
										foreach ($post_domain_array_final as $post_final) {
											if ($post_final !=='') {
												?>
												<div class="post-board-photo">
													<img src="<?php echo $post_final; ?>" alt="" />
												</div>
												<?php
											} else {
												?>
												<div class="post-board-photo">
												</div>
												<?php
											}
										}
										?>
										</a>
								</div>
								<div class="clearfix"></div>
							</div>
						<?php
							}
						}
						
						$post_likes = get_post_meta($post->ID, "_Likes User ID");
						$post_likes_count = count($post_likes[0]);
						if (!empty($post_likes[0])) {
						$post_likes[0] = array_slice($post_likes[0], -16);
						?>
						<div class="post-likes">
							<div class="post-likes-wrapper">
								<h4><?php _e('Likes', 'pinclone'); ?></h4>
								<div class="post-likes-avatar">
								<?php
								foreach ($post_likes[0] as $post_like) {
									$like_author = get_user_by('id', $post_like);
									?>
									<a id="likes-<?php echo $post_like; ?>" href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $like_author->user_nicename; ?>/" rel="tooltip" title="<?php echo esc_attr($like_author->display_name); ?>">
									<?php echo get_avatar($like_author->ID, '48'); ?>
									</a>
								<?php 
								}
								if ($post_likes_count > 16) {
								?>
									<p class="more-likes"><strong>+<?php echo $post_likes_count - 16 ?></strong> <?php _e('more likes', 'pinclone'); ?></p>
								<?php } ?>
								</div>
							</div>
						</div>
						<?php } ?>
						
						<?php
						$post_repins = get_post_meta($post->ID, "_Repin Post ID");
						$post_repins_count = count($post_repins[0]);
						if (!empty($post_repins[0])) {
						$post_repins[0] = array_slice($post_repins[0], -10);
						?>
						<div id="post-repins">
							<div class="post-repins-wrapper">
								<h4><?php _e('Repins', 'pinclone'); ?></h4>
								<ul>
								<?php
								foreach ($post_repins[0] as $post_repin) {
									$repin_postdata = get_post($post_repin, 'ARRAY_A');
									$repin_author = get_user_by('id', $repin_postdata['post_author']);
									?>
									<li id="repins-<?php echo $post_repin; ?>">
									<a class="post-repins-avatar pull-left" href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $repin_author->user_nicename; ?>/">
									<?php echo get_avatar($repin_author->ID, '48'); ?>
									</a> 
									<div class="post-repins-content">
									<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $repin_author->user_nicename; ?>/">
									<?php echo $repin_author->display_name; ?>
									</a> 
									<?php
									_e('onto', 'pinclone');
									$board = wp_get_post_terms($post_repin, 'board', array("fields" => "all"));
									echo ' <a href="' . get_term_link($board[0]->slug, 'board') . '">' . $board[0]->name . '</a></div>';
									?>
									</li>
								<?php 
								}	
								if ($post_repins_count > 10) {
								?>
									<li class="more-repins"><strong>+<?php echo $post_repins_count - 10; ?></strong> <?php _e('more repins', 'pinclone'); ?></li>
								<?php } ?>
								</ul>
							</div>
						</div>
						<?php } ?>
						
						<div id="post-zoom-overlay"></div>
						<div id="post-embed-overlay"></div>
						<div id="post-email-overlay"></div>
						<div id="post-report-overlay"></div>
						
						<div id="post-fullsize" class="lightbox hide" tabindex="-1" role="dialog" aria-hidden="true">
							<div class='lightbox-header'>
								<button type="button" class="close" id="post-fullsize-close" aria-hidden="true">&times;</button>
							</div>
							<div class="lightbox-content">
								<img itemprop="image" src="<?php echo $imgsrc_full[0]; ?>" width="<?php echo $imgsrc_full[1]; ?>" height="<?php echo $imgsrc_full[2]; ?>" alt="<?php echo mb_strimwidth(the_title_attribute('echo=0'), 0, 100, ' ...'); ?>" />
							</div>
						</div>
						
						<div class="modal hide" id="post-embed-box" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-header">
								<button id="post-embed-close" type="button" class="close" aria-hidden="true">x</button>
								<div><?php _e('Embed Pin on Your Blog', 'pinclone'); ?></div>
							</div>
							
							<div class="modal-footer">
								<?php $size = getimagesize(realpath(str_replace(home_url('/'),'',$imgsrc[0]))); ?>
								<input type="text" id="embed-width" value="<?php echo $size[0]; ?>" /><span class="help-inline"> <?php _e('px -Image Width', 'pinclone'); ?></span>
								<input type="text" id="embed-height" value="<?php echo $size[1]; ?>" /><span class="help-inline"> <?php _e('px -Image Height', 'pinclone'); ?></span>
								<textarea><div style='padding-bottom: 2px;line-height:0px;'><a href='<?php the_permalink(); ?>' target='_blank'><img src='<?php echo $imgsrc[0]; ?>' border='0' width='<?php echo $size[0]; ?>' height='<?php echo $size[1]; ?>' /></a></div><div style='float:left;padding-top:0px;padding-bottom:0px;'><p style='font-size:10px;color:#76838b;'><?php _e('Source', 'pinclone'); ?>: <a style='text-decoration:underline;font-size:10px;color:#76838b;' href='<?php echo $photo_source;  ?>'><?php echo $photo_source_domain; ?></a> <?php _e('via', 'pinclone'); ?> <a style='text-decoration:underline;font-size:10px;color:#76838b;' href='<?php echo home_url('/' . $wp_rewrite->author_base . '/') . get_the_author_meta('user_nicename'); ?>' target='_blank'><?php echo get_the_author_meta('display_name'); ?></a> <?php _e('on', 'pinclone'); ?> <a style='text-decoration:underline;color:#76838b;' href='<?php echo home_url('/'); ?>' target='_blank'><?php bloginfo('name'); ?></a></p></div></textarea>
							</div>
						</div>
						
						<div class="modal hide" id="post-email-box" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-header">
								<button id="post-email-close" type="button" class="close" aria-hidden="true">x</button>
								<div><?php _e('Email This Pin', 'pinclone'); ?></div>
							</div>
							
							<div class="modal-footer">
								<input type="text" id="recipient-name" /><span class="help-inline"> <?php _e('Recipient Name', 'pinclone'); ?></span>
								<input type="email" id="recipient-email" /><span class="help-inline"> <?php _e('Recipient Email', 'pinclone'); ?></span>
								<input type="hidden" id="email-post-id" value="<?php echo $post->ID; ?>" />
								<textarea placeholder="<?php _e('Message (optional)', 'pinclone'); ?>"></textarea>
								<input class="btn btn-primary" type="submit" disabled="disabled" value="<?php _e('Send Email', 'pinclone'); ?>" id="post-email-submit" name="post-email-submit">
								<div class="ajax-loader-email-pin ajax-loader hide"></div>
							</div>
						</div>
						
						<div class="modal hide" id="post-report-box" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-header">
								<button id="post-report-close" type="button" class="close" aria-hidden="true">x</button>
								<div><?php _e('Report This Pin', 'pinclone'); ?></div>
							</div>
							
							<div class="modal-footer">
								<input type="hidden" id="report-post-id" value="<?php echo $post->ID; ?>" />
								<textarea placeholder="<?php _e('Please write a little about why you want to report this pin.', 'pinclone'); ?>"></textarea>
								<input class="btn btn-primary" type="submit" disabled="disabled" value="<?php _e('Report Pin', 'pinclone'); ?>" id="post-report-submit" name="post-report-submit">
								<div class="ajax-loader-report-pin ajax-loader hide"></div>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
				</div>
				
				<div id="single-right-column" class="span3">
					<?php
					if (!isset($_GET['lightbox'])) {
						get_sidebar('left');
					}
					?>
				</div>
			</div>
		</div>

		<div class="span3">
			<?php
			if (!isset($_GET['lightbox'])) {
				get_sidebar('right');
			}
			?>
		</div>
	</div>
</div>

<?php
if (!isset($_GET['lightbox'])) {
	get_template_part('single', 'masonry');
}

get_footer();
?>