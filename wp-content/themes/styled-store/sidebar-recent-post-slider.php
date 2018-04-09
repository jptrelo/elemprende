<?php
/**
 * The sidebar contains homepage recnet post section
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 To change footer sidebar,make a copy of this template sidebar-recent-post-slider on child theme. Happy Customize
 * @package styledstore
 */

 if ( ! is_active_sidebar( 'styledstore-recent-post-slider-section'  ) ) :

	return;

endif; ?>

<div class="st_block_71">
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar( 'styledstore-recent-post-slider-section' ); ?>
		</div>
	</div>
</div>

