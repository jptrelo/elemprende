<?php
/**
 * Footer For photoshoot Theme.
 */
 global $photoshoot_options; ?>
<footer>
	<div class="container photoshoot-container">
    	<div class="row">
       		<div class="col-md-3">
            	<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-1' ) ) {  dynamic_sidebar( 'footer-1' ); } ?>
                </div>
            </div>
       		<div class="col-md-3">
            	<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-2' ) ) {  dynamic_sidebar( 'footer-2' ); } ?>
                </div>
            </div>
       		<div class="col-md-3">
            	<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-3' ) ) {  dynamic_sidebar( 'footer-3' ); } ?>
                </div>
            </div>
            <div class="col-md-3">
            	<div class="footer-widget">
			   <?php   if((!empty($photoshoot_options['fburl'])) || (!empty($photoshoot_options['twitter'])) || (!empty($photoshoot_options['googleplus']))  || (!empty($photoshoot_options['rss']))) {?>

                	<h3><?php _e('Follow Us','photoshoot') ?></h3>
                    <ul class="footer-social">
                    	<?php if(!empty($photoshoot_options['fburl'])): ?><li><a href="<?php echo esc_url($photoshoot_options['fburl']); ?>"><i class="social_facebook_circle"></i></a></li><?php endif; ?>
                        <?php if(!empty($photoshoot_options['twitter'])): ?><li><a href="<?php echo esc_url($photoshoot_options['twitter']); ?>"><i class="social_twitter_circle"></i></a></li><?php endif; ?>
                        <?php if(!empty($photoshoot_options['googleplus'])): ?><li><a href="<?php echo esc_url($photoshoot_options['googleplus']); ?>"><i class="social_googleplus_circle"></i></a></li><?php endif; ?>
                        <?php if(!empty($photoshoot_options['rss'])): ?><li><a href="<?php echo esc_url($photoshoot_options['rss']); ?>"> <i class="social_rss_circle"></i></a></li><?php endif; ?>
                    </ul>
                    <?php } ?>
                    <div class="logo">
					<?php if(!empty($photoshoot_options['footertext'])){ ?>
	                    <p><?php echo esc_attr($photoshoot_options['footertext']).' '; ?></p>
                    <?php }
                    printf( __( 'Powered by %1$s.', 'photoshoot' ), '<a href="http://fasterthemes.com/wordpress-themes/photoshoot" target="_blank">Photoshoot WordPress Theme</a>' ); ?>
                    </div>
                </div>
            </div> 
        </div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>