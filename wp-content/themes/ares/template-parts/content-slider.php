<?php $ares_options = ares_get_options(); ?>

<?php if ( $ares_options['ares_slide1_image'] || $ares_options['ares_slide2_image'] || $ares_options['ares_slide3_image'] ) : ?>

    <div class="sc-slider-wrapper">

        <div class="fluid_container">

            <div class="camera_wrap" id="ares_slider_wrap">

                <?php for ( $ctr = 1; $ctr < apply_filters( 'ares_capacity', 1 ); $ctr++ ) : ?>

                    <?php if ( $ares_options['ares_slide' . $ctr . '_image'] ) : ?>

                        <div data-thumb="<?php echo esc_attr( $ares_options['ares_slide' . $ctr . '_image'] ); ?>" data-src="<?php echo esc_attr( $ares_options['ares_slide' . $ctr . '_image'] ); ?>">

                            <div class="camera_caption fadeFromBottom">

                                <?php if ( isset( $ares_options['ares_slide' . $ctr . '_text'] ) ) : ?>

                                    <span class="smartcat-animate fadeInUp">
                                        <?php echo esc_attr( $ares_options['ares_slide' . $ctr . '_text'] ); ?>
                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endif; ?>

                <?php endfor; ?>

            </div><!-- #camera_wrap_1 -->

        </div>

    </div>

<?php endif;
