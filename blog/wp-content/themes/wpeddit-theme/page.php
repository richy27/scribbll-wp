<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); 

global $wp_query,$post,$wpdb;
$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

?>

<div class = 'reddit-pad'></div>

			<?php  if(!is_user_logged_in()) { ?>
			<script>var loggedin = 'false';</script>
			<?php }else{  ?>
			<script>var loggedin = 'true';</script>
			<?php } ?>

<div class = 'container'>
	<div class = 'row'>
		<div class = 'span8'>

			<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?> 
			
			
		
			<div class = 'row' style = 'margin-bottom:20px'>


			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
			<?php if ( has_post_thumbnail() ) { ?>
				<div class = 'reddit-image pull-left'>
					<img src = "<?php echo $image[0]; ?>" width = "70" class="img-rounded">
				</div>
			<?php } ?>
			<div class = 'reddit-post pull-left'>
				<p class = 'title'><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
			
					<?php the_content(); ?> 
			

			</div>
			
			<div style="clear:both"></div>
			

			
			</div>
			<?php endwhile; ?>

			<?php else: ?> 
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p> 
			<?php endif; ?>
	
	
				
			<div class="pagination pagination-centered">
				<?php
				global $wp_query;
				
				$big = 999999999; // need an unlikely integer
				echo paginate_links( array(
				'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
				'format' => '?paged=%#%',
				'show_all' => False,
				'end_size' => 1,
				'mid_size' => 2,
				'prev_next' => True,
				'prev_text' => __('&laquo;'),
				'next_text' => __('&raquo;'),
				'current' => max( 1, get_query_var('paged') ),
				'total' => $wp_query->max_num_pages,
				'type' => 'list'
				) );
				?>
			</div>
	
		</div>
	
		<div class = 'span4 sidebar'>
			<?php get_sidebar(); ?>
		</div>
	
	</div>
	
	<!-- Modal -->
		<div id="myModal" class="modal hide fade">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h6 id="myModalLabel">you'll need to login or register to do that</h6>
		  </div>
		  <div class="modal-body">
		    <div class = "reddit-register pull-left divide">
		    	<?php echo do_shortcode("[theme-my-login default_action='register']"); ?>
		    </div>
		    <div class = "reddit-login pull-right">
		    	<?php echo do_shortcode("[theme-my-login default_action='login']"); ?>
		    </div>
		  </div>
		  <div class="modal-footer">
		    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		  </div>
		</div>
	

</div>

<?php get_footer(); ?>