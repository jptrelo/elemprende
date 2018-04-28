<div class="<?php echo esc_attr($this->mi_get_class($mi_logo_parent_wrap_class)); ?>">
<div class=" <?php echo esc_attr($this->mi_get_class($mi_logo_container_class, " mi_logo_link")); ?> <?php echo esc_attr($this->mi_get_class($mi_logo_wrap_class)); ?>">
    <?php
    foreach ($mi_logo_logos as $mi_logo_logo):
        
        $mi_logo_logo = (array)$mi_logo_logo;
        if(!empty($mi_logo_logo['link'])){
            ?>
            <a  <?php echo ($mi_logo_link_attr)? 'rel="nofollow"':''; ?> href="<?php echo esc_url($mi_logo_logo['link']) ; ?>" target="_blank">
                <?php
                include($this->mi_get_plugin_path('styles/style', $mi_logo_special_style));
                ?>
            </a>

            <?php
        }else{

            include($this->mi_get_plugin_path('styles/style', $mi_logo_special_style));
        }
    endforeach;
    ?>

</div>
</div>