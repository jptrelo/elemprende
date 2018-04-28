<?php
/*
* Plugin Name: YotuWP - YouTube Gallery
* Plugin URI: https://www.yotuwp.com/
* Description: Easy embed YouTube playlist, channel, videos and user videos to posts/pages/widgets
* Version: 1.2.7
* Text Domain: yotuwp-easy-youtube-embed
* Domain Path: /languages
* Author URI: https://www.yotuwp.com/contact/
* License:     GPL-3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.txt
*/
if(!defined('YOTU_ADMIN_URI'))
	define('YOTU_ADMIN_URI', plugin_dir_url( __FILE__ ) );

if(!defined('YTDS'))
	define('YTDS', DIRECTORY_SEPARATOR );

if(!defined('YOTUWP_VERSION'))
	define('YOTUWP_VERSION', '1.2.7' );

/**
* YotuWP class for plugin
*/
class YotuWP{


	public $data;
	public $api;
	public $version;
	public $is_cache = false;
	public $deploy = true;
	public $url;
	public $path;
	public $cache_path;
	public $assets_url;
	public $timeout = 30;
	public $lang = array();

	public $cache_cfg = array(
		'enable' => false,
		'timeout' => 'daily'
	);

	public $player = array(
			'mode' => 'large',
			'width' => '600',
			'scrolling' => '100',
			'autoplay' => 'off',
			'controls' => 'on',
			'modestbranding' => 'on',
			'loop' => 'off',
			'autonext' => 'off',
			'showinfo' => 'on',
			'rel' => 'on',
			'playing' => 'off',
			'playing_description' => 'off',
			'thumbnails' => 'off'
		);

	public $options = array(
			'type' => 'playlist',
			'id' => '',
			'pagination' => 'on',
			'pagitype' => 'pager',
			'column' => 3,
			'per_page' => 12,
			'template' => 'grid',
			'title' => 'on',
			'description' => 'on',
			'thumbratio' => '43',
			'player' => ''
		);

	public $styling = array(
		'pager_layout' => 'default',
		'button' => '1',
		'button_color' => '',
		'button_bg_color' => '',
		'button_color_hover' => '',
		'button_bg_color_hover' => ''
	);

