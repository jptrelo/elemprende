<?php
/**
 * Template Name:Page with Right Sidebar
 */

get_header(); ?>

<div class="container">
    <div class="middle-align">       
		<div class="col-md-9" id="content-vw" >
			<?php while ( have_posts() ) : the_post(); ?>			
                <?php the_content();?>
                <?php
                //If comments are open or we have at least one comment, load up the comment template
                	if ( comments_open() || '0' != get_comments_number() )
                    	comments_template();
                ?>
            <?php endwhile; // end of the loop. ?>
        </div>
        <div class="col-md-3">
			<?php get_sidebar('page'); ?>
		</div>
        <div class="clear"></div>    
    </div>
</div>
<?php get_footer(); ?>