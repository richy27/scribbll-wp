<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
     <title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

       
    <meta name="description" content="">
    <meta name="author" content="">


    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
	<?php	wp_enqueue_script("jquery");  
  	if(!is_admin()){
	
	$wpstyle = get_option('wpedditstyle');
		
		if($wpstyle == 'default'){
			wp_enqueue_style('defaultboot', get_template_directory_uri() . '/css/bootstrapdefault.css' );
			wp_enqueue_style('defaultcss', get_template_directory_uri() . '/default.css' );
	
			wp_register_script( 'epic-script', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ) ); 
			wp_enqueue_script( 'epic-script' );
		}
		if($wpstyle == 'bootstrap'){
			wp_enqueue_style('defaultboot', get_template_directory_uri() . '/css/bootstrap.css' );
			wp_enqueue_style('defaultcss', get_template_directory_uri() . '/boot.css' );
	
			wp_register_script( 'epic-script', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ) ); 
			wp_enqueue_script( 'epic-script' );
			}
	
	}?>
	
	<?php wp_head(); ?>

<body>

	<?php
	$defaults = array(
		'theme_location'  => 'header-menu',
		'container'       => 'div',
		'container_class' => 'container',
		'menu_class'      => 'menu',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'items_wrap'      => '<ul id="%1$s" class="nav">%3$s</ul>',
		'depth'           => 0
	);
	?>
	<?php $args = array(
		'show_option_all'    => '',
		'orderby'            => 'name',
		'order'              => 'ASC',
		'style'              => 'list',
		'show_count'         => 0,
		'hide_empty'         => 0,
		'use_desc_for_title' => 1,
		'child_of'           => 0,
		'feed'               => '',
		'feed_type'          => '',
		'feed_image'         => '',
		'exclude'            => '',
		'exclude_tree'       => '',
		'include'            => '',
		'hierarchical'       => 1,
		'title_li'           => '',
		'show_option_none'   => '',
		'number'             => 14,
		'echo'               => 1,
		'depth'              => 0,
		'current_category'   => 0,
		'pad_counts'         => 0,
		'taxonomy'           => 'category',
		'walker'             => null
	); ?>
	
<?php global $current_user;
      get_currentuserinfo();
	   $wpstyle = get_option('wpedditstyle');
?>

<?php
//get a random category code

$taxonomy = 'category';
$terms = get_terms($taxonomy);

if ($terms) {
 $count = 0;
  $random = rand(0,count($terms)-1);  //get a random number
  foreach( $terms as $term ) {
    $count++;
    if ($count == $random ) {  // only if count is equal to random number display get posts for that category
     $random =  $term->term_id;

    }
  }
  
}

?>


<div id = "page">
<?php
if($wpstyle == 'default'){ ?>
		<div id = "sr-header-area">	
		<div class = "width-clip">
							<ul class="inline">
					<div class = 'reddit-left-menu'>
						<li>wp eddit</li>
						<li><a href="<?php echo home_url();?>" title="" style = "color:red;font-weight:bold">front</a></li>
						<li><a href="<?php echo home_url();?>" title="">all</a></li>
						<li><a href = "<?php echo get_category_link( $random ); ?>">Random</a></li>
						<li>|</li>
						<!-- these can come in a future update if anyone actually wants this theme!
							<li class="dropdown cat-item">
		                      <span id="drop2" role="button" class="dropdown-toggle" data-toggle="dropdown">my wpeddits<b class="caret"></b></span>
		                      <ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
		                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
		                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
		                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
		                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class = 'reddit-dotty'>manage subscriptions</a></li>
		                      </ul>
		                    </li>
		                  -->
		                    <?php wp_list_categories($args); ?> 
		                </div>
		                 <div class = 'reddit-more'>
		                    <li class = 'pull-right'><b>More >></b></li>
		                 </div>
		        </ul>
		</div>
		
	</div>
<?php }else{  ?>
<div class="navbar navbar-inverse navbar-fixed-top"> 
	<div class="navbar-inner"> <div class="container"> 
		<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> 
			<span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> 
			<a class="brand" href="<?php echo site_url(); ?>"><?php bloginfo('name'); ?></a> <div class="nav-collapse collapse"> 
				<ul class="nav"> <?php
					wp_nav_menu( array( 
	'container' => 'div',
	'container_class' => 'nav-collapse collapse',
	'theme_location' => 'primary',
	'menu_class' => 'nav',
	'walker' => new Bootstrap_Walker(),									
	) ); ?>
	
	 </ul> </div><!--/.nav-collapse --> </div> </div> </div>


			
<?php }?>			


	

<?php if($wpstyle == 'default'){ ?>
		<div id="header-bottom-left"><a href="<?php echo home_url();?>" id="header-img" class="default-header" title="">reddit.epicplugins.com</a>&nbsp;
			<?php  if(!is_user_logged_in()) { ?>
			<div id="header-bottom-right" class = 'pull-right'><span class="user">want to join? <a href="#myModal" data-toggle="modal" class="login-required">login or register</a> in seconds</span>
			<?php }else{  ?>
			<div id="header-bottom-right" class = 'pull-right'><span class="user">Welcome <a href="<?php echo get_author_posts_url($current_user->ID);?>"><?php echo $current_user->display_name;?></a></span>
				<span class="separator">|</span><a href="<?php echo wp_logout_url(); ?>" title="Logout">logout</a>	
			<?php } ?>
			</div>
	
	</div>
<?php } ?>


 <!-- end header -->