	public function __construct($version)
	{
		
		$this->version = $version;
		$this->url = plugin_dir_url(__FILE__);
		$this->path = plugin_dir_path(__FILE__);
		$this->assets_url = $this->url . 'assets/';
		$options = get_option( 'yotu-settings' );
		$player = get_option( 'yotu-player' );
		$styling = get_option( 'yotu-styling' );
		$this->api = get_option( 'yotu-api');
		$cache = get_option( 'yotu-cache');
		$this->cache_path = trailingslashit(WP_CONTENT_DIR).'yotuwp_cache' . YTDS;

		include($this->path . YTDS . 'inc' . YTDS  .  'views.php');

		$this->views = new YotuViews();

		if(is_array($player )){

			foreach ($this->player as $key => $value) {

				if(isset($player[$key]))
					$this->player[$key] = $player[$key];
				else if(!in_array($key, array('width','scrolling')))
					$this->player[$key] = 'off';

				if(!is_admin()){
					if($this->player[$key] == 'on')
						$this->player[$key] = 1;
					else if($this->player[$key] == 'off')
						$this->player[$key] = 0;
				}
				
			}
		}
		
		if(is_array($options)){
			foreach ($this->options as $key => $value) {
				if(!isset($options[$key]) && !in_array($key, array('pagitype', 'styling', 'player', 'hover_icon', 'type', 'id','thumbratio')))
					$this->options[$key] = 'off';
				else if(isset($options[$key]))
					$this->options[$key] = $options[$key];
			}
		}

		if(is_array($styling)){
			foreach ($this->styling as $key => $value) {
				if(isset($styling[$key])){
					$this->styling[$key] = $styling[$key];
				}
			}
		}

		if(is_array($cache)){
			$this->cache_cfg = $cache;
			$this->is_cache = ((isset($cache['enable']) && $cache['enable'] == 'on'))? true : false;
			$this->cache_timeout = isset($cache['timeout'])? $cache['timeout'] : null;
		}

		if(!is_admin()){
			add_action( 'wp_enqueue_scripts', array(&$this, 'enqueue_script'), 10 );
		}
		else{
			if (!get_option('yotuwp_install_date', false)){
				$date_now = date('Y-m-d G:i:s');
				update_option('yotuwp_rating_date', $date_now);
				update_option('yotuwp_install_date', $date_now);
			}

			add_action( 'admin_init', array($this, 'check_notice') );
			add_action( 'admin_menu', array($this, 'menu_page') );
			add_action('admin_enqueue_scripts', array($this, 'enqueue_admin'), 999);
			add_action('admin_notices', array($this, 'admin_notice'));

			add_action('admin_head', array($this, 'admin_header_css'));
		}

		add_action('media_buttons', array($this, 'media_button'));
		add_action('admin_footer', array($this, 'insert_popup'));

		add_action('wp_ajax_nopriv_yotu_pagination', array(&$this, 'load_more'));
		add_action('wp_ajax_yotu_pagination', array(&$this, 'load_more'));

		add_action('wp_ajax_nopriv_yotu_thumbs', array(&$this, 'load_thumbs'));
		add_action('wp_ajax_yotu_thumbs', array(&$this, 'load_thumbs'));

		add_action('wp_ajax_nopriv_yotu_getinfo', array(&$this, 'search'));
		add_action('wp_ajax_yotu_getinfo', array(&$this, 'search'));

		add_filter( 'yotu_classes', array($this, 'classes'), 10, 2);

		add_filter( 'cron_schedules', array($this, 'cron_intervals'));

		add_filter( 'yotu_video_description', array($this, 'description_length'));

		add_action('admin_init', array($this, 'plugin_redirect'), 1);

		add_shortcode('yotuwp', array($this, 'shortcode'));

		add_action('updated_option', function($option, $old_value, $value ) {
			if($option == 'yotu-cache'){

				if((isset($value['enable']) && $value['enable'] == 'on')){
					wp_clear_scheduled_hook('yotuwp_cache_event');
					wp_schedule_event(time(), $value['timeout'], 'yotuwp_cache_event');
				}else{
					wp_clear_scheduled_hook('yotuwp_cache_event');
				}
			}
			
		}, 10, 3);

		add_action('plugins_loaded', array($this, 'textdomain' ));
		add_action('yotuwp_cache_event', array($this, 'clear_cache'), 10);

		register_activation_hook(__FILE__,array($this, 'activation'));
		register_deactivation_hook(__FILE__, array($this, 'deactivation'));

		$this->lang_cfg();

		do_action('yotu_init');
	}

	function textdomain() {
	    load_plugin_textdomain( 'yotuwp-easy-youtube-embed', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
	

	public function enqueue_admin(){
		wp_enqueue_style( 'wp-color-picker' );
	    wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_style('yotu-icons', $this->url .'assets/css/icons.css', false, $this->version );
		wp_enqueue_style('yotu-admin', $this->url .'assets/css/admin.css', false, $this->version );
		wp_enqueue_script( 'yotu-admin', $this->url .'assets/js/admin.js',array('jquery', 'wp-color-picker'), $this->version, true  );
		wp_localize_script( 'yotu-admin', 'yotujs', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'options' => $this->options, 'player' => $this->player, 'styling' => $this->styling, 'lang' => $this->lang ) );
	}

	public function enqueue_script(){
		wp_register_script('yotu-script', $this->url . 'assets/js/frontend'.($this->deploy? '.min': '').'.js', array('jquery'), $this->version, true);
		wp_register_script('yotu-script', 'https://www.youtube.com/iframe_api');
		wp_register_style('yotu-style', $this->url.'assets/css/frontend'.($this->deploy? '.min': '').'.css', false, $this->version);
		wp_register_style('yotu-icons', $this->url.'assets/css/icons'.($this->deploy? '.min': '').'.css', false, $this->version);
		wp_localize_script( 'yotu-script', 'yotujs', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'player' => $this->player,'lang' => $this->lang) );
		//vendors
		wp_register_script('jquery-owlcarousel', $this->url . 'assets/vendors/owlcarousel/owl.carousel.min.js', array('jquery'), $this->version, true);
		wp_register_style('jquery-owlcarousel', $this->url.'assets/vendors/owlcarousel/assets/owl.carousel.min.css', false, $this->version);
		wp_register_style('jquery-owlcarousel-theme', $this->url.'assets/vendors/owlcarousel/assets/owl.theme.default.css', false, $this->version);


	}

