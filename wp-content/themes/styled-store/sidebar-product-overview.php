<?php
/**
 * The sidebar contains homepage product overview section
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 To change footer sidebar,make a copy of this template sidebar-product-overview.php on child theme. Happy Customize
 * @package styledstore
 */
 if ( ! is_active_sidebar( 'product_overview'  ) ) :

	return;

endif; ?>

<div class="st_product_highlight">
	<div class="container">
		<div class="row">
			<!-- best sales -->
			<div class="st_product_overview clearfix">
				<?php dynamic_sidebar('product_overview'); ?>
			</div>
		</div>
	</div>
</div>
