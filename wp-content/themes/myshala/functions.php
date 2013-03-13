<?php

define('OM_THEME_PREFIX', 'om_metro_');
define('OM_THEME_SHORT_PREFIX', 'om_');
define('OM_THEME_NAME', 'metro');
define('TEMPLATE_DIR_URI', get_template_directory_uri());

/*************************************************************************************
 *	Translation Text Domain
 *************************************************************************************/

load_theme_textdomain('om_theme');

/*************************************************************************************
 *	Register WP3.0+ Menu
 *************************************************************************************/
 
if( !function_exists( 'om_register_menu' ) ) {
	function om_register_menu() {
	  register_nav_menu('primary-menu', __('Primary Menu', 'om_theme'));
	  //register_nav_menu('reserved-menu', __('Reserved Menu (can be used in widget)', 'om_theme'));
	}

	add_action('init', 'om_register_menu');
}

/*************************************************************************************
 *	Set Max Content Width
 *************************************************************************************/
 
if ( ! isset( $content_width ) ) $content_width = 940;

/*************************************************************************************
 *	Post Formats
 *************************************************************************************/
 
add_theme_support( 'post-formats', array(
		'audio',
		'gallery', 
		'image', 
		'link', 
		'quote', 
		'video'
)); 
	
/*************************************************************************************
 *	Post Thumbnails
 *************************************************************************************/

if( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 144, 144, true );
	add_image_size( 'thumbnail-post-big', 456, 300, true); // for blogroll
	add_image_size( 'portfolio-thumb', 480, 328, true); // for portfolio
	add_image_size( 'portfolio-q-thumb', 480, 480, true); // for portfolio
	add_image_size( 'page-full', 900, '', false); 
	add_image_size( 'page-full-2', 924, '', false); 
}

/*************************************************************************************
 *	Automatic Feed Links
 *************************************************************************************/

function om_feedburner_hook() {
	echo '<link rel="alternate" type="application/rss+xml" title="'. get_bloginfo( 'name' ) .' RSS Feed" href="'. get_option(OM_THEME_PREFIX.'feedburner') .'" />';
}

if (get_option(OM_THEME_PREFIX.'feedburner')) {
	add_action('wp_head','om_feedburner_hook');
} else {
	add_theme_support( 'automatic-feed-links' );
}

/*************************************************************************************
 *	Excerpt Length
 *************************************************************************************/

if( !function_exists( 'om_excerpt_length' ) ) {
	function om_excerpt_length($length) {
		return 10; 
	}
	add_filter('excerpt_length', 'om_excerpt_length');
}

if( !function_exists( 'om_excerpt_more' ) ) {
	function om_excerpt_more( $more ) {
		global $post;
		return ' <a href="'. get_permalink($post->ID) . '" style="font-weight:bold">'.__('Read more', 'om_theme').'</a>';
	}
	add_filter('excerpt_more', 'om_excerpt_more');
}

function om_custom_excerpt_more($excerpt, $return=false) {
	global $post;
	
	$more=' <a href="'. get_permalink($post->ID) . '" style="font-weight:bold">'.__('Read more', 'om_theme').'</a>';
	
	if( ($pos=strrpos($excerpt, '</p>')) === false)
		$excerpt = $excerpt.$more;
	else
		$excerpt = substr($excerpt,0,$pos).$more.substr($excerpt,$pos);
	
	if($return)
		return $excerpt;
	else
		echo $excerpt;
}

/*************************************************************************************
 *	Remove Read More Jump
 *************************************************************************************/

function om_remove_more_jump_link($link) {
	$offset = strpos($link, '#more-');
	if ($offset !== false) {
		$end = strpos($link, '"',$offset);
		$link = substr_replace($link, '', $offset, $end-$offset);
	}

	return $link;
}
add_filter('the_content_more_link', 'om_remove_more_jump_link');

/*************************************************************************************
 *	Register Sidebars
 *************************************************************************************/

if( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => __('Main Sidebar','om_theme'),
		'before_widget' => '<div class="block-3 bg-color-sidebar"><div class="block-inner widgets-area">',
		'after_widget' => '</div></div>',
		'before_title' => '<div class="widget-header">',
		'after_title' => '</div>',
	));
	register_sidebar(array(
		'name' => __('Footer Left Column','om_theme'),
		'id' => 'footer-column-left',
		'before_widget' => '',
		'after_widget' => '<div class="clear"></div>',
		'before_title' => '<div class="widget-header">',
		'after_title' => '</div>',
	));
	register_sidebar(array(
		'name' => __('Footer Center Column','om_theme'),
		'id' => 'footer-column-center',
		'before_widget' => '',
		'after_widget' => '<div class="clear"></div>',
		'before_title' => '<div class="widget-header">',
		'after_title' => '</div>',
	));
	register_sidebar(array(
		'name' => __('Footer Right Column','om_theme'),
		'id' => 'footer-column-right',
		'before_widget' => '',
		'after_widget' => '<div class="clear"></div>',
		'before_title' => '<div class="widget-header">',
		'after_title' => '</div>',
	));
	register_sidebar(array(
		'name' => __('Footer Right ','om_theme'),
		'id' => 'footer-right',
		'before_widget' => '',
		'after_widget' => '<div class="clear"></div>',
		'before_title' => '<div class="widget-header">',
		'after_title' => '</div>',
	));

	$sidebars_num=intval(get_option(OM_THEME_PREFIX."sidebars_num"));
	for($i=1;$i<=$sidebars_num;$i++)
	{
		register_sidebar(array(
			'name' => __('Main Alternative Sidebar','om_theme').' '.$i,
			'id' => 'alt-sidebar-'.$i,
			'before_widget' => '<div class="block-3 bg-color-sidebar"><div class="block-inner widgets-area">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="widget-header">',
			'after_title' => '</div>',
		));	
	}
	
}

