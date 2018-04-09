<?php
/*
 * photoshoot Breadcrumbs
*/
function photoshoot_custom_breadcrumbs() {
  $photoshoot_showonhome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $photoshoot_delimiter = '/'; // photoshoot_delimiter between crumbs
  $photoshoot_home = __('Home','photoshoot'); // text for the 'Home' link
  $photoshoot_showcurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $photoshoot_before = ' '; // tag before the current crumb
  $photoshoot_after = ' '; // tag after the current crumb

  global $post;
  $photoshoot_homelink = esc_url(home_url());

  if (is_home() || is_front_page()) {

    if ($photoshoot_showonhome == 1) echo '<div class="breadcrumb-photoshoot"><div class="container photoshoot-container"><ul class="breadcrumb"><li class="active"><a href="' . $photoshoot_homelink . '">' . $photoshoot_home . '</a></li></ul></div></div>';
  } else {

    echo '<div class="breadcrumb-photoshoot"><div class="container photoshoot-container"><ul class="breadcrumb"><li class="active"><a href="' . $photoshoot_homelink . '">' . $photoshoot_home . '</a> ' . $photoshoot_delimiter . ' ';
    if ( is_category() ) {
      $photoshoot_thisCat = get_category(get_query_var('cat'), false);
      if ($photoshoot_thisCat->parent != 0) echo get_category_parents($photoshoot_thisCat->parent, TRUE, ' ' . $photoshoot_delimiter . ' ');
      echo $photoshoot_before . __('Archive by category','photoshoot') .' "'. single_cat_title('', false) . '"' . $photoshoot_after;

    } elseif ( is_search() ) {
      echo $photoshoot_before . __('Search results for','photoshoot') .' "' . get_search_query() . '"' . $photoshoot_after;

    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $photoshoot_delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $photoshoot_delimiter . ' ';
      echo $photoshoot_before . get_the_time('d') . $photoshoot_after;

    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $photoshoot_delimiter . ' ';
      echo $photoshoot_before . get_the_time('F') . $photoshoot_after;

    } elseif ( is_year() ) {
      echo $photoshoot_before . get_the_time('Y') . $photoshoot_after;

    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $photoshoot_post_type = get_post_type_object(get_post_type());
        $photoshoot_slug = $photoshoot_post_type->rewrite;
        echo '<a href="' . $photoshoot_homelink . '/' . $photoshoot_slug['slug'] . '/">' . $photoshoot_post_type->labels->singular_name . '</a>';
        if ($photoshoot_showcurrent == 1) echo ' ' . $photoshoot_delimiter . ' ' . $photoshoot_before . get_the_title() . $photoshoot_after;
      } else {
        $photoshoot_cat = get_the_category(); $photoshoot_cat = $photoshoot_cat[0];
        $photoshoot_cats = get_category_parents($photoshoot_cat, TRUE, ' ' . $photoshoot_delimiter . ' ');
        if ($photoshoot_showcurrent == 0) $photoshoot_cats = 
        preg_replace("#^(.+)\s$photoshoot_delimiter\s$#", "$1",$photoshoot_cats);
        echo $photoshoot_cats;
        if ($photoshoot_showcurrent == 1) echo $photoshoot_before . get_the_title() . $photoshoot_after;
      }

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $photoshoot_post_type = get_post_type_object(get_post_type());
      echo $photoshoot_before . $photoshoot_post_type->labels->singular_name . $photoshoot_after;

    } elseif ( is_attachment() ) {
      $photoshoot_parent = get_post($post->post_parent);
      $photoshoot_cat = get_the_category($photoshoot_parent->ID); $photoshoot_cat = $photoshoot_cat[0];
      echo get_category_parents($photoshoot_cat, TRUE, ' ' . $photoshoot_delimiter . ' ');
      echo '<a href="' . get_permalink($photoshoot_parent) . '">' . $photoshoot_parent->post_title . '</a>';
      if ($photoshoot_showcurrent == 1) echo ' ' . $photoshoot_delimiter . ' ' . $photoshoot_before . get_the_title() . $photoshoot_after;

    } elseif ( is_page() && !$post->post_parent ) {
      if ($photoshoot_showcurrent == 1) echo $photoshoot_before . get_the_title() . $photoshoot_after;

    } elseif ( is_page() && $post->post_parent ) {
      $photoshoot_parent_id  = $post->post_parent;
      $photoshoot_breadcrumbs = array();
      while ($photoshoot_parent_id) {
        $photoshoot_page = get_page($photoshoot_parent_id);
        $photoshoot_breadcrumbs[] = '<a href="' . get_permalink($photoshoot_page->ID) . '">' . get_the_title($photoshoot_page->ID) . '</a>';
        $photoshoot_parent_id  = $photoshoot_page->post_parent;
      }
      $photoshoot_breadcrumbs = array_reverse($photoshoot_breadcrumbs);
      for ($photoshoot_i = 0; $photoshoot_i < count($photoshoot_breadcrumbs); $photoshoot_i++) {
        echo $photoshoot_breadcrumbs[$photoshoot_i];
        if ($photoshoot_i != count($photoshoot_breadcrumbs)-1) echo ' ' . $photoshoot_delimiter . ' ';
      }
      if ($photoshoot_showcurrent == 1) echo ' ' . $photoshoot_delimiter . ' ' . $photoshoot_before . get_the_title() . $photoshoot_after;

    } elseif ( is_tag() ) {
      echo $photoshoot_before . __('Posts tagged','photoshoot').' "' . single_tag_title('', false) . '"' . $photoshoot_after;

    } elseif ( is_author() ) {
       global $author;
      $photoshoot_userdata = get_userdata($author);
         echo $photoshoot_before . __('Articles posted by','photoshoot').' : ' . $photoshoot_userdata->display_name . $photoshoot_after;

    } elseif ( is_404() ) {
      echo $photoshoot_before . __('Error 404','photoshoot'). $photoshoot_after;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page','photoshoot') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }

    echo '</li></ul></div></div>';

  }
} // end photoshoot_custom_breadcrumbs() ?>