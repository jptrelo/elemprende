<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package styledstore
 */

if ( ! function_exists( 'styledstore_posted_on' ) ) :
/**
if ( ! function_exists( 'styledstore_entry_date' ) ) :
/**
 * @package Styledstore
 * @author styledthemes
 * @description Prints HTML with date information for current post.
 * Create your own styledstoreentry_date() function to override in a child theme.
 * @since Styeldstore 1.0
 */
function styledstore_entry_date() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	} else {
		$time_string = '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	if ( 'post' === get_post_type() && ! is_singular() ) {
		$author_description = sprintf( '<span class="byline clearfix"><span class="author vcard"><span class="posted_by">%1$s</span><a class="url fn n" href="%2$s">%3$s</a></span></span>',
			esc_html__( 'Posted by ', 'styled-store' ) . '&nbsp',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);

		printf( '<div class="posted-meta">%1$s<div class="posted-on"><a href="%2$s" rel="bookmark">%3$s</a></div></div>',
		$author_description,
		esc_url( get_permalink() ),
		$time_string
		
	);
	} elseif ( 'post' === get_post_type() && is_singular() ) {
		$author_avatar_size = apply_filters( 'styledstore_author_avatar_size', 100 );
		printf( '
			<span class="byline clearfix"><span class="author vcard"><div class="auhor-thumb col-md-2 col-sm-3 col-xs-12">%1$s</div><span class="screen-reader-text">%2$s </span>
			<div class="author-metainfo col-md-10 col-sm-9 col-xs-12" <a class="url fn n" href="%3$s">by %4$s</a>
			<span class="st-author-description">%5$s</span>
			</div></span></span>',
			get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
			_x( 'Author', 'Used before post author name.', 'styled-store' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author(),
			get_the_author_meta( 'description' )
		);
	}

	
}
endif;

if ( ! function_exists( 'styledstore_entry_taxonomies' ) ) :
/**
 * Prints HTML with category and tags for current post.
 *
 * Create your own styledstore_entry_taxonomies() function to override in a child theme.
 *
 * @since Styled Store 1.0
 */
function styledstore_entry_taxonomies() {

		$categories_list = get_the_category_list( esc_html__( ', ', 'styled-store' ) );
		if ( $categories_list && styledstore_categorized_blog() ) {
			
			printf( '<span class="cat-links">
					<span class="cat-text">%1$s</span>
					<span class="screen-reader-text">%2$s</span>%3$s</span>',
				esc_html__( 'Posted In', 'styled-store'),
				esc_html__( 'Categories', 'styled-store' ),
				$categories_list
			);
		}

		$tags_list =  get_the_tag_list( '', esc_html__( ', ', 'styled-store' ) );
		if ( $tags_list ) {
			printf( '<span class="tax-divider">|</span>
					<span class="tags-links">
					<span class="tag-text">%1$s</span>
					<span class="screen-reader-text">%2$s </span>%3$s</span>',
					esc_html__('Tagged', 'styled-store'),
					esc_html__( 'Tags', 'styled-store' ),
					$tags_list
			);
		}  
		if ( ! is_singular()) :
			if ( comments_open() ) { ?>
				<span class="comments-link"><a href="<?php comments_link(); ?>"><?php esc_html_e(  'Leave a comment', 'styled-store' ); ?></a></span> 
			<?php }	
		endif;
		if ( is_singular() ) :
			/*display author meta information*/
			styledstore_entry_date();
		endif;
}
endif;

 /* Determines whether blog/site has more than one category.
 *
 * Create your own styledstore_categorized_blog() function to override in a child theme.
 *
 * @since Styld Store 1.0
 *
 * @return bool True if there is more than one category, false otherwise.
 */
function styledstore_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'styledstore_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			// We only need to know if there is more than one category.
			'number'     => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'styledstore_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 0 ) {
		// This blog has more than 1 category so styledstore_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so styledstore_categorized_blog should return false.
		return false;
	}
}

/**
 * Flushes out the transients used in styledstore_categorized_blog().
 *
 * @since Styled Store 1.0
 */
function styledstore_categories_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'styledstore_categories' );
}
add_action( 'edit_category', 'styledstore_categories_transient_flusher' );
add_action( 'save_post',     'styledstore_categories_transient_flusher' );