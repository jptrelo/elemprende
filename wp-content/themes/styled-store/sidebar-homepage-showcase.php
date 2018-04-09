<?php
/**
 * The sidebar contains service information 
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * To change footer sidebar,make a copy of this template sidebar-service-info.php on child theme. Happy Customize
 * @package styledstore-pro
 * @return render content just below category section
 */

if ( ! is_active_sidebar( 'styledstore-homepage-showcase-product'  ) ) :

	return;

endif; ?>

<section class="st_store_showcase_product">
	<div class="container-fluid">
		<div class="row">

			<?php dynamic_sidebar( 'styledstore-homepage-showcase-product' ); ?>

		</div>
	</div>
</section>