<?php
/**
 * The header for our theme
 * This is the template that displays all of the <head> section and everything up.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @since Bizplan 0.1
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content">
			<?php echo esc_html__( 'Skip to content', 'bizplan' ); ?>
		</a>
		<?php get_template_part( 'template-parts/header/offcanvas', 'menu' ); ?>
		<header id="masthead" class="wrapper site-header" role="banner">
			<div class="container">
				<div class="row">
					<div class="col-xs-7 col-sm-9 col-md-4">
						<?php get_template_part( 'template-parts/header/site', 'branding' ); ?>
					</div>
					<div class="col-xs-5 col-sm-3 col-md-8" id="primary-nav-container">
						<div class="header-bottom-right">
							<span class="search-icon">
								<a href="#">
									<span class="kfi kfi-search" aria-hidden="true"></span>
								</a>
								<div id="search-form">
									<?php get_search_form(); ?>
								</div><!-- /#search-form -->
							</span>
							<?php if( class_exists( 'WooCommerce' ) ): ?>
								<span class="cart-icon">
									<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
										<span class="kfi kfi-cart-alt"></span>
										<span class="count">
											<?php echo absint( WC()->cart->get_cart_contents_count() ); ?>
										</span>
									</a>
								</span>
							<?php endif; ?>
							<span class="alt-menu-icon visible-sm">
								<a class="offcanvas-menu-toggler" href="#">
									<span class="kfi kfi-menu"></span>
								</a>
							</span>
						</div>
						<div class="wrap-nav main-navigation">
							<div id="navigation" class="hidden-xs hidden-sm">
							    <nav class="nav">
									<?php bizplan_get_menu( 'primary' ); ?>
							    </nav>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header><!-- #masthead -->
