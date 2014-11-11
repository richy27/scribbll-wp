<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Inkzine
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="parallax-bg" data-stellar-background-ratio="1.3"></div>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<?php
	if ( ( ( of_get_option('tickr_enabled') != 0 ) && (is_home() ) ) )
		{ ?>
    <div id="tickr-wrapper" class="col-md-12 clearfix">
    <div id="tickr-bg">
    <div class="tickr-inner-wrapper container">
    <ul class="bxtickr">
    	<?php
    		$args = array( 'posts_per_page' => of_get_option('tickr_count'), 'category' => of_get_option('tickr_category') );
			$lastposts = get_posts( $args );
			foreach ( $lastposts as $post ) :
			  setup_postdata( $post ); ?>
			  	<li><a title="<?php the_title(); ?>" href='<?php the_permalink(); ?>'>
			  	<?php the_title(); ?>
			  	</a></li>
			<?php endforeach; 
			wp_reset_postdata(); 
			?>			
     </ul>   
    </div>
    </div>
	</div>
    <?php } ?>
	<div id="top-bar">
	<div class="container">
		   <div id="primary-nav-wrapper" class="col-md-12 container">
				<nav id="primary-navigation" class="primary-navigation" role="navigation">
					
						<h1 class="menu-toggle"><?php _e( 'Menu', 'inkzine' ); ?></h1>
						<div class="screen-reader-text skip-link"><a href="#content"><?php _e( 'Skip to content', 'inkzine' ); ?></a></div>
			
						<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
					
				</nav><!-- #site-navigation -->
		</div>
		<div id="fixed-search">
			<?php get_template_part('searchform', 'top') ?>
		</div>	
	</div>
	</div><!--#top-bar-->
	<header id="masthead" class="site-header row container" role="banner">
		<div class="site-branding col-md-12">
		<?php if((of_get_option('logo', true) != "") && (of_get_option('logo', true) != 1) ) { ?>
			<h1 class="site-title logo-container"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<?php
			echo "<img class='main_logo' src='".of_get_option('logo', true)."' title='".esc_attr(get_bloginfo( 'name','display' ) )."'></a></h1>";	
			}
		else { ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1> 
			<h2 class="site-description"><?php bloginfo('description') ?></h2>
		<?php	
		}
		?>
		</div>
		<div id="social-icons-top" class="col-md-12">
			    <?php if ( of_get_option('facebook', true) != "") { ?>
				 <a href="<?php echo esc_url(of_get_option('facebook', true)); ?>" title="Facebook" ><img src="<?php echo get_template_directory_uri()."/images/social/facebook.png"; ?>"></a>
	             <?php } ?>
	            <?php if ( of_get_option('twitter', true) != "") { ?>
				 <a href="<?php echo esc_url("http://twitter.com/".of_get_option('twitter', true)); ?>" title="Twitter" ><img src="<?php echo get_template_directory_uri()."/images/social/twitter.png"; ?>"></a>
	             <?php } ?>
	             <?php if ( of_get_option('google', true) != "") { ?>
				 <a href="<?php echo esc_url(of_get_option('google', true)); ?>" title="Google Plus" ><img src="<?php echo get_template_directory_uri()."/images/social/google.png"; ?>"></a>
	             <?php } ?>
	             <?php if ( of_get_option('feedburner', true) != "") { ?>
				 <a href="<?php echo esc_url(of_get_option('feedburner', true)); ?>" title="RSS Feeds" ><img src="<?php echo get_template_directory_uri()."/images/social/rss.png"; ?>"></a>
	             <?php } ?>
	             <?php if ( of_get_option('pinterest', true) != "") { ?>
				 <a href="<?php echo esc_url(of_get_option('pinterest', true)); ?>" title="Pinterest" ><img src="<?php echo get_template_directory_uri()."/images/social/pinterest.png"; ?>"></a>
	             <?php } ?>
	             <?php if ( of_get_option('instagram', true) != "") { ?>
				 <a href="<?php echo esc_url(of_get_option('instagram', true)); ?>" title="Instagram" ><img src="<?php echo get_template_directory_uri()."/images/social/instagram.png"; ?>"></a>
	             <?php } ?>
	             <?php if ( of_get_option('linkedin', true) != "") { ?>
				 <a href="<?php echo esc_url(of_get_option('linkedin', true)); ?>" title="LinkedIn" ><img src="<?php echo get_template_directory_uri()."/images/social/linkedin.png"; ?>"></a>
	             <?php } ?>
	             <?php if ( of_get_option('youtube', true) != "") { ?>
				 <a href="<?php echo esc_url(of_get_option('youtube', true)); ?>" title="YouTube" ><img src="<?php echo get_template_directory_uri()."/images/social/youtube.png"; ?>"></a>
	             <?php } ?>
         
	</div>
	</header><!-- #masthead -->
	
	<?php
    if ( ( ( of_get_option('slider_enabled') != 0 ) && (is_home() ) )
		|| ( (of_get_option('slider_enabled_front') != 0 ) && (is_front_page() ) ) ) 
		{ ?>
    <div id="slider-wrapper" class='container'>
    <ul id="slider">
    	<?php
		  		$slider_flag = false;
		  		for ($i=1;$i<4;$i++) {
					if ( of_get_option('slide'.$i, true) != "" ) {
						echo "<div class='xslide'><a href='".of_get_option('slideurl'.$i, true)."'><img src='".of_get_option('slide'.$i, true)."' title='".of_get_option('slidetitle'.$i, true)."'></a></div>"; 
						   
						$slider_flag = true;
					}
				}
           ?>
     </ul>   
	</div>
    
    <?php } 
	?>
	
	<div id="content" class="site-content row">
		<div class="container col-md-12"> 
