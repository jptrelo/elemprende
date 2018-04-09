<?php
/**
 * The sidebar contains homepage promo section
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 To change footer sidebar,make a copy of this template sidebar-promo.php on child theme. Happy Customize
 * @package styledstore
 */
 if ( ! is_active_sidebar( 'styledstore-promo-section'  ) ) :

	return;

endif; ?>

<!-- promo ad-1 -->
<div class="st_promo_block">
	<div class="fullwidth-promo">
		<?php dynamic_sidebar( 'styledstore-promo-section' ); ?>
	</div>
</div>
