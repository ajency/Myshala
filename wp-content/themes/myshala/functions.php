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
		
		// Enqueue the global JS - Ajax will not work without it
		wp_enqueue_script( 'dtheme-ajax-js', get_template_directory_uri() . '/_inc/global.js', array( 'jquery' ), bp_get_version() );
		
		// Add words that we need to use in JS to the end of the page so they can be translated and still used.
		$params = array(
				'my_favs'           => __( 'My Favorites', 'buddypress' ),
				'accepted'          => __( 'Accepted', 'buddypress' ),
				'rejected'          => __( 'Rejected', 'buddypress' ),
				'show_all_comments' => __( 'Show all comments for this thread', 'buddypress' ),
				'show_all'          => __( 'Show all', 'buddypress' ),
				'comments'          => __( 'comments', 'buddypress' ),
				'close'             => __( 'Close', 'buddypress' ),
				'view'              => __( 'View', 'buddypress' ),
				'mark_as_fav'	    => __( 'Favorite', 'buddypress' ),
				'remove_fav'	    => __( 'Remove Favorite', 'buddypress' )
		);
		wp_localize_script( 'dtheme-ajax-js', 'BP_DTheme', $params );
		
		// Maybe enqueue comment reply JS
		if ( is_singular() && bp_is_blog_page() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

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
			echo '<a title="Add Book Review" href="'. $link .'" class="button " style="color:#fff">Add Book Review</a>';
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
		<span class="date"><?php echo $date; ?> </span>
	</div>
	<h4>
		<a title="<?php echo get_the_title(); ?>"
			href="<?php echo get_permalink( get_the_ID() ); ?>"> <?php //echo $post_title; ?>
			<?php echo get_the_title(); ?>
		</a>
	</h4>
	<div class="review-cats">
		<?php 
		$categories = get_the_terms(get_the_ID(), 'bookreview_category' );
		if($categories):
		?>
		Posted in
		<?php foreach($categories as $category ):?>
		<a
			href="<?php echo get_term_link($category, 'bookreview_category' ); ?>"
			class="box_tag"><?php echo $category->name; ?> </a>&nbsp;
		<?php endforeach; endif; ?>
	</div>
	<div class="review-meta">
		Reviewed by <a
			href="<?php echo bp_core_get_user_domain(get_the_author_ID()); ?>"><?php echo bp_core_get_user_displayname(get_the_author_ID());  ?>
		</a>
	</div>
	<div class="review-desc">
		<?php echo $post_content;?>
		<a href="<?php echo get_permalink( get_the_ID() ); ?>" class="">Read
			More</a>
	</div>
	<div class="review-bar clear">
		<div class="review-actions">
			<?php if($view_page == 'MY_REVIEWS' && is_user_logged_in() && (get_the_author_ID() == bp_displayed_user_id())): ?>
			<div class="edit-options">
				<a
					href="<?php echo get_bloginfo('url') . '/edit-review/?id=' . get_the_ID()?>"
					class="edit-classified box_tag button size-mini">Edit</a> <span
					class="delete_review box_tag button size-mini"
					review_id="<?php echo get_the_ID();?>">Delete</span>
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
	<?php if($nav_prev){?>
	<div class="navigation-prev">
		<?php echo $nav_prev; ?>
	</div>
	<?php } ?>
	<?php if($nav_next){?>
	<div class="navigation-next">
		<?php echo $nav_next; ?>
	</div>
	<?php } ?>
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
	background: url(<?php bloginfo('template_url')?>/img/bookreview-icon.png)
		no-repeat 6px -32px !important;
}

#menu-posts-bookreview:hover .wp-menu-image,#menu-posts-bookreview.wp-has-current-submenu .wp-menu-image
	{
	background-position: 6px 0px !important;
}

