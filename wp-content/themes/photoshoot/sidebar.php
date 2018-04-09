<?php
/*
 * Main Sidebar File.
 */
?>
<div class="col-md-3 sidebar photoshoot-sidebar">
<div class="photoshoot-widget">
<?php if ( is_active_sidebar( 'sidebar-1' ) ) : dynamic_sidebar( 'sidebar-1' ); endif; ?>
</div>
</div>