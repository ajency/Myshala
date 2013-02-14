<?php 
if(isset($_POST['fileUploaded']))
{
	global $bp;

	if ($_FILES["file"]["error"] > 0)
	{
		$error = acg_file_upload_error($_FILES["file"]["error"]);
		acg_file_upload_error_display($error);
		avatar_upload_form();
	}
	else
	{
		if(bp_core_check_avatar_size( $_FILES ))
		{
			if(bp_core_check_avatar_type($_FILES))
			{
				//add_filter('bp_core_avatar_upload_path','agc_xprofile_avatar_upload_dir',1);
				//add_filter('bp_core_avatar_url','agc_xprofile_avatar_upload_url',1);
				
				$original = bp_core_avatar_handle_upload( $_FILES);
	
				// Check image size and shrink if too large
				if ( $original)
				{
					global $bp;
					
					$image = @getimagesize( bp_core_avatar_upload_path() . $bp->avatar_admin->image->dir );
					$aspect_ratio = 1;
					
					$full_height = bp_core_avatar_full_height();
					$full_width  = bp_core_avatar_full_width();
					
					// Calculate Aspect Ratio
					if ( $full_height && ( $full_width != $full_height ) )
						$aspect_ratio = $full_width / $full_height;
					
					$width  = $image[0] / 2;
					$height = $image[1] / 2;
					
					?>
											<div class="step" id="step-1">		
								  	  			<div class="clearfix"></div>
												<!-- This is the form that our event handler fills -->
												<form class="form-horizontal" action="" method="post" id="form-upload-avatar" enctype="multipart/form-data">
													<fieldset>
														<h3>
															Crop Avatar
														</h3>
														<p>Let's crop your avatar for your profile!</p>
														
														<input type="hidden" id="x" name="x" />
														<input type="hidden" id="y" name="y" />
														<input type="hidden" id="w" name="w" />
														<input type="hidden" id="h" name="h" />
														<input type="hidden" id="image_src" name="image_src" value="<?php echo bp_get_avatar_to_crop_src(); ?>"/>
														
														<div class="cropbox" style="width:<?php echo $image[0];?>px;">
					             							<img src="<?php bp_avatar_to_crop(); ?>" id="cropbox" />
					             						</div>  
					             					
					             						<div class="cropview">
					             							<img src="<?php bp_avatar_to_crop(); ?>" style="max-width:<?php echo $image[0];?>px;" id="avatar-crop-preview" alt="Avatar preview" />
					             						</div>
														
														<div class="control-group">
													 		<label class="control-label"></label>
														    <!-- Button -->
															<div class="controls">
																<div class="next-step button size-small" id="fileCropped">Crop Image &amp; Upload</div>
																<span class="loading-16" id="loading-acgUploadCroppedAvatar" style="display:none;vertical-align:middle;margin-left:10px"></span>
															</div>
														</div>
														</fieldset>
													</form>
												
											</div>
								
									<script type="text/javascript">

									jQuery(document).ready(function(){
										
										jQuery('#cropbox').Jcrop({
											onChange: showPreview,
											onSelect: showPreview,
											onSelect: updateCoords,
											aspectRatio: <?php echo $aspect_ratio ?>,
											setSelect: [ 0, 0, <?php echo $width;?>,<?php echo $height?>],
											minSize:[150,150]
										});
										updateCoords({x: 0, y: 0, w: <?php echo $width;?>, h: <?php echo $height?>});
									});
								
										function updateCoords(c) {
											jQuery('#x').val(c.x);
											jQuery('#y').val(c.y);
											jQuery('#w').val(c.w);
											jQuery('#h').val(c.h);
										}
								
										function showPreview(coords) {
											if ( parseInt(coords.w) > 0 ) {
												var rx = <?php echo $full_width; ?> / coords.w;
												var ry = <?php echo $full_height; ?> / coords.h;
								
												jQuery('#avatar-crop-preview').css({
												<?php if ( $image ) : ?>
													width	: Math.round(rx * <?php echo $image[0]; ?>) + 'px',
													height	: Math.round(ry * <?php echo $image[1]; ?>) + 'px',
												<?php endif; ?>
													marginLeft	: '-' + Math.round(rx * coords.x) + 'px',
													marginTop	: '-' + Math.round(ry * coords.y) + 'px',
												});
											}
										}
										
										function checkCoords()
										{
											if (parseInt(jQuery('#w').val())) return true;
											alert('Please select a crop region then press submit.');
											return false;
										};
	
										jQuery(document).ready(function(){
										
											jQuery('#fileCropped').click(function(event){
												event.preventDefault();
					
													if(checkCoords())
													{	
														jQuery('#loading-acgUploadCroppedAvatar').show();
																var crop_data = 
																{
																		'step'			: 1,
																		'x' 		 	: jQuery('#x').val(),
																		'y' 		 	: jQuery('#y').val(),
																		'w' 		 	: jQuery('#w').val(),
																		'h' 		 	: jQuery('#h').val(),
																		'image_src'		: jQuery('#image_src').val(),
																		action	 :'agc_set_core_avatar_image',
																};
														
														jQuery.post(ajaxurl, crop_data, function(response) {
							
																	if(response.status == 'success')
																	{
																		alert(response.msg);
																		jQuery('div#step-1').hide();
																		<?php for($i = 1; $i <= $agc_login_step; $i++ ):?>
																			jQuery('div#step-<?php echo $i?>').hide();
																		<?php endfor;?>
																		jQuery('#loading-acgUploadCroppedAvatar').hide();
																		jQuery('div#step-2').show();
																		jQuery('#bubbles').progressBubbles('progress');
																			 
																	}
																	else
																	{
																		//console.log(response);
																		alert(response.msg);
																		jQuery('#loading-step-1').hide();
																		window.location.href = '<?php echo get_bloginfo('url');?>/login-steps/';
																	}	
																});
													}
												});
										});			
										
										</script>
					<?php 	
				}
				else
				{
					acg_file_upload_error_display('Please upload a image with dimensions greater than '. bp_core_avatar_original_max_width() .'x'.bp_core_avatar_original_max_width());
					avatar_upload_form();
				}
					
							
			}
			else
			{
					acg_file_upload_error_display('Only .jpg , .gif or .png file types allowed.');
					avatar_upload_form();
			}
			
		}
		else
		{
			acg_file_upload_error_display('File exceeds the maximum allowable size limit.');
			avatar_upload_form();
		}
	}	
}

