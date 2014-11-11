<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div class='row'>
	<div class='col-md-2' style='padding-right:0px'>
		<?php EpicMainNav(); ?>
	</div>

	<div id="main-sidebar-sub" class="container bg-dark sub-mobile-menu">
		<?php EpicSideSub(); ?>
	</div>

<div class='col-md-8 maincontent toppad'>
	<div class='row author-pad'>
	<?php 
	$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
	echo get_avatar( $author->ID, 124 ); 
	echo "<h1>" . $author->user_firstname . " " . $author->user_lastname . "</h1>";

	?>
	<em class='g'><?php echo $author->description; ?></em>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li class = "active"><a href="#posts" role="tab" data-toggle="tab">Submitted Links</a></li>
  <li><a href="#questions-a" role="tab" data-toggle="tab">Submitted Questions</a></li>
  <li><a href="#comments-a" role="tab" data-toggle="tab">Comments</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane" id="comments-a">
<?php
     $args = array(
            'user_id' => $author->ID,
            'status' => 'approve'
            );

        $comments = get_comments( $args );

        if ( $comments )
        {

            foreach ( $comments as $c )
            {
            $output.= '<div class="row bm"><div class="span5">
						<div class="reddit-post pull-left"><p class="title"><a href="'.get_comment_link( $c->comment_ID ).'">';
            $output.= get_the_title($c->comment_post_ID);
            $output.= '</a></p><div class="tagline">';
            $output.= $c->comment_content;
            $output.= '</div></div></div></div>';
            }

            echo $output;
        } else { 
            echo "This user has not made any comments";
        }
?>
  </div>
<div class="tab-pane active" id="posts">
  		<?php
			global $wp_query;
			$args = array_merge( $wp_query->query, array( 'posts_per_page' => -1 ) );
			query_posts( $args );
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
			
			<?php }else{ ?>
				
			<?php } ?>
			
			
					<div class = 'span5'>
						<div class = 'reddit-post pull-left'>
							<div class='author-ava'>
								<span class='author-tool' data-toggle="tooltip" data-placement="bottom" title="<?php echo get_the_author_meta('user_nicename'); ?>">  <?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?></span>
							</div>
						<?php if($post->post_type == 'post'){
							$out = get_post_meta($post->ID, 'outbound', true);
						 ?>
						<p class = 'title'><a href="<?php echo $out; ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php the_title(); ?></a><span class='tiny grey link-domain out-link'>  <?php echo remove_http($out) ?></span></p>
						<?php }else{ ?>
						<p class = 'title'><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php the_title(); ?></a></p>
						<?php } ?>
				<span class = 'tagline'>submitted <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?> by <?php the_author_posts_link();    
						?> in <?php $category = get_the_category(); 
						if($category[0]){
						echo '<a href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</a>';
						} ?>
						</span> 
							
							<?php if(!is_single()){ ?>
							<?php }else{ ?>
							<?php the_content(); ?> 
							<?php } ?>
							<div class='post-item-footer'>
								<a href="<?php comments_link(); ?>">
							    <?php comments_number( 'Start a discussion', '1 comment', '% comments' ); ?>. 
							    </a>
							    <span class='com-ava'>
							    	<?php
							    	$comments = get_comments('post_id=' . $post->ID);
									foreach($comments as $comment): ?>
<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo $comment->comment_author; ?>">  <?php echo get_avatar( $comment->user_id , 20 ); ?></span>
									<?php	endforeach;
							    	?>
</span>
							</div>
							<div class='post-details post-details-<?php echo $post->ID; ?>'>
								<?php if($post->post_type =='post'){
									echo '<div class="post-details-title"><b>Article Details</b></div>';
								}else{
									echo '<div class="post-details-title"><b>Question Details</b></div>';
								} ?>
								<p><?php the_content(); ?></p>
							</div>
						</div>
					

					</div>

			
			<div style="clear:both"></div>
			
				<div class = 'span8 pull-right'>
					<?php comments_template(); ?>
				</div>
			


				</div>
			</div>


			
			<?php } ?>
			
			<?php endwhile; ?>

			<?php else: ?> 
				<p><?php _e('This user has not posted anything yet.'); ?></p> 
			<?php endif; ?>
	
		    <div class='next-posts-link'>
            <?php echo get_next_posts_link('More Posts'); ?>
			</div>
			
			<?php wp_reset_query(); ?>
  </div>

<!-- questions -->
<div class="tab-pane active" id="questions-a">
  		<?php
			global $wp_query;
			$args = array_merge( $wp_query->query, array( 'posts_per_page' => -1 , 'post_type' => 'questions') );
			query_posts( $args );
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
			
			<?php }else{ ?>
				
			<?php } ?>
			
			
					<div class = 'span5'>
						<div class = 'reddit-post pull-left'>
							<div class='author-ava'>
								<span class='author-tool' data-toggle="tooltip" data-placement="bottom" title="<?php echo get_the_author_meta('user_nicename'); ?>">  <?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?></span>
							</div>
						<?php if($post->post_type == 'post'){
							$out = get_post_meta($post->ID, 'outbound', true);
						 ?>
						<p class = 'title'><a href="<?php echo $out; ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php the_title(); ?></a><span class='tiny grey link-domain out-link'>  <?php echo remove_http($out) ?></span></p>
						<?php }else{ ?>
						<p class = 'title'><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php the_title(); ?></a></p>
						<?php } ?>
				<span class = 'tagline'>submitted <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?> by <?php the_author_posts_link();    
						?> in <?php $category = get_the_category(); 
						if($category[0]){
						echo '<a href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</a>';
						} ?>
						</span> 
							
							<?php if(!is_single()){ ?>
							<?php }else{ ?>
							<?php the_content(); ?> 
							<?php } ?>
							<div class='post-item-footer'>
								<a href="<?php comments_link(); ?>">
							    <?php comments_number( 'Start a discussion', '1 comment', '% comments' ); ?>. 
							    </a>
							    <span class='com-ava'>
							    	<?php
							    	$comments = get_comments('post_id=' . $post->ID);
									foreach($comments as $comment): ?>
<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo $comment->comment_author; ?>">  <?php echo get_avatar( $comment->user_id , 20 ); ?></span>
									<?php	endforeach;
							    	?>
</span>
							</div>
							<div class='post-details post-details-<?php echo $post->ID; ?>'>
								<?php if($post->post_type =='post'){
									echo '<div class="post-details-title"><b>Article Details</b></div>';
								}else{
									echo '<div class="post-details-title"><b>Question Details</b></div>';
								} ?>
								<p><?php the_content(); ?></p>
							</div>
						</div>
					

					</div>

			
			<div style="clear:both"></div>
			
				<div class = 'span8 pull-right'>
					<?php comments_template(); ?>
				</div>
			


				</div>
			</div>


			
			<?php } ?>
			
			<?php endwhile; ?>

			<?php else: ?> 
				<p><?php _e('This user has not posted anything yet.'); ?></p> 
			<?php endif; ?>
	
		    <div class='next-posts-link'>
            <?php echo get_next_posts_link('More Posts'); ?>
			</div>
			
			<?php wp_reset_query(); ?>
  </div>

</div>

</div>


<?php
get_footer();
