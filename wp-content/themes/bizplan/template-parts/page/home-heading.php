<?php
/**
* Template for Home page's Heading Section
* @since Bizplan 0.1
*/
?>
<div class="container">
	<div class="section-title-group">
		<h2 class="section-title"><?php echo esc_html( bizplan_remove_pipe( get_the_title(), true ) ); ?></h2>
		<?php if( $args[ 'divider' ] ): ?>
			<div class="maintitle-divider"></div>
		<?php endif; ?>

		<?php if( $args[ 'sub_title' ] ): ?>
			<div class="sub-title"><?php the_content(); bizplan_edit_link(); ?></div>
		<?php endif; ?>
	</div>
</div>