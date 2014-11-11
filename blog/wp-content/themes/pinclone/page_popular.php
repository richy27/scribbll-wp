<?php
/*
Template Name: _popular
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

<div id="header-ad" class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
		<?php
		$categories = get_categories('exclude=' . implode(',', pinclone_blog_cats()) . ', 1');

		if($categories){
			echo __('Category', 'pinclone');
			if (!isset($_GET['category'])) $active = ' popular-categories-active';
			echo ' <a class="popular-categories' . $active . '" href="' . home_url('/popular/') . '">' . __('All', 'pinclone') . '</a>';
			foreach($categories as $category) {
			?>
				<a class="popular-categories<?php if ($_GET['category'] == $category->category_nicename) echo ' popular-categories-active'; ?>" href="<?php echo home_url('/popular/'); ?>?category=<?php echo $category->category_nicename; ?>"><?php echo $category->name; ?></a> 
		<?php }
		} ?>
		</div>
	</div>
</div>

<div class="container-fluid">
	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	function filter_where($where = '') {
		$duration = '-' . of_get_option('popularity_duration') . ' days';
		$where .= " AND post_date > '" . date('Y-m-d', strtotime($duration)) . "'";
		return $where;
	}
	
	if ('likes' == $popularity = of_get_option('popularity')) {
		$args = array(
			'meta_key' => '_Likes Count',
			'meta_compare' => '>',
			'meta_value' => '0',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'category_name' => sanitize_text_field($_GET['category']),
			'paged' => $paged
		);
		add_filter('posts_where', 'filter_where');
		add_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
		query_posts($args);
		remove_filter('posts_where', 'filter_where');
		remove_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
	} else if ($popularity == 'repins') {	
		$args = array(
			'meta_key' => '_Repin Count',
			'meta_compare' => '>',
			'meta_value' => '0',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'category_name' => sanitize_text_field($_GET['category']),
			'paged' => $paged
		);
		add_filter('posts_where', 'filter_where');
		add_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
		query_posts($args);
		remove_filter('posts_where', 'filter_where');
		remove_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
	} else if ($popularity == 'comments') {
		$args = array(
			'orderby' => 'comment_count',
			'category_name' => sanitize_text_field($_GET['category']),
			'paged' => $paged
		);
		add_filter('posts_where', 'filter_where');
		add_filter('posts_orderby', 'pinclone_comments_orderby');
		query_posts($args);
		remove_filter('posts_where', 'filter_where');
		add_filter('posts_orderby', 'pinclone_comments_orderby');
	} else {
		$args = array(
			'category_name' => sanitize_text_field($_GET['category']),
			'paged' => $paged
		);
		query_posts($args);
	}

	get_template_part('index', 'masonry');
	get_footer();
?>