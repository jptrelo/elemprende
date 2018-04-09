<?php
add_action( 'widgets_init', 'photoshoot_widget' );

function photoshoot_widget() {
    register_widget( 'photoshoot_info_widget' );
}
class photoshoot_info_widget extends WP_Widget {
    function __construct() {
        $photoshoot_widget_ops = array( 'classname' => 'photoshoot_info', 'description' => __('A widget that displays the title, content, image and socia links. ', 'photoshoot') );
        $photoshoot_control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'photoshoot-info-widget' );
        parent::__construct( 'photoshoot-info-widget', __('photoshoot Author information', 'photoshoot'), $photoshoot_widget_ops, $photoshoot_control_ops );
    }
    function widget( $photoshoot_args, $photoshoot_instance ) {
        extract( $photoshoot_args );
        //Our variables from the widget settings.
        $photoshoot_title = apply_filters('widget_title', $photoshoot_instance['title'] );
        $photoshoot_name = $photoshoot_instance['cname'];
		    $photoshoot_image = $photoshoot_instance['image'];
        $photoshoot_country = $photoshoot_instance['country'];
        $photoshoot_email = $photoshoot_instance['email'];
        $photoshoot_website = $photoshoot_instance['website'];
        $photoshoot_facebook = $photoshoot_instance['facebook'];
        $photoshoot_twitter = $photoshoot_instance['twitter'];
        $photoshoot_linkedin = $photoshoot_instance['linkedin'];
        $photoshoot_google = $photoshoot_instance['google'];
        $photoshoot_pinterest = $photoshoot_instance['pinterest'];
        echo $before_widget;
        //Display widget ?>
<div class="contributor">
  <h2><?php if(!empty($photoshoot_instance['title'])) echo $photoshoot_instance['title']; else _e("Contributor","photoshoot"); ?></h2>
  <div class="contributor-details">
    <div class="contributor-author"> <img alt="details" src="<?php if($photoshoot_instance['image']) { echo $photoshoot_instance['image']; } else { echo get_template_directory_uri().'/images/default-user.png'; } ?>" class="img-circle">
      <div class="contributor-author-details">
        <?php if(!empty($photoshoot_instance['cname'])) { ?>
        <p class="contributor_name"><?php echo $photoshoot_instance['cname']; ?></p>
          <?php } ?>
          <?php if(!empty($photoshoot_instance['country'])) { ?>
	        <p><?php echo $photoshoot_instance['country']; ?></p>
          <?php } ?>
        <ul> 
		<?php if(!empty($photoshoot_instance['facebook'])) { ?>
        <li><a target="_blank" class="social_facebook_circle" href="<?php echo esc_url($photoshoot_instance['facebook']); ?>"></a></li>
            <?php }
            if(!empty($photoshoot_instance['twitter'])) { ?>
            <li><a target="_blank" class="social_twitter_circle" href="<?php echo esc_url($photoshoot_instance['twitter']); ?>"></a></li>
            <?php }
            if(!empty($photoshoot_instance['linkedin'])) { ?>
            <li><a target="_blank" class="social_googleplus_circle" href="<?php echo esc_url($photoshoot_instance['linkedin']); ?>"></a></li>
            <?php }
            if(!empty($photoshoot_instance['google'])) { ?>
            <li><a target="_blank" class="social_linkedin_circle" href="<?php echo esc_url($photoshoot_instance['google']); ?>"></a></li>
            <?php }
            if(!empty($photoshoot_instance['pinterest'])) { ?>
            <li><a target="_blank" class="social_pinterest_circle" href="<?php echo esc_url($photoshoot_instance['pinterest']); ?>"></a></li>
            <?php } ?>
        </ul>
      </div>
    </div>
    <?php if(!empty($photoshoot_instance['website'])) { ?>
    <div class="contributor-info"><?php _e('Website','photoshoot') ?>: <a href="<?php echo esc_url($photoshoot_instance['website']); ?>"><?php echo esc_url($photoshoot_instance['website']); ?></a></div>
    <?php } ?>
    <?php if(!empty($photoshoot_instance['email'])) { ?>
    <div class="contributor-info"><?php _e('Email','photoshoot') ?>: <a href="mailto:<?php echo $photoshoot_instance['email']; ?>"><?php echo $photoshoot_instance['email']; ?></a></div>
    <?php } ?>
  </div>
</div>
<?php        
    echo $after_widget;
  }
    //Update the widget
    function update( $new_instance, $old_instance ) {
        $photoshoot_instance = $old_instance;
        //Strip tags from title and name to remove HTML
        $photoshoot_instance['title'] = strip_tags( $new_instance['title'] );
        $photoshoot_instance['cname'] = strip_tags( $new_instance['cname'] );
        $photoshoot_instance['country'] = strip_tags( $new_instance['country'] );		
		    $photoshoot_instance['image'] = strip_tags( $new_instance['image'] );
        $photoshoot_instance['website'] = strip_tags( $new_instance['website'] );		
        $photoshoot_instance['email'] = strip_tags( $new_instance['email'] );		
        $photoshoot_instance['facebook'] = strip_tags( $new_instance['facebook'] );
        $photoshoot_instance['twitter'] = strip_tags( $new_instance['twitter'] );
    		$photoshoot_instance['linkedin'] = strip_tags( $new_instance['linkedin'] );
    		$photoshoot_instance['google'] = strip_tags( $new_instance['google'] );
    		$photoshoot_instance['pinterest'] = strip_tags( $new_instance['pinterest'] );
        return $photoshoot_instance;
    }
    function form( $photoshoot_instance ) { ?>
<p>
  <label for="<?php echo $this->get_field_id( 'title' ); ?>">
    <?php _e('Widget Title:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if(!empty($photoshoot_instance['title'])) { echo $photoshoot_instance['title']; } ?>" style="width:100%;" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'cname' ); ?>">
    <?php _e('Full Name:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'cname' ); ?>" name="<?php echo $this->get_field_name( 'cname' ); ?>" value="<?php if(!empty($photoshoot_instance['cname'])) { echo $photoshoot_instance['cname']; } ?>" style="width:100%;" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'country' ); ?>">
    <?php _e('Country:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'country' ); ?>" name="<?php echo $this->get_field_name( 'country' ); ?>" value="<?php if(!empty($photoshoot_instance['country'])) { echo $photoshoot_instance['country']; } ?>" style="width:100%;" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'email' ); ?>">
    <?php _e('Email Address:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php if(!empty($photoshoot_instance['email'])) { echo $photoshoot_instance['email']; } ?>" style="width:100%;" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'website' ); ?>">
    <?php _e('Website:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'website' ); ?>" name="<?php echo $this->get_field_name( 'website' ); ?>" value="<?php if(!empty($photoshoot_instance['website'])) { echo esc_url($photoshoot_instance['website']); } ?>" style="width:100%;" />
</p>
<p class="section">
  <label for="<?php echo $this->get_field_id( 'image' ); ?>">
    <?php _e('Image:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'image' ); ?>"  type="text" class="widefat photoshoot_media_url upload" name="<?php echo $this->get_field_name( 'image' ); ?>" value="<?php if(!empty($photoshoot_instance['image'])) { echo esc_url($photoshoot_instance['image']); } ?>" placeholder="<?php _e('No file chosen','photoshoot') ?>" style="width:100%;" />
  <?php if(!empty($photoshoot_instance['image'])) { ?>
  <img src="<?php echo esc_url($photoshoot_instance['image']); ?>" style='max-width:100%;' />
  <?php } ?>
  <input id="upload_image_button_widget" class="upload-button button" type="button" value="<?php _e('Upload','photoshoot') ?>" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'facebook' ); ?>">
    <?php _e('Facebook url:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" value="<?php if(!empty($photoshoot_instance['facebook'])) { echo esc_url($photoshoot_instance['facebook']); } ?>" style="width:100%;" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'twitter' ); ?>">
    <?php _e('Twitter url:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" value="<?php if(!empty($photoshoot_instance['twitter'])) { echo esc_url($photoshoot_instance['twitter']); } ?>" style="width:100%;" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'linkedin' ); ?>">
    <?php _e('Linkedin url:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'linkedin' ); ?>" name="<?php echo $this->get_field_name( 'linkedin' ); ?>" value="<?php if(!empty($photoshoot_instance['linkedin'])) { echo esc_url($photoshoot_instance['linkedin']); } ?>" style="width:100%;" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'google' ); ?>">
    <?php _e('Google+ url:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'google' ); ?>" name="<?php echo $this->get_field_name( 'google' ); ?>" value="<?php if(!empty($photoshoot_instance['google'])) { echo esc_url($photoshoot_instance['google']); } ?>" style="width:100%;" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'pinterest' ); ?>">
    <?php _e('Pinterest url:', 'photoshoot'); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'pinterest' ); ?>" name="<?php echo $this->get_field_name( 'pinterest' ); ?>" value="<?php if(!empty($photoshoot_instance['pinterest'])) { echo esc_url($photoshoot_instance['pinterest']); } ?>" style="width:100%;" />
</p>
<?php }
} ?>