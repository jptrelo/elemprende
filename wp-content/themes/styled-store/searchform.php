<?php
/**
 * Search Form Template
 *
 * @link http://codex.wordpress.org/Function_Reference/get_search_form
 *
 * @package styledstore
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="st-search">
	  <input type="text" placeholder="<?php echo esc_attr_x( 'search For', 'placeholder', 'styled-store' ) ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" >
	  
	  <button class="btn" type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'styled-store' ); ?>"><i class="fa fa-search"></i> </button>
	</div>
</form>