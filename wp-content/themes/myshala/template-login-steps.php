<?php
/*
* Template Name: Login Steps Template
*/
?>
<?php
get_header(); ?>

<div class="block-full content-without-sidebar">
	<div class="block-inner">
		<div id="log-info">
			<div id="bubbles"></div>
		
			<div id="log-info-forms">
				<!-- Upload Avatar Step -->
				<?php include_once TEMPLATEPATH .'/includes/custom-templates/step-upload-avatar.php';?>
				<!-- Xprofile Steps -->
				<?php include_once TEMPLATEPATH .'/includes/custom-templates/custom-xprofile-loop.php';?>
				<!-- Change Password Step -->
				<?php include_once TEMPLATEPATH .'/includes/custom-templates/step-change-password.php';?>		
			</div>
		</div>
	</div>
</div>
<div class="clear anti-mar">&nbsp;</div><div>
<?php get_footer(); ?>