	function admin_header_css() {
		$custom_css = '';
		foreach ($this->views->sections as $tab) {
			foreach ($tab['fields'] as $field) {
				if(isset($field['css'])){
					$data = explode('|', $field['css']);
					$field_id = 'yotu-' . strtolower($tab['title']).'-'.$field['name'];
					$custom_css .= '/*'.$field_id.'*/' . (!empty($this->styling[$field['name']])? $data[0].'{'.$data[1].':'.$this->styling[$field['name']].'}' : '').'/*end '.$field_id.'*/';
				}
			}
		};
		?>
	    <style type="text/css" id="yotu-styling"><?php echo $custom_css;?></style>
	    <?php
	}
	

	public function shortcode($atts){

		wp_enqueue_style('yotu-style');
		wp_enqueue_style('yotu-icons');
		wp_enqueue_style('yotupro');
		wp_enqueue_script('yotu-script');
		wp_enqueue_script('yotupro');

		$custom_css = '';
		$styling_tab = $this->views->styling_fields($this->styling);
		foreach ($styling_tab['fields'] as $field) {
			if(isset($field['css'])){

				$data = explode('|', $field['css']);
				$custom_css .= !empty($this->styling[$field['name']])? $data[0].'{'.$data[1].':'.$this->styling[$field['name']].'}' : '';
			}
		}

		wp_add_inline_style( 'yotu-style', $custom_css );

		$atts = shortcode_atts( $this->options, $atts, 'yotu' );
		$player_options = $this->player;
		$styling_options = $this->styling;

		if( $atts['player'] !='' ){			
			parse_str($atts['player'], $player);

			if( is_array($player) ){
				foreach ($player as $key => $value)
					$player_options[$key] = ($key !== 'mode')? intval($player[$key]) : $player[$key];
			}
		}

		if( isset($atts['styling']) && $atts['styling'] !='' ){
			parse_str($atts['style'], $styling);

			if( is_array($styling) ){
				foreach ($styling as $key => $value)
					$styling_options[$key] = ($key !== 'mode')? intval($styling[$key]) : $styling[$key];
			}
		}


		if( $player_options['thumbnails'] ){
			wp_enqueue_style('jquery-owlcarousel');
			wp_enqueue_style('jquery-owlcarousel-theme');
			wp_enqueue_script('jquery-owlcarousel');
		}
		
		$atts['player'] = $player_options;
		$atts['styling'] = $styling_options;
		
		$data = $this->prepare($atts);

		if(
			is_object($data) && 
			count($data->items) >0
		){
			foreach($data->items as $video){
				$videoId = $this->getVideoId($video);
				$info = array($video->snippet->title, nl2br($video->snippet->description));
				wp_add_inline_script( 'yotu-script', "yotuwp.data.videos['" . $videoId . "'] = " . json_encode($info, true) . ';' );
			}
		}
		
		return $this->views->display( $atts['template'], $data, $atts );
	}

	public function load_content($url){

		$url .= '&key='. $this->api['api_key'];

		if($this->is_cache){

			$cache_id = md5($url);
			$cache_content = $this->cache($cache_id);

			if(!is_string($cache_content)){
				$response = wp_remote_get($url, array('timeout' => $this->timeout));
				$this->cache($cache_id, json_encode($response));
			}else
				$response = json_decode($cache_content, true);

		}else
			$response = wp_remote_get($url, array('timeout' => $this->timeout));

		if (is_wp_error($response) || $response['response']['code'] !== 200)
		{
			$msg = '';

			if( is_array($response) && isset($response['body']) ){
				$obj = json_decode($response['body']);
				$msg = $obj->error->message;
			}

			if( isset($response->errors) && isset($response->errors['http_request_failed'])){
				$msg = $response->errors['http_request_failed'][0];
			}
			return array('items' => array(), 'error' => true, 'msg' => $msg);
		}

		return json_decode($response['body']);
	}	

