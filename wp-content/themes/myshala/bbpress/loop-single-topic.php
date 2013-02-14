<?php

/**
 * Topics Loop - Single
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<li class="bbp-body forum-li">
	<div class="thread-poster">
		<?php printf( __( ' %1$s', 'bbpress' ), bbp_get_topic_author_link( array( 'size' => '20', 'type' => 'avatar' ) ) ); ?>
	</div>
	<div class="thread-info">
		<div class="thread-title">
			<h4 class="item-title">
				<?php do_action( 'bbp_theme_before_topic_title' ); ?>

				<a class="bbp-topic-permalink topic-title" href="<?php bbp_topic_permalink(); ?>" title="<?php bbp_topic_title(); ?>"><?php bbp_topic_title(); ?></a>

				<?php do_action( 'bbp_theme_after_topic_title' ); ?>
			</h4>
		</div>
		<div class="thread-group">
			<div class="object-name"> 
				<?php //if ( !bbp_is_single_forum() || ( bbp_get_topic_forum_id() != bbp_get_forum_id() ) ) : ?>

					<?php do_action( 'bbp_theme_before_topic_started_in' ); ?>

					<?php printf( __( 'Posted in: <a href="%1$s">%2$s</a>', 'bbpress' ), bbp_get_forum_permalink( bbp_get_topic_forum_id() ), bbp_get_forum_title( bbp_get_topic_forum_id() ) ); ?>

					<?php do_action( 'bbp_theme_after_topic_started_in' ); ?>

				<?php //endif; ?>
			</div>
		</div>
		<div class="thread-post-users">
			<span class="poster-name thread-creator">
				<?php do_action( 'bbp_theme_before_topic_started_by' ); ?>

					<?php printf( __( 'Creator: %1$s', 'bbpress' ), bbp_get_topic_author_link( array( 'type' => 'name' ) ) ); ?>

				<?php do_action( 'bbp_theme_after_topic_started_by' ); ?>
			</span>
			<span class="sep">/</span>
			<span class="poster-name latest-reply">
				<?php do_action( 'bbp_theme_before_topic_freshness_author' ); ?>

					Latest: 
					<?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'type' => name ) ); ?>

				<?php do_action( 'bbp_theme_after_topic_freshness_author' ); ?>
			</span>					
		</div>
	</div>
	<div class="thread-history">
		<div class="thread-postcount">
			<span class="postCount"><?php bbp_show_lead_topic() ? bbp_topic_reply_count() : bbp_topic_post_count(); ?></span>
			<span class="replies">
				<?php 
					echo (bbp_get_topic_post_count() > 1)?'replies':'reply'; 
				?>
			</span>
			<div class="clearfix clear"></div>
		</div>
		<div class="thread-freshness">
			<?php do_action( 'bbp_theme_before_topic_freshness_link' ); ?>

			<?php bbp_topic_freshness_link(); ?>

			<?php do_action( 'bbp_theme_after_topic_freshness_link' ); ?>
		</div>
	</div>				
	<div class="clearfix clear"></div>
</li>

<!-- #topic-<?php bbp_topic_id(); ?> -->