<?php 
/**
 * @package WordPress
 * @subpackage Azkaban
 */

global $azkaban_options;
?>

<?php
    if( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Contact Sidebar') ) : ?>
    <div class="az-sidebarsection">
        <h3><?php esc_html_e('About This Sidebar', 'azkaban'); ?></h3>
        <p><?php _e("To edit this sidebar, go to admin backend's <strong><em>Appearance -> Widgets</em></strong> and place widgets into the <strong><em>ContactSidebar</em></strong> Widget Area", 'azkaban'); ?></p>
    </div>
<?php endif; ?>
