<?php
/*
Template Name: _following
*/
?>

<?php get_header(); global $user_ID; ?>

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
	global $user_ID;
	
	$boards = get_user_meta($user_ID, '_Following Board ID');
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args = array(
		'tax_query' => array(
			array(
				'taxonomy' => 'board',
				'field' => 'id',
				'terms' => $boards[0],
				'include_children' => false
			)
		),
		'paged' => $paged
	);
	
	query_posts($args);

	get_template_part('index', 'masonry');
	get_footer();
?>