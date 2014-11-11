<?php
add_action( 'wp_enqueue_scripts', 'my_parent_theme_scripts');
function my_parent_theme_scripts() {
	wp_enqueue_style( 'parent-theme-css', get_template_directory_uri() . '/style.css', array());
	wp_enqueue_style( 'parent-default-theme-css', get_template_directory_uri() . '/default.css', array() );
	// wp_enqueue_style( 'parent-theme-css-clip', get_template_directory_uri() . '/css/clip.css', array() );
	// wp_enqueue_style( 'parent-theme-css-delicons', get_template_directory_uri() . '/css/delicons.css', array() );
	// wp_enqueue_style( 'parent-theme-css-gnmenu', get_template_directory_uri() . '/css/gnmenu.css', array() );
	wp_enqueue_style( 'parent-theme-css-home', get_template_directory_uri() . '/css/home.css', array() );
	// wp_enqueue_style( 'parent-theme-css-prettify', get_template_directory_uri() . '/css/prettify.css', array() );
	// wp_enqueue_style( 'parent-theme-css-bootstrap', get_template_directory_uri() . '/css/bootstrap/bootstrap.css', array() );
	wp_enqueue_style( 'parent-theme-css-bootstrap-switch', get_template_directory_uri() . '/css/bootstrap/bootstrap-switch.css', array() );
	// scripts
	// wp_enqueue_script( 'parent-theme-js-bootstrap', get_template_directory_uri() . '/js/bootstrap/bootstrap.js', array() );
	wp_enqueue_script( 'parent-theme-js-val', get_template_directory_uri() . '/js/val.js', array() );
	wp_enqueue_script( 'parent-theme-js-meth', get_template_directory_uri() . '/js/meth.js', array() );
	// wp_enqueue_script( 'parent-theme-js-bootstrap-switch', get_template_directory_uri() . '/js/bootstrap/bootstrap-switch.js', array() );
	// wp_enqueue_script( 'parent-theme-js-bootstrap-slider', get_template_directory_uri() . '/js/bootstrap/bootstrap-slider.js', array() );
	// wp_enqueue_script( 'parent-theme-js-clip-application', get_template_directory_uri() . '/js/clip-application.js', array() );
	// wp_enqueue_script( 'parent-theme-js-clip-checkbox', get_template_directory_uri() . '/js/clip-checkbox.js', array() );
	// wp_enqueue_script( 'parent-theme-js-clip-radio', get_template_directory_uri() . '/js/clip-radio.js', array() );
	// wp_enqueue_script( 'parent-theme-js-gnmenu', get_template_directory_uri() . '/js/gnmenu.js', array() );
	//wp_enqueue_script( 'parent-theme-js-jquery-1.11.1', get_template_directory_uri() . '/js/jquery-1.11.1.js', array() );
	wp_enqueue_script( 'parent-theme-js-epictheme', get_template_directory_uri() . '/js/epictheme.js', array() );
	// wp_enqueue_script( 'parent-theme-js-jquery.clipPagination', get_template_directory_uri() . '/js/jquery.clipPagination.js', array() );
	// wp_enqueue_script( 'parent-theme-js-jquery.knob', get_template_directory_uri() . '/js/jquery.knob.js', array() );
	// wp_enqueue_script( 'parent-theme-js-jquery.slimscroll', get_template_directory_uri() . '/js/jquery.slimscroll.js', array() );
	// wp_enqueue_script( 'parent-theme-js-jquery.sortable', get_template_directory_uri() . '/js/jquery.sortable.js', array() );
	// wp_enqueue_script( 'parent-theme-js-parent-min', get_template_directory_uri() . '/js/parent-min.js', array() );
	// wp_enqueue_script( 'parent-theme-js-prettify', get_template_directory_uri() . '/js/prettify.js', array() );
	// wp_enqueue_script( 'parent-theme-js-scrolld', get_template_directory_uri() . '/js/scrolld.js', array() );
}

function MyMainNav(){

  ?>
      <div id="main-sidebar">
<?php
wp_nav_menu( array( 
  'container' => 'div',
  'container_class' => 'side-nav',
  'theme_location' => 'side-menu',
  'menu_class' => 'nav nav-stacked',
  'walker' => new Hacker_Walker(),                 
  ) );

// wp_nav_menu( array(
// 'theme_location' => 'side-menu',
// 'depth' => 2,
// 'container' => false,
// 'menu_class' => 'nav navbar-nav',
// 'fallback_cb' => 'wp_page_menu',
// //Process nav menu using our custom nav walker
// 'walker' => new wp_bootstrap_navwalker())
// );
?>
  </div>
  <?php
}

function MySubmitUri(){
	$url = '<div class="nav nav-stacked" id="sidebar-nav">';
    // $url .=  '<li>';
    if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { 
      	$url .= '<a href="<?php echo wp_login_url(); ?>" title="Login">';
    }else{
      	$url .= '<a id="sidebar-submit" href="/submit-articles" title="Submit Article" class="toggle-submenu ">';
    }
    $url .= '<i class="fa fa-plus-circle fa-4x"></i>';
    $url .=   '</a>';
    // $url .=   '</li>';
    $url .=   '</div>';
	echo $url;
}

// Register Custom Navigation Walker
require_once('wp_bootstrap_navwalker.php');

register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'Epic-child' ),
) );

?>