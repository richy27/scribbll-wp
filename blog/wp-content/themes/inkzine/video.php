<?php
/**
 * The template for displaying Video Attachments.
 *
 * @package Inkzine
 */

get_header(); ?>

	</div>
	<?php while ( have_posts() ) : the_post(); ?>
	<div id="title-bar">
		<h1 class="bar-entry-title container"><?php the_title(); ?></h1>
	</div>	
	<?php endwhile; ?>	
	<div class="container col-md-12"><!-- restart previous section-->

	<div id="primary" class="content-area image-attachment">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">

		<div class="entry-meta">
			<?php inkzine_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<div class="featured-image-single">
			<?php if (has_post_thumbnail() )
				the_post_thumbnail();
				?>
		</div>
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'inkzine' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'inkzine' ) );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', __( ', ', 'inkzine' ) );

			if ( ! inkzine_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					$meta_text = __( '<i class="fa fa-tags"> </i> %2$s', 'inkzine' );
				} else {
					$meta_text = __( '', 'inkzine' );
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					$meta_text = __( '<i class="fa fa-folder-open"> </i> %1$s <i class="fa fa-tags"> </i> %2$s', 'inkzine' );
				} else {
					$meta_text = __( '<i class="fa fa-folder-open"> </i> %1$s', 'inkzine' );
				}

			} // end check for categories on this blog

			printf(
				$meta_text,
				$category_list,
				$tag_list
			);
		?>

		<?php edit_post_link( __( 'Edit', 'inkzine' ), '<span class="edit-link"><i class="fa fa-pencil-square-o"> </i> ', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_sidebar('footer'); ?>
<?php get_footer(); ?>