/*************************************************************************************
 *	Widgets
 *************************************************************************************/

// Latest Tweets
include_once("widgets/tweets/tweets.php");

// Flickr
include_once("widgets/flickr/flickr.php");

// Video
include_once("widgets/video/video.php");

// Recent Posts
include_once("widgets/recent-posts/recent-posts.php");

// Recent Portfolio
include_once("widgets/recent-portfolio/recent-portfolio.php");

// No-margins
include_once("widgets/no-margins/no-margins.php");

// Facebook
include_once("widgets/facebook/facebook.php");

// Testimonials
include_once("widgets/testimonials/testimonials.php");

// Apply Shortcodes for Widgets
add_filter('widget_text', 'do_shortcode');

//Include Custom Ajency Theme Widgets
require_once(TEMPLATEPATH . '/includes/widgets/widgets_inc.php');

require_once(TEMPLATEPATH . '/includes/bp_activity.php');

require_once( TEMPLATEPATH . '/includes/bp-includes/ajax.php' );

/*************************************************************************************
 *	Front-end JS/CSS
 *************************************************************************************/
 
if(!function_exists('om_enqueue_scripts')) {
	function om_enqueue_scripts() {

		wp_register_script('isotope', TEMPLATE_DIR_URI.'/js/jquery.isotope.min.js', array('jquery'), false, true);
		wp_register_script('jPlayer', TEMPLATE_DIR_URI.'/js/jquery.jplayer.min.js', array('jquery'), false, true);
		wp_register_script('prettyPhoto', TEMPLATE_DIR_URI.'/js/jquery.prettyPhoto.js', array('jquery'), false, true);
		wp_register_script('omSlider', TEMPLATE_DIR_URI.'/js/jquery.omslider.min.js', array('jquery'), false, true);
		wp_register_script('image-picker', TEMPLATE_DIR_URI.'/js/image-picker.js', array('jquery'), false, true);
		wp_register_script('masonry', TEMPLATE_DIR_URI.'/js/jquery.masonry.min.js', array('jquery'), false, true);
		wp_register_script('libraries', TEMPLATE_DIR_URI.'/js/libraries.js', array('jquery'), false, true);
		wp_register_script('validate', TEMPLATE_DIR_URI.'/js/jquery.validate.min.js', array('jquery'), false, true);
		wp_register_script('form', TEMPLATE_DIR_URI.'/js/jquery.form.min.js', array('jquery'), false, true);
		wp_register_script('om_custom', TEMPLATE_DIR_URI.'/js/custom.js', array('jquery','omSlider','libraries'), false, true);
		
		// Enqueue Scripts for login-steps page
		if(is_page_template('template-login-steps.php'))
		{
			wp_enqueue_script('agc-progress-bubbles-js', TEMPLATE_DIR_URI.'/js/jquery-progress-bubbles.js',array(),'',true);
			wp_enqueue_script('agc-date-picker-js', TEMPLATE_DIR_URI.'/js/bootstrap-datepicker.js',array(),'',true);
			wp_enqueue_script('agc-jquery-jcrop-js', TEMPLATE_DIR_URI.('/js/jquery.Jcrop.js' ),array(),'',true);	
		}
		wp_enqueue_script('bp-global-js',get_stylesheet_directory_uri().'/includes/bp-includes/global.js',array(),'',true);
		// Enqueue - No conditions as for use on all pages
		wp_enqueue_script('jquery');
		wp_enqueue_script('jPlayer');
		wp_enqueue_script('prettyPhoto');
		wp_enqueue_script('omSlider');
		wp_enqueue_script('image-picker');
		wp_enqueue_script('masonry');
		wp_enqueue_script('libraries');
		wp_enqueue_script('isotope');
		wp_enqueue_script('validate');
		wp_enqueue_script('form');
		wp_enqueue_script('om_custom');
		
		// styles
		wp_register_style('prettyPhoto', TEMPLATE_DIR_URI.'/css/prettyPhoto.css');
		wp_enqueue_style('prettyPhoto');
		wp_register_style('image-picker', TEMPLATE_DIR_URI.'/css/image-picker.css');
		wp_enqueue_style('image-picker');
	
  }

	add_action('wp_enqueue_scripts', 'om_enqueue_scripts');
}

/*************************************************************************************
 *	More Functions
 *************************************************************************************/

