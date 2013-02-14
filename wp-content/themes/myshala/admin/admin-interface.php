<?php

/*************************************************************************************
 *	Options Admin Interface
 *************************************************************************************/
 
function om_options_add_admin() {

    global $query_string;
    
    // Reset Options
    if ( @$_REQUEST['page'] == 'om_options' && @$_REQUEST['om_options_action'] == 'reset') {
			$options_template =  get_option(OM_THEME_PREFIX.'options_template');
			om_reset_options($options_template,'om_options');
			header("Location: admin.php?page=om_options&reset=true");
			die;
    }

		// Export Options
    if ( @$_REQUEST['page'] == 'om_options' && @$_REQUEST['om_options_action'] == 'export') {
    	$dump=om_options_export_dump();
    	header("Content-Type: text/plain");
    	header("Content-Length: ".strlen($dump)."\n\n");
    	header("Content-Disposition: attachment; filename=".OM_THEME_NAME.".options.dat");
			echo $dump;
			die;
    }
    
    // Import Options
    if ( @$_REQUEST['page'] == 'om_options' && @$_REQUEST['om_options_action'] == 'import' ) {
    	if(@$_FILES['import_file']['tmp_name']) {
    		$s=trim(file_get_contents($_FILES['import_file']['tmp_name']));
    		$options=@unserialize($s);
    		if(is_array($options)) {
    			if($options['theme_prefix'] == OM_THEME_PREFIX) {
    				foreach($options['options'] as $k=>$v) {
    					update_option($k, $v);
    				}
    				header("Location: admin.php?page=om_options&import_ok=true");
    				die;
    			}
    		}
    	}
    	header("Location: admin.php?page=om_options&import_error=true");
			die;
    }

		
    $options_page = add_theme_page(__('Theme Options', 'om_theme'), __('Theme Options', 'om_theme'), 'edit_theme_options', 'om_options','om_options_page');
	
	add_action("admin_print_scripts-".$options_page, 'om_load_options_scripts');
	add_action("admin_print_styles-".$options_page,'om_load_options_styles');
} 

add_action('admin_menu', 'om_options_add_admin');

/*************************************************************************************
 *	Options Reset Function
 *************************************************************************************/

function om_reset_options($options,$page = '') {

	$options_template = get_option(OM_THEME_PREFIX.'options_template');
	
	foreach($options_template as $option) {
		if(isset($option['id'])) {
			update_option($option['id'], $option['std']);
		}
	}
}

/*************************************************************************************
 *	Build the Options Page
 *************************************************************************************/

function om_options_page(){
    $options =  get_option(OM_THEME_PREFIX.'options_template');
	?>

	<div class="wrap" id="om-container">
		<div id="om-popup-save" class="om-popup"><div><?php _e('Options Updated', 'om_theme'); ?></div></div>
		<div id="om-popup-reset" class="om-popup"><div><?php _e('Options Reset', 'om_theme'); ?></div></div>
		<div id="om-popup-import-ok" class="om-popup"><div><?php _e('Options Imported', 'om_theme'); ?></div></div>
		<div id="om-popup-import-error" class="om-popup"><div><?php _e('Sorry, there has been an error while import', 'om_theme'); ?></div></div>
		<form action="" enctype="multipart/form-data" id="om-options-form">
			<div id="header">
				<div class="icon-options"></div>
				<div class="logo">
					<h2><?php _e('Theme Options', 'om_theme'); ?></h2>
				</div>
				<div class="clear"></div>
		   </div>
			<?php 
		    $options_html = om_options_generator($options);
			?>
			<div class="save_bar top">
				<img style="display:none" src="<?php echo get_stylesheet_directory_uri(); ?>/admin/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
				<input type="submit" value="<?php _e('Save All Changes','om_theme');?>" class="button-primary" />
			</div>
			<div id="pane">
				<div id="om-options-sections">
					<ul>
						<?php echo $options_html['menu']; ?>
					</ul>
				</div>
				<div id="content">
					<?php echo $options_html['options']; ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="save_bar bottom">
				<img style="display:none" src="<?php echo get_stylesheet_directory_uri(); ?>/admin/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
				<input type="submit" value="<?php _e('Save All Changes','om_theme');?>" class="button-primary" />
			</div>
		</form>
		<form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" method="post" id="om-options-form-reset">
			<input name="reset" type="submit" value="<?php _e('Reset Options','om_theme');?>" class="button submit-button reset-button" onclick="return confirm('Click OK to reset. Any settings will be lost!');" />
			<input type="hidden" name="om_options_action" value="reset" />
		</form>
	</div>
	
	<div class="clear"></div>
	<p><a href="#" onclick="jQuery('#om_options_import_export').slideToggle(200);return false;"><?php _e('(+) Export / Import Options','om_theme'); ?></a></p>
	
	<div id="om_options_import_export" style="display:none;border-left:1px solid #eee;padding-left:20px">
		<b><?php _e('Export:','om_theme'); ?></b>
		<form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" method="post" target="_blank">
			<input type="submit" value="<?php _e('Download Export File','om_theme');?>" class="button" />
			<input type="hidden" name="om_options_action" value="export" />
		</form>
	
		<br />
		<b><?php _e('Import:','om_theme'); ?></b>
		<form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" method="post" enctype="multipart/form-data">
			<?php _e('Choose a file from your computer:','om_theme'); ?>
			<input type="file" name="import_file" size="25" />
			<input type="submit" value="<?php _e('Upload and Import','om_theme');?>" class="button" />
			<input type="hidden" name="om_options_action" value="import" />
		</form>
	</div>

	<div class="clear"></div>
<?php
}

/*************************************************************************************
 *	Load required styles for Options Page
 *************************************************************************************/
 
function om_load_options_styles() {
	wp_enqueue_style('admin-style', TEMPLATE_DIR_URI.'/admin/admin-style.css');
	wp_enqueue_style('color-picker', TEMPLATE_DIR_URI.'/admin/css/colorpicker.css');
}	

/*************************************************************************************
 *	Load required javascripts for Options Page
 *************************************************************************************/
 
function om_load_options_scripts() {

	add_action('admin_head', 'om_admin_head');
	
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('color-picker', TEMPLATE_DIR_URI.'/admin/js/colorpicker.js', array('jquery'));
	wp_enqueue_script('ajaxupload', TEMPLATE_DIR_URI.'/admin/js/ajaxupload.js', array('jquery'));

}

