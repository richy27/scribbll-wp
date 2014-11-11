<?php
/*
Template Name: _gifts
*/
?>

<?php get_header(); ?>

<?php
$prices = array(
//**************************************************
	//edit price range
	'0-10',
	'11-50',
	'51-100',
	'101-1000',
	'1001-2000',
	'2001-3000',
	'3001-1000000',
	//end edit priace range
//***************************************************
);
?>

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
			if (!isset($_GET['category']) || $_GET['category'] == '') $active = ' gifts-categories-active';
			echo ' <a class="gifts-categories' . $active . '" href="' . home_url('/gifts/?category=&amp;price=') . sanitize_text_field($_GET['price']) . '">' . __('All', 'pinclone') . '</a>';
			foreach($categories as $category) {
			?>
				<a class="gifts-categories<?php if ($_GET['category'] == $category->category_nicename) echo ' gifts-categories-active'; ?>" href="<?php echo home_url('/gifts/?category=') . $category->category_nicename; ?>&amp;price=<?php echo sanitize_text_field($_GET['price']); ?>&amp;sort=<?php echo sanitize_text_field($_GET['sort']); ?>"><?php echo $category->name; ?></a> 
		<?php }
		} ?>

		<div class="clearfix"></div>

		<?php _e('Price', 'pinclone'); ?> 
		<?php
		if (!isset($_GET['price']) || $_GET['price'] == '') $active_price = ' gifts-categories-active';
		echo ' <a class="gifts-categories' . $active_price . '" href="' . home_url('/gifts/?category=' . sanitize_text_field($_GET['category']) . '&amp;price=') . '">' . __('All', 'pinclone') . '</a>';
		foreach ($prices as $price) {
		?>
			<a class="gifts-categories<?php if ($_GET['price'] == $price) echo ' gifts-categories-active'; ?>" href="<?php echo home_url('/gifts/?category=') . sanitize_text_field($_GET['category']); ?>&amp;price=<?php echo $price; ?>&amp;sort=<?php echo sanitize_text_field($_GET['sort']); ?>"><?php echo $price; ?></a> 
		<?php } ?>
		</div>
		
		<div class="clearfix"></div>

		<?php _e('Sort by', 'pinclone'); ?> 
			<a class="gifts-categories<?php if (!isset($_GET['sort']) || $_GET['sort'] == '' || $_GET['sort'] == 'recent') echo ' gifts-categories-active'; ?>" href="<?php echo home_url('/gifts/?category=') . sanitize_text_field($_GET['category']); ?>&amp;price=<?php echo sanitize_text_field($_GET['price']); ?>&amp;sort=recent"><?php _e('Most Recent', 'pinclone'); ?></a> 
			<a class="gifts-categories<?php if ($_GET['sort'] == 'lowfirst') echo ' gifts-categories-active'; ?>" href="<?php echo home_url('/gifts/?category=') . sanitize_text_field($_GET['category']); ?>&amp;price=<?php echo sanitize_text_field($_GET['price']); ?>&amp;sort=lowfirst"><?php _e('Price: Lowest First', 'pinclone'); ?></a> 
			<a class="gifts-categories<?php if ($_GET['sort'] == 'highfirst') echo ' gifts-categories-active'; ?>" href="<?php echo home_url('/gifts/?category=') . sanitize_text_field($_GET['category']); ?>&amp;price=<?php echo sanitize_text_field($_GET['price']); ?>&amp;sort=highfirst"><?php _e('Price: Highest First', 'pinclone'); ?></a> 
		</div>
	</div>
</div>

<div class="container-fluid">
	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	switch ($_GET['sort']) {
	case "recent":
		$order = '';
		$orderby = '';
		$meta_key = '';
	break;
	case "lowfirst":
		$order = 'asc';
		$orderby = 'meta_value_num';
		$meta_key = '_Price';
	break;
	case "highfirst":
		$order = 'desc';
		$orderby = 'meta_value_num';
		$meta_key = '_Price';
	break;
	default:
		$order = '';
		$orderby = '';
		$meta_key = '';
	}

	if (isset($_GET['price']) && $_GET['price'] != '') {
		$price = explode('-', sanitize_text_field($_GET['price']));
		
		$args = array(
			'category_name' => sanitize_text_field($_GET['category']),
			'meta_query' => array(
				array(
					'key' => '_Price',
					'value' => $price,
					'type' => 'numeric',
					'compare' => 'BETWEEN'
				)
			),
			'orderby' => $orderby,
			'meta_key' => $meta_key,
			'order' => $order,
			'paged' => $paged
		);
	} else {
		$args = array(
			'category_name' => sanitize_text_field($_GET['category']),
			'meta_query' => array(
				array(
				'key' => '_Price',
				'compare' => 'EXISTS'
				)
			),
			'orderby' => $orderby,
			'meta_key' => $meta_key,
			'order' => $order,
			'paged' => $paged
		);
	}
	
	if ($orderby == 'meta_value_num')
		add_filter('posts_orderby', 'pinclone_meta_value_num_orderby');
	
	query_posts($args);
	
	if ($orderby == 'meta_value_num')
		remove_filter('posts_orderby', 'pinclone_meta_value_num_orderby');

	get_template_part('index', 'masonry');
	get_footer();
?>