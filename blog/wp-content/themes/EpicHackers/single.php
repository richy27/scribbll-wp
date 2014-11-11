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
 * Lets enhance this and move the epic_reddit_index($agrs) function into this file.
 */

get_header(); 

if(isset($_POST['save'])){
	EpicArticle();
}

?>

<div class='row'>
	<div class='col-md-2' style='padding-right:0px'>
     <?php EpicMainNav(); ?>
</div>

<div id="main-sidebar-sub" class="container bg-dark sub-mobile-menu">
	<?php EpicSideSub(); ?>
 </div>

<div class='col-md-8 maincontent toppad'>

<?php 
// store in meta
if(get_post_meta($post->ID,'ehackalert',true) == ''){ ?>
<div class="alert alert-info alert-dismissible toppad2 submsgcons" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <strong>Success!</strong> View your submission below<span class='submsg'></span>.
</div>
<?php 
update_post_meta($post->ID,'ehackalert', 1);
} ?>

<div id="main-content-header">
  <div id="must-read-alert" class="main-alert main-alert-notice border-box" style="display:none;"><p>Welcome to the Must Reads! We update this page regularly with only the best content on the site. Enjoy!</p><i class="icon-remove main-alert-close"></i></div>  <h1 class="page-title mike"><?php wp_title(); ?></h1>
  
<div id="feed-utils-container" class="dropdown">
	<?php EpicDropDown(); ?>
</div>

 </div>


<?php
	global $wp_query,$post,$wpdb, $current_user,$query_string;
    get_currentuserinfo();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

	$args = get_query_var('latest');

    //need to create our own query_posts for the hot and controversial
	if($args != 'latest'){
		
	if(!$wp_query) {
    global $wp_query;
    }
    
	$cat = get_query_var('cat');
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'meta_key' => 'epicredrank',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'paged' => $paged,
        'cat' => $cat
    );

    query_posts( array_merge( $args , $wp_query->query ) );
		
	}else{
	wp_reset_query(); 
	$cat = get_query_var('cat');
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
   
        'paged' => $paged,
        'cat' => $cat,
        
    );

    query_posts( $query_string );

	}
    
	if ( have_posts() ) : ?>
 			
			<?php while ( have_posts() ) : the_post(); ?> 
				
			<?php if(is_page()){
				
			}else{
			
			 $postvote = get_post_meta($post->ID, 'epicredvote' ,true);

			wpeddit_post_ranking($post->ID);

			if($postvote == NULL){
				$postvote = 0;
			}
			
			//again if IP locked set the fid variable to be the IP address.
	if(get_option('epicred_ip') == 'yes'){
		$fid = "'" . $_SERVER['REMOTE_ADDR'] . "'";	
	}else{
		$fid = $current_user->ID;
	}
			
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
			
			 ?>
			
			<div class = 'row' style = 'margin-bottom:20px'>
			
			
			<?php if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
			<script>var loggedin = 'false';</script>
			<?php }else{  ?>
			<script>var loggedin = 'true';</script>
			<?php } ?>
			
			<?php if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
			<a href="<?php echo wp_login_url(); ?>" title="Login">
			
			<?php } ?>
			
			<div class = 'span3'>

				<div class = 'reddit-voting'>
					<ul class="unstyled">
				<?php  if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
						<div class="arrow2 fa fa-arrow-up <?php echo $redclassu;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "up" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
						<div class="score2 <?php echo $redscore;?> score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
						<div class="arrow2 fa fa-arrow-down <?php echo $redclassd;?> arrow-down-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "down" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
						<?php }else{ ?>
						<div class="arrow fa fa-arrow-up <?php echo $redclassu;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "up" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
						<div class="score <?php echo $redscore;?> score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
						<div class="arrow fa fa-arrow-down <?php echo $redclassd;?> arrow-down-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "down" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>	
						<?php }  ?>
					</ul>
				</div>	
			<?php  if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
			</a>
			<?php } ?>

			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
			<?php if ( has_post_thumbnail() ) { ?>
				<div class = 'reddit-image pull-left' style = 'width:180px'>
			
				</div>
			<?php }else{ ?>
				<div class = 'reddit-image pull-left' style = 'width:180px'>
				</div>
			<?php } ?>
			
			
					<div class = 'span5'>
						<div class = 'reddit-post pull-left'>
							<div class='author-ava'>
								<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo get_the_author_meta('user_nicename'); ?>">  <?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?></span>
							</div>
						<?php if($post->post_type == 'post'){
							$out = get_post_meta($post->ID, 'outbound', true);
						 ?>
						<p class = 'title'><a href="<?php echo $out; ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php the_title(); ?></a><span class='tiny grey link-domain out-link'>  <?php echo remove_http($out) ?></span></p>
						<?php }else{ ?>
						<p class = 'title'><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php the_title(); ?></a></p>
						<?php } ?>
	
							
							<?php if(!is_single()){ ?>
							<p style = "text-align:justify">
							
							</p>
							<?php }else{ ?>
							<?php the_content(); ?> 
							<?php } ?>
							<div class='post-item-footer'>
								<a href="<?php comments_link(); ?>">
							    <?php comments_number( 'Start a discussion', '1 comment', '% comments' ); ?>. 
							    </a>
							</div>
		
						</div>
					

					</div>

			
			<div style="clear:both"></div>
			
					<?php comments_template(); ?>


				</div>
			</div>
			
			<?php } ?>
			
			<?php endwhile; ?>

			<?php else: ?> 
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p> 
			<?php endif; ?>
	
		    <div class='next-posts-link'>
            <?php echo get_next_posts_link('More Posts'); ?>
			</div>
			
			<?php wp_reset_query(); ?>


<?php get_footer(); ?>