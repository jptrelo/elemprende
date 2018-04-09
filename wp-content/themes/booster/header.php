<?php
/**
 * The Header template for our theme
 */
 $booster_options = get_option( 'faster_theme_options' ); ?>
 <!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php if(!empty($booster_options['fevicon'])) { ?>
    <link rel="shortcut icon" href="<?php echo esc_url($booster_options['fevicon']);?>">
    <?php } ?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
	<div class="separator"></div>
  <div class="col-md-12">
    <div class="container no-padding">
    <?php if(get_header_image()){ ?>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img src="<?php header_image(); ?>" class="custom-header-img" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="">
	</a>
    <?php } ?>
      <div class="col-md-3 no-padding text-left-menu">
      		<?php if(empty($booster_options['logo'])) { ?>
      			<a href="<?php echo site_url(); ?>" class="pull-left booster-site-name"><?php echo get_bloginfo('name'); ?></a> 
            <?php } else { ?>
            	<a href="<?php echo site_url(); ?>" class="pull-left booster-site-name"><img src="<?php echo esc_url($booster_options['logo']); ?>" alt="" class="img-responsive header-logo" /></a> 
            <?php } ?>	
                <div class="navbar-header pull-right">
                	<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle toggle-top" type="button">
                    	<span class="sr-only"><?php _e('Toggle navigation','booster') ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
					</button>
				</div>
        <div class="clearfix"></div>
		</div>
      <div class="col-md-7 no-padding text-left-menu">
        <div class="navbar-collapse collapse padding-menu">
        <?php $booster_args = array(
					'theme_location'  => 'primary',
					'menu'            => '',
					'container'       => 'div',
					'container_class' => 'nav navbar-nav menu font-type-roboto',
					'container_id'    => '',
					'menu_class'      => 'nav navbar-nav',
					'menu_id'         => '',
					'echo'            => true,
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',    
					'items_wrap'      => '<ul id="%1$s" class="%2$s booster-menu">%3$s</ul>',
					'depth'           => 0,
					'walker'            => ''
				   );   
				   wp_nav_menu( $booster_args ); ?>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="col-md-2 no-padding text-left-menu">
        <div class="">
          <ul class="social-icon">
            <li><?php if(!empty($booster_options['fburl'])) { ?><a href="<?php echo esc_url($booster_options['fburl']); ?>"><img class="sp" src="<?php echo get_template_directory_uri();?>/images/fb.png" alt="" /></a><?php } ?></li>
            <li><?php if(!empty($booster_options['twitter'])) { ?><a href="<?php echo esc_url($booster_options['twitter']); ?>"><img class="sp" src="<?php echo get_template_directory_uri();?>/images/tw.png" alt="" /></a><?php } ?></li>
            <li><?php if(!empty($booster_options['linkedin'])) { ?><a href="<?php echo esc_url($booster_options['linkedin']); ?>"><img class="sp" src="<?php echo get_template_directory_uri();?>/images/in.png" alt="" /></a><?php } ?></li>         
          </ul>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</header>