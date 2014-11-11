<?php
//extra functions for WPeddit (Growh Hackers Edition) PRO!!
/* the EQuestion Growth Hackers website utilises the WPeddit theme and as such the theme developer is constantly asked how can I have a site like GH
*  this Child-Theme goes steps of the way to give a cool, flat skin with GH functionality.
*/


#} Initialisation - enqueueing scripts/styles

 // add_rewrite_rule('([^/]*)/([^/]*)/?','index.php?latest=$matches[1]','top');
 // add_rewrite_rule('([^/]*)/([^/]*)/?','index.php?latest=$matches[1]&paged=$matches[2]','top');



function epic_query_vars_filter( $vars ){
  $vars[] = "latest";
  return $vars;
}
add_filter( 'query_vars', 'epic_query_vars_filter' );

function epicgrav(){
        if ( is_user_logged_in() ){
      $size='124';
      $current_user = wp_get_current_user();
      $email = $current_user->user_email;
      $author_id = $current_user->id;
      $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
      ?>
      <span class="pad"><img src="<?php echo $grav_url; ?>" alt="" class="pad avatar avatar-30 photo"/>
        <br/>
        <em>change avatar at <a href = "http://en.gravatar.com/" target = "_blank">Gravatar.com</a></em>
      </span>
      <?php
    }
}
add_shortcode('epicgrava', 'epicgrav');

function epic_customizer( $wp_customize ) {
  // customizer build code
  $colors = array();
  $colors[] = array(
    'slug'=>'theme_color', 
    'default' => '#99b898',
    'label' => __('Theme Color', 'Epic')
  );
foreach( $colors as $color ) {
  // SETTINGS
  $wp_customize->add_setting(
    $color['slug'], array(
      'default' => $color['default'],
      'type' => 'option', 
      'capability' => 
      'edit_theme_options'
    )
  );
  // CONTROLS
  $wp_customize->add_control(
    new WP_Customize_Color_Control(
      $wp_customize,
      $color['slug'], 
      array('label' => $color['label'], 
      'section' => 'colors',
      'settings' => $color['slug'])
    )
  );
}
}
add_action( 'customize_register', 'epic_customizer' );


add_action('init', 'theme__init');
function theme__init(){
  
    global $Questionsmash_slugs, $Questionsmash_taxonomy; #} Req
    
    
    #} Custom post types - Questions
    $labels = array(
                'name' => _x('Questions', 'post type general name','QuestionsMash'),
                'singular_name' => _x('Questions', 'post type singular name','QuestionsMash'),
                'add_new' => _x('Manually Add Question', 'Question','QuestionsMash'),
                'add_new_item' => __('Manually Add New Question','QuestionsMash'),
                'edit_item' => __('Edit Question','QuestionsMash'),
                'new_item' => __('New Question','QuestionsMash'),
                'view_item' => __('View Question','QuestionsMash'),
                'search_items' => __('Search Questions','QuestionsMash'),
                'not_found' =>  __('No Questions found','QuestionsMash'),
                'not_found_in_trash' => __('No Questions found in Trash','QuestionsMash'),
                'parent_item_colon' => '',
                'menu_name' => 'Questions'
            );
    $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'questions','with_front' => FALSE ),
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => false,
                'menu_icon' => '',
                'menu_position' => null,
                'supports' => array( 'title', 'author','comments')
            );
    #} Register it
    register_post_type('questions',$args);
  
   if(get_option('theme_myo_flush') == 'no'){
   flush_rewrite_rules();  //flushes rewrite rules
   update_option("theme_myo_flush", 'yes');
   }

    

  }


add_action( 'wp_ajax_nopriv_epicred_submit', 'epicred_submit' );
add_action( 'wp_ajax_epicred_submit', 'epicred_submit' );
function epicred_submit(){

  $title      = $_POST['title'];
  $content    = $_POST['content'];
  $cat        = $_POST['cat'];
  $posttype   = $_POST['submit_type'];
  $details    = $_POST['new_post_details'];

 
  $status = 'publish';

  $author = $current_user->ID;
  $taxonomy = 'category';
  
    $my_post = array(
      'post_title' => $title,
      'post_status' => $status,
      'post_type' => $posttype,
      'post_author' => $author,
      'post_content' => $details,
       );

    //clean up the URL 
                        
    
    $post_id = wp_insert_post( $my_post );
    
    // update_post_meta( $post_id, 'wpedditimage', $image );
    if($posttype == 'post'){
      $content = esc_url($content);
      update_post_meta($post_id,'outbound', $content);
    }
    update_post_meta( $post_id, 'details', $details);
    // update_post_meta( $post_id, 'wpedditimage', $image ); 
    update_post_meta( $post_id, 'epicredvote', 0); 
    update_post_meta( $post_id, 'epicredrank', 0);    
    wp_set_post_terms( $post_id, $cat, $taxonomy);
    $permalink = get_permalink( $post_id );

    //control the pace of posting...
    $current_user = wp_get_current_user();
    update_user_meta($current_user->ID, 'ehacklast', time());


    $response['stat'] = $status;
    $response['perma'] = $permalink;

    
    echo json_encode($response);
  
  // IMPORTANT: don't forget to "exit"
  exit;



}