function om_admin_head() {
	?>
 	<script type="text/javascript" language="javascript">
		jQuery(document).ready(function(){
			
			// Overall Functionality
			jQuery('.group').hide();
			
			if(window.location.hash) {
				var $current=jQuery(window.location.hash+'.group');
				if($current.length) {
					jQuery('#om-options-sections li a[href='+window.location.hash+']').parent().addClass('current');
					$current.fadeIn();
				} else {
					jQuery('.group:first').fadeIn();
					jQuery('#om-options-sections li:first').addClass('current');
				}
			}
			else {
				jQuery('.group:first').fadeIn();
				jQuery('#om-options-sections li:first').addClass('current');
			}
			
			jQuery('#om-options-sections li a').click(function(evt){
			
				jQuery('#om-options-sections li').removeClass('current');
				jQuery(this).parent().addClass('current');
				
				var clicked_group = jQuery(this).attr('href');
 
				jQuery('.group').hide();
				
				jQuery(clicked_group).fadeIn();

				evt.preventDefault();
			});
			
			// Image Radio
			jQuery('.om-radio-img-img').click(function(){
				jQuery(this).parent().parent().find('.om-radio-img-img').removeClass('om-radio-img-selected');
				jQuery(this).addClass('om-radio-img-selected');
			});
			
			jQuery('.om-radio-img-label').hide();
			jQuery('.om-radio-img-img').show();
			jQuery('.om-radio-img-radio').hide();
			
			//Update Message popup
			jQuery.fn.center = function () {
				this.animate({"top":( jQuery(window).height() - this.height() - 200 ) / 2+jQuery(window).scrollTop() + "px"},100);
				this.css("left", 350 );
				return this;
			}
	
			<?php if(isset($_REQUEST['reset'])) { ?>
				var reset_popup = jQuery('#om-popup-reset');
				reset_popup.fadeIn();
				window.setTimeout(function(){
					reset_popup.fadeOut();                        
				}, 2000);
			<?php } ?>
			
			<?php if(isset($_REQUEST['import_ok'])) { ?>
				var import_ok_popup = jQuery('#om-popup-import-ok');
				import_ok_popup.fadeIn();
				window.setTimeout(function(){
					import_ok_popup.fadeOut();                        
				}, 3000);
			<?php } ?>

			<?php if(isset($_REQUEST['import_error'])) { ?>
				var import_ok_error = jQuery('#om-popup-import-error');
				import_ok_error.fadeIn();
				window.setTimeout(function(){
					import_ok_error.fadeOut();                        
				}, 4000);
			<?php } ?>

			jQuery('#om-popup-save, #om-popup-reset, #om-popup-import-ok, #om-popup-import-error').center();
			jQuery(window).scroll(function() { 
			
				jQuery('#om-popup-save, #om-popup-reset, #om-popup-import-ok, #om-popup-import-error').center();
			
			});			
			
			//Color Picker
			function initPickers(parent)
			{
				if(typeof(parent) == 'string')
					var set=jQuery(parent+' .om-option-color');
				else if(typeof(parent) == 'object')
					var set=parent.find('.om-option-color');
				else
					var set=jQuery('.om-option-color');

				set.each(function(){
					var option_id=this.id;
					jQuery('#'+option_id+'_picker').children('div').css('backgroundColor', this.value);
					jQuery('#'+option_id+'_picker').ColorPicker({
								color: this.value,
								onShow: function (colpkr) {
									jQuery(colpkr).fadeIn(500);
									return false;
								},
								onHide: function (colpkr) {
									jQuery(colpkr).fadeOut(500);
									return false;
								},
								onChange: function (hsb, hex, rgb) {
									jQuery('#'+option_id+'_picker').children('div').css('backgroundColor', '#' + hex);
									jQuery('#'+option_id+'_picker').next('input').attr('value','#' + hex);
								}
					}); 
				});
			}
			initPickers();
			

		
			//AJAX Upload
			function initUploaders(parent, save_type)
			{
				if(typeof(save_type) == 'undefined')
					save_type='upload';

				var classname='.image_upload_button';
				if(save_type == 'upload_only')
					classname+='_only';

				if(typeof(parent) == 'string')
					var set=jQuery(parent + ' '+classname);
				else if(typeof(parent) == 'object')
					var set=parent.find(classname);
				else
					var set=jQuery(classname);
					
				set.each(function(){
				
					var clickedObject = jQuery(this);
					var clickedID = jQuery(this).attr('id');
					
					var data={
								action: 'om_ajax_post_action',
								type: save_type,
								data: clickedID,
								thumb: 0
					};

					if(clickedObject.data('thumb')) {
						data.thumb=1;
						data.thumb_width=clickedObject.data('width');
						data.thumb_height=clickedObject.data('height');
						data.thumb_crop=clickedObject.data('crop');
					}
					
					new AjaxUpload(clickedID, {
						  action: '<?php echo admin_url("admin-ajax.php"); ?>',
						  name: clickedID, // File upload name
						  data: data, // Additional data to send
						  autoSubmit: true, // Submit file after selection
						  responseType: false,
						  onChange: function(file, extension){},
						  onSubmit: function(file, extension){
								clickedObject.text('Uploading'); // change button text, when user selects file	
								this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
								interval = window.setInterval(function(){
									var text = clickedObject.text();
									if (text.length < 13){	clickedObject.text(text + '.'); }
									else { clickedObject.text('Uploading'); } 
								}, 200);
						  },
						  onComplete: function(file, response) {
						   
								window.clearInterval(interval);
								clickedObject.text('Upload Image');	
								this.enable(); // enable upload button
								
								// If there was an error
								if(response.search('Upload Error') > -1){
									var buildReturn = '<span class="upload-error">' + response + '</span>';
									jQuery(".upload-error").remove();
									clickedObject.parent().after(buildReturn);
								
								}
								else{
									var buildReturn = '<img class="hide om-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';
			
									jQuery(".upload-error").remove();
									jQuery("#image_" + clickedID).remove();	
									clickedObject.parent().after(buildReturn);
									jQuery('img#image_'+clickedID).fadeIn();
									clickedObject.next('span').fadeIn();
									clickedObject.parent().prev('input').val(response);
								}
						  }
					});
				
				});
				
				//AJAX Remove (clear option value)
				
				var classname='.image_reset_button';
				if(save_type == 'upload_only')
					classname+='_only';

				if(typeof(parent) == 'string')
					var set=jQuery(parent + ' '+classname);
				else if(typeof(parent) == 'object')
					var set=parent.find(classname);
				else
					var set=jQuery(classname);
					
				set.click(function(){
			
					var clickedObject = jQuery(this);
					var clickedID = jQuery(this).attr('id');
					var theID = jQuery(this).attr('title');	
					
					if(save_type == 'upload_only')
					{
						var image_to_remove = jQuery('#image_' + theID);
						var button_to_hide = jQuery('#reset_' + theID);
						image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
						button_to_hide.fadeOut();
						clickedObject.parent().prev('input').val('');
					}
					else
					{
						var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
					
						var data = {
							action: 'om_ajax_post_action',
							type: 'image_reset',
							data: theID
						};
						
						jQuery.post(ajax_url, data, function(response) {
							
							var image_to_remove = jQuery('#image_' + theID);
							var button_to_hide = jQuery('#reset_' + theID);
							image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
							button_to_hide.fadeOut();
							clickedObject.parent().prev('input').val('');
							
						});
					}					
					return false; 
				});   	 
			}
			initUploaders();
			
			//Save everything else
			jQuery('#om-options-form').submit(function(){
			
				jQuery('.ajax-loading-img').fadeIn();
				var serializedReturn = jQuery("#om-options-form").serialize();
				 
				var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
			
				var args = {
					<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'om_options'){ ?>
					type: 'options',
					<?php } ?>
					action: 'om_ajax_post_action',
					data: serializedReturn
				};
				
				jQuery.post(ajax_url, args, function(response) {
					jQuery('.ajax-loading-img').fadeOut();
					var success = jQuery('#om-popup-save').fadeIn();
					window.setTimeout(function(){
					   success.fadeOut(); 
					}, 2000);
				});
				
				return false; 
				
			});
			
			// styling presets save
			jQuery('#om-styling-button-save').click(function(){
				
				jQuery(this).unbind('click'); // once clicked document will be reloaded
			
				jQuery('.ajax-loading-img').fadeIn();
				var serializedReturn = jQuery("#om-options-form").serialize();
				 
				var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
			
				var args = {
					<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'om_options'){ ?>
					type: 'options',
					<?php } ?>
					action: 'om_ajax_post_action',
					data: serializedReturn
				};
				
				jQuery.post(ajax_url, args, function(response) {
					jQuery('.ajax-loading-img').fadeOut();
					var success = jQuery('#om-popup-save').fadeIn();
					window.setTimeout(function(){
					   window.location.hash='#om-option-section-styling';
						 window.location.reload();
					}, 1000);
				});
				
				return false; 
				
			});
			
			// styling presets remove
			jQuery('.om-style-remove-button').click(function(){
				
				if(!confirm('<?php _e('Remove this style preset?','om_theme')?>'))
					return;
				
				var $this=jQuery(this);
				$this.unbind('click'); // once clicked document will be reloaded
			
				jQuery('.ajax-loading-img').fadeIn();
				 
				var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
				
				var data = {
					id: jQuery(this).data('optionid'),
					name: jQuery(this).data('optionname')
				}
			
				var args = {
					type: 'style_preset_remove',
					action: 'om_ajax_post_action',
					data: data
				};
				
				jQuery.post(ajax_url, args, function(response) {
					jQuery('.ajax-loading-img').fadeOut();
					$this.parents('tr').remove();
				});
				
				return false; 
				
			});
			
			// styling presets apply
			jQuery('.om-style-apply-button').click(function(){
				
				var $this=jQuery(this);
				$this.unbind('click'); // once clicked document will be reloaded
			
				jQuery('.ajax-loading-img').fadeIn();
				 
				var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
				
				var data = {
					id: jQuery(this).data('optionid'),
					name: jQuery(this).data('optionname')
				}
			
				var args = {
					type: 'style_preset_apply',
					action: 'om_ajax_post_action',
					data: data
				};
				
				jQuery.post(ajax_url, args, function(response) {
					jQuery('.ajax-loading-img').fadeOut();
					var success = jQuery('#om-popup-save').fadeIn();
					window.setTimeout(function(){
					   window.location='?page=om_options&rnd='+Math.random()+'#om-option-section-styling';
					}, 1000);
				});
				
				return false; 
				
			});
			
			// Slider with sections
			jQuery('.om_add_slider_section_button').click(function(){
				var option_id=jQuery(this).attr('rel');
				var section_index=++om_slider_max_section_index[option_id];
				om_slider_max_section_slide_index[option_id]=[];
				om_slider_max_section_slide_index[option_id][section_index]=0;
				
				var tpl=jQuery('#om-slider-'+option_id+'-section-template').html();
				tpl=tpl.replace(/SECTION_INDEX/g,section_index);
				var section=jQuery('<div class="om-slider-section">'+tpl+'</div>');
				section.hide().insertBefore(this).slideDown(200);
				
				initPickers(section);
				initUploaders(section,'upload_only');
				section.find('.om_add_slider_slide_button').click(function(){
					var slide_index=++om_slider_max_section_slide_index[option_id][section_index];
										
					var tpl=jQuery('#om-slider-'+option_id+'-slide-template').html();
					tpl=tpl.replace(/SECTION_INDEX/g,section_index);
					tpl=tpl.replace(/SLIDE_INDEX/g,slide_index);
					var slide=jQuery('<div class="om-slider-section-slide">'+tpl+'</div>');
					slide.hide().insertBefore(this).slideDown(200);	
					
					initPickers(slide);
					initUploaders(slide,'upload_only');

					slide.find('.om_remove_slider_slide').click(function(){
						slide.slideUp(200,function(){
							jQuery(this).remove();
						});
						return false;
					});
					
					return false;
				});
				
				section.find('.om_remove_slider_section').click(function(){
					section.slideUp(200,function(){
						jQuery(this).remove();
					});
					return false;
				});
				
				return false;
			});
			
			jQuery('.om-slider .om-slider-section .om_add_slider_slide_button').click(function(){

				var option_id=jQuery(this).parents('.om-slider').attr('rel');				
				var section_index=jQuery(this).parents('.om-slider-section').attr('rel');
				var slide_index=++om_slider_max_section_slide_index[option_id][section_index];
									
				var tpl=jQuery('#om-slider-'+option_id+'-slide-template').html();
				tpl=tpl.replace(/SECTION_INDEX/g,section_index);
				tpl=tpl.replace(/SLIDE_INDEX/g,slide_index);
				var slide=jQuery('<div class="om-slider-section-slide">'+tpl+'</div>');
				slide.hide().insertBefore(this).slideDown(200);	
				
				initPickers(slide);
				initUploaders(slide,'upload_only');

				slide.find('.om_remove_slider_slide').click(function(){
					slide.slideUp(200,function(){
						jQuery(this).remove();
					});
					return false;
				});
				
				return false;
			});
			
			jQuery('.om-slider').find('.om_remove_slider_section').click(function(){
				jQuery(this).parents('.om-slider-section').slideUp(200,function(){
					jQuery(this).remove();
				});
				return false;
			});
			
			jQuery('.om-slider').find('.om_remove_slider_slide').click(function(){
				jQuery(this).parents('.om-slider-section-slide').slideUp(200,function(){
					jQuery(this).remove();
				});
				return false;
			});
			
			
			// Slider
			jQuery('.om_add_simple_slider_section_button').click(function(){
				
				var option_id=jQuery(this).attr('rel');
				var slide_index=++om_simple_slider_max_slide_index;
				
				var tpl=jQuery('#om-slider-'+option_id+'-slide-template').html();
				tpl=tpl.replace(/SLIDE_INDEX/g,slide_index);
				var section=jQuery('<div class="om-slider-section">'+tpl+'</div>');
				section.hide().insertBefore(this).slideDown(200);
				
				
				
				initPickers(section);
				initUploaders(section,'upload_only');
				
				section.find('.om_remove_simple_slider_section').click(function(){
					section.slideUp(200,function(){
						jQuery(this).remove();
					});
					return false;
				});
				
				return false;
			});
			
			jQuery('.om-slider').find('.om_remove_simple_slider_section').click(function(){
				jQuery(this).parents('.om-slider-section').slideUp(200,function(){
					jQuery(this).remove();
				});
				return false;
			});

			//
			initPickers();
			initUploaders(jQuery('.om-slider'),'upload_only');
			
		});
		</script>
