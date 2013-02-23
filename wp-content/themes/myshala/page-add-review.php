<?php

	$file_proper = false;
	$MESSAGE = '';
	/** process form here */
	//check for file upload
	if(!empty($_FILES['bookreview_file'])) {
		
		$file   = $_FILES['bookreview_file'];
		if(strlen($file['name']) > 0)
		{
			//include helper files
			include_once(ABSPATH. 'wp-admin/includes/media.php');
			include_once(ABSPATH. 'wp-admin/includes/file.php');
			include_once(ABSPATH. 'wp-admin/includes/image.php');
				
			$upload = wp_handle_upload($file, array('test_form' => false));

			if(!isset($upload['error']) && isset($upload['file'])) {
				$file_proper = true;
			}
			else {
				$MESSAGE = 'Failed to upload file';
			}
		}
	}

	if(!empty($_POST['bookreview_title'])):
		//check if all fields are filled
		$bookreview_title = $_POST['bookreview_title'];
		$bookreview_description = $_POST['bookreview_desc'];
		
		$bookreview_category = $_POST['bookreview_category'];
		$bookreview_tags = $_POST['bookreview_tags'];

		$category_string = '';
		$cat_ids = array();
		if(count($bookreview_category) > 0)
		{
			for ($i=0 ; $i<count($bookreview_category) ; $i++)
			{
				$category_string = $category_string . $bookreview_category[$i].', ';
				 $term = get_term_by('name',$bookreview_category[$i],'bookreview_category');
				$cat_ids[] = $term->term_id; 
			}
		}
		// now we have all data. lets add a new review
		$post_data = array(
				'post_title' =>  wp_strip_all_tags($bookreview_title),
				'post_content' =>$bookreview_description,
				'post_status' => 'publish',
				'post_author' => bp_loggedin_user_id(),
				'post_type' => 'bookreview');

		//add post
		$post_id = wp_insert_post($post_data);
		//set post categories
		wp_set_post_terms($post_id,$cat_ids,'bookreview_category');
		
		wp_set_post_terms( $post_id,$bookreview_tags,'bookreview_tags');
		
		if($post_id == 0)
		{
			$MESSAGE = 'Sorry! Could not add review. Please try again';
		}
		else{
			if($file_proper)
			{
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
				
			//set tags
			wp_set_post_terms( $post_id,$bookreview_tags);
			
			$MESSAGE = 'Successfully added! <b><a href="'.  get_permalink( $post_id) .'">View</a></b>';
			
			//add this to site-wide activity
			$userlink = bp_core_get_userlink( bp_loggedin_user_id() );
			
			bp_activity_add( array(
				'user_id' 	=> bp_loggedin_user_id(),
				'item_id'	=> $post_id,
				'action' 	=> $userlink . ' added a new review',
				'content' 	=> apply_filters('agc_activity_new_classified_content','<a style="display:inline" href="'.  get_permalink( $post_id) .'">'. $bookreview_title . '</a>',$post_id),
				'component' => 'profile',
				'type' => 'new_bookreview'
			 ));
			
			//finaly. lets view it
			bp_core_redirect(get_permalink( $post_id));	
		}
	endif;

?>

<?php get_header(); ?>

<div id="content" class="block-6 no-mar content-with-sidebar">
	<div class="padder block-6 bg-color-main">
		<div class="block-inner">
				<?php if(is_user_logged_in()): ?>
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
						
						<form action="" method="post" id="bookreview_post"	enctype="multipart/form-data" class="contact-form">
							
								<div class="line">
									<label for="bookreview_title" class="control-label">Title</label> 
									
										<input type="text" id="bookreview_title" name="bookreview_title" value="" class="input-xlarge"/>
									
								</div>
								
								<div class="line">
									<label for="bookreview_file" class="control-label">Upload file</label>
									
										<input type="file" name="bookreview_file" id="bookreview_file" class="input-xlarge"/>
										
									
								</div>
								
								<div class="line">
									<label for="bookreview_category" class="control-label">Category</label>
									
									<?php  
										$args = array ('taxonomy'=> 'bookreview_category','hide_empty' => false);
										$categories= get_categories($args);
										foreach($categories as $category){?>
											<label class="checkbox">
											  <input type="checkbox" id="bookreview_category<?php echo $category->cat_ID; ?>" name="bookreview_category[]" value="<?php echo $category->cat_name;?>" />
											  <?php echo $category->cat_name;?>
											</label>
									<?php }?>
									
								</div>
								<div class="line">
									<label for="bookreview_tags" class="control-label">Tags</label>
									
										<input type="text" name="bookreview_tags" id="bookreview_tags" value="" class="input-xlarge"/>
										<p class="help-block meta">Separate each tag with a comma "," (ex: google,internet,computer)</p>
									
								</div>
								
								<div class="line">
									<label for="classified_desc" class="control-label">Review</label>
									
											  <textarea name="bookreview_desc" class="input-xlarge" rows="5"> </textarea>
										
										<p class="help-block meta">Enter your Review here. (required)</p>
									
								</div>
								<input type="hidden" name="create_bookreview" value="yes" /> 
								<div class="line">
									
										<input id="bookreview_submit_button" type="submit" class="button size-small" name="submit" value="Upload and Save" />
									
								</div>
							
						</form>
						<script>
							jQuery('#bookreview_post').validate({
									errorClass: 'bookreview_error',
									rules: {
										bookreview_title: "required",
										bookreview_desc:"required",
										bookreview_file:{
											accept: "jpg|jpeg|gif|png|bmp"
										}	      
									},
									messages: {
										
									}
								});
							</script>
					<?php
					else: ?>
						<div class="">
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
							<div class="post-content">
								<div class="wrap">
									<h4>You are not logged in!</h4>
									<p>You need to login or register to add a Review.</p>
									<p><a class="btn quick-register cboxElement" href="#"><span>Create An Account ?</span></a> 
									<span class="sign-in">Or sign-in to your account</span></p>
								</div>
							</div>
						</div>
					<?php endif; ?>
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