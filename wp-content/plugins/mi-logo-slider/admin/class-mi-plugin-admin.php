<?php
ob_start();

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://miplugins.com
 * @since      1.0.0
 *
 * @package    Mi_Pro_Plugin
 * @subpackage Mi_Pro_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mi_Pro_Plugin
 * @subpackage Mi_Pro_Plugin/admin
 * @author     Mi Plugins <miplugins@gmail.com>
 */
class Mi_Plugin_Admin
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
     * The basename of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $basename The basename of the plugin.
     */
    protected $basename;


    /**
     * @var mi_public default valuse
     */


    protected $mi_public_defaults_values = array(
        'display_mode'  => array('h-slider','grid'),
        'gutter'        => array(0,5,10,15),
        'grid'          => array(1,2,3,4,5,6,7,8),
        'layout'        => array('simple','box'),
        'position'      => array('left','center','right'),
        'nav-position'  => array('both-side','right','bottom'),
        'bool'          => array("true","false")
    );

    public $slides = array();


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version, $basename)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->basename = $basename;
        $this->register_option_key();


    }

    /*
     * get Option value
     * */
    private function get_option_value()
    {
        return get_option('logo_items');

    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/mi-plugin-admin.css', array(), $this->version, 'all');

    }


    /**
     * Add an setting page
     *
     * @since  1.0.0
     */
    public function add_settings_page()
    {

        $page_title = esc_html__('MI Logo', 'mi_logo');
        $menu_title = esc_html__('MI Logo', 'mi_logo');
        $capability = 'manage_options';
        $menu_slug = $this->plugin_name;
        $function = array($this, 'display_options_page_main');
        $icon_url = '';
        $position = 100;
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);



        $parent_slug = $this->plugin_name;
        $page_title = esc_html__('Get Premium', 'mi_logo');
        $menu_title = esc_html__('Get Premium', 'mi_logo');
        $capability = 'manage_options';
        $menu_slug = 'mi_logo_premium';
        $function = array($this, 'display_premium_page');
        add_submenu_page($this->plugin_name, $page_title, $menu_title, $capability, $menu_slug, $function);



        $parent_slug = $this->plugin_name;
        $page_title = esc_html__('Mi Others', 'mi_logo');
        $menu_title = esc_html__('Mi Others', 'mi_logo');
        $capability = 'manage_options';
        $menu_slug = 'mi_logo_others';
        $function = array($this, 'display_other_page');
        add_submenu_page($this->plugin_name, $page_title, $menu_title, $capability, $menu_slug, $function);


    }

    function register_option_key()
    {
        $d_uid = $this->mi_uid();

        $logo_items[$d_uid] = array(
            'title' => 'no title',
            'shortcode' => '[mi-logo id =' . $d_uid . ']',
            'logo' => array(),
            'category' => null,
            'settings' => array(
                
            ),
        );
        add_option('logo_items', $logo_items);

        if(count($this->get_option_value())==0){
            update_option('logo_items', $logo_items);
        }
    }

    /**
     * Unique Id Generator
     **/

    public function mi_uid()
    {
        return time().mt_rand();
    }

    function show_row_form($id = null)
    {

        $title = '';
        $encoded_image = '';
        $encoded_cat = '';
        $unique_id = (int) $_REQUEST['new_logo_id'];
        $optionValue = $this->get_option_value()[$id];
        $genarated_shortcode = '[mi-logo id=' . $unique_id . ']';
        $title = $optionValue['title'];
        $uploded_image = $optionValue['logo'];
        $encoded_image = json_encode($uploded_image, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        $settings_value = $optionValue['settings'];

        if (count($settings_value) > 0) {

            $mi_prev_display_mode = $settings_value['display_mode'];
            $mi_prev_layout_selection = $settings_value['layout_selection'];
            $mi_prev_dot_option = $settings_value['dot_option'];
            if ($mi_prev_dot_option ) {
                $mi_prev_dot_option = "checked";
            }
            $mi_prev_nav_option = $settings_value['nav_option'];

            if ($mi_prev_nav_option ) {
                $mi_prev_nav_option = "checked";
            }

            $mi_prev_dot_position = $settings_value['dot_position'];
            $mi_prev_nav_position_h = $settings_value['nav_position_h'];
            $mi_prev_nav_position_v = $settings_value['nav_position_v'];
            $mi_prev_slider_txt_color = $settings_value['slider_txt_color'];
            $mi_prev_slider_bg_color = $settings_value['slider_bg_color'];
            $mi_prev_slider_txt_h_color = $settings_value['slider_txt_h_color'];
            $mi_prev_slider_bg_h_color = $settings_value['slider_bg_h_color'];
            $mi_prev_filter_button_position = $settings_value['filter_button_position'];
            $mi_prev_filter_bg_color = $settings_value['filter_bg_color'];
            $mi_prev_filter_txt_color = $settings_value['filter_txt_color'];
            $mi_prev_filter_text_h_color = $settings_value['filter_text_h_color'];
            $mi_prev_filter_bg_h_color = $settings_value['filter_bg_h_color'];
            $mi_prev_filter_text_a_color = $settings_value['filter_text_a_color'];
            $mi_prev_filter_bg_a_color = $settings_value['filter_bg_a_color'];
            $mi_prev_large_desktop_number_of_grid = $settings_value['large_desktop_number_of_grid'];
            $mi_prev_desktop_number_of_grid = $settings_value['desktop_number_of_grid'];
            $mi_prev_tab_number_of_grid = $settings_value['tab_number_of_grid'];
            $mi_prev_mobile_number_of_grid = $settings_value['mobile_number_of_grid'];
            $mi_prev_small_mobile_number_of_grid = $settings_value['small_mobile_number_of_grid'];
            $mi_prev_layout_background_color = $settings_value['layout_background_color'];
            $mi_prev_layout_border_color = $settings_value['layout_border_color'];
            $mi_prev_layout_background_h_color = $settings_value['layout_background_h_color'];
            $mi_prev_layout_border_h_color = $settings_value['layout_border_h_color'];
            $mi_prev_tooltip_option = $settings_value['tooltip_option'];
            $mi_prev_gutter = $settings_value['mi_logo_gutter'];
            $mi_prev_image_width = $settings_value['image_width'];
            $mi_prev_autoplay = $settings_value['mi_slider_autotplay'];
            $mi_prev_nav_speed = $settings_value['mi_slider_nav_speed'];
            $mi_prev_dot_speed = $settings_value['mi_slider_dot_speed'];
            $mi_prev_autoplay_speed = $settings_value['mi_slider_autoplay_speed'];
            $mi_prev_link_attr = $settings_value['link_attr'];
            $mi_prev_nav_inner = $settings_value['nav_inner'];

        } else {

            $mi_prev_display_mode = '';
            $mi_prev_layout_selection = '';
            $mi_prev_dot_option = '';
            $mi_prev_nav_option = '';
            $mi_prev_dot_position = '';
            $mi_prev_nav_position_h = '';
            $mi_prev_nav_position_v = '';
            $mi_prev_slider_txt_color = '';
            $mi_prev_slider_bg_color = '';
            $mi_prev_slider_txt_h_color = '';
            $mi_prev_slider_bg_h_color = '';
            $mi_prev_filter_txt_color = '';
            $mi_prev_filter_button_position = '';
            $mi_prev_filter_bg_color = '';
            $mi_prev_filter_text_h_color = '';
            $mi_prev_filter_bg_h_color = '';
            $mi_prev_filter_text_a_color = '';
            $mi_prev_filter_bg_a_color = '';
            $mi_prev_large_desktop_number_of_grid = 5;
            $mi_prev_desktop_number_of_grid = 4;
            $mi_prev_tab_number_of_grid = 3;
            $mi_prev_mobile_number_of_grid = 2;
            $mi_prev_small_mobile_number_of_grid = 1;
            $mi_prev_layout_background_color = '';
            $mi_prev_layout_border_color = '';
            $mi_prev_layout_background_h_color = '';
            $mi_prev_layout_border_h_color = '';
            $mi_prev_tooltip_option = '';
            $mi_prev_list_view = '';
            $mi_prev_gutter = '';
            $mi_prev_item_width = '';
            $mi_prev_v_scroll_item = '';
            $mi_prev_v_display_item = '';
            $mi_prev_layout_text_color = '';
            $mi_prev_layout_text_h_color = '';
            $mi_prev_image_width = '80px';
            $mi_prev_autoplay = false;
            $mi_prev_autoplay_speed = 3000;
            $mi_prev_nav_speed = 500;
            $mi_prev_dot_speed = 500;
            $mi_prev_link_attr = true;
            $mi_prev_nav_inner = true;
        } //if settings value set
        if($mi_prev_link_attr){
            $mi_prev_link_attr = 'checked';
        }
        if ($mi_prev_nav_inner ) {
            $mi_prev_nav_inner = "checked";
        }
        if ($mi_prev_autoplay ) {
            $mi_prev_autoplay = "checked";
        }
        if ($mi_prev_tooltip_option ) {
            $mi_prev_tooltip_option = "checked";
        }

        ?>

        <form  action="admin.php?page=<?php echo $this->plugin_name ?>&action=submit_data" method="post"
              enctype="multipart/form-data">
            <fieldset class="new-slide">
                <div class="wrap">
                    <h1><?php esc_html_e('Add New Row', 'mi_logo'); ?></h1>
                </div>

                <div id="titlediv">
                    <div id="titlewrap">
                        <?php wp_nonce_field($this->plugin_name,'mi-logo_slider_nonce');?>
                        <input type='hidden' name=<?php echo "new_logo_id"; ?> placeholder="Title"
                               value='<?php echo esc_html($unique_id); ?>' readonly/>
                        <label class="screen-reader-text" id="title-prompt-text" for="title"><?php esc_html_e('Enter title here', 'mi_logo'); ?></label>
                        <input type='text' id="title" size="30" class="v"
                               name=<?php echo "new_logo_title"; ?> placeholder="Title"
                               value='<?php echo esc_html($title); ?>'/>
                        <div class="mi-innner-shortcode">
                            <span>Shortcode: </span>
                            <input type='text' size="35"
                               name="mi_logo_shortcode" onfocus="this.select();" value='<?php echo $genarated_shortcode; ?>'
                               readonly/>
                        </div>

                    </div>

                </div>


            </fieldset>
            <div class="mi-tab tabs-style-flip">

                <ul class="mi-logo-slider-tabs-control">
                    <li class="tab-current"><a href="#mi-logo-slider-tab-logos"><?php esc_html_e('Add Logos', 'mi_logo'); ?></a></li>
                    <li><a href="#mi-logo-slider-tab-settings"><?php esc_html_e('Settings', 'mi_logo'); ?></a></li>
                </ul>

                <div class="mi-logo-slider-tabs-content">

                    <section id="mi-logo-slider-tab-logos" class="content-current">
                        <fieldset class="logosection">


                            <?php wp_enqueue_media(); ?>

                            <table class='wp-list-table widefat fixed striped mi-plugin-table image-preview-wrapper'>
                                <thead>
                                <tr>
                                    <th scope="col" class="column-image"><?php esc_html_e('Image', 'mi_logo'); ?></th>
                                    <th scope="col" class="column-title"><?php esc_html_e('Title', 'mi_logo'); ?></th>
                                    <th scope="col" class="column-link"><?php esc_html_e('Link', 'mi_logo'); ?></th>
                                    <th scope="col" class="column-action"><?php esc_html_e('Remove', 'mi_logo'); ?></th>


                                </tr>
                                </thead>
                                <tbody id="image-list">
                                <?php if ($encoded_image !== '' && isset($uploded_image)):

                                    foreach ($uploded_image as $item):
                                        ?>


                                        <tr class="mi-logo-slider-single-logo logo-id-<?php echo $item->id; ?>"
                                            data-logo-id="<?php echo esc_attr($item->id); ?>">
                                            <td class="column-thumb" data-colname="Title"><img width="40px"
                                                                                               height="40px"
                                                                                               src="<?php echo $item->url; ?>"
                                                                                               alt=""><input
                                                    type="hidden"
                                                    name="mi-logo-url"
                                                    value="<?php echo esc_attr($item->url); ?>">
                                            </td>
                                            <td class="column-title"><input class="logo-title" type="text"
                                                                            name="mi-logo-title"
                                                                            value="<?php echo $item->title; ?>"
                                                                            placeholder="Logo Title"></td>


                                            <td class="column-link"><input class="logo-link" type="text"
                                                                           name="mi-logo-link"
                                                                           placeholder="http://"
                                                                           value="<?php echo $item->link; ?>">
                                            </td>
                                            <td class="column-action"><a href="#" class="mi-logo-remove mi-logo-color-danger"><span class="dashicons dashicons-no-alt""></span></a></td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>
                                </tbody>

                            </table>
                            <input id="upload_image_button" type="button" class="button"
                                   value="<?php _e('Upload image'); ?>"/>
                            <input type='hidden' name='cp_image_data' id='cp_image_data'
                                   value='<?php echo $encoded_image; ?>'>

                        </fieldset>
                    </section>
                    <section id="mi-logo-slider-tab-settings">
                        <fieldset class="settings">
                            <table class="form-table">
                                <tr>
                                    <th>
                                        <label for="display_mode"> <?php esc_html_e('Display Mode', 'mi_logo'); ?></label>
                                    </th>
                                    <td>
                                        
                                        <select name="display_mode" id="display_mode">

                                            <option

                                                value="h-slider" <?php echo ($mi_prev_display_mode == 'h-slider') ? "selected" : "" ?>>
                                                <?php esc_html_e('Horizental Slider', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="grid" <?php echo ($mi_prev_display_mode == 'grid') ? "selected" : "" ?>>
                                                <?php esc_html_e('Grid', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="v-slider" disabled>
                                                <?php esc_html_e('Vertical Slider', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="filter" disabled>
                                                <?php esc_html_e('Filter', 'mi_logo'); ?>
                                            </option>

                                            <option
                                                value="list" disabled>
                                                <?php esc_html_e('List', 'mi_logo'); ?>
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <table class="form-table slider-settings">
                                <h2 class="title mt-40 slider-settings"><?php esc_html_e('Slider Settings', 'mi_logo'); ?></h2>

                                <tr class="autoplay">
                                    <th>
                                        <label for="mi-logo-autoplay"> <?php esc_html_e('Autoplay', 'mi_logo'); ?> </label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="mi_slider_autoplay"
                                               id="mi-logo-autoplay" <?php echo $mi_prev_autoplay; ?>>
                                    </td>
                                </tr>

                                <tr class="autoplay-speed">
                                    <th>
                                        <label for="mi-logo-autoplay-speed"> <?php esc_html_e('Autoplay Speed', 'mi_logo'); ?> </label>
                                    </th>
                                    <td>
                                        <input type="number" name="mi_slider_autoplay_speed"
                                               id="mi-logo-autoplay-speed" value="<?php echo $mi_prev_autoplay_speed; ?>">
                                        <p class="description"><?php esc_html_e('Ex:500', 'mi_logo'); ?></p>
                                    </td>
                                </tr>


                                <tr class="dot-view">

                                    <th>
                                        <label for="dot_option"> <?php esc_html_e('Dot View', 'mi_logo'); ?> </label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="dot_option"
                                               id="dot_option" <?php echo $mi_prev_dot_option; ?>>
                                    </td>

                                </tr>
                                <tr class="dot-speed">
                                    <th>
                                        <label for="mi-logo-dot-speed"> <?php esc_html_e('dot Speed', 'mi_logo'); ?> </label>
                                    </th>
                                    <td>
                                        <input type="number" name="mi_slider_dot_speed"
                                               id="mi-logo-dot-speed" value="<?php echo $mi_prev_dot_speed; ?>">
                                        <p class="description"><?php esc_html_e('Ex:500', 'mi_logo'); ?></p>
                                    </td>
                                </tr>

                                <tr class="dot-position">
                                    <th>
                                        <label for="dot_position"> <?php esc_html_e('Dot Position', 'mi_logo'); ?></label>
                                    </th>
                                    <td>
                                        <select name="dot_position" id="dot_position">

                                            <option
                                                value="left" <?php echo ($mi_prev_dot_position == 'left') ? "selected" : "" ?>>
                                                <?php esc_html_e('Left', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="center" <?php echo ($mi_prev_dot_position == 'center') ? "selected" : "" ?>>
                                                <?php esc_html_e('Center', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="right" <?php echo ($mi_prev_dot_position == 'right') ? "selected" : "" ?>>
                                                <?php esc_html_e('Right', 'mi_logo'); ?>
                                            </option>
                                        </select>
                                    </td>

                                </tr>


                                <tr class="nav-view">

                                    <th>
                                        <label for="nav_option"> <?php esc_html_e('Nav View', 'mi_logo'); ?> </label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="nav_option"
                                               id="nav_option" <?php echo $mi_prev_nav_option; ?>>
                                    </td>

                                </tr>
                                <tr class="nav-speed">
                                    <th>
                                        <label for="mi-logo-nav-speed"> <?php esc_html_e('Nav Speed', 'mi_logo'); ?> </label>
                                    </th>
                                    <td>
                                        <input type="number" name="mi_slider_nav_speed"
                                               id="mi-logo-nav-speed" value="<?php echo $mi_prev_nav_speed; ?>">
                                        <p class="description"><?php esc_html_e('Ex:500', 'mi_logo'); ?></p>
                                    </td>
                                </tr>
                                <tr class="nav-position-horizontal">
                                    <th>
                                        <label for="nav-position-h"> <?php esc_html_e('Nav Position', 'mi_logo'); ?></label>
                                    </th>
                                    <td class="nav-position-horizontal">
                                        <select name="nav_position_h" id="nav-position-h">

                                            <option
                                                value="both-side" <?php echo ($mi_prev_nav_position_h == 'both-side') ? "selected" : "" ?>>
                                                <?php esc_html_e('Both Side', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="right" <?php echo ($mi_prev_nav_position_h == 'right') ? "selected" : "" ?>>
                                                <?php esc_html_e('Right', 'mi_logo'); ?>
                                            </option>

                                            <option
                                                value="bottom" <?php echo ($mi_prev_nav_position_h == 'bottom') ? "selected" : "" ?>>
                                                <?php esc_html_e('Bottom Center', 'mi_logo'); ?>
                                            </option>
                                        </select>
                                    </td>
                                </tr>

                                <tr class="nav-inner">
                                    <th>
                                        <label for="mi-logo-nav-inner"> <?php esc_html_e('Nav Within Container', 'mi_logo'); ?> </label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="nav_inner"
                                               id="mi-logo-nav-inner" <?php echo esc_html($mi_prev_nav_inner); ?>>
                                        <p class="description"> <?php esc_html_e('This option allow you to include the nav in the container', 'mi_logo'); ?></p>

                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2"><h2 class="title mt-40 custom-margin"><?php esc_html_e('Slider Nav/Dots Design Option', 'mi_logo'); ?></h2></td>
                                </tr>
                                <tr>
                                    <th><label for="slider_text_color"> <?php esc_html_e('Text Color', 'mi_logo'); ?></label></th>
                                    <td><input type="text" name="slider_txt_color" class="color-field slider-txt-color"
                                               id="slider-text-color" value="<?php echo $mi_prev_slider_txt_color; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="slider_bg_color"> <?php esc_html_e('Background Color', 'mi_logo'); ?></label></th>
                                    <td><input type="text" name="slider_bg_color" class="color-field slider-bg-color"
                                               id="slider-bg-color" value="<?php echo $mi_prev_slider_bg_color; ?>">
                                    </td>
                                </tr>

                                <tr>
                                    <th><label for="slider-text-h-color"> <?php esc_html_e('Text Hover Color', 'mi_logo'); ?></label></th>
                                    <td><input type="text" name="slider_txt_h_color"
                                               class="color-field slider-txt-h-color"
                                               id="text-h-color" value="<?php echo $mi_prev_slider_txt_h_color; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="slider-bg-h-color"> <?php esc_html_e('Background Hover Color', 'mi_logo'); ?></label></th>
                                    <td><input type="text" name="slider_bg_h_color"
                                               class="color-field slider-bg-h-color" id="slider-bg-h-color"
                                               value="<?php echo $mi_prev_slider_bg_h_color; ?>"></td>
                                </tr>


                            </table>

                            <table class="form-table responsive-design">
                                <h2 class="title mt-40 responsive-design"><?php esc_html_e('Visibility Settings', 'mi_logo'); ?></h2>
                                <tr class="mi-logo-gutter">
                                    <th>
                                        <label for="mi_logo_gutter"> <?php esc_html_e('Gutter', 'mi_logo'); ?></label>
                                    </th>
                                    <td class="nav-position-h">
                                        <select name="mi_logo_gutter" id="mi_logo_gutter">

                                            <option
                                                value="0" <?php echo ($mi_prev_gutter == '0') ? "selected" : "" ?>>
                                                <?php esc_html_e(' 0', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="5" <?php echo ($mi_prev_gutter == '5') ? "selected" : "" ?>>
                                                <?php esc_html_e('5', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="10" <?php echo ($mi_prev_gutter == '10') ? "selected" : "" ?>>
                                                <?php esc_html_e('10', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="15" <?php echo ($mi_prev_gutter == '15') ? "selected" : "" ?>>
                                                <?php esc_html_e('15', 'mi_logo'); ?>
                                            </option>


                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="larger-desktop-view"><?php esc_html_e('Large Desktop Grid Number', 'mi_logo'); ?></label>
                                    </th>
                                    <td class="nav-position-h">
                                        <select name="large_desktop_number_of_grid" id="larger-desktop-view">

                                            <option
                                                value="1" <?php echo ($mi_prev_large_desktop_number_of_grid == '1') ? "selected" : "" ?>>
                                                <?php esc_html_e('1', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="2" <?php echo ($mi_prev_large_desktop_number_of_grid == '2') ? "selected" : "" ?>>
                                                <?php esc_html_e(' 2', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="3" <?php echo ($mi_prev_large_desktop_number_of_grid == '3') ? "selected" : "" ?>>
                                                <?php esc_html_e('3', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="4" <?php echo ($mi_prev_large_desktop_number_of_grid == '4') ? "selected" : "" ?>>
                                                <?php esc_html_e('4', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="5" <?php echo ($mi_prev_large_desktop_number_of_grid == '5') ? "selected" : "" ?>>
                                                <?php esc_html_e('5', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="6" <?php echo ($mi_prev_large_desktop_number_of_grid == '6') ? "selected" : "" ?>>
                                                <?php esc_html_e('6', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="7" <?php echo ($mi_prev_large_desktop_number_of_grid == '7') ? "selected" : "" ?>>
                                                <?php esc_html_e('7', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="8" <?php echo ($mi_prev_large_desktop_number_of_grid == '8') ? "selected" : "" ?>>
                                                <?php esc_html_e('8', 'mi_logo'); ?>
                                            </option>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="desktop-view"> <?php esc_html_e('Desktop Grid Number', 'mi_logo'); ?></label>
                                    </th>
                                    <td class="nav-position-h">
                                        <select name="desktop_number_of_grid" id="desktop-view">

                                            <option
                                                value="1" <?php echo ($mi_prev_desktop_number_of_grid == '1') ? "selected" : "" ?>>
                                                <?php esc_html_e('1', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="2" <?php echo ($mi_prev_desktop_number_of_grid == '2') ? "selected" : "" ?>>
                                                <?php esc_html_e('2', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="3" <?php echo ($mi_prev_desktop_number_of_grid == '3') ? "selected" : "" ?>>
                                                <?php esc_html_e('3', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="4" <?php echo ($mi_prev_desktop_number_of_grid == '4') ? "selected" : "" ?>>
                                                <?php esc_html_e('4', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="5" <?php echo ($mi_prev_desktop_number_of_grid == '5') ? "selected" : "" ?>>
                                                <?php esc_html_e('5', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="6" <?php echo ($mi_prev_desktop_number_of_grid == '6') ? "selected" : "" ?>>
                                                <?php esc_html_e('6', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="7" <?php echo ($mi_prev_desktop_number_of_grid == '7') ? "selected" : "" ?>>
                                                <?php esc_html_e('7', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="8" <?php echo ($mi_prev_desktop_number_of_grid == '8') ? "selected" : "" ?>>
                                                <?php esc_html_e('8', 'mi_logo'); ?>
                                            </option>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="tab-view"> <?php esc_html_e('Tab Grid Number', 'mi_logo'); ?></label>
                                    </th>
                                    <td class="nav-position-h">
                                        <select name="tab_number_of_grid" id="tab-view">

                                            <option
                                                value="1" <?php echo ($mi_prev_tab_number_of_grid == '1') ? "selected" : "" ?>>
                                                <?php esc_html_e('1', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="2" <?php echo ($mi_prev_tab_number_of_grid == '2') ? "selected" : "" ?>>
                                                <?php esc_html_e('2', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="3" <?php echo ($mi_prev_tab_number_of_grid == '3') ? "selected" : "" ?>>
                                                <?php esc_html_e('3', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="4" <?php echo ($mi_prev_tab_number_of_grid == '4') ? "selected" : "" ?>>
                                                <?php esc_html_e('4', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="5" <?php echo ($mi_prev_tab_number_of_grid == '5') ? "selected" : "" ?>>
                                                <?php esc_html_e('5', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="6" <?php echo ($mi_prev_tab_number_of_grid == '6') ? "selected" : "" ?>>
                                                <?php esc_html_e('6', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="7" <?php echo ($mi_prev_tab_number_of_grid == '7') ? "selected" : "" ?>>
                                                <?php esc_html_e('7', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="8" <?php echo ($mi_prev_tab_number_of_grid == '8') ? "selected" : "" ?>>
                                                <?php esc_html_e('8', 'mi_logo'); ?>
                                            </option>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="mobile-view"> <?php esc_html_e('Mobile Grid Number', 'mi_logo'); ?></label>
                                    </th>
                                    <td class="nav-position-h">
                                        <select name="mobile_number_of_grid" id="mobile-view">

                                            <option
                                                value="1" <?php echo ($mi_prev_mobile_number_of_grid == '1') ? "selected" : "" ?>>
                                                <?php esc_html_e('1', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="2" <?php echo ($mi_prev_mobile_number_of_grid == '2') ? "selected" : "" ?>>
                                                <?php esc_html_e('2', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="3" <?php echo ($mi_prev_mobile_number_of_grid == '3') ? "selected" : "" ?>>
                                                <?php esc_html_e('3', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="4" <?php echo ($mi_prev_mobile_number_of_grid == '4') ? "selected" : "" ?>>
                                                <?php esc_html_e('4', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="5" <?php echo ($mi_prev_mobile_number_of_grid == '5') ? "selected" : "" ?>>
                                                <?php esc_html_e('5', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="6" <?php echo ($mi_prev_mobile_number_of_grid == '6') ? "selected" : "" ?>>
                                                <?php esc_html_e('6', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="7" <?php echo ($mi_prev_mobile_number_of_grid == '7') ? "selected" : "" ?>>
                                                <?php esc_html_e('7', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="8" <?php echo ($mi_prev_mobile_number_of_grid == '8') ? "selected" : "" ?>>
                                                <?php esc_html_e('8', 'mi_logo'); ?>
                                            </option>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="small-mobile-view"> <?php esc_html_e('Small Mobile Grid Number', 'mi_logo'); ?></label>
                                    </th>
                                    <td class="nav-position-h">
                                        <select name="small_mobile_number_of_grid" id="small-mobile-view">

                                            <option
                                                value="1" <?php echo ($mi_prev_small_mobile_number_of_grid == '1') ? "selected" : "" ?>>
                                                <?php esc_html_e('1', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="2" <?php echo ($mi_prev_small_mobile_number_of_grid == '2') ? "selected" : "" ?>>
                                                <?php esc_html_e('2', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="3" <?php echo ($mi_prev_small_mobile_number_of_grid == '3') ? "selected" : "" ?>>
                                                <?php esc_html_e('3', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="4" <?php echo ($mi_prev_small_mobile_number_of_grid == '4') ? "selected" : "" ?>>
                                                <?php esc_html_e('4', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="5" <?php echo ($mi_prev_small_mobile_number_of_grid == '5') ? "selected" : "" ?>>
                                                <?php esc_html_e('5', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="6" <?php echo ($mi_prev_small_mobile_number_of_grid == '6') ? "selected" : "" ?>>
                                                <?php esc_html_e('6', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="7" <?php echo ($mi_prev_small_mobile_number_of_grid == '7') ? "selected" : "" ?>>
                                                <?php esc_html_e('7', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="8" <?php echo ($mi_prev_small_mobile_number_of_grid == '8') ? "selected" : "" ?>>
                                                <?php esc_html_e('8', 'mi_logo'); ?>
                                            </option>

                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <table class="form-table mi-logo-item-image-width">
                                <tr>
                                    <th>
                                        <label for="mi-logo-image-width"> <?php esc_html_e('Image Max Width & Height', 'mi_logo'); ?> </label>
                                    </th>
                                    <td>
                                        <input type="text" name="image_width" value="<?php echo $mi_prev_image_width ?>" id="mi-logo-image-width">
                                        <p class="description" id="tagline-description"><?php esc_html_e('Ex: 250px or 25%.', 'mi_logo'); ?></p>
                                    </td>
                                </tr>
                            </table>
                            <table class="form-table layout-settings">
                                <h2 class="title mt-40 layout-settings"><?php esc_html_e('Layout Settings', 'mi_logo'); ?></h2>
                                <tr class="row-layout">
                                    <th>
                                        <label for="layout_selection"> <?php esc_html_e('Layout', 'mi_logo'); ?></label>
                                    </th>
                                    <td>
                                        <select name="layout_selection" id="layout_selection">

                                            <option
                                                value="simple" <?php echo ($mi_prev_layout_selection == 'simple') ? "selected" : "" ?>>
                                                <?php esc_html_e('Simple', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="box" <?php echo ($mi_prev_layout_selection == 'box') ? "selected" : "" ?>>
                                                <?php esc_html_e('Box', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="box-hover-color" disabled>
                                                <?php esc_html_e('Box with Hover color', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="box-opacity" disabled>
                                                <?php esc_html_e('box with Opacity', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="box-hover-shadow" disabled>
                                                <?php esc_html_e('box hover shadow', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="box-blur" disabled>
                                                <?php esc_html_e('Box Blur', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="box-gray-style" disabled>
                                                <?php esc_html_e('Box Gray Style', 'mi_logo'); ?>
                                            </option>
                                            <option
                                                value="round-shadow-effect" disabled>
                                                <?php esc_html_e('Round Shadow Effect', 'mi_logo'); ?>
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="layout-design-option">
                                    <td colspan="2"><h2 class="title mt-40 custom-margin"><?php esc_html_e('Layout Design Option', 'mi_logo'); ?></h2></td>
                                </tr>
                                <tr class="layout-background-color">
                                    <th><label for="layout-background-color"> <?php esc_html_e('Background Color', 'mi_logo'); ?></label></th>
                                    <td><input type="text" name="layout_background_color"
                                               class="color-field" id="layout-background-color"
                                               value="<?php echo $mi_prev_layout_background_color; ?>"></td>
                                </tr>
                                <tr class="layout-border-color">
                                    <th><label for="layout-border-color"> <?php esc_html_e('Border Color', 'mi_logo'); ?></label></th>
                                    <td><input type="text" name="layout_border_color"
                                               class="color-field " id="layout-border-color"
                                               value="<?php echo $mi_prev_layout_border_color; ?>"></td>
                                </tr>


                            </table>
                            <table class="form-table nofollow">
                                <tr>
                                    <th>
                                        <label
                                            for="link-attribute"> <?php esc_html_e('NoFollow Link', 'mi_logo'); ?> </label>
                                    </th>
                                    <td>
                                        <input type="checkbox"
                                               name="link_attr" <?php echo $mi_prev_link_attr ?> >
                                    </td>
                                </tr>

                            </table>

                            <div class="mi-promo">If you need full features please get the premium one from <a href="https://miplugins.com/plugin/mi-logo-slider" class="button button-large" target="_blank">here</a></div>


                        </fieldset>
                    </section>
                </div>

            </div>


            <input type="submit" class="button button-primary button-large" value="Publish" id="mi-publish">


        </form>

        <?php

    }


    /**
     * Render the options page for plugin
     *
     * @since  1.0.0
     */
    public function add_slides_system()
    {

        $this->options = get_option('mi-logo-slider-options', array(
            'mi-logo-slide-size-auto' => 'on',
            'mi-logo-slide-width' => '',
            'mi-logo-slide-auto' => 'on',
            'mi-logo-slide-image-item' => 5,
            'mi-logo-slide-style' => 'horizontal-slide',
            'mi-logo-slide-arrow-style' => 'default',
            'mi-logo-slide-bollet-style' => 'default',
        ));

    }

    function display_options_page_main()
    {
        include_once 'partials/mi-plugin-admin-display-main.php';

    }





    /**
     * @param $value
     * @param string $type
     * @return string
     */

    public static function mi_form_sanitization($value, $type )
    {
        if(!in_array($type, array('text', 'color', 'title', 'url', 'int'))) {
            throw new Exception("type not valid");
            return;
        }

        $value = trim($value);
        $value = stripslashes($value);


        if ( $type == 'text' ) {
            return sanitize_text_field($value);
        }

        if ($type == "color"){
            return sanitize_hex_color($value);
        }

        if ($type == "title"){
            return sanitize_title($value);
        }


        if ($type == "url"){
            return esc_url($value);
        }


        if ($type == "int"){
            return intval($value);
        }



    }

    /**
     * @param $item
     * @return mixed
     */

    public static function walk_recursive(&$item, &$key)
    {
        $type = 'text';

        if($key == 'id'){
            $type = 'int';
        }
        if($key == 'url' || $key == 'link'){
            $type = 'url';
        }
        if ($key == 'title') {
            $type = 'title';
        }

        return $item = array_map('self::mi_form_sanitization', (array)$item,(array)$type)[0];//have to
        // replace value with previsous value and return it. just like item

    }


    /**
     * Add custom action links to the plugin
     *
     * @since  1.0.0
     */
    public function add_action_links($actions, $plugin_file)
    {

        $action_links = array();

        $action_links['settigns'] = array(
            'label' => __('Settings', 'mi-logo'),
            'url' => get_admin_url(null, 'admin.php?page=mi-logo-slider')
        );

        return $this->plugin_action_links($actions, $plugin_file, $action_links, 'before');

    }


    private function plugin_action_links($actions, $plugin_file, $action_links = array(), $position = 'after')
    {

        if ($this->basename == $plugin_file && !empty($action_links)) {

            foreach ($action_links as $key => $value) {

                $target = "";

                if (isset($value['new_tab']) && !empty($value['new_tab']) && $value['new_tab']) {
                    $target = "target='_blank'";
                }

                $link = array($key => '<a href="' . $value['url'] . '" ' . $target . '>' . $value['label'] . '</a>');

                if ($position == 'after') {
                    $actions = array_merge($actions, $link);
                } else {
                    $actions = array_merge($link, $actions);
                }

            }

        }

        return $actions;

    }

    function display_premium_page()
    {
        include_once 'partials/admin-display-get-premium.php';
    }

    function display_other_page()
    {
        include_once 'partials/admin-display-other-products.php';
    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_style('wp-color-picker');

        // Include our custom jQuery file with WordPress Color Picker dependency

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/mi-plugin-admin.js', array('jquery', 'wp-color-picker'), $this->version, true);

    }


}

if ( ! function_exists( 'sanitize_hex_color' ) ) {
    function sanitize_hex_color( $color ) {
        if ( '' === $color )
            return '';

        // 3 or 6 hex digits, or the empty string.
        if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
            return $color;

        return null;
    }
}

//delete_option('logo_items');