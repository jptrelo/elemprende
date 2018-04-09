<?php
/**
 * The sidebar containing footer top widget area
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * To change footer sidebar,make a copy of this template sidebar-footer-top.php on child theme. Happy Customize
 * @package styledstore
 * @return render content on footer top 1, footer top 2, footer top 3 and footer top 4  widget areas
 */

if ( ! is_active_sidebar( 'styled_store_footertop1'  )
	&& ! is_active_sidebar( 'styled_store_footertop2' )
	&& ! is_active_sidebar( 'styled_store_footertop3'  )
	&& ! is_active_sidebar( 'styled_store_footertop4'  )
	&& ! is_active_sidebar( 'styled_store_footertop5'  )
) :
	return;
endif; ?>

<!-- footer content -->
<div class="footer-top-area">
	<div class="container">
		<div class="row">
			<div class="footer-top-widgets clearfix" id="footer-top-widgets">
				<div id="fist-half-footer-toop-widgets" class="col-md-6 col-sm-12 clearfix">
					<?php if( is_active_sidebar( 'styled_store_footertop1' )) { ?>
						<div id="footer-top-1" class="<?php echo esc_attr( styledstore_footertopgroup() ); ?>" role="complementary">
								<?php dynamic_sidebar( 'styled_store_footertop1' ); ?>
						</div>
					<?php } 

					if( is_active_sidebar( 'styled_store_footertop2' )) { ?>
						<div id="footer-top-2" class="<?php echo esc_attr( styledstore_footertopgroup() ); ?>" role="complementary">
								<?php dynamic_sidebar( 'styled_store_footertop2' ); ?>
						</div>
					<?php }
					if( is_active_sidebar( 'styled_store_footertop3' )) { ?>
						<div id="footer-top-3" class="<?php echo esc_attr( styledstore_footertopgroup() ); ?>" role="complementary">
								<?php dynamic_sidebar( 'styled_store_footertop3' ); ?>
						</div>
					<?php } ?>
				</div>
				<div id="second-half-footer-top-widgets" class="col-md-6 col-sm-12 clearfix">
					<?php if( is_active_sidebar( 'styled_store_footertop4' )) { ?>
						<div id="footer-top-4" class="col-md-6 col-sm-6 col-xs-12"role="complementary">
								<?php dynamic_sidebar( 'styled_store_footertop4' ); ?>
						</div>
					<?php }
					if( is_active_sidebar( 'styled_store_footertop5' )) { ?>
						<div id="footer-top-5" class="col-md-6 col-sm-6 col-xs-12"role="complementary">
								<?php dynamic_sidebar( 'styled_store_footertop5' ); ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>	
	</div>
</div>