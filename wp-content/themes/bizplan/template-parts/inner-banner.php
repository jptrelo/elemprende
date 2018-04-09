<?php
/**
* Template for Inner Banner Section for all the inner pages
*
* @since Bizplan 0.1
*/
?>
<section class="wrapper wrap-inner-banner">
	<header class="page-header">
		<h1 class="page-title"><?php echo wp_kses_post( $args[ 'title' ] ); ?></h1>
		<?php if( $args[ 'description' ] ): ?>
				<div class="page-description">
					<?php echo wp_kses_post( $args[ 'description' ] ); ?>
				</div>
		<?php endif; bizplan_breadcrumb(); ?>
	</header>
</section>