require_once (TEMPLATEPATH . '/functions/misc.php');
require_once (TEMPLATEPATH . '/functions/breadcrumbs.php');
require_once (TEMPLATEPATH . '/functions/common-meta.php');
require_once (TEMPLATEPATH . '/functions/homepage.php');
require_once (TEMPLATEPATH . '/functions/homepage-meta.php');
require_once (TEMPLATEPATH . '/functions/comments.php');
require_once (TEMPLATEPATH . '/functions/page-meta.php');
require_once (TEMPLATEPATH . '/functions/post-meta.php');
require_once (TEMPLATEPATH . '/functions/portfolio.php');
require_once (TEMPLATEPATH . '/functions/portfolio-meta.php');
require_once (TEMPLATEPATH . '/functions/shortcodes.php');
require_once (TEMPLATEPATH . '/functions/testimonials.php');
require_once (TEMPLATEPATH . '/functions/testimonials-meta.php');
require_once (TEMPLATEPATH . '/functions/galleries.php');
require_once (TEMPLATEPATH . '/functions/galleries-meta.php');
require_once (TEMPLATEPATH . '/functions/contact-form.php');
require_once (TEMPLATEPATH . '/functions/facebook-comments.php');
require_once (TEMPLATEPATH . '/functions/myshala_remote_db.php');

/*************************************************************************************
 *	TinyMCE Shortcodes button
 *************************************************************************************/

require_once (TEMPLATEPATH . '/tinymce/tinymce.php');


/*************************************************************************************
 *	Theme Options
 *************************************************************************************/

require_once (TEMPLATEPATH . '/admin/admin-functions.php');
require_once (TEMPLATEPATH . '/admin/admin-interface.php');
require_once (TEMPLATEPATH . '/functions/theme-options.php');

/*************************************************************************************
 *	Custom Login Logo
 *************************************************************************************/

function om_custom_login_logo() {
	echo '<style type="text/css">h1 a { background-image:url('.TEMPLATE_DIR_URI.'/img/custom-logo-login.png) !important; }</style>';
}
add_action('login_head', 'om_custom_login_logo');

function om_login_headerurl() {
	return home_url();
}
add_filter('login_headerurl', 'om_login_headerurl');

function om_login_headertitle() {
	return get_option('blogname');
}
add_filter('login_headertitle', 'om_login_headertitle');


/*************************************************************************************
 *	Sidebar Sliding
 *************************************************************************************/

function om_sliding_sidebar() {

	echo '<script>jQuery(document).ready(function(){sidebar_slide_init();});</script>';
	
}
 
if(get_option(OM_THEME_PREFIX."sidebar_sliding") == 'true') {
	
	add_action('wp_head', 'om_sliding_sidebar');
	
}

/*************************************************************************************
 *	Custom Events Plugin
 *************************************************************************************/
require_once(get_stylesheet_directory() . '/events/agc-events.php');

/*************************************************************************************
 *	Communications Module
 *************************************************************************************/
require_once(get_stylesheet_directory() . '/communications/agc_com_module.php');

/*************************************************************************************
 *	Book Reviews Function
 *************************************************************************************/
 
 /* Register Post Type */
 add_action( 'init', 'create_bookreview_post_type' );
function create_bookreview_post_type() {
		$labels = array(
			'name' => _x('Book Reviews', 'post type general name'),
			'singular_name' => _x('Book Review', 'post type singular name'),
			'add_new' => _x('Add New', 'bookreview'),
			'add_new_item' => __('Add New Book Review'),
			'edit_item' => __('Edit Book Review'),
			'new_item' => __('New Book Review'),
			'all_items' => __('All Book Reviews'),
			'view_item' => __('Read Book Review'),
			'search_items' => __('Search Book Reviews'),
			'not_found' =>  __('No Book Reviews found'),
			'not_found_in_trash' => __('No Book Reviews found in Trash'),
			'parent_item_colon' => '',
			'menu_name' => __('Book Reviews')
	
	);
	$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' =>false,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
	);
	
	register_post_type('bookreview',$args);
	
	register_taxonomy(
			'bookreview_category',
			'bookreview',
			array(
					'hierarchical' => true,
					'label' => 'Book Review Categories',
					'query_var' => true,
					'rewrite' =>false
			));
	register_taxonomy(
			'bookreview_tags',
			'bookreview',
			array(
					'hierarchical' => false,
					'label' => 'Book Review Tags',
					'query_var' => true,
					'rewrite' =>false
			));
}

add_action('init', 'demo_add_default_boxes');


function demo_add_default_boxes() {
	register_taxonomy_for_object_type('bookreview_category', 'bookreview');
	register_taxonomy_for_object_type('bookreview_tags', 'bookreview');
}

