<?php get_header(); ?>
<div class="page-title">
  <div class="container">
    <div class="row">
      <div class="col-md-6  col-sm-6 ">
        <h1><?php _e('404 Page','booster'); ?></h1>
      </div>
      <div class="col-md-6  col-sm-6 ">
        <ol class="archive-breadcrumb  pull-right">
          <?php if (function_exists('booster_custom_breadcrumbs')) booster_custom_breadcrumbs(); ?>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="main-container">
  <div class="container"> 
    <div class="row">
      <div class="col-md-12 main no-padding">
        <div class="jumbotron">			
				<h1><?php _e('Epic 404 - Article Not Found','booster') ?></h1>
				<p><?php _e('This is embarrassing. We could not find what you were looking for.','booster'); ?></p>
                <section class="post_content">
                    <p><?php _e('Whatever you were looking for was not found, but maybe try looking again or search using the form below.','booster'); ?></p>
                  <div class="row">
                      <div class="col-sm-12">
                          <form role="search" method="get" class="search-form" action="<?php echo site_url('/'); ?>">
                          <label>
                             <input type="search" class="search-field"   placeholder="<?php _e('Search','booster'); ?>" value="" name="s">
                          </label>
                          <input type="submit" class="search-submit" value="<?php _e('Search','booster'); ?>">
                          </form>								
                      </div>
              	</div>
				</section>
			</div>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>