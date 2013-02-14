<?php do_action( 'agc_before_profile_edit_content' );
 
global $agc_login_step;

$agc_login_step = 2; // Used to get all the steps.

$agc_profile_group_ids = agc_get_xprofile_group_ids(); //Get all the profile group ids

if ( bp_has_profile('user_id='.bp_loggedin_user_id().'&hide_empty_groups=0&hide_empty_fields=0')) :

while ( bp_profile_groups() ) : bp_the_profile_group();?>


<div class="step" id="step-<?php echo $agc_login_step; ?>">

<form id="form-step-<?php echo $agc_login_step; ?>" action="<?php bp_the_profile_group_edit_form_action(); ?>" method="post" id="profile-edit-form" class="form-horizontal <?php bp_the_profile_group_slug(); ?>">

	<?php do_action( 'agc_before_profile_field_content' ); ?>

		<fieldset>
		
			<div id="legend" class="">
				<legend class=""><?php printf(__("%s","buddypress"),bp_get_the_profile_group_name());?></legend>
			</div>
		
		<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

		<?php global $field;?>
		
			<div class="control-group">
			
				<?php if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>

					<label  class="control-label"  for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( $field->is_required ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
					
					<div class="controls">
						<input type="text" placeholder="<?php bp_the_profile_field_name(); ?>" class="input-xlarge <?php if ( $field->is_required ) : ?><?php echo 'required' ?><?php endif; ?>" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>/>
						<p class="help-block meta"><?php bp_the_profile_field_description(); ?></p>
					</div>
					
				<?php endif; ?>

				<?php if ( 'textarea' == bp_get_the_profile_field_type() ) : ?>

					<label class="control-label" for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( $field->is_required ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
					<div class="controls">
						<div class="textarea">
							<textarea class="input-xlarge <?php if ( $field->is_required ) : ?><?php echo 'required' ?><?php endif; ?>" rows="5" cols="40" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>><?php bp_the_profile_field_edit_value(); ?></textarea>
							<p class="help-block meta"><?php bp_the_profile_field_description(); ?></p>
						</div>
					</div>		
				<?php endif; ?>

				<?php if ( 'selectbox' == bp_get_the_profile_field_type() ) : ?>

					<label class="control-label" for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( $field->is_required ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
					<div class="controls">
						<select class="input-xlarge <?php if ( $field->is_required ) : ?><?php echo 'required' ?><?php endif; ?>" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>>
							
							<?php bp_the_profile_field_options(); ?>
						
						</select>
						<p class="help-block meta"><?php bp_the_profile_field_description(); ?></p>
					</div>

				<?php endif; ?>

				<?php if ( 'multiselectbox' == bp_get_the_profile_field_type() ) : ?>

					<label class="control-label" for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( $field->is_required ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
					<div class="controls">
						<select class="input-xlarge <?php if ($field->is_required ) : ?><?php echo 'required' ?><?php endif; ?>" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" multiple="multiple" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>>

							<?php bp_the_profile_field_options(); ?>

						</select>
						<p class="help-block meta"><?php bp_the_profile_field_description(); ?></p>
					</div>	

					<?php if ( !$field->is_required ) : ?>

						<a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name(); ?>' );"><?php _e( 'Clear', 'buddypress' ); ?></a>

					<?php endif; ?>

				<?php endif; ?>

				<?php if ( 'radio' == bp_get_the_profile_field_type() ) : ?>

					
						<label class="control-label"><?php bp_the_profile_field_name(); ?> <?php if ( $field->is_required ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
						<div class="controls">
							
							<?php bp_the_profile_field_options(); ?>
							<p class="help-block meta"><?php bp_the_profile_field_description(); ?></p>
						
						</div>

				<?php endif; ?>

				<?php if ( 'checkbox' == bp_get_the_profile_field_type() ) : ?>
				
					<label class="control-label"><?php bp_the_profile_field_name(); ?> <?php if ( $field->is_required) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
					<div class="controls">
						
						<?php bp_the_profile_field_options(); ?>
						<p class="help-block meta"><?php bp_the_profile_field_description(); ?></p>
						
					</div>

				<?php endif; ?>

				<?php if ( 'datebox' == bp_get_the_profile_field_type() ) : ?>

					
					<label class="control-label" for="<?php bp_the_profile_field_input_name(); ?>_date"><?php bp_the_profile_field_name(); ?> <?php if ( $field->is_required ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
					
					<div class="controls">
						
						<div class="input-append date" id="<?php bp_the_profile_field_input_name(); ?>_dp" data-date="<?php echo date('d-m-Y');?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
							<input class="span2 <?php if ( $field->is_required ) : ?><?php echo 'required' ?><?php endif; ?>" size="16" type="text"  name="<?php bp_the_profile_field_input_name(); ?>_date" value="<?php echo date('d-m-Y',strtotime(bp_get_the_profile_field_edit_value())); ?>">
							<span class="add-on"><i class="icon-calendar"></i></span>
							
						</div>
						<p class="help-block meta"><?php bp_the_profile_field_description(); ?></p>
						
						<!-- datepicker plugin -->
						<script>
						jQuery(document).ready(function() {
								jQuery('#<?php bp_the_profile_field_input_name(); ?>_dp').datepicker();
							});
						 </script>
					</div>

				<?php endif; ?>
				
				<?php do_action( 'agc_custom_profile_edit_fields' ); ?>
			
			</div><!-- /.control-group -->

		<?php endwhile; ?>

	<?php do_action( 'agc_after_profile_field_content' ); ?>
	
	<input type="hidden" name="field_ids" id="field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />

	<?php wp_nonce_field( 'bp_xprofile_edit' ); ?>
	
			<div class="control-group">
				<label class="control-label"></label>
				<!-- Button -->
				<div class="controls">
					<div class="next-step button size-small" id="step<?php echo $agc_login_step;?>"><?php _e( 'Next', 'buddypress' ); ?></div>
					<span class="loading-16" id="loading-step-<?php echo $agc_login_step;?>" style="display:none;vertical-align:middle;margin-left:10px"></span>
				</div>
			</div>
	
		</fieldset>

	</form>
</div><!-- /#step-<?php echo $agc_login_step; ?> -->

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#step<?php echo $agc_login_step;?>').click(function()
			{
				jQuery('form#form-step-<?php echo $agc_login_step; ?>').validate({
					errorClass: "error-block",
					errorElement: "span",
					errorPlacement: function(error, element) {
					     element.parents('.controls').find('p').after(error);
					     element.parents('.control-group').addClass('error');
					   },  
					submitHandler: function(form) {
						jQuery('#loading-step-<?php echo $agc_login_step;?>').show();
						jQuery.post(ajaxurl,{
												action:'agc_ajax_bp_xprofile_save',
												fields: jQuery('#form-step-<?php echo $agc_login_step; ?>').serialize(),
												step  :<?php echo $agc_login_step; ?>
											},
											function(response){	
												
													<?php for($i = 1 ; $i <= count($agc_profile_group_ids) + 1 ; $i++):?>
													jQuery('div#step-<?php echo $i?>').hide();	
													<?php endfor?>											
													jQuery('#bubbles').progressBubbles('progress');
													jQuery('div#step-<?php echo $agc_login_step+1;?>').show();
													jQuery('#loading-step-<?php echo $agc_login_step;?>').hide();
																											
											});
					   }
					});
				jQuery('form#form-step-<?php echo $agc_login_step; ?>').submit();
			});

});
</script>

<?php  $agc_login_step += 1;?>	

<?php  endwhile; endif; ?>

<?php do_action( 'agc_after_profile_edit_content' ); ?>

