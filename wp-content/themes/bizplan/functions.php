<?php
/**
 * Bizplan functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @since Bizplan 0.1
 */

/**
 * Bizplan only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

function bizplan_scripts(){

	# Enqueue Vendor's Script & Style
	$scripts = array(
		array(
			'handler'  => 'bizplan-google-fonts',
			'style'    => esc_url( '//fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i' ),
			'absolute' => true
		),
		array(
			'handler' => 'bootstrap',
			'style'   => 'bootstrap/css/bootstrap.min.css',
			'script'  => 'bootstrap/js/bootstrap.min.js', 
			'version' => '3.3.7'
		),
		array(
			'handler' => 'kfi-icons',
			'style'   => 'kf-icons/css/style.css',
			'version' => '1.0.0'
		),
		array(
			'handler' => 'owlcarousel',
			'style'   => 'OwlCarousel2-2.2.1/assets/owl.carousel.min.css',
			'script'  => 'OwlCarousel2-2.2.1/owl.carousel.min.js',
			'version' => '2.2.1'
		),
		array(
			'handler' => 'owlcarousel-theme',
			'style'   => 'OwlCarousel2-2.2.1/assets/owl.theme.default.min.css',
			'version' => '2.2.1'
		),
		array(
			'handler'  => 'bizplan-style',
			'style'    => get_stylesheet_uri(),
			'absolute' => true,
		),
		array(
			'handler'    => 'bizplan-script',
			'script'     => get_theme_file_uri( '/assets/js/main.js' ),
			'absolute'   => true,
			'prefix'     => '',
			'dependency' => array( 'jquery', 'masonry' )
		)
	);

	bizplan_enqueue( $scripts );

	$locale = apply_filters( 'bizplan_localize_var', array(
		'is_admin_bar_showing'        => is_admin_bar_showing() ? true : false,
		'enable_scroll_top_in_mobile' => bizplan_get_option( 'enable_scroll_top_in_mobile' ) ? 1 : 0,
		'home_slider' => array(
			'autoplay' => bizplan_get_option( 'slider_autoplay' ),
			'timeout'  => absint( bizplan_get_option( 'slider_timeout' ) ) * 1000
		),
		'is_rtl' => is_rtl(),
		'search_placeholder'=> esc_html__( 'hit enter for search.', 'bizplan' )
	));

	wp_localize_script( 'bizplan-script', 'BIZPLAN', $locale );

	if ( is_singular() && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bizplan_scripts' );

/**
* Adds a submit button in search form
* 
* @since Bizplan 0.1
* @param string $form
* @return string
*/
function bizplan_modify_search_form( $form ){
	return $form;
	return str_replace( '</form>', '</form>', $form );
}
add_filter( 'get_search_form', 'bizplan_modify_search_form' );

