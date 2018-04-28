<div class="<?php echo esc_attr($this->mi_get_class($mi_grid_display));  echo (isset($matched_value))?
    implode(' ', array_map(function ($entry) {
       return ' '.$entry.' ';
   }, $matched_value)) : '';
 ?>">
    <div class="<?php echo esc_attr($this->mi_get_class($mi_elelment_class)); ?>">
        <div class="<?php echo esc_attr($this->mi_get_class($mi_image_class)); ?>"  >
            <img src="<?php echo esc_attr($mi_logo_logo['url']); ?>" alt="<?php echo esc_attr($mi_logo_logo['title']); ?>">
        </div>
    </div>
</div>


