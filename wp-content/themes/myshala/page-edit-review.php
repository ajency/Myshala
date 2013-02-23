<?php
$file_proper = false;
$MESSAGE = '';
$post_id = $_GET['id'];

/** process form here */
//check for file upload
if(isset($_FILES['bookreview_file'])) {
	
	$file   = $_FILES['bookreview_file'];
	if(strlen($file['name']) > 0)
	{
		//include helper files
		include_once(ABSPATH. 'wp-admin/includes/media.php');
		include_once(ABSPATH. 'wp-admin/includes/file.php');
		include_once(ABSPATH. 'wp-admin/includes/image.php');
		
		$upload = wp_handle_upload($file, array('test_form' => false));
		
		if(!isset($upload['error']) && isset($upload['file']))
		{
			$file_proper = true;
			//add attachment
			$attachment = array(
					'post_mime_type' => $upload['type'],
					'post_title' => sanitize_file_name($file['name']),
					'post_content' => '',
					'post_status' => 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
			wp_update_attachment_metadata( $attach_id, $attach_data );
				
			//set post thumbnail
			set_post_thumbnail( $post_id, $attach_id );
		}
		else {
			$MESSAGE = 'Failed to upload file';
		}
	}
	else
	{
		//file is not set, hence no code for file upload
		$file_proper = true;
	}	
}

if($file_proper)
{
	//update the post	
	//check if all fields are filled
	$bookreview_title = $_POST['bookreview_title'];
	$bookreview_description = $_POST['bookreview_desc'];
	
	$bookreview_category = $_POST['bookreview_category'];
	$bookreview_tags = $_POST['bookreview_tags'];

	$category_string = '';
	$cat_ids = array();
	if(count($bookreview_category) > 0)
	{
		for ($i=0 ; $i<count($bookreview_category) ; ++$i)
		{
			$category_string = $category_string . $bookreview_category[$i].', ';
			$term = get_term_by('name',$bookreview_category[$i],'bookreview_category');
			$cat_ids[] = $term->term_id; 
		}
	}
	// now we have all the data. lets edit a classified
	$post_data = array(
			'ID' => $post_id,
			'post_title' =>  wp_strip_all_tags($bookreview_title),
			'post_content' =>$bookreview_description,
			'post_status' => 'publish',
			'post_author' => bp_loggedin_user_id(),
			'post_type' => 'bookreview');

	//add post
	$post_id = wp_update_post($post_data);
	
	//set post categories
	wp_set_post_terms($post_id,$cat_ids,'bookreview_category');
	
	//set tags
	wp_set_post_terms( $post_id,$bookreview_tags,'bookreview_tags');
			
	if($post_id == 0)
	{
		$MESSAGE = 'Sorry! Could not update review. Please try again';
	}
	else{
		
		$userlink = bp_core_get_userlink( bp_loggedin_user_id() );
			
		bp_activity_add( array(
		'user_id' 	=> bp_loggedin_user_id(),
		'item_id'	=> $post_id,
		'action' 	=> $userlink . ' updated a review',
		'content' 	=> apply_filters('agc_activity_updated_classified_content','<a style="display:inline" href="'.  get_permalink( $post_id) .'">'. $bookreview_title . '</a>',$post_id),
		'component' => 'profile',
		'type' 		=> 'new_bookreview'
				));
		
		$MESSAGE = 'Successfully updated! <b><a href="'.  get_permalink( $post_id) .'">View</a></b>';
		//successfully updated. lets view it
		bp_core_redirect(get_permalink( $post_id));
	}		
}



?>

<?php get_header(); ?>

<div id="content" class="block-6 no-mar content-with-sidebar">
	<div class="padder block-6 bg-color-main">
		<div class="block-inner">
			<?php
	          if ( current_user_can( 'edit_post', $post->ID ) )
	      	    edit_post_link( __('edit', 'om_theme'), '<div class="edit-post-link">[', ']</div>' );
	    		?>
					<div class="tbl-bottom">
						<div class="tbl-td">
							<h1 class="page-h1"><?php the_title(); ?></h1>
						</div>
						<?php if(get_option(OM_THEME_PREFIX . 'show_breadcrumbs') == 'true') { ?>
							<div class="tbl-td">
								<?php om_breadcrumbs(get_option(OM_THEME_PREFIX . 'breadcrumbs_caption')) ?>
							</div>
						<?php } ?>
					</div>
					<div class="clear page-h1-divider"></div>
	      		
	          <?php echo get_option(OM_THEME_PREFIX . 'code_after_page_h1'); ?>
			
			<?php 
				echo $MESSAGE;
			?>
			
			<?php 
				//get post details to populate the form
				$post_data = get_post($post_id);
			?>
				<form action="" method="post" id="bookreview_post"	enctype="multipart/form-data" class="contact-form">
					
					<div class="line">
						<label for="bookreview_title" class="control-label">Title</label> 
						
							<input type="text" id="bookreview_title" name="bookreview_title" value="<?php echo $post_data->post_title; ?>" class="input-xlarge"/>
						
					</div>
					
					<div class="line">
						<label for="classified_file" class="control-label">Upload file</label>
						
							<input type="file" name="bookreview_file" id="bookreview_file" class="input-xlarge"/>
							
					</div>
					<div class="line">
						<label for="bookreview_category" class="control-label">Category</label>
						
						<?php  
							//get the categories for post
							$post_categories = get_the_terms($post_id,'bookreview_category');	
							if(!$post_categories)
								$post_categories = array();
							
							$post_categories_ids	= array();
								
							foreach($post_categories as $cat)
							{
								$post_categories_ids[] = $cat->term_id;
							}
							if(!$post_categories)
								$post_categories = array();
							$args = array ('taxonomy'=> 'bookreview_category','hide_empty' => false);
							$categories= get_categories($args);
							foreach($categories as $category){
								$checked = '';
								if(in_array($category->term_id, $post_categories_ids))
										$checked = 'checked=checked';
								?>
								<label class="checkbox">
									<input <?php echo $checked; ?> type="checkbox" id="bookreview_catagory<?php echo $category->cat_ID; ?>" name="bookreview_catagory[]" value="<?php echo $category->cat_name;?>" />
									<?php echo $category->cat_name;?>
								</label>
						<?php }?>
						
					</div>
					<div class="line">
						<label for="bookreview_tags" class="control-label">Tags</label>
						<?php 
							//get all tags
							$tags_array  = wp_get_post_tags($post_id);
							$tags = '';
							if(count($tags_array) > 0)
							{
								foreach($tags_array as $tag)
								{
									$tags .= $tag->name . ',';	
								}
							}
						?>
						
							<input type="text" name="bookreview_tags" id="bookreview_tags" value="<?php echo rtrim($tags,',');?>" class="input-xlarge"/>
							<p class="help-block meta">Separate each tag with a comma ","</p>
						
					</div>
					
					<div class="line">
						<label for="bookreview_desc" class="control-label">Review</label>
						
							
							
								  <textarea name="bookreview_desc" class="input-xlarge" rows="5"><?php echo $post_data->post_content; ?></textarea>
							
						
					</div>
			       	<input type="hidden" name="create_classified" value="yes" /> 
					<div class="line">
						
							<input id="classified_submit_button" type="submit" class="button size-small" name="submit" value="Update" />
						
					</div>
					
				</form>
				<script>
					jQuery('#bookreview_post').validate({
							errorClass: 'bookreview_error',
							rules: {
								bookreview_title: "required",
								bookreview_desc:"required",
								,
								bookreview_category: {
									required: true,
							        minlength: 1
								}/*,
								bookreview_file:{
									required: true,
								    accept: "jpg|jpeg|gif|png|bmp"
								}*/	      
							},
							messages: {
								bookreview_category: "Please select atleast one"
							}
						});
				</script>
		</div>
	
	</div>
</div>
<div class="block-3 no-mar sidebar">
	
	<div id="event-cats" class="block-3 bg-color-sidebar">
		<div class="block-inner widgets-area">
			<div class="widget-header">Book Review Categories</div>
			<ul>
			<?php
			$args = array( 'taxonomy' => 'bookreview_category' );

			$terms = get_terms('bookreview_category', $args);

			$count = count($terms); $i=0;
			if ($count > 0) {
				
				foreach ($terms as $term) {
					$i++;
					$term_list .= '<li><a href="' .get_bloginfo('url'). '/?bookreview_category=' . $term->slug . '" title="' . sprintf(__('View all post filed under %s', 'my_localization_domain'), $term->name) . '">' . $term->name . '</a></li>';
					
				}
				echo $term_list;
			}
			?>
			</ul>
		</div>
	</div><!-- /#event-cats -->
	
	<?php
	// alternative sidebar
	$alt_sidebar=intval(get_post_meta($post->ID, OM_THEME_SHORT_PREFIX.'sidebar', true));
	if($alt_sidebar && $alt_sidebar <= intval(get_option(OM_THEME_PREFIX."sidebars_num")) ) {
		if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'alt-sidebar-'.$alt_sidebar ) ) ;
	} else {
		get_sidebar();
	}
	?>
	
</div><!-- sidebar -->
<div class="clearfix clear anti-mar"></div>
	
<?php get_footer(); ?>