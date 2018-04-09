<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package VW Ecommerce Shop
 */

get_header(); ?>

<div id="content-vw">
	<div class="container">
        <div class="page-content">		
			<div class="col-md-12">
				 <h3><?php printf( '<strong>%s</strong> %s', esc_html__( '404', 'vw-ecommerce-shop' ), esc_html__( 'Not Found', 'vw-ecommerce-shop' ) ) ?></h3>
				<p class="text-404"><?php esc_html_e( 'Looks like you have taken a wrong turn&hellip', 'vw-ecommerce-shop' ); ?></p>
				<p class="text-404"><?php esc_html_e( 'Dont worry&hellip it happens to the best of us.', 'vw-ecommerce-shop' ); ?></p>
				<div class="read-moresec">
            		<div><a href="<?php echo esc_url(); ?>" class="button hvr-sweep-to-right"><?php esc_html_e( 'Return to the home page', 'vw-ecommerce-shop' ); ?></a></div>
					</div>
			</div>
			<div class="clearfix"></div>
        </div>
    <div class="clearfix"></div>
	</div>
</div>
<?php get_footer(); ?>