#icon-edit.icon32-posts-bookreview {
	background: url(<?php bloginfo('template_url')?>/img/bookreview-32x32.png)
		no-repeat;
}
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
//add_action( 'bp_setup_nav', 'my_bp_nav_adder' ); // comment/uncomment to add tab to profile

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

	if ( bp_displayed_user_id()== bp_loggedin_user_id() || is_site_admin())
	
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
							$show_selected = array();

							$image_name= "";
							$image_path = explode("/",$resultdata->path);
							if (count($image_path) > 0)
							{
								$image_name = $image_path[count($image_path)-1];
							}
							if(in_array($resultdata->path, $selected_photos))
							{
								$show_selected = "selected";
								$selected_images[]=$image_name;
							}
							echo '<option data-img-src="'.$resultdata->path.'" value="'.$resultdata->path.'" '.$show_selected.'>'.$image_name.'</option>';
							}
						 
						 
				}
				echo '</select>'; 
				if(count($selected_images) !=0 )
				{
				 $selected_images = implode("<br>",$selected_images);
				}
				else
				{
					$selected_images ="";
				}
				echo '<div class="display-select-info">You have selected: <span><br>'.$selected_images.'</span></div>';
				
				echo '<input type="submit" class="" value="Update" id="image-update"/>';
				wp_nonce_field('action-save-my-photos','save-my-photos');
			 
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
						jQuery("#image-update").show();
			            // variable to hold string
			            var sel = "";
			            jQuery("select.image-picker option:selected").each(function(){
			                // when the select box is changed, we add the value text to the varible
			                sel += jQuery(this).html() + "<br>";
			            });
			            // then display it in the following class	
			            jQuery(".display-select-info span").html("<br>"+sel);
			        });
			        });
			    </script>';
				?>
				<script type="text/javascript">
 

		 
				jQuery(document).ready(function(){
					jQuery('#image-update').hide();
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




/*************************************************************************************
 *	DVD Tab Function
*************************************************************************************/
function my_dvd_bp_nav()
{
	if ( bp_displayed_user_id()== bp_loggedin_user_id() || is_site_admin())
	
	{
	bp_core_new_nav_item(
			array(
					'name' => __('My DVDs', 'buddypress'),
					'slug' => 'my-dvd',
					'position' => 90,
					'show_for_displayed_user' => true,
					'screen_function' => 'my_dvd_tab',
					'item_css_id' => 'all-conversations'
			));
	}
}
function my_dvd_tab () {
	//add title and content here - last is to call the members plugin.php template
	add_action( 'bp_template_title', 'my_dvd_tab_title' );
	add_action( 'bp_template_content', 'my_dvd_tab_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function my_dvd_tab_title() {
	echo 'My DVDs';
}
function my_dvd_tab_content() {

	$upload_dir = wp_upload_dir();
	$dvdpath = $upload_dir['baseurl']."/dvd/";
	
	if (wp_verify_nonce($_POST['save-my-dvd'],'action-save-my-dvd')  )
	{
		$msh_dvd_picker = (!isset($_POST['msh_dvd_picker']) || empty($_POST['msh_dvd_picker'])) ? array():$_POST['msh_dvd_picker'];
			//var_dump($msh_dvd_picker);
		update_user_meta(bp_displayed_user_id(),'dvd_picked',$msh_dvd_picker);
	}
	global $wpdb;
	$refid = get_user_meta(bp_displayed_user_id(),'msh_remote_refid',true);
	if($refid)
	{
		 

		$selected_dvds = array();
			
		$selected_dvds = get_user_meta(bp_displayed_user_id(),'dvd_picked');
		
			
		$selected_dvds = empty($selected_dvds) ? array() : $selected_dvds[0];

			
		
		function get_dvd_content($item)
		{
			$str = "";
			switch ($item) {
				case "dvd1":
					$content_array = array(
					0 => ' N3 |2101 | Lakadi ki kathi | A tribute to the ever green tak bak song',
					1 => 'N1 |2102 |  Barbie Girl | Children move to the super hit song by Aqua',
					2 => ' S3 |2103 | Barfi | A cute dance performance',
					3 => 'S7 | 2104 | Bhumro | A Kashmiri folk dance by our tiny tots',
					4 => ' J6 | 2105 |Zoobi doobi | A fun dance to a Bollywood hit',
					5 => ' 2 | 2106 |Yoga | For physical fitness and peace of mind',
					6 => '4A, 4B |2107 |  Honesty pays | A play showing the value of Honesty ',
					7 => '1A, 1B | 2108 | Dhoom Tana | A superb collage across different eras ',
					8 => ' 2A, 2B | 2109 |I am the Best | Watch them assert their goodness!',
					9 => '2A, 2B | 2110 | Dinus Bill | A skit about the importance of values',
					10 => ' 2C, 2D | 2111 |Le gai le gai | Our tribute to the late  Yash Chopra',
					11 => ' 4C, 4D | 2112 |Oh Haseena | Dance away as old is indeed, gold!',
					);
					
					break;
					
				case "dvd2":
					$content_array = array(
					0 => 'N7 | 2201 | Gore Nal | A Punjabi folk dance',
					1 => ' N2 |2202 | Rangeela Re | Swaying to the foot tapping number',
					2 => 'J1 |2203 |  Gore gore | Dance away as old is indeed, gold!',
					3 => 'J2 |2204 |  Aika dajiba | A Marathi super hit dance',
					4 => 'S1 |2205 |  Jai ho | Swinging to the Oscar winning song',
					5 => '1A, 1B |2206 |  Kids in the garden | Drama showing how  life is beautiful',
					6 => '1A, 1B |2207 |  Dhadak Dhadak | Energy, power and force packed dance',
					7 => '3A, 3B |2208 |  My Bat | Drama showing self learning is the best',
					8 => '3A, 3B |2209 |  Ayo re maro Dholana | Children dance to the tune of the desert',
					6 => '6A, 6B |2210 |  Barso re | A rain dance',
					7 => '8A, 8B |2211 |  Jogan jogan | A rajasthani folk dance',
					8 => '9B |2212 |  Kahe ched ched mohe | A classical dance',
					9 => '9A, 9B |2213 |  Disco Diwane | Seniors boogey away to a popular hit',
					);
						
					break;

			
				case "dvd3":
					$content_array = array(
					0 => 'N1 | 2301 | Barbie girl | Children move to the super hit song by Aqua',
					1 => ' J4 |2302 | Zoobi doobi | A fun dance to a Bollywood hit',
					2 => 'S8 |2303 |  Sha la la | Energetic dance to the popular Vengaboys tune',
					3 => '3A, 3B |2304 |  My Bat | Drama showing self learning is the best',
					4 => '4A, 4B |2305 |  Barso re | A dance to the tune of a popular rain song',
					5 => '4A, 4B |2306 |  Honesty pays | A play showing the value of Honesty',
					6 => '5A, 5B |2307 |  Bachana ae Hasino | A dance to a medley of old tunes',
					7 => '6C | 2308 | Lallati Bhandar | A gondhal, a marathi folk dance ',
					8 => ' 7A, 7B | 2309 |Aaja nachale | Dancing to the beat of the dancing queen',
					9 => '5 to 9 | 2310 | Orchestra | Be dazzled by a breathtaking ensemble',
					
					
					);
			
					break;	
					
					
					case "dvd4":
						$content_array = array(
						
						0 => ' 5 to 9 | 2311 |Gata Shivajichi | A one hour Marathi play showcasing the life and times of Shivaji Maharaj',
							
						);
							
						break;
					
					case "dvd5":
					$content_array = array(
					0 => 'N6 | 3101 | Rangeela Re |Swaying to the foot tapping number',
					1 => ' J4 |3102 | Zoobi doobi | A fun dance to a Bollywood hit',
					2 => 'S4 |3103 |  Bhumro | A Kashmiri folk dance by our tiny tots',
					3 => 'S6, 3B |3104 |  Jai ho | Swinging to the Oscar winning song',
					4 => '1C, 1D |3105 |  Dhadak Dhadak | Energy, power and force packed dance',
					5 => '3C, 3D |3106 |  Ayo re maro Dholana | Children dance to the tune of the desert',
					6 => '3A, 3B |3107 |  Koi yaha waha naache | Disco Dancing',
					7 => '3C, 3D | 3108 | My Bat | Drama showing self learning is the best ',
					8 => ' 5C, 5D | 3109 |Jambhul | A typical Marathi folk dance',
					9 => '3 | 3110 | Gymnastics | Sports demo',
					10 => ' 8A, 8B | 3111 |Jogan jogan | A traditional rajasthani folk dance',
					
					);
			
					break;	
						
					
					case "dvd6":
						$content_array = array(
						0 => 'N2 | 3201 | Rangeela Re |Swaying to the foot tapping number',
						1 => ' N7 |3202 | Gul Nal Ishq Mitha | A north Indian folk dance',
						2 => 'J7 |3203 |  Aika dajiba | A Marathi super hit dance',
						3 => 'J8 |3204 |  Chanda chamke | A twisting dance to a popular number',
						4 => 'S1 |3205 |  Jai ho | Energy, Swinging to the Oscar winning song',
						5 => 'S5 |3206 |  Barfi | A cute dance performance',
						6 => '1A, 1B |3207 | My Bat | Drama showing how  life is beautiful',
						7 => '4C, 4D | 3208 | Oh Haseena | Dance away as old is indeed, gold!',
						8 => ' 5C, 5D | 3209 |Koli Medley | The Marathi coastal culture in a dance',
						9 => '4D | 3210 | Honesty pays | A play showing the value of Honesty',
						10 => ' 6A, 6B | 3211 |Barso re | A rain dance',
						11 => ' 4 | 3212 |Rope Gymnastics | Our girls stretch and flip in mid air!',
							
						);
							
						break;
						
						
						case "dvd7":
							$content_array = array(
							0 => 'N3 | 3301 | Lakadi ki kathi |Swaying to the foot tapping number',
							1 => ' S2 |3302 | Sha la la | A north Indian folk dance',
							2 => 'J7 |3303 |  Aika dajiba | A Marathi super hit dance',
							3 => '2 |3304 |  Karate Demo | A twisting dance to a popular number',
							4 => '1A, 1B |3305 |  Koi Mil Gaya | Energy, Swinging to the Oscar winning song',
							5 => '1C, 1D |3306 |  Kids in the garden | A cute dance performance',
							6 => '2A, 2B |3307 | Aaj kal tere mere | Drama showing how  life is beautiful',
							7 => '7A, 7B | 3308 | Aaja Nachle | Dance away as old is indeed, gold!',
							8 => ' 9A, 9B | 3309 |The Mob | The Marathi coastal culture in a dance',
							9 => '11A | 3310 | Fraud gurus & blind faith  | A play showing the value of Honesty',
							10 => ' 5 to 9 | 3311 |Orchestra | A rain dance',
							11 => ' 5 to 9 | 3312 |Gata Shivajichi | Our girls stretch and flip in mid air!',
								
							);
								
							break;
							
							case "dvd8":
								$content_array = array(							
								0 => ' 5 to 9 | 3312 |Gata Shivajichi | Our girls stretch and flip in mid air!',					
								);
							
								break;
								
								case "dvd9":
									$content_array = array(
									0 => 'N4 | 4101 | Barbie girl |Children move to the super hit song by Aqua',
									1 => ' N6 |4102 | Rangeela Re | Swaying to the foot tapping number',
									2 => 'J8 |4103 |  Chanda chamke| A twisting dance to a popular number',
									3 => 'S3 |4104 |  Barfi | A cute dance performance',
									4 => 'S7 |4105 |  Bhumro| A Kashmiri folk dance by our tiny tots',
									5 => 'J6 |4106 |  Zoobi doobi | A fun dance to a Bollywood hit',
									6 => '2A, 2B |4107 | Aaj kal tere mere | A tribute to Shammi Kapoor',
									7 => '3C, 3D | 4108 | Koi yaha waha naache | Disco Dancing',
									8 => ' 5C, 5D | 4109 |Koli Medley| The Marathi coastal culture in a dance',
									9 => '5A, 5B | 4110 | Bachana ae Hasino  | A dance to a medley of old tunes',
									10 => ' 6C | 4111 |Lallati Bhandar | A gondhal, a marathi folk dance',
									11 => ' 7A, 7B | 4112 |Dola re dola | A hypnotizing dance to a melodious tune',
								
									);
								
									break;
									
									
									
									case "dvd10":
										$content_array =
										array(
										0 => 'N8 | 4201 | Gul Nal Ishq Mitha |A north Indian folk dance',
										1 => 'J5 | 4202 | Chanda chamke | A twisting dance to a popular number',
										2 => 'N4 | 4203 | Barbie girl | Children move to the super hit by Aqua',
										3 => 'J3 | 4204 | Gore gore | Dance away as old is indeed, gold!',
										4 => 'S4 | 4205 | Bhumro |A dance to free you from all your stress.Kashmiri folk dance by our tiny tots',
										5 => ' S8 | 4206 | Sha la la | Energetic dance to the Vengaboys',
										6 => '2C, 2D | 4207 |Dinus Bill |A skit about the importance of values',
										7 => '4C, 4D | 4208 | Oh Haseena | A tribute to Shammi Kapoor',
										8 => '4A, 4B | 4209 | Honesty pays |A play showing the value of Honesty',
										9 => '4| 4210 | Skating Demo | A fusion of folk and bollywood',
										10 => '3A, 3B | 4211 | My Bat |Drama showing self learning is the best',
										11 => '9A, 9B | 4212 | Gangnam Style | Groovy steps to a groovy tune',
									
									
										);
										
										break;
										
										
										case "dvd11":
											$content_array =
											array(
											0 => 'J1 | 4301 | Gore gore |Dance away as old is indeed, gold!',
											1 => 'S5 | 4302 | Barfi | A cute dance performance',
											2 => '1C, 1D | 4303 | Kids in the garden | Drama showing how life is beautiful',
											3 => '1C, 1D | 4304 | Koi Mil Gaya | Kids do an endearing dance',
											4 => '5 | 4305 | All is well |A dance to free you from all your stress.',
											5 => '3C, 3D | 4306 | My Bat | Drama showing self learning is the best',
											6 => '1 | 4307 |Karate Demo |Kids take on super human challenges',
											7 => '7A, 7B | 4308 | Dola re dola | A hypnotizing dance to a melodious tune',
											8 => '9A, 9B | 4309 | Turn up the music |Dancing to a medley of songs',
											9 => '9B| 4310 | Bollywood Lavani | A fusion of folk and bollywood'
										
										
											);
											
											break;
										
										case "dvd12":
											$content_array = 
											array(
											 0 => '5 to 9 | 4311 | Ghatha Shivajichi |A one hour play showcasing the life and times of Shivaji Maharaj'
											
											);
																						
 											 
 											 break;
 											 
 											 
 											 case "dvd13":
 											 	$content_array =
 											 	array(
 											 
 											 	0 => 'N8| 5101 | Gul Nal Ishq Mitha| A north Indian folk dance',
 											 	1 => 'N5|5102 | Lakadi ki kathi|A tribute to the ever green tak bak song',
 											 	2 => 'J3|5103|Gore gore|Dance away as old is indeed, gold!',
 											 	3 => 'J2|5104|Aika dajiba|A Marathi super hit dance',
 											 	4 => 'S6|5105|Jai ho|Swinging to the Oscar winning song',
 											 	5 => '1C, 1D|5106|Dhoom Tana|A superb collage across different eras',
 											 	6 => '2C, 2D|5107|Aaj kal tere mere|A tribute to Shammi Kapoor',
 											 	7 => '2A, 2B|5108|Dinus Bill|A skit about the importance of values',
 											 	8 => '4A, 4B|5109|Oh Haseena|Dance away as old is indeed, gold!',
 											 	9 => '5C|5110|Takshak|A high energy group dance',
 											 	10 => '5|5111|All is well|A dance to free you from all your stress.',
 											 	11 => '5C, 5D|5112|Jambhul|A typical Marathi folk dance'
 											 
 											 	);
 											 	
 											 	break;
 											 	
 											 	
 											 	case "dvd14":
 											 		$content_array =
 											 		array(							 	
 											 	
 											 		0 => 'N5 | 5201 | Lakadi ki kathi | A tribute to the ever green tak bak song',											 	
 											 		1 => 'J5 | 5202 | Chanda chamke | A twisting dance to a popular number', 											 	
 											 		2 => 'S2 | 5203 | Sha la la | Energetic dance to the Vengaboys tune', 											 	
 											 		3 => '2C, 2D | 5204 | Dinus Bill | A skit about the importance of values', 											 	
 											 		4 => '4A, 4B | 5205 | Oh Haseena | Dance away as old is indeed, gold!', 											 	
 											 		5 => '4C, 4D | 5206 | Honesty pays | A play showing the value of Honesty', 											 	
 											 		6 => '3A, 3B | 5207 | Ayo re maro Dholana | Children dance to the desert tune', 											 	
 											 		7 => '7C | 5208 | Bachana ae Hasino | A dance to a medley of old tunes', 											 	
 											 		8 => '4 | 5209 | Mallakhamb | Our boys defy gravity and reason!', 											 	
 											 		);
 											 		
 											 		
 											 		break;
 											 		
 											 		case "dvd15":
 											 			$content_array =
 											 			array(
 											 			0 => '1 | 5301 | Yoga | For physical fitness and peace of mind',
 											 			1 => '2C, 2D | 5302 | Aaj kal tere mere | A tribute to Shammi Kapoor',
 											 			2 => '3C, 3D | 5303 | Ayo re maro Dholana | Children dance to the desert tune',
 											 			3 => '3C, 3D | 5304 | My Bat | Drama showing self learning is the best',
 											 			4 => '4C, 4D | 5305 | Barso re | A rain dance to a melodious tune',
 											 			5 => '5A, 5B | 5306 | Bachana ae Hasino | A dance to a medley of old tunes',
 											 			6 => '9B | 5307 | Break Dance | Watch the boys go all out!'
 											 			);
 											 			
 											 			
 											 			break;
 											 			
 											 			
 											 			case "dvd16":
 											 				$content_array =
 											 				array(
 											 			
 											 				0 => '5 to 9 | 5308 | Gatha Shivajicha | A one hour play showcasing the life and times of Shivaji Maharaj'
 											 				);
 											 			
 											 				break;
 											 				
 	 
 	 
			}
			
			if($content_array)
			{
				
			$str .= '<table class="table table-bordered table-striped">';
			$str .= "<thead><tr>";
			$str .= "<th>Class</th>";
			$str .= "<th>Code</th>";
			$str .= "<th>Program</th>";
			$str .= "<th></th>";
			$str .= "</tr></thead>";
			
			
				foreach ($content_array as $key => $value) {
					$str .= "<tbody><tr>";
					$content_value = explode("|",$value);
					$str .= "<td>".$content_value[0]."</td>";
					$str .= "<td>".$content_value[1]."</td>";
					$str .= "<td>".$content_value[2]."</td>";
					$str .= "<td>".$content_value[3]."</td>";
					$str .= "</tr></tbody>";
				}
			
			$str .= "</table>";
			}else{
				$str = '<h2>No Playlist Found</h2>';
			}
		
			echo $str;
		}
		
		
			
			
			
			
		
				

			echo '<p>Please select DVD by clicking on them, then click on choose selected below.</p>';
			echo '<form class="image-select-form" action="" method="post">';
			echo '<select multiple="multiple" class="dvd-picker show-labels show-html" name="msh_dvd_picker[]">';

				
			$show_selected = "";
			$selected_dvds_disp = array();
				if(in_array($dvdpath.'1.jpg', $selected_dvds))
				{
					$show_selected1 = "selected";
					$selected_dvds_disp[] = "Prog no 2101 - 2112";
				}
				
				if(in_array($dvdpath.'2.jpg', $selected_dvds))
				{
					$show_selected2 = "selected";
					$selected_dvds_disp[] = "Prog no 2201 - 2113";
				
				}
				if(in_array($dvdpath.'3.jpg', $selected_dvds))
				{
					$show_selected3 = "selected";
					$selected_dvds_disp[] = "Prog no 2301 - 2310";
				
				}
				
				if(in_array($dvdpath.'4.jpg', $selected_dvds))
				{
					$show_selected4 = "selected";
					$selected_dvds_disp[] = "Prog no 2311";
					
				
				}
				if(in_array($dvdpath.'5.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 3101 - 3111";
					$show_selected5 = "selected";
				
				}
				if(in_array($dvdpath.'6.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 3201 - 3212";
					$show_selected6 = "selected";
				
				}
				if(in_array($dvdpath.'7.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 3301 - 3311";
					$show_selected7 = "selected";
				
				}
				if(in_array($dvdpath.'8.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 3312 ";
					$show_selected8 = "selected";
				
				}
				
				if(in_array($dvdpath.'9.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 4101 - 4112";
					$show_selected9 = "selected";
				
				}
				if(in_array($dvdpath.'10.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 4201 - 4212";
					$show_selected10 = "selected";
				
				}
				if(in_array($dvdpath.'11.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 4301 - 4310";
					$show_selected11 = "selected";
				
				}
				
				if(in_array($dvdpath.'12.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 4311";
					$show_selected12 = "selected";
				
				}
				
				if(in_array($dvdpath.'13.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 5101 - 5112";
					$show_selected13 = "selected";
				
				}
				
				if(in_array($dvdpath.'14.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 5201 - 5209";
					$show_selected14 = "selected";
				
				}
				
				if(in_array($dvdpath.'15.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 5301 - 5307";
					$show_selected15 = "selected";
				
				}
				
				if(in_array($dvdpath.'16.jpg', $selected_dvds))
				{
					$selected_dvds_disp[] = "Prog no 5308";
					$show_selected16 = "selected";
				
				}
			
				echo '<option id="div1" data-img-src="'.$dvdpath.'1.jpg" value="'.$dvdpath.'1.jpg" '.$show_selected1.'>Prog no 2101 - 2112</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'2.jpg" value="'.$dvdpath.'2.jpg" '.$show_selected2.'>Prog no 2201 - 2113</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'3.jpg" value="'.$dvdpath.'3.jpg" '.$show_selected3.'>Prog no 2301 - 2310</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'4.jpg" value="'.$dvdpath.'4.jpg" '.$show_selected4.'>Prog no 2311</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'5.jpg" value="'.$dvdpath.'5.jpg" '.$show_selected5.'>Prog no 3101 - 3111</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'6.jpg" value="'.$dvdpath.'6.jpg" '.$show_selected6.'>Prog no 3201 - 3212</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'7.jpg" value="'.$dvdpath.'7.jpg" '.$show_selected7.'>Prog no 3301 - 3311</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'8.jpg" value="'.$dvdpath.'8.jpg" '.$show_selected8.'>Prog no 3312</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'9.jpg" value="'.$dvdpath.'9.jpg" '.$show_selected9.'>Prog no 4101 - 4112</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'10.jpg" value="'.$dvdpath.'10.jpg" '.$show_selected10.'>Prog no 4201 - 4212</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'11.jpg" value="'.$dvdpath.'11.jpg" '.$show_selected11.'>Prog no 4301 - 4310</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'12.jpg" value="'.$dvdpath.'12.jpg" '.$show_selected12.'>Prog no 4311</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'13.jpg" value="'.$dvdpath.'13.jpg" '.$show_selected13.'>Prog no 5101 - 5112</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'14.jpg" value="'.$dvdpath.'14.jpg" '.$show_selected14.'>Prog no 5201 - 5209
				</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'15.jpg" value="'.$dvdpath.'15.jpg" '.$show_selected15.'>Prog no 5301 - 5307</option>';
				echo '<option id="div1" data-img-src="'.$dvdpath.'16.jpg" value="'.$dvdpath.'16.jpg" '.$show_selected16.'>Prog no 5308</option>';
			echo '</select>';
			if(count($selected_dvds_disp) !=0 )
			{
				$selected_dvds_disp = implode("<br>",$selected_dvds_disp);
			}
			else
			{
				$selected_dvds_disp ="";
			}
			echo '<div class="display-select-info">You have selected:<br> <span>'.$selected_dvds_disp.'</span></div>';
			echo '<input type="submit" class="" value="Update" id="image-update" />';
			wp_nonce_field('action-save-my-dvd','save-my-dvd');
			echo '</form>';
			echo '<script>jQuery(document).ready(function(){jQuery("select.dvd-picker").imagepicker({show_label : true,show_desc_link: true});});</script>';
			echo '<script>jQuery(document).ready(function(){var $container = jQuery(".dvd_picker_selector");
			$container.imagesLoaded( function(){
			$container.masonry({
			itemSelector : "li"
		});
		});});</script>';
			echo '<script>
			jQuery(document).ready(function(){
			// This selector is called every time a select box is changed
			jQuery("select.dvd-picker").change(function(){
			jQuery("#image-update").show();
			// variable to hold string
			var sel = "";
			jQuery("select.dvd-picker option:selected").each(function(){
			// when the select box is changed, we add the value text to the varible
			sel += jQuery(this).html() + "<br>";
		});
		// then display it in the following class
		jQuery(".display-select-info span").html("<br>"+sel);
		});
		});
		</script>';
			?>
			
<?php //Add loop here to add the dvd contents into and js array
$countDvd = 16;//The count of the dvds.

for($i = 1 ; $i<= $countDvd; $i++):
	echo '<div id="dvdPlaylist-'.$i.'" style="display:none;">';
	echo '<div class="sixteen columns table_res">';
		get_dvd_content('dvd'.$i);
	echo '</div>';
	echo '</div>';
 endfor;
 ?>			
<script type="text/javascript">
 //Function to initialize the popup for the playlist.
function getDvdPlaylist(dvdid,aInstance) {
	aInstance.speedoPopup({htmlContent: jQuery('#dvdPlaylist-'+dvdid).html()});
};
jQuery(document).ready(function(){
	
		jQuery('.ms_advd').live('click',function (){
			console.log(jQuery(this).attr('data-id'));
			getDvdPlaylist(jQuery(this).attr('data-id'),jQuery(this));
		});
					
		jQuery('#image-update').hide();
			jQuery('.msh-dvd-select-submit').click(function(e){
					e.preventDefault(); //dont submit the form untill confirmed
					var check = confirm('Are you sure you want to select these dvds?');
					if(check == true)
					{	
						jQuery('.dvd-select-form').submit();
					} 
				});
						
		});
</script>
<?php
		 
	}
}
add_action( 'bp_setup_nav', 'my_dvd_bp_nav' );

// FUNCTION TO REMOVE FORUMS TAB
function remove_forums_profile_tab() {
	global $bp;
	$bp->bp_nav['forums'] = false;
}

add_action( 'bp_setup_nav', 'remove_forums_profile_tab', 999 );

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
		$str = '<img src=" http://content.rudiment.s3.amazonaws.com/apps/ID_Photos_2012_13/'.$refid.'.jpg" class="avatar gal-rounded_cr"> ';
		echo $str;
	}
	else
	{
		bp_displayed_user_avatar( 'type=full' );

	}


}

function custom_login_logo() { 
	echo '<style type="text/css">
h1 a { background-image: url('.get_bloginfo('template_directory').'/img/myshala-logo-login.png) !important; }
</style>';
}
add_action('login_head', 'custom_login_logo');

