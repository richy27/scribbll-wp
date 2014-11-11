<?php get_header(); ?>

<div class="container">
	<div class="row">
		<div class="span9">
			<div class="row">
				<div id="double-left-column" class="span6 pull-right">
					<?php while (have_posts()) : the_post(); ?>
					<div id="post-<?php the_ID(); ?>" <?php post_class('post-wrapper'); ?>>
						<div class="h1-wrapper">
							<h1><?php the_title(); ?></h1>
						</div>		

						<div class="post-meta-top">
							<div class="pull-right"><a href="#navigation"><?php comments_number(__('0 Comments', 'pinclone'), __('1 Comment', 'pinclone'), __('% Comments', 'pinclone'));?></a><?php edit_post_link(__('Edit', 'pinclone'), ' | '); ?></div>
							<div class="pull-left"><?php echo pinclone_human_time_diff(get_post_time('U', true)) . ' / ';the_author(); ?></div>
						</div>

						<?php
						$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
						if ($imgsrc[0] != '') {
						?>
						<div class="post-featured-photo">
							<img class="featured-thumb" src="<?php echo $imgsrc[0]; ?>" alt="<?php the_title_attribute(); ?>" />
						</div>
						<?php } ?>

						<div class="post-content">
							<?php
							the_content();
							wp_link_pages( array( 'before' => '<p><strong>' . __('Pages:', 'pinclone') . '</strong>', 'after' => '</p>' ) );
							?>
							
							<div class="clearfix"></div>
							
							<div class="post-meta-category-tag">								
								<?php
								$categories = get_the_category();
								if($categories){
									echo __('Categories', 'pinclone') . ' <span class="thetags">';
									
									foreach($categories as $category) {
										echo '<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a> ';
									}
									
									echo '</span>';
								}
								
								$posttags = get_the_tags();
								if ($posttags) {
									echo __('Tags', 'pinclone') . ' <span class="thetags">';
									
									foreach($posttags as $tag) {
										echo '<a href="' . get_tag_link($tag->term_id). '">' . $tag->name . '</a> '; 
									}
									
									echo '</span>';
								}
								?>
							</div>
							
							<div>
								<ul class="pager">
									<li class="previous"><?php previous_post_link('%link', '&laquo; %title', true); ?></li>
									<li class="next"><?php next_post_link('%link', '%title &raquo;', true); ?></li>
								</ul>
							</div>
						</div>
						
						<div class="post-comments">
							<div class="post-comments-wrapper">
								<?php comments_template(); ?>
								<?php if (of_get_option('facebook_comments') != 'disable') { ?>
								<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-num-posts="5"<?php if (of_get_option('color_scheme') == 'dark') { echo ' data-colorscheme="dark"'; } ?> data-width="100%"></div>
								<?php } ?>
							</div>
						</div>
						
					</div>
					<?php endwhile; ?>
				</div>
				
				<div id="single-right-column" class="span3">
					<?php get_sidebar('left'); ?>
				</div>
			</div>
		</div>
		
		<div class="span3">
			<?php get_sidebar('right'); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>