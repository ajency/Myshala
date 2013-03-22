<?php

/**
 * BuddyPress - Users Home
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

get_header( 'buddypress' ); ?>

	<div id="content" class="block-6 no-mar content-with-sidebar">
		<div class="padder block-6 bg-color-main">
			<div class="block-inner">
			<?php do_action( 'bp_before_member_home_content' ); ?>

			<div id="item-header" role="complementary">

				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>

			</div><!-- #item-header -->

			<div id="item-nav" class="one-fifth">
				<div class="item-list-tabs tabs-st no-ajax" id="object-nav" role="navigation">
					</br>
					<ul style="background:none;" id="left_nav">

						<?php bp_get_displayed_user_nav(); ?>

						<?php do_action( 'bp_member_options_nav' ); ?>

					</ul>
					
				</div>
			</div><!-- #item-nav -->


			<div id="item-body" class="four-fifth last">

				<?php do_action( 'bp_before_member_body' );

				if ( bp_is_user_activity() || !bp_current_component() ) :
					locate_template( array( 'members/single/activity.php'  ), true );

				 elseif ( bp_is_user_blogs() ) :
					locate_template( array( 'members/single/blogs.php'     ), true );

				elseif ( bp_is_user_friends() ) :
					locate_template( array( 'members/single/friends.php'   ), true );

				elseif ( bp_is_user_groups() ) :
					locate_template( array( 'members/single/groups.php'    ), true );

				elseif ( bp_is_user_messages() ) :
					locate_template( array( 'members/single/messages.php'  ), true );

				elseif ( bp_is_user_profile() ) :
					locate_template( array( 'members/single/profile.php'   ), true );

				elseif ( bp_is_user_forums() ) :
					locate_template( array( 'members/single/forums.php'    ), true );

				elseif ( bp_is_user_settings() ) :
					locate_template( array( 'members/single/settings.php'  ), true );

				// If nothing sticks, load a generic template
				else :
					locate_template( array( 'members/single/plugins.php'   ), true );

				endif;

				do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->
			
			<div class="clear"></div>
			
			<?php do_action( 'bp_after_member_home_content' ); ?>

			</div> <!-- .block-inner -->
		</div><!-- .padder -->
	</div><!-- #content -->

	<div class="block-3 no-mar sidebar">
		<?php
			// alternative sidebar
			$alt_sidebar=intval(get_post_meta($post->ID, OM_THEME_SHORT_PREFIX.'sidebar', true));
			if($alt_sidebar && $alt_sidebar <= intval(get_option(OM_THEME_PREFIX."sidebars_num")) ) {
				if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'alt-sidebar-'.$alt_sidebar ) ) ;
			} else {
				get_sidebar();
			}
			?>
	</div>
	
	<!-- /Content -->
	
	<div class="clear anti-mar">&nbsp;</div>

<?php get_footer(); ?>
