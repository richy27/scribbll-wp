<?php
/*
Template Name: _everything
*/
?>

<?php get_header(); ?>

<div class="subpage-title container-fluid">
	<div class="row-fluid">
		<div class="span4 hidden-phone"></div>
		<div class="span4">
			<h1><?php the_title(); ?></h1>
		</div>
		<div class="span4"></div>
	</div>
</div>

<div class="container-fluid">
	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args = array(
		'post_type' => 'post',
		'paged' => $paged
	);
	
	query_posts($args);

	get_template_part('index', 'masonry');
	get_footer();
?>