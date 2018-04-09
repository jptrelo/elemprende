<?php
/**
 * File Name: styledstore-function
 * @package styledstore
 * @author StyledThemes
 * @since version since 1.0
 * @uses contain functions to extend features
*/


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function styledstore_pro_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// add class woocommerce-page in shope homepage
	if ( is_page_template('page-templates/woocommerce-home.php') ) {
		$classes[] = 'woocommerce-page';
	}

	return $classes;
}
add_filter( 'body_class', 'styledstore_pro_body_classes' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 * @param array $args Configuration arguments.
 * @return array
 */
function styledstore_page_menu_args( $args ) {
	
	$args[ 'show_home' ] = true;
	$args[ 'container' ] = 'ul';
	$args[ 'menu_class' ] = 'header-menu sm sm-mint';
	$args[ 'menu_id' ]	= 'main-menu';
    $args['depth'] = 1;
	return $args;
}

add_filter( 'wp_page_menu_args', 'styledstore_page_menu_args' );

if ( ! function_exists( 'styledstore_the_custom_logo' ) ) :

/**
 * Displays the optional custom logo.
 * Does nothing if the custom logo is not available.
 * @since StyledStore
 */

function styledstore_the_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
endif;

if ( ! function_exists( 'styledstore_social_links' ) ) :
/**
 * @author StyledThemes 
 * @action_hook header_top_bar_social_links
 * @uses social media links with icon
*/

function styledstore_social_links() { 

	if( get_theme_mod( 'styledstore_show_social_icon' ) != '' ) { ?>
		<div class="social-icons ">
			<?php if( get_theme_mod( 'facebook_uid') ) { ?>
				<li class="social-icon facebook">
					<a href="<?php echo esc_url( get_theme_mod( 'facebook_uid') ); ?>">
						<i class="fa fa-facebook" aria-hidden="true"></i>
					</a>
				</li>
			<?php }
			if( get_theme_mod( 'skype_uid' ) ) { ?> 
				<li class="social-icon skype">
					<a href="skype:<?php echo get_theme_mod( 'skype_uid' );?>?call">
						<i class="fa fa-skype" aria-hidden="true"></i>
					</a>
				</li>
			<?php }
			if( get_theme_mod( 'twitter_uid' ) ) { ?> 
				<li class="social-icon twitter">
					<a href="<?php echo esc_url( get_theme_mod( 'twitter_uid' ) ); ?>">
						<i class="fa fa-twitter" aria-hidden="true"></i>
					</a>
				</li>
			<?php }
			if( get_theme_mod( 'rss_uid' ) ) { ?> 
				<li class="social-icon rss">
					<a href="<?php echo esc_url( get_theme_mod( 'rss_uid' ) ); ?>">
						<i class="fa fa-rss" aria-hidden="true"></i>
					</a>
				</li>
			<?php } 	
			//add extra social icon links 
			do_action( 'styledstore_add_social_icon' ); 
			?>
		</div>
	<?php }
}
endif;

add_action( 'styledstore_header_top_bar_social_links', 'styledstore_social_links', 10 );


if ( ! function_exists( 'styledstore_the_post_thumbnail' ) ) :
/**
 * @package Styledstore 
 * @author StyledThemes 
 * @since version 1.0
 * @return  post featured image
*/
function styledstore_the_post_thumbnail() {

	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	} ?>

	<div class="post-thumbnail">
		<?php if( is_singular() ) : ?>

			
				<?php if( is_single() && get_theme_mod( 'styledstore_hide_featured' ) == '' ) { ?>
					<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
					<?php 
						the_post_thumbnail( 'styledstoreblog-image', array( 'alt' => the_title_attribute( 'echo=0' ), 'class' => 'post-featured-image' ) );
					echo '</a>';
					} elseif( is_page() && get_theme_mod( 'styledstore_hide_featured_page' ) == '' ) { ?>
						<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
							<?php the_post_thumbnail( 'styledstoreblog-image', array( 'alt' => the_title_attribute( 'echo=0' ), 'class' => 'page-featured-image' ) );
						echo '</a>';
					} else {
						return;
					}

		else : 
			if( get_theme_mod( 'blog_layout' ) == 'blogwide') { ?>
				<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
					<?php the_post_thumbnail( 'styledstore-blog-fulwidth', array( 'alt' => the_title_attribute( 'echo=0' ), 'class' => "blog-featured-image" ) ); ?>
				</a>
			<?php } else { ?>
					<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
						<?php the_post_thumbnail( 'styledstoreblog-image', array( 'alt' => the_title_attribute( 'echo=0' ), 'class' => "blog-featured-image" ) ); ?>
					</a>

			<?php } ?>
					

		<?php endif; // End is_singular() ?>
	</div><!-- .post-thumbnail -->
		
<?php }
endif;