add_filter('the_content', 'epic_nofollow');
add_filter('the_excerpt', 'epic_nofollow');
function epic_nofollow($content) {
    return preg_replace_callback('/<a[^>]+/', 'epic_nofollow_callback', $content);
}
function epic_nofollow_callback($matches) {
    $link = $matches[0];
    $site_link = get_bloginfo('url');
    if (strpos($link, 'rel') === false) {
        $link = preg_replace("%(href=\S(?!$site_link))%i", 'rel="nofollow" $1', $link);
    } elseif (preg_match("%href=\S(?!$site_link)%i", $link)) {
        $link = preg_replace('/rel=\S(?!nofollow)\S*/i', 'rel="nofollow"', $link);
    }
    return $link;
}



function remove_http($url) {
   $disallowed = array('http://', 'https://');
   foreach($disallowed as $d) {
      if(strpos($url, $d) === 0) {
         return str_replace($d, '', $url);
      }
   }
   $url = rtrim($url," /");
   $url = ltrim($url);
   return $url;
}


function EpicHack_scripts(){

    wp_enqueue_style('eStorestyle', get_stylesheet_directory_uri() . '/style.css', 100 );
    wp_enqueue_style('eStoreboot', get_stylesheet_directory_uri() . '/css/bootstrap/bootstrap.css' );
    wp_enqueue_style('eStorehome', get_stylesheet_directory_uri() . '/css/home.css' );

    wp_register_script( 'eStoreboot', get_stylesheet_directory_uri() . '/js/bootstrap/bootstrap.js', array( 'jquery' ) ); 
    wp_enqueue_script( 'eStoreboot' );

    wp_register_script( 'eStoretheme', get_stylesheet_directory_uri() . '/js/epictheme.js', array( 'jquery' ) ); 
    wp_enqueue_script( 'eStoretheme' );

}

if(!is_admin()){
  EpicHack_scripts();
}

function register_epic_menus() {
  register_nav_menus(
    array(
      'primary' => __( 'Mobile Menu' ),
      'side-menu' => __( 'Side Menu' )
    )
  );
}
add_action( 'init', 'register_epic_menus' );


function EpicDropDown(){
  ?>
  <ul id="utils-list">
    <li>
            <a id="post-expand-all" class="small expand-all expand-all-now" href="#">Expand All</a>
          </li>
    <li>
      <a class="dropdown-toggle utility-dropdown dark-grey small" id="cat-drop" role="button" data-toggle="dropdown" href="#">
          <?php if(is_category()){
            single_cat_title('Category: '); }else{
            echo 'Category: All';
          } ?>
          <b class="caret"></b>
        </a>
      <ul class="dropdown-menu" id="category-dropdown-menu" role="menu" aria-labelledby="cat-drop">
          <li class='cat-item'><a class="small dark-grey" href="<?php echo home_url(); ?>" title="All Categories">All</a></li>
        <?php EpicCats(); ?>
      </ul>
    </li>
    <li>
          <a class="dropdown-toggle utility-dropdown dark-grey small" id="type-drop" role="button" data-toggle="dropdown" href="#">
      Type: All <b class="caret"></b>
    </a>
    <ul class="dropdown-menu" id="type-dropdown-menu" role="menu" aria-labelledby="type-drop">
      <li class="small dark-grey"><a href="<?php echo home_url(); ?>" title="Filter posts by type: All">All</a></li>
      <?php EpicTypes(); ?>
    </ul>
      </li>
  </ul>
  <?php
}