	public function prepare( &$atts ){
		$api_url = '';

		switch ($atts['type']) {
			case 'playlist':
				$api_url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=id,snippet,contentDetails,status&maxResults='.$atts['per_page'].'&playlistId='. $atts['id'];
				break;

			case 'videos':
				$page = isset( $atts['pageToken'] )? intval( $atts['pageToken'] ) + 1 : 1;
				$all_ids = explode(',', $atts['id']);
				$total = count( $all_ids );
				$totalPages = ceil( $total/ $atts['per_page'] );
				$page = max($page, 1);
				$page = min($page, $totalPages);
				$offset = ($page - 1) * $atts['per_page'];
				if( $offset < 0 ) $offset = 0;

				$cur_ids = array_slice( $all_ids, $offset, $atts['per_page'] );

				$atts['next'] = $page;

				if($page > 1){
					$atts['prev'] = $page - 2;
				}else unset($atts['prev']);

				if($totalPages == $page) unset($atts['next']);

				unset($atts['pageToken']);

				$api_url = 'https://www.googleapis.com/youtube/v3/videos?part=id,snippet,contentDetails&maxResults='.$atts['per_page'].'&id='. implode(',', $cur_ids);
				break;

			case 'keyword':
				$api_url = 'https://www.googleapis.com/youtube/v3/search?type=video&part=snippet,id&maxResults='.$atts['per_page'].'&q='. $atts['id'];
				break;

			case 'channel':

				//find playlist id from channel 
				$url = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&id='.$atts['id'];
				$data = $this->load_content($url);

				if(!is_array($data)){
					$playlist = $data->items[0]->contentDetails->relatedPlaylists->uploads;
					$api_url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=id,snippet,contentDetails,status&maxResults='.$atts['per_page'].'&playlistId='. $playlist;
					$atts['type'] = 'playlist';
					$atts['id'] = $playlist;
				}

				break;

			case 'username':

				//find playlist id from channel 
				$url = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername='.$atts['id'];
				$data = $this->load_content($url);

				if(!is_array($data)){
					$playlist = $data->items[0]->contentDetails->relatedPlaylists->uploads;
					$api_url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=id,snippet,contentDetails,status&maxResults='.$atts['per_page'].'&playlistId='. $playlist;

					$atts['type'] = 'playlist';
					$atts['id'] = $playlist;
				}

				break;

			default:
				# code...
				break;
		}

		
		if(isset($atts['pageToken']))
			$api_url .= '&pageToken=' . $atts['pageToken'];

		$data = $this->load_content($api_url);
		
		if( !is_array($data) ){

			if($atts['type'] !== 'videos'){
				$atts['next'] = isset($data->nextPageToken) ? $data->nextPageToken : '';
				$atts['prev'] = isset($data->prevPageToken) ? $data->prevPageToken : '';
				if(isset($data->pageInfo) && isset($data->pageInfo->totalResults))
					$data->totalPage = ceil($data->pageInfo->totalResults / $data->pageInfo->resultsPerPage);
				else
					$data->totalPage = 0;

			}else $data->totalPage = $totalPages;
		}

		return $data;
	}


	public function search(){

		$type = $_POST['type'];
		$data = $_POST['data'];

		switch ($type) {
			case 'playlist':
				$api_url = 'https://www.googleapis.com/youtube/v3/playlists?part=snippet,contentDetails&id='. $data;
				break;

			case 'channel':
				$api_url = 'https://www.googleapis.com/youtube/v3/search?type=channel&part=snippet,id&channelId='. $data;
				break;

			case 'videos':
				$api_url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id=' . $data;
				break;

			case 'username':
				$api_url = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails,snippet,id&forUsername='. $data;
				break;

			case 'keyword':
				$api_url = 'https://www.googleapis.com/youtube/v3/search?type=video&part=snippet,id&q='. $data;
				break;

			default:
				$api_url = 'https://www.googleapis.com/youtube/v3/search?part=snippet,id&type=' . $type .'&q=' . $data;
				break;
		}

		$resp = $this->load_content($api_url);

		wp_send_json($resp );
	}

