<?php 
/*
 * 404 Template File.
 */
get_header();
if (function_exists('photoshoot_custom_breadcrumbs')) photoshoot_custom_breadcrumbs();  ?>
<div class="detail-section">
	<div class="container photoshoot-container">
    	<div class="row">
			<div class="col-md-12">
				<p>
					<?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'photoshoot' ); ?>
				</p>
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</div>		
<?php get_footer(); ?>