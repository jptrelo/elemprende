<?php


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

?>

<?php

$action = (isset($_GET['action'])) ? $_GET['action'] : false;


//add or edit new logo row.
if ($action === 'submit_data' && isset($_REQUEST['new_logo_id']) && wp_verify_nonce($_POST['mi-logo_slider_nonce'], $this->plugin_name)) {

    $id = (int)$_REQUEST['new_logo_id']; //get the logo id
    $logo_items = $this->get_option_value();
    $title = $this->mi_form_sanitization($_POST['new_logo_title'], 'title');
    if (empty(trim($title))) {
        $title = '(no title)';
    }
    if (isset($_POST['mi_logo_shortcode'])):
        $shortcode = $this->mi_form_sanitization($_POST['mi_logo_shortcode'],'text');
    else:
        $shortcode = ' ';
    endif;
    if (isset($_POST['cp_image_data'])):
        $image_data = $_POST['cp_image_data'];
    else:
        $image_data = '';
    endif;
    $image_data = json_decode(stripslashes($image_data));

    foreach ($image_data as $key => $value) {

        array_walk_recursive($value, 'Mi_Plugin_Admin::walk_recursive');

    }


    if (isset($_POST['display_mode'])):
        $mi_display_mode = $this->mi_form_sanitization($_POST['display_mode'],'text');
        $mi_display_mode = (!in_array($mi_display_mode, $this->mi_public_defaults_values['display_mode'])) ?
            $this->mi_public_defaults_values['display_mode'][0] : $mi_display_mode;

    else:
        $mi_display_mode = '';
    endif;
    if (isset($_POST['layout_selection'])):
        $mi_layout_selection = $this->mi_form_sanitization($_POST['layout_selection'],'text');
        $mi_layout_selection = (!in_array($mi_layout_selection, $this->mi_public_defaults_values['layout'])) ?
            $this->mi_public_defaults_values['layout'][0] : $mi_layout_selection;
    else:
        $mi_layout_selection = '';
    endif;

    if (isset($_POST['dot_option'])):
        $mi_dot_option = $this->mi_form_sanitization($_POST['dot_option'],'text');
        $mi_dot_option = (!in_array($mi_dot_option, $this->mi_public_defaults_values['bool'])) ?
            $this->mi_public_defaults_values['bool'][0] : $mi_dot_option;
    else:
        $mi_dot_option = '';
    endif;

    if (isset($_POST['nav_option'])):
        $mi_nav_option = $this->mi_form_sanitization($_POST['nav_option'],'text');
        $mi_nav_option = (!in_array($mi_nav_option, $this->mi_public_defaults_values['bool'])) ?
            $this->mi_public_defaults_values['bool'][0] : $mi_nav_option;
    else:
        $mi_nav_option = '';
    endif;
    if (isset($_POST['dot_position'])):
        $mi_dot_position = $this->mi_form_sanitization($_POST['dot_position'],'text');
        $mi_dot_position = (!in_array($mi_dot_position, $this->mi_public_defaults_values['position'])) ?
            $this->mi_public_defaults_values['position'][0] : $mi_dot_position;
    else:
        $mi_dot_position = '';
    endif;
    if (isset($_POST['nav_position_h'])):
        $mi_nav_position_h = $this->mi_form_sanitization($_POST['nav_position_h'],'text');
        $mi_nav_position_h = (!in_array($mi_nav_position_h, $this->mi_public_defaults_values['nav-position'])) ?
            $this->mi_public_defaults_values['nav-position'][0] : $mi_nav_position_h;
    else:
        $mi_nav_position_h = '';
    endif;
    $mi_nav_position_v = '';
    if (isset($_POST['slider_txt_color'])):
        $mi_slider_txt_color = $this->mi_form_sanitization($_POST['slider_txt_color'], 'color');
    else:
        $mi_slider_txt_color = '';
    endif;
    if (isset($_POST['slider_bg_color'])):
        $mi_slider_bg_color = $this->mi_form_sanitization($_POST['slider_bg_color'], 'color');
    else:
        $mi_slider_bg_color = '';
    endif;
    if (isset($_POST['slider_txt_h_color'])):
        $mi_slider_txt_h_color = $this->mi_form_sanitization($_POST['slider_txt_h_color'], 'color');
    else:
        $mi_slider_txt_h_color = '';
    endif;
    if (isset($_POST['slider_bg_h_color'])):
        $mi_slider_bg_h_color = $this->mi_form_sanitization($_POST['slider_bg_h_color'], 'color');
    else:
        $mi_slider_bg_h_color = '';
    endif;


    if (isset($_POST['item_width'])):
        $mi_vertical_slider_item_width = $this->mi_form_sanitization($_POST['item_width'],'text');
    else:
        $mi_vertical_slider_item_width = '';
    endif;

    if (isset($_POST['large_desktop_number_of_grid'])):
        $mi_large_desktop_number_of_grid = $this->mi_form_sanitization($_POST['large_desktop_number_of_grid'], 'int');
        $mi_large_desktop_number_of_grid = (!in_array($mi_large_desktop_number_of_grid, $this->mi_public_defaults_values['grid'])) ?
            $this->mi_public_defaults_values['grid'][0] : $mi_large_desktop_number_of_grid;
    else:
        $mi_large_desktop_number_of_grid = '';
    endif;
    if (isset($_POST['desktop_number_of_grid'])):
        $mi_desktop_number_of_grid = $this->mi_form_sanitization($_POST['desktop_number_of_grid'], 'int');
        $mi_desktop_number_of_grid = (!in_array($mi_desktop_number_of_grid, $this->mi_public_defaults_values['grid'])) ?
            $this->mi_public_defaults_values['grid'][0] : $mi_desktop_number_of_grid;
    else:
        $mi_desktop_number_of_grid = '';
    endif;
    if (isset($_POST['tab_number_of_grid'])):
        $mi_tab_number_of_grid = $this->mi_form_sanitization($_POST['tab_number_of_grid'], 'int');
        $mi_tab_number_of_grid = (!in_array($mi_tab_number_of_grid, $this->mi_public_defaults_values['grid'])) ?
            $this->mi_public_defaults_values['grid'][0] : $mi_tab_number_of_grid;
    else:
        $mi_tab_number_of_grid = '';
    endif;
    if (isset($_POST['mobile_number_of_grid'])):
        $mi_mobile_number_of_grid = $this->mi_form_sanitization($_POST['mobile_number_of_grid'], 'int');
        $mi_mobile_number_of_grid = (!in_array($mi_mobile_number_of_grid, $this->mi_public_defaults_values['grid'])) ?
            $this->mi_public_defaults_values['grid'][0] : $mi_mobile_number_of_grid;
    else:
        $mi_mobile_number_of_grid = '';
    endif;
    if (isset($_POST['small_mobile_number_of_grid'])):
        $mi_small_mobile_number_of_grid = $this->mi_form_sanitization($_POST['small_mobile_number_of_grid'], 'int');
        $mi_small_mobile_number_of_grid = (!in_array($mi_small_mobile_number_of_grid, $this->mi_public_defaults_values['grid'])) ?
            $this->mi_public_defaults_values['grid'][0] : $mi_small_mobile_number_of_grid;
    else:
        $mi_small_mobile_number_of_grid = '';
    endif;


    if (isset($_POST['layout_background_color'])):
        $mi_layout_background_color = $this->mi_form_sanitization($_POST['layout_background_color'], 'color');
    else:
        $mi_layout_background_color = '';
    endif;
    if (isset($_POST['layout_border_color'])):
        $mi_layout_border_color = $this->mi_form_sanitization($_POST['layout_border_color'], 'color');
    else:
        $mi_layout_border_color = '';
    endif;
    if (isset($_POST['layout_text_color'])):
        $mi_layout_text_color = $this->mi_form_sanitization($_POST['layout_text_color'], 'color');
    else:
        $mi_layout_text_color = '';
    endif;
    if (isset($_POST['layout_background_h_color'])):
        $mi_layout_background_h_color = $this->mi_form_sanitization($_POST['layout_background_h_color'], 'color');
    else:
        $mi_layout_background_h_color = '';
    endif;
    if (isset($_POST['layout_border_h_color'])):
        $mi_layout_border_h_color = $this->mi_form_sanitization($_POST['layout_border_h_color'], 'color');
    else:
        $mi_layout_border_h_color = '';
    endif;
    if (isset($_POST['layout_text_h_color'])):
        $mi_layout_text_h_color = $this->mi_form_sanitization($_POST['layout_text_h_color'], 'color');
    else:
        $mi_layout_text_h_color = '';
    endif;

    if (isset($_POST['mi_logo_gutter'])):
        $mi_gutter = $this->mi_form_sanitization($_POST['mi_logo_gutter'], 'int');
        $mi_gutter = (!in_array($mi_gutter, $this->mi_public_defaults_values['gutter'])) ?
            $this->mi_public_defaults_values['gutter'][0] : $mi_gutter;
    else:
        $mi_gutter = '';
    endif;
    if (isset($_POST['nav_inner'])):
        $mi_nav_inner = $this->mi_form_sanitization($_POST['nav_inner'],'text');
        $mi_nav_inner = (!in_array($mi_nav_inner, $this->mi_public_defaults_values['bool'])) ?
            $this->mi_public_defaults_values['bool'][0] : $mi_nav_inner;
    else:
        $mi_nav_inner = '';
    endif;

    if (isset($_POST['image_width'])):
        $mi_image_width = $this->mi_form_sanitization($_POST['image_width'],'text');
    else:
        $mi_image_width = '';
    endif;

    if (isset($_POST['mi_slider_autoplay'])):
        $mi_autoplay = $this->mi_form_sanitization($_POST['mi_slider_autoplay'],'text');
        $mi_autoplay = (!in_array($mi_autoplay, $this->mi_public_defaults_values['bool'])) ?
            $this->mi_public_defaults_values['bool'][0] : $mi_autoplay;
    else:
        $mi_autoplay = '';
    endif;
    if (isset($_POST['mi_slider_autoplay_speed'])):
        $mi_autoplay_speed = $this->mi_form_sanitization($_POST['mi_slider_autoplay_speed'], 'int');
    else:
        $mi_autoplay_speed = '';
    endif;
    if (isset($_POST['mi_slider_nav_speed'])):
        $mi_nav_speed = $this->mi_form_sanitization($_POST['mi_slider_nav_speed'], 'int');
    else:
        $mi_nav_speed = '';
    endif;
    if (isset($_POST['mi_slider_dot_speed'])):
        $mi_dot_speed = $this->mi_form_sanitization($_POST['mi_slider_dot_speed'], 'int');
    else:
        $mi_dot_speed = '';
    endif;
    if (isset($_POST['link_attr'])) {
        $mi_link_attr = $this->mi_form_sanitization($_POST['link_attr'],'text');
        if ($mi_link_attr == 'on') {
            $mi_link_attr = true;
        }
    } else {
        $mi_link_attr = '';
    };
    if (isset($_POST['mi_transition_speed'])):
        $mi_transition_speed = $this->mi_form_sanitization($_POST['mi_transition_speed'], 'int');
    else:
        $mi_transition_speed = '';
    endif;



    /*add item on arr(array)*/
    $arr = array(
        'title' => $title,
        'shortcode' => $shortcode,
        'logo' => $image_data,
        'category' => array(),
        'settings' => array()
    );

    /*add settings value*/
    if ($mi_display_mode !== false) {
        $arr['settings']['display_mode'] = $mi_display_mode;
    }
    if ($mi_layout_selection !== false) {
        $arr['settings']['layout_selection'] = $mi_layout_selection;
    }
    if ($mi_dot_position !== false) {
        $arr['settings']['dot_position'] = $mi_dot_position;
    }
    if ($mi_dot_option !== false) {
        $arr['settings']['dot_option'] = $mi_dot_option;
    }
    if ($mi_nav_option !== false) {
        $arr['settings']['nav_option'] = $mi_nav_option;
    }
    if ($mi_nav_position_h !== false) {
        $arr['settings']['nav_position_h'] = $mi_nav_position_h;
    }
    if ($mi_nav_position_v !== false) {
        $arr['settings']['nav_position_v'] = $mi_nav_position_v;
    }
    if ($mi_slider_txt_color !== false) {
        $arr['settings']['slider_txt_color'] = $mi_slider_txt_color;
    }
    if ($mi_slider_bg_color !== false) {
        $arr['settings']['slider_bg_color'] = $mi_slider_bg_color;
    }
    if ($mi_slider_txt_h_color !== false) {
        $arr['settings']['slider_txt_h_color'] = $mi_slider_txt_h_color;
    }
    if ($mi_slider_bg_h_color !== false) {
        $arr['settings']['slider_bg_h_color'] = $mi_slider_bg_h_color;
    }
    if ($mi_filter_txt_color !== false) {
        $arr['settings']['filter_txt_color'] = $mi_filter_txt_color;
    }
    if ($mi_filter_button_position !== false) {
        $arr['settings']['filter_button_position'] = $mi_filter_button_position;
    }
    if ($mi_filter_bg_color !== false) {
        $arr['settings']['filter_bg_color'] = $mi_filter_bg_color;
    }
    if ($mi_filter_text_h_color !== false) {
        $arr['settings']['filter_text_h_color'] = $mi_filter_text_h_color;
    }
    if ($mi_filter_bg_h_color !== false) {
        $arr['settings']['filter_bg_h_color'] = $mi_filter_bg_h_color;
    }
    if ($mi_filter_text_a_color !== false) {
        $arr['settings']['filter_text_a_color'] = $mi_filter_text_a_color;
    }
    if ($mi_filter_bg_a_color !== false) {
        $arr['settings']['filter_bg_a_color'] = $mi_filter_bg_a_color;
    }
    if ($mi_large_desktop_number_of_grid !== false) {
        $arr['settings']['large_desktop_number_of_grid'] = $mi_large_desktop_number_of_grid;
    }
    if ($mi_desktop_number_of_grid !== false) {
        $arr['settings']['desktop_number_of_grid'] = $mi_desktop_number_of_grid;
    }
    if ($mi_tab_number_of_grid !== false) {
        $arr['settings']['tab_number_of_grid'] = $mi_tab_number_of_grid;
    }
    if ($mi_mobile_number_of_grid !== false) {
        $arr['settings']['mobile_number_of_grid'] = $mi_mobile_number_of_grid;
    }
    if ($mi_small_mobile_number_of_grid !== false) {
        $arr['settings']['small_mobile_number_of_grid'] = $mi_small_mobile_number_of_grid;
    }
    if ($mi_layout_background_color !== false) {
        $arr['settings']['layout_background_color'] = $mi_layout_background_color;
    }
    if ($mi_layout_border_color !== false) {
        $arr['settings']['layout_border_color'] = $mi_layout_border_color;
    }
    if ($mi_layout_text_color !== false) {
        $arr['settings']['layout_text_color'] = $mi_layout_text_color;
    }
    if ($mi_layout_background_h_color !== false) {
        $arr['settings']['layout_background_h_color'] = $mi_layout_background_h_color;
    }
    if ($mi_layout_border_h_color !== false) {
        $arr['settings']['layout_border_h_color'] = $mi_layout_border_h_color;
    }
    if ($mi_layout_text_h_color !== false) {
        $arr['settings']['layout_text_h_color'] = $mi_layout_text_h_color;
    }
    if ($mi_list_view !== false) {
        $arr['settings']['list_view'] = $mi_list_view;
    }
    if ($mi_list_divider !== false) {
        $arr['settings']['list_divider'] = $mi_list_divider;
    }
    if ($mi_gutter !== false) {
        $arr['settings']['mi_logo_gutter'] = $mi_gutter;
    }
    if ($mi_nav_inner !== false) {
        $arr['settings']['nav_inner'] = $mi_nav_inner;
    }
    if ($mi_vertical_slider_item_width !== false) {
        $arr['settings']['item_width'] = $mi_vertical_slider_item_width;
    }

    if ($mi_tooltip_option !== false) {
        $arr['settings']['tooltip_option'] = $mi_tooltip_option;
    }

    if ($mi_vertical_scroll_item !== false) {
        $arr['settings']['v_scroll_item'] = $mi_vertical_scroll_item;
    }

    if ($mi_vertical_display_item !== false) {
        $arr['settings']['v_display_item'] = $mi_vertical_display_item;
    }

    if ($mi_image_width !== false) {
        $arr['settings']['image_width'] = $mi_image_width;
    }

    if ($mi_autoplay !== false) {
        $arr['settings']['mi_slider_autotplay'] = $mi_autoplay;
    }

    if ($mi_autoplay_speed !== false) {
        $arr['settings']['mi_slider_autoplay_speed'] = $mi_autoplay_speed;
    }
    if ($mi_nav_speed !== false) {
        $arr['settings']['mi_slider_nav_speed'] = $mi_nav_speed;
    }
    if ($mi_dot_speed !== false) {
        $arr['settings']['mi_slider_dot_speed'] = $mi_dot_speed;
    }

    if ($mi_transition_speed !== false) {
        $arr['settings']['mi_slider_transition_speed'] = $mi_transition_speed;
    }
    if ($mi_link_attr !== false) {
        $arr['settings']['link_attr'] = $mi_link_attr;
    }
    if ($mi_nav_inner_v !== false) {
        $arr['settings']['nav_inner_v'] = $mi_nav_inner_v;
    }


    if ($logo_items !== false) { //if option registered

        $logo_items[$id] = $arr;

        update_option('logo_items', $logo_items);

    } else {
        add_option('logo_items', array($id => $arr));
    }

    $this->show_row_form($id);


    wp_safe_redirect(add_query_arg(array(
        'page' => $this->plugin_name,
        'action' => 'edit',
        'new_logo_id' => $id
    ), admin_url('admin.php')));
    exit;

} elseif ($action === 'edit') {

    $id = $_REQUEST['new_logo_id'];

    $this->show_row_form($id);


} else {

    $this->show_row_form();
}


?>





