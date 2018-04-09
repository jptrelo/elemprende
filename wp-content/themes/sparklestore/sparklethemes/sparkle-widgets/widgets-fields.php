<?php
/**
** Sparkle Store Field Functional file
* @package Sparkle_Store
*/
function sparklestore_widgets_show_widget_field($instance = '', $widget_field = '', $sparklestore_field_value = '') {
   
    // List Category List in array
    $sparklestore_category_list[0] = array(
        'value' => 0,
        'label' => esc_html__('Select Categories','sparklestore')
    );
    $sparklestore_posts = get_categories();
    foreach ( $sparklestore_posts as $sparklestore_post ) :
        $sparklestore_category_list[$sparklestore_post->term_id] = array(
            'value' => $sparklestore_post->term_id,
            'label' => $sparklestore_post->name
        );
    endforeach;

    /**
     * Default Page List in array
    */
    $sparklestore_pagelist[0] = array(
        'value' => 0,
        'label' => esc_html__('Select Pages','sparklestore')
    );
    $arg = array( 'posts_per_page' => -1 );
    $sparklestore_pages = get_pages( $arg );
    foreach ( $sparklestore_pages as $sparklestore_page ) :
        $sparklestore_pagelist[$sparklestore_page->ID] = array(
            'value' => $sparklestore_page->ID,
            'label' => $sparklestore_page->post_title
        );
    endforeach;

    extract($widget_field);

    switch ($sparklestore_widgets_field_type) {

        // Standard text field
        case 'text' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>"><?php echo $sparklestore_widgets_title; ?> :</label>
                <input class="widefat" id="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>" name="<?php echo $instance->get_field_name($sparklestore_widgets_name); ?>" type="text" value="<?php echo esc_attr($sparklestore_field_value) ; ?>" />

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo $sparklestore_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        //title
        case 'title' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>"><?php echo $sparklestore_widgets_title; ?> :</label>
                <input class="widefat" id="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>" name="<?php echo $instance->get_field_name($sparklestore_widgets_name); ?>" type="text" value="<?php echo esc_attr($sparklestore_field_value) ; ?>" />
                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo $sparklestore_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        case 'group_start' :
            ?>
            <div class="sparklestore-main-group" id="ap-font-awesome-list <?php echo $instance->get_field_id(($sparklestore_widgets_name)); ?>">
                <div class="sparklestore-main-group-heading" style="font-size: 15px;  font-weight: bold;  padding-top: 12px;"><?php echo $sparklestore_widgets_title ; ?><span class="toogle-arrow"></span></div>
                <div class="sparklestore-main-group-wrap">

            <?php
            break;

            case 'group_end':
            ?></div>
            </div><?php
            break;

        // Standard url field
        case 'url' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>"><?php echo $sparklestore_widgets_title; ?> :</label>
                <input class="widefat" id="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>" name="<?php echo $instance->get_field_name($sparklestore_widgets_name); ?>" type="text" value="<?php echo $sparklestore_field_value; ?>" />

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo $sparklestore_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Textarea field
        case 'textarea' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>"><?php echo $sparklestore_widgets_title; ?> :</label>
                <textarea class="widefat" rows="<?php echo $sparklestore_widgets_row; ?>" id="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>" name="<?php echo $instance->get_field_name($sparklestore_widgets_name); ?>"><?php echo $sparklestore_field_value; ?></textarea>
            </p>
            <?php
            break;

        // Checkbox field
        case 'checkbox' :
            ?>
            <p>
                <input id="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>" name="<?php echo $instance->get_field_name($sparklestore_widgets_name); ?>" type="checkbox" value="1" <?php checked('1', $sparklestore_field_value); ?>/>
                <label for="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>"><?php echo $sparklestore_widgets_title; ?></label>

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo $sparklestore_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Radio fields
        case 'radio' :
            ?>
            <p>
                <?php
                echo $sparklestore_widgets_title;
                echo '<br />';
                foreach ($sparklestore_widgets_field_options as $sparklestore_option_name => $sparklestore_option_title) {
                    ?>
                    <input id="<?php echo $instance->get_field_id($sparklestore_option_name); ?>" name="<?php echo $instance->get_field_name($sparklestore_widgets_name); ?>" type="radio" value="<?php echo $sparklestore_option_name; ?>" <?php checked($sparklestore_option_name, $sparklestore_field_value); ?> />
                    <label for="<?php echo $instance->get_field_id($sparklestore_option_name); ?>"><?php echo $sparklestore_option_title; ?></label>
                    <br />
                <?php } ?>

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <small><?php echo $sparklestore_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Select field
        case 'select' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>"><?php echo $sparklestore_widgets_title; ?> :</label>
                <select name="<?php echo $instance->get_field_name($sparklestore_widgets_name); ?>" id="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>" class="widefat">
                    <?php foreach ($sparklestore_widgets_field_options as $sparklestore_option_name => $sparklestore_option_title) { ?>
                        <option value="<?php echo $sparklestore_option_name; ?>" id="<?php echo $instance->get_field_id($sparklestore_option_name); ?>" <?php selected($sparklestore_option_name, $sparklestore_field_value); ?>><?php echo $sparklestore_option_title; ?></option>
                    <?php } ?>
                </select>

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo $sparklestore_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Select Pages field
        case 'selectpage' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>"><?php echo $sparklestore_widgets_title; ?>:</label>
                <select name="<?php echo $instance->get_field_name($sparklestore_widgets_name); ?>" id="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>" class="widefat">
                    <?php foreach ($sparklestore_pagelist as $sparklestore_page) { ?>
                        <option value="<?php echo $sparklestore_page['value']; ?>" id="<?php echo $instance->get_field_id($sparklestore_page['label']); ?>" <?php selected( $sparklestore_page['value'], $sparklestore_field_value ); ?>><?php echo $sparklestore_page['label']; ?></option>
                    <?php } ?>
                </select>

                <?php if ( isset( $sparklestore_widgets_description ) ) { ?>
                    <br />
                    <small><?php echo $sparklestore_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Number field
        case 'number' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>"><?php echo $sparklestore_widgets_title; ?> :</label><br />
                <input name="<?php echo $instance->get_field_name($sparklestore_widgets_name); ?>" type="number" id="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>" value="<?php echo $sparklestore_field_value; ?>" class="widefat" />

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo $sparklestore_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;        

        // Select category field
        case 'select_category' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>"><?php echo $sparklestore_widgets_title; ?> :</label>
                <select name="<?php echo $instance->get_field_name($sparklestore_widgets_name); ?>" id="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>" class="widefat">
                    <?php foreach ($sparklestore_category_list as $sparklestore_single_post) { ?>
                        <option value="<?php echo $sparklestore_single_post['value']; ?>" id="<?php echo $instance->get_field_id($sparklestore_single_post['label']); ?>" <?php selected($sparklestore_single_post['value'], $sparklestore_field_value); ?>><?php echo $sparklestore_single_post['label']; ?></option>
                    <?php } ?>
                </select>

                <?php if (isset($sparklestore_widgets_description)) { ?>
                    <br />
                    <small><?php echo $sparklestore_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        //Multi checkboxes
        case 'multicheckboxes' :
            
            if( isset( $sparklestore_mulicheckbox_title ) ) { ?>
                <label><?php echo esc_attr( $sparklestore_mulicheckbox_title ); ?>:</label>
            <?php }
            echo '<div class="sparklestore-multiplecat">';
                foreach ( $sparklestore_widgets_field_options as $sparklestore_option_name => $sparklestore_option_title) {
                    if( isset( $sparklestore_field_value[$sparklestore_option_name] ) ) {
                        $sparklestore_field_value[$sparklestore_option_name] = 1;
                    }else{
                        $sparklestore_field_value[$sparklestore_option_name] = 0;
                    }                
                ?>
                    <p>
                        <input id="<?php echo $instance->get_field_id($sparklestore_widgets_name); ?>" name="<?php echo $instance->get_field_name($sparklestore_widgets_name).'['.$sparklestore_option_name.']'; ?>" type="checkbox" value="1" <?php checked('1', $sparklestore_field_value[$sparklestore_option_name]); ?>/>
                        <label for="<?php echo $instance->get_field_id($sparklestore_option_name); ?>"><?php echo $sparklestore_option_title; ?></label>
                    </p>
                <?php
                    }
            echo '</div>';
                if (isset($sparklestore_widgets_description)) {
            ?>
                    <small><em><?php echo $sparklestore_widgets_description; ?></em></small>
            <?php
                }
            
        break;

        case 'upload' :

            $output = '';
            $id = $instance->get_field_id($sparklestore_widgets_name);
            $class = '';
            $int = '';
            $value = $sparklestore_field_value;
            $name = $instance->get_field_name($sparklestore_widgets_name);

            if ($value) {
                $class = ' has-file';
            }
            $output .= '<div class="sub-option section widget-upload">';
            $output .= '<label for="'.$instance->get_field_id($sparklestore_widgets_name).'">'.$sparklestore_widgets_title.'</label><br/>';
            $output .= '<input id="' . $id . '" class="upload' . $class . '" type="text" name="' . $name . '" value="' . $value . '" placeholder="' . esc_html__('No file chosen', 'sparklestore') . '" />' . "\n";
            
            if (function_exists('wp_enqueue_media')) {
                if (( $value == '')) {
                    $output .= '<input id="upload-' . $id . '" class="upload-button-wdgt button" type="button" value="' . esc_html__('Upload', 'sparklestore') . '" />' . "\n";
                } else {
                    $output .= '<input id="remove-' . $id . '" class="remove-file button" type="button" value="' . esc_html__('Remove', 'sparklestore') . '" />' . "\n";
                }
            } else {
                $output .= '<p><i>' . esc_html__('Upgrade your version of WordPress for full media support.', 'sparklestore') . '</i></p>';
            }

            $output .= '<div class="screenshot team-thumb" id="' . $id . '-image">' . "\n";
            if ($value != '') {
                $remove = '<a class="remove-image">' . esc_html__('Remove', 'sparklestore') . '</a>';
                $image = preg_match('/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value);
                if ($image) {
                    $output .= '<img src="' . $value . '" alt="" />' . $remove;
                } else {
                    $parts = explode("/", $value);
                    for ($i = 0; $i < sizeof($parts); ++$i) {
                        $title = $parts[$i];
                    }
                    $output .= '';
                    $title = esc_html__('View File', 'sparklestore');
                    $output .= '<div class="no-image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">' . $title . '</a></span></div>';
                }
            }
            $output .= '</div></div>' . "\n";
            echo $output;
            break;
    }
}

function sparklestore_widgets_updated_field_value($widget_field, $new_field_value) {

    extract($widget_field);

    if ($sparklestore_widgets_field_type == 'number') {

        return absint($new_field_value);

    } elseif ($sparklestore_widgets_field_type == 'textarea') {
        
        if (!isset($sparklestore_widgets_allowed_tags)) {
            $sparklestore_widgets_allowed_tags = '<p><strong><em><a><br>';
        }

        return wp_kses_post($new_field_value, $sparklestore_widgets_allowed_tags);
    } 
    elseif ($sparklestore_widgets_field_type == 'url') {
        return esc_url_raw($new_field_value);
    }
    elseif ($sparklestore_widgets_field_type == 'title') {
        return wp_kses_post($new_field_value);
    }
    elseif ($sparklestore_widgets_field_type == 'multicheckboxes') {
        return wp_kses_post($new_field_value);
    }
    else {
        return wp_kses_post($new_field_value);
    }
}



/**
 * Load about section widget area file.
*/
require sparklestore_file_directory('sparklethemes/sparkle-widgets/sparkle-promo.php');


/**
 * Load Full Promo widget area file.
*/
require sparklestore_file_directory('sparklethemes/sparkle-widgets/sparkle-fullpromo.php');


/**
 * Load Blogs Posts widget area file.
*/
require sparklestore_file_directory('sparklethemes/sparkle-widgets/sparkle-blogs-widget.php');

/**
 * Load contact form information widget area file.
*/
require sparklestore_file_directory('sparklethemes/sparkle-widgets/contact-info.php');




if (sparklestore_is_woocommerce_activated()) {
    /**
     * Load products widget area file.
    */
    require sparklestore_file_directory('sparklethemes/sparkle-widgets/sparkle-products-area.php');

    /**
     * Load category product widget area file.
    */
    require sparklestore_file_directory('sparklethemes/sparkle-widgets/sparkle-category-products.php');

    /**
     * Load category collection widget area file.
    */
    require sparklestore_file_directory('sparklethemes/sparkle-widgets/sparkle-category-collection.php');

    /**
     * Load tabs category products widget area file.
    */
    require sparklestore_file_directory('sparklethemes/sparkle-widgets/sparkle-tabs-category.php');
}