<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Best_Commerce
 */

	/**
	 * Hook - best_commerce_action_after_content.
	 *
	 * @hooked best_commerce_content_end - 10
	 */
	do_action( 'best_commerce_action_after_content' );
?>

	<?php
	/**
	 * Hook - best_commerce_action_before_footer.
	 *
	 * @hooked best_commerce_add_footer_widgets - 5
	 * @hooked best_commerce_footer_start - 10
	 */
	do_action( 'best_commerce_action_before_footer' );
	?>
	<?php
	  /**
	   * Hook - best_commerce_action_footer.
	   *
	   * @hooked best_commerce_footer_copyright - 10
	   */
	  do_action( 'best_commerce_action_footer' );
	?>
	<?php
	/**
	 * Hook - best_commerce_action_after_footer.
	 *
	 * @hooked best_commerce_footer_end - 10
	 */
	do_action( 'best_commerce_action_after_footer' );
	?>

<?php
	/**
	 * Hook - best_commerce_action_after.
	 *
	 * @hooked best_commerce_page_end - 10
	 * @hooked best_commerce_footer_goto_top - 20
	 */
	do_action( 'best_commerce_action_after' );
?>

<?php wp_footer(); ?>
</body>
</html>
