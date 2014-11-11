<?php

add_theme_support( 'post-thumbnails' );  //theme supports thumbnails

//initial theme options
    add_option('wpedditstyle','default','','yes');
	add_option('wpmenu','fixed','','yes');
	add_option('wplogo','','','yes');


//enqueue the styles. This will be where the theme style chooser comes in for the admin side of the site.

//default
function wpeddit_create(){

global $post, $wpdb,$current_user,$wp;
    get_currentuserinfo();	


if(isset($_POST['save'])){
	// we are saving the post
	$title = $_POST['title'];
	$content = $_POST['content'];
	$image = $_POST['thumbnail'];
	$cat = $_POST['cat'];
	
	
	if(get_option('wpedditnewpost') == 'publish'){
	$status = 'publish';
	}else{
	$status = 'pending';
	}
	
	$posttype = 'post';
	$author = $current_user->ID;
	$taxonomy = 'category';

    $arg = array('description' => "$content", 'parent' => 0);
	$new_cat_id = wp_insert_term("$title", "category", $arg);
	
	?>
		
	<div class="alert">
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	  <strong>Success!</strong> Your category has been created.
	</div>
	
	<?php if(get_option('wpedditnewpost') == 'pending'){
	echo "<br/>The administrator has chosen to make posts pending review before they appear on the site";
	}?>
	
	<style>
		#thumbnail_upload{
			display:none;
		}
	</style>

<?php



	
}


if(!is_user_logged_in()) {
	
	echo "Sorry you must be logged in to do this";
	
}else{ 

?>

<form id="thumbnail_upload" method="post" action="">
<label>title</label>
<input type="text" name="title" id = "title" style = "width:70%"></br><br/>

<div style = "clear:both"></div>

<label>description</label>
<textarea name="content" id="content" style = "width:70%" rows="10" tabindex="4"></textarea>
<input type = "hidden" name = "save" value = 1>
		
<div style = "clear:both"></div>
<input id="wpedditfront" class = "btn btn-primary"  name="wpedditfront" type="submit" value="Submit">
</form>
	

<?php

}

}

add_shortcode("wpedditcreate", "wpeddit_create");


function wpeddit_frontend(){

global $post, $wpdb,$current_user;
    get_currentuserinfo();	


if(isset($_POST['save'])){
	// we are saving the post
	$title = $_POST['title'];
	$content = $_POST['content'];
	$image = $_POST['thumbnail'];
	$cat = $_POST['cat'];

	if(get_option('wpedditnewpost') == 'publish'){
	$status = 'publish';
	}else{
	$status = 'pending';
	}


	$posttype = 'post';
	$author = $current_user->ID;
	$taxonomy = 'category';
	
    $my_post = array(
	    'post_title' => $title,
	    'post_status' => $status,
	    'post_type' => $posttype,
	    'post_author' => $author,
	    'post_content' => $content,
	     );
                        
		
	    $post_id = wp_insert_post( $my_post );
		update_post_meta( $post_id, 'wpedditimage', $image );	
		update_post_meta( $post_id, 'epicredvote', 0);		
		wp_set_post_terms( $post_id, $cat, $taxonomy);?>
		
	<div class="alert">
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	  <strong>Success!</strong> Your post has been added <a href = "<?php echo get_permalink($post_id); ?>">view it</a>.
	</div>
	<style>
		#thumbnail_upload{
			display:none;
		}
	</style>

<?php
	
}

