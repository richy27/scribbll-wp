<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Inkzine
 */

get_header(); ?>

	</div>
	<?php while ( have_posts() ) : the_post(); ?>
	<div id="title-bar" data-stellar-background-ratio="0.5">
		<h1 class="bar-entry-title container"><?php the_title(); ?></h1>
	</div>	
	<?php endwhile; ?>	

	<div class="container col-md-12"><!-- restart previous section-->
	
	<div id="primary" class="content-area primary-single col-md-8">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php //inkzine_content_nav( 'nav-below' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_sidebar('footer'); ?>
<?php get_footer(); ?>