<?php
}

/*************************************************************************************
 *	Ajax Save Action
 *************************************************************************************/

add_action('wp_ajax_om_ajax_post_action', 'om_ajax_callback');

function om_ajax_callback() {
	global $wpdb; // this is how you get access to the database

	$save_type = $_POST['type'];
	
	if ( get_magic_quotes_gpc() ) {
		$_POST = stripslashes_deep( $_POST );
	}
	
	//Uploads and Uploads only (without saving the option)
	if($save_type == 'upload' || $save_type == 'upload_only') {
		
		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
    $filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']); 
		
		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';    
		$uploaded_file = wp_handle_upload($filename,$override);
		
		if(empty($uploaded_file['error'])) {
			if($_POST['thumb']) {
				$_POST['thumb_crop']=$_POST['thumb_crop']=='true'?true:false;
				$thumb=image_make_intermediate_size($uploaded_file['file'], $_POST['thumb_width'], $_POST['thumb_height'], $_POST['thumb_crop']);
				if($thumb) {
					$uploaded_file['url']=str_replace(basename($uploaded_file['file']),basename($thumb['file']),$uploaded_file['url']);
				}
			}
		}
		 
		if($save_type == 'upload')
			update_option( $clickedID , $uploaded_file['url'] );
		
		if(!empty($uploaded_file['error']))
			echo 'Upload Error: ' . $uploaded_file['error'];
		else
			echo $uploaded_file['url']; // Is the Response
		
	}
	
	//Image Reset
	elseif($save_type == 'image_reset') {

		$id = $_POST['data']; // Acts as the name
		global $wpdb;
		$query = "DELETE FROM $wpdb->options WHERE option_name LIKE '$id'";
		$wpdb->query($query);
		
	}
	
	// All Options
	elseif ($save_type == 'options') {
		
		$data = $_POST['data'];
		
		parse_str($data,$output);
		$output=array_map( 'stripslashes_deep', $output );
		
   	$options = get_option(OM_THEME_PREFIX.'options_template');
		
		foreach($options as $option_array) {

			if(isset($option_array['id'])) { // Non - Headings...

				$id = $option_array['id'];
				$old_value = get_option($id);
				$new_value = '';
				
				if(isset($output[$id])){
					$new_value = $output[$option_array['id']];
				}
		
				$type = $option_array['type'];
				
				if($new_value == '' && $type == 'checkbox'){ // Checkbox Save

					update_option($id,'false');
				}
				elseif ($new_value == 'true' && $type == 'checkbox'){ // Checkbox Save
					
					update_option($id,'true');
				}
				elseif($type == 'multicheck'){ // Multi Check Save
					
					$option_options = $option_array['options'];

					$tmp=array();					
					foreach ($option_options as $options_id => $options_value){
						
					  $tmp[$options_id]=isset($output[$id][$options_id]);
					}
					update_option($id,$tmp);
				} 
				elseif($type == 'typography'){
						
					$typography_array = array();	
					
					$typography_array['size'] = $output[$option_array['id'] . '_size'];
						
					$typography_array['face'] = $output[$option_array['id'] . '_face'];
						
					$typography_array['style'] = $output[$option_array['id'] . '_style'];
						
					$typography_array['color'] = $output[$option_array['id'] . '_color'];
						
					update_option($id,$typography_array);
						
				}
				elseif($type == 'border'){
						
					$border_array = array();	
					
					$border_array['width'] = $output[$option_array['id'] . '_width'];
						
					$border_array['style'] = $output[$option_array['id'] . '_style'];
						
					$border_array['color'] = $output[$option_array['id'] . '_color'];
						
					update_option($id,$border_array);
						
				}
				elseif($type == 'slider_w_sections'){
						
					if(is_array(@$output[$id]))
					{
						unset($output[$id]['SECTION_INDEX']); // it's an extra record, that is actually the template
						// sort sections
						$section_sort=array();
						foreach($output[$id] as $section_index=>$section)
						{
							$section_sort[$section_index]=intval($section['ord']);
							if(!$section_sort[$section_index])
								$section_sort[$section_index]=100;
						}
						$section_sort=array_reverse($section_sort,true); // save positions on same ord
						asort($section_sort);
						$new_output=array();
						foreach($section_sort as $section_index=>$v)
						{
							//sort slides
							if(is_array(@$output[$id][$section_index]['slides']))
							{
								$slide_sort=array();
								foreach($output[$id][$section_index]['slides'] as $slide_index=>$slide)
								{
									$slide_sort[$slide_index]=intval($slide['ord']);
									if(!$slide_sort[$slide_index])
										$slide_sort[$slide_index]=100;
								}
								$slide_sort=array_reverse($slide_sort,true); // save positions on same ord
								asort($slide_sort);
								$new_slides=array();
								foreach($slide_sort as $slide_index=>$v)
									$new_slides[]=$output[$id][$section_index]['slides'][$slide_index];
								$output[$id][$section_index]['slides']=$new_slides;
							}
							else
							{
								$output[$id][$section_index]['slides']=array();
							}
							$new_output[]=$output[$id][$section_index];
						}
						$output[$id]=$new_output;
						
						/*
						foreach($output[$id] as $section_index=>$section)
						{
							if(is_array(@$section['slides']))
								$output[$id][$section_index]['slides']=array_values($section['slides']); // reset indexes
							else
								$output[$id][$section_index]['slides']=array();
						}
						$output[$id]=array_values($output[$id]); // reset indexes
						*/
					}
				}
				elseif($type == 'slider'){
						
					if(is_array(@$output[$id]))
					{
						unset($output[$id]['SLIDE_INDEX']); // it's an extra record, that is actually the template
						// sort sections
						$section_sort=array();
						foreach($output[$id] as $section_index=>$section)
						{
							$section_sort[$section_index]=intval($section['ord']);
							if(!$section_sort[$section_index])
								$section_sort[$section_index]=100;
						}
						$section_sort=array_reverse($section_sort,true); // save positions on same ord
						asort($section_sort);
						$new_output=array();
						foreach($section_sort as $section_index=>$v)
						{
							$new_output[]=$output[$id][$section_index];
						}
						$output[$id]=$new_output;
						
					}
					else
						$output[$id]=array();
					
					update_option($id,$output[$id]);
						
				}
				elseif($type == 'form_fields'){
						
					if(!is_array(@$output[$id]))
						$output[$id]=array();
					
					update_option($id,$output[$id]);
				}
				elseif($type == 'styling_presets'){
					$tmp=array();
					
					if(is_array($option_array['options'])) {
						foreach($option_array['options'] as $k) {
							$tmp[$k]=@$output[$k];
						}
					}
					$name=$output[$id.'_new'];
					if($name) {
						$output[$id] = get_option($id);
						$output[$id][$name] = $tmp;
						update_option($id,$output[$id]);
					}
				}
				elseif($type != 'upload_min'){
				
					update_option($id,$new_value);
				}
			}	
		}
	}
	// Applt Styling
	elseif ($save_type == 'style_preset_apply') {
		
		$data = $_POST['data'];
		if(@$data['id'] && @$data['name']) {
			$presets = get_option($data['id']);
			$data['name']=urldecode($data['name']);
			
			if(is_array(@$presets[$data['name']])) {
				foreach($presets[$data['name']] as $k=>$v) {
					update_option($k,$v);
				}
			}
			
		}
	}
	// Remove Styling
	elseif ($save_type == 'style_preset_remove') {
		
		$data = $_POST['data'];
		if(@$data['id'] && @$data['name']) {
			
			$presets = get_option($data['id']);
			unset($presets[urldecode($data['name'])]);
			
			update_option($data['id'],$presets);
			
		}
	}
	
  die();

}