if(!is_user_logged_in()) {
	
	echo "Sorry you must be logged in to do this";
	
}else{ 

?>

<form id="thumbnail_upload" method="post" action="">
<label>Post title</label>
<input type="text" name="title" id = "title" style = "width:70%" required></br><br/>
<div class = "thumbnailform">
	<div class == "thumbinput" style = "width:75%;float:left">
		<label>Thumbmail Image</label>
		<input type="url" name="thumbnail" id="thumbnail" required>
		<br/><i>Paste in your image URL here. A preview will show up to the right.</i>
		<input type = "hidden" name = "save" value = 1>
	</div>
	<div class = "thumbpreview" id = "thumbprev" style = "width:20%;float:right"></div>
</div>

<div style = "clear:both"></div>
</br>

<label>Post Content</label>
<textarea name="content" id="content" style = "width:70%" rows="10" tabindex="4" required></textarea>

<br/>
<?php wpedditcats();?>
<br/>

<input id="wpedditfront" class = "wpeddit-image-upload btn btn-primary" name="wpedditfront" type="submit" value="Submit">
</form>
	

<?php
}

}

function wpedditcats(){
 $args = array(
					    'orderby'            => 'ID', 
					    'order'              => 'ASC',
					    'show_count'         => 0,
					    'hide_empty'         => 0, 
					    'child_of'           => 0,
					    'echo'               => 1,
					    'selected'           => 0,
					    'hierarchical'       => 0, 
					    'name'               => 'cat',
					    'class'              => 'postform',
					    'depth'              => 0,
					    'tab_index'          => 0,
					    'taxonomy'           => 'category',
					    'hide_if_empty'      => false ); 
					
					?>
					 <br/><label>Category</label><br/>
					<?php 
	                  wp_dropdown_categories($args); 
}


add_shortcode("wpedditfront", "wpeddit_frontend");

function epic_reddit_categories(){
global $wpdb, $wp;
	
$taxonomy = 'category';
$terms = get_terms($taxonomy);

	if ($terms) {
	?>
	<ul class = 'red-more'>
	<?php
	  foreach( $terms as $term ) {
	  	
	     $random =  $term->term_id; ?>
	     
	<li><a href = "<?php echo get_category_link( $random ); ?>"><?php echo $term->name;?></a>
		<p>
			<?php echo $term->description; ?>
		</p>
		
	</li>
	    
	 <?php }
	?>
	</ul>
	<?php }
}
add_shortcode("wpedditmorecats", "epic_reddit_categories");

add_action('admin_menu', 'epic_theme_menu');

function epic_theme_menu() {
	add_theme_page('Theme Options', 'Theme Options', 'read', 'epic-theme-settings', 'wpeddit_pages_settings');
}


function wpeddit_pages_settings() {
    
    global $wpdb;    #} Req
    
    if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.','wpeddit') );
    }
    
    
?><div id="sgpBod">
     	<img src = "<?php echo get_template_directory_uri() . '/img/wpeddit-default.png'; ?>"/>
        	             
         <?php
         	if(isset($_GET['save'])){ 
             if ($_GET['save'] == "1"){
                epic_theme_save_settings();
             }
		    }
            if(!isset($_GET['save'])){
                epic_theme_settings();
            }
    ?></div>
    

</div>
<?php
}

