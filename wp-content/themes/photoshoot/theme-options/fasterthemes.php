<?php
function photoshoot_options_init(){
 register_setting( 'photoshoot_options', 'photoshoot_theme_options', 'photoshoot_options_validate');
} 
add_action( 'admin_init', 'photoshoot_options_init' );
function photoshoot_options_validate( $input ) {
	 $input['logo'] = photoshoot_image_validation(esc_url_raw( $input['logo'] ));
	 $input['favicon'] = photoshoot_image_validation(esc_url_raw( $input['favicon'] ));
	 $input['footertext'] = sanitize_text_field( $input['footertext'] );
   $input['blogtitle'] = sanitize_text_field( $input['blogtitle'] );
	 $input['fburl'] = esc_url_raw( $input['fburl'] );
	 $input['twitter'] = esc_url_raw( $input['twitter'] );
	 $input['googleplus'] = esc_url_raw( $input['googleplus'] );
	 $input['rss'] = esc_url_raw( $input['rss'] );
	for($photoshoot_k=0; $photoshoot_k < 5 ;$photoshoot_k++ ):
		$input['slider-image-'.$photoshoot_k] = photoshoot_image_validation(esc_url_raw( $input['slider-image-'.$photoshoot_k] ));
		$input['slider-title-'.$photoshoot_k] = sanitize_text_field( $input['slider-title-'.$photoshoot_k] );
		$input['slider-caption-'.$photoshoot_k] = sanitize_text_field( $input['slider-caption-'.$photoshoot_k] );
	endfor;
  return $input;
}
function photoshoot_image_validation($photoshoot_imge_url){
	$photoshoot_filetype = wp_check_filetype($photoshoot_imge_url);
	$photoshoot_supported_image = array('gif','jpg','jpeg','png','ico');
	if (in_array($photoshoot_filetype['ext'], $photoshoot_supported_image)) {
		return $photoshoot_imge_url;
	} else {
	return '';
	}
}
function photoshoot_framework_load_scripts(){
	wp_enqueue_media();
	wp_enqueue_style( 'photoshoot_framework', get_template_directory_uri(). '/theme-options/css/fasterthemes_framework.css' ,false, '1.0.0');
	// Enqueue custom option panel JS
	wp_enqueue_script( 'options-custom', get_template_directory_uri(). '/theme-options/js/fasterthemes-custom.js', array( 'jquery' ) );
	wp_enqueue_script( 'media-uploader', get_template_directory_uri(). '/theme-options/js/media-uploader.js', array( 'jquery' ) );		
}
add_action( 'admin_enqueue_scripts', 'photoshoot_framework_load_scripts' );
function photoshoot_framework_menu_settings() {
	$photoshoot_menu = array(
		'page_title' => __( 'Photoshoot Theme Options', 'photoshoot'),
		'menu_title' => __('Theme Options', 'photoshoot'),
		'capability' => 'edit_theme_options',
		'menu_slug' => 'photoshoot_framework',
		'callback' => 'photoshoot_framework_page'
	);
	return apply_filters( 'photoshoot_framework_menu', $photoshoot_menu );
}
add_action( 'admin_menu', 'photoshoot_theme_options_add_page' ); 
function photoshoot_theme_options_add_page() {
	$photoshoot_menu = photoshoot_framework_menu_settings();
   	add_theme_page($photoshoot_menu['page_title'],$photoshoot_menu['menu_title'],$photoshoot_menu['capability'],$photoshoot_menu['menu_slug'],$photoshoot_menu['callback']);
} 
function photoshoot_framework_page(){ 
	global $select_options; 
	if ( ! isset( $_REQUEST['settings-updated'] ) ) 
	$_REQUEST['settings-updated'] = false; ?>
<div class="fasterthemes-themes">
  <form method="post" action="options.php" id="form-option" class="theme_option_ft">
    <div class="fasterthemes-header">
      <div class="logo">
        <?php $photoshoot_image=get_template_directory_uri().'/theme-options/images/logo.png';
        echo "<a href='http://fasterthemes.com' target='_blank'><img src='".$photoshoot_image."' alt='FasterThemes' /></a>"; ?>
      </div>
      <div class="header-right">
        <?php echo "<h1>". __( 'Theme Options', 'photoshoot' ) . "</h1>"; 			
			echo "<div class='btn-save'><input type='submit' class='button-primary' value='". __( 'Save Options', 'photoshoot' ) . "' /></div>"; ?>
      </div>
    </div>
    <div class="fasterthemes-details">
      <div class="fasterthemes-options">
        <div class="right-box">
          <div class="nav-tab-wrapper">
            <ul>
              <li><a id="options-group-1-tab" class="nav-tab basicsettings-tab" title="<?php _e('Basic Settings','photoshoot') ?>" href="#options-group-1"><?php _e('Basic Settings','photoshoot'); ?></a></li>
              <li><a id="options-group-2-tab" class="nav-tab homepagesettings-tab" title="<?php _e('Home Page Settings','photoshoot') ?>" href="#options-group-2"><?php _e('Home Page Settings','photoshoot'); ?></a></li>
              <li><a id="options-group-3-tab" class="nav-tab socialsettings-tab" title="<?php _e('Social Settings','photoshoot') ?>" href="#options-group-3"><?php _e('Social Settings','photoshoot'); ?></a></li>
				      <li><a id="options-group-4-tab" class="nav-tab profeatures-tab" title="Pro Settings" href="#options-group-4"><?php _e('PRO Theme Features','photoshoot'); ?></a></li>
            </ul>
          </div>
        </div>
        <div class="right-box-bg"></div>
        <div class="postbox left-box"> 
          <!-- F I N A L - - T H E M E - - O P T I O N -->
          <?php settings_fields( 'photoshoot_options' );
          $photoshoot_options = get_option( 'photoshoot_theme_options' ); ?>
          <!--  Basic Setting  -->
          <div id="options-group-1" class="group socialsettings faster-inner-tabs">
            <h3><?php _e('Basic Settings','photoshoot'); ?></h3>
            <div class="section theme-tabs theme-logo"> <a class="heading faster-inner-tab active" href="javascript:void(0)"><?php _e('Site Logo','photoshoot'); ?></a>
              <div class="faster-inner-tab-group active">
                <div class="ft-control">
                  <input id="logo-img" class="upload" type="text" name="photoshoot_theme_options[logo]" value="<?php if(!empty($photoshoot_options['logo'])) { echo esc_url($photoshoot_options['logo']); } ?>" placeholder="<?php _e('No file chosen','photoshoot'); ?>" />
                  <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','photoshoot'); ?>" />
                  <div class="screenshot" id="logo-image">
                    <?php if(!empty($photoshoot_options['logo'])) { echo "<img src='".esc_url($photoshoot_options['logo'])."' /><a class='remove-image'></a>"; } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="section theme-tabs theme-favicon"> <a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Favicon','photoshoot'); ?></a>
              <div class="faster-inner-tab-group">
                <div class="explain"><?php _e('Size of favicon should be exactly 32x32px for best results.','photoshoot'); ?></div>
                <div class="ft-control">
                  <input id="favicon-img" class="upload" type="text" name="photoshoot_theme_options[favicon]" value="<?php if(!empty($photoshoot_options['favicon'])) { echo esc_url($photoshoot_options['favicon']); } ?>" placeholder="<?php _e('No file chosen','photoshoot'); ?>" />
                  <input id="upload_image_button1" class="upload-button button" type="button" value="<?php _e('Upload','photoshoot'); ?>" />
                  <div class="screenshot" id="favicon-image">
                    <?php  if(!empty($photoshoot_options['favicon'])) { echo "<img src='".esc_url($photoshoot_options['favicon'])."' /><a class='remove-image'></a>"; } ?>
                  </div>
                </div>
              </div>
            </div>
            <div id="section-footertext" class="section theme-tabs"> <a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Copyright Text','photoshoot'); ?></a>
              <div class="faster-inner-tab-group">
                <div class="ft-control">
                  <div class="explain"><?php _e('Some text regarding copyright of your site, you would like to display in the footer.','photoshoot'); ?></div>
                  <input type="text" id="footertext" class="of-input" name="photoshoot_theme_options[footertext]" size="32"  value="<?php if(!empty($photoshoot_options['footertext'])) { echo esc_attr($photoshoot_options['footertext']); } ?>">
                </div>
              </div>
            </div>
            <div id="section-blogtitle" class="section theme-tabs"> <a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Blog Title','photoshoot'); ?></a>
              <div class="faster-inner-tab-group">
                <div class="ft-control">
                  <input type="text" id="blogtitle" class="of-input" name="photoshoot_theme_options[blogtitle]" size="32"  value="<?php if(!empty($photoshoot_options['blogtitle'])) { echo esc_attr($photoshoot_options['blogtitle']); } ?>">
                </div>
              </div>
            </div>
          </div>          
          <!--  Home Page settings  -->
          <div id="options-group-2" class="group faster-inner-tabs">
            <h3><?php _e('Home Page Slider','photoshoot'); ?></h3>
			      <?php for($photoshoot_i=0; $photoshoot_i < 5 ;$photoshoot_i++ ):
            if($photoshoot_i == 0) $photoshoot_class='active'; else $photoshoot_class=''; ?>
            <div class="section theme-tabs theme-slider-image">
            <a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Slider','photoshoot'); ?> <?php echo $photoshoot_i+1; ?></a>
            <div class="faster-inner-tab-group  <?php echo $photoshoot_class; ?>">
            <div class="explain"></div>
            <div class="ft-control">
            <input id="slider-image-<?php echo $photoshoot_i;?>" class="upload" type="text" name="photoshoot_theme_options[slider-image-<?php echo $photoshoot_i;?>]" value="<?php if(!empty($photoshoot_options['slider-image-'.$photoshoot_i])) { echo esc_url($photoshoot_options['slider-image-'.$photoshoot_i]); } ?>" placeholder="<?php _e('No file chosen','photoshoot'); ?>" />
            <input id="1upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','photoshoot'); ?>" />
            <div class="screenshot" id="slider-image-<?php echo $photoshoot_i;?>">
              <?php if(!empty($photoshoot_options['slider-image-'.$photoshoot_i])) { echo "<img src='".esc_url($photoshoot_options['slider-image-'.$photoshoot_i])."' /><a class='remove-image'></a>"; } ?>
            </div>
            </div>
            <div class="ft-control">
            <input type="text" id="slider-title-<?php echo $photoshoot_i;?>" class="of-input" name="photoshoot_theme_options[slider-title-<?php echo $photoshoot_i;?>]" size="75"  value="<?php if(!empty($photoshoot_options['slider-title-'.$photoshoot_i])) { echo esc_attr($photoshoot_options['slider-title-'.$photoshoot_i]); } ?>" placeholder="<?php _e('Slider title','photoshoot'); ?>">
            </div>
            <div class="ft-control">
            <input type="text" id="slider-caption-<?php echo $photoshoot_i;?>" class="of-input" name="photoshoot_theme_options[slider-caption-<?php echo $photoshoot_i;?>]" size="75"  value="<?php if(!empty($photoshoot_options['slider-caption-'.$photoshoot_i])) { echo esc_attr($photoshoot_options['slider-caption-'.$photoshoot_i]); } ?>" placeholder="<?php _e('Slider caption','photoshoot'); ?> ">
            </div>
            </div>            
            </div>
            <?php endfor; ?>
          </div>
          <!--  Social settings  -->
          <div id="options-group-3" class="group faster-inner-tabs">
            <div id="section-facebook" class="section theme-tabs"> <a class="heading faster-inner-tab active" href="javascript:void(0)"><?php _e('Facebook','photoshoot'); ?></a>
              <div class="faster-inner-tab-group active">
                <div class="ft-control">
                  <div class="explain"><?php _e('Facebook profile or page URL i.e.','photoshoot'); ?> http://facebook.com/username/ </div>
                  <input id="facebook" class="of-input" name="photoshoot_theme_options[fburl]" size="30" type="text" value="<?php if(!empty($photoshoot_options['fburl'])) { echo esc_url($photoshoot_options['fburl']); } ?>" />
                </div>
              </div>
            </div>
            <div id="section-twitter" class="section theme-tabs"> <a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Twitter','photoshoot'); ?></a>
              <div class="faster-inner-tab-group">
                <div class="ft-control">
                  <div class="explain"><?php _e('Twitter profile or page URL i.e.','photoshoot'); ?> http://www.twitter.com/username/</div>
                  <input id="twitter" class="of-input" name="photoshoot_theme_options[twitter]" type="text" size="30" value="<?php if(!empty($photoshoot_options['twitter'])) { echo esc_url($photoshoot_options['twitter']); } ?>" />
                </div>
              </div>
            </div>
            <div id="section-googleplus" class="section theme-tabs"> <a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Google','photoshoot'); ?>+</a>
              <div class="faster-inner-tab-group">
                <div class="ft-control">
                  <div class="explain"><?php _e('Google plus profile or page URL i.e.','photoshoot'); ?> https://plus.google.com/username/</div>
                  <input id="googleplus" class="of-input" name="photoshoot_theme_options[googleplus]" type="text" size="30" value="<?php if(!empty($photoshoot_options['googleplus'])) { echo esc_url($photoshoot_options['googleplus']); } ?>" />
                </div>
              </div>
            </div>
            <div id="section-rss" class="section theme-tabs"> <a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('RSS','photoshoot') ?></a>
              <div class="faster-inner-tab-group">
                <div class="ft-control">
                  <div class="explain"><?php _e('Rss profile or page URL i.e. ','photoshoot'); ?>https://rss.com/username/</div>
                  <input id="rss" class="of-input" name="photoshoot_theme_options[rss]" type="text" size="30" value="<?php if(!empty($photoshoot_options['rss'])) { echo esc_url($photoshoot_options['rss']); } ?>" />
                </div>
              </div>
            </div>
          </div>
          <div id="options-group-4" class="group faster-inner-tabs fasterthemes-pro-image">
          	<div class="fasterthemes-pro-header">
              <img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/photoshoot_logo.png" class="fasterthemes-pro-logo" />
              <a href="http://fasterthemes.com/wordpress-themes/photoshoot" target="_blank">
                <img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/buy-now.png" class="fasterthemes-pro-buynow" /></a>
              </div>
          	<img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/pro_features.png" />
          </div>
          <!--  End group  --> 
        </div>
      </div>
    </div>
    <div class="fasterthemes-footer">
      <ul>
        <li class="btn-save">
          <input type="submit" class="button-primary" value="<?php _e('Save Options','photoshoot'); ?>" />
        </li>
      </ul>
    </div>
  </form>
</div>
<div class="save-options">
  <h2><?php _e('Options saved successfully.','photoshoot'); ?></h2>
</div>
<?php } ?>