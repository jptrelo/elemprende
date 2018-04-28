<div class="<?php echo esc_attr($this->mi_get_class($mi_logo_parent_wrap_class)); ?>">
    <div class=" <?php echo esc_attr($this->mi_get_class($mi_h_slider_class)); ?> <?php echo esc_attr($this->mi_get_class($mi_logo_wrap_class)); ?>  "
         data-owl-options='{"loop":true,"nav":true,"responsiveClass":true,"autoplay":<?php echo ($mi_logo_autoplay ) ? true : false; ?>,"navSpeed":<?php echo esc_attr($mi_logo_nav_speed); ?>,"dotsSpeed":<?php echo esc_attr($mi_logo_dot_speed); ?> ,"autoplaySpeed":<?php echo esc_attr($mi_logo_dot_speed); ?>,"autoplayTimeout":<?php echo esc_attr($mi_logo_autoplay_speed); ?>,"responsive":{"0":{"items":<?php echo esc_attr($mi_logo_settings['small_mobile_number_of_grid']); ?>},"480":{"items":<?php echo esc_attr($mi_logo_settings['mobile_number_of_grid']); ?>},"768":{"items":<?php echo esc_attr($mi_logo_settings['tab_number_of_grid']); ?>},"992":{"items":<?php echo esc_attr($mi_logo_settings['desktop_number_of_grid']); ?>},"1200":{"items":<?php echo esc_attr($mi_logo_settings['large_desktop_number_of_grid']); ?>}}}'>
        <?php

        foreach ($mi_logo_logos as $mi_logo_logo):
            $mi_logo_logo = (array)$mi_logo_logo;
            if (!empty($mi_logo_logo['link'])) {
                ?>
                <a <?php echo ($mi_logo_link_attr) ? 'rel="nofollow"' : ''; ?>
                        href="<?php echo esc_url($mi_logo_logo['link']); ?>" target="_blank">
                    <?php
                    include($this->mi_get_plugin_path('styles/style', $mi_logo_special_style));
                    ?>
                </a>

                <?php
            } else {

                include($this->mi_get_plugin_path('styles/style', $mi_logo_special_style));
            }
        endforeach;

        ?>


    </div>
</div>