function epic_theme_settings(){
    global $wpdb;  #} Req
    
    $wpeddit = array();
    $wpeddit['wpeddit'] 		    =           get_option('wpedditstyle'); 
	$wpeddit['wpmenu'] 		  		=           get_option('wpmenu');     
	$wpeddit['wplogo'] 		  		=           get_option('wplogo'); 

    
    ?>
    
<div class = 'wrap'>

	
     <form action="?page=epic-theme-settings&save=1" method="post">
     <div class="postbox">
     <h3><label>General settings</label></h3>
     
     <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="6">
         

        <?php
        //settings array
		$wpstylearray = array('default','bootstrap','light','dark');
		$wpmenuarray = array('fixed','floating');
		
        $i = 0;
        ?>
        <tr valign="top">
        	<td width="25%" align="left"><strong>Theme stylesheet:</strong></td>
			<td align="left">
                <select id= 'wpedditstyle' name = 'wpedditstyle'>
                	<?php foreach ($wpstylearray as $wpstylea){
                		if($wpstylea == $wpeddit['wpeddit'] ){
                		echo "<option value = '$wpstylea' selected>$wpstylea</option>";	
                		}else{
                		echo "<option value = '$wpstylea'>$wpstylea</option>";
						}
						$i++;
					}
					?>
                </select><br><i>What style would you like for your site</i>
        	</td>
        </tr>
        
         <tr valign="top">
        	<td width="25%" align="left"><strong>Menu style:</strong></td>
			<td align="left">
                <select id= 'wpmenu' name = 'wpmenu'>
                	<?php foreach ($wpmenuarray as $wpmenu){
                		if($wpmenu == $wpeddit['wpmenu'] ){
                		echo "<option value = '$wpmenu' selected>$wpmenu</option>";	
                		}else{
                		echo "<option value = '$wpmenu'>$wpmenu</option>";
						}
						$i++;
					}
					?>
                </select><br><i>Fixed or floating menu? (fixed to the top of the page)</i>
        	</td>
        </tr>
        
        <tr valign="top">
        	<td width="25%" align="left"><strong>Logo URL:</strong></td>
			<td align="left">
               	<input type="text" name="logo" id="logo" value = "<?php echo $wpeddit['wplogo'];?>"><br><i>Only shown if floating menu is chosen</i>
        	</td>
        </tr>

      
    </table>
    <p id="footerSub"><input class = "button-primary" type="submit" value="Save settings" /></p>
    </form>
</div>

</div>

<?php
}

#} Save options changes
function epic_theme_save_settings(){
    
    global $wpdb;  #} Req
    
    $wpeddit= array();
    $wpeddit['wpedditstyle'] = $_POST['wpedditstyle'];
	$wpeddit['wpmenu'] = $_POST['wpmenu'];
	$wpeddit['wplogo'] = $_POST['logo'];
    
    #} Save down
    update_option("wpedditstyle", $wpeddit['wpedditstyle']);
	update_option("wpmenu", $wpeddit['wpmenu']);
	update_option("wplogo", $wpeddit['wplogo']);


    #} Msg
    ?>

	<div id="message" class="updated fade below-h2"><strong>Success!</strong> Settings Saved.</div>

    <?php
    #} Run standard
    epic_theme_settings();
    
}


function epic_scripts_with_jquery() {
 // Register the script like this for a theme: 
wp_register_script( 'epic-script', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ) ); 
// For either a plugin or a theme, you can then enqueue the script: 
wp_enqueue_script( 'epic-script' );
} 

add_action( 'wp_enqueue_scripts', 'epic_scripts_with_jquery' );

add_action( 'after_setup_theme', 'epic_register_my_menus' );
 
function epic_register_my_menus() {
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'wpeddit' ),
	) );
}



add_filter('show_admin_bar', '__return_false');

register_sidebar(array(
  'name' => __( 'Right Hand Sidebar' ),
  'id' => 'right-sidebar',
  'description' => __( 'Widgets in this area will be shown on the right-hand side.' ),
  'before_title' => '<p class = "title">',
  'after_title' => '</p>'
));

register_sidebar(array(
  'name' => __( 'Footer 1' ),
  'id' => 'footer-sidebar',
  'description' => __( 'Widgets in this area will be shown in the footer 1 location.' ),
  'before_title' => '<h2>',
  'after_title' => '</h2>'
));

register_sidebar(array(
  'name' => __( 'Footer 2' ),
  'id' => 'footer2-sidebar',
  'description' => __( 'Widgets in this area will be shown in the footer 2 location.' ),
  'before_title' => '<h2>',
  'after_title' => '</h2>'
));

register_sidebar(array(
  'name' => __( 'Footer 3' ),
  'id' => 'footer3-sidebar',
  'description' => __( 'Widgets in this area will be shown in the footer 3 location.' ),
  'before_title' => '<h2>',
  'after_title' => '</h2>'
));

register_sidebar(array(
  'name' => __( 'Footer 4' ),
  'id' => 'footer4-sidebar',
  'description' => __( 'Widgets in this area will be shown in the footer 4 location.' ),
  'before_title' => '<h2>',
  'after_title' => '</h2>'
));


