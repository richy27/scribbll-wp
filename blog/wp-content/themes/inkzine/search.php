<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Inkzine
 */

get_header(); ?>

	<section id="primary" class="content-area col-md-8">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'inkzine' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */$ink_count = 0; $ink_row_count=0 ?>
			<?php while ( have_posts() ) : the_post(); 
				 if ($ink_count == 0 ) {echo "<div class='row-".$ink_row_count." row'>";}
			?>
			

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', 'home' );
				?>

			<?php 
				if ($ink_count == 2 )
					{
						echo "</div>";
						$ink_count=0;
						$ink_row_count++;
					}
				else {	
					$ink_count++;
				}
				
				endwhile; 
			?>

			<?php inkzine_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'search' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_sidebar('footer'); ?>
<?php get_footer(); ?>