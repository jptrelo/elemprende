<?php
/*
 * Theme function file.
*/
require get_template_directory() . '/inc/theme_setup.php';
require get_template_directory() . '/inc/enqueue_script.php';
require get_template_directory() . '/inc/breadcrumbs.php';
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/theme-options/fasterthemes.php';
require get_template_directory() . '/inc/contributor_information.php';
require get_template_directory() . '/inc/tgm-plugins.php';
/**
 * Add default menu style if menu is not set from the backend.
 */
function photoshoot_add_menuid ($page_markup) {
preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $photoshoot_matches);
$photoshoot_toreplace = array('<div class="menu">', '</div>');
$photoshoot_replace = array('', '');
$photoshoot_new_markup = str_replace($photoshoot_toreplace,$photoshoot_replace, $page_markup);
$photoshoot_new_markup= preg_replace('/<ul/', '<ul', $photoshoot_new_markup);
return $photoshoot_new_markup; } //}
add_filter('wp_page_menu', 'photoshoot_add_menuid');
//fetch title
function photoshoot_title() {
	  if (is_category() || is_single())
	  {
	   if(is_category())
		  the_category();
	   if (is_single())
		 the_title();
	   }
	   elseif (is_page()) 
		  the_title();
	   elseif (is_search())
		   echo the_search_query();
    }
// thumbnail list 
function photoshoot_thumbnail_image($content) {
    if( has_post_thumbnail() )
         return the_post_thumbnail( 'thumbnail' ); 
}    
if ( ! function_exists( 'photoshoot_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Meta information for current post: categories, tags, permalink, author, and date.
 **/
function photoshoot_entry_meta() { ?>
<div class="details-right"><?php
	$photoshoot_date = '<span>'.__('Added','photoshoot').':</span>'.sprintf( '<a href="%1$s" title="%2$s"><time datetime="%3$s">%4$s</time></a>',
		esc_url( get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j')) ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date('d M,Y') )
	); ?>
	</div><div class="details-left">
	<?php $photoshoot_author = '<span>'.__('Author','photoshoot').':</span>'.sprintf( ' <a href="%1$s" title="%2$s" >%3$s</a>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr(ucwords(get_the_author()) ),
		ucwords(get_the_author())
	); ?></div><?php $photoshoot_comment = '<div class="details-left"><span>'.__('Comments','photoshoot').':</span>'.sprintf( ' %1$s',
		get_comments_number()
	).'</div>';
	printf('%1$s %2$s %3$s',
        $photoshoot_comment,
		$photoshoot_date,
		$photoshoot_author
		);
	}
endif;
if ( ! function_exists( 'photoshoot_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own photoshoot_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
function photoshoot_comment( $comment, $photoshoot_args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments. ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
  <p>
    <?php _e( 'Pingback:', 'photoshoot' );
    	comment_author_link();
    	edit_comment_link( __( 'Edit', 'photoshoot' ), '<span class="edit-link">', '</span>' ); ?>
  </p>
</li>
<?php break;
		default :
		// Proceed with normal comments.
		if($comment->comment_approved==1)
		{
		global $post; ?>
	<div <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>" class="comment-list">
  		<div id="comment-<?php comment_ID(); ?>" class="comment col-md-12 photoshoot-blog-comment no-padding">
    		<div class="comment-img"> <a href="#"><?php echo get_avatar( get_the_author_meta('ID'), '80'); ?></a> </div>
                <div class="comment-message-section">
                    <div class="comm-title">
						<h2><?php printf( '%1$s',get_comment_author_link()); ?></h2>
                            <?php printf( '',
                                    get_comment_author_link(),
                                    ( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author ', 'photoshoot' ) . '</span>' : ''
                                ); ?>
                            <?php echo '<span>'.get_comment_date().'</span>';
                            echo '<a href="#">'.comment_reply_link( array_merge( $photoshoot_args, array( 'reply_text' =>  '<div class="pull-right color-red">'.__('Reply','photoshoot').'</div>', 'after' => '', 'depth' => $depth, 'max_depth' => $photoshoot_args['max_depth'] ) ) ).'</a>'; ?>
                         <div class="blog-post-comment-text comment">
                              <?php comment_text(); ?>
                         </div>
          <!-- .comment-content --> 
        </div>
            </div>
            <div class="comment-line-post"></div>
    <!-- .txt-holder --> 
  </article>
  <!-- #comment-## -->
  <?php }
		break;
	endswitch; // end comment_type check
}
endif;
function photoshoot_setPostViews($postID) {
    $photoshoot_count = get_post_meta($postID, 'post_views_count', true);
    if((!empty($photoshoot_count)))
        update_post_meta($postID, 'post_views_count', $photoshoot_count+=1);
    else if($photoshoot_count == 0)
      update_post_meta($postID, 'post_views_count', $photoshoot_count+=1);
    else
       add_post_meta($postID, 'post_views_count', '0');
} ?>