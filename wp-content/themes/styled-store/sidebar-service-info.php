<?php
/**
 * The sidebar contains service information 
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * To change footer sidebar,make a copy of this template sidebar-service-info.php on child theme. Happy Customize
 * @package styledstore
 * @return render content just below category section
 */

if ( ! is_active_sidebar( 'styledstore-service-section'  ) ) :

	return;

endif; ?>

<div class="st_store_services_info">
	<div class="container">
		<div class="row">

		<?php dynamic_sidebar( 'styledstore-service-section' ); ?>

		</div>
	</div>
</div>