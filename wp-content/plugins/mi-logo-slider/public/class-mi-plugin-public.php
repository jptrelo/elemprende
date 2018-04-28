<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://miplugins.com
 * @since      1.0.0
 *
 * @package    Mi_Pro_Plugin
 * @subpackage Mi_Pro_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mi_Pro_Plugin
 * @subpackage Mi_Pro_Plugin/public
 * @author     Mi Plugins <miplugins@gmail.com>
 */
if (!class_exists('Mi_Logo_Plugin_Public')):
    class Mi_Logo_Plugin_Public
    {

        /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_name The ID of this plugin.
         */
        private $plugin_name;

        /**
         * The version of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $version The current version of this plugin.
         */
        private $version;


        /**
         * @var get all the cats id in column
         */
        private static $mi_logo_cats_id;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         * @param      string $plugin_name The name of the plugin.
         * @param      string $version The version of this plugin.
         */

        public $custom_css = '';

        public function __construct($plugin_name, $version)
        {

            $this->plugin_name = $plugin_name;
            $this->version = $version;
            add_shortcode('mi-logo', array($this, 'mi_logo_shortcode_callback'));


        }

        /**
         * @return url
         * @param file name first part
         * @param  fine name second part
         */

        public function mi_get_plugin_path($first_part_file_name, $second_part_file_name = null)
        {

            $this->firstPart = $first_part_file_name;
            $this->secondPart = $second_part_file_name;

            $this->fullName = $first_part_file_name;

            if ($this->secondPart) {

                $this->fullName = $first_part_file_name . "-" . $second_part_file_name;
            }


            $this->pathName = plugin_dir_path(__FILE__);


            return realpath($this->pathName) . '/' . $this->fullName . '.php';

        }


        /*
       * get Option value
       * */

        private function get_option_value()
        {
            return get_option('logo_items');

        }

        /**
         * implode funcion to implode array and data class
         */
        public function mi_get_class($array_value, $string = null)
        {

            $this->class = (array)$array_value;

            return trim(implode($this->class, ' ') . " " . $string);

        }


        /**
         * mi logo shortcode function caling . call [mi-logo id= ""]
         */

        public function mi_logo_shortcode_callback($atts, $content = null)
        {

            $default = shortcode_atts(array(
                'id' => null
            ), $atts);


            $mi_logo_id = $default['id'];
            $mi_logo_value = $this->get_option_value();
            $mi_logo_values_id = array_keys($mi_logo_value);
            $mi_valid_logo = in_array($mi_logo_id, $mi_logo_values_id);


            if ($mi_logo_id && $mi_valid_logo) {


                $mi_logo_inner_value = $mi_logo_value[$mi_logo_id];
                $mi_logo_logos = $mi_logo_inner_value['logo'];
                $mi_logo_cats = $mi_logo_inner_value['category'];
                $mi_logo_settings = $mi_logo_inner_value['settings'];
                if (count($mi_logo_settings) > 1):
                    $mi_logo_gutter = $mi_logo_settings['mi_logo_gutter'];
                    $mi_logo_image_width = $mi_logo_settings['image_width'];
                    $mi_logo_slider_dot_option = $mi_logo_settings['dot_option'];
                    $mi_logo_slider_dot_position = $mi_logo_settings['dot_position'];
                    $mi_logo_slider_nav_option = $mi_logo_settings['nav_option'];
                    $mi_logo_slider_nav_position = $mi_logo_settings['nav_position_h'];
                    $mi_logo_display_mode = $mi_logo_settings['display_mode'];
                    $mi_logo_layout = $mi_logo_settings['layout_selection'];
                    $mi_logo_bg_layout = $mi_logo_settings['layout_background_color'];
                    $mi_logo_bg_h_layout = $mi_logo_settings['layout_background_h_color'];
                    $mi_logo_border_layout = $mi_logo_settings['layout_border_color'];
                    $mi_logo_border_h_layout = $mi_logo_settings['layout_border_h_color'];
                    $mi_logo_text_layout = $mi_logo_settings['layout_text_color'];
                    $mi_logo_text_h_layout = $mi_logo_settings['layout_text_h_color'];
                    $mi_logo_slider_txt_color = $mi_logo_settings['slider_txt_color'];
                    $mi_logo_slider_txt_h_color = $mi_logo_settings['slider_txt_h_color'];
                    $mi_logo_slider_bg_color = $mi_logo_settings['slider_bg_color'];
                    $mi_logo_slider_bg_h_color = $mi_logo_settings['slider_bg_h_color'];
                    $mi_logo_autoplay = $mi_logo_settings['mi_slider_autotplay'];
                    $mi_logo_autoplay_speed = $mi_logo_settings['mi_slider_autoplay_speed'];
                    $mi_logo_nav_speed = $mi_logo_settings['mi_slider_nav_speed'];
                    $mi_logo_dot_speed = $mi_logo_settings['mi_slider_dot_speed'];
                    $mi_logo_link_attr = $mi_logo_settings['link_attr'];
                    $mi_nav_inner = $mi_logo_settings['nav_inner'];
                    if (empty($mi_logo_autoplay_speed)) {
                        $mi_logo_autoplay_speed = 400;
                    }
                    if (empty($mi_logo_nav_speed)) {
                        $mi_logo_nav_speed = 400;
                    }
                    if (empty($mi_logo_dot_speed)) {
                        $mi_logo_dot_speed = 400;
                    }


                    // Grid Class
                    $mi_lg_grid_number = ' mi-logo-col-lg-' . $mi_logo_settings['large_desktop_number_of_grid'];
                    $mi_md_grid_number = ' mi-logo-col-md-' . $mi_logo_settings['desktop_number_of_grid'];
                    $mi_sm_grid_number = ' mi-logo-col-sm-' . $mi_logo_settings['tab_number_of_grid'];
                    $mi_xs_grid_number = ' mi-logo-col-xs-' . $mi_logo_settings['mobile_number_of_grid'];
                    $mi_xxs_grid_number = 'mi-logo-col-xxs-' . $mi_logo_settings['small_mobile_number_of_grid'];

                    //Dynamic Class

                    $mi_logo_parent_wrap_class_default = ' mi-logo-slider-plugin ';
                    $mi_logo_parent_wrap_class = array();
                    $mi_logo_wrap_class_default = ' mi-logo-slider-plugin mi-logo-container-' . $mi_logo_id . " ";
                    $mi_logo_wrap_class = array();
                    $mi_logo_filter_button_class_default = '';
                    $mi_logo_filter_button_class = array();
                    $mi_logo_v_slider_class_default = '';
                    $mi_logo_v_slider_class = array();
                    $mi_logo_container_class_default = '';
                    $mi_logo_container_class = array();
                    $mi_logo_list_container_defalut = '';
                    $mi_logo_list_container = array();
                    $mi_logo_image_list_class_default = ' mi-logo__style-default';
                    $mi_logo_image_list_class = array();
                    $mi_logo_list_layout_class_default = ' mi-logo-list  ';
                    $mi_logo_list_layout_class = array();
                    $mi_logo_filter_trigger_default_calss = ' mi-logo-filter-trigger';
                    $mi_logo_filter_trigger = array();
                    $mi_h_slider_default_class = ' mi-owl-carousel';
                    $mi_h_slider_class = array();
                    $mi_display_default_class = '';
                    $mi_grid_display = array('mi-logo__item');
                    $mi_elelment_default_class = ' mi-logo__style-default';
                    $mi_elelment_class = array();
                    $mi_image_class = array('mi-logo__image');
                    $mi_image_title = array('mi-logo__image-title');
                    $mi_image_wrapper_default = '';
                    $mi_image_wrapper = array();

                    $mi_logo_special_style = null;
                    $mi_logo_list_special_style = null;


                    if ($mi_logo_layout == 'simple') {
                        $mi_elelment_default_class .= ' mi-logo__style-simple';
                        $mi_logo_list_layout_class_default .= ' mi-logo-list--style-default';

                    }

                    if ($mi_logo_display_mode == 'filter') {
                        $mi_display_default_class .= ' mi-logo-grid__item';
                        $mi_logo_container_class_default .= "mi-logo-grid mi-logo mi-logo-row-" . $mi_logo_gutter;
                        $mi_logo_wrap_class_default .= " mi-logo-filter-wrapper";
                    }
                    if ($mi_logo_display_mode == 'grid') {
                        $mi_logo_container_class_default .= "mi-logo mi-logo-row-" . $mi_logo_gutter;
                        $mi_logo_wrap_class_default .= " mi-logo-display-grid";
                    }

                    if ($mi_logo_display_mode == 'v-slider') {
                        $mi_logo_v_slider_class_default .= " mi-logo-slick-carousel  ";

                        $mi_logo_special_style = "v-slider";

                        if ($mi_logo_layout == 'box_with_title') {

                            $mi_logo_list_special_style = "box-title";
                        }
                    }
                    $mi_logo_wrap_class_default .= " mi-logo-layout-" . $mi_logo_layout;

                    if ($mi_logo_display_mode == 'h-slider') {

                        $mi_logo_parent_wrap_class_default .= " mi-logo-horizontal-slider ";

                        if ($mi_logo_slider_dot_position == "center" && $mi_logo_slider_nav_position == "bottom") {
                            $mi_h_slider_default_class .= "  mi-owl--dots-nav-bottom";
                        }

                        if (!$mi_logo_slider_dot_option) {
                            $mi_h_slider_default_class .= ' mi-logo mi-owl--dots-hidden ';
                            $mi_logo_v_slider_dots = "false";
                        } else {
                            $mi_logo_v_slider_dots = "true";
                            $mi_logo_wrap_class_default .= " mi-logo-slider-dot-active";
                        }

                        $mi_logo_wrap_class_default .= " mi-logo-display-horizontal-slider";
                        if ($mi_logo_slider_dot_option) {
                            switch ($mi_logo_slider_dot_position) {
                                case "right":
                                    $mi_h_slider_default_class .= ' mi-owl--dots-right ';
                                    break;
                                case "left":
                                    $mi_h_slider_default_class .= ' mi-owl--dots-left ';
                                    break;
                                default:
                                    $mi_h_slider_default_class .= ' mi-owl--dots-center ';


                            }
                        }

                        if (!$mi_logo_slider_nav_option) {
                            $mi_h_slider_default_class .= ' mi-logo mi-owl--nav-hidden ';
                            $mi_logo_v_slider_navs = "false";

                        } else {

                            $mi_logo_v_slider_navs = "true";

                            $mi_logo_wrap_class_default .= " mi-logo-slider-nav-active";

                            switch ($mi_logo_slider_nav_position) {
                                case "right":
                                    $mi_h_slider_default_class .= ' mi-owl--nav-right ';
                                    if( $mi_nav_inner == 'on'){
                                        $mi_logo_parent_wrap_class_default .= " mi-logo-inner-nav mi-logo-inner-nav-right";
                                    }
                                    break;
                                case "bottom":
                                    $mi_h_slider_default_class .= ' mi-owl--nav-bottom-center ';
                                    break;
                                default:
                                    if( $mi_nav_inner == 'on'){
                                        $mi_logo_parent_wrap_class_default .= " mi-logo-inner-nav ";
                                    }
                                    $mi_h_slider_default_class .= ' mi-owl--nav-both-side ';

                            }
                        }

                    }


                    $mi_logo_list_layout_class_default .= ' mi_logo_generate_class-' . $mi_logo_id . ' ';
                    if ($mi_logo_layout == 'box-hover-color') {
                        $mi_elelment_default_class .= ' mi-logo__style--hover-background';
                        $mi_logo_list_layout_class_default .= ' mi-logo-list--style-border-hover-bacground ';
                    }

                    if ($mi_logo_layout == 'box-opacity') {
                        $mi_elelment_default_class .= ' mi-logo__style--opacity';
                    }

                    if ($mi_logo_layout == 'box-blur') {
                        $mi_elelment_default_class .= ' mi-logo__style--blur';
                    }

                    if ($mi_logo_layout == 'box-gray-style') {
                        $mi_elelment_default_class .= ' mi-logo__style--grayscale';
                        $mi_logo_list_layout_class_default .= ' mi-logo-list--style-grayscale';
                    }

                    if ($mi_logo_layout == 'round-shadow-effect') {
                        $mi_elelment_default_class .= ' mi-logo__style--circle';
                        $mi_display_default_class .= ' mi-logo__item--equal-width-height';
                        $mi_logo_list_layout_class_default .= ' mi-logo-list--style-circle ';
                        $mi_logo_image_list_class_default .= ' mi-logo__style--circle';
                        $mi_logo_list_container_defalut .= ' mi-logo-list-container--large';
                        $mi_logo_image_list_class_default .= ' mi_logo_generate_class-' . $mi_logo_id . ' ';

                    }

                    if ($mi_logo_layout == 'inner-shadow') {
                        $mi_elelment_default_class .= ' mi-logo__style--inner-shadow';

                    }

                    if ($mi_logo_layout == 'outer-shadow') {
                        $mi_elelment_default_class .= ' mi-logo__style--tooltip-outside';
                        $mi_logo_list_layout_class_default .= ' mi-logo-list--style-shadow ';
                        $mi_logo_list_layout_class_default .= ' mi_logo_generate_class-' . $mi_logo_id . ' ';

                    }

                    if ($mi_logo_layout == 'box_with_title') {
                        $mi_elelment_default_class .= ' mi-logo__style--with-title';
                        $mi_logo_special_style = 'box-title';
                        $mi_image_wrapper_default .= "mi-logo__image-wrapper";

                    }

                    $mi_elelment_default_class .= " mi_logo_generate_class-" . $mi_logo_id . ' ';

                    $mi_logo_filter_button_class_default .= '  mi-logo-btn mi-logo-btn--green';

                    array_push($mi_logo_parent_wrap_class, $mi_logo_parent_wrap_class_default);
                    array_push($mi_logo_wrap_class, $mi_logo_wrap_class_default);
                    array_push($mi_logo_v_slider_class, $mi_logo_v_slider_class_default);
                    array_push($mi_logo_filter_button_class, $mi_logo_filter_button_class_default);
                    array_push($mi_logo_container_class, $mi_logo_container_class_default);
                    array_push($mi_logo_list_container, $mi_logo_list_container_defalut);
                    array_push($mi_logo_image_list_class, $mi_logo_image_list_class_default);
                    array_push($mi_logo_list_layout_class, $mi_logo_list_layout_class_default);
                    array_push($mi_logo_filter_trigger, $mi_logo_filter_trigger_default_calss);
                    array_push($mi_h_slider_class, $mi_h_slider_default_class);
                    array_push($mi_grid_display, $mi_xxs_grid_number, $mi_xs_grid_number, $mi_sm_grid_number, $mi_md_grid_number, $mi_lg_grid_number, $mi_display_default_class);
                    array_push($mi_elelment_class, $mi_elelment_default_class);
                    array_push($mi_image_wrapper, $mi_image_wrapper_default);


                    ob_start();

                    include($this->mi_get_plugin_path('display-mode/' . $mi_logo_display_mode));

                    ?>
                    <style>

                        .mi_logo_generate_class-<?php echo esc_attr($mi_logo_id); ?> .mi-logo__image img {
                            max-width: <?php echo esc_attr($mi_logo_image_width); ?>;
                            max-height: <?php echo esc_attr($mi_logo_image_width); ?>;
                        }

                        <?php if($mi_logo_layout != 'simple' && $mi_logo_layout != 'box-gray-style' ):?>
                        .mi_logo_generate_class-<?php echo esc_attr($mi_logo_id); ?> {
                            background: <?php echo esc_attr($mi_logo_bg_layout ); ?>;
                            border-color: <?php echo esc_attr($mi_logo_border_layout); ?>;
                        }

                        <?php endif; ?>

                        .mi-logo-container-<?php echo esc_attr($mi_logo_id); ?> .mi-owl-prev, .mi-logo-container-<?php echo esc_attr($mi_logo_id); ?> .mi-owl-next, .mi-logo-container-<?php echo esc_attr($mi_logo_id); ?> .mi-logo-slick-arrow, .mi-logo-container-<?php echo esc_attr($mi_logo_id); ?> .mi-owl-dot.active, .mi-logo-container-<?php echo $mi_logo_id; ?> .mi-owl-dot:hover, .mi-logo-container-<?php echo esc_attr($mi_logo_id); ?> .mi-logo-slick-pagination li.mi-logo-slick-active, .mi-logo-container-<?php echo esc_attr($mi_logo_id); ?> .mi-logo-slick-pagination li:hover {

                            background: <?php echo esc_attr($mi_logo_slider_bg_color); ?>;
                            color: <?php echo esc_attr($mi_logo_slider_txt_color); ?>;

                        }

                        .mi-logo-container-<?php echo esc_attr($mi_logo_id); ?> .mi-owl-prev:hover, .mi-logo-container-<?php echo esc_attr($mi_logo_id); ?> .mi-owl-next:hover, .mi-logo-container-<?php echo esc_attr($mi_logo_id); ?> .mi-logo-slick-arrow:hover {
                            background: <?php echo esc_attr($mi_logo_slider_bg_h_color); ?>;
                            color: <?php echo esc_attr($mi_logo_slider_txt_h_color); ?>;
                        }

                    </style>

                    <?php

                    $output = ob_get_clean();

                    return $output;
                endif;

            };


        }

        /**
         * Register the stylesheets for the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function enqueue_styles()
        {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Mi_Plugin_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Mi_Plugin_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
            wp_enqueue_style('mi-owl-css', plugin_dir_url(__FILE__) . 'css/owl.carousel.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/mi-plugin-public.css', array(), $this->version, 'all');

        }

        /**
         * Register the JavaScript for the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts()
        {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Mi_Plugin_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Mi_Plugin_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */


            wp_enqueue_script('mi-owl-js', plugin_dir_url(__FILE__) . 'js/owl.carousel.js', array('jquery'), $this->version, false);


            wp_enqueue_script('mi-logo-default-script', plugin_dir_url(__FILE__) . 'js/mi-plugin-public.js', array('jquery', 'mi-owl-js'), $this->version, false);


        }

    }
endif;