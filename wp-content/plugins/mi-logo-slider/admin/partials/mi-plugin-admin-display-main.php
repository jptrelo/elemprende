<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://miplugins.com
 * @since      1.0.0
 *
 * @package    Mi_Logo_Slider
 * @subpackage Mi_Logo_Slider/admin/partials
 */

 $id = $action =  null;


if ( isset($_GET['id']) && $_GET['id'] ) {
	$id = $_GET['id'];
}

if ( isset($_GET['action']) && $_GET['action'] ) {
	$action = $_GET['action'];
}
if (   $action == "submit_data" ) {
	include_once 'admin-display-slide-new.php';

}

if (   $action == "edit" ) {
	include_once 'admin-display-slide-new.php';

} else {
	include_once('mi-plugin-admin-display-slides.php');
}

?>

<?php
