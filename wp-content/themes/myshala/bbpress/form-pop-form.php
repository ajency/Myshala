<?php

/**
 * New Topic Slide
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<a href="#" id="add-topic-button" title="Add New Topic" class="button size-small">Add New Topic</a>

<div class="form-slide" id="add-topic">
	<?php bbp_get_template_part( 'form', 'topic' ); ?>
</div>

<script>
jQuery(document).ready(function() {
	jQuery('.form-slide').hide();
	jQuery('#add-topic-button').click(function() {
		jQuery('.form-slide').slideToggle('slow');
		return false;
	});
});
</script>