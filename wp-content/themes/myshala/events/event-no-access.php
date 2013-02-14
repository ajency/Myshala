<?php 
/**
 * This is a single event view.
 */

get_header(); ?>

<?php agc_display_blog_meta(get_current_blog_id());?>

<?php agc_blog_menu('blog_menu','main-menu');
global $event;
?>
		<div class="content-container">
			<div class="main-area span12">
				<h2 class="page-title">
							Oops!
				</h2>
				<?php if(!$event->user_has_access): ?>
				<div class="not-found">
					<div class="hero-unit">
					  <h1>No Permission:</h1>
					  <p>Sorry! It seems that the page you were looking for is unavailable. This could be due to a lot of different reasons, which we're sure you don't really want to get into right now. So, you could search our site for whatever you're looking for. Or just,</p>
					  <p>
						<a class="btn btn-info btn-large" href="<?php echo get_site_url();?>">
						  Go Home
						</a>
					  </p>
					</div>
				</div><!-- /.not-found -->
				<?php elseif($event->user_has_access && !$event->is_upcoming): ?>
				<div class="not-found">
					<div class="hero-unit">
					  <h1>You are late !!!! :P</h1>
					  <p>Sorry! It seems that the page you were looking for is unavailable. This could be due to a lot of different reasons, one of which can be that, <strong>you missed the event</strong>. So, you could search our site for whatever you're looking for. Or just,</p>
					  <p>
						<a class="btn btn-info btn-large" href="<?php echo get_site_url();?>">
						  Go Home
						</a>
					  </p>
					</div>
				</div><!-- /.not-found -->	
				<?php endif; ?>
			</div><!-- /.main-area -->
			<div class="clearfix"></div>
		</div><!-- /.content-container -->
		<script>
		jQuery(document).ready(function($) {

			jQuery('.full-width').horizontalNav();
			
			//jQuery('a[rel="tooltip"]').tooltip();
		});
    </script>
   
   <!-- Modal
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	    <h3 id="myModalLabel">Modal header</h3>
	  </div>
	  <div class="modal-body">
	    <p>One fine body</p>
	  </div>
	  <div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	    <button class="btn btn-primary">Save changes</button>
	  </div>
	</div> -->
		
<?php get_footer( ); ?>