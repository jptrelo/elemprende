<?php
/**
 * Bizplan back compat functionality
 *
 * Prevents Bizplan from running on WordPress versions prior to 4.7,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.7.
 *
 * @since Bizplan 0.1
 */

/**
 * Prevent switching to Bizplan on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Bizplan 0.1
 */
function bizplan_switch_theme() {
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'bizplan_upgrade_notice' );
}
add_action( 'after_switch_theme', 'bizplan_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Bizplan on WordPress versions prior to 4.7.
 *
 * @since Bizplan 0.1
 * @global string $wp_version WordPress version.
 */
function bizplan_upgrade_notice() {
	$message = sprintf( esc_html__( 'Bizplan requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'bizplan' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 4.7.
 *
 * @since Bizplan 0.1
 *
 * @global string $wp_version WordPress version.
 */
function bizplan_customize() {
	wp_die( sprintf( esc_html__( 'Bizplan requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'bizplan' ), $GLOBALS['wp_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'bizplan_customize' );

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.7.
 *
 * @since Bizplan 0.1
 * @global string $wp_version WordPress version.
 */
function bizplan_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( esc_html__( 'Bizplan requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'bizplan' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'bizplan_preview' );
