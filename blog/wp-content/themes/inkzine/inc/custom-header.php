<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers

 * @package Inkzine
 */

function inkzine_custom_header_setup() {
	add_theme_support( 'custom-header', array(
		'default-image'          => get_template_directory_uri().'/images/dheader.jpg',
		'width'                  => 1920,
		'height'                 => 400,
		'flex-height'            => true,
		'wp-head-callback'       => 'inkzine_header_style',
		'admin-head-callback'    => 'inkzine_admin_header_style',
		'admin-preview-callback' => 'inkzine_admin_header_image',
	) );
}
add_action( 'after_setup_theme', 'inkzine_custom_header_setup' );

if ( ! function_exists( 'inkzine_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see inkzine_custom_header_setup().
 */
function inkzine_header_style() {
	$header_text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == $header_text_color )
		return;
}
endif; // inkzine_header_style

if ( ! function_exists( 'inkzine_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see inkzine_custom_header_setup().
 */
function inkzine_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			border: none;
		}
	</style>
<?php
}
endif; // inkzine_admin_header_style

if ( ! function_exists( 'inkzine_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see inkzine_custom_header_setup().
 */
function inkzine_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
	</div>
<?php
}
endif; // inkzine_admin_header_image