if(!isset($_POST['fileUploaded']))
{
	avatar_upload_form();
}

function avatar_upload_form()
{
	//var_dump($_SERVER);
	?>
<div class="step" id="step-1">									
			<form class="form-horizontal" action="" method="post" id="form-upload-avatar" enctype="multipart/form-data">
				<fieldset>
					<h3>
						Upload Avatar
					</h3>
					<p class="mar-bot">Let's get your profile up-to-date and looking good!</p>
					<div class="control-group">
						<label class="control-label">Choose Avatar</label>
						<div class="controls">
						
							<input class="input-file" id="fileInput" type="file" name="file">
							<input type="hidden" name="fileUploaded" value="acg_avatar_upload"/>
							<input type="hidden" name="action" value="bp_avatar_upload"/>
							 
							<p class="help-block meta">Upload a picture for your profile.</p>
						
						</div>
					</div>
					<div class="control-group">
								 
						 <label class="control-label"></label>

						 <!-- Button -->
						<div class="controls">
							<div class="next-step button size-small" id="acgUploadAvatar">Upload</div>
							<span class="loading-16" id="loading-acgUploadAvatar" style="display:none;vertical-align:middle;margin-left:10px"></span>
						</div>
					</div>

				</fieldset>
			</form>
			<script type="text/javascript">
				jQuery(document).ready(function(){
						jQuery('#acgUploadAvatar').click(function(){
								jQuery('#loading-acgUploadAvatar').show();
								jQuery('#form-upload-avatar').submit();
							});
					});
			</script>
		</div><!-- /#step-1 -->
<?php }
function acg_file_upload_error_display($error_msg)
{?>
	<div class="alert alert-block alert-error fade in">
       <button type="button" class="close" data-dismiss="alert">&times;</button>
       <h4 class="alert-heading"><?php printf(__('Oh snap! You got an error!','buddypress'));?></h4>
       <p><?php printf(__('%s','buddypress'),$error_msg);?></p>
    </div>
<?php }?>