	public function load_more(){

		$page = $_POST['page'];
		$atts = json_decode(base64_decode($_POST['settings']), true);
		
		switch ($page) {
			
			case 'next':
			case 'more':
				$atts['pageToken'] = $atts['next'];
				break;
			
			default:
				$atts['pageToken'] = $atts['prev'];
				break;
		}

		$data = $this->prepare($atts);
		$atts_tmp = $atts;

		$items = (!is_array($data))? $data->items : array();

		$filtered = array();

		foreach($items as $video){
			$filtered[] = array(
				'thumb' => $this->get_thumb($video),
				'title' => $video->snippet->title,
				'description' => $video->snippet->description,
				'videoId' => $this->getVideoId($video)
			);
		}

		$html = $this->template($atts['template'], $data, $atts_tmp);

		$settings = array();
		foreach (['next', 'prev'] as $key) {
			$settings[$key] = isset($atts[$key]) ? $atts[$key]: '';
		}

		wp_send_json(array('html' => $html, 'items' => $filtered, 'settings' => $settings, 'error' => (is_array($data) && isset($data['error'])? $data['error'] : false ) ));
	}

	public function load_thumbs(){

		$atts = json_decode(base64_decode($_POST['settings']), true);

		$atts['pageToken'] = (isset($_POST['token']) && $_POST['token'] != '')? $_POST['token'] : $atts['next'];

		$data = $this->prepare($atts);
		$token = '';
		$thumb_type = $this->get_thumb_type($atts['column']);

		if(!is_array($data)){
			$token = $data->nextPageToken;
			$items = $data->items;
		}
		else
			$items = array();

		$filtered = array();

		foreach($items as $video){
			$filtered[] = array(
				'thumb' => $video->snippet->thumbnails->$thumb_type->url,
				'title' => base64_encode($video->snippet->title),
				'videoId' => $this->getVideoId($video)
			);
		}

		wp_send_json(array('items' => $filtered, 'token' => $token));
	}

	public static function getVideoId($video){

		if(isset($video->snippet->resourceId) && isset($video->snippet->resourceId->videoId))
			return $video->snippet->resourceId->videoId;

		else if(isset($video->contentDetails) && isset($video->contentDetails->videoId))
			return $video->contentDetails->videoId;

		else if(isset($video->id) && isset($video->id->videoId))
			return $video->id->videoId;

		else if(isset($video->id))
			return $video->id;

		return '';
	}

	public static function getVideoThumb($video){

		if(isset($video->snippet->resourceId) && isset($video->snippet->resourceId->videoId))
			return $video->snippet->resourceId->videoId;

		else if(isset($video->contentDetails) && isset($video->contentDetails->videoId))
			return $video->contentDetails->videoId;

		else if(isset($video->id) && isset($video->id->videoId))
			return $video->id->videoId;

		else if(isset($video->id))
			return $video->id;

		return '';
	}
	public function template($template, $data, $settings){
		global $yotuwp;

		$yotuwp->data = array(
			'data' => $data,
			'settings' => $settings
		);
		
		ob_start();

		if ( $overridden_template = locate_template( 'yotu'.YTDS. $template .'.php' ) ) {
			load_template( $overridden_template, false );
		} else {
			$path = $this->path . 'templates'.YTDS. $template . '.php';
			$path = apply_filters('yotu_template_path', $path, $template);
			if(file_exists($path))
				load_template( $path, false );
			else echo __('Template not found : ', 'yotuwp-easy-youtube-embed'). $template;
		}

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function check_file_writeable(){
		require_once(ABSPATH . 'wp-admin/includes/file.php');

		$creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());

		if ( !WP_Filesystem($creds) ) return false;

		global $wp_filesystem;

		//check folder is exist
		if(!$wp_filesystem->is_dir($this->cache_path)){
			$wp_filesystem->mkdir($this->cache_path);
		}
	}

