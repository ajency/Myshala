<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<li class="bbp-body item-list-li">
	<article>
		<div class="poster-avatar">
			<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>

			<?php bbp_reply_author_link( array( 'type' => 'avatar' ) ); ?>

			<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>
		</div>											
		<div class="item-content-container">
			<div class="post-bubble-arrow"></div>												
			<header class="comment-header">
				<h4 class="poster-name">
					<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>

					<?php bbp_reply_author_link( array( 'type' => 'name' ) ); ?>

					<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>
				</h4> 
				<span class="said">said</span>
			</header>							
			<div class="post-content">
				<p>
					<?php do_action( 'bbp_theme_before_reply_content' ); ?>

					<?php bbp_reply_content(); ?>

					<?php do_action( 'bbp_theme_after_reply_content' ); ?>
				</p>							
			</div>						
			<footer class="post-footer item-footer">
				<div class="item-actions admin-links">
					<?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>

					<?php bbp_reply_admin_links(); ?>

					<?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>
				</div>
				<div class="date"><?php printf( __( '%1$s at %2$s', 'bbpress' ), get_the_date(), esc_attr( get_the_time() ) ); ?></div>
			</footer>	
		</div>
	</article>
	<div class="clearfix clear"></div>
</li><!-- .bbp-body -->