function epic_find_cat(){
	 		
			$category_id = get_query_var('cat');
			return   $category_id;
}

 class Bootstrap_Walker extends Walker_Nav_Menu 
    {     
     
        /* Start of the <ul> 
         * 
         * Note on $depth: Counterintuitively, $depth here means the "depth right before we start this menu".  
         *                   So basically add one to what you'd expect it to be 
         */         
        function start_lvl(&$output, $depth) 
        {
            $tabs = str_repeat("\t", $depth); 
            // If we are about to start the first submenu, we need to give it a dropdown-menu class 
            if ($depth == 0 || $depth == 1) { //really, level-1 or level-2, because $depth is misleading here (see note above) 
                $output .= "\n{$tabs}<ul class=\"dropdown-menu\">\n"; 
            } else { 
                $output .= "\n{$tabs}<ul>\n"; 
            } 
            return;
        } 
         
        /* End of the <ul> 
         * 
         * Note on $depth: Counterintuitively, $depth here means the "depth right before we start this menu".  
         *                   So basically add one to what you'd expect it to be 
         */         
        function end_lvl(&$output, $depth)  
        {
            if ($depth == 0) { // This is actually the end of the level-1 submenu ($depth is misleading here too!) 
                 
                // we don't have anything special for Bootstrap, so we'll just leave an HTML comment for now 
                $output .= '<!--.dropdown-->'; 
            } 
            $tabs = str_repeat("\t", $depth); 
            $output .= "\n{$tabs}</ul>\n"; 
            return; 
        }
                 
        /* Output the <li> and the containing <a> 
         * Note: $depth is "correct" at this level 
         */         
        function start_el(&$output, $item, $depth, $args)  
        {    
            global $wp_query; 
            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : ''; 
            $class_names = $value = ''; 
            $classes = empty( $item->classes ) ? array() : (array) $item->classes; 

            /* If this item has a dropdown menu, add the 'dropdown' class for Bootstrap */ 
            if ($item->hasChildren) { 
                $classes[] = 'dropdown'; 
                // level-1 menus also need the 'dropdown-submenu' class 
                if($depth == 1) { 
                    $classes[] = 'dropdown-submenu'; 
                } 
            } 

            /* This is the stock Wordpress code that builds the <li> with all of its attributes */ 
            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ); 
            $class_names = ' class="' . esc_attr( $class_names ) . '"'; 
            $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';             
            $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : ''; 
            $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : ''; 
            $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : ''; 
            $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : ''; 
            $item_output = $args->before; 
                         
            /* If this item has a dropdown menu, make clicking on this link toggle it */ 
            if ($item->hasChildren && $depth == 0) { 
                $item_output .= '<a'. $attributes .' class="dropdown-toggle" data-toggle="dropdown">'; 
            } else { 
                $item_output .= '<a'. $attributes .'>'; 
            } 
             
            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after; 

            /* Output the actual caret for the user to click on to toggle the menu */             
            if ($item->hasChildren && $depth == 0) { 
                $item_output .= '<b class="caret"></b></a>'; 
            } else { 
                $item_output .= '</a>'; 
            } 

            $item_output .= $args->after; 
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args ); 
            return; 
        }
        
        /* Close the <li> 
         * Note: the <a> is already closed 
         * Note 2: $depth is "correct" at this level 
         */         
        function end_el (&$output, $item, $depth, $args)
        {
            $output .= '</li>'; 
            return;
        } 
         
        /* Add a 'hasChildren' property to the item 
         * Code from: http://wordpress.org/support/topic/how-do-i-know-if-a-menu-item-has-children-or-is-a-leaf#post-3139633  
         */ 
        function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) 
        { 
            // check whether this item has children, and set $item->hasChildren accordingly 
            $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]); 

            // continue with normal behavior 
            return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output); 
        }         
    } 


?>