/*************************************************************************************
 *	Generates The Options
 *************************************************************************************/
 
function om_options_generator($options) {

  $counter = 0;
	$menu = '';
	$output = '';
	foreach ($options as $value) {
	   
		$counter++;
		$val = '';
		//Start Heading
		if ( $value['type'] != "heading" )
		{
			$output .= '<div class="section section-'.$value['type'].'">';
			$output .= '<h3 class="heading">'. $value['name'] .'</h3>';
			$output .= '<div class="option"><div class="controls">';
		} 
		//End Heading
		$select_value = '';                                   
		switch ( $value['type'] ) {
		
			case 'text':
				$val = $value['std'];
				$std = get_option($value['id']);
				if ( $std != "")
					$val = $std;
				$output .= '<input name="'. $value['id'] .'" id="'. $value['id'] .'" type="text" value="'. stripslashes(htmlspecialchars($val)) .'" />';
			break;
			
			case 'select':
	
				$output .= '<select name="'. $value['id'] .'" id="'. $value['id'] .'">';
				$select_value = get_option($value['id']);
				foreach ($value['options'] as $option) {
					$selected = '';
					 if($select_value != '') {
						 if ( $select_value == $option )
						 	$selected = ' selected="selected"';
				   } else {
						 if ( isset($value['std']) )
							 if ($value['std'] == $option)
							 	$selected = ' selected="selected"';
					 }
					 $output .= '<option'. $selected .'>'.$option.'</option>';
				 } 
				 $output .= '</select>';
			break;
			
			case 'select-cat':
				
				$val = $value['std'];
				$std = get_option($value['id']);
				if ( $std != "") { $val = $std; }
				
				$args = array(
					'show_option_all'    => __('All Categories', 'om_theme'),
					'show_option_none'   => __('No Categories', 'om_theme'),
					'hide_empty'         => 0, 
					'echo'               => 0,
					'selected'           => $val,
					'hierarchical'       => 0, 
					'name'               => $value['id'],
					'class'              => 'postform',
					'depth'              => 0,
					'tab_index'          => 0,
					'taxonomy'           => 'category',
					'hide_if_empty'      => false 	
				);
		
				 $output .= wp_dropdown_categories( $args );
			break;
			
			case 'select-page':
				
				$val = $value['std'];
				$std = get_option($value['id']);
				if ( $std != "") { $val = $std; }
				
				$args = array(
					'selected'         => $val,
					'echo'             => 0,
					'name'             => $value['id']
				);
		
				$output .= wp_dropdown_pages( $args );
			break;

			case 'select-tax':
				
				$val = $value['std'];
				$std = get_option($value['id']);
				if ( $std != "") { $val = $std; }
				
				$args = array(
					'show_option_all'    => __('All', 'om_theme').' '.$value['taxonomy'],
					'show_option_none'   => __('No', 'om_theme').' '.$value['taxonomy'],
					'hide_empty'         => 0, 
					'echo'               => 0,
					'selected'           => $val,
					'hierarchical'       => 0, 
					'name'               => $value['id'],
					'class'              => 'postform',
					'depth'              => 0,
					'tab_index'          => 0,
					'taxonomy'           => $value['taxonomy'],
					'hide_if_empty'      => false 	
				);
		
				$output .= @wp_dropdown_categories( $args );
			break;
			
			case 'select2':
	
				$output .= '<select name="'. $value['id'] .'" id="'. $value['id'] .'">';
			
				$select_value = get_option($value['id']);
				 
				foreach ($value['options'] as $option => $name) {
					
					$selected = '';
					
					 if($select_value != '') {
						 if ( $select_value == $option) { $selected = ' selected="selected"';} 
				     } else {
						 if ( isset($value['std']) )
							 if ($value['std'] == $option) { $selected = ' selected="selected"'; }
					 }
					  
					 $output .= '<option'. $selected .' value="'.$option.'">'.$name.'</option>';
				 
				 } 
				 $output .= '</select>';
			break;

			case 'textarea':
				
				$cols = '8';
				$ta_value = '';
				if(isset($value['std'])) {
					
					$ta_value = $value['std']; 
					
					if(isset($value['options'])){
						$ta_options = $value['options'];
						if(isset($ta_options['cols'])){
						$cols = $ta_options['cols'];
						} else { $cols = '8'; }
					}
					
				}
				$std = get_option($value['id']);
				if( $std != "") { $ta_value = stripslashes( $std ); }
				$output .= '<textarea name="'. $value['id'] .'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.htmlspecialchars($ta_value).'</textarea>';
			break;

			case "radio":
				
				 $select_value = get_option( $value['id']);
					   
				 foreach ($value['options'] as $key => $option) 
				 { 
	
					 $checked = '';
					   if($select_value != '') {
							if ( $select_value == $key) { $checked = ' checked'; } 
					   } else {
						if ($value['std'] == $key) { $checked = ' checked'; }
					   }
					$output .= '<input type="radio" name="'. $value['id'] .'" value="'. $key .'" '. $checked .' />' . $option .'<br />';
				
				}
			break;

			case "checkbox": 
			
				$std = $value['std'];  
				$saved_std = get_option($value['id']);
				$checked = '';
				
				if(!empty($saved_std)) {
					if($saved_std == 'true') {
					$checked = 'checked="checked"';
					}
					else{
					   $checked = '';
					}
				}
				elseif( $std == 'true') {
				   $checked = 'checked="checked"';
				}
				else {
					$checked = '';
				}
				$output .= '<input type="checkbox" name="'.  $value['id'] .'" id="'. $value['id'] .'" value="true" '. $checked .' />';
			break;
			
			case "multicheck":
			
				$std =  $value['std'];         
				$saved_std = get_option($value['id']);
				
				foreach ($value['options'] as $key => $option) {
												 
					if(!empty($saved_std)) { 
					  if($saved_std[$key] == 'true'){
						 $checked = 'checked="checked"';  
					  } 
					  else{
						  $checked = '';   
					  }    
					} 
					elseif( $std[$key] == 'true') {
					  $checked = 'checked="checked"';
					}
					else {
						$checked = '';                                                                                    }
					
					$output .= '<input type="checkbox" name="'. $value['id'] .'['.$key.']" id="'. $value['id'] .'_'.$key .'" value="true" '. $checked .' /><label for="'. $value['id'] .'_'.$key .'">'. $option .'</label><br />';

				}
			break;
			
			case "upload":
				
				$output .= om_options_uploader_generator($value['id'],$value['std'],null);
			break;

			case "upload_min":
				
				$output .= om_options_uploader_generator($value['id'],$value['std'],'min');
			break;
			
			case "note":
			
				$output .= '<div class="notes"><p>'. $value['message'] .'</p></div>';
			break;
			
			case "intro":
			
				$output .= '<div class="intro"><p>'. $value['message'] .'</p></div>';
			break;
			
			case "color":
			
				$val = $value['std'];
				$stored  = get_option( $value['id'] );
				if ( $stored != "") { $val = $stored; }
				$output .= '<div id="' . $value['id'] . '_picker" class="colorSelector"><div></div></div>';
				$output .= '<input class="om-option-color" name="'. $value['id'] .'" id="'. $value['id'] .'" type="text" value="'. $val .'" />';
			break;   
			
			case "typography":
			
				$default = $value['std'];
				$typography_stored = get_option($value['id']);
				
				/* Font Size */
				$val = $default['size'];
				if ( $typography_stored['size'] != "") { $val = $typography_stored['size']; }
				$output .= '<select class="om-option-typography om-option-typography-size" name="'. $value['id'].'_size" id="'. $value['id'].'_size">';
					for ($i = 9; $i < 71; $i++){ 
						if($val == $i){ $active = 'selected="selected"'; } else { $active = ''; }
						$output .= '<option value="'. $i .'" ' . $active . '>'. $i .'px</option>'; }
				$output .= '</select>';
			
				/* Font Face */
				$val = $default['face'];
				if ( $typography_stored['face'] != "") 
					$val = $typography_stored['face']; 
	
				$font01 = ''; 
				$font02 = ''; 
				$font03 = ''; 
				$font04 = ''; 
				$font05 = ''; 
				$font06 = ''; 
				$font07 = ''; 
				$font08 = '';
				$font09 = '';
	
				if (strpos($val, 'Arial, sans-serif') !== false){ $font01 = 'selected="selected"'; }
				if (strpos($val, 'Verdana, Geneva') !== false){ $font02 = 'selected="selected"'; }
				if (strpos($val, 'Trebuchet') !== false){ $font03 = 'selected="selected"'; }
				if (strpos($val, 'Georgia') !== false){ $font04 = 'selected="selected"'; }
				if (strpos($val, 'Times New Roman') !== false){ $font05 = 'selected="selected"'; }
				if (strpos($val, 'Tahoma, Geneva') !== false){ $font06 = 'selected="selected"'; }
				if (strpos($val, 'Palatino') !== false){ $font07 = 'selected="selected"'; }
				if (strpos($val, 'Helvetica') !== false){ $font08 = 'selected="selected"'; }
				
				$output .= '<select class="om-option-typography om-option-typography-face" name="'. $value['id'].'_face" id="'. $value['id'].'_face">';
				$output .= '<option value="Arial, sans-serif" '. $font01 .'>Arial</option>';
				$output .= '<option value="Verdana, Geneva, sans-serif" '. $font02 .'>Verdana</option>';
				$output .= '<option value="&quot;Trebuchet MS&quot;, Tahoma, sans-serif"'. $font03 .'>Trebuchet</option>';
				$output .= '<option value="Georgia, serif" '. $font04 .'>Georgia</option>';
				$output .= '<option value="&quot;Times New Roman&quot;, serif"'. $font05 .'>Times New Roman</option>';
				$output .= '<option value="Tahoma, Geneva, Verdana, sans-serif"'. $font06 .'>Tahoma</option>';
				$output .= '<option value="Palatino, &quot;Palatino Linotype&quot;, serif"'. $font07 .'>Palatino</option>';
				$output .= '<option value="&quot;Helvetica Neue&quot;, Helvetica, sans-serif" '. $font08 .'>Helvetica</option>';
				$output .= '</select>';
			
				/* Font Weight */
				$val = $default['style'];
				if ( $typography_stored['style'] != "") { $val = $typography_stored['style']; }
					$normal = ''; $italic = ''; $bold = ''; $bolditalic = '';
				if($val == 'normal'){ $normal = 'selected="selected"'; }
				if($val == 'italic'){ $italic = 'selected="selected"'; }
				if($val == 'bold'){ $bold = 'selected="selected"'; }
				if($val == 'bold italic'){ $bolditalic = 'selected="selected"'; }
				
				$output .= '<select class="om-option-typography om-option-typography-style" name="'. $value['id'].'_style" id="'. $value['id'].'_style">';
				$output .= '<option value="normal" '. $normal .'>Normal</option>';
				$output .= '<option value="italic" '. $italic .'>Italic</option>';
				$output .= '<option value="bold" '. $bold .'>Bold</option>';
				$output .= '<option value="bold italic" '. $bolditalic .'>Bold/Italic</option>';
				$output .= '</select>';
				
				/* Font Color */
				$val = $default['color'];
				if ( $typography_stored['color'] != "") { $val = $typography_stored['color']; }			
				$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div></div></div>';
				$output .= '<input class="om-option-color om-option-typography om-option-typography-color" name="'. $value['id'] .'_color" id="'. $value['id'] .'_color" type="text" value="'. $val .'" />';
	
			break;  
			
			case "border":
			
				$default = $value['std'];
				$border_stored = get_option( $value['id'] );
				
				/* Border Width */
				$val = $default['width'];
				if ( $border_stored['width'] != "") { $val = $border_stored['width']; }
				$output .= '<select class="om-option-border om-option-border-width" name="'. $value['id'].'_width" id="'. $value['id'].'_width">';
					for ($i = 0; $i < 21; $i++){ 
						if($val == $i){ $active = 'selected="selected"'; } else { $active = ''; }
						$output .= '<option value="'. $i .'" ' . $active . '>'. $i .'px</option>'; }
				$output .= '</select>';
				
				/* Border Style */
				$val = $default['style'];
				if ( $border_stored['style'] != "") { $val = $border_stored['style']; }
					$solid = ''; $dashed = ''; $dotted = '';
				if($val == 'solid'){ $solid = 'selected="selected"'; }
				if($val == 'dashed'){ $dashed = 'selected="selected"'; }
				if($val == 'dotted'){ $dotted = 'selected="selected"'; }
				
				$output .= '<select class="om-option-border om-option-border-style" name="'. $value['id'].'_style" id="'. $value['id'].'_style">';
				$output .= '<option value="solid" '. $solid .'>Solid</option>';
				$output .= '<option value="dashed" '. $dashed .'>Dashed</option>';
				$output .= '<option value="dotted" '. $dotted .'>Dotted</option>';
				$output .= '</select>';
				
				/* Border Color */
				$val = $default['color'];
				if ( $border_stored['color'] != "") { $val = $border_stored['color']; }			
				$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div></div></div>';
				$output .= '<input class="om-option-color om-option-border om-option-border-color" name="'. $value['id'] .'_color" id="'. $value['id'] .'_color" type="text" value="'. $val .'" />';
	
			break;   
			
			case "images":
				$i = 0;
				$select_value = get_option( $value['id']);
					   
				foreach ($value['options'] as $key => $option) { 
					$i++;
	
					$checked = '';
					$selected = '';
				  if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; $selected = 'om-radio-img-selected'; } 
				  } else {
						if ($value['std'] == $key) { $checked = ' checked'; $selected = 'om-radio-img-selected'; }
						elseif ($i == 1  && !isset($select_value)) { $checked = ' checked'; $selected = 'om-radio-img-selected'; }
						elseif ($i == 1  && $value['std'] == '') { $checked = ' checked'; $selected = 'om-radio-img-selected'; }
						else { $checked = ''; }
					}	
					
					$output .= '<span>';
					$output .= '<input type="radio" id="om-radio-img-' . $value['id'] . $i . '" class="checkbox om-radio-img-radio" value="'.$key.'" name="'. $value['id'].'" '.$checked.' />';
					$output .= '<div class="om-radio-img-label">'. $key .'</div>';
					$output .= '<img src="'.$option.'" alt="" class="om-radio-img-img '. $selected .'" onClick="document.getElementById(\'om-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
					$output .= '</span>';
					
				}
			break; 
			
			case "info":
				$default = $value['std'];
				$output .= $default;
			break;                                   
			
			case 'slider_w_sections':
				$val = $value['std'];
				$std = get_option($value['id']);
				if ( $std != "")
					$val = $std;

				//templates			
				$output .='
					<div class="hide" id="om-slider-'.$value['id'].'-section-template">
						<div style="float:right;margin-top:8px"><small>'.__('Section order priority:','om_theme').'</small> <input type="text" name="'.$value['id'].'[SECTION_INDEX][ord]" style="width:40px" value="100"></div>
						<p><b>Section</b>&nbsp;&nbsp;&nbsp;<span class="om_remove_slider_section button">'.__('Remove','om_theme').'</span></p>
						<div class="clear"></div>
						<div><small>'.__('Section name:','om_theme').'</small></div>
						<input type="text" name="'.$value['id'].'[SECTION_INDEX][name]"><br/>
						<div><small>'.__('Section Background Color:','om_theme').'</small></div>
						<div id="'.$value['id'].'_SECTION_INDEX_bgcolor_picker" class="colorSelector"><div></div></div>
						<input class="om-option-color" name="'.$value['id'].'[SECTION_INDEX][bgcolor]" id="'.$value['id'].'_SECTION_INDEX_bgcolor" type="text" value="" />
						<div class="clear"></div>
						<div><small>'.__('Section Background Image:','om_theme').'</small></div>
						'.om_options_uploader_generator($value['id'].'[SECTION_INDEX][bgimage]','',null,true).'
						<div class="clear" style="height:20px"></div>
						<p><b>'.__('Slides','om_theme').'</b></p>
						<div class="om-slider-section-slides">
							<span class="button om_add_slider_slide_button" rel="SECTION_INDEX">'.__('+ Add Slide To Section','om_theme').'</span>
							<div class="clear" style="height:10px"></div>
						</div>
					</div>
					
					<div class="hide" id="om-slider-'.$value['id'].'-slide-template">
						<div style="float:right;margin-top:8px"><small>'.__('Slide order priority:','om_theme').'</small> <input type="text" name="'.$value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][ord]" style="width:40px" value="100"></div>
						<p><b>Slide</b>&nbsp;&nbsp;&nbsp;<span class="om_remove_slider_slide button">'.__('Remove','om_theme').'</span></p>
						<div class="clear"></div>
						<div><small>'.__('Slide title:','om_theme').'</small></div>
						<input type="text" name="'.$value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][title]"><br/>
						<div><small>'.__('Slide Background Color:','om_theme').'</small></div>
						<div id="'.$value['id'].'_SECTION_INDEX_slides_SLIDE_INDEX_bgcolor_picker" class="colorSelector"><div></div></div>
						<input class="om-option-color" name="'.$value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][bgcolor]" id="'.$value['id'].'_SECTION_INDEX_slides_SLIDE_INDEX_bgcolor" type="text" value="" />
						<div class="clear"></div>
						<div><small>'.__('Section Background Image:','om_theme').'</small></div>
						'.om_options_uploader_generator($value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][bgimage]','',null,true).'
						<div class="clear" style="height:20px"></div>
						<div><small>'.__('Slide Image (460x320 or 940x320 for full-width image slides):','om_theme').'</small></div>
						'.om_options_uploader_generator($value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][slideimage]','',null,true).'
						<div class="clear" style="height:20px"></div>
						<div><small>'.__('Slide Video Embed Code (fill this field instead of uploading image if you want video,<br />size: 460x320 or 940x320 for full-width image slides):','om_theme').'</small></div>
						<textarea name="'.$value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][video_embed]" rows="8"></textarea>
						<div><small>'.__('Full-width image slide (no text):','om_theme').'</small> <input type="checkbox" name="'.$value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][fullwidth_image]" value="true" /></div>
						<div><small>'.__('Slide description:','om_theme').'</small></div>
						<textarea name="'.$value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][description]" rows="8"></textarea>
						<div><small>'.__('Slide Details Link:','om_theme').'</small></div>
						<input type="text" name="'.$value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][link]"><br/>
						<div><small>'.__('Slide Details Link Anchor:','om_theme').'</small></div>
						<input type="text" name="'.$value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][link_anchor]"><br/>
						<div><small>'.__('Slide Text Color:','om_theme').'</small></div>
						<select name="'.$value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][text_color]"><option value="light">'.__('Light','om_theme').'</option><option value="dark">'.__('Dark','om_theme').'</option></select>
						<div><small>'.__('Flip slide:','om_theme').'</small> <input type="checkbox" name="'.$value['id'].'[SECTION_INDEX][slides][SLIDE_INDEX][flip_slide]" value="true" /></div>

						<div class="clear" style="height:10px"></div>
					</div>
				';

				$output.= '<div class="om-slider" rel="'.$value['id'].'">';

				$last_section_index=0;
				if(!empty($val))
				{
					foreach($val as $section)
					{
						$output.= '
						<div class="om-slider-section" rel="'.$last_section_index.'">
							<div style="float:right;margin-top:8px"><small>'.__('Slide order priority:','om_theme').'</small> <input type="text" name="'.$value['id'].'['.$last_section_index.'][ord]" style="width:40px" value="'.($section['ord']?$section['ord']:'100').'"></div>
							<p><b>Section</b>&nbsp;&nbsp;&nbsp;<span class="om_remove_slider_section button">'.__('Remove','om_theme').'</span></p>
							<div class="clear"></div>
							<div><small>'.__('Section name:','om_theme').'</small></div>
							<input type="text" name="'.$value['id'].'['.$last_section_index.'][name]" value="'. stripslashes(htmlspecialchars($section['name'])) .'"><br/>
							<div><small>'.__('Section Background Color:','om_theme').'</small></div>
							<div id="'.$value['id'].'_'.$last_section_index.'_bgcolor_picker" class="colorSelector"><div></div></div>
							<input class="om-option-color" name="'.$value['id'].'['.$last_section_index.'][bgcolor]" id="'.$value['id'].'_'.$last_section_index.'_bgcolor" type="text" value="'.$section['bgcolor'].'" />
							<div class="clear"></div>
							<div><small>'.__('Section Background Image:','om_theme').'</small></div>
							'.om_options_uploader_generator($value['id'].'['.$last_section_index.'][bgimage]',$section['bgimage'],null,true).'
							<div class="clear" style="height:20px"></div>
							<p><b>'.__('Slides','om_theme').'</b></p>
							<div class="om-slider-section-slides">
						';
						$last_section_slide_index=0;
						if(!empty($section['slides']))
						{
							foreach($section['slides'] as $slide)
							{
								$output.='
								<div class="om-slider-section-slide">
									<div style="float:right;margin-top:8px"><small>'.__('Slide order priority:','om_theme').'</small> <input type="text" name="'.$value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][ord]" style="width:40px" value="'.($slide['ord']?$slide['ord']:'100').'"></div>
									<p><b>Slide</b>&nbsp;&nbsp;&nbsp;<span class="om_remove_slider_slide button">'.__('Remove','om_theme').'</span></p>
									<div class="clear"></div>
									<div><small>'.__('Slide title:','om_theme').'</small></div>
									<input type="text" name="'.$value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][title]" value="'. stripslashes(htmlspecialchars($slide['title'])) .'"><br/>
									<div><small>'.__('Slide Background Color:','om_theme').'</small></div>
									<div id="'.$value['id'].'_'.$last_section_index.'_slides_'.$last_section_slide_index.'_bgcolor_picker" class="colorSelector"><div></div></div>
									<input class="om-option-color" name="'.$value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][bgcolor]" id="'.$value['id'].'_'.$last_section_index.'_slides_'.$last_section_slide_index.'_bgcolor" type="text" value="'.$slide['bgcolor'].'" />
									<div class="clear"></div>
									<div><small>'.__('Slide Background Image:','om_theme').'</small></div>
									'.om_options_uploader_generator($value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][bgimage]',$slide['bgimage'],null,true).'
									<div class="clear" style="height:20px"></div>
									<div><small>'.__('Slide Image (460x320 or 940x320 for full-width image slides):','om_theme').'</small></div>
									'.om_options_uploader_generator($value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][slideimage]',$slide['slideimage'],null,true).'
									<div class="clear" style="height:20px"></div>
									<div><small>'.__('Slide Video Embed Code (fill this field instead of uploading image if you want video,<br />size: 460x320 or 940x320 for full-width image slides):','om_theme').'</small></div>
									<textarea name="'.$value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][video_embed]" rows="8">'.htmlspecialchars(stripslashes($slide['video_embed'])).'</textarea>
									<div><small>'.__('Full-width image slide (no text):','om_theme').'</small> <input type="checkbox" name="'.$value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][fullwidth_image]" value="true" '.(@$slide['fullwidth_image']=='true'?'checked="checked"':'') .' /></div>
									<div><small>'.__('Slide description:','om_theme').'</small></div>
									<textarea name="'.$value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][description]" rows="8">'.htmlspecialchars(stripslashes($slide['description'])).'</textarea>
									<div><small>'.__('Slide Details Link:','om_theme').'</small></div>
									<input type="text" name="'.$value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][link]" value="'. stripslashes(htmlspecialchars($slide['link'])) .'"><br/>
									<div><small>'.__('Slide Details Link Anchor:','om_theme').'</small></div>
									<input type="text" name="'.$value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][link_anchor]" value="'. stripslashes(htmlspecialchars($slide['link_anchor'])) .'"><br/>
									<div><small>'.__('Slide Text Color:','om_theme').'</small></div>
									<select name="'.$value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][text_color]"><option value="light"'.(@$slide['text_color']=='light'?' selected="selected"':'').'>'.__('Light','om_theme').'</option><option value="dark"'.(@$slide['text_color']=='dark'?' selected="selected"':'').'>'.__('Dark','om_theme').'</option></select>
									<div><small>'.__('Flip slide:','om_theme').'</small> <input type="checkbox" name="'.$value['id'].'['.$last_section_index.'][slides]['.$last_section_slide_index.'][flip_slide]" value="true" '.(@$slide['flip_slide']=='true'?'checked="checked"':'') .' /></div>
										
									<div class="clear" style="height:10px"></div>
								</div>
								';
								$last_section_slide_index++;
							}
						}
						$output.='
							<script>
								if(typeof(om_slider_max_section_slide_index)=="undefined")
									var om_slider_max_section_slide_index=[];
								if(typeof(om_slider_max_section_slide_index["'.$value['id'].'"])=="undefined")
									om_slider_max_section_slide_index["'.$value['id'].'"]=[];
		
								om_slider_max_section_slide_index["'.$value['id'].'"]['.$last_section_index.']='.$last_section_slide_index.';
							</script>
						';
						$output.= '
								<span class="button om_add_slider_slide_button" rel="'.$last_section_index.'">+ Add Slide To Section</span>
								<div class="clear" style="height:10px"></div>
							</div>
						</div>
						';
						$last_section_index++;
					}
				}
				
				$output.='
					
					<span class="button om_add_slider_section_button" rel="'.$value['id'].'">+ Add Section To Slider</span>

					<script>
						if(typeof(om_slider_max_section_index)=="undefined")
							var om_slider_max_section_index=[];
						if(typeof(om_slider_max_section_slide_index)=="undefined")
							var om_slider_max_section_slide_index=[];

						om_slider_max_section_index["'.$value['id'].'"]='.$last_section_index.';
					</script>
					
					</div>
				';
				
			break;		
			
			case 'slider':
				$val = $value['std'];
				$std = get_option($value['id']);
				if ( $std != "")
					$val = $std;

				//templates			
				$output .='
					<div class="hide" id="om-slider-'.$value['id'].'-slide-template">
						<div style="float:right;margin-top:8px"><small>'.__('Slide order priority:','om_theme').'</small> <input type="text" name="'.$value['id'].'[SLIDE_INDEX][ord]" style="width:40px" value="100"></div>
						<p><b>Slide</b>&nbsp;&nbsp;&nbsp;<span class="om_remove_simple_slider_section button">'.__('Remove','om_theme').'</span></p>
						<div class="clear"></div>
						<div><small>'.__('Slide title:','om_theme').'</small></div>
						<input type="text" name="'.$value['id'].'[SLIDE_INDEX][title]" />
						<div><small>'.__('Slide Image (minimal size 480x328, will be resized automatically if bigger):','om_theme').'</small></div>
						'.om_options_uploader_generator($value['id'].'[SLIDE_INDEX][bgimage]','',null,true,array('width'=>480, 'height'=>328, 'crop'=>'true')).'
						<div class="clear" style="height:20px"></div>
						<div><small>'.__('Slide Video Embed Code (fill this field instead of uploading image if you want video,<br />size: any, it will be fitted):','om_theme').'</small></div>
						<textarea name="'.$value['id'].'[SLIDE_INDEX][video_embed]" rows="8"></textarea>
						<div><small>'.__('Slide description:','om_theme').'</small></div>
						<textarea name="'.$value['id'].'[SLIDE_INDEX][description]" rows="8"></textarea>
						<div><small>'.__('Slide Details Link:','om_theme').'</small></div>
						<input type="text" name="'.$value['id'].'[SLIDE_INDEX][link]"><br/>
						<div class="clear" style="height:10px"></div>
					</div>
				';