/**
* Modify some markup for comment section
*
* @since Bizplan 0.1
* @param array $defaults
* @return array $defaults
*/
function bizplan_modify_comment_form_defaults( $defaults ){

	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$defaults[ 'logged_in_as' ] = '<p class="logged-in-as">' . sprintf(
		                              /* translators: 1: edit user link, 2: accessibility text, 3: user name, 4: logout URL */
		                              __( '<a href="%1$s" aria-label="%2$s">Logged in as %3$s</a> <a href="%4$s">Log out?</a>', 'bizplan' ),
		                              get_edit_user_link(),
		                              /* translators: %s: user name */
		                              esc_attr( sprintf( __( 'Logged in as %s. Edit your profile.', 'bizplan' ), $user_identity ) ),
		                              $user_identity,
		                              wp_logout_url( apply_filters( 'the_permalink', get_permalink( get_the_ID() ) ) )
		                          ) . '</p>';
	return $defaults;
}
add_filter( 'comment_form_defaults', 'bizplan_modify_comment_form_defaults',99 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 *
 * @since Bizplan 0.1
 * @return void
 */
function bizplan_pingback_header(){
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'bizplan_pingback_header' );

/**
* Add a class in body when previewing customizer
*
* @since Bizplan 0.1
* @param array $class
* @return array $class
*/
function bizplan_body_class_modification( $class ){
	if( is_customize_preview() ){
		$class[] = 'keon-customizer-preview';
	}
	
	if( is_404() || ! have_posts() ){
 		$class[] = 'content-none-page';
	}

	if( ( is_front_page() && ! is_home() ) || is_search() ){

		$class[] = 'grid-col-3';
	}else if( is_home() || is_archive() ){

		$class[] = 'grid-col-2';
	}

	return $class;
}
add_filter( 'body_class', 'bizplan_body_class_modification' );

if( ! function_exists( 'bizplan_get_ids' ) ):
/**
* Fetches setting from customizer and converts it to an array
*
* @uses bizplan_explode_string_to_int()
* @return array | false
* @since Bizplan 0.1
*/
function bizplan_get_ids( $setting ){

    $str = bizplan_get_option( $setting );
    if( empty( $str ) )
    	return;

    return bizplan_explode_string_to_int( $str );

}
endif;

if( !function_exists( 'bizplan_section_heading' ) ):
/**
* Prints the heading section for home page
*
* @return void
* @since Bizplan 0.1
*/
function bizplan_section_heading( $args ){

	$defaults = array(
	    'divider'  => true,
	    'query'    => true,
	    'sub_title' => true
	);

	$args = wp_parse_args( $args, $defaults );

	# No need to query if already inside the query.
	if( !$args[ 'query'] ){
		get_template_part( 'template-parts/page/home', 'heading' );
		return;
	}

	$id = bizplan_get_option( $args[ 'id' ] );

	if( !empty( $id ) ){

		$query = new WP_Query( array(
			'p' => $id,
			'post_type' => 'page'
		));

		while( $query->have_posts() ){
			$query->the_post();
			set_query_var( 'args', $args );
			get_template_part( 'template-parts/page/home', 'heading' );
		}
		wp_reset_postdata();
	}
}
endif;

if( ! function_exists( 'bizplan_inner_banner' ) ):
/**
* Includes the template for inner banner
*
* @return void
* @since Bizplan 0.1
*/
function bizplan_inner_banner(){

	$description = false;

	if( is_archive() ){

		# For all the archive Pages.
		$title       = get_the_archive_title();
		$description = get_the_archive_description();
	}else if( !is_front_page() && is_home() ){

		# For Blog Pages.
		$title = single_post_title( '', false );
	}else if( is_search() ){

		# For search Page.
		$title = esc_html__( 'Search Results for: ', 'bizplan' ) . get_search_query();
	}else if( is_front_page() && is_home() ){
		# If Latest posts page

		$title = esc_html__( 'Blog', 'bizplan' );
	}else{

		# For all the single Pages.
		$title = get_the_title();
	}

	$args = array(
		'title'       => bizplan_remove_pipe( $title ),
		'description' => $description
	);

	set_query_var( 'args', $args );
	get_template_part( 'template-parts/inner', 'banner' );
}
endif;

if( !function_exists( 'bizplan_testimonial_title' ) ):
/**
* Prints the title for testimonial in more attractive way
*
* @return void
* @since Bizplan 0.1
*/
function bizplan_testimonial_title(){

	$title = str_replace( "\|", "&exception", get_the_title() );

	$arr = explode( '|', $title );

	echo esc_html__( 'By', 'bizplan' ) . ' ' . esc_html( str_replace( '&exception', '|', $arr[ 0 ] ) );

	if( isset( $arr[ 1 ] ) ){
		echo '<span>' . esc_html( $arr[ 1 ] ) . '</span>';
	}
}
endif;

if( !function_exists( 'bizplan_get_piped_title' ) ):
/**
* Returns the title and sub title from piped title
*
* @return array
* @since Bizplan 0.1
*/
function bizplan_get_piped_title(){

	$title = str_replace( "\|", "&exception", get_the_title() );

	$arr = explode( '|', $title );
	$data = array(
		'title' => $arr[ 0 ],
		'sub_title'  => false
	);

	if( isset( $arr[ 1 ] ) ){
		$data[ 'sub_title' ] = trim( $arr[ 1 ] );
	}

	$data[ 'title' ] = str_replace( '&exception', '|', $arr[ 0 ] );
	return $data;
}
endif;

if( !function_exists( 'bizplan_remove_pipe' ) ):
/**
* Removes Pipes from the title
*
* @return string
* @since Bizplan 0.1
*/
function bizplan_remove_pipe( $title, $force = false ){

	if( $force || ( is_page() && !is_front_page() ) ){

		$title = str_replace( "\|", "&exception", $title );
		$arr = explode( '|', $title );

		$title = str_replace( '&exception', '|', $arr[ 0 ] );
	}

	return $title;
}
add_filter( 'the_title', 'bizplan_remove_pipe',9999 );

endif;

function bizplan_remove_title_pipe( $title ){
	$title[ 'title' ] = bizplan_remove_pipe( $title[ 'title' ], true );
	return $title;
}
add_filter( 'document_title_parts', 'bizplan_remove_title_pipe',9999 );

if( !function_exists( 'bizplan_get_icon_by_post_format' ) ):
/**
* Gives a css class for post format icon
*
* @return string
* @since Bizplan 0.1
*/
function bizplan_get_icon_by_post_format(){
	$icons = array(
		'standard' => 'kfi-pushpin-alt',
		'sticky'  => 'kfi-pushpin-alt',
		'aside'   => 'kfi-documents-alt',
		'image'   => 'kfi-image',
		'video'   => 'kfi-arrow-triangle-right-alt2',
		'quote'   => 'kfi-quotations-alt2',
		'link'    => 'kfi-link-alt',
		'gallery' => 'kfi-images',
		'status'  => 'kfi-comment-alt',
		'audio'   => 'kfi-volume-high-alt',
		'chat'    => 'kfi-chat-alt',
	);

	$format = get_post_format();
	if( empty( $format ) ){
		$format = 'standard';
	}

	return apply_filters( 'bizplan_post_format_icon', $icons[ $format ] );
}
endif;

if( !function_exists( 'bizplan_has_sidebar' ) ):

/**
* Check whether the page has sidebar or not.
*
* @see https://codex.wordpress.org/Conditional_Tags
* @since Bizplan 0.1
* @return bool Whether the page has sidebar or not.
*/
function bizplan_has_sidebar(){

	if( is_page() || is_search() || is_single() ){
		return false;
	}

	return true;
}
endif;

if( !function_exists( 'bizplan_is_search' ) ):
/**
* Conditional function for search page / jet pack supported
* @since Bizplan 0.2
* @return Bool 
*/
function bizplan_is_search(){

	if( ( is_search() || ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'infinite_scroll'  && isset( $_POST[ 'query_args' ][ 's' ] ))) ){
		return true;
	}

	return false;
}
endif;

function bizplan_post_class_modification( $classes ){

	# Add no thumbnail class when its search page
	if( bizplan_is_search() && ( 'post' !== get_post_type() && !has_post_thumbnail() ) ){
		$classes[] = 'no-thumbnail';
	}
	return $classes;
}
add_filter( 'post_class', 'bizplan_post_class_modification' );

require_once get_parent_theme_file_path( '/inc/setup.php' );
require_once get_parent_theme_file_path( '/inc/template-tags.php' );
require_once get_parent_theme_file_path( '/modules/loader.php' );
require_once get_parent_theme_file_path( '/trt-customize-pro/example-1/class-customize.php' );