if ( ! function_exists( 'styledstore_navigation_menu' ) ) :
/**
 * @author StyledThemes 
 * @action_hook styledstore_header_navigation_menu
 * @uses social media links with icon
*/

function styledstore_navigation_menu() { ?>

		<div class="navigation-menu sameheight col-xs-4 col-sm-6 col-md-8">
			<?php
				$styledstore_primary_nav = array(
					'theme_location'  => 'primary',
					'container'       => false,
					'menu_class'      => 'header-menu sm sm-mint',
					'menu_id'			=> 'main-menu',
					'fallback_cb'       => 'wp_page_menu'
				);
				wp_nav_menu( $styledstore_primary_nav );
			?>
		</div>

		<div class="mobile-navigation col-xs-4 col-sm-6 col-md-8">
		<div class="st-mobile-menu">
			<input id="main-menu-state" type="checkbox" />
				<label class="main-menu-btn" for="main-menu-state">
				<span class="main-menu-btn-icon"></span>
			</label>
			
			<?php
				$styledstore_primary_nav = array(
					'theme_location'  => 'mobile',
					'container'       => false,
					'menu_class'      => 'header-menu sm sm-mint',
					'menu_id'			=> 'main-menu',
					'fallback_cb'       => 'wp_page_menu',
				);
				wp_nav_menu( $styledstore_primary_nav );
			?>
		</div>
	</div>
	</div><!-- container class closed -->
</div><!-- header class closed -->

		<!-- mobile menu -->
	
<?php
}
endif;	//styledstore_navigation_menu

add_filter('wp_nav_menu_items','styledstore_add_search_form_with_navigation_menu', 10, 2);

if( ! function_exists( 'styledstore_add_search_form_with_navigation_menu' ) ) :
/**
 * @author StyledThemes 
 * @action_hook wp_nav_menu_items
 * @return search form
 * @uses add woocommerce search form and wordpress search form with navigation menu. Amd YOu can customize this function simply by overwriting styledstore_add_search_form_with_navigation_menu() on child theme functions.php
 * @version 1.0
*/


function styledstore_add_search_form_with_navigation_menu( $items, $args ) {
	
	$form = '';
    if( $args->theme_location == 'primary' ) {
    	if ( styledstore_check_woocommerce_activation() ) :

    	$form = '<li class="st-search attach-with-menu"> <form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
			<label class="screen-reader-text" for="s">' . __( 'Search for:', 'styled-store' ) . '</label>
			<input type="text" value="' .esc_attr( get_search_query() ) . '" name="s" id="s" placeholder="'.esc_attr_x( 'search For', 'placeholder', 'styled-store' ).'" />
			<button class="btn" type="submit" value="'.esc_attr_x( 'Search', 'submit button', 'styled-store' ).'"><i class="fa fa-search"></i> </button>
			<input type="hidden" name="post_type" value="product" />
			</form>
			</li>';
		else :
			$form = '<li class="st-search attach-with-menu"><form role="search" id="searchform" method="get" class="search-form" action="'. esc_url( home_url( '/' ) ).'">
					  <input type="text" placeholder="'.esc_attr_x( 'search For', 'placeholder', 'styled-store' ).'" value="'.esc_attr( get_search_query() ).'" name="s" >
					  
					  <button class="btn" type="submit" value="'.esc_attr_x( 'Search', 'submit button', 'styled-store' ).'"><i class="fa fa-search"></i></button>
				</form></li>';
		endif;
	}
    return $items.$form;
}
endif;
add_action( 'styledstore_header_navigation_menu', 'styledstore_navigation_menu', 10);

if ( ! function_exists( 'styledstore_paging_nav' ) ) :
/**
 * Create pagination of posts
 *
 * Create your own styledstore_paging_nav() function to override in a child theme
 * hook
 * @since Styld Store 1.0
 *
 * @return pagination
 */

