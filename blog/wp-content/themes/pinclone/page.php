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

						<div class="post-content">
							<div class="thecontent">
							<?php the_content(); ?>
							</div>
							<?php
							wp_link_pages( array( 'before' => '<p><strong>' . __('Pages:', 'pinclone') . '</strong>', 'after' => '</p>' ) );
							edit_post_link(__('Edit Page', 'pinclone'),'<p>[ ',' ]</p>');
							?>
						</div>
						
						<div class="post-comments">
							<div class="post-comments-wrapper">
								<?php comments_template(); ?>
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