/*
*/
				$output.= '<div class="om-slider" rel="'.$value['id'].'">';

				$last_slide_index=0;
				if(!empty($val))
				{
					foreach($val as $slide)
					{
						$output.='
						<div class="om-slider-section">
							<div style="float:right;margin-top:8px"><small>'.__('Slide order priority:','om_theme').'</small> <input type="text" name="'.$value['id'].'['.$last_slide_index.'][ord]" style="width:40px" value="'.($slide['ord']?$slide['ord']:'100').'"></div>
							<p><b>Slide</b>&nbsp;&nbsp;&nbsp;<span class="om_remove_simple_slider_section button">'.__('Remove','om_theme').'</span></p>
							<div class="clear"></div>
							<div><small>'.__('Slide title:','om_theme').'</small></div>
							<input type="text" name="'.$value['id'].'['.$last_slide_index.'][title]" value="'. stripslashes(htmlspecialchars($slide['title'])) .'"/>
							<div><small>'.__('Slide Image (minimal size 480x328, will be resized automatically if bigger):','om_theme').'</small></div>
							'.om_options_uploader_generator($value['id'].'['.$last_slide_index.'][bgimage]',$slide['bgimage'],null,true,array('width'=>480, 'height'=>328, 'crop'=>'true')).'
							<div class="clear" style="height:20px"></div>
							<div><small>'.__('Slide Video Embed Code (fill this field instead of uploading image if you want video,<br />size: any, it will be fitted):','om_theme').'</small></div>
							<textarea name="'.$value['id'].'['.$last_slide_index.'][video_embed]" rows="8">'.htmlspecialchars(stripslashes($slide['video_embed'])).'</textarea>
							<div><small>'.__('Slide description:','om_theme').'</small></div>
							<textarea name="'.$value['id'].'['.$last_slide_index.'][description]" rows="8">'.htmlspecialchars(stripslashes($slide['description'])).'</textarea>
							<div><small>'.__('Slide Details Link:','om_theme').'</small></div>
							<input type="text" name="'.$value['id'].'['.$last_slide_index.'][link]" value="'. stripslashes(htmlspecialchars($slide['link'])) .'"><br/>
								
							<div class="clear" style="height:10px"></div>
						</div>
						';
/*
*/
						$last_slide_index++;
					}
				}
				
				$output.='
					
					<span class="button om_add_simple_slider_section_button" rel="'.$value['id'].'">+ Add Slide</span>

					<script>
						om_simple_slider_max_slide_index='.$last_slide_index.';
					</script>
					
					</div>
				';
				
			break;		


			case "form_fields": 
			
				$std = $value['std'];  
				$saved_std = get_option($value['id']);
				if(!is_array($saved_std))
					$saved_std=array();

				for($i=0;$i<10;$i++) {
					$output .= __('<b>Field','om_theme').' '.($i+1).'</b><br/>';
					$output .= __('Name:','om_theme').' <input type="text" name="'.  $value['id'] .'['.$i.'][name]" value="'.addslashes(@$saved_std[$i]['name']).'" /><br/>';
					$output .= __('Type:','om_theme').' <select style="width:120px" name="'.  $value['id'] .'['.$i.'][type]"><option value="text">String</option><option value="textarea"'.(@$saved_std[$i]['type']=='textarea'?' selected="selected"':'').'>Textarea</option><option value="checkbox"'.(@$saved_std[$i]['type']=='checkbox'?' selected="selected"':'').'>Checkbox</option></select> &nbsp;&nbsp;&nbsp;';
					$output .= __('Required:','om_theme').' <input type="checkbox" name="'.  $value['id'] .'['.$i.'][required]" '.(@$saved_std[$i]['required']?' checked="checked"':'').' />';
					$output .= '<br/><div style="border-bottom:1px dotted #aaa"></div><br/>';
				}
			break;
			
			case "styling_presets": 
			
				$saved_std = get_option($value['id']);
				if(!is_array($saved_std))
					$saved_std=array();

				if(empty($saved_std))
					$output .= '<i>'.__('No presets created yet.','om_theme').'</i><br />';
				else {
					$output .= '<table border="0" cellpadding="10" cellspacing="0">';
					foreach($saved_std as $k=>$v) {
						$output .= '<tr>
							<td style="border-bottom:1px dotted #aaa"><b>'.$k.'</b></td>
							<td style="border-bottom:1px dotted #aaa"><span class="button om-style-apply-button" id="'.$value['id'].'_apply" data-optionid="'.$value['id'].'" data-optionname="'.urlencode($k).'">'.__('Apply','om_theme').'</span></td>
							<td style="border-bottom:1px dotted #aaa"><span class="button om-style-remove-button" id="'.$value['id'].'_apply" data-optionid="'.$value['id'].'" data-optionname="'.urlencode($k).'">'.__('Remove','om_theme').'</span></td>
						</tr>';
					}
					$output .= '</table><br />';
				}
				$output .= '<br /><b>'.__('Save current styling options as new preset:','om_theme').'</b><br/>Name: <input type="text" name="'.$value['id'].'_new" style="width:60%" /> <span class="button " id="om-styling-button-save">'.__('Save','om_theme').'</span> <br />';
			break;
						
			case "heading":
				
				if($counter >= 2){
				   $output .= '</div>'."\n";
				}
				$jquery_click_hook = ereg_replace("[^A-Za-z0-9]", "", strtolower($value['name']) );
				$jquery_click_hook = "om-option-section-" . $jquery_click_hook;
				$menu .= '<li><a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">'.  $value['name'] .'</a></li>';
				$output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
			break;
			
		} 
		
		if ( $value['type'] != "heading" ) { 
			if ( $value['type'] != "checkbox" ) 
				$output .= '<br/>';
			if(!isset($value['desc']))
				$explain_value = '';
			else
				$explain_value = $value['desc']; 
				
			$output .= '</div><div class="explain">'. $explain_value .'</div>';
			$output .= '<div class="clear"> </div></div></div>';
		}
	   
	}

	$output .= '</div>';
	return array('options'=>$output,'menu'=>$menu);

}