function EpicSideSub(){

$args = array(
  'show_option_all'    => '',
  'show_option_none'   => '',
  'orderby'            => 'ID', 
  'order'              => 'ASC',
  'show_count'         => 0,
  'hide_empty'         => 0, 
  'child_of'           => 0,
  'exclude'            => '',
  'echo'               => 1,
  'selected'           => 0,
  'hierarchical'       => 0, 
  'name'               => 'cat',
  'id'                 => '',
  'class'              => 'postform',
  'depth'              => 0,
  'tab_index'          => 0,
  'taxonomy'           => 'category',
  'hide_if_empty'      => false,
);


  ?>
      <a href="/" title="Cancel" class="lt-grey" id="icon-cancel-submit"><i class="icon-remove icon-large"></i> Cancel</a>
        <div id="submit-container">
      <ul class="nav nav-tabs" id="submit-tabs">
        <li class="border-box active"><a class="no-transition" href="#submit-article" data-toggle="tab">Submit Article</a></li>
        <li class="border-box"><a class="no-transition" href="#submit-question" data-toggle="tab">Ask Question</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="submit-article">
          <form id="submit-article-form" method="post" action="#" class="clearfix">
            <label>Title</label>
            <input type="text" name="title" id="title" placeholder="Original title" value="" required="">
            <label>URL</label>
            <input type="url" class="form-control" name="content" id="out" placeholder="http://example.com" value="" required="">
            <input type="hidden" id="art_type" name="submit_type" value="post">
            <input type="hidden" name="save" value="1">
            <div style="clear:both"></div>
              <label>Category</label>
        <?php wp_dropdown_categories($args); ?>
                        <label>Article Summary <span class="tiny">(optional)</span></label>
            <textarea id="article-details-input" name="new_post_details" placeholder="Optionally, add a summary of the post here." wrap="soft"></textarea>
            <input type="hidden" name="_wpnonce" value="91dee83183"><input type="hidden" name="_wp_http_referer" value="/">            <input id="submit-article-btn" class="btn btn-primary pull-left" name="submit-article-form" type="submit" value="Submit">
            <div style="clear:both"></div>
            <br>
            <div class='toosoon'></div>
          </form>
        </div>
        <div class="tab-pane" id="submit-question">
          <form id="submit-question-form" method="post" action="#" class="clearfix">
            <label>Question</label>
            <textarea id="question-text" name="title" wrap="soft" required="">Ask Q: </textarea>
            <input type="hidden" name="content" value="">
            <div style="clear:both"></div>
              <label>Category</label>
        <?php wp_dropdown_categories($args); ?>
                        <label>Question Details <span class="tiny">(optional)</span></label>
            <textarea id="question-details-input" name="new_post_details" placeholder="Please keep your question concise and add any additional details here." wrap="soft"></textarea>
                        <input type="hidden" id="q_type" name="submit_type" value="questions">
            <input type="hidden" name="save" value="1">
            <div style="clear:both"></div>
            <input type="hidden" id="_wpnonce"  value="91dee83183"><input type="hidden" name="_wp_http_referer" value="/">            <input id="submit-question-btn" class="btn btn-primary pull-left" name="submit-question-form" type="submit" value="Submit">
            <div style="clear:both"></div>
            <br>
            <div class='toosoon'></div>
          </form>
        </div>  
      </div>
    </div>
  <?php
}
function EpicSideSub2(){

$args = array(
  'show_option_all'    => '',
  'show_option_none'   => '',
  'orderby'            => 'ID', 
  'order'              => 'ASC',
  'show_count'         => 0,
  'hide_empty'         => 0, 
  'child_of'           => 0,
  'exclude'            => '',
  'echo'               => 1,
  'selected'           => 0,
  'hierarchical'       => 0, 
  'name'               => 'cat',
  'id'                 => '',
  'class'              => 'postform',
  'depth'              => 0,
  'tab_index'          => 0,
  'taxonomy'           => 'category',
  'hide_if_empty'      => false,
);


  ?>
      <a href="/" title="Cancel" class="lt-grey" id="icon-cancel-submit"><i class="icon-remove icon-large"></i> Cancel</a>
        <div id="submit-container">
      <ul class="nav nav-tabs" id="submit-tabs">
        <li class="border-box active"><a class="no-transition" href="#submit-article2" data-toggle="tab">Submit Article</a></li>
        <li class="border-box"><a class="no-transition" href="#submit-question2" data-toggle="tab">Ask Question</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="submit-article2">
          <form id="submit-article-form" method="post" action="#" class="clearfix">
            <label>Title</label>
            <input type="text" name="title" id="title" placeholder="Original title" value="" required="">
            <label>URL</label>
            <input type="url" class="form-control" name="content" id="out" placeholder="http://example.com" value="" required="">
            <input type="hidden" id="art_type" name="submit_type" value="post">
            <input type="hidden" name="save" value="1">
            <div style="clear:both"></div>
              <label>Category</label>
        <?php wp_dropdown_categories($args); ?>
                        <label>Article Summary <span class="tiny">(optional)</span></label>
            <textarea id="article-details-input" name="new_post_details" placeholder="Optionally, add a summary of the post here." wrap="soft"></textarea>
                        <input type="hidden" name="_wpnonce" value="91dee83183"><input type="hidden" name="_wp_http_referer" value="/">            <input id="submit-article-btn" class="btn btn-primary pull-left" name="submit-article-form" type="submit" value="Submit">
          </form>
        </div>
        <div class="tab-pane" id="submit-question2">
          <form id="submit-question-form" method="post" action="#" class="clearfix">
            <label>Question</label>
            <textarea id="question-text" name="title" wrap="soft" required="">Ask Q: </textarea>
            <input type="hidden" name="content" value="">
            <div style="clear:both"></div>
              <label>Category</label>
        <?php wp_dropdown_categories($args); ?>
                        <label>Question Details <span class="tiny">(optional)</span></label>
            <textarea id="question-details-input" name="new_post_details" placeholder="Please keep your question concise and add any additional details here." wrap="soft"></textarea>
                        <input type="hidden" id="q_type" name="submit_type" value="questions">
            <input type="hidden" name="save" value="1">
            <div style="clear:both"></div>
            <input type="hidden" id="_wpnonce"  value="91dee83183"><input type="hidden" name="_wp_http_referer" value="/">            <input id="submit-question-btn" class="btn btn-primary pull-left" name="submit-question-form" type="submit" value="Submit">
          </form>
        </div>  
      </div>
    </div>
  <?php
}
add_shortcode('epicside','EpicSideSub2');