	public function cache($id, $content =''){

		require_once(ABSPATH . 'wp-admin/includes/file.php');

		$access_type = get_filesystem_method();

		if($access_type === 'direct'){

			$creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());

			if ( ! WP_Filesystem($creds) ) {
				return false;
			}

			global $wp_filesystem;

			//get content
			if(empty($content)){

				$file_name = $this->cache_path . $id . '.json';

				if(!$wp_filesystem->exists($file_name)){
					return false;
				}
				else{
					return $wp_filesystem->get_contents( $file_name );
				}
			}
			//store content
			else{
				//check folder is exist
				$wp_filesystem->put_contents(
					$this->cache_path . $id . '.json',
					$content,
					FS_CHMOD_FILE
				);
			}
		}

		return false;
	}

	public function clear_cache(){
		require_once(ABSPATH . 'wp-admin/includes/file.php');

		$access_type = get_filesystem_method();

		if($access_type === 'direct'){

			$creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());

			if ( ! WP_Filesystem($creds) ) {
				return false;
			}

			global $wp_filesystem;
			$wp_filesystem->rmdir($this->cache_path, true);
		}
	}



	/** admin **/

	public function menu_page() {
		add_menu_page(
			__( 'YotuWP', 'yotuwp-easy-youtube-embed' ),
				'YotuWP',
				'manage_options',
				'yotuwp',
				array($this->views, 'admin_page'),
				$this->url . 'assets/images/yotu.png',
			90
		);

		remove_submenu_page ('yotuwp', 'yotuwp');

		add_submenu_page(
			'yotuwp',
			esc_html__('YotuWP', 'yotuwp-easy-youtube-embed'),
			esc_html__('General Settings', 'yotuwp-easy-youtube-embed'),
			'manage_options',
			'yotuwp',
			array($this->views, 'admin_page')
		);

		add_submenu_page(
			'yotuwp',
			__('Shortcode Generator', 'yotuwp-easy-youtube-embed'),
			__('Shortcode Generator', 'yotuwp-easy-youtube-embed'),
			'manage_options',
			'yotuwp-shortcode',
			array($this->views, 'shortcode_gen')
		);

		add_action( 'admin_init', array($this, 'register_settings') );
	}

	public function register_settings(){
		register_setting( 'yotu', 'yotu-settings' );
		register_setting( 'yotu', 'yotu-player' );
		register_setting( 'yotu', 'yotu-styling' );
		register_setting( 'yotu', 'yotu-api' );
		register_setting( 'yotu', 'yotu-cache' );
		register_setting( 'yotu', 'yotu-presets' );
	}


	/** editor buttons**/

	public function media_button(){

		if( stripos( $_SERVER['REQUEST_URI'], 'post.php' ) === FALSE && stripos( $_SERVER['REQUEST_URI'], 'post-new.php' ) === FALSE ) {
			return;
		}

		$button_tip = __('Yotu - Embed Youtube', 'yotuwp-easy-youtube-embed');
		$icon = '<img src="'.$this->url . 'assets/images/yotu.png'.'"/>';

		echo '<a title="' . __('Yotu - Embed Youtube', 'yotuwp-easy-youtube-embed') . '" title="' . $button_tip . '" href="#TB_inline?width=700&height=550&inlineId=yotu_insert_popup" class="yotu-insert-btn button thickbox" >'.$icon.' YotuWP</a>';

	}

	public function insert_popup(){

		if( stripos( $_SERVER['REQUEST_URI'], 'post.php' ) === FALSE && stripos( $_SERVER['REQUEST_URI'], 'post-new.php' ) === FALSE ) {
			return;
		}
		add_thickbox();
		?>
		<div id="yotu_insert_popup" style="display:none; width: 800px;">
		<?php
		$this->views->popup($this);
		?>
		</div>
		<?php
	}

	function deactivation() {
		wp_clear_scheduled_hook('yotuwp_cache_event');
	}
	
	function activation() {
		add_option('yotuwp_activation_redirect', true);
		
		if (!get_option('yotuwp_install_date', false)){
			$date_now = date('Y-m-d G:i:s');
			update_option('yotuwp_rating_date', $date_now);
			update_option('yotuwp_install_date', $date_now);
		}
	}

	function plugin_redirect() {

		$this->check_file_writeable();

		if (get_option('yotuwp_activation_redirect', false)) {
			delete_option('yotuwp_activation_redirect');
			wp_redirect('admin.php?page=yotuwp&install=true');
			die();
		}
	}

	public function get_thumb($video){
		return (isset($video->snippet->thumbnails) && isset($video->snippet->thumbnails->standard))? $video->snippet->thumbnails->standard->url : $video->snippet->thumbnails->high->url;
	}

	public function encode($str){
		return addslashes($str);
	}

	public function description($video){
		$desc = nl2br(strip_tags($video->snippet->description));
		return apply_filters('yotu_video_description', $desc);
	}

	public function description_length($str){
		return wp_trim_words($str, 50);
	}

	public function cron_intervals( $schedules ) {
		$schedules['everyminute'] = array(
		    'interval' => 60*1,
		    'display' => __( 'Once Every 1 Minute' )
	    );

		$schedules['everyfiveminute'] = array(
		    'interval' => 60*5,
		    'display' => __( 'Once Every 5 Minutes' )
	    );

	   	$schedules['everyfifteenminute'] = array(
		    'interval' => 60*15,
		    'display' => __( 'Once Every 15 Minutes' )
	    );

	    $schedules['twiceanhour'] = array(
		    'interval' => 60*30,
		    'display' => __( 'Twice an Hour' )
	    );

	    $schedules['onceanhour'] = array(
		    'interval' => 60*60,
		    'display' => __( 'Once an Hour' )
	    );

	    $schedules['everytwohours'] = array(
		    'interval' => 60*60*2,
		    'display' => __( 'Once Every 2 Hours' )
	    );

	    $schedules['everythreehours'] = array(
		    'interval' => 60*60*3,
		    'display' => __( 'Once Every 3 Hours' )
	    );

	    $schedules['everyfourhours'] = array(
		    'interval' => 60*60*4,
		    'display' => __( 'Once Every 4 Hours' )
	    );

	    $schedules['everyfivehours'] = array(
		    'interval' => 60*60*5,
		    'display' => __( 'Once Every 5 Hours' )
	    );

	    $schedules['everysixhours'] = array(
		    'interval' => 60*60*6,
		    'display' => __( 'Once Every 6 Hours' )
	    );

	    $schedules['everysevenhours'] = array(
		    'interval' => 60*60*7,
		    'display' => __( 'Once Every 7 Hours' )
	    );

	    $schedules['everyeighthours'] = array(
		    'interval' => 60*60*8,
		    'display' => __( 'Once Every 8 Hours' )
	    );

	    $schedules['everyninehours'] = array(
		    'interval' => 60*60*9,
		    'display' => __( 'Once Every 9 Hours' )
	    );

	    $schedules['everytenhours'] = array(
		    'interval' => 60*60*10,
		    'display' => __( 'Once Every 10 Hours' )
	    );

	    $schedules['onceaday'] = array(
		    'interval' => 60*60*24,
		    'display' => __( 'Once a Day' )
	    );

	    $schedules['everythreedays'] = array(
		    'interval' => 60*60*24*3,
		    'display' => __( 'Once Every 3 Days' )
	    );

	    $schedules['weekly'] = array(
		    'interval' => 60*60*24*7,
		    'display' => __( 'Once a Week' )
	    );

	    $schedules['everytendays'] = array(
		    'interval' => 60*60*24*10,
		    'display' => __( 'Once Every 10 Days' )
	    );

	    $schedules['montly'] = array(
		    'interval' => 60*60*24*30,
		    'display' => __( 'Once a Month' )
	    );

	    $schedules['yearly'] = array(
		    'interval' => 60*60*24*30*12,
		    'display' => __( 'Once a Year' )
	    );

	    return $schedules;
	}

	public function check_notice(){
		global $current_user;
		
		$user_id = $current_user->ID;
		
		if(isset($_REQUEST['yotuwp_rating_ignore_notice'])){

			$rating_notice = $_REQUEST['yotuwp_rating_ignore_notice'];

			switch ($rating_notice) {
				case 'yes':
					update_user_meta($user_id, 'yotuwp_rating_ignore_notice', true);
					break;

				case 'one_week_review':
					$date_now = date('Y-m-d G:i:s');
					update_option('yotuwp_rating_date', $date_now);
					break;
				
				default:
					// code...
					break;
			}
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit;
		}

		if(isset($_REQUEST['yotuwp_scgen_ignore_notice'])){
			update_user_meta($user_id, 'yotuwp_scgen_ignore_notice', true);
		}

	}

	function admin_notice() {
		global $current_user ;
		$user_id = $current_user->ID;

		$install_date = get_option( 'yotuwp_rating_date', '');

		$install_date = date_create( $install_date );
		$date_now     = date_create( date('Y-m-d G:i:s') );
		$date_diff    = date_diff( $install_date, $date_now );

		if ( $date_diff->format("%d") <= 7 ) {

			return false;
		}

		if ( ! get_user_meta($user_id, 'yotuwp_rating_ignore_notice' ) ) {

		echo '<div class="updated">
			<div class="yotu-notice-logo"></div>
			<p>Awesome, you\'ve been using <a href="admin.php?page=yotuwp">YotuWP - Embed Youtube Videos</a> plugin for more than 1 week! We hope you\'ve enjoyed using it. Would you consider leaving us a review on WordPress.org?</p>
			<ul class="yotu-rating-notice">
				<li>
					<span class="dashicons dashicons-external"></span>
					<a href="https://wordpress.org/support/plugin/yotuwp-easy-youtube-embed/reviews/?filter=5" target="_blank">Sure! I\'d love to!
					</a>
				</li>
                <li>
                	<span class="dashicons dashicons-smiley"></span>
                	<a href="admin.php?page=yotuwp&yotuwp_rating_ignore_notice=yes"> I\'ve already left a review</a>
            	</li>
				<li>
					<span class="dashicons dashicons-calendar-alt"></span>
					<a href="admin.php?page=yotuwp&yotuwp_rating_ignore_notice=one_week_review">Maybe Later</a>
				</li>
				<li>
					<span class="dashicons dashicons-dismiss"></span>
					<a href="admin.php?page=yotuwp&yotuwp_rating_ignore_notice=yes">Never show again</a>
				</li>
				</ul>
			</div>';
		}
	}

	public function lang_cfg(){
		$this->lang = array(
			1 => __('Forbidden: You do not have permission to access resource on this server.', 'yotuwp-easy-youtube-embed'),
			2 => __('Resource not found, please ensure you has correct information.', 'yotuwp-easy-youtube-embed'),
			3 => __('Resource not found, please inform administrator about issues.', 'yotuwp-easy-youtube-embed'),
			4 => __('Search Results:', 'yotuwp-easy-youtube-embed'),
			5 => __('YotuWP: An issue happend when getting the videos, please check your connection and refresh page again.', 'yotuwp-easy-youtube-embed'),
			6 => __('YotuWP: 500 Internal Server Error. Please inform administrator about issues.', 'yotuwp-easy-youtube-embed'),
			7 => __('Play next video.', 'yotuwp-easy-youtube-embed'),
			8 => __('Play previous video.', 'yotuwp-easy-youtube-embed'),
		);
	}

	public function classes($classes, $settings){
		if(isset($settings['column'])){
			$classes[] ='yotu-column-' . $settings['column'];
		}
		if(isset($settings['player']['mode'])){
			$classes[] ='yotu-player-mode-' . $settings['player']['mode'];
		}

		return $classes;
	}
}

global $yotuwp;
$yotuwp = new YotuWP(YOTUWP_VERSION);