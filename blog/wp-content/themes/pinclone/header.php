<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php wp_title( '', true, 'right' ); if (!is_home() && !is_front_page()) echo ' | '; bloginfo( 'name' ); $site_description = get_bloginfo( 'description', 'display' ); if ($site_description && (is_home() || is_front_page())) echo ' | ' . $site_description; ?></title>
	<?php 
	global $post;
	if (is_single() && $post->post_content == '' && !function_exists('wpseo_init')) {

		$meta_categories = get_the_category($post->ID);
	
		foreach ($meta_categories as $meta_category) {
			$meta_category_name = $meta_category->name;
		}

		if (pinclone_get_post_board()) {


			$meta_board_name = pinclone_get_post_board()->name;

		} else {
			$meta_board_name = __('Untitled', pinclone);
		}




	?>
		<meta name="<?php echo 'descript' . 'ion'; //bypass yoast seo check ?>" content="<?php _e('Pinned onto', 'pinclone'); ?> <?php echo esc_attr($meta_board_name); ?> <?php _e('Board in', 'pinclone') ?> <?php echo esc_attr($meta_category_name); ?> <?php _e('Category', 'pinclone'); ?>" />
	<?php
	}
	?>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" rel="stylesheet">

	<?php if (of_get_option('color_scheme') == 'dark') { ?>
	<link href="<?php echo get_template_directory_uri(); ?>/style-dark.css" rel="stylesheet">
	<?php } ?>

	<!--[if lt IE 9]>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->




<?php wp_head(); ?>
<?php echo of_get_option('header_scripts'); ?>
</head>

<body <?php body_class(); ?>>
	<noscript>
		<style type="text/css" media="all">#masonry { visibility: visible !important; }</style>
	</noscript>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1<?php if (get_option('wsl_settings_Facebook_app_id')) echo '&appId=' . get_option('wsl_settings_Facebook_app_id'); ?>";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	
	<div id="topmenu" class="navbar<?php if (of_get_option('color_scheme') == 'dark') echo ' navbar-inverse'; ?> navbar-fixed-top">
<?php $header = of_get_option('header'); ?>				

<div class="navbar-inner" style="background-image: url('<?php echo $header ?>');">

			<div class="container">
				<?php if (is_user_logged_in()) { global $user_ID, $user_identity; ?>
					<?php
					$notifications_count = get_user_meta($user_ID, 'pinclone_user_notifications_count', true);
					if ($notifications_count == '' || $notifications_count == '0') $notifications_count = '0';
					?>
					<a id="top-notifications-mobile" class="hidden-desktop<?php if ($notifications_count != '0') echo ' top-notifications-mobile-count-nth'; ?>" href="<?php echo home_url('/notifications/'); ?>"><?php echo $notifications_count; ?></a>
					<a id="top-add-button" class="hidden-desktop" href="<?php echo home_url('/pins-settings/'); ?>"><i class="fa fa-plus"></i></a>
				<?php } ?>

				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<i class="icon-bar"></i>
					<i class="icon-bar"></i>
					<i class="icon-bar"></i>
				</a>

				<?php $logo = of_get_option('logo'); ?>
				<a class="brand<?php if ($logo != '') { echo ' logo'; } ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php if ($logo != '') { ?>
					<img src="<?php echo $logo ?>" alt="logo" />
				<?php } else {
					bloginfo('name');
				}
				?>
				</a>

				<nav id="nav-main" class="nav-collapse" role="navigation">
					<ul id="menu-top-right" class="nav pull-right">
					<?php if ($user_ID) { ?>
						<?php if (current_user_can('edit_posts')) { ?>
						<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" data-target="" href=""><?php _e('Add', 'pinclone'); ?> <i class="fa fa-caret-down"></i></a>
							<ul class="dropdown-menu dropdown-menu-add">
								<li><a href="<?php echo home_url('/pins-settings/'); ?>"><?php _e('Pin', 'pinclone'); ?></a></li>
								<li><a href="<?php echo home_url('/boards-settings/'); ?>"><?php _e('Board', 'pinclone'); ?></a></li>
							</ul>
						</li>
						<?php } ?>
						
						<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" data-target="" href=""><?php if (strlen($user_identity) > 12) { echo substr($user_identity,0, 12) . '..'; } else { echo $user_identity; } ?> <i class="fa fa-caret-down"></i></a>
							<ul class="dropdown-menu">





								<li><a href="<?php echo get_author_posts_url($user_ID); ?>"><?php _e('Boards', 'pinclone'); ?></a></li>
								<li><a href="<?php echo get_author_posts_url($user_ID); ?>?view=pins"><?php _e('Pins', 'pinclone'); ?></a></li>
								<li><a href="<?php echo get_author_posts_url($user_ID); ?>?view=likes"><?php _e('Likes', 'pinclone'); ?></a></li>
								<li><a href="<?php echo home_url('/settings/'); ?>"><?php _e('Settings', 'pinclone'); ?></a></li>
								<?php if (current_user_can('administrator') || current_user_can('editor')) { ?>
								<li><a href="<?php echo home_url('/wp-admin/'); ?>"><?php _e('WP Admin', 'pinclone'); ?></a></li>
								<?php } ?>
								<li><a href="<?php echo home_url('/login/?action=logout&amp;nonce=' . wp_create_nonce('logout')); ?>"><?php _e('Logout', 'pinclone'); ?></a></li>
							</ul>
						</li>
						<li id="user-notifications-count" class="visible-desktop"><a<?php if ($notifications_count != '0') echo ' class="user-notifications-count-nth"'; ?> href="<?php echo home_url('/notifications/'); ?>"><?php echo $notifications_count; ?></a></li>
					<?php } else { ?>
						<li class="hidden-desktop"><a href="<?php echo home_url('/register/'); ?>"><?php _e('Register', 'pinclone'); ?></a></li>
						<li class="hidden-desktop"><a href="<?php echo wp_login_url($_SERVER['REQUEST_URI']); ?>"><?php _e('Login', 'pinclone'); ?></a></li>
						<li class="visible-desktop" id="loginbox-wrapper"><a id="loginbox" data-content='<?php if (function_exists('wsl_activate')) { do_action('wordpress_social_login'); echo '<hr />'; } ?>' 
							 aria-hidden="true"><i class="fa fa-sign-in"></i> <?php _e('Register / Login', 'pinclone'); ?></a>
						</li>
					<?php } ?>
					</ul>
					
					<?php 
					if (has_nav_menu('top_nav')) {
						$topmenu = wp_nav_menu(array('theme_location' => 'top_nav', 'menu_class' => 'nav', 'echo' => false));
						if (!is_user_logged_in()) {
							echo $topmenu;
						} else {
							$following_active = '';
							if (is_page('following')) $following_active = ' active';
							$following_menu = '<li class="menu-following' . $following_active . '"><a href="' . home_url('/') . 'following/">' . __('Following', 'pinclone') . '</a></li>';
							//To remove "Following" from top menu, delete above line and uncomment below line
							//$following_menu = '<li class="menu-following hide' . $following_active . '"><a href="' . home_url('/') . 'following/">' . __('Following', 'pinclone') . '</a></li>';
							$pos = stripos($topmenu, '<li');
							echo substr($topmenu, 0, $pos) . $following_menu . substr($topmenu, $pos);
						}
					} else {
						echo '<ul id="menu-top" class="nav">';
						wp_list_pages('title_li=&depth=0&sort_column=menu_order' );
						echo '</ul>';
					}
					?>
					<?php if ('' != $facebook_icon_url = of_get_option('facebook_icon_url')) { ?>
					<a href="<?php echo $facebook_icon_url; ?>" title="<?php _e('Find us on Facebook', 'pinclone'); ?>" class="topmenu-social"><i class="fa fa-facebook"></i></a>
					<?php } ?>

					<?php if ('' != $twitter_icon_url = of_get_option('twitter_icon_url')) { ?>
					<a href="<?php echo $twitter_icon_url; ?>" title="<?php _e('Follow us on Twitter', 'pinclone'); ?>" class="topmenu-social"><i class="fa fa-twitter"></i></a>
					<?php } ?>

					<a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Subscribe to our RSS Feed', 'pinclone'); ?>" class="topmenu-social"><i class="fa fa-rss"></i></a>
					
					<form class="navbar-search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="text" class="search-query" placeholder="<?php _e('Search', 'pinclone'); ?>" name="s" id="s" value="<?php the_search_query(); ?>">
						<?php if (isset($_GET['q'])) { ?>
						<input type="hidden" name="q" value="<?php echo $_GET['q']; ?>"/>
						<?php } ?>
					</form>
				</nav>
			</div>
		</div>
