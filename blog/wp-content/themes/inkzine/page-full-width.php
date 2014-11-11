<?php
/*
Template Name: Full Width(No Sidebar)
*/

get_header(); ?>

	</div>
	<?php while ( have_posts() ) : the_post(); ?>
	<div id="title-bar">
		<h1 class="bar-entry-title container"><?php the_title(); ?></h1>
	</div>	
	<?php endwhile; ?>	
	<div class="container col-md-12"><!-- restart previous section-->

	<div id="primary" class="full-width content-area col-md-12">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template();
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	
<?php get_sidebar('footer'); ?>
<?php get_footer(); ?>