function EpicMainNav(){


  ?>
      <div id="main-sidebar">
<?php
wp_nav_menu( array( 
  'container' => 'div',
  'container_class' => 'side-nav',
  'theme_location' => 'side-menu',
  'menu_class' => 'nav nav-stacked',
  'walker' => new Hacker_Walker(),                 
  ) );
?>



    <ul class="nav nav-stacked" id="sidebar-nav">

      <li>
      
      <?php if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
      <a href="<?php echo wp_login_url(); ?>" title="Login"> 
    <?php }else{ ?>
      <a id="sidebar-submit" href="/submit-articles" title="Submit Article" class="toggle-submenu ">
      <?php } ?>
            <i class="fa fa-plus-circle fa-2x"></i>
            <span class="icontext">Submit</span>
          </a> 
                </li>
      
    </ul>
  </div>
  <?php
}

function EpicTypes(){
  echo '<li class="small dark-grey"><a href="';
      echo home_url();
      echo '?post_type=post" title="Filter posts by type: Articles">Articles</a></li>
      <li class="small dark-grey"><a href="';
      echo home_url();
      echo'?post_type=questions" title="Filter posts by type: Questions">Questions</a></li>';
}

function EpicCats(){
    $args = array(
    'show_option_all'    => '',
    'orderby'            => 'name',
    'order'              => 'ASC',
    'style'              => 'list',
    'show_count'         => 0,
    'hide_empty'         => 1,
    'use_desc_for_title' => 1,
    'child_of'           => 0,
    'feed'               => '',
    'feed_type'          => '',
    'feed_image'         => '',
    'exclude'            => '',
    'exclude_tree'       => '',
    'include'            => '',
    'hierarchical'       => 1,
    'title_li'           => __( '' ),
    'show_option_none'   => __( 'No categories' ),
    'number'             => null,
    'echo'               => 1,
    'depth'              => 0,
    'current_category'   => 0,
    'pad_counts'         => 0,
    'taxonomy'           => 'category',
    'walker'             => null
  );

    wp_list_categories($args);


}

//menu walker
class Hacker_Walker extends Walker_Nav_Menu 
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
                $output .= "\n{$tabs}<ul class=\"dropdown-menu dropdown-invert\">\n"; 
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
            $output .= $indent . '<li id="menu-item-'. $item->ID . '">';             
            $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : ''; 
            $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : ''; 
            $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : ''; 
            $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : ''; 
            $item_output = $args->before; 
                         
            /* If this item has a dropdown menu, make clicking on this link toggle it */ 
            if ($item->hasChildren && $depth == 0) { 
                $item_output .= '<a'. $attributes .'><i '. $value . $class_names .'></i> '; 
            } else { 
                $item_output .= '<a'. $attributes .'><i '. $value . $class_names .'></i> '; 
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

/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.4.0
 * @author     Thomas Griffin <thomasgriffinmedia.com>
 * @author     Gary Jones <gamajo.com>
 * @copyright  Copyright (c) 2014, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/includes/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'epichackers_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function epichackers_theme_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        // This is an example of how to include a plugin pre-packaged with a theme.
        array(
            'name'               => 'WPeddit Plugin Theme Edition', // The plugin name.
            'slug'               => 'wpeddit-plugin', // The plugin slug (typically the folder name).
            'source'             => get_stylesheet_directory() . '/plugins/wpeddit-plugin.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

        // This is an example of how to include a plugin from the WordPress Plugin Repository.
        array(
            'name'      => 'Theme My Login',
            'slug'      => 'theme-my-login',
            'required'  => false,
        ),

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
            'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
            'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $plugins, $config );

  }


?>