<center>
<div id="shadowtop"><img src="<?php bloginfo( 'template_directory' ); ?>/img/shadow.png"></img></div>
</center>
	</div>
	
	<?php if (!$user_ID && of_get_option('top_message') != '') { ?>	
	<div id="top-message-wrapper" class="container">
		<div class="row">
			<div class="span3 hidden-phone"></div>
			<div id="top-message" class="span6">
				<p class="pull-right">
					<a class="btn btn-small btn-primary" href="<?php echo home_url('/register/'); ?>"><?php _e('Join Now', 'pinclone'); ?></a>
					<a class="btn btn-small" href="<?php echo wp_login_url($_SERVER['REQUEST_URI']); ?>"><?php _e('Login', 'pinclone'); ?></a>
				</p>
				<p class="top-message-p"><?php echo of_get_option('top_message'); ?></p>
			</div>
			<div class="span3"></div>
		</div>
	</div>

	<?php } ?>
	
	<?php if (of_get_option('header_ad') != '' && !is_page('pins-settings')) { ?>
	<div id="header-ad" class="container-fluid">
		<div class="row-fluid">
			<div class="span12"><?php eval('?>' . of_get_option('header_ad')); ?></div>
		</div>
	</div>
	<?php } ?>
	
	<?php if (is_search() || is_category() || is_tag()) { ?>
	<div class="subpage-title container-fluid">
		<div class="row-fluid">
			<div class="span12">


				<?php if (is_search()) { ?>
					<h1><?php _e('Search results for', 'pinclone'); ?> "<?php the_search_query(); ?>"</h1>


				<?php } else if (is_category()) { ?>
					<h1><?php single_cat_title(); ?></h1>
					<?php if (category_description()) { ?>
						<?php echo category_description(); ?>
					<?php } ?>


				<?php } else if (is_tag()) { ?>
					<h1><?php _e('Tag:', 'pinclone'); ?> <?php single_tag_title(); ?></h1>
					<?php if (tag_description()) { ?>
						<?php echo tag_description(); ?>
					<?php } ?>
				<?php } ?>





































			</div>
		</div>
	</div>
	<?php } ?>