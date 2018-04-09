<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Best_Commerce
 */

?><?php
	/**
	 * Hook - best_commerce_action_doctype.
	 *
	 * @hooked best_commerce_doctype - 10
	 */
	do_action( 'best_commerce_action_doctype' );
?>
<head>
	<?php
	/**
	 * Hook - best_commerce_action_head.
	 *
	 * @hooked best_commerce_head - 10
	 */
	do_action( 'best_commerce_action_head' );
	?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	/**
	 * Hook - best_commerce_action_before.
	 *
	 * @hooked best_commerce_page_start - 10
	 * @hooked best_commerce_skip_to_content - 15
	 */
	do_action( 'best_commerce_action_before' );
	?>

	<?php
	  /**
	   * Hook - best_commerce_action_before_header.
	   *
	   * @hooked best_commerce_header_start - 10
	   */
	  do_action( 'best_commerce_action_before_header' );
	?>
		<?php
		/**
		 * Hook - best_commerce_action_header.
		 *
		 * @hooked best_commerce_site_branding - 10
		 */
		do_action( 'best_commerce_action_header' );
		?>
	<?php
	  /**
	   * Hook - best_commerce_action_after_header.
	   *
	   * @hooked best_commerce_header_end - 10
	   * @hooked best_commerce_add_primary_navigation - 20
	   * @hooked best_commerce_add_featured_carousel - 25
	   */
	  do_action( 'best_commerce_action_after_header' );
	?>

	<?php
	/**
	 * Hook - best_commerce_action_before_content.
	 *
	 * @hooked best_commerce_content_start - 10
	 */
	do_action( 'best_commerce_action_before_content' );
	?>
	<?php
	  /**
	   * Hook - best_commerce_action_content.
	   */
	  do_action( 'best_commerce_action_content' );
