<?php
/**
 * The template for displaying archived woocommerce products
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @since Bizplan 0.1
 */
get_header(); 
# Print banner with breadcrumb and post title.
bizplan_inner_banner(); 
?>
<section class="wrapper wrap-detail-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-8">
				<main id="main" class="post-detail-content woocommerce-products" role="main">
					<?php if ( have_posts() ) :
						woocommerce_content();
					endif;
					?>
				</main>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</section>
<?php
get_footer();
