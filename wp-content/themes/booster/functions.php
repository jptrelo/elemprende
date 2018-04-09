<?php 
/*
 * Set up the content width value based on the theme's design.
 */
if ( ! function_exists( 'booster_setup' ) ) :
function booster_setup() {
	
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 745;
	}
	/*
	 * Make booster theme available for translation.
	 */
	load_theme_textdomain( 'booster');
	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', booster_font_url() ) );
	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	// This theme uses wp_nav_menu() in two locations.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 672, 372, true );
	add_image_size( 'booster-full-width', 1038, 576, true );
	add_theme_support( "title-tag" );
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'booster' ),
		'secondary' => __( 'Footer Secondary menu', 'booster' ),
	) );
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list',
	) );
	add_theme_support( 'custom-background', apply_filters( 'booster_custom_background_args', array(
	'default-color' => 'f5f5f5',
	) ) );
	// Add support for featured content.
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'booster_get_featured_posts',
		'max_posts' => 6,
	) );
	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
endif; // booster_setup
add_action( 'after_setup_theme', 'booster_setup' );
// Implement Custom Header features.
require get_template_directory() . '/inc/custom-header.php';
/**
 * Add default menu style if menu is not set from the backend.
 */
function booster_add_menuid ($page_markup) {
preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
$booster_divclass = '';
if(!empty($matches)) { $booster_divclass = $matches[1]; }
$booster_toreplace = array('<div class="'.$booster_divclass.' pull-right-res">', '</div>');
$booster_replace = array('<div class="navbar-collapse collapse pull-right-res">', '</div>');
$booster_new_markup = str_replace($booster_toreplace,$booster_replace, $page_markup);
$booster_new_markup= preg_replace('/<ul/', '<ul class="nav navbar-nav booster-menu"', $booster_new_markup);
return $booster_new_markup; }
add_filter('wp_page_menu', 'booster_add_menuid');

// thumbnail list 
function booster_thumbnail_image($content) {

    if( has_post_thumbnail() )
         return the_post_thumbnail( 'thumbnail' ); 
}
/**
 * Register Lato Google font for booster.
 */
function booster_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'booster' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Lato:300,400,700,900,300italic,400italic,700italic' ), "//fonts.googleapis.com/css" );
	}
	return $font_url;
}
/*
 * Header Title
*/
function booster_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}
	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );
	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}
	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'booster' ), max( $paged, $page ) );
	}
	return $title;
}
add_filter( 'wp_title', 'booster_wp_title', 10, 2 );
/*********** Register Sidebar **************/
function booster_widgets_init() {

	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'booster' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar that appears on the left.', 'booster' ),
		'before_widget' => '<aside id="%1$s" class="widget blog-categories %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'booster_widgets_init' );
/*********** Enqueue File **************/
function booster_enqueue()
{
	wp_enqueue_style('bootstrap',get_template_directory_uri().'/css/bootstrap.css',array(),'','');
	wp_enqueue_style('custom',get_template_directory_uri().'/css/custom.css',array(),'','');
	wp_enqueue_style('style',get_stylesheet_uri(),array(),'','');
	wp_enqueue_script('bootstrapjs',get_template_directory_uri().'/js/bootstrap.js',array('jquery'),'','');	
	wp_enqueue_script('default',get_template_directory_uri().'/js/default.js',array());		
	if ( is_singular() ) wp_enqueue_script( "comment-reply" ); 
}
add_action('wp_enqueue_scripts', 'booster_enqueue');
/***************** Theme Option **********************/
require_once('theme-option/fasterthemes.php');
/*** TGM ***/
require_once('functions/tgm-plugins.php');
/***************** Breadcrumbs **********************/
function booster_custom_breadcrumbs() {
  $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $delimiter = '/'; // delimiter between crumbs
  $home = 'Home'; // text for the 'Home' link
  $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $booster_before = '<span>'; // tag before the current crumb
  $booster_after = '</span>'; // tag after the current crumb
  global $post;
  $homeLink = esc_url(home_url());
  if (is_home() || is_front_page()) {
    if ($showOnHome == 1) echo '<div id="crumbs" class="font-14 color-fff conter-text booster-breadcrumb"><a href="' . $homeLink . '">' . $home . '</a></div>';
  } else {
    echo '<div id="crumbs" class="font-14 color-fff conter-text booster-breadcrumb"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
    if ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
       echo $booster_before . _e('Archive by category','booster') .'"'. single_cat_title('', false) . '"' . $booster_after;
    } elseif ( is_search() ) {
        echo $booster_before . _e('Search results for','booster').' "' . get_search_query() . '"' . $booster_before;
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $booster_before . get_the_time('d') . $booster_after;
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $booster_before . get_the_time('F') . $booster_after;
    } elseif ( is_year() ) {
      echo $booster_before . get_the_time('Y') . $booster_after;
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
        if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $booster_before . get_the_title() . $booster_after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        echo $cats;
        if ($showCurrent == 1) echo $booster_before . get_the_title() . $booster_after;
      }
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $booster_before . $post_type->labels->singular_name . $booster_after;

    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $booster_before . get_the_title() . $booster_after;

    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $booster_before . get_the_title() . $booster_after;

    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $booster_before . get_the_title() . $booster_after;

    } elseif ( is_tag() ) {
     
      echo $booster_before . _e('Posts tagged','booster') .' "' . single_tag_title('', false) . '"' . $booster_before;

    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $booster_before . _e('Articles posted by','booster') . $booster_userdata->display_name . $booster_before;

    } elseif ( is_404() ) {
          echo $booster_before . _e('Error 404','booster') . $booster_before;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page','booster') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
    echo '</div>';
  }
} // end booster_custom_breadcrumbs()
/**
 * Set up post entry meta.
 *
 * Meta information for current post: categories, tags, permalink, author, and date.
 **/
