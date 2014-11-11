<?php
/**
 * The Sidebar containing the footer widget areas.
 *
 * @package InkZine
 */
?>
	<div class="clear"></div>
	</div><!--.container-->
	<div id="footer-sidebar" data-stellar-background-ratio="0" class="widget-area col-md-12 clearfix" role="complementary">
	 <div id="footer-inner-wrapper">	
		<div class="container col-md-12">
			<?php do_action( 'before_sidebar' ); ?>
			<?php if ( is_active_sidebar( 'sidebar-2' ) ) { ?>
			<div class="footer-column col-md-4"> <?php
				dynamic_sidebar( 'sidebar-2'); 
			?> </div> <?php	
			}
				
			if ( is_active_sidebar( 'sidebar-3' ) ) { ?>
			<div class="footer-column col-md-4"> <?php
				dynamic_sidebar( 'sidebar-3'); 
			?> </div> <?php	
			}
	
			if ( is_active_sidebar( 'sidebar-4' ) ) { ?>
			<div class="footer-column col-md-4"> <?php
				dynamic_sidebar( 'sidebar-4'); 
			?> </div> <?php	
			}
			?>	 
		</div>	
			