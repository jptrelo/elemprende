<?php
/**
 * The template for displaying the footer
**/
 $booster_options = get_option( 'faster_theme_options' ); ?>
<!-- footer -->
<div class="copyright col-lg-12">
    <div class="container no-padding">
      <div class="col-md-7 footer-margin-top footer-center no-padding">
	  		<?php if(!empty($booster_options['footertext'])) { 
	  			echo wp_filter_nohtml_kses($booster_options['footertext']).'. ';
			}
			_e('Powered by ','booster'); ?><a href='http://fasterthemes.com/wordpress-themes/Booster' target='_blank'><?php _e('Booster WordPress Theme.','booster'); ?></a>
      </div>
       <?php $booster_footer = array(
			'theme_location'  => 'secondary',
			'container'       => 'div',
			'container_class' => 'col-md-5',
			'container_id'    => '',
			'menu_class'      => '',
			'menu_id'         => '',
			'echo'            => true,   
			'items_wrap'      => '<ul id="%1$s" class="%2$s nav nav-pills pull-right-res footer pull-right">%3$s</ul>',
		);
       if ( has_nav_menu('secondary')) { wp_nav_menu( $booster_footer ); } ?>
    </div>
  </div>
<!-- footer End-->
<?php wp_footer();?>
</body>
</html>