<?php
/*
Template Name: _videos
*/
?>

<?php get_header(); ?>
<div class="container-fluid">
	<?php	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args = array(
		'post_type' => 'post',
		'paged' => $paged,
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => '_Photo Source Domain',
				'value' => apply_filters('pinclone_page_videos_domains', array('www.youtube.com', 'vimeo.com', 'soundcloud.com'))
			),
			array(
			'key' => '_Original Post ID',
			'compare' => 'NOT EXISTS'
			)
		)
	);
	
	query_posts($args);

	get_template_part('index', 'masonry');
	get_footer();
?>