<?php
/**
 * The template for displaying Comments.
 *
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return; ?>
<div class="clearfix"></div>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
    <h2 class="comments-title">
		<?php printf( _n( 'One thought on - %2$s', '%1$s thoughts on - %2$s', get_comments_number(), 'booster' ), number_format_i18n( get_comments_number() ), get_the_title() ); ?>
	</h2>
    <ul class="">
    <?php wp_list_comments( array( 'callback' => 'booster_comment', 'style' => 'ul' ) ); ?>
    </ul>
       <?php paginate_comments_links();
       endif; // have_comments()
	$booster_args = array('comment_notes_after'=>'All fields are mandatory.',
	  'comment_notes_before'=>'',
	  'title_reply' => 'LEAVE A COMMENT',
	  'label_submit'=>'Submit Comment',
	  'fields' => apply_filters( 'comment_form_default_fields', array(
			'author' =>
			  '<p class="comment-form-author">' .						  
			  '<input id="author" name="author" type="text"  value="' . esc_attr( $commenter['comment_author'] ) .
			  '" size="30" /></p>',
		
			'email' =>
			  '<p class="comment-form-email">'.
			  '<input id="email" name="email" type="text"  value="' . esc_attr(  $commenter['comment_author_email'] ) .
			  '" size="30" /></p>'
			  )),
			  'comment_field' => '<p>' .
			  '<textarea id="comment" name="comment"  cols="45" rows="8" aria-required="true"></textarea>' .
			  '</p>',
			  'comment_notes_after' => 'All fields are mandatory.',
			);
	comment_form($booster_args); ?>
</div><!-- #comments .comments-area -->