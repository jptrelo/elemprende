<?php
function fasterthemes_options_init(){
 register_setting( 'ft_options', 'faster_theme_options', 'ft_options_validate');
} 
add_action( 'admin_init', 'fasterthemes_options_init' );
function ft_options_validate( $input ) {
	 $input['welcome-image'] = esc_url( $input['welcome-image'] );
	 $input['welcome-title'] = wp_filter_nohtml_kses( $input['welcome-title'] );
	 $input['welcome-content'] = wp_filter_nohtml_kses( $input['welcome-content'] );
	 
	 $input['why-chooseus-image'] = esc_url( $input['why-chooseus-image'] );
	 $input['why-chooseus-title'] = wp_filter_nohtml_kses( $input['why-chooseus-title'] );
	 $input['why-chooseus-content'] = wp_filter_nohtml_kses( $input['why-chooseus-content'] );
	 
	 $input['logo'] = esc_url( $input['logo'] );
	 $input['fevicon'] = esc_url( $input['fevicon'] );
	 $input['footertext'] = wp_filter_nohtml_kses( $input['footertext'] );
	 
	 $input['fburl'] = esc_url( $input['fburl'] );
	 $input['twitter'] = esc_url( $input['twitter'] );
	 $input['linkedin'] = esc_url( $input['linkedin'] );
	 
	 $input['first-slider-image'] = esc_url( $input['first-slider-image'] );
	 $input['first-slider-link'] = esc_url( $input['first-slider-link'] );
	 
	 $input['second-slider-image'] = esc_url( $input['second-slider-image'] );
	 $input['second-slider-link'] = esc_url( $input['second-slider-link'] );
	 
	 $input['third-slider-image'] = esc_url( $input['third-slider-image'] );
	 $input['third-slider-link'] = esc_url( $input['third-slider-link'] );
	 
	 $input['forth-slider-image'] = esc_url( $input['forth-slider-image'] );
	 $input['forth-slider-link'] = esc_url( $input['forth-slider-link'] );
	 
	 $input['fifth-slider-image'] = esc_url( $input['fifth-slider-image'] );
	 $input['fifth-slider-link'] = esc_url( $input['fifth-slider-link'] );
    return $input;
}
function fasterthemes_framework_load_scripts(){
	wp_enqueue_media();
	wp_enqueue_style( 'fasterthemes_framework', get_template_directory_uri(). '/theme-option/css/fasterthemes_framework.css' ,false, '1.0.0');
	wp_enqueue_style( 'fasterthemes_framework' );
	// Enqueue custom option panel JS
	wp_enqueue_script( 'options-custom', get_template_directory_uri(). '/theme-option/js/fasterthemes-custom.js', array( 'jquery' ) );
	wp_enqueue_script( 'media-uploader', get_template_directory_uri(). '/theme-option/js/media-uploader.js', array( 'jquery' ) );		
	wp_enqueue_script('media-uploader');
}
add_action( 'admin_enqueue_scripts', 'fasterthemes_framework_load_scripts' );
function fasterthemes_framework_menu_settings() {
	$booster_menu = array(
				'page_title' => __( 'FasterThemes Options', 'booster'),
				'menu_title' => __('Theme Options', 'booster'),
				'capability' => 'edit_theme_options',
				'menu_slug' => 'fasterthemes_framework',
				'callback' => 'fastertheme_framework_page'
				);
	return apply_filters( 'fasterthemes_framework_menu', $booster_menu );
}
add_action( 'admin_menu', 'theme_options_add_page' ); 
function theme_options_add_page() {
	$booster_menu = fasterthemes_framework_menu_settings();
   	add_theme_page($booster_menu['page_title'],$booster_menu['menu_title'],$booster_menu['capability'],$booster_menu['menu_slug'],$booster_menu['callback']);
} 
function fastertheme_framework_page(){ 
		global $select_options; 
		if ( ! isset( $_REQUEST['settings-updated'] ) ) 
		$_REQUEST['settings-updated'] = false;
		$booster_image=get_template_directory_uri().'/theme-option/images/logo.png';
		echo "<h1><img src='".$booster_image."' height='64px'  /> ". __( 'FasterThemes Options', 'booster' ) . "</h1>"; 
		if ( false !== $_REQUEST['settings-updated'] ) :
			echo "<div><p><strong>"._e( 'Options saved', 'booster' )."</strong></p></div>";
		endif; ?>
<div id="fasterthemes_framework-wrap" class="wrap">
  <h2 class="nav-tab-wrapper"> 
  		<a id="options-group-1-tab" class="nav-tab basicsettings-tab" title="<?php _e('Home Settings','booster') ?>" href="#options-group-1"><?php _e('Home Settings','booster'); ?></a> 
        <a id="options-group-2-tab" class="nav-tab thirdsettings-tab" title="<?php _e('Home Slider','booster') ?>" href="#options-group-2"><?php _e('Home Slider','booster'); ?></a>
        <a id="options-group-3-tab" class="nav-tab socialsettings-tab" title="<?php _e('Basic Settings','booster') ?>" href="#options-group-3"><?php _e('Basic Settings','booster'); ?></a>
  		<a id="options-group-4-tab" class="nav-tab thirdsettings-tab" title="<?php _e('Social Settings','booster') ?>" href="#options-group-4"><?php _e('Social Settings','booster'); ?></a>
  </h2>
  <div id="fasterthemes_framework-metabox" class="metabox-holder">
    <div id="fasterthemes_framework" class="postbox">
      <!--======================== F I N A L - - T H E M E - - O P T I O N ===================-->
      <form method="post" action="options.php" id="form-option" class="theme_option_ft">
        <?php settings_fields( 'ft_options' );  
		$booster_options = get_option( 'faster_theme_options' ); ?>
        <!-- First group -->
        <div id="options-group-1" class="group basicsettings">
          <h3><?php _e('Home Settings','booster') ?></h3>
          <div id="welcome-image" class="section section-text mini">
            <h4 class="heading"><?php _e('Welcome Image','booster'); ?></h4>
            <div class="option">
                 <div class="controls">
                <input id="welcome" class="upload" type="text" name="faster_theme_options[welcome-image]" value="<?php if(!empty($booster_options['welcome-image'])) { echo $booster_options['welcome-image']; } ?>" placeholder="<?php _e('No file chosen','booster'); ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','booster') ?>" />
                    <div class="screenshot" id="welcome-image">
                      <?php if(!empty($booster_options['welcome-image'])) { echo "<img src='".esc_url($booster_options['welcome-image'])."' /><a class='remove-image'></a>"; } ?>
                    </div>
                </div>
            </div>
          </div>
          <div id="welcome-title-div" class="section section-text mini">
            <h4 class="heading"><?php _e('Welcome Title','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="welcome-title" class="of-input" name="faster_theme_options[welcome-title]" type="text" size="30" value="<?php if(!empty($booster_options['welcome-title'])) { echo wp_filter_nohtml_kses($booster_options['welcome-title']); } ?>" />
              </div>
            </div>
          </div>
          <div id="welcome-content-div" class="section section-text mini">
            <h4 class="heading"><?php _e('Welcome Content','booster') ?></h4>
            <div class="option">
              <div class="controls">
                <textarea id="welcome-content" class="of-input" name="faster_theme_options[welcome-content]"><?php if(!empty($booster_options['welcome-content'])) { echo wp_filter_nohtml_kses($booster_options['welcome-content']); } ?></textarea>
              </div>
            </div>
          </div>
          <div id="why-choose-us-image" class="section section-text mini">
            <h4 class="heading"><?php _e('Why Choose us Image','booster'); ?></h4>
            <div class="option">
                 <div class="controls">
                <input id="why-choose-us" class="upload" type="text" name="faster_theme_options[why-chooseus-image]" value="<?php if(!empty($booster_options['why-chooseus-image'])) { echo esc_url($booster_options['why-chooseus-image']); } ?>" placeholder="<?php _e('No file chosen','booster') ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','booster') ?>" />
                    <div class="screenshot" id="logo-image">
                      <?php if(!empty($booster_options['why-chooseus-image'])) { echo "<img src='".$booster_options['why-chooseus-image']."' /><a class='remove-image'></a>"; } ?>
                    </div>
                </div>
            </div>
          </div>
          <div id="welcome-title-div" class="section section-text mini">
            <h4 class="heading"><?php _e('Why Choose us Title','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="why-chooseus-title" class="of-input" name="faster_theme_options[why-chooseus-title]" type="text" size="30" value="<?php if(!empty($booster_options['why-chooseus-title'])) { echo wp_filter_nohtml_kses($booster_options['why-chooseus-title']); } ?>" />
              </div>
            </div>
          </div>
          <div id="welcome-content-div" class="section section-text mini">
            <h4 class="heading"><?php _e('Why Choose us Content','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <textarea id="why-chooseus-content" class="of-input" name="faster_theme_options[why-chooseus-content]"><?php if(!empty($booster_options['why-chooseus-content'])) { echo wp_filter_nohtml_kses($booster_options['why-chooseus-content']); } ?></textarea>
              </div>
            </div>
          </div>
        </div>
        <!-- Second group -->
        <div id="options-group-2" class="group basicsettings">
          <h3><?php _e('First Slide','booster'); ?></h3>
          <div id="first-slider-image" class="section section-upload">
            <h4 class="heading"><?php _e('Slide Image','booster'); ?></h4>
            <div class="option">
                 <div class="controls">
                <input id="first-slider" class="upload" type="text" name="faster_theme_options[first-slider-image]" value="<?php if(!empty($booster_options['first-slider-image'])) { echo esc_url($booster_options['first-slider-image']); } ?>" placeholder="<?php _e('No file chosen','booster'); ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','booster'); ?>" />
                    <div class="screenshot" id="first-image">
                      <?php if(!empty($booster_options['first-slider-image'])) { echo "<img src='".esc_url($booster_options['first-slider-image'])."' /><a class='remove-image'></a>"; } ?>
                    </div>
                  </div>
                <div class="explain"><?php _e('Size of banner should be exactly 1200x400px for best results.','booster'); ?></div>
            </div>
          </div>
          <div id="first-slider-div" class="section section-text mini">
            <h4 class="heading"><?php _e('Slide Link','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="first-slider-link" class="of-input" name="faster_theme_options[first-slider-link]" type="text" size="30" value="<?php if(!empty($booster_options['first-slider-link'])) { echo esc_url($booster_options['first-slider-link']); } ?>" />
              </div>
            </div>
          </div>
          <h3><?php _e('Second Slide','booster'); ?></h3>
          <div id="second-slider-image" class="section section-upload">
            <h4 class="heading"><?php _e('Slide Image','booster'); ?></h4>
            <div class="option">
                 <div class="controls">
                <input id="second-slider" class="upload" type="text" name="faster_theme_options[second-slider-image]" value="<?php if(!empty($booster_options['second-slider-image'])) { echo esc_url($booster_options['second-slider-image']); } ?>" placeholder="<?php _e('No file chosen','booster'); ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','booster'); ?>" />
                    <div class="screenshot" id="second-image">
                      <?php if(!empty($booster_options['second-slider-image'])) { echo "<img src='".esc_url($booster_options['second-slider-image'])."' /><a class='remove-image'></a>"; } ?>
                    </div>
                </div>
                <div class="explain"><?php _e('Size of banner should be exactly 1200x400px for best results.','booster'); ?></div>
            </div>
          </div>
          <div id="second-slider-div" class="section section-text mini">
            <h4 class="heading"><?php _e('Slide Link','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="second-slider-link" class="of-input" name="faster_theme_options[second-slider-link]" type="text" size="30" value="<?php if(!empty($booster_options['second-slider-link'])) { echo esc_url($booster_options['second-slider-link']); } ?>" />
              </div>
            </div>
          </div>
          <h3><?php _e('Third Slide','booster'); ?></h3>
          <div id="third-slider-image" class="section section-upload">
            <h4 class="heading"><?php _e('Slide Image','booster') ?></h4>
            <div class="option">
                 <div class="controls">
                <input id="third-slider" class="upload" type="text" name="faster_theme_options[third-slider-image]" value="<?php if(!empty($booster_options['third-slider-image'])) { echo esc_url($booster_options['third-slider-image']); } ?>" placeholder="<?php _e('No file chosen','booster') ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','booster'); ?>" />
                    <div class="screenshot" id="third-image">
                      <?php if(!empty($booster_options['third-slider-image'])) { echo "<img src='".esc_url($booster_options['third-slider-image'])."' /><a class='remove-image'></a>"; } ?>
                    </div>
                </div>
                <div class="explain"><?php _e('Size of banner should be exactly 1200x400px for best results.','booster'); ?></div>
            </div>
          </div>
          <div id="third-slider-div" class="section section-text mini">
            <h4 class="heading"><?php _e('Slide Link','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="third-slider-link" class="of-input" name="faster_theme_options[third-slider-link]" type="text" size="30" value="<?php if(!empty($booster_options['third-slider-link'])) { echo esc_url($booster_options['third-slider-link']); } ?>" />
              </div>
            </div>
          </div> 
          <h3><?php _e('Forth Slide','booster'); ?></h3>
          <div id="forth-slider-image" class="section section-upload">
            <h4 class="heading"><?php _e('Slide Image','booster'); ?></h4>
            <div class="option">
                 <div class="controls">
                <input id="forth-slider" class="upload" type="text" name="faster_theme_options[forth-slider-image]" value="<?php if(!empty($booster_options['forth-slider-image'])) { echo esc_url($booster_options['forth-slider-image']); } ?>" placeholder="<?php _e('No file chosen','booster'); ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','booster'); ?>" />
                    <div class="screenshot" id="forth-image">
                      <?php if(!empty($booster_options['forth-slider-image'])) { echo "<img src='".esc_url($booster_options['forth-slider-image'])."' /><a class='remove-image'></a>"; } ?>
                    </div>
                </div>
                <div class="explain"><?php _e('Size of banner should be exactly 1200x400px for best results.','booster'); ?></div>
            </div>
          </div>
          <div id="forth-slider-div" class="section section-text mini">
            <h4 class="heading"><?php _e('Slide Link','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="forth-slider-link" class="of-input" name="faster_theme_options[forth-slider-link]" type="text" size="30" value="<?php if(!empty($booster_options['forth-slider-link'])) { echo esc_url($booster_options['forth-slider-link']); } ?>" />
              </div>
            </div>
          </div> 
          <h3><?php _e('Fifth Slide','booster') ?></h3>
          <div id="fifth-slider-image" class="section section-upload">
            <h4 class="heading"><?php _e('Slide Image','booster'); ?></h4>
            <div class="option">
                 <div class="controls">
                <input id="fifth-slider" class="upload" type="text" name="faster_theme_options[fifth-slider-image]" value="<?php if(!empty($booster_options['fifth-slider-image'])) { echo esc_url($booster_options['fifth-slider-image']); } ?>" placeholder="<?php _e('No file chosen','booster'); ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','booster') ?>" />
                    <div class="screenshot" id="fifth-image">
                      <?php if(!empty($booster_options['fifth-slider-image'])) { echo "<img src='".esc_url($booster_options['fifth-slider-image'])."' /><a class='remove-image'></a>"; } ?>
                    </div>
                </div>
                <div class="explain"><?php _e('Size of banner should be exactly 1200x400px for best results.','booster'); ?></div>
            </div>
          </div>
          <div id="fifth-slider-div" class="section section-text mini">
            <h4 class="heading"><?php _e('Slide Link','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="fifth-slider-link" class="of-input" name="faster_theme_options[fifth-slider-link]" type="text" size="30" value="<?php if(!empty($booster_options['fifth-slider-link'])) { echo esc_url($booster_options['fifth-slider-link']); } ?>" />
              </div>
            </div>
          </div>
        </div>
        <!-- Third group -->
        <div id="options-group-3" class="group socialsettings">
          <h3><?php _e('Basic Settings','booster'); ?></h3>
          <div id="section-logo" class="section section-upload ">
            <h4 class="heading"> <?php _e('Site Logo','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="main-logo" class="upload" type="text" name="faster_theme_options[logo]" value="<?php if(!empty($booster_options['logo'])) { echo esc_url($booster_options['logo']); } ?>"  placeholder="<?php _e('No file chosen','booster'); ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','booster') ?>" />
                <div class="screenshot" id="main-logo-image">
                  <?php if(!empty($booster_options['logo'])) { echo "<img src='".esc_url($booster_options['logo'])."' /><a class='remove-image'></a>"; } ?>
                </div>
              </div>
              <div class="explain"><?php _e('Size of logo should be exactly 250x125px for best results.','booster'); ?></div>
            </div>
          </div>
          <div id="section-logo" class="section section-upload ">
            <h4 class="heading"><?php _e('Favicon','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="logo" class="upload" type="text" name="faster_theme_options[fevicon]" value="<?php if(!empty($booster_options['fevicon'])) { echo esc_url($booster_options['fevicon']); } ?>" placeholder="<?php _e('No file chosen','booster'); ?>" />
                <input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','booster'); ?>" />
                <div class="screenshot" id="logo-image">
                  <?php if(!empty($booster_options['fevicon'])) { echo "<img src='".esc_url($booster_options['fevicon'])."' /><a class='remove-image'></a>"; } ?>
                </div>
              </div>
              <div class="explain"><?php _e('Size of fevicon should be exactly 32x32px for best results.','booster'); ?></div>
            </div>
          </div>
          <div id="section-footertext2" class="section section-textarea">
            <h4 class="heading"><?php _e('Copyright Text','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input type="text" id="footertext2" class="of-input" name="faster_theme_options[footertext]" size="32"  value="<?php if(!empty($booster_options['footertext'])) { echo wp_filter_nohtml_kses($booster_options['footertext']); } ?>">
              </div>
              <div class="explain"><?php _e('Some text regarding copyright of your site, you would like to display in the footer.','booster'); ?></div>
            </div>
          </div>
        </div>
        <!-- Forth group -->
        <div id="options-group-4" class="group socialsettings">
          <h3><?php _e('Social Settings','booster'); ?></h3>
          <div id="section-facebook" class="section section-text mini">
            <h4 class="heading"><?php _e('Facebook','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="facebook" class="of-input" name="faster_theme_options[fburl]" size="30" type="text" value="<?php if(!empty($booster_options['fburl'])) { echo esc_url($booster_options['fburl']); } ?>" />
              </div>
              <div class="explain"><?php _e('Facebook profile or page URL i.e. ','booster'); ?>http://facebook.com/username/</div>
            </div>
          </div>
          <div id="section-twitter" class="section section-text mini">
            <h4 class="heading"><?php _e('Twitter','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="twitter" class="of-input" name="faster_theme_options[twitter]" type="text" size="30" value="<?php if(!empty($booster_options['twitter'])) { echo esc_url($booster_options['twitter']); } ?>" />
              </div>
              <div class="explain"><?php _e('Twitter profile or page URL i.e.','booster'); ?> http://twitter.com/username/</div>
            </div>
          </div>
          <div id="section-twitter" class="section section-text mini">
            <h4 class="heading"><?php _e('Linkedin','booster'); ?></h4>
            <div class="option">
              <div class="controls">
                <input id="linkedin" class="of-input" name="faster_theme_options[linkedin]" type="text" size="30" value="<?php if(!empty($booster_options['linkedin'])) { echo esc_url($booster_options['linkedin']); } ?>" />
              </div>
              <div class="explain"><?php _e('Linkedin profile or page URL i.e. ','booster'); ?> https://in.linkedin.com/username/</div>
            </div>
          </div>
        </div>
        <!-- End group -->
        <div id="fasterthemes_framework-submit" class="section-submite"> 
          <input type="submit" class="button-primary" value="<?php _e('Save Options','booster'); ?>" />
          <div class="clear"></div>
        </div>
        <!-- Container -->
      </form>
      <!-- F I N A L - - T H E M E - - O P T I O N S -->
    </div>
  </div>
</div>
<?php } ?>