function styledstore_paging_nav() {
	
	global $wp_query;
    if ( $wp_query->max_num_pages > 1 ) { ?>
        <nav role="navigation" id="styledstore-navigation">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'styled-store' ); ?></h1>
                <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'styled-store' ) ); ?></div>
                <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'styled-store' ) ); ?></div>
        </nav><!-- #nav -->
	<?php
	}
}
endif;

if ( ! function_exists( 'styeldstore_design_comment_form')) :
/**
 * make custom changes on comment form
 *
 * Create your own styledstore_categorized_blog() function to override in a child theme.
 *
 * @since Styld Store 1.0
 *
 * @return customize comment form
 */
function styeldstore_design_comment_form($fields) {

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields =  array(

	'author' =>
	    '<div classs="row">
	    	<div class="col-md-4 col-xs-12" id="styledstore-disable-padding">
			    <p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'styled-store' ) . '</label> ' .
			    ( $req ? '<span class="required">*</span>' : '' ) .
			    '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			    '" size="30"' . $aria_req . ' />
			    </p>
	    	</div>',

	'email' =>
	    '<div class="col-md-4 col-xs-12">
		    <p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'styled-store' ) . '</label> ' .
		    ( $req ? '<span class="required">*</span>' : '' ) .
		    '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
		    '" size="30"' . $aria_req . ' />
		    </p>
		</div>',

	'url' =>
	    '<div class="col-md-4 col-xs-12">
		    <p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'styled-store' ) . '</label>' .
		    '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
		    '" size="30" />
		    </p>
		</div>
	    </div>',
);
	return $fields;
	

}
add_filter( 'comment_form_default_fields', 'styeldstore_design_comment_form' );
endif;

if ( ! function_exists( 'styledstore_comment_field_to_bottom')) :
/**
 * make text form below other form fields
 *
 * Create your own styledstore_comment_field_to_bottom() function to override in a child theme.
 *
 * @since Styld Store 1.0
 *
 * @return customize comment form
 */
function styledstore_comment_field_to_bottom( $fields ) {

	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}
add_filter( 'comment_form_fields', 'styledstore_comment_field_to_bottom' );
endif;


if ( ! function_exists( 'styledstore_change_comment_args') ) :
/**
 * change default arguments on comment form
 *
 * Create your own styledstore_change_comment_args() function to override in a child theme using styledstore_change_comment_args 
 * hook
 * @since Styld Store 1.0
 *
 * @return customize comment form
 */

function styledstore_change_comment_args($args) {

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$required_text = '';

	$args = array(
		'title_reply' => esc_html__( 'Leave a Reply', 'styled-store' ),
		'class_submit' => 'styledstore-submit',
		'comment_notes_before' => '<p class="comment-notes">' .esc_html__( 'Your email address will not be published.', 'styled-store' ) . ( $req ? $required_text : '' ) . '</p>',
		'label_submit' => esc_html__( 'SUBMIT', 'styled-store' )
		);
	return $args;


}
endif;
add_filter( 'styledstore_change_comment_argument', 'styledstore_change_comment_args', 10, 1 );


/**
*@package Styled Store
*@since  version 2.5.0
*@description add move to top featured
*/

function styledstore_move_to_top_fnc() {

  $move_to_top_check = get_theme_mod('styledstore_movetotop' );
    if ($move_to_top_check == 1) { ?>
      <div class="styledstore_move_to_top"> 
        <i class="fa fa-arrow-up"></i>
      </div>  
    <?php }
}
add_action('styledstore_move_to_top', 'styledstore_move_to_top_fnc');

/**
*@package Styled Store
*@description add read more tag text
*/

add_filter( 'the_content_more_link', 'styledstore_modify_read_more_link' );

function styledstore_modify_read_more_link() {

	return '<div class="more-link">
			<a href="'.esc_url( get_permalink() ).'">' . esc_html__( 'READ MORE', 'styled-store' ) . '</a>
		</div>';
}

