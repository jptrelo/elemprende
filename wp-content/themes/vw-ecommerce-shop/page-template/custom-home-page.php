<?php
/**
 * Template Name: Custom Home
 */

get_header(); ?>
<div class="container">
	<div class="col-md-3 col-sm-3 padremove">
		<div class="categry-header"><i class="fa fa-bars" aria-hidden="true"></i><span><?php echo esc_html_e('ALL CATEGORIES','vw-ecommerce-shop'); ?></span></div>
		<?php
			$args = array(
		    //'number'     => $number,
		    'orderby'    => 'title',
		    'order'      => 'ASC',
		    'hide_empty' => 0,
		    'parent'  => 0
		    //'include'    => $ids
			);
			$product_categories = get_terms( 'product_cat', $args );
			$count = count($product_categories);
			if ( $count > 0 ){
			    foreach ( $product_categories as $product_category ) {
			    		$cat_id   = $product_category->term_id;
						$cat_link = get_category_link( $cat_id );
						$thumbnail_id = get_woocommerce_term_meta( $product_category->term_id, 'thumbnail_id', true ); // Get Category Thumbnail
						$image = wp_get_attachment_url( $thumbnail_id );
			    	if ($product_category->category_parent == 0) {
			    		?>
					 <li class="drp_dwn_menu"><a href="<?php echo esc_url(get_term_link( $product_category ) ); ?>">
					 	<?php
					 if ( $image ) {
					echo '<img class="thumd_img" src="' . esc_url( $image ) . '" alt="" />';
				}
					echo esc_html( $product_category->name ); ?></a></li>
					 <?php
					}			    
				}
			}
		?>
	</div>
	<div class="col-md-9 col-sm-9 padremove">
		<?php /** slider section **/ ?>
			<?php
			// Get pages set in the customizer (if any)
			$pages = array();
			for ( $count = 1; $count <= 5; $count++ ) {
			$mod = absint( get_theme_mod( 'vw_ecommerce_shop_slidersettings-page-' . $count ));
			if ( 'page-none-selected' != $mod ) {
			  $pages[] = $mod;
			}
			}
			if( !empty($pages) ) :
			  $args = array(
			    'post_type' => 'page',
			    'post__in' => $pages,
			    'orderby' => 'post__in'
			  );
			  $query = new WP_Query( $args );
			  if ( $query->have_posts() ) :
			    $count = 1;
			    ?>
				<div class="slider-main">
			    	<div id="slider" class="nivoSlider">
				      <?php
				        $vw_ecommerce_shop_n = 0;
						while ( $query->have_posts() ) : $query->the_post();
						  
						  $vw_ecommerce_shop_n++;
						  $vw_ecommerce_shop_slideno[] = $vw_ecommerce_shop_n;
						  $vw_ecommerce_shop_slidetitle[] = get_the_title();
						  $vw_ecommerce_shop_content[] = get_the_content();
						  $vw_ecommerce_shop_slidelink[] = esc_url(get_permalink());
						  ?>
						   <img src="<?php the_post_thumbnail_url('full'); ?>" title="#slidecaption<?php echo esc_attr( $vw_ecommerce_shop_n ); ?>" />
						  <?php
						$count++;
						endwhile;
						wp_reset_postdata();
				      ?>
				    </div>

				    <?php
				    $vw_ecommerce_shop_k = 0;
			      	foreach( $vw_ecommerce_shop_slideno as $vw_ecommerce_shop_sln )
			      	{ ?>
				      <div id="slidecaption<?php echo esc_attr( $vw_ecommerce_shop_sln ); ?>" class="nivo-html-caption">
				        <div class="slide-cap  ">
				          <div class="container">
				            <h2><?php echo esc_html($vw_ecommerce_shop_slidetitle[$vw_ecommerce_shop_k]); ?></h2>
				            <p><?php echo esc_html($vw_ecommerce_shop_content[$vw_ecommerce_shop_k]); ?></p>
				            <a class="read-more" href="<?php echo esc_url($vw_ecommerce_shop_slidelink[$vw_ecommerce_shop_k] ); ?>"><?php esc_html_e( 'SHOP NOW','vw-ecommerce-shop' ); ?></a>
				          </div>
				        </div>
				      </div>
				        <?php $vw_ecommerce_shop_k++;
				    } ?>
				</div>
			  <?php else : ?>
			      <div class="header-no-slider"></div>
			    <?php
			  endif;
			endif;
		?>
	</div>
	<div class="clearfix"></div>
</div>
<section id="our-products">
	<div class="container">
	    <div class="text-center">
	        <?php if( get_theme_mod('vw_ecommerce_shop_maintitle') != ''){ ?>     
	            <h3><?php echo esc_html(get_theme_mod('vw_ecommerce_shop_maintitle',__('Trending In Our Store','vw-ecommerce-shop'))); ?></h3>
	        <?php }?>
	    </div>
		<?php $pages = array();
		for ( $count = 0; $count <= 0; $count++ ) {
			$mod = absint( get_theme_mod( 'vw_ecommerce_shop_page' . $count ));
			if ( 'page-none-selected' != $mod ) {
			  $pages[] = $mod;
			}
		}
		if( !empty($pages) ) :
		  $args = array(
		    'post_type' => 'page',
		    'post__in' => $pages,
		    'orderby' => 'post__in'
		  );
		  $query = new WP_Query( $args );
		  if ( $query->have_posts() ) :
		    $count = 0;
				while ( $query->have_posts() ) : $query->the_post(); ?>
				    <div class="row box-image text-center">
				        <p><?php the_content(); ?></p>
				        <div class="clearfix"></div>
				    </div>
				<?php $count++; endwhile; ?>
		  <?php else : ?>
		      <div class="no-postfound"></div>
		  <?php endif;
		endif;
		wp_reset_postdata()?>
	    <div class="clearfix"></div> 
	</div>
</section>

<?php get_footer(); ?>