function booster_entry_meta() {	
	$booster_category_list = get_the_category_list() ? ' '. get_the_category_list(', ').' ' :'';	
	$booster_tag_list = get_the_tag_list( ', ', 'booster');
	$booster_date = sprintf( '<a href="%1$s" title="%2$s" ><time datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	$booster_author = sprintf( '<span><a href="%1$s" title="%2$s" >%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'booster' ), get_the_author() ) ),
		get_the_author()
	);
	if ( $booster_tag_list ) {
        $booster_utility_text = __( 'Posted in : %1$s on %3$s by : %4$s %2$s Comments: '.get_comments_number(), 'booster' );
    } elseif ( $booster_category_list ) {
        $booster_utility_text = __( 'Posted in : %1$s on %3$s by : %4$s %2$s Comments: '.get_comments_number(), 'booster' );
    } else {
        $booster_utility_text = __( 'Posted on : %3$s by : %4$s %2$s Comments: '.get_comments_number(), 'booster' );
    }
	printf(
		$booster_utility_text,
		$booster_category_list,
		$booster_tag_list,
		$booster_date,
		$booster_author
	);
}
if ( ! function_exists( 'booster_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own booster_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
function booster_comment( $comment, $booster_args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments. ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
  <p>
    <?php _e( 'Pingback:', 'booster' );
    	comment_author_link();
    	edit_comment_link( __( '(Edit)', 'booster' ), '<span class="edit-link">', '</span>' ); ?>
  </p>
</li>
<?php break;
		default :
		// Proceed with normal comments.
		if($comment->comment_approved==1)
		{
		global $post; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>" class="commentlist">
  		<article id="comment-<?php comment_ID(); ?>" class="comment col-md-12 margin-top-bottom">
    		<figure class="avtar"> <a href="#"><?php echo get_avatar( get_the_author_meta(), '80'); ?></a> </figure>
    			<div class="txt-holder">
      					<?php
                            printf( '<b class="fn">%1$s',
                                get_comment_author_link(),
                                ( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author ', 'booster' ) . '</span>' : ''
                            );
                            echo ' '.get_comment_date().'</b>';
							echo '<a href="#" class="reply pull-right">'.comment_reply_link( array_merge( $booster_args, array( 'reply_text' => __( 'Reply', 'booster' ), 'after' => '', 'depth' => $depth, 'max_depth' => $booster_args['max_depth'] ) ) ).'</a>'; ?>
     				 <div class="blog-post-comment-text comment">
      					<?php comment_text(); ?>
     				 </div>
      <!-- .comment-content --> 
    </div>
    <!-- .txt-holder --> 
  </article>
  <!-- #comment-## -->
  <?php }
		break;
	endswitch; // end comment_type check
}
endif;
/**********************************/
function booster_pagination($pages = '', $range = 1)
{
	$booster_showitems = ($range * 2)+1;
	global $paged;
	if(empty($paged)) $paged = 1;
	if($pages == '')
	{
	global $wp_query;
	$pages = wp_count_posts()->publish;
	if(!$pages)
	{
	$pages = 1;
	}
	}
	if(1 != $pages)
	{
	echo "<div class='booster-pagination-color list-inline text-center'>";
	if($paged > 2 && $paged > $range+1 && $booster_showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'><span class='sprite prev-all-icon'> First </span></a></li>";
	if($paged > 1 && $booster_showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."'><span class='sprite prev-icon'> Prev </span></a></li>";
	
	for ($i=1; $i <= $pages; $i++)
	{
	if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $booster_showitems ))
	{
	echo ($paged == $i)? "<li><a href='#'>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a></li>";
	}
	}
	if ($paged < $pages && $booster_showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."'><span class='sprite next-icon'> Next </span></a></li>";
	if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $booster_showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'> Last <span class='sprite next-all-icon'></span></a></li>";
	echo "</div>\n";
	}
}
/*
 * Replace Excerpt [...] with Read More
 */
function booster_read_more( ) {
return ' ..<br /><a href="'. get_permalink()  .'" title="'.__('Read more','booster').'">'.__('Read more','booster').'</a>';
 }
add_filter( 'excerpt_more', 'booster_read_more' );
add_filter( 'comment_form_default_fields', 'booster_comment_placeholders' );
/**
* Change default fields, add placeholder and change type attributes.
*
* @param array $fields
* @return array
*/
function booster_comment_placeholders( $fields )
{
$fields['author'] = str_replace(
'<input',
'<input placeholder="'
/* Replace 'theme_text_domain' with your theme’s text domain.
* I use _x() here to make your translators life easier. :)
* See http://codex.wordpress.org/Function_Reference/_x
*/
. _x(
'First Name',
'comment form placeholder',
'booster'
)
. '"',
$fields['author']
);
$fields['email'] = str_replace(
'<input',
'<input id="email" name="email" type="text" placeholder="'
. _x(
'Email Id',
'comment form placeholder',
'booster'
)
. '"',
$fields['email']
);
return $fields;
}
add_filter( 'comment_form_defaults', 'booster_textarea_insert' );
function booster_textarea_insert( $fields )
{
$fields['comment_field'] = str_replace(
'</textarea>',
''. _x(
'Comment',
'comment form placeholder',
'booster'
)
. ''. '</textarea>',
$fields['comment_field']
);
return $fields;
} ?>