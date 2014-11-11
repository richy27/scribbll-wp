<?php
/*
Template Name: _top_users
*/
?>

<?php get_header(); global $user_ID; ?>

<div class="subpage-title container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<h1><?php _e('Top Users (Most Followers)', 'pinclone'); ?></h1>
		</div>
	</div>
</div>

<div class="container-fluid">
<?php
$args = array(
	'order' => 'desc',
	'orderby' => 'meta_value',
	'meta_key' => '_Followers Count',
	'meta_query' => array(
		array(
		'key' => '_Followers Count',
		'compare' => '>',
		'value' => '0',
		'type' => 'numeric'
		)
	),
	'number' => '20'
 );

$top_user_follower_query = new WP_User_Query($args);

if ($top_user_follower_query->total_users > 0) {
	echo '<div id="user-profile-follow" class="row-fluid">';
	$count = 1;
	foreach ($top_user_follower_query->results as $top_user_follower) {
		?>
		<div class="follow-wrapper">		
			<div class="post-content">
			<?php
			if ($top_user_follower->ID != $user_ID) {
			?>
			<button class="btn follow pinclone-follow<?php $parent_board = get_user_meta($top_user_follower->ID, '_Board Parent ID', true); if ($followed = pinclone_followed($parent_board)) { echo ' disabled'; } ?>" data-author_id="<?php echo $top_user_follower->ID; ?>" data-board_id="<?php echo $parent_board; ?>" data-board_parent_id="0" data-disable_others="no" type="button"><?php if (!$followed) { _e('Follow', 'pinclone'); } else { _e('Unfollow', 'pinclone'); } ?></button>
			<?php } else { ?>
			<a class="btn follow disabled"><?php _e('Myself!', 'pinclone'); ?></a>
			<?php } ?>
				<div class="user-avatar">
					<span class="top-user-count top-user-count-alt1"><?php echo $count; $count++; ?></span>
					<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $top_user_follower->user_nicename; ?>/"><?php echo get_avatar($top_user_follower->ID , '32'); ?></a>
				</div>
				
				<div class="user-name">
					<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $top_user_follower->user_nicename; ?>/">
						<h4><?php echo $top_user_follower->display_name; ?></h4>
						<p><?php if ('' == $followers_count = get_user_meta($top_user_follower->ID, '_Followers Count', true)) echo '0'; else echo $followers_count; ?> <?php _e('Followers', 'pinclone'); ?> - <?php echo count_user_posts($top_user_follower->ID); ?> <?php _e('Pins', 'pinclone'); ?></p>
					</a>
				</div>
			</div>
		</div>
	<?php 
	}
	echo '</div>';
} else {
?>
	<div class="row-fluid">		
		<div class="span12">
			<div class="bigmsg">
					<h2><?php _e('Nobody yet.', 'pinclone'); ?></h2>
			</div>
		</div>
	</div>
<?php } ?>
</div>

<div class="subpage-title container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<br /><br />
			<h1><?php _e('Top Users (Most Pins)', 'pinclone'); ?></h1>
		</div>
	</div>
</div>

<div class="container-fluid">
<?php
$args = array(
	'order' => 'desc',
	'orderby' => 'post_count',
	'number' => '20'
 );

$top_user_postcount_query = new WP_User_Query($args);

if ($top_user_postcount_query->total_users > 0) {
	echo '<div id="user-profile-follow" class="row-fluid">';
	$count = 1;
	foreach ($top_user_postcount_query->results as $top_user_postcount) {
		if (count_user_posts($top_user_postcount->ID) > 0) {
		?>
		<div class="follow-wrapper">		
			<div class="post-content">
			<?php
			if ($top_user_postcount->ID != $user_ID) {
			?>
			<button class="btn follow pinclone-follow<?php $parent_board = get_user_meta($top_user_postcount->ID, '_Board Parent ID', true); if ($followed = pinclone_followed($parent_board)) { echo ' disabled'; } ?>" data-author_id="<?php echo $top_user_postcount->ID; ?>" data-board_id="<?php echo $parent_board; ?>" data-board_parent_id="0" data-disable_others="no" type="button"><?php if (!$followed) { _e('Follow', 'pinclone'); } else { _e('Unfollow', 'pinclone'); } ?></button>
			<?php } else { ?>
			<a class="btn follow disabled"><?php _e('Myself!', 'pinclone'); ?></a>
			<?php } ?>
				<div class="user-avatar">
					<span class="top-user-count top-user-count-alt2"><?php echo $count; $count++; ?></span>
					<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $top_user_postcount->user_nicename; ?>/"><?php echo get_avatar($top_user_postcount->ID , '32'); ?></a>
				</div>
				
				<div class="user-name">
					<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $top_user_postcount->user_nicename; ?>/">
						<h4><?php echo $top_user_postcount->display_name; ?></h4>
						<p><?php echo count_user_posts($top_user_postcount->ID); ?> <?php _e('Pins', 'pinclone'); ?> - <?php if ('' == $followers_count = get_user_meta($top_user_postcount->ID, '_Followers Count', true)) echo '0'; else echo $followers_count; ?> <?php _e('Followers', 'pinclone'); ?></p>
					</a>
				</div>
			</div>
		</div>
	<?php 
		}		
	}
	echo '</div>';
} else {
?>
	<div class="row-fluid">		
		<div class="span12">
			<div class="bigmsg">
					<h2><?php _e('Nobody yet.', 'pinclone'); ?></h2>
			</div>
		</div>
	</div>
<?php } ?>
</div>
<?php get_footer(); ?>