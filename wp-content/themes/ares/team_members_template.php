<?php 
/**
 * The Template for displaying single team members.
 *
 * @package Ares
 */

namespace ots_pro;

$ares_options = ares_get_options();

?>

<?php get_header(); ?>

<div id="primary" class="content-area"> 

    <main id="main" class="site-main">

        <div id="single-post-container" class="container">

            <?php while ( have_posts() ) : the_post(); ?>

                <div class="page-content row">
                    
                    <div class="col-md-<?php echo is_active_sidebar(1) && $ares_options['ares_single_layout'] == 'col2r' ? '8' : '12'; ?>">
                        
                        <article>
                            
                            <div class="sc_team_single_member row">

                                <div class="sc_single_side" itemscope itemtype="http://schema.org/Person">

                                    <div class="inner">

                                        <?php \ots\member_avatar(); ?>

                                    </div>

                                </div>

                                <div class="sc_single_main sc-skills">

                                    <h2 class="name" itemprop="name"><?php echo the_title(); ?></h2>
                                    <h3 class="title" itemprop="jobtitle"><?php echo get_post_meta( get_the_ID(), 'team_member_title', true ); ?></h3>
                                    
                                    <?php if ( get_option( \ots\Options::SHOW_SINGLE_SOCIAL ) == 'on' ) : ?>

                                        <div class="social">

                                            <?php \ots\do_member_social_links(); ?>

                                        </div>

                                    <?php endif; ?>
                                    
                                    <div class="content-wrap">
                                    
                                        <?php $quote = get_post_meta( get_the_ID(), 'team_member_quote', true ); ?>

                                        <?php if ( !empty( $quote ) ) : ?>

                                            <div class="sc_personal_quote">
                                                <span class="sc_team_icon-quote-left"></span>
                                                <span class="sc_personal_quote_content"><?php echo esc_html( $quote ); ?></span>
                                            </div>

                                        <?php endif; ?>

                                        <?php the_content(); ?>
                                    
                                    </div>
                                   
                                    <div class="articles-wrap">
                                    
                                        <?php if( get_post_meta( get_the_ID(), 'team_member_article_bool', true ) == 'on' ) : ?>

                                            <div class="articles">

                                                <h2><?php echo esc_html( get_post_meta( get_the_ID(), 'team_member_article_title', true ) ); ?></h2>

                                                <div class="sc_member_articles">

                                                    <?php foreach( \ots\get_member_articles() as $article ) : ?>

                                                        <div class="article">

                                                            <a href="<?php the_permalink( $article ); ?>">
                                                                <?php echo get_the_title( $article ); ?>
                                                            </a>
                                                            
                                                            <span class="post-date">
                                                                <?php echo get_the_date( null, $article ); ?>
                                                            </span>

                                                        </div>

                                                    <?php endforeach; ?>

                                                    <div class="clear"></div>

                                                </div>

                                            </div>

                                        <?php endif; ?>

                                    </div>
                                        
                                </div>

                                <div class="sc_team_single_skills">

                                    <?php if ( get_post_meta( get_the_ID(), 'team_member_skill_bool', true ) == 'on' ) : ?>

                                        <div class="inner">

                                            <h2 class="skills-title"><?php echo esc_html( get_post_meta( get_the_ID(), 'team_member_skill_title', true ) ); ?></h2>

                                            <?php do_member_skills(); ?>

                                        </div>

                                    <?php endif; ?>

                                    <?php if ( get_post_meta( get_the_ID(), 'team_member_tags_bool', true ) == 'on' ) : ?>

                                        <div class="inner">

                                            <div class="sc-tags">

                                                <h2 class="skills-title"><?php echo esc_html( get_post_meta( get_the_ID(), 'team_member_tags_title', true ) ); ?></h2>

                                                <?php $tags = explode( ',', get_post_meta( get_the_ID(), 'team_member_tags', true ) ); ?>

                                                <?php foreach( $tags as $tag ) : ?>

                                                    <span class="sc-single-tag"><?php echo esc_html( $tag ); ?></span>

                                                <?php endforeach; ?>

                                            </div>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </article>
                        
                    </div>
                    
                    <?php if ( is_active_sidebar(1) && $ares_options['ares_single_layout'] == 'col2r' ) : ?>

                        <div class="col-md-4 avenue-sidebar">
                            <?php get_sidebar(); ?>
                        </div>

                    <?php endif; ?>
                    
                </div>
                        
            <?php endwhile; ?>

        </div>
        
    </main>
    
</div>

<?php get_footer();
