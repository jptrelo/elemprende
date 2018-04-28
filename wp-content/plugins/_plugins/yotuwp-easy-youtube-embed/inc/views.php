<?php

/**
 * 
 */
class YotuViews{

	public $sections = array();
	
	public function __construct()
	{
		global $yotuwp;

		$sections = array();

		//Setting general
		$sections[] = array(
			'icon' => '',
			'key' => 'options',
			'title' => __('Settings', 'yotuwp-easy-youtube-embed'),
			'fields' => array(
				array(
					'name'			=> 'template',
					'type' 			=> 'select',
					'label'			=> __('Videos Layout Template', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'grid',
					'description'	=> __('Layout for display videos.', 'yotuwp-easy-youtube-embed'),
					'options' 		=> array(
						'grid' => __('Grid', 'yotuwp-easy-youtube-embed'),
						'list' => __('List', 'yotuwp-easy-youtube-embed'),
						'mix' => __('Mix', 'yotuwp-easy-youtube-embed')
					)
				),
				array(
					'name'			=> 'column',
					'type'			=> 'select',
					'label'			=> __('Columns', 'yotuwp-easy-youtube-embed'),
					'default'		=> '3',
					'description'	=> __('The number columns of videos on Grid and Mix layout mode.', 'yotuwp-easy-youtube-embed'),
					'options' => array(
						'1' => '1 column',
						'2' => '2 columns',
						'3' => '3 columns',
						'4' => '4 columns',
						'5' => '5 columns',
						'6' => '6 columns'
					)
				),
				array(
					'name'			=> 'per_page',
					'type'			=> 'text',
					'label'			=> __('Videos per page', 'yotuwp-easy-youtube-embed'),
					'default'		=> '12',
					'description'	=> __('The limit number videos per page.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'pagination',
					'type'			=> 'toggle',
					'label'			=> __('Pagination?', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'on',
					'description'	=> __('The pagination for reaching more videos on list.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'pagitype',
					'type'			=> 'select',
					'label'			=> __('Pagination type', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'pager',
					'description'	=> __('The type display and loading of pagination. Pager display next/preve button and current page. Load more displays one button on bottom. Default: pager', 'yotuwp-easy-youtube-embed'),
					'options' => array(
						'pager' => 'Pager',
						'loadmore' => 'Load More'
					)
				),
				array(
					'name'			=> 'title',
					'type'			=> 'toggle',
					'label'			=> __('Videos Title?', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'on',
					'description'	=> __('Display video title on listing.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'description',
					'type'			=> 'toggle',
					'label'			=> __('Videos Description?', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'on',
					'description'	=> __('Display video description on listing.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'thumbratio',
					'type'			=> 'select',
					'label'			=> __('Video Thumbnail Ratio', 'yotuwp-easy-youtube-embed'),
					'default'		=> '43',
					'description'	=> __('', 'yotuwp-easy-youtube-embed'),
					'options' => array(
						'43' => 'Normal - 4:3',
						'169' => 'HD - 16:9'
					)
				),
			)
		);

		//Player settings
		$sections[] = array(
			'icon' => '',
			'key' => 'player',
			'title' => __('Player', 'yotuwp-easy-youtube-embed'),
			'fields' => array(
				array(
					'name'			=> 'mode',
					'type' 			=> 'select',
					'label'			=> __('Player Mode', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'large',
					'description'	=> __('Layout for video player.', 'yotuwp-easy-youtube-embed'),
					'options' 		=> array(
						'large' => __('Large', 'yotuwp-easy-youtube-embed'),
						'popup' => __('Popup', 'yotuwp-easy-youtube-embed')
					)
				),
				array(
					'name'			=> 'width',
					'type'			=> 'text',
					'label'			=> __('Player width', 'yotuwp-easy-youtube-embed'),
					'default'		=> '600',
					'description'	=> __('The default width of player. Set 0 to use full container width player. Default : 600(px)', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'scrolling',
					'type'			=> 'text',
					'label'			=> __('Scrolling Offset', 'yotuwp-easy-youtube-embed'),
					'default'		=> '100',
					'description'	=> __('The distance betwen top browser with player when play a video. Default : 100(px)', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'playing',
					'type'			=> 'toggle',
					'label'			=> __('Playing Title', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'off',
					'description'	=> __('Show title playing video on top of player. Default disabled.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'playing_description',
					'type'			=> 'toggle',
					'label'			=> __('Playing Description', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'off',
					'description'	=> __('Show description playing video at bottom of player. Default disabled.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'controls',
					'type'			=> 'toggle',
					'label'			=> __('Controls', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'on',
					'description'	=> __('This parameter indicates whether the video player controls are displayed', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'autoplay',
					'type'			=> 'toggle',
					'label'			=> __('Auto play', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'on',
					'description'	=> __('This parameter specifies whether the initial video will automatically start to play when the player loads.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'autonext',
					'type'			=> 'toggle',
					'label'			=> __('Auto Next Video', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'off',
					'description'	=> __('Enable play next video in list automatically after previous one end.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'rel',
					'type'			=> 'toggle',
					'label'			=> __('Related Videos', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'on',
					'description'	=> __('This parameter indicates whether the player should show related videos when playback of the initial video ends.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'loop',
					'type'			=> 'toggle',
					'label'			=> __('Loop', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'on',
					'description'	=> __('In the case of a single video player, enable this for the player to play the initial video again and again.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'modestbranding',
					'type'			=> 'toggle',
					'label'			=> __('Branding logo', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'on',
					'description'	=> __('Display your brand logo from displaying in the control bar. This option will remove YouTube logo as well.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'showinfo',
					'type'			=> 'toggle',
					'label'			=> __('Show info', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'on',
					'description'	=> __('Enable information like the video title and uploader before the video starts playing.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'thumbnails',
					'type'			=> 'toggle',
					'label'			=> __('Modal Thumbnails', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'on',
					'description'	=> __('Display list of videos on Modal popup player.', 'yotuwp-easy-youtube-embed'),
				),
			)
		);

		

		$this->sections = $sections;

	}
	
	public function settings($data)
	{
		global $yotuwp;

		
		
		for($i=0; $i< count($this->sections); $i++){
			$tab = $this->sections[$i]['key'];
			for($j=0; $j< count($this->sections[$i]['fields']); $j++){
				$key = $this->sections[$i]['fields'][$j]['name'];
				if(
					isset($data[$tab]) &&
					isset($data[$tab][$key])
				){
					$this->sections[$i]['fields'][$j]['value'] = $data[$tab][$key];
				}
			}
		}

		if(isset($data['styling'])){
			$styling = $data['styling'];
			$this->styling_fields($styling);
		}

		//API settings

		if(isset($data['api'])){
			$api = $data['api'];
			$this->sections[] = array(
				'icon' => '',
				'key' => 'api',
				'title' => __('API', 'yotuwp-easy-youtube-embed'),
				'fields' => array(
					array(
						'name'			=> 'api_key',
						'type'			=> 'text',
						'label'			=> __('Youtube API Key', 'yotuwp-easy-youtube-embed'),
						'default'		=> '',
						'description'	=> sprintf(__('Follow %s to get your own YouTube API key', 'yotuwp-easy-youtube-embed'), '<a href="https://www.yotuwp.com/how-to-get-youtube-api-key/" target="_blank">this guide</a>'),
						'value'			=> $api['api_key'],
					),

				)
			);
		}

		//Cache settings
		if(isset($data['cache'])){
			$cache = $data['cache'];
			$this->sections[] = array(
				'icon' => '',
				'key' => 'cache',
				'title' => __('Cache', 'yotuwp-easy-youtube-embed'),
				'fields' => array(
					array(
						'name'			=> 'enable',
						'type'			=> 'toggle',
						'label'			=> __('Enable?', 'yotuwp-easy-youtube-embed'),
						'default'		=> 'off',
						'description'	=> __('The cache to reduce time for loading videos. Give best experience to your readers.', 'yotuwp-easy-youtube-embed'),
						'value'			=> isset($cache['enable']) ? $cache['enable'] : 'off'
					),
					array(
						'name'			=> 'timeout',
						'type'			=> 'select',
						'label'			=> __('Timeout', 'yotuwp-easy-youtube-embed'),
						'default'		=> 'weekly',
						'description'	=> __('The time your cache removed after created to ensure your videos are fresh.', 'yotuwp-easy-youtube-embed'),
						'value'			=> $cache['timeout'],
						'options' => array(
							"weekly" => "Once a Week",
							"everyminute" => "Once Every 1 Minute",
							"everyfiveminute" => "Once Every 5 Minutes",
							"everyfifteenminute" => "Once Every 15 Minutes",
							"twiceanhour" => "Twice an Hour",
							"onceanhour" => "Once an Hour",
							"everytwohours" => "Once Every 2 Hours",
							"everythreehours" => "Once Every 3 Hours",
							"everyfourhours" => "Once Every 4 Hours",
							"everyfivehours" => "Once Every 5 Hours",
							"everysixhours" => "Once Every 6 Hours",
							"everysevenhours" => "Once Every 7 Hours",
							"everyeighthours" => "Once Every 8 Hours",
							"everyninehours" => "Once Every 9 Hours",
							"everytenhours" => "Once Every 10 Hours",
							"onceaday" => "Once a Day",
							"everythreedays" => "Once Every 3 Days",
							"everytendays" => "Once Every 10 Days",
							"montly" => "Once a Month",
							"yearly" => "Once a Year",
							"hourly" => "Once Hourly",
							"twicedaily" => "Twice Daily",
							"daily" => "Once Daily"

						)
					),
				)
			);
		}

		$this->sections = apply_filters('yotu_settings', $this->sections, $data);

		$this->render_tabs($this->sections, (isset($data['api'])? true : false));
	}

	public function styling_fields($styling){
		//Styling
		$data = array(
			'icon' => '',
			'key' => 'styling',
			'title' => __('Styling', 'yotuwp-easy-youtube-embed'),
			'fields' => array(
				array(
					'name'			=> 'pager_layout',
					'type'			=> 'radios',
					'label'			=> __('Pager Layout', 'yotuwp-easy-youtube-embed'),
					'default'		=> 'default',
					'description'	=> __('The layout for pager. Select one of them to use.', 'yotuwp-easy-youtube-embed'),
					'class' 		=> 'half',
					'value' 		=> $styling['pager_layout'],
					'options' 		=> array(
						'default' => array(
							'title' => __('Default', 'yotuwp-easy-youtube-embed'),
							'img' => 'images/fields/pager_layout/default.png' 
						),
						'center_no_text' => array(
							'title' => __('Center No Text', 'yotuwp-easy-youtube-embed'),
							'img' => 'images/fields/pager_layout/center_no_text.png' 
						),
						'bothside' => array(
							'title' => __('Both Side', 'yotuwp-easy-youtube-embed'),
							'img' => 'images/fields/pager_layout/bothside.png'
						),
						'bothside_no_text' => array(
							'title' => __('Both Side No Text', 'yotuwp-easy-youtube-embed'),
							'img' => 'images/fields/pager_layout/bothside_no_text.png'
						),
					)
				),
				array(
					'name'			=> 'button',
					'type'			=> 'buttons',
					'label'			=> __('Button Style', 'yotuwp-easy-youtube-embed'),
					'default'		=> '10',
					'value' 		=> $styling['button'],
					'description'	=> __('The styling for all buttons. Select one of them to using.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'button_color',
					'value' 		=> $styling['button_color'],
					'type'			=> 'color',
					'label'			=> __('Button Text Color', 'yotuwp-easy-youtube-embed'),
					'default'		=> '',
					'description'	=> __('The color of text on button.', 'yotuwp-easy-youtube-embed'),
					'css' 			=> '.yotu-button-prs|color'
				),
				array(
					'name'			=> 'button_bg_color',
					'value' 		=> $styling['button_bg_color'],
					'type'			=> 'color',
					'label'			=> __('Button Background Color', 'yotuwp-easy-youtube-embed'),
					'default'		=> '',
					'css'		=> '.yotu-button-prs|background-color',
					'description'	=> __('The button background color.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'button_color_hover',
					'value' 		=> $styling['button_color_hover'],
					'type'			=> 'color',
					'label'			=> __('Button Color Hover', 'yotuwp-easy-youtube-embed'),
					'default'		=> '',
					'css'		=> '.yotu-button-prs:hover,.yotu-button-prs:focus|color',
					'description'	=> __('The color of text button on hover.', 'yotuwp-easy-youtube-embed'),
				),
				array(
					'name'			=> 'button_bg_color_hover',
					'value' 		=> $styling['button_bg_color_hover'],
					'type'			=> 'color',
					'label'			=> __('Button Background Color Hover', 'yotuwp-easy-youtube-embed'),
					'default'		=> '',
					'css'		=> '.yotu-button-prs:hover,.yotu-button-prs:focus|background-color',
					'description'	=> __('The background color of button on hover.', 'yotuwp-easy-youtube-embed'),
				),
			)
		);
		$this->sections[] = $data;
		return $data;
	}
	
	public static function sidebar(){
	?>
	<div class="yotu-sidebar">
		
		<div class="yotu-sidebar-box">
			<h2>Shortcode Generator</h2>
			<p>I just created new feature called <a target="_blank" href="admin.php?page=yotuwp-shortcode">Shortcode Generator</a>. That feature allow you create the YotuWP shortcode for using on widgets, product description, page builder or other place which support shortcode running. <a target="_blank" href="admin.php?page=yotuwp-shortcode">Check it now!</a></p>
		</div>
		<div class="yotu-sidebar-box">
			<h2><?php _e('Document', 'yotuwp-easy-youtube-embed');?></h2>
			<p>
				 <?php _e('YotuWP Document', 'yotuwp-easy-youtube-embed');?> <a href="https://www.yotuwp.com/document/?utm_source=clientsite&utm_medium=docs&utm_campaign=doc" target="_blank"><?php _e('Read more', 'yotuwp-easy-youtube-embed');?></a>
			</p>
			<p>
				<?php _e('You do not know how to get YouTube API key', 'yotuwp-easy-youtube-embed');?> > <a href="https://www.yotuwp.com/how-to-get-youtube-api-key/?utm_source=clientsite&utm_medium=docs&utm_campaign=api" target="_blank"><?php _e('Read this article', 'yotuwp-easy-youtube-embed');?></a>
			</p>
		</div>
		<div class="yotu-sidebar-box">
			<h2><?php _e('Support', 'yotuwp-easy-youtube-embed');?></h2>
			<p>
				 <?php _e('For futher question and suggestion, please open theard on', 'yotuwp-easy-youtube-embed');?> <a href="https://wordpress.org/support/plugin/yotuwp-easy-youtube-embed" target="_blank"><?php _e('WordPress.org forum', 'yotuwp-easy-youtube-embed');?></a>
			</p>
			<p>
				<?php _e('Or send us message from ', 'yotuwp-easy-youtube-embed');?> <a href="https://www.yotuwp.com/contact/?utm_source=clientsite&utm_medium=contact&utm_campaign=contact" target="_blank"><?php _e('contact form', 'yotuwp-easy-youtube-embed');?></a>
			</p>
		</div>		

	</div>
	<?php
	}

	public function popup($yotuwp, $is_panel = true)
	{
	?>

	<div class="yotu_insert_popup" data-type="playlist">
		<?php 
		if(is_array($yotuwp->api) && $yotuwp->api['api_key'] !=''):?>
			<h4><?php _e('Step #1: Select type videos you want to inserts', 'yotuwp-easy-youtube-embed');?></h4>
			<ul class="yotu-tabs yotu-tabs-insert">
				<li>
					<a href="#" data-tab="playlist" data-yotu="insert"><?php _e('Playlist/List', 'yotuwp-easy-youtube-embed');?></a>
				</li>
				<li>
					<a href="#" data-tab="channel" data-yotu="insert"><?php _e('Channel', 'yotuwp-easy-youtube-embed');?></a>
				</li>
				<li>
					<a href="#" data-tab="username" data-yotu="insert"><?php _e('Username', 'yotuwp-easy-youtube-embed');?></a>
				</li>
				<li>
					<a href="#" data-tab="single" data-yotu="insert"><?php _e('Single Video', 'yotuwp-easy-youtube-embed');?></a>
				</li>
				<li>	
					<a href="#" data-tab="videos" data-yotu="insert"><?php _e('Multi Videos', 'yotuwp-easy-youtube-embed');?></a>
				</li>
				<li>	
					<a href="#" data-tab="keyword" data-yotu="insert"><?php _e('By Keyword', 'yotuwp-easy-youtube-embed');?></a>
				</li>
			</ul>
			<div class="yotu-tabs-content yotu-insert-popup">
				<div class="yotu-tab-content" id="yotu-tab-playlist" data-type="playlist">
					<p><?php _e('Please enter playlist/list URL for getting info. Then press Verify button to checking data.', 'yotuwp-easy-youtube-embed');?><br><em>Example: https://www.youtube.com/playlist?list=PLmU8B4gZ41idW0H82OGG8nvlkceNPqpvq</em></p>
					<div class="yotu-input-url">
						<input type="text" name="yotu-input-url" class="yotu-input-value"/>
						<a href="#" class="yotu-button yotu-search-action"><?php _e('Verify', 'yotuwp-easy-youtube-embed');?></a>
					</div>
				</div>
				<div class="yotu-tab-content" id="yotu-tab-channel" data-type="channel">
					<p><?php _e('Please enter channel URL for getting info. Then press Verify button to checking data.', 'yotuwp-easy-youtube-embed');?><br><em>Example: https://www.youtube.com/channel/UCANLZYMidaCbLQFWXBC95Jg</em></p>
					<div class="yotu-input-url">
						<input type="text" name="yotu-input-url" class="yotu-input-value"/>
						<a href="#" class="yotu-button yotu-search-action"><?php _e('Verify', 'yotuwp-easy-youtube-embed');?></a>
					</div>
				</div>
				<div class="yotu-tab-content" id="yotu-tab-username" data-type="username">
					<p><?php _e('Please enter username you want to get videos. Then press Verify button to checking data.', 'yotuwp-easy-youtube-embed');?><br>
						<em>Example: <br />OneDirectionVEVO</em>
					</p>
					<div class="yotu-input-url">
						<textarea type="text" rows="3" cols="50" name="yotu-input-url" class="yotu-input-value"></textarea>
						<a href="#" class="yotu-button yotu-search-action"><?php _e('Verify', 'yotuwp-easy-youtube-embed');?></a>
					</div>
				</div>
				<div class="yotu-tab-content" id="yotu-tab-single" data-type="videos">
					<p><?php _e('Enter your video URL into text box below. Each video filled into each line. Then press Verify button to checking data.', 'yotuwp-easy-youtube-embed');?><br>
						<em>Example: <br />https://www.youtube.com/watch?v=JLf9q36UsBk</em>
					</p>
					<div class="yotu-input-url">
						<input type="text" rows="3" cols="50" name="yotu-input-url" class="yotu-input-value"/>
						<a href="#" class="yotu-button yotu-search-action"><?php _e('Verify', 'yotuwp-easy-youtube-embed');?></a>
					</div>
				</div>
				<div class="yotu-tab-content" id="yotu-tab-videos" data-type="videos">
					<p><?php _e('Enter your videos URL into text box below. Each video filled into each line. Then press Verify button to checking data.', 'yotuwp-easy-youtube-embed');?><br>
						<em>Example: <br />https://www.youtube.com/watch?v=JLf9q36UsBk<br />https://www.youtube.com/watch?v=wyK7YuwUWsU<br />https://www.youtube.com/watch?v=dwdtzwua2pY</em>
					</p>
					<div class="yotu-input-url">
						<textarea type="text" rows="3" cols="50" name="yotu-input-url" class="yotu-input-value"></textarea>
						<a href="#" class="yotu-button yotu-search-action"><?php _e('Verify', 'yotuwp-easy-youtube-embed');?></a>
					</div>
				</div>
				<div class="yotu-tab-content" id="yotu-tab-keyword" data-type="keyword">
					<p><?php _e('Enter your keyword into text box below to listing all videos with that keyword.', 'yotuwp-easy-youtube-embed');?><br>
						<em>Example: <br />TED videos</em>
					</p>
					<div class="yotu-input-url">
						<input type="text" rows="3" cols="50" name="yotu-input-url" class="yotu-input-value"/>
						<a href="#" class="yotu-button yotu-search-action"><?php _e('Verify', 'yotuwp-easy-youtube-embed');?></a>
					</div>
				</div>
			</div>
			<div class="yotu-info-res"></div>
			<div class="yotu-step">
				<h4><?php _e('Step #2: Layout Settings', 'yotuwp-easy-youtube-embed');?></h4>
				<div class="yotu-field">
					<label><?php _e('Use default options', 'yotuwp-easy-youtube-embed');?></label>
					<label class="yotu-switch">
					  <input type="checkbox" checked="checked" id="yotu-settings-handler"/>
					  <span class="yotu-slider yotu-round"></span>
					</label>
				</div>
			</div>
			<div class="yotu-layout yotu-hidden">
				<p>
					<?php _e('Do you need help?', 'yotuwp-easy-youtube-embed');?> <a href="https://www.yotuwp.com/document/" target="_blank"><?php _e('Check out document here', 'yotuwp-easy-youtube-embed');?></a>
				</p>
				<?php
				$data = array(
					'options' => $yotuwp->options,
					'player' => $yotuwp->player
				);
				$this->settings($data);
				?>
			</div>
			<?php if($is_panel):?>
				<div class="yotu-actions">
					<a href="#" class="yotu-button yotu-button-primary"><?php _e('Insert', 'yotuwp-easy-youtube-embed');?></a>    
				</div>
			<?php else:?>
				<div class="yotu-step">
					<h4><?php _e('Step #3: Copy your shortcode', 'yotuwp-easy-youtube-embed');?></h4>
					<p><?php _e('Click on the input the select shortcode text then paste into your place you want to display gallery.', 'yotuwp-easy-youtube-embed');?></p>
					<div class="yotu-shortcode-gen yotu-input-url">
						<input type="text" name="shortcode" id="shortcode_val" value="" class="yotu-input-value" />   
					</div>
				</div>
			<?php endif;?>
		<?php else :?>
			<h4 style="color: #f00;">
				<?php printf( __( 'Please enter your Youtube API key from <a href="%s">setting page</a> to use this feature.', 'yotuwp-easy-youtube-embed' ), menu_page_url('yotuwp', false) );?>
			</h4>
			<p><?php _e('You can follow guide to get API Key and setup it.', 'yotuwp-easy-youtube-embed');?> <a href="#"><?php _e('Check out document here', 'yotuwp-easy-youtube-embed');?> >></a></p>
		<?php endif;?>
	</div>
	<?php
	}

	public function display($template, $data, $settings){
		global $yotuwp;

		$is_single = false;

		ob_start();
		
		do_action_ref_array( 'yotu_before_display', array(&$template, &$data, &$settings ));

		if(!isset($yotuwp->api['api_key']) || empty($yotuwp->api['api_key'])){
			$html = __('YotuWP warning: API Key was removed, please contact to your admin about this issues.', 'yotuwp-easy-youtube-embed');
		} else if(is_array($data)){
			//print_r($data);
			$html = __('YotuWP: An issue happend when getting the videos, please check your connection and refresh page again .', 'yotuwp-easy-youtube-embed');
		}
		else{
				
			$playerId = uniqid();

			if($settings['player']['mode'] == 'popup')
				$playerId = 'modal';

			$player = $settings['player'];
			
			$width = '';
			$width_class = '';

			if(isset($player['width']) && $player['width'] > 0)
				$width = $player['width'] . 'px';
			else
				$width_class = 'yotu-player-full';

			?>
			<div class="yotu-playlist yotuwp yotu-limit-min<?php echo ($data->totalPage == 1)? ' yotu-limit-max' : '';?> <?php echo $width_class; echo ($is_single)? ' yotu-playlist-single' : ''; echo isset($settings['thumbratio'])? ' yotu-thumb-'.$settings['thumbratio'] : ''; ?>" data-yotu="<?php echo $playerId;?>" data-page="1" data-total="<?php echo $data->totalPage;?>" data-settings="<?php echo base64_encode(json_encode($settings));?>" data-player="<?php echo $settings['player']['mode'];?>">
				<?php if($player['mode'] =='large'):?>
				<div class="yotu-wrapper-player" style="<?php echo $width;?>">
					<?php if($player['playing']):?>
					<div class="yotu-playing">
						<?php if(count($data->items) >1 ):
							echo $data->items[0]->snippet->title;
						endif;?>
					</div>
					<?php endif;?>
					<div class="yotu-player">
						<div class="yotu-video-placeholder" id="yotu-player-<?php echo $playerId;?>"></div>
					</div>
					<div class="yotu-playing-status"></div>
					<?php if($player['playing_description']):?>
					<div class="yotu-playing-description">
						<?php if(count($data->items) >1 ):
							echo nl2br(trim($data->items[0]->snippet->description));
						endif;?>
					</div>
					<?php endif;?>
				</div>
				<?php
				endif;

				if(
					isset($settings['pagination']) && 
					$settings['pagination'] == 'on' && 
					$settings['pagitype'] == 'pager'
				)
					include($yotuwp->path . YTDS . 'templates' . YTDS . 'pagination.php');

				echo $yotuwp->template($template, $data, $settings);

				if(
					isset($settings['pagination']) && 
					$settings['pagination'] == 'on'
				)
					include($yotuwp->path . YTDS . 'templates' . YTDS . 'pagination.php');
				?>
			</div>
			<?php
			
			$html = ob_get_contents();
			ob_end_clean();
		}

		return $html;
	}


	public function admin_page()
	{
		global $yotuwp;
		?>
		<div class="yotu-wrap wrap">
			<div class="yotu-logo">
				<img src="<?php echo $yotuwp->url . 'assets/images/yotu-small.png';?>" height="80"/>
				<div><?php _e('Version', 'yotuwp-easy-youtube-embed'); echo ' '. $yotuwp->version;?></div>
			</div>
			<div class="yotu-body">
				<?php if(isset($_GET['install'])):?>
					<div id="message" class="updated notice notice-success is-dismissible megabounce-msg">
						<p><?php _e('Thank you for activation YotuWP! Set your API key to start using. <a href="https://www.yotuwp.com/document/?utm_source=clientsite&amp;utm_medium=docs&amp;utm_campaign=doc" target="_blank">Read more</a>', 'yotuwp-easy-youtube-embed');?></p>
					</div>
				<?php endif?>
				
				<h1><?php _e('YotuWP Settings', 'yotuwp-easy-youtube-embed');?></h1>
				<?php settings_errors(); ?>
				<form method="post" action="options.php">
				<?php

				$data = array(
					'options' => $yotuwp->options,
					'player' => $yotuwp->player,
					'cache' => $yotuwp->cache_cfg,
					'styling' => $yotuwp->styling,
					'api' => $yotuwp->api
				);

				$this->settings($data);

				?>
				</form>
				<?php $this->sidebar();?>
			</div>
			
		</div>
		<?php
		
	}

	public function slugify($text)
	{
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		$text = @iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = trim($text, '-');
		$text = preg_replace('~-+~', '-', $text);
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}

	public function render_tabs($sections, $is_panel = false){
		global $yotuwp;

		include($yotuwp->path . YTDS . 'inc' . YTDS  .  'fields.php');

		$field_control = new YotuFields();

		$tab_control = array();
		$tab_content = array();

		foreach ($sections as $section) {

			$group_id = $this->slugify($section['title']);

			$tab_control[] = '<li><a href="#" data-tab="'.$group_id.'">'.$section['title'].'</a></li>';
			$tab_content[] = '<div class="yotu-tab-content" id="yotu-tab-'.$group_id.'">';

			foreach ($section['fields'] as $field) {
				$field['group'] = $group_id;
				$tab_content[] = $field_control->render_field($field);
			}

			$tab_content[] = '</div>';
		}
		?>
		<ul class="yotu-tabs"><?php echo implode('',$tab_control);?></ul>
		<div class="yotu-tabs-content"><?php echo implode('',$tab_content);
		if($is_panel):
		?>
		<div class="yotu-submit">
            <?php
            // This prints out all hidden setting fields
            settings_fields( 'yotu' );
            do_settings_sections( 'yotu-settings' );
            submit_button(); ?>

        </div>
		<?php
		endif;
		?></div>
		<?php

	}

	public function shortcode_gen(){
		global $yotuwp, $current_user ;
		
		$user_id = $current_user->ID;
		if ( !get_user_meta($user_id, 'yotuwp_scgen_ignore_notice' ) ) {
			update_user_meta($user_id, 'yotuwp_scgen_ignore_notice', false);
		}
		?>
		<div class="yotu-wrap wrap">
			<div class="yotu-logo">
				<img src="<?php echo $yotuwp->url . 'assets/images/yotu-small.png';?>" height="80"/>
				<div><?php _e('Version', 'yotuwp-easy-youtube-embed'); echo ' '. $yotuwp->version;?></div>
			</div>
			<div class="yotu-body shortcode_gen">
				<h1><?php _e('YotuWP Shortcode Generate', 'yotuwp-easy-youtube-embed');?></h1>
				<p><?php _e('This feature helps you generate YotuWP shortcode to adding to any page builder, product description or widget.', 'yotuwp-easy-youtube-embed');?></p>
				<?php $this->popup($yotuwp, false); ?>
				<?php $this->sidebar();?>
			</div>
			
		</div>
		<?php
	}
}

