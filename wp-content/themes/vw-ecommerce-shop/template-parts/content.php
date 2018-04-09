<?php
/**
 * The template part for displaying slider
 *
 * @package VW Ecommerce Shop 
 * @subpackage vw_ecommerce_shop
 * @since VW Ecommerce Shop 1.0
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>
  <div class="post-main-box">
    <div class="box-image">
        <?php 
          if(has_post_thumbnail()) { 
            the_post_thumbnail(); 
          }
        ?>  
      </div>
    <div class="col-md-2 col-sm-2 wow bounceInUp">
      <div class="datebox">
          <div class="date-monthwrap">
             <span class="date-month"><?php echo esc_html( get_the_date( 'M' ) ); ?></span>
             <span class="date-day"><?php echo esc_html( get_the_date( 'd') ); ?></span>
          </div>
          <div class="yearwrap">
              <span class="date-year"><?php echo esc_html( get_the_date( 'Y' ) ); ?></span>
          </div>
        </div>
    </div>
    <div class="col-md-10 col-sm-10">
      <h3 class="section-title"><a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute(); ?>"><?php the_title();?></a></h3>      
      <div class="new-text">
        <?php the_excerpt();?>
      </div>
       <div class="content-bttn">
        <a href="<?php echo esc_url( get_permalink() );?>" class="blogbutton-small hvr-sweep-to-right" title="<?php esc_attr_e( 'Read More', 'vw-ecommerce-shop' ); ?>"><?php esc_html_e('Read More','vw-ecommerce-shop'); ?></a>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
</div>