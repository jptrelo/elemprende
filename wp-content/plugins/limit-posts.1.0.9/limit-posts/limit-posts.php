<?php
/*
Plugin Name: Limit Posts
Plugin URI: www.limitposts.com
Description: A plugin to allow administrators to limit the number of posts a user can publish in a given time period.
Version: 1.0.6
Author: PluginCentral
Author URI: https://profiles.wordpress.org/plugincentral/
Text Domain: limit-posts
License: GPLv2
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

namespace LimitPosts;

//Plugin name
if (!defined('CB_LIMIT_POSTS_PLUGIN_NAME')) {
    define('CB_LIMIT_POSTS_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
}

// Plugin url 
if (!defined('CB_LIMIT_POSTS_PLUGIN_URL')) {
    define('CB_LIMIT_POSTS_PLUGIN_URL', WP_PLUGIN_URL . '/' . CB_LIMIT_POSTS_PLUGIN_NAME);
}

class CBLimitPosts{
	
	private static $textDomain = 'limit-posts';
	private static $pageTitle = 'Limit Posts';
	private static $menuTitle = 'Limit Posts';
	
	protected $multiSite = false;

	function __construct(){
		
		//Setup multisite indicator
		if (function_exists('is_multisite') && is_multisite()){
				$this->multiSite = true;
		}
		
		//Enqueue the required scripts and styles
		add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
		
		//Setup the admin settings page
		add_action('admin_menu', array($this, 'createSettingsMenu'));
		
		//Register the settings for this plugin
		add_action('admin_init', array($this, 'registerPluginOptions'));
		
		//Setup the new post hook
		add_action('admin_notices', array($this, 'applyPostLimtRules'));
		
		//Add shortcode
		add_shortcode('limit_posts', array($this, 'shortcodeHandler'));
		
		
	}

	public function createSettingsMenu(){
		if($this->multiSite){
			add_options_page(__(self::$pageTitle, 'limit-posts'), __(self::$menuTitle, 'limit-posts'), 'manage_network', 'limit_posts_menu', array($this, 'outputSettingsPage'));
		}
		else{
			add_options_page(__(self::$pageTitle, 'limit-posts'), __(self::$menuTitle, 'limit-posts'), 'manage_options', 'limit_posts_menu', array($this, 'outputSettingsPage'));
		}
	}
	
	public function enqueueScripts(){
		wp_enqueue_script('cb_limit_posts_js', CB_LIMIT_POSTS_PLUGIN_URL . '/js/LimitPosts.js', array('jquery'));
		wp_enqueue_style('cb_limit_posts_css', CB_LIMIT_POSTS_PLUGIN_URL . '/css/limitposts.css');
	}

	public function outputSettingsPage(){
		if($this->multiSite && !current_user_can('manage_network')){
			wp_die( __('You do not have sufficient permissions to access this page.', 'limit-posts'));
		}
		elseif(!current_user_can('manage_options')){
			wp_die( __('You do not have sufficient permissions to access this page.', 'limit-posts'));
		}
			
		require_once('options-page.php');
	}
	
	//Setup the settings for this plugin
	public function registerPluginOptions(){
		register_setting( 'limit_posts_options', 'limit_posts' );
	}
		
	//Get the current settings for this plugin
	public function getPluginOptions(){
		$options = get_option('limit_posts');
		
		if(empty($options) && $this->multiSite){
			$options = get_site_option('limit_posts');
		}
		if(is_main_site()){
			update_site_option('limit_posts', $options);
		}
		
		return $options;
	}
	
	//Get all user roles for add/edit drop down
	private function getUserRoles(){
		global $wp_roles;
		
		$rolesOptions = array();
		
		foreach($wp_roles->roles as $key=>$value){
			if($value['name'] == 'Contributor'){
				$rolesOptions[] = '<option value="'.$value['name'].'" selected>'.$value['name'].'</option>';
			}
			else{
				$rolesOptions[] = '<option value="'.$value['name'].'">'.$value['name'].'</option>';
			}
		}
                
                $rolesOptions[] = '<option value="ALL">ALL</option>';
		
		return implode($rolesOptions);
	}
	
	//Get all users for add/edit drop down
	private function getUsers(){
		global $wpdb;
		
		$wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY display_name");
		
		$usersOptions = array();

		foreach ( $wp_user_search as $userid ) {
			$displayName = stripslashes($userid->display_name);
			$usersOptions[] = '<option value='.$userid->ID.'>'.$displayName.'</option>';
		}
		
		return implode($usersOptions);
		
	}
	
	private function getPostTypes(){
		$postTypesOptions = array();
		
		$postTypes = get_post_types('', 'objects');
		foreach($postTypes as $key=>$value){
			$postTypesOptions[] = '<option value="'.$value->name.'">'.ucfirst($value->name).'</option>';
		}
		
		return implode($postTypesOptions);
	}
	
	private function getPublishActions(){
		$publishActions = array();
		
		$publishActions[] = '<option value="Publish">Publish</option>';
		$publishActions[] = '<option value="Submit For Review">Submit For Review</option>';
		
		return implode($publishActions);
	}

	//Apply any post limit rules which have been set up
	public function applyPostLimtRules(){
		
		global $pagenow;
		
		if ($pagenow == 'post-new.php'){  //submit for review and publish
			global $typenow;
			
			$publishAction = 'Publish';
			if(!current_user_can('publish_posts')){
				$publishAction = 'Submit For Review';
			}
			
			if($this->areLimitsReached($typenow, $publishAction)){
				//disable publish and display message
				wp_enqueue_style('cb_disable_publish', CB_LIMIT_POSTS_PLUGIN_URL . '/css/disablepublish.css');
				echo '<div class="error"><p>Publication limit has been reached - you cannot create a new '.$typenow.' at this time.</p></div>';
			}
			
		}
	}
	
	private function areLimitsReached($currentType, $publishAction){
		$limitPostRules = $this->getPluginOptions();
			
		//Check if any rules have been setup
		if(!isset($limitPostRules['rule'])){
			return;
		}
		
		return $this->isLimitReached($limitPostRules, $currentType, $publishAction);
	}
	
	//Determine if a user has reached their publishing limit
	private function isLimitReached($limitPostRules, $currentType, $publishAction){
		//get all relevent rules
		$userRole = $this->getCurrentUserRole();
		$userId = $this->getCurrentUserId();
		
		$releventRules = $this->getReleventRules($limitPostRules, $userRole, $userId, $currentType, $publishAction);
		if(count($releventRules) <= 0){
			return false;
		}
		//echo 'RELEVENT RULE COUNT = '.count($releventRules);
		foreach($releventRules as $releventRule){
			//how many posts of this type has this user published within the rules time periods
			$startDateTime = $this->getStartDateTime($releventRule['period_number'], $releventRule['period_denominator']);
			$ruleAction = 'Publish';
			if(isset($releventRule['publish_action'])){
				$ruleAction = $releventRule['publish_action'];
			}
                        $all = ( ($releventRule['role_or_user'] == 'role') && ($releventRule['role'] == 'ALL') );
			$postCount = $this->getPostCount($currentType, $startDateTime, $ruleAction, $all);
			//echo 'POST COUNT = '.$postCount;
			if($postCount >= $releventRule['limit']){
				return true;  //limit has been reached
			}
		}
		
		return false;
	}
	
	private function getReleventRules($limitPostRules, $userRole, $userId, $postType, $publishAction){
		
		//filter out any rules not for users role
		$rules = $this->filterRulesForUserRole($limitPostRules, $userRole);
		
		//filter out any rules not for user id
		$rules = $this->filterRulesForUserId($rules, $userId);
		
		//filter out any rules not for post type
		$rules = $this->filterRulesForPostType($rules, $postType);
		
		//filter out any rules where action is not publish / submit
		$rules = $this->filterRulesForPublishAction($rules, $publishAction);
		
		return $rules;
	}
	
	private function getCurrentUserRole(){
		global $current_user;
		
		$userRole = $current_user->roles;
		
		return ucfirst($userRole[0]);
	}
	
	private function getCurrentUserId(){
		global $current_user;
		
		$userId = $current_user->ID;
		
		return $userId;
	}
	
	//filter out any rules not for a user role
	private function filterRulesForUserRole($inputRules, $userRole){
		$filteredRules = array();
		
		foreach($inputRules['rule'] as $rule){
			$userOrRole = $this->getUserOrRole($rule);
			if($userOrRole != 'role'){
				$filteredRules[] = $rule;
			}
			else{
				if(($rule['role'] == $userRole) || $rule['role'] == 'ALL'){
					$filteredRules[] = $rule;
				}
			}
		}
		
		return $filteredRules;
	}
	
	private function getUserOrRole($rule){
		$roleOrUser = 'role';
		if(!isset($rule['role_or_user'])){
			$roleOrUser = 'role';
		}
		else{
			$roleOrUser = $rule['role_or_user'];
		}
		
		return $roleOrUser;
	}
	
	private function filterRulesForUserId($inputRules, $userId){
		$filteredRules = array();
		
		foreach($inputRules as $rule){
			$userOrRole = $this->getUserOrRole($rule);
			if($userOrRole != 'user'){
				$filteredRules[] = $rule;
			}
			else{
				if($rule['user_id'] == $userId){
					$filteredRules[] = $rule;
				}
			}
		}
		
		return $filteredRules;
	}
	
	//filter our any rules not for post type
	private function filterRulesForPostType($inputRules, $postType){
		$filterRules = array();
		
		foreach($inputRules as $rule){
			if(strtolower($rule['post_type']) == strtolower($postType)){
				$filterRules[] = $rule;
			}
		}
		
		return $filterRules;
	}
	
	private function filterRulesForPublishAction($inputRules, $publishAction){
		$filterRules = array();
		
		foreach($inputRules as $rule){
			//echo 'publish_action = '.$rule['publish_action'].' $publishAction = '.$publishAction;
			$ruleAction = 'Publish';
			if(isset($rule['publish_action'])){
				$ruleAction = $rule['publish_action'];
			}
			if(strtolower($ruleAction) == strtolower($publishAction)){
				$filterRules[] = $rule;
			}
		}
		
		return $filterRules;
	}
	
	private function getStartDateTime($periodNumber, $periodDenominator){
		$startDateTime = new \DateTime('now');
		$intervalSpec = $this->createIntervalSpec($periodNumber, $periodDenominator);
		$interval = new \DateInterval($intervalSpec);
		$startDateTime->sub($interval);
		return $startDateTime;
	}
	
	private function createIntervalSpec($periodNumber, $periodDenominator){
		$intervalSpec = '';
		switch($periodDenominator){
			case "Seconds":
				$intervalSpec = 'PT'.$periodNumber.'S';
				break;
			case "Minutes":
				$intervalSpec = 'PT'.$periodNumber.'M';
				break;
			case "Hours":
				$intervalSpec = 'PT'.$periodNumber.'H';
				break;
			case "Days":
				$intervalSpec = 'P'.$periodNumber.'D';
				break;
			case "Months":
				$intervalSpec = 'P'.$periodNumber.'M';
				break;
			case "Years":
				$intervalSpec = 'P'.$periodNumber.'Y';
				break;
		}
		return $intervalSpec;
	}
	
	private function getPostCount($postType, $startDateTime, $publishAction, $all){
		global $wpdb;
		$userId = get_current_user_id();
		$query = "SELECT COUNT(ID) FROM $wpdb->posts WHERE ";
		$query .= "post_type='$postType' ";
		$query .= "AND post_date>'".$startDateTime->format('c')."' ";
                if(!$all){
                    $query .= "AND post_author=$userId ";
                }
		if($publishAction == "Publish"){
			$query .= "AND post_status='publish'";  
		}
		elseif($publishAction == "Submit For Review"){
			$query .= "AND post_status='pending'";  //or pending
		}
                
		$count = $wpdb->get_var($query);
		
		return $count;
	}
	
	public function shortcodeHandler($attr, $content = NULL){
		$attributes = shortcode_atts(array(
			'type' => 'post',
			'action' => 'Publish'
		), $attr);
		
		if (!is_user_logged_in()){
				return apply_filters('limit_posts_shortcode_not_logged_in', 'You must be logged in');
		}
		
		global $current_user;
		get_currentuserinfo();
		
		if($this->areLimitsReached($attributes['type'], $attributes['action'])){
			return apply_filters('limit_posts_shortcode_limit_exceeded', 'You have exceeded your limit for this post type');
		}
		
		return apply_filters('limit_posts_shortcode_ok', do_shortcode($content));
	}
}

global $cblp;
$cblp = new CBLimitPosts();