/* Shortcode to Display Book Reviews Page */
function show_bookreview_view($attr)
{
	/**
	 * get the view page
	 * 
	 */
	$view_page = $attr['view_page'];
	$bookreview_post;
		
	if($view_page == 'HOME')
	{
		//create link
		$link = get_bloginfo('url') . '/book-reviews/';
		echo '<div id="reviews-box" class="reviews-box-class">
				<h3 class="widget-title"><a title="View all Book Reviews" href="'. $link .'">Book Reviews</a></h3>
				<ul class="clearfix">';
		//get recent 3 bookreview post
		$bookreview_post = new WP_Query('post_type=bookreview&posts_per_page=3');
	}
	elseif($view_page == 'BOOK_REVIEWS')
	{
		//create link
		$link = get_bloginfo('url') . '/add-review';
		echo '<a title="Add Book Review" href="'. $link .'" class="button add-review-btn">Add Book Review</a>';
		echo '<div id="reviews-box" class="reviews-box-class">
				<ul class="clearfix">';
		//get all book review posts
		
		$bookreview_post = new WP_Query(array('post_type' => 'bookreview','posts_per_page' => 6,'paged' => get_query_var('paged')));
	}
	elseif($view_page == 'MY_REVIEWS')
	{
		//create link
		global $user_ID;  
		$link = get_bloginfo('url') . '/add-review';
		if(bp_displayed_user_id()== $user_ID)
		{
			echo '<a title="Add Book Review" href="'. $link .'" class="button size-mini">Add Book Review</a>';
		}
		echo '<div id="reviews-box" class="reviews-box-class">
				<ul class="clearfix">';
		//get all reviews by author
		$bookreview_post = new WP_Query('post_type=bookreview&author='. bp_displayed_user_id() . '&posts_per_page=-1');
	}
	
	//var_dump($bookreview_post);
	
	if($bookreview_post->have_posts())
	{
		while($bookreview_post->have_posts()): 
			
			$bookreview_post->the_post(); 
				
			//create proper date format
 			$time = strtotime(get_the_date());
 			$date = date('d M y',$time);
 			 			
 			//strip post content if extra
 			$post_content = (strlen(get_the_content()) > 200) ? substr(get_the_content(),0,197) . '...' : get_the_content();
 			//get_post($post->ID);
 			
			//trim post title
 			$post_title = (strlen(get_the_title()) > 25) ? substr(get_the_title(),0,21) . '&raquo;' : get_the_title();
 			
		?>
		<li>
			<div class="review-thumb">
			<?php 
				if(has_post_thumbnail(get_the_ID())):
					echo get_the_post_thumbnail(get_the_ID(),array(193,193)); 
				else:
					echo '<img src="'. get_template_directory_uri() . '/img/nobookimage.png" width="193" height="193" alt="' . get_the_title() . '"/>';	
				endif;	
			 ?>
			<span class="date"><?php echo $date; ?></span>
			</div>
			<h4><a title="<?php echo get_the_title(); ?>" href="<?php echo get_permalink( get_the_ID() ); ?>"> 
				<?php //echo $post_title; ?><?php echo get_the_title(); ?>
			</a></h4>
			<div class="review-cats">
				<?php 
					$categories = get_the_terms(get_the_ID(), 'bookreview_category' ); 
					if($categories): 
				?>
				Posted in
				<?php foreach($categories as $category ):?>
					<a href="<?php echo get_term_link($category, 'bookreview_category' ); ?>" class="box_tag"><?php echo $category->name; ?></a>&nbsp;
			 	<?php endforeach; endif; ?>
			</div>
			<div class="review-meta">
				Reviewed by <a href="<?php echo bp_core_get_user_domain(get_the_author_ID()); ?>"><?php echo bp_core_get_user_displayname(get_the_author_ID());  ?></a>
			</div>
			<div class="review-desc">
				<?php echo $post_content;?>
				<a href="<?php echo get_permalink( get_the_ID() ); ?>" class="">Read More</a>
			</div>
			<div class="review-bar clear">
				<div class="review-actions">
					<?php if($view_page == 'MY_REVIEWS' && is_user_logged_in() && (get_the_author_ID() == bp_displayed_user_id())): ?>
						<div class="edit-options">
							 <a href="<?php echo get_bloginfo('url') . '/edit-review/?id=' . get_the_ID()?>" class="edit-classified box_tag button size-mini">Edit</a>
							 <span class="delete_review box_tag button size-mini" review_id="<?php echo get_the_ID();?>">Delete</span>
						</div> 
					<?php else: endif; ?>
				</div>
			</div>
		</li>
				
		<?php 
		endwhile; 
		
		//close the div
		echo '</ul></div><div class="clearfix clear"></div>';
		
		//handle delete operation
		if($view_page == 'MY_REVIEWS'):
		?>
				<script>
				jQuery('.delete_review').click(function(){
					var c = confirm("Are you sure you want to delete this review?");
		
					if(c == true)
					{
						jQuery(this).html('Deleting. . .');
						var _this = this;	
						var data = {
							action: 'delete_review',
							review_id : jQuery(this).attr('review_id') 
						};
					
						// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
						jQuery.post(ajaxurl, data, function(response) {
							if(response == 'DELETED')
							{
								jQuery(_this).parent().parent().fadeOut();	
							}					
						});
					}	
				});	
				</script>
		
		<?php 
		endif;
		
		//if main reviews page. add pagination 
		if($view_page == 'BOOK_REVIEWS'	) :
		?>
				
		<?php
			$nav_prev = get_previous_posts_link(__('Newer Reviews', 'om_theme'), 20);
			$nav_next = get_next_posts_link(__('Older Reviews', 'om_theme'), 20);
			if( $nav_prev || $nav_next ) {
				?>
				<div class="navigation-prev-next">
					<?php if($nav_prev){?><div class="navigation-prev"><?php echo $nav_prev; ?></div><?php } ?>
					<?php if($nav_next){?><div class="navigation-next"><?php echo $nav_next; ?></div><?php } ?>
					<div class="clear"></div>
				</div>
				<?php
			}		
		?>
		<?php 
		endif;
	}
	else
	{
		if($view_page == 'HOME')
		{
			echo '</ul></div>';
			echo '<div class="no-classifieds"><div class="alert alert-info">No reviews found... you can <a href="'. get_bloginfo('url') .'/add-review/" class="button size-mini">Add a New Book Review</a></div></div>';
		}
		else
		{
			echo '</ul></div><div class="clearfix clear"></div>';
			echo '<div class="alert alert-info">No reviews found</div>';
		}	
					
	}
}

