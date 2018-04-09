<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package SweetHeat
 */

if ( ! function_exists( 'sweetheat_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function sweetheat_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'sweetheat' ); ?></h1>
		<div class="pagination">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="button rounded small nav-previous"><?php next_posts_link( __( 'Older posts', 'sweetheat' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="button rounded small nav-previous nav-next"><?php previous_posts_link( __( 'Newer posts', 'sweetheat' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'sweetheat_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function sweetheat_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'sweetheat' ); ?></h1>
		<div class="pagination">
			<?php
				previous_post_link( '<div class="nav-previous button rounded small nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'sweetheat' ) );
				next_post_link(     '<div class="nav-next button rounded small nav-previous">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'sweetheat' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'sweetheat_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function sweetheat_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( '%s', 'post date', 'sweetheat' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( '%s', 'post author', 'sweetheat' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

}
endif;

if ( ! function_exists( 'sweetheat_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function sweetheat_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'sweetheat' ) );
		if ( $categories_list && sweetheat_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( '%1$s', 'sweetheat' ) . '</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'sweetheat' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( '%1$s', 'sweetheat' ) . '</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'sweetheat' ), __( '1 Comment', 'sweetheat' ), __( '% Comments', 'sweetheat' ) );
		echo '</span>';
	}

	edit_post_link( __( 'Edit', 'sweetheat' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'sweetheat_entry_footer_index' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function sweetheat_entry_footer_index() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'sweetheat' ) );
		if ( $categories_list && sweetheat_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( '%1$s', 'sweetheat' ) . '</span>', $categories_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'sweetheat' ), __( '1 Comment', 'sweetheat' ), __( '% Comments', 'sweetheat' ) );
		echo '</span>';
	}

	edit_post_link( __( 'Edit', 'sweetheat' ), '<span class="edit-link">', '</span>' );
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function sweetheat_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'sweetheat_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'sweetheat_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so sweetheat_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so sweetheat_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in sweetheat_categorized_blog.
 */
function sweetheat_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'sweetheat_categories' );
}
add_action( 'edit_category', 'sweetheat_category_transient_flusher' );
add_action( 'save_post',     'sweetheat_category_transient_flusher' );
