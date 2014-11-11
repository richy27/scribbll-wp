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

global $wp_query,$post,$wpdb,$current_user;
$wpdb->myo_ip   = $wpdb->prefix . 'epicred';
get_currentuserinfo();

$postvote = get_post_meta($post->ID, 'epicredvote' ,true);
			if($postvote == NULL){
				$postvote = 0;
			}
			$fid = $current_user->ID;

			
			$query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $post->ID";
			$al = $wpdb->get_var($query);
			
			if($al == NULL){
				$al = 0;
			}
			if($al == 1){
				$redclassu = 'upmod';
				$redclassd = 'down';
				$redscore = 'likes';
			}elseif($al == -1){
				$redclassd = 'downmod';
				$redclassu = 'up';
				$redscore = "dislikes";
			}else{
				$redclassu = "up";
				$redclassd = "down";
				$redscore = "unvoted";
			}
			
global $query_string;
query_posts( $query_string);

?>

<div class = 'reddit-pad'></div>


<div class = 'container'>
	
	<div class = 'row'>
		<div class = 'span8'>
			
<div class = 'row' style = 'margin-bottom:20px'>
			<?php  if(!is_user_logged_in()) { ?>
			<a href="#myModal" data-toggle="modal">
			<?php } ?>

			<div class = 'reddit-voting'>
				<ul class="unstyled">
			<?php  if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
					<div class="arrow2 <?php echo $redclassu;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "up" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
					<div class="score2 <?php echo $redscore;?> score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
					<div class="arrow2 <?php echo $redclassd;?> arrow-down-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "down" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
					<?php }else{ ?>
					<div class="arrow <?php echo $redclassu;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "up" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
					<div class="score <?php echo $redscore;?> score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
					<div class="arrow <?php echo $redclassd;?> arrow-down-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "down" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>	
					<?php }  ?>
				</ul>
			</div>	
	
			<?php  if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
			</a>
			<?php } ?>
			
			<div class = 'span7'>
							<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
			<?php if ( has_post_thumbnail() ) { ?>
				<div class = 'span4 reddit-image-single pull-left'>
					<img src = "<?php echo $image[0]; ?>" width = "210px" class="img-rounded">
				</div>
			<?php }else{ ?>
				<div class = 'span4 reddit-image-single pull-left'>
					<img src = "<?php echo get_post_meta( $post->ID, 'wpedditimage', true ); ?>" width = "210px" class="img-rounded">
				</div>
			<?php } ?>
			</div>

			
			</div>
			
			<div class = 'row'>
				<p class = 'title'><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>

			<div class = 'reddit-post2 pull-left'>
				<p class = 'title'></p>
				
			<p>
				<?php $post_data = get_post( $post->ID );
				$the_content = str_replace( ']]>',']]&gt>', apply_filters( 'the_content', $post_data->post_content ) );
				echo $the_content;
				?>
			</p>
			</div>
			
			<div style="clear:both"></div>

				<div class = 'span8 pull-right'>
					<?php comments_template(); ?>
				</div>
			
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