add_shortcode('show_bookreview_view', 'show_bookreview_view');

/* Delete a Review */
function delete_bookreview()
{
	
	//get the ID
	$bookreview_id = $_POST['bookreview_id'];
	//its was easy :)
	$result = wp_delete_post($bookreview_id);
	//$result = true;
	
	if(!$result)
		echo '0';
	else 
		echo '1';
	
	die(); //!important

}

add_action('wp_ajax_delete_bookreview', 'delete_bookreview');

/* Book Review Icon */
function review_icons() {
?>
<style type="text/css" media="screen">
#menu-posts-bookreview .wp-menu-image {
	background: url(<?php bloginfo('template_url') ?>/img/bookreview-icon.png) no-repeat 6px -32px !important;
}
#menu-posts-bookreview:hover .wp-menu-image, #menu-posts-bookreview.wp-has-current-submenu .wp-menu-image {
	background-position:6px 0px !important;
}
#icon-edit.icon32-posts-bookreview {background: url(<?php bloginfo('template_url') ?>/img/bookreview-32x32.png) no-repeat;}
</style>

<?php }
add_action( 'admin_head', 'review_icons' );

/* Add Book Review Profile Tab */
function my_bp_nav_adder()
{
    bp_core_new_nav_item(
        array(
            'name' => __('Book Reviews', 'buddypress'),
            'slug' => 'book-reviews',
            'position' => 90,
            'show_for_displayed_user' => true,
            'screen_function' => 'bookreview_tab',
            'item_css_id' => 'all-conversations'
        ));
        print_r($wp_filter); 
}
function bookreview_tab () {
    //add title and content here - last is to call the members plugin.php template
    add_action( 'bp_template_title', 'bookreview_tab_title' );
    add_action( 'bp_template_content', 'bookreview_tab_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function bookreview_tab_title() {
    echo 'My Book Reviews';
}
function bookreview_tab_content() { 
	echo do_shortcode('[show_bookreview_view view_page=MY_REVIEWS]');
}
add_action( 'bp_setup_nav', 'my_bp_nav_adder' );

/*************************************************************************************
 *	Login Steps Functions
 *************************************************************************************/
function agc_login_steps_redirect_loggedin()
{
	if(is_page_template('template-login-steps.php'))
	{
		if(!is_user_logged_in())
		{
			wp_redirect(bp_get_signup_page());
			exit;
		}
		//Prevent user from going back to login steps after completion once.
		elseif(is_user_logged_in() && (agc_login_steps_getset_step() === 'completed'))
		{
			wp_redirect(get_bloginfo('url')."/millennium-community/");
			exit;
		}			
	}
}
add_action('template_redirect', 'agc_login_steps_redirect_loggedin');

function agc_login_steps_css()
{
	echo '<link href="'.TEMPLATE_DIR_URI.('/css/jquery.Jcrop.css' ).'" rel="stylesheet" type="text/css" />';
}
add_action('wp_head', 'agc_login_steps_css');

/**
 * Function to save the login step number for user
 */
function agc_login_steps_getset_step($step_number = 0,$set = false)
{
	$current_step = get_user_meta(bp_loggedin_user_id(),'agc_login_steps_completed',true);
	$current_step = ($current_step)?$current_step:0;
	
	if($set)
	{
		update_user_meta(bp_loggedin_user_id(),'agc_login_steps_completed',$step_number,$current_step);
		
		do_action('agc_after_login_step_saved',$step_number,$current_step);
		
		return;
	}
	return $current_step;
}
/**
 * Function to redirect the user to login-steps page if the steps have not been completed.
 * @param string $redirect_to
 * @param string $url_redirect_to
 * @param object $user
 * @return string 
 */
function agc_login_steps_redirect($redirect_to, $url_redirect_to, $user)
{
	$login_steps  = get_user_meta($user->ID,'agc_login_steps_completed',true);

	if($login_steps && $login_steps == 'completed')
	{
		$main_site = home_url()."/millennium-community/";
		return $main_site;
	}
	else
	{
		$main_site = home_url();
		return $main_site . "/login-steps/";
	}
}
add_filter('login_redirect', 'agc_login_steps_redirect', 10, 3);
/**
 * Function to get the profile group ids.
 * @return array: group ids 
 */
function agc_get_xprofile_group_ids()
{
	$groups 	= BP_XProfile_Group::get( array( 'fetch_fields' => true ) );
	$group_ids 	= array();
	for($i=0;$i<count($groups);$i++)
	{
		$group_ids[] = $groups[$i]->id;
	}
	
	return $group_ids;
}

/**
 * Function to save the xprofile field data via ajax.
 */
function agc_ajax_bp_xprofile_save()
{
	$params = array();
	parse_str($_POST['fields'],$params);
	
	$success = array();
	
	$field_ids = explode(',',$params['field_ids']);
	
	foreach($field_ids as $field_id)
	{
		if($params['field_'.$field_id])
			$value = $params['field_'.$field_id];
		else
			$value = null;
		
		if($params['field_'.$field_id.'_date'])
		{
			$value 	= date( 'Y-m-d H:i:s', strtotime( $params['field_'.$field_id.'_date'] ));
		}
		
		$success[$field_id] = xprofile_set_field_data( $field_id, bp_loggedin_user_id(), $value , true );
	}
	$step 	 = $_POST['step'];
	agc_login_steps_getset_step($step,true);
	$response = json_encode( array( 'success' => $success,'parameters' => $params,'date' => $my_date) );
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}
add_action('wp_ajax_agc_ajax_bp_xprofile_save', 'agc_ajax_bp_xprofile_save');


function agc_avatar_upload_dir( $directory = false, $user_id = 0 ) {

	if ( empty( $user_id ) )
		$user_id = bp_loggedin_user_id();

	if ( empty( $directory ) )
		$directory = 'avatars';

	$path    = bp_core_avatar_upload_path() . '/avatars/' . $user_id;
	$newbdir = $path;

	if ( !file_exists( $path ) )
		@wp_mkdir_p( $path );

	$newurl    = bp_core_avatar_url() . '/avatars/' . $user_id;
	$newburl   = $newurl;
	$newsubdir = '/avatars/' . $user_id;

	return apply_filters( 'agc_avatar_upload_dir', array(
			'path'    => $path,
			'url'     => $newurl,
			'subdir'  => $newsubdir,
			'basedir' => $newbdir,
			'baseurl' => $newburl,
			'error'   => false
	) );
}

/**
 * Function to handle the cropping of the avatar via ajax and setting it to current user.
 */
function agc_set_core_avatar_image()
{
	global $bp;
	header( "Content-Type: application/json" );
		
	if (! bp_core_avatar_handle_crop( array( 'avatar_dir' => 'avatars','item_id' => bp_loggedin_user_id() , 'original_file' => $_POST['image_src'], 'crop_x' => $_POST['x'], 'crop_y' => $_POST['y'], 'crop_w' => $_POST['w'], 'crop_h' => $_POST['h'] )))
		$result = array('status' => 'fail', 'msg' => 'There was a problem cropping your avatar, please try uploading it again','upload_path' => $_POST['image_src']);
	else
	{
		$step 	 = $_POST['step'];
		agc_login_steps_getset_step($step,true);
		$result = array('status' => 'success', 'msg' => 'Your new avatar was uploaded successfully!','values' => array('x' => $_POST['x'],'y' => $_POST['y'],'w' => $_POST['w'],'h' => $_POST['h']));
	}
	
	echo json_encode($result);
	die();
}
add_action('wp_ajax_agc_set_core_avatar_image','agc_set_core_avatar_image');

/**
 * Function to change the password via ajax.
 * Dependent on the old password for server level authentication.
 */
function acg_change_password()
{
	global $bp;
	$old_pass 		= $_POST['agc_old_password'];
	$new_pass 		= $_POST['agc_new_password'];
	$re_new_pass 	= $_POST['agc_re_new_password'];
	
	$user = get_user_by('id', bp_loggedin_user_id());
	
	if(is_user_logged_in())
	{
		if ( wp_check_password( $old_pass, $user->data->user_pass, $user->ID)) {
			if($new_pass == $re_new_pass)
			{
				wp_set_password( $new_pass, bp_loggedin_user_id());
				$response = array('success'=> true,'msg' => 'Your password has been successfully changed.');
				$step 	 = $_POST['step'];
				agc_login_steps_getset_step($step,true);
			}
			else
			{
				$response = array('success'=> false,'msg' => 'There seems to be a mismatch between the new password and the re-entered new password.');
			}

		} else {

			$response = array('success'=> false,'msg' => 'Sorry but your password could not be changed. Please try again.');
		}
	}
	else
	{
		$response = array('success'=> false,'msg' => 'Sorry but you need to be logged in to change your password.');
	}
	
	
	
	header( "Content-Type: application/json" );
	echo json_encode($response);
	die();
}
add_action('wp_ajax_acg_change_password','acg_change_password');
add_action('wp_ajax_nopriv_acg_change_password','acg_change_password');

/**
 * Function to modify the output of bp-xprofile radio button template
 * @param string $html
 * @param object $option
 * @param int $field_id
 * @param string $selected
 * @param int $index
 * @return string
 */
function agc_custom_xprofile_radio_button($html, $option, $field_id, $selected, $index )
{
	global $field;
	$field_data = bp_get_profile_field_data( array('field' => $field_id, 'user_id' => bp_loggedin_user_id()));
	$selected = ( $option->name == $field_data )?' checked="checked"':'';
	$required = ($field->is_required)?'required':'';
	return '<label class="radio"><input' . $selected . ' type="radio" class="'.$required.'" name="field_' . $field_id . '" id="option_' . $option->id . '" value="' . esc_attr( stripslashes( $option->name ) ) . '"> ' . esc_attr( stripslashes( $option->name ) ) . '</label>';
}
add_filter('bp_get_the_profile_field_options_radio', 'agc_custom_xprofile_radio_button',10,5);


/**
 * Function to modify the output of bp-xprofile checkbox template
 * @param string $html
 * @param object $option
 * @param int $field_id
 * @param string $selected
 * @param int $index
 * @return string
 */
function agc_custom_xprofile_checkbox_button($html, $option, $field_id, $selected, $index )
{
	global $field;
	$field_data = bp_get_profile_field_data( array('field' => $field_id, 'user_id' => bp_loggedin_user_id()));
	$selected = ( $option->name == $field_data )?' checked="checked"':'';
	$required = ($field->is_required)?'required':'';
	return '<label class="checkbox"><input' . $selected . ' type="checkbox" class="'.$required.'" name="field_' . $field_id . '[]" id="field_' . $option->id . '_' . $index . '" value="' . esc_attr( stripslashes( $option->name ) ) . '"> ' . esc_attr( stripslashes( $option->name ) ) . '</label>';
}
add_filter('bp_get_the_profile_field_options_checkbox', 'agc_custom_xprofile_checkbox_button',10,5);

/**
 * Function to modify the output of bp-xprofile select template
 * @param string $html
 * @param object $option
 * @param int $field_id
 * @param string $selected
 * @param int $index
 * @return string
 */
function agc_custom_xprofile_select_button($html, $option, $field_id, $selected, $index )
{
	$field_data = bp_get_profile_field_data( array('field' => $field_id, 'user_id' => bp_loggedin_user_id()));
	$selected = ( $option->name == $field_data )?' selected="selected"':'';
	
	return  '<option' . $selected . ' value="' . esc_attr( stripslashes( $option->name ) ) . '">' . esc_attr( stripslashes( $option->name ) ) . '</option>';
}
add_filter('bp_get_the_profile_field_options_select', 'agc_custom_xprofile_select_button',10,5);

/**
 * Function to modify the output of bp-xprofile multi select template
 * @param string $html
 * @param object $option
 * @param int $field_id
 * @param string $selected
 * @param int $index
 * @return string
 */
function agc_custom_xprofile_multi_select_button($html, $option, $field_id, $selected, $index )
{
	$field_data = bp_get_profile_field_data( array('field' => $field_id, 'user_id' => bp_loggedin_user_id()));
	$selected = ( $option->name == $field_data )?' selected="selected"':'';
	
	return  '<option' . $selected . ' value="' . esc_attr( stripslashes( $option->name ) ) . '">' . esc_attr( stripslashes( $option->name ) ) . '</option>';
}
add_filter('bp_get_the_profile_field_options_multiselect', 'agc_custom_xprofile_multi_select_button',10,5);


////////////////////////////////////////SCRIPTS VIA PHP////////////////////////////////////////////////

/**
 * Function to print out the script for numbered bubbles
 * at the top of the login in steps template.
 */
function agc_login_steps_bubbles_script()
{
	global $agc_login_step;
	if(is_page_template('template-login-steps.php')):?>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#bubbles').progressBubbles( {
			 bubbles : [
						 {'title' : 'Step 1'},
							
							<?php for($i = 2 ;$i < $agc_login_step; $i++ ) :?>
	
							{'title' : 'Step <?php echo $i;?>'},
								
							<?php endfor;?>
	
							{'title' : 'Step <?php echo $agc_login_step;?>'},	
						]
					}
				);
				jQuery('.prev-step').on('click', function(event){
					jQuery('#bubbles').progressBubbles('regress');
				});
			});
	</script>