/*************************************************************************************
 *	Options Uploader
 *************************************************************************************/

function om_options_uploader_generator($id,$std,$mod,$skip_db=false,$thumb=false) {
    
	$uploader = '';
  if($skip_db)
  	$upload=$std;
	else
  	$upload = get_option($id);
  
  $strip_id=str_replace(']','',str_replace('[','_',$id));
	
	if($mod != 'min') { 
		$val = $std;
		$tmp=get_option( $id );
    if ( $tmp != "")
    	$val = get_option($id); 
    	     
    $uploader .= '<input name="'. $id .'" id="'. $strip_id .'_upload" type="text" value="'. $val .'" />';
	}
	
	if($skip_db)
		$class_name='image_upload_button_only';
	else
		$class_name='image_upload_button';
		
	$data_ids='';
	if($thumb) {
		$data_ids=' data-thumb="true" data-width="'.$thumb['width'].'" data-height="'.$thumb['height'].'" data-crop="'.$thumb['crop'].'"';
	}
		
	$uploader .= '<div class="upload_button_div"><span class="button '.$class_name.'" id="'.$strip_id.'" '.$data_ids.'>Upload Image</span>';
	
	if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}

	if($skip_db)
		$class_name='image_reset_button_only';
	else
		$class_name='image_reset_button';
			
	$uploader .= '<span class="button '.$class_name.' '. $hide.'" id="reset_'. $strip_id .'" title="' . $strip_id . '">Remove</span>';
	$uploader .='</div>';
  $uploader .= '<div class="clear"></div>';
	if(!empty($upload)) {
		$uploader .= '<a href="'. $upload . '">';
		$uploader .= '<img class="om-option-image" id="image_'.$strip_id.'" src="'.$upload.'" alt="" />';
		$uploader .= '</a>';
	}
	$uploader .= '<div class="clear"></div>' . "\n"; 


	return $uploader;
}

/*************************************************************************************
 *	Export Options
 *************************************************************************************/
 
function om_options_export_dump() {

	$options =  get_option(OM_THEME_PREFIX.'options_template');

	$output = array('theme_prefix' => OM_THEME_PREFIX, 'options' => array());
	
	foreach ($options as $value) {
	   
	  if(isset($value['id']) && $value['id'])
	  {
	  	$output['options'][$value['id']] = get_option($value['id']);
	  }
  
	}

	return serialize($output);
}

?>
