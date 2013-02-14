<?php
$tmp = explode( 'wp-content', __FILE__ );
$wp_path = $tmp[0];
require_once( $wp_path . '/wp-load.php' );

require_once( 'options.php' );

$shortcode = trim( $_GET['shortcode'] );
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
<div class="om-tmce-sortcode-container" id="om-tmce-sortcode-container">
	<input type="hidden" name="om-tmce-shortcode-type" id="om-tmce-shortcode-type" value="<?php echo $shortcode?>">
	<br/>
	<table class="form-table">
	<?php echo om_tmce_shortcode_options_machine($shortcode); ?>
	</table>
	<br/><br/>
	<a href="#" class="button-primary" id="om_tmce_insert_button"><?php _e('Insert Shortcode','om_theme') ?></a>
</div>
<script>om_tmce_popup_init();</script>
<script>
	function initPickers()
	{
		jQuery('#om-tmce-sortcode-container .om-option-color').each(function(){
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
</script>			
</body>
</html>