<?php 
	endif;
}
add_action('wp_footer','agc_login_steps_bubbles_script');

/**
 * Function to print out the ajax script for changing the password.
 */
function agc_login_steps_pass_change_script()
{
	if(is_page_template('template-login-steps.php')):?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#agcChangePassword').click(function(){
					jQuery('#loading-acgChangePassword').show();
					var data = { 
									agc_new_password 	: jQuery('#agc_new_password').val(),
									agc_old_password 	: jQuery('#agc_old_password').val(),
									agc_re_new_password : jQuery('#agc_re_new_password').val(),
									action 				: 'acg_change_password',
									step				: 'completed', 
								};
					jQuery.post(ajaxurl,data,function(response){
						console.log(response);
						if(response.success == true)
						{
							//jQuery('#loading-acgChangePassword').hide();
							var msg_html = '<div class="alert alert-block alert-success fade in" style="display:none">'+
			            						'<h4 class="alert-heading">Success!</h4>'+
			            						'<p>'+ response.msg +'</p>'+
			            						'<p>Please wait while you are being redirected to the login page.</p>'+
			           						'</div>';
			           		jQuery('div.alert').remove();
							jQuery('div.agcPasswordError').append(msg_html);
							jQuery('div.alert').fadeIn('fast');
							window.location.href = '<?php echo site_url().'/login/';?>';
						}
						else
						{
							jQuery('#loading-acgChangePassword').hide();
							var msg_html = '<div class="alert alert-block alert-error fade in" style="display:none">'+
			            						'<h4 class="alert-heading">Oh snap! You got an error!</h4>'+
			            						'<p>'+ response.msg +'</p>'+
			           						'</div>';
			           		jQuery('div.alert').remove();
							jQuery('div.agcPasswordError').append(msg_html);
							jQuery('div.alert').fadeIn('fast');
						}
						});
				});
			});
	</script>
	<?php
	endif;	
}
add_action('wp_footer','agc_login_steps_pass_change_script');


