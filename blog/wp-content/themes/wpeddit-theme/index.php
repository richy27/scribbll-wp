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

?>


<div class = 'reddit-pad'></div>


<div class = 'container main-content'>
	
	<div class = 'row'>
		<div class = 'span8'>
			
		<div class="tabbable">  
			<ul class="nav nav-tabs">  
			<li class="active"><a href="#1" data-toggle="tab">hot</a></li>  
			<li class=""><a href="#2" data-toggle="tab">new</a></li>  

			</ul>  
		<div class="tab-content">  
			<div class="tab-pane active" id="1">  
			<p>
				<?php $args = 'hot'; ?>
				<?php epic_reddit_index($args); ?>
				
			</p>  
			</div>  
			<div class="tab-pane" id="2">  
			<p>
				<?php $args = 'new'; ?>
				<?php epic_reddit_index($args); ?>
			</p>  
			</div>  

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