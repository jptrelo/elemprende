<?php
/**
* Loads all the components related to customizer 
*
* @since Bizplan 0.1
*/
require get_parent_theme_file_path( '/modules/customizer/framework/customizer.php' );
require get_parent_theme_file_path( '/modules/customizer/panels/panels.php' );
require get_parent_theme_file_path( '/modules/customizer/sections/sections.php' );

require get_parent_theme_file_path( '/modules/customizer/settings/general.php' );
require get_parent_theme_file_path( '/modules/customizer/settings/frontpage.php' );
require get_parent_theme_file_path( '/modules/customizer/defaults/defaults.php' );


function bizplan_modify_default_settings( $wp_customize ){

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
	$wp_customize->get_control( 'background_color' )->label = esc_html__( 'Background', 'bizplan' );

	$wp_customize->get_control( 'background_color' )->description = esc_html__( 'This option will work when your homepage displays setting is your latest posts.', 'bizplan' );
	$wp_customize->get_control( 'display_header_text' )->description = esc_html__( 'Site Title and Tagline will not show if you select logo.', 'bizplan' );
}
add_action( 'bizplan_customize_register', 'bizplan_modify_default_settings' );

function bizplan_default_styles(){

	$show_title         = bizplan_get_option( 'show_title' );
	$show_tagline       = bizplan_get_option( 'show_tagline' );
	$site_title_color   = bizplan_get_option( 'site_title_color' );
	$site_tagline_color = bizplan_get_option( 'site_tagline_color' );
	$primary_color      = bizplan_get_option( 'site_primary_color' );

	$slider_control     = bizplan_get_option( 'slider_control' );
	$menu_padding_top   = bizplan_get_option( 'menu_padding_top' );


	?>
	<style type="text/css">
		
		.offcanvas-menu-open .kt-offcanvas-overlay {
		    position: fixed;
		    width: 100%;
		    height: 100%;
		    background: #2527273b;
		    opacity: 1;
		    z-index: 9;
		    top: 0px;
		}

		.kt-offcanvas-overlay {
		    width: 0;
		    height: 0;
		    opacity: 0;
		    transition: opacity 0.5s;
		}
		
		#primary-nav-container{
			padding-top: <?php echo esc_attr( $menu_padding_top ) . 'px'; ?>;
		}

		.wrap-inner-banner {
			background-image: url( '<?php echo esc_url( bizplan_get_banner_url() ); ?>' );
		}
		<?php if( is_admin_bar_showing() ): ?>
			.site-header {
				padding-top: 47px !important;
			}
			@media screen and (max-width: 782px){
				.site-header {
					padding-top: 61px !important;
				}
			}
		<?php endif; ?>

		<?php if( !$slider_control ): ?>
			.block-slider .controls, .block-slider .owl-pager{
				opacity: 0;
			}
		<?php endif; ?>

		<?php if( ! $show_title ): ?>
			.site-title{
				display: none;
			}
		<?php endif; ?>

		<?php if( ! $show_tagline ): ?>
			.site-description{
				display: none;
			}
		<?php endif; ?>
		
		/*======================================*/
		/* Site title */
		/*======================================*/
		.site-header .site-branding .site-title a,
		.site-header .site-branding a {
			color: <?php echo esc_attr( $site_title_color ); ?>;
		} 

		/*======================================*/
		/* Tagline title */
		/*======================================*/
		.site-header .site-branding .site-description {
			color: <?php echo esc_attr( $site_tagline_color ); ?>;
		}

		/*======================================*/
		/* Primary color */
		/*======================================*/

		.block-grid .post-content .post-thumb-outer .post-detail a.date{
			background-color: <?php echo esc_attr( bizplan_hex2rgba( $primary_color, 0.8 ) ); ?>
		}
		.site-header .header-bottom-right .cart-icon a .count,
		#go-top span,
		.icon-block-outer .list-inner .icon-area .icon-outer:hover,
		.icon-block-outer .list-inner .icon-area .icon-outer:focus,
		.icon-block-outer .list-inner .icon-area .icon-outer:active,
		article.hentry.sticky .post-format-outer > span a {
			background-color: <?php echo esc_attr( $primary_color ); ?>
		}
		
		.icon-block-outer .list-inner .icon-area .icon-outer:hover,
		.icon-block-outer .list-inner .icon-area .icon-outer:focus,
		.icon-block-outer .list-inner .icon-area .icon-outer:active {
			-webkit-box-shadow-color: 0 0 0 10px <?php echo esc_attr( $primary_color ); ?>;
		       -moz-box-shadow: 0 0 0 10px <?php echo esc_attr( $primary_color ); ?>;
		        -ms-box-shadow: 0 0 0 10px <?php echo esc_attr( $primary_color ); ?>;
		         -o-box-shadow: 0 0 0 10px <?php echo esc_attr( $primary_color ); ?>;
		            box-shadow: 0 0 0 10px <?php echo esc_attr( $primary_color ); ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'bizplan_default_styles' );

/**
* Load customizer preview js file
*/
function bizplan_customize_preview_js() {
	wp_enqueue_script( 'bizplan-customize-preview', get_theme_file_uri( '/assets/js/customizer/customize-preview.js' ), array( 'jquery', 'customize-preview'), '1.0', true );
}
add_action( 'customize_preview_init', 'bizplan_customize_preview_js' );