function agc_login_steps_goto_step()
{
	if(is_page_template('template-login-steps.php')):
	$completed_steps = agc_login_steps_getset_step();
	?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		<?php for($i = 1; $i <= $completed_steps ; $i++):?>
			jQuery('#bubbles').progressBubbles('progress');
			jQuery('#loading-step-<?php echo $i;?>').hide();
			jQuery('div#step-<?php echo $i?>').hide();	
		<?php endfor;?>
		jQuery('div#step-<?php echo $completed_steps+1;?>').show();	
	});
	</script>
	<?php
	endif;
}
add_action('wp_footer','agc_login_steps_goto_step');

/*************************************************************************************
 *	Photo Select Tab Function
 *************************************************************************************/
function my_photos_bp_nav()
{
    bp_core_new_nav_item(
        array(
            'name' => __('My Photos', 'buddypress'),
            'slug' => 'my-photos',
            'position' => 90,
            'show_for_displayed_user' => true,
            'screen_function' => 'my_photos_tab',
            'item_css_id' => 'all-conversations'
        ));
}
function my_photos_tab () {
    //add title and content here - last is to call the members plugin.php template
    add_action( 'bp_template_title', 'my_photos_tab_title' );
    add_action( 'bp_template_content', 'my_photos_tab_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function my_photos_tab_title() {
    echo 'My Photos';
}
function my_photos_tab_content() { 
	
	if (wp_verify_nonce($_POST['save-my-photos'],'action-save-my-photos')  )
	{
		$msh_image_picker = (!isset($_POST['msh_image_picker']) || empty($_POST['msh_image_picker'])) ? array():$_POST['msh_image_picker'];
		 
		update_user_meta(bp_displayed_user_id(),'photos_picked',$msh_image_picker);
	}
	global $wpdb;
	$refid = get_user_meta(bp_displayed_user_id(),'msh_remote_refid',true);
	if($refid)
	{	
		$get_gathering_images = array(
				'function' => "getGatheringImages",
				'refno' => $refid
		);
		//$remote_query = $wpdb->prepare("select phataksir_events_attachments.* from phataksirkundalievent INNER JOIN phataksir_events_attachments where mgmtgroupid REGEXP  '(^|,)".$refid."($|,)' and   eventid =  phataksirkundalievent.id");
	 
	 	//$remote_query = $wpdb->prepare("SELECT * FROM `phataksir_events_attachments`");
	 	
		
		$result = fetch_from_local_db($get_gathering_images);
		 $selected_photos = array();
		 
		 $selected_photos = get_user_meta(bp_displayed_user_id(),'photos_picked');
		 
		 $selected_photos = empty($selected_photos) ? array() : $selected_photos[0];
	 
		 
		 if (in_array("No rows found",$result) && count($result)==1)
		 {
		 	echo "No Photos Found!";
		 }
		 else
		 {
		 	
		 	if(bp_loggedin_user_id() ==bp_displayed_user_id())
		 	{
				echo '<p>Please select photos by clicking on them, then click on choose selected below.</p>';
		 	}
				echo '<form class="image-select-form" action="" method="post">';
				echo '<select multiple="multiple" class="image-picker show-labels show-html" name="msh_image_picker[]">';
			
				if($result)
				{
		 				foreach ($result as $resultdata) { 
							$show_selected = "";
							if(in_array($resultdata->absolutepath, $selected_photos))
							{
								$show_selected = "selected";
							}
		 					 echo '<option data-img-src="'.$resultdata->absolutepath.'" value="'.$resultdata->absolutepath.'" '.$show_selected.'>'.$resultdata->description.'</option>';
							}
						 
						 
				}
				echo '</select>';
				if(bp_loggedin_user_id() ==bp_displayed_user_id())
				{
				echo '<div class="display-select-info">You have selected: <span></span></div>';
				
				echo '<input type="submit" class="" value="Choose Selected" />';
				wp_nonce_field('action-save-my-photos','save-my-photos');
				}
				echo '</form>';
				echo '<script>jQuery(document).ready(function(){jQuery("select.image-picker").imagepicker({show_label : true});});</script>';
				echo '<script>jQuery(document).ready(function(){var $container = jQuery(".image_picker_selector");
						$container.imagesLoaded( function(){
						  $container.masonry({
							itemSelector : "li"
						  });
						});});</script>';
				echo '<script>
				jQuery(document).ready(function(){
			        // This selector is called every time a select box is changed
			        jQuery("select.image-picker").change(function(){
			            // variable to hold string
			            var sel = "";
			            jQuery("select.image-picker option:selected").each(function(){
			                // when the select box is changed, we add the value text to the varible
			                sel += jQuery(this).html() + ",";
			            });
			            // then display it in the following class	
			            jQuery(".display-select-info span").html(sel);
			        });
			        });
			    </script>';
				?>
				<script type="text/javascript">
				jQuery(document).ready(function(){
						jQuery('.msh-photo-select-submit').click(function(e){
								e.preventDefault(); //dont submit the form untill confirmed
								var check = confirm('Are you sure you want to select these photos?');
								if(check == true)
								{	
									jQuery('.image-select-form').submit();
								} 
							});
					});
				</script>
				<?php
		}
	}
}
add_action( 'bp_setup_nav', 'my_photos_bp_nav' );


//function to get data from remote server
function fetch_from_local_db($data) {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://121.243.24.194/apps/PhatakSirNew/PhatakSirDataServices-debug/Interface/interface.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$output = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	$return_value = unserialize($output);
	return $return_value;
		
}

add_filter( 'show_admin_bar', '__return_false' );

function msh_display_avatar($user_id=0)
{ 
	$refid = get_user_meta($user_id,'msh_remote_refid',true);

	if($refid != "")
	{
		$str = '<img src=" http://content.rudiment.s3.amazonaws.com/apps/ID_Photos_2012_13/'.$refid.'.JPG" class="avatar gal-rounded_cr"> ';
		echo $str;
	}
	else
	{
		bp_displayed_user_avatar( 'type=full' );

	}
	
	
}
