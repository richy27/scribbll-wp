<?php
/*
Template Name: _notifications
*/

if (!is_user_logged_in()) { wp_redirect(wp_login_url($_SERVER['REQUEST_URI'])); exit; }

global $wpdb, $wp_rewrite, $user_ID;

get_header();

$notifications_count = $wpdb->get_var(
	$wpdb->prepare(
		"SELECT COUNT(*)
		FROM " . $wpdb->prefix . "pinclone_notifications
		WHERE user_id = %d
		AND notification_date >= (NOW() - INTERVAL 30 DAY)
		"
		, $user_ID
	)
);

$notifications_count_unread = get_user_meta($user_ID, 'pinclone_user_notifications_count', true);
if ($notifications_count_unread == '') $notifications_count_unread = 0;
?>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span4 hidden-phone"></div>

		<div id="user-notifications" class="span4 usercp-wrapper">
			<h1><?php _e('Notifications', 'pinclone') ?></h1>
			<p class="text-center"><em><?php _e('Only notifications from last 30 days are available for viewing', 'pinclone'); ?></em></p>
			<table id="user-notifications-table" class="table">
				<?php if ($notifications_count == 0) { ?>
					<tr class="info">
						<td class="notifications-none"><div class="text-center"><strong><?php _e('No Notifications Yet.', 'pinclone'); ?></strong></div></td>
					</tr>
				<?php
				} else {
					$pnum = isset($_GET['pnum']) ? intval($_GET['pnum']) : 1;
					$notifications_per_page = 30;
					$maxpage = ceil($notifications_count/$notifications_per_page);
					
					$notifications = $wpdb->get_results(
						$wpdb->prepare(
						"SELECT notification_date, notification_type, notification_from, notification_post_id
						FROM " . $wpdb->prefix . "pinclone_notifications
						WHERE user_id = %d
						AND notification_date >= (NOW() - INTERVAL 30 DAY)
						ORDER BY notification_id DESC
						LIMIT " . ($pnum - 1) * $notifications_per_page . ", " . $pnum * $notifications_per_page
						, $user_ID
						)
					);
					
					$count = 0;
					foreach ($notifications as $notification) {
						$count++;
						$user_info = get_userdata($notification->notification_from);
				?>
					<tr class="notifications-wrapper<?php if ($count <= $notifications_count_unread) echo ' notifications_unread'; ?>">
						<td class="notifications_from"><a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $user_info->user_nicename; ?>/"><?php echo get_avatar($user_info->ID, '48'); ?></a></td>
						<td class="notifications_msg">
							<a href="<?php echo home_url('/' . $wp_rewrite->author_base . '/') . $user_info->user_nicename; ?>/"><?php echo $user_info->display_name; ?></a> 
							<?php 
							switch ($notification->notification_type)
							{
							case "like":
								_e('likes your', 'pinclone');
								echo ' <a href="' . get_permalink($notification->notification_post_id) . '">' . __('pin', 'pinclone') . '</a>';
								break;
							case "repin":
								_e('repinned your', 'pinclone');
								echo ' <a href="' . get_permalink($notification->notification_post_id) . '">' . __('pin', 'pinclone') . '</a>';
								break;
							case "following":
								_e('is following you', 'pinclone');
								break;
							case "comment":
								_e('commented on your', 'pinclone');
								echo ' <a href="' . get_permalink($notification->notification_post_id) . '">' . __('pin', 'pinclone') . '</a>';
								break;
							}							
							?>
							<br />
							<?php echo pinclone_human_time_diff(strtotime($notification->notification_date),  current_time('timestamp')); ?>
						</td>
						<td class="notifications_post_id"><a href="<?php echo get_permalink($notification->notification_post_id); ?>"><?php echo get_the_post_thumbnail($notification->notification_post_id, 'thumbnail'); ?></a></td>
					</tr>
				<?php 
					}
				}
				?>
			</table>
				
			<?php if ($maxpage != 0) { ?>
				<div id="navigation">
					<ul class="pager">				
						<?php if ($pnum != 1 && $maxpage >= $pnum) { ?>
						<li id="navigation-previous">
							<a href="?pnum=<?php echo $pnum-1; ?>"><?php _e('&laquo; Previous', 'pinclone') ?></a>
						</li>
						<?php } ?>
						
						<?php if ($maxpage != 1 && $maxpage != $pnum) { ?>
						<li id="navigation-next">
							<a href="?pnum=<?php echo $pnum+1; ?>"><?php _e('Next &raquo;', 'pinclone') ?></a>
						</li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>
		</div>

		<div class="span4"></div>
	</div>
</div>

<?php
update_user_meta($user_ID, 'pinclone_user_notifications_count', '0');
get_footer();
?>