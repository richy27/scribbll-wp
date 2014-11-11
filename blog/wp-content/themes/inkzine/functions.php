<?php
/**
 * Inkzine functions and definitions
 *
 * @package Inkzine
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

/**
 * Initialize Options Panel
 */
if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
	require_once dirname( __FILE__ ) . '/inc/options-framework.php';
}

if ( ! function_exists( 'inkzine_setup' ) ) :

function inkzine_setup() {

	load_theme_textdomain( 'inkzine', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_image_size('homepage-banner',725,400,true);
	add_image_size('carousel-thumb',390,240,true);
	add_image_size('grid-thumb',390,300,true);

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'inkzine' )
	) );

	add_theme_support( 'custom-background', apply_filters( 'inkzine_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	
	add_theme_support( 'post-formats', array( 'image', 'video' ) );
}
endif; // inkzine_setup
add_action( 'after_setup_theme', 'inkzine_setup' );

function inkzine_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'inkzine' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Left', 'inkzine' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Center', 'inkzine' ),
		'id'            => 'sidebar-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Right', 'inkzine' ),
		'id'            => 'sidebar-4',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'inkzine_widgets_init' );

if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
	require_once dirname( __FILE__ ) . '/inc/options-framework.php';
}

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#example_showhidden').click(function() {
  		jQuery('#section-example_text_hidden').fadeToggle(400);
	});
	
	if (jQuery('#example_showhidden:checked').val() !== undefined) {
		jQuery('#section-example_text_hidden').show();
	}
	
});
</script>
 
<?php
}

function inkzine_scripts() {
	wp_enqueue_style( 'inkzine-fonts', 'http://fonts.googleapis.com/css?family=Lato:100,300,400,700,300italic|Armata' );
	wp_enqueue_style( 'inkzine-basic-style', get_stylesheet_uri() );
	if ( (function_exists( 'of_get_option' )) && (of_get_option('sidebar-layout', true) != 1) ) {
		if (of_get_option('sidebar-layout', true) ==  'right') {
			wp_enqueue_style( 'inkzine-layout', get_template_directory_uri()."/css/layouts/content-sidebar.css" );
		}
		else {
			wp_enqueue_style( 'inkzine-layout', get_template_directory_uri()."/css/layouts/sidebar-content.css" );
		}	
	}
	else {
		wp_enqueue_style( 'inkzine-layout', get_template_directory_uri()."/css/layouts/content-sidebar.css" );
	}
		
	wp_enqueue_style( 'inkzine-bxslider-style', get_template_directory_uri()."/css/bxslider/jquery.bxslider.css" );
		
	wp_enqueue_style( 'inkzine-bootstrap-style', get_template_directory_uri()."/css/bootstrap/bootstrap.min.css", array('inkzine-layout') );
		
	wp_enqueue_style( 'inkzine-main-style', get_template_directory_uri()."/css/skins/main.css", array('inkzine-layout','inkzine-bootstrap-style') );
	
	wp_enqueue_script( 'inkzine-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'inkzine-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	
	wp_enqueue_style('inkzine-nivo-lightbox', get_template_directory_uri()."/css/nivo/lightbox/nivo-lightbox.css" );
	
	wp_enqueue_style( 'inkzine-nivo-lightbox-default-theme', get_template_directory_uri()."/css/nivo/lightbox/themes/default/default.css" );
	
	wp_enqueue_script( 'inkzine-sliphover', get_template_directory_uri() . '/js/sliphover.js', array('jquery') );
				
	wp_enqueue_script( 'inkzine-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery') );
		
	wp_enqueue_script( 'inkzine-bxslider', get_template_directory_uri() . '/js/bxslider.min.js', array('jquery') );
	
	wp_enqueue_script( 'inkzine-stellar', get_template_directory_uri() . '/js/stellar.js', array('jquery') );
	
	wp_enqueue_script( 'inkzine-custom', get_template_directory_uri() . '/js/custom.js', array('jquery') );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'inkzine-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'inkzine_scripts' );

function inkzine_custom_head_codes() {
 if ( (function_exists( 'of_get_option' )) && (of_get_option('headcode1', true) != 1) ) {
	echo of_get_option('headcode1', true);
 }
 if ( (function_exists( 'of_get_option' )) && (of_get_option('style2', true) != 1) ) {
	echo "<style>".of_get_option('style2', true)."</style>";
 }
 if ( ( ( of_get_option('slider_enabled') != 0 ) && (is_home() ) )
		|| ( (of_get_option('slider_enabled_front') != 0 ) && (is_front_page() ) ) ) 
		{ ?>
	<script>
 jQuery(document).ready(function(){
			jQuery('#slider').bxSlider( {
			mode: 'horizontal',
			captions: true,
			minSlides: 1,
			maxSlides: 1,
			auto: true,
			preloadImages: 'all',
			nextText: '<i class="fa fa-angle-right"></i>',
			prevText: '<i class="fa fa-angle-left"></i>',
			autoHover: true } );
			});
			
	</script>
	<?php }
if ( get_header_image() ) : 
 	echo "<style>#parallax-bg { background: url('".get_header_image()."') center top repeat-x; }</style>";
 endif;
 if ( of_get_option('credit1', true) != 0 ) { 
	 echo "<style>#colophon .sep { display: none; }</style>";
	 }
}	
add_action('wp_head', 'inkzine_custom_head_codes');

function inkzine_nav_menu_args( $args = '' )
{
    $args['container'] = false;
    return $args;
} // function
add_filter( 'wp_page_menu_args', 'inkzine_nav_menu_args' );

function inkzine_pagination() {
	global $wp_query;
	$big = 12345678;
	$page_format = paginate_links( array(
	    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	    'format' => '?paged=%#%',
	    'current' => max( 1, get_query_var('paged') ),
	    'total' => $wp_query->max_num_pages,
	    'type'  => 'array'
	) );
	if( is_array($page_format) ) {
	            $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
	            echo '<div class="pagination"><div><ul>';
	            echo '<li><span>'. $paged . ' of ' . $wp_query->max_num_pages .'</span></li>';
	            foreach ( $page_format as $page ) {
	                    echo "<li>$page</li>";
	            }
	           echo '</ul></div></div>';
	 }
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates. Import Widgets
 */
require get_template_directory() . '/inc/extras.php';
require get_template_directory() . '/inc/widgets.php';
/**
 * Custom Menu For Bootstrap
 */
require get_template_directory() . '/inc/wp_bootstrap_navwalker.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