if ( ! function_exists( 'styledstore_payment_links' ) ) :
	/**
	 * @author StyledThemes 
	 * @action_hook header_top_bar_social_links
	 *  * Create your own styledstore_payment_links() function to override in a child theme.
	 * @uses social media links with icon
	*/
	function styledstore_payment_links() { ?>

		<div class="st-icons-block">
			<div class="container">
				<?php if( get_theme_mod( 'styledstore_support_payment_visa') ) { ?>
					<span class="footer-icon">
					<img src="<?php echo esc_url( get_theme_mod( 'styledstore_support_payment_visa' ) ); ?>">
					</span>
				<?php }

				if( get_theme_mod( 'styledstore_support_payment_mastercard' ) ) { ?>
					<span class="footer-icon">
						<img src="<?php echo esc_url( get_theme_mod( 'styledstore_support_payment_mastercard' ) ); ?>">
					</span>
				<?php } 
			 
				if( get_theme_mod( 'styledstore_support_payment_paypal') ) { ?>		
					<span class="footer-icon">
						<img src="<?php echo esc_url( get_theme_mod( 'styledstore_support_payment_paypal' ) ); ?>">
					</span>
				<?php }	

				if( get_theme_mod( 'styledstore_support_payment_amazon') ) { ?>	
				<span class="footer-icon">
					<img src="<?php echo esc_url( get_theme_mod( 'styledstore_support_payment_amazon' ) ); ?>">
				</span>
				<?php }
				
				if( get_theme_mod( 'styledstore_support_payment_am') ) { ?>	
					<span class="footer-icon">
						<img src="<?php echo esc_url( get_theme_mod( 'styledstore_support_payment_am' ) ); ?>">
					</span>
				<?php }
				/*Make use of styledstore-add-paymentgate action hooks to add payment links */ 	
				do_action( 'styledstore_add_paymentgate_way' ); ?>
			</div>
		</div>	

	<?php }
endif;
add_action( 'styledstore_add_payment_links', 'styledstore_payment_links' );

/**
 * @author StyledThemes 
 * @return true /false
 * @uses check for woocomerce plugin
 * @version 1.0
*/
function styledstore_check_woocommerce_activation() {

	return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}


add_action( 'styledstore_post_loop', 'styledstore_post_content_loop', 10 );

