<?php

/**
 * Pagination for pages of replies (when viewing a topic)
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_pagination_loop' ); ?>

<div class="bbp-pagination pagination">
	<div class="bbp-pagination-count pag-count">

		<?php bbp_topic_pagination_count(); ?>

	</div>

	<div class="bbp-pagination-links">

		<?php bbp_topic_pagination_links(); ?>

	</div>
	<div class="clearfix clear"></div>
</div><!-- /.pagination -->

<?php do_action( 'bbp_template_after_pagination_loop' ); ?>
