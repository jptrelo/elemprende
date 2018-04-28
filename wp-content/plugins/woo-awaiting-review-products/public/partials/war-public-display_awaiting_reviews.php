<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       www.nw2web.com.br
 * @since      1.0.0
 *
 * @package    Woo_Awaiting_Reviews
 * @subpackage Woo_Awaiting_Reviews/public/partials
 */
?>

<?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->id)) : ?>

    <div id="review_form_wrapper">
        <div id="review_form" >
            <?php
            $commenter = wp_get_current_commenter();

            $comment_form = array(
                /* FIX have_comments() */
                'title_reply' => $comments ? __('Add a review', 'woocommerce') : sprintf(__('Be the first to review &ldquo;%s&rdquo;', 'woocommerce'), get_the_title()),
                'title_reply_to' => __('Leave a Reply to %s', 'woocommerce'),
                'comment_notes_after' => '',
                'fields' => array(
                    'author' => '<p class="comment-form-author">' . '<label for="author">' . __('Name', 'woocommerce') . ' <span class="required">*</span></label> ' .
                    '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" aria-required="true" required /></p>',
                    'email' => '<p class="comment-form-email"><label for="email">' . __('Email', 'woocommerce') . ' <span class="required">*</span></label> ' .
                    '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" aria-required="true" required /></p>',
                ),
                'label_submit' => __('Submit', 'woocommerce'),
                'logged_in_as' => '',
                'comment_field' => ''
            );

            if ($account_page_url = wc_get_page_permalink('myaccount')) {
                $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a review.', 'woocommerce'), esc_url($account_page_url)) . '</p>';
            }

            if (get_option('woocommerce_enable_review_rating') === 'yes') {
                $comment_form['comment_field'] = '<p class="comment-form-rating"><label for="rating">' . __('Your Rating', 'woocommerce') . '</label><select name="rating" id="rating" aria-required="true" required>
							<option value="">' . __('Rate&hellip;', 'woocommerce') . '</option>
							<option value="5">' . __('Perfect', 'woocommerce') . '</option>
							<option value="4">' . __('Good', 'woocommerce') . '</option>
							<option value="3">' . __('Average', 'woocommerce') . '</option>
							<option value="2">' . __('Not that bad', 'woocommerce') . '</option>
							<option value="1">' . __('Very Poor', 'woocommerce') . '</option>
						</select></p>';
            }

            $comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . __('Your Review', 'woocommerce') . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required></textarea></p>';

            comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
            ?>
        </div>
    </div>

<?php else : ?>

    <p class="woocommerce-verification-required"><?php _e('Only logged in customers who have purchased this product may leave a review.', 'woocommerce'); ?></p>

<?php endif; ?>

