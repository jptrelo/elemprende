<?php
/*
 * Header For Photoshoot Theme.
 */
global $photoshoot_options; ?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php if(!empty($photoshoot_options['favicon'])) { ?>
    <link rel="shortcut icon" href="<?php echo esc_url($photoshoot_options['favicon']);?>">
    <?php } ?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
  <?php if(get_header_image()){ ?>
      <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" class="photoshoot-custom-header" alt="" />
      <?php } ?>
	<div class="container photoshoot-container">
    	<div class="row">
        	<div class="col-md-3 logo">
            	<a href="<?php echo  esc_url(home_url()); ?>">
                <?php if(!empty($photoshoot_options['logo'])){ ?>
                <img src="<?php echo esc_url($photoshoot_options['logo']); ?>" alt="<?php _e('site logo','photoshoot'); ?>" class="img-responsive img-responsive-photoshoot">
                <?php }else{
                    echo get_bloginfo('name');
                } ?>
                </a>
            </div>
            <div class="col-md-9 photoshoot-nav">
            	<div class="navbar-header">
            <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle navbar-toggle-top sort-menu-icon collapsed" type="button"> <span class="sr-only"></span> <span class="icon_menu-square_alt2"></span></button>
          </div>
          <nav class="navbar-collapse collapse photoshoot-menu">
            <?php wp_nav_menu(array('theme_location'  => 'primary','container' => ' ')); ?>
            </nav>
            </div>
        </div>
    </div>
</header> ?>