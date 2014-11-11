<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = wp_get_theme();
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

function optionsframework_options() {

	$options = array();
	$imagepath =  get_template_directory_uri() . '/images/';
	$slider_effect_array = array(
		'random' => __('Random', 'inkzine'),
		'sliceDown' => __('Slice Down', 'inkzine'),
		'sliceUp' => __('Slice Up', 'inkzine'),
		'sliceUpLeft' => __('Slice Up Left', 'inkzine'),
		'sliceUpDown' => __('Slice Up Down', 'inkzine'),
		'sliceUpDownLeft' => __('Slice Up Down Left', 'inkzine'),
		'fold' => __('Fold', 'inkzine'),
		'boxRandom' => __('Box Random', 'inkzine'),
		'slideInRight' => __('Slide In Right', 'inkzine'),
		'slideInLeft' => __('Slide In Left', 'inkzine'),
		'boxRain' => __('Box Rain', 'inkzine'),
		'boxRainReverse' => __('Box Rain Reverse', 'inkzine'),
		'boxRainGrow' => __('Box Rain Grow', 'inkzine'),
		'boxRainGrowReverse' => __('Box Rain Grow Reverse', 'inkzine'),
		'fade' => __('Fade', 'inkzine')
	);	
	$true_false = array(
		'true' => __('true', 'inkzine'),
		'false' => __('false', 'inkzine')
	);	
	// Get all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	$carousel_count = array(
		'4' => __('4', 'inkzine'),
		'5' => __('5', 'inkzine'),
		'6' => __('6', 'inkzine'),
		'7' => __('7', 'inkzine'),
		'8' => __('8', 'inkzine'),
		'9' => __('9', 'inkzine'),
		'10' => __('10', 'inkzine'),
		'11' => __('11', 'inkzine'),
		'12' => __('12', 'inkzine'),
	);
	
	//Basic Settings
	
	$options[] = array(
		'name' => __('Basic', 'inkzine'),
		'type' => 'heading');
			
	$options[] = array(
		'name' => __('Site Logo', 'inkzine'),
		'desc' => __('Leave Blank to use text Heading.', 'inkzine'),
		'id' => 'logo',
		'class' => '',
		'type' => 'upload');
		
	$options[] = array(
		'desc' => __('For More Cuztomization Options including Favicon, Styles, etc., <a target="_blank" href="http://inkhive.com/product/inkzine-plus/">Upgrade to Pro Now.</a> ', 'inkzine'),
		'type' => 'info');				
	
	$options[] = array(
		'name' => __('Copyright Text', 'inkzine'),
		'desc' => __('Some Text regarding copyright of your site, you would like to display in the footer.', 'inkzine'),
		'id' => 'footertext2',
		'std' => '',
		'type' => 'text');
		
	
		
	//Design Settings
		
	$options[] = array(
		'name' => __('Layout', 'inkzine'),
		'type' => 'heading');	
	
	$options[] = array(
		'name' => "Sidebar Layout",
		'desc' => "Select Layout for Posts & Pages.",
		'id' => "sidebar-layout",
		'std' => "right",
		'type' => "images",
		'options' => array(
			'left' => $imagepath . '2cl.png',
			'right' => $imagepath . '2cr.png')
	);
	
	$options[] = array(
		'desc' => __('With Inkzine Plus You can choose from Multiple Homepage Layouts including Masonry, Grid, Grid+ and Poster Layout. <a target="_blank" href="http://inkhive.com/product/inkzine-plus/">Upgrade to Pro Now.</a> ', 'inkzine'),
		'type' => 'info');	
	
	$options[] = array(
		'name' => __('Custom CSS', 'inkzine'),
		'desc' => __('Some Custom Styling for your site. Place any css codes here instead of the style.css file.', 'inkzine'),
		'id' => 'style2',
		'std' => '',
		'type' => 'textarea');
		

	//TICKR SETTINGS
	
	$options[] = array(
		'name' => __('Tickr', 'inkzine'),
		'type' => 'heading');	

	$options[] = array(
		'name' => __('Enable Tickr', 'inkzine'),
		'desc' => __('Check this to Enable Tickr', 'inkzine'),
		'id' => 'tickr_enabled',
		'type' => 'checkbox',
		'std' => '0' );	

	$options[] = array(
		'desc' => __('With InkZine Plus you can Enable this ticker on all other pages inlcuding Static Front Page, Posts, Pages, Archives, etc. <a target="_blank" href="http://inkhive.com/product/inkzine-plus/">Upgrade to Pro Now.</a> ', 'inkzine'),
		'type' => 'info');	
		
	if ( $options_categories ) {
	$options[] = array(
		'name' => __('Select Tickr Category', 'inkzine'),
		'desc' => __('The category from which posts will be fetched', 'inkzine'),
		'id' => 'tickr_category',
		'type' => 'select',
		'options' => $options_categories);
	}
	
	$options[] = array(
		'name' => __('Select No of Posts', 'inkzine'),
		'desc' => __('The no. of posts you want to display in the tickr.', 'inkzine'),
		'id' => 'tickr_count',
		'std' => '6',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $carousel_count);	
		
	$options[] = array(
		'desc' => __('InkZine Plus allows you to choose more settings like Ticker Speed, infinite looping, etc. <a target="_blank" href="http://inkhive.com/product/inkzine-plus/">Upgrade to Pro Now.</a> ', 'inkzine'),
		'type' => 'info');	

	//SHOWCASE SETTINGS

	$options[] = array(
		'name' => __('ShowCase', 'inkzine'),
		'type' => 'heading');
	
	$options[] = array(
		'name' => __('Enable Showcase', 'inkzine'),
		'desc' => __('Check this to Enable Showcase', 'inkzine'),
		'id' => 'showcase_enabled',
		'type' => 'checkbox',
		'std' => '0' );	
		
	$options[] = array(
		'name' => __('Showcase 1 Title', 'inkzine'),
		'desc' => __('Title of the first Showcase Block.', 'inkzine'),
		'id' => 'cat1_title',
		'std' => 'Category 1',
		'class' => 'mini',
		'type' => 'text');
	
	if ( $options_categories ) {
	$options[] = array(
		'name' => __('Select Category 1', 'inkzine'),
		'desc' => __('The category from which posts will be fetched.', 'inkzine'),
		'id' => 'cat1',
		'class' => 'mini',		
		'type' => 'select',
		'options' => $options_categories);
	}
		
	$options[] = array(
		'name' => __('Select No of Posts', 'inkzine'),
		'desc' => __('The no. of posts you want to display For Category 1.', 'inkzine'),
		'id' => 'cat1_count',
		'std' => '6',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $carousel_count);	
		
	$options[] = array(
		'name' => __('Showcase 2 Title', 'inkzine'),
		'desc' => __('Title of the second Showcase Block.', 'inkzine'),
		'id' => 'cat2_title',
		'std' => 'Category 2',
		'class' => 'mini',
		'type' => 'text');
	
	if ( $options_categories ) {
	$options[] = array(
		'name' => __('Select Category 2', 'inkzine'),
		'desc' => __('The category from which posts will be fetched.', 'inkzine'),
		'id' => 'cat2',
		'type' => 'select',
		'class' => 'mini',		
		'options' => $options_categories);
	}
		
	$options[] = array(
		'name' => __('Select No of Posts', 'inkzine'),
		'desc' => __('The no. of posts you want to display For Category 2.', 'inkzine'),
		'id' => 'cat2_count',
		'std' => '6',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $carousel_count);
		
	$options[] = array(
		'name' => __('Showcase 3 Title', 'inkzine'),
		'desc' => __('Title of the third Showcase Block.', 'inkzine'),
		'id' => 'cat3_title',
		'std' => 'Category 3',
		'class' => 'mini',
		'type' => 'text');
	
	if ( $options_categories ) {
	$options[] = array(
		'name' => __('Select Category 3', 'inkzine'),
		'desc' => __('The category from which posts will be fetched.', 'inkzine'),
		'id' => 'cat3',
		'type' => 'select',
		'class' => 'mini',		
		'options' => $options_categories);
	}
		
	$options[] = array(
		'name' => __('Select No of Posts', 'inkzine'),
		'desc' => __('The no. of posts you want to display For Category 3.', 'inkzine'),
		'id' => 'cat3_count',
		'std' => '6',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $carousel_count);			


	//Slider
	//Slider Settings
	
	$options[] = array(
		'name' => __('Slider', 'inkzine'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Enable Slider', 'inkzine'),
		'desc' => __('Enable Slider on HomePage', 'inkzine'),
		'id' => 'slider_enabled',
		'type' => 'checkbox',
		'std' => '0' );	
		
	$options[] = array(
		'desc' => __('Enable Slider on Static Front Page', 'inkzine'),
		'id' => 'slider_enabled_front',
		'type' => 'checkbox',
		'std' => '0' );	
		
		
	$options[] = array(
		'name' => __('Using the Slider', 'inkzine'),
		'desc' => __('This Slider supports upto 3 Images. To show only 2 Slides in the slider, upload only 3 images. Leave the rest Blank. For best results, upload images of size 1180x500px.', 'inkzine'),
		'type' => 'info');

	$options[] = array(
		'name' => __('Slider Image 1', 'inkzine'),
		'desc' => __('First Slide', 'inkzine'),
		'id' => 'slide1',
		'class' => '',
		'type' => 'upload');
	
	$options[] = array(
		'desc' => __('Title', 'inkzine'),
		'id' => 'slidetitle1',
		'std' => '',
		'type' => 'text');
				
		
	$options[] = array(
		'desc' => __('Url', 'inkzine'),
		'id' => 'slideurl1',
		'std' => '',
		'type' => 'text');		
	
	$options[] = array(
		'name' => __('Slider Image 2', 'inkzine'),
		'desc' => __('Second Slide', 'inkzine'),
		'class' => '',
		'id' => 'slide2',
		'type' => 'upload');
	
	$options[] = array(
		'desc' => __('Title', 'inkzine'),
		'id' => 'slidetitle2',
		'std' => '',
		'type' => 'text');	
		
		
	$options[] = array(
		'desc' => __('Url', 'inkzine'),
		'id' => 'slideurl2',
		'std' => '',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('Slider Image 3', 'inkzine'),
		'desc' => __('Third Slide', 'inkzine'),
		'id' => 'slide3',
		'class' => '',
		'type' => 'upload');	
	
	$options[] = array(
		'desc' => __('Title', 'inkzine'),
		'id' => 'slidetitle3',
		'std' => '',
		'type' => 'text');	
		
			
	$options[] = array(
		'desc' => __('Url', 'inkzine'),
		'id' => 'slideurl3',
		'std' => '',
		'type' => 'text');	

	//Social Settings
	
	$options[] = array(
	'name' => __('Social', 'inkzine'),
	'type' => 'heading');

	$options[] = array(
		'name' => __('Facebook', 'inkzine'),
		'desc' => __('Facebook Profile or Page URL i.e. http://facebook.com/username/ ', 'inkzine'),
		'id' => 'facebook',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');
	
	$options[] = array(
		'name' => __('Twitter', 'inkzine'),
		'desc' => __('Twitter Username', 'inkzine'),
		'id' => 'twitter',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');
	
	$options[] = array(
		'name' => __('Google Plus', 'inkzine'),
		'desc' => __('Google Plus profile url, including "http://"', 'inkzine'),
		'id' => 'google',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Feeburner', 'inkzine'),
		'desc' => __('URL for your RSS Feeds', 'inkzine'),
		'id' => 'feedburner',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('Pinterest', 'inkzine'),
		'desc' => __('Your Pinterest Profile URL', 'inkzine'),
		'id' => 'pinterest',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Instagram', 'inkzine'),
		'desc' => __('Your Instagram Profile URL', 'inkzine'),
		'id' => 'instagram',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('Linked In', 'inkzine'),
		'desc' => __('Your Linked In Profile URL', 'inkzine'),
		'id' => 'linkedin',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('Youtube', 'inkzine'),
		'desc' => __('Your Youtube Channel URL', 'inkzine'),
		'id' => 'youtube',
		'std' => '',
		'class' => 'mini',
		'type' => 'text');		
		
	$options[] = array(
		'desc' => __('Inkzine Plus Has More Social Icons. We can add any custom social icon on request. <a target="_blank" href="http://inkhive.com/product/inkzine-plus/">Read Features and Buy Inkzine Plus.</a> ', 'inkzine'),
		'type' => 'info');								
		
	$options[] = array(
	'name' => __('Support', 'inkzine'),
	'type' => 'heading');
	
	$options[] = array(
		'desc' => __('Inkzine WordPress theme has been Designed and Created by <a href="http://InkHive.com" target="_blank">InkHive.com</a>. For any Queries or help regarding this theme, <a href="http://wordpress.org/support/theme/inkzine/" target="_blank">use the support forums.</a>', 'inkzine'),
		'type' => 'info');	
	
	$options[] = array(
		'desc' => __('A Documentation file has been provided with the theme, for your convenience. <a target="_blank" href="'.get_template_directory_uri().'/Documentation-Inkzine.pdf">Inkzine Theme Documentation.</a> ', 'inkzine'),
		'type' => 'info');	
		
	$options[] = array(
		'desc' => __('Pro Version Comes with Dedicated Support and personal e-mail support. <a target="_blank" href="http://inkhive.com/product/inkzine-plus/">Upgrade to Pro Now.</a> ', 'inkzine'),
		'type' => 'info');	
		
	 $options[] = array(
		'desc' => __('<a href="http://twitter.com/rohitinked" target="_blank">Follow Me on Twitter</a> to know about my upcoming themes.', 'inkzine'),
		'type' => 'info');
		
	$options[] = array(
		'name' => __('Live Demo Blog', 'inkzine'),
		'desc' => __('For your convenience, we have created a <a href="http://demo.inkhive.com/inkzine/" target="_blank">Live Demo Blog</a> of the theme Inkzine. You can take a look at and find out how your site would look once complete.', 'inkzine'),
		'type' => 'info');			
	
	$options[] = array(
		'name' => __('Regenerating Post Thumbnails', 'inkzine'),
		'desc' => __('If you are using inkzine Theme on a New Wordpress Installation, then you can skip this section.<br />But if you have just switched to this theme from some other theme, then you are requested regenerate all the post thumbnails. It will fix all the issues you are facing with distorted & ugly homepage thumbnail Images. ', 'inkzine'),
		'type' => 'info');	
		
	$options[] = array(
		'desc' => __('To Regenerate all Thumbnail images, Install and Activate the <a href="http://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerate Thumbnails</a> WP Plugin. Then from <strong>Tools &gt; Regen. Thumbnails</strong>, re-create thumbnails for all your existing images. And your blog will look even more stylish with Inkzine theme.<br /> ', 'inkzine'),
		'type' => 'info');	
		
			
	$options[] = array(
		'desc' => __('<strong>Note:</strong> Regenerating the thumbnails, will not affect your original images. It will just generate a separate image file for those images.', 'inkzine'),
		'type' => 'info');	
		
	
	$options[] = array(
		'name' => __('Theme Credits', 'inkzine'),
		'desc' => __('Check this if you want to you do not want to give us credit in your site footer.', 'inkzine'),
		'id' => 'credit1',
		'std' => '0',
		'type' => 'checkbox');

		//CAROUSEL SETTINGS
	$options[] = array(
		'name' => __('Carousel', 'inkzine'),
		'type' => 'heading');	

	$options[] = array(
		'desc' => __('Inkzine Plus supports a full fledged Carousel and Slider with plenty of customization settings including speed, transition effects, etc.<a target="_blank" href="http://inkhive.com/product/inkzine-plus/">Upgrade to Pro Now.</a> ', 'inkzine'),
		'type' => 'info');		
	
	

	return $options;
}