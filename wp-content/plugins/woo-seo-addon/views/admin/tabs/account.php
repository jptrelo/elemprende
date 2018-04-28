<?php
if(function_exists('premmerce_wsa_fs') && premmerce_wsa_fs()->is_registered()){
	premmerce_wsa_fs()->add_filter('hide_account_tabs', '__return_true');
	premmerce_wsa_fs()->_account_page_load();
	premmerce_wsa_fs()->_account_page_render();
}