if ( ! function_exists( 'styledstore_post_content_loop' ) ) :
	/**
	 * @author StyledThemes 
	 * @action_hook styledstore_post_loop
	 * @return post loop on blog page
	 * @uses show number of post
	 * @version 1.0
	*/
	function styledstore_post_content_loop() { ?>

		<main id="main" class="site-main" role="main">
			<?php if ( have_posts() ) :
				while( have_posts() ) : the_post();
					/*
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content' );
			    	// End the loop.
				endwhile;
				//create pagination
				styledstore_paging_nav();
				// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif; ?>
		</main><!-- #main .site-main -->
<?php }
endif;

if ( ! function_exists( 'styledstore_footertopgroup')) :
/**
 * change default arguments on comment list
 *
 * Create your own styledstore_footertopgroup() function to override in a child theme
 * hook
 * @since Styld Store 1.1.0
 *
 * @return customize comment form
 */
function styledstore_footertopgroup() {

	$count = 0;
	if ( is_active_sidebar( 'styled_store_footertop1' ) )
		$count++;
	if ( is_active_sidebar( 'styled_store_footertop2' ) )
		$count++;
	if ( is_active_sidebar( 'styled_store_footertop3' ) )
		$count++;		
	$class = '';
	switch ( $count ) {
		case '1':
			$class = 'col-md-12';
			break;
		case '2':
			$class = 'col-md-6 col-sm-6 col-xs-6';
			break;
		case '3':
			$class = 'col-md-4 col-sm-4 col-sm-4';
			break;
		
	}
	if ( $class )
		return $class;
}
endif;

add_action( 'styledstore-homepage-latest-article', 'styledstore_homepage_latest_articles' );

if ( ! function_exists( 'styledstore_homepage_latest_articles' ) ) :
/**
 * @author StyledThemes 
 * @action_hook styledstore-homepage-latest-article
 * Create your own styledstore_homepage_latest_articles() function to override in a child theme.
 * @uses display latest article
 * @version @since 1.1.0
*/
function styledstore_homepage_latest_articles() { ?>

	<div class="st_block_71">
		<div class="container">
			<div class="row">
				<div class="st_section_title"><?php esc_html_e( 'Latest Article', 'styled-store' ); ?></div>
				<div class="st_latest_article">
					<?php
						$counter = 0;
						$st_loop = new WP_Query(array(
							'post_type'	=> 'post',
							'posts_per_page'	=> 4,
						));
						while( $st_loop->have_posts() ) : $st_loop->the_post();
						$counter++; ?>	
							<div id="post-<?php the_ID(); ?>" <?php post_class( 'st-article-'.$counter .' article_list col-md-3 col-xs-12'); ?>>
								<?php if( has_post_thumbnail() ) { ?>
									<a href="<?php echo esc_url( get_the_permalink() ); ?>">
										<div class="artclee_img">
										<?php the_post_thumbnail( 'shop_catalog' ); ?>
										</div>
									</a>	
								<?php } ?>
								<div class="article_detail">
									<h3 class="title"><?php the_title(); ?></h3>
									<span class="author_"><?php printf( esc_html__( '%1$s %2$s', 'styled-store' ), 'By', get_the_author() ); ?></span>
									<span class="post_date"><?php echo the_time( 'd.m.Y' ); ?></span>
								</div>
							</div>
					<?php endwhile;
						wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	</div>

<?php }
endif;


add_action( 'styledstore_slider', "styledstore_slider_generate");

if ( ! function_exists( 'styledstore_slider_generate' ) ) :
/**
 * @author StyledThemes 
 * @action_hook styledstore_slider
 * Create your own styledstore_slider() function to override in a child theme.
 * @uses display slider on homepage
 * @version @since 1.1.0
*/
function styledstore_slider_generate() { 

	$st_portfolio_id = get_theme_mod( 'styledstore_slider_category' );
	$loop = new WP_Query( array( 'post_type' => 'product', 
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'terms'    => $st_portfolio_id
			),
		)
	) ); ?>
    <div id="st-slider">
        <div class="st-slider-slides">
          	<div class="st_slider" style="visibility: hidden">
            
	            <?php while( $loop->have_posts() ) : $loop->the_post(); ?>
	                <div>
	                    <?php the_post_thumbnail( 'styledstore-homepage-slider' ); ?>
	                    <div class="st-slider-caption">
	                        <div class="subtitle_abv"><?php the_excerpt(); ?></div>
	                        <div class="slide_title"><?php the_title() ?></div>
                        		<div class="labels">
	                        		<?php $woocommerce_tags = get_the_terms( get_the_ID(), 'product_tag' );
	                        	
	                        		if( $woocommerce_tags ) {
		                        		foreach ( $woocommerce_tags  as $key => $woocommerce_tag) { ?>
		                        			<span class="lable">
		                     					<a href="<?php echo esc_url( get_tag_link( $woocommerce_tag->term_id) ); ?>" >
		                     						<?php echo esc_html( $woocommerce_tag->name ); ?>
		                     					</a>	
		                     				</span>	
		                        		<?php }
	                        		} ?>
                        		</div>
	                    </div>
	               	</div>
            	<?php endwhile;
          		wp_reset_postdata();  ?>
        	</div>
      	</div>
    </div>
<?php }
endif;

/**
* @return add notice on theme activation
* @since @version 1.5.9
*/
add_action( 'load-themes.php', 'styledstore_admin_notice' );
if( ! function_exists( 'styledstore_admin_notice' ) ) :
	function styledstore_admin_notice() {
		global $pagenow;
		wp_enqueue_style( 'styled-store-admin-notice', get_template_directory_uri() . '/css/admin-message.css', array(), '1.0' );
		// Let's bail on theme activation.
		if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', 'styledstore_welcome_message' );
			update_option( 'styledstore_admin_notice_welcome', 1 );

		// No option? Let run the notice wizard again..
		} elseif( ! get_option( 'styledstore_admin_notice_welcome' ) ) {
			add_action( 'admin_notices', 'styledstore_welcome_message' );
		}
	}
endif;

/*
* dismiss notice
*/
function styledstore_welcome_message() {
	?>
	<div id="message" class="updated st-message">
		<a class="st-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( remove_query_arg( array( 'activated' ), add_query_arg( 'styledstore-admin-notice', 'welcome' ) ), 'styledstore_hide_notices_nonce', '_st_nonce' ) ); ?>"><?php esc_html_e( 'Dismiss', 'styled-store' ); ?></a>
		<p><?php printf( esc_html__( 'Welcome to the Styled Store,We are delighted that you chose us.If you have any problem setting up this theme then please import demo data with our  %sStyled Store Theme Demo Importer Plugin %s ', 'styled-store' ), '<a target="_blank" href="' . esc_url( 'https://wordpress.org/plugins/styled-store-demo-importer/' ) . '">', '</a>' ); ?></p>
	</div>
	<?php
}