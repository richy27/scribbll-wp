<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
     <title><?php bloginfo('name'); ?></title>    
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
  	<?php	

wp_enqueue_script("jquery");  
  	if(!is_admin()){
  		
	// wp_enqueue_style('wptheme', get_stylesheet_directory_uri() . '/style.css' );
	// wp_enqueue_style('wpdefault', get_stylesheet_directory_uri() . '/default.css' );
  // wp_enqueue_script( 'validate', get_stylesheet_directory_uri() . '/js/val.js', 'jquery' );
  // wp_enqueue_script( 'methods', get_stylesheet_directory_uri() . '/js/meth.js', 'jquery' );


$current_user = wp_get_current_user();
if ( 0 == $current_user->ID ) {
    // Not logged in.
} else {
    $ehacklast = get_user_meta($current_user->ID, 'ehacklast', true);
    $ehacksince = time() - $ehacklast;
    echo '<script>var ehacklast = ' . $ehacksince . '</script>';
}
	
		
	}?>
	
	<?php wp_head(); 

  $theme_color = get_option('theme_color');

  ?>

<style>

  .navbar{ background-color: <?php echo $theme_color; ?>}
  .page-title, #respond h3, .side-nav a:hover ,.title a:visited, .company-item-title, .icontext, .score, .unvoted, .comment-reply-title, body a, .fa { color:  <?php echo $theme_color; ?>; }
</style>

  </head>

<body>


<div id='wrapper'>


<nav class="navbar navbar-inverse nav-fixed-top" role="navigation">
<div class="container-fluid monkey">
    <i class="fa fa-reorder fa-2x" id="icon-toggle-main-menu"></i>
    <a href="<?php echo home_url(); ?>" class="brand">  <?php bloginfo('name'); ?></a>
    <span class="tag-line"><?php echo get_bloginfo ( 'description' );?></span>
    <span class="pull-right topnav-options">
                  <span class="inline">
        <nav>
        	<?php
			if ( is_user_logged_in() ){
			$size='30';
 			$current_user = wp_get_current_user();
 			$email = $current_user->user_email;
      $author_id = $current_user->id;
 			$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
			?>
			<img src="<?php echo $grav_url; ?>" alt="" class="avatar avatar-30 photo"/>
            <span class="dropdown inline">
        <a class="dropdown-toggle small" id="account-dropdown" role="button" data-toggle="dropdown" href="#">
          
          <span class="strongest">
            <?php echo 'Hi ' . $current_user->user_login; ?>            <b class="caret"></b>
          </span>
        </a>
        <ul class="dropdown-menu" id="user-dropdown-menu" role="menu" aria-labelledby="account-dropdown">
          <li><a href="<?php echo get_author_posts_url( $author_id ); ?>"><i class="icon-fixed-width icon-user"></i>Public Profile</a></li>
          <li><a href="<?php echo home_url(); ?>/your-profile"><i class="icon-fixed-width icon-user"></i>Your Account</a></li>
          <li><a href="<?php echo wp_logout_url(); ?>" title="Logout">Logout</a></li>
        </ul>
      </span>
			<?php
			}else{
			?>
          <a href="<?php echo wp_login_url(); ?>" title="Login">Login</a>
          <span class="dot white down5">•</span>
          <?php wp_register('', ''); 
			     } ?>
        </nav>
      </span>
          </span>
  </div>
</nav>
<div class='mobile-mike hide'>
  <?php

wp_nav_menu( array( 
  'container' => 'div',
  'container_class' => 'side-nav',
  'theme_location' => 'primary',
  'menu_class' => 'nav nav-stacked',
  // 'walker' => new Hacker_Walker(),    
  'walker' => new wp_bootstrap_navwalker()      
  ) );
?>
</div>
<?php

// wp_nav_menu( array(
// 'theme_location' => 'top_menu',
// 'depth' => 2,
// 'container' => false,
// 'menu_class' => 'nav navbar-nav',
// 'fallback_cb' => 'wp_page_menu',
// //Process nav menu using our custom nav walker
// 'walker' => new wp_bootstrap_navwalker())
// );
?>

<div class='col-md-2' style='padding-right:0px'>
 <?php MyMainNav(); ?>
</div>

 <!-- end header -->



