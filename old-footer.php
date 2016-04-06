<?php 
/**
 * @package WordPress
 * @subpackage azkaban
 */

global $azkaban_options;
?>
</div> <!-- end of az-container -->
</div> <!-- End of grid-container -->
</div> <!-- End of az-containerwrap -->


<div id="az-backtotop"> </div>


<div id="az-footerwidgetwrap">
<div class="grid-container">
<div class="grid-100 tablet-grid-100 mobile-grid-100 grid-parent" id="az-footerwidgets">
<?php if( $azkaban_options['footer_widgets_layout'] == 1 ) { ?>
    <div class="grid-35 tablet-grid-50 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 1') ) :?><?php endif; ?>
    </div>    
    <div class="grid-15 tablet-grid-50 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 2') ) :?><?php endif; ?>
    </div>    
    <div class="grid-20 tablet-grid-50 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 3') ) :?><?php endif; ?>
    </div>    
    <div class="grid-30 tablet-grid-50 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 4') ) :?><?php endif; ?>
    </div>
<?php } elseif ( $azkaban_options['footer_widgets_layout'] == 2 ) { ?>
    <div class="grid-25 tablet-grid-50 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 1') ) :?><?php endif; ?>
    </div>    
    <div class="grid-25 tablet-grid-50 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 2') ) :?><?php endif; ?>
    </div>    
    <div class="grid-25 tablet-grid-50 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 3') ) :?><?php endif; ?>
    </div>    
    <div class="grid-25 tablet-grid-50 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 4') ) :?><?php endif; ?>
    </div>
<?php } else { ?>
    <div class="grid-33 tablet-grid-50 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 1') ) :?><?php endif; ?>
    </div>    
    <div class="grid-33 tablet-grid-50 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 2') ) :?><?php endif; ?>
    </div>    
    <div class="grid-33 tablet-grid-100 mobile-grid-100">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Column 3') ) :?><?php endif; ?>
    </div>    
<?php } ?>
</div> <!-- End of az-footerwidgets -->
</div> <!-- End of grid-container -->
</div> <!-- end of az-footerwidgetwrap -->


<div id="az-footerwrap">
<div class="grid-container">
<div class="grid-100 grid-parent" id="az-footer">
    <div class="grid-70 tablet-grid-100 mobile-grid-100 grid-parent" id="az-footerCopyright">
	<p>
	<?php if( $azkaban_options['footer_text'] == "" ) { ?>
		Copyright &copy; 2015-2016 <a href="<?php home_url(); ?>"><?php bloginfo('name'); ?></a> - All Rights Reserved
	<?php } else { ?>
		<?php echo $azkaban_options['footer_text']; ?>
	<?php }
		
		if( $azkaban_options['show_wp_link_in_footer'] ) :
			_e(' is proudly powered by <a href="http://wordpress.org/">WordPress</a>', 'azkaban');
		endif;
		if( $azkaban_options['show_entries_rss_in_footer'] ) : ?>
			| <a href="<?php bloginfo('rss2_url'); ?>"><?php esc_html_e('Entries (RSS)', 'azkaban'); ?></a>
	<?php
		endif;
		if( $azkaban_options['show_comments_rss_in_footer'] ) : ?>
			| <a href="<?php bloginfo('comments_rss2_url'); ?>"><?php esc_html_e('Comments (RSS)', 'azkaban'); ?></a>
	<?php
		endif;
	?>
	</p>

	<?php wp_footer(); ?>

    <?php
    if( $azkaban_options['footer_embed_codes'] != "" ) {
	   echo $azkaban_options['footer_embed_codes'];
    }
    ?>
    </div>
    <div class="grid-30 tablet-grid-100 mobile-grid-100" id="az-footersocials">
    <?php if( $azkaban_options['show_footer_social_icons'] ) { ?>
        <?php
		if( !$azkaban_options['hide_rss_icon'] ) {
            if( !empty($azkaban_options['rss_url']) OR !$azkaban_options['rss_url'] == '' OR $azkaban_options['rss_url'] == ' ' )
                $rss = $azkaban_options['rss_url'];
            else $rss = get_bloginfo('rss2_url');
			?><a title="RSS" href="<?php echo $rss ; ?>" target="_blank"><i class="fa fa-rss"></i></a><?php 
		}
		// Facebook
		if ( !empty($azkaban_options['facebook_url']) && $azkaban_options['facebook_url'] != ' ' ) {
			?><a title="Facebook" href="<?php echo $azkaban_options['facebook_url']; ?>" target="_blank"><i class="fa fa-facebook"></i></a><?php 
		}
		// Twitter
		if ( !empty($azkaban_options['twitter_url']) && $azkaban_options['twitter_url'] != ' ') {
			?><a title="Twitter" href="<?php echo $azkaban_options['twitter_url']; ?>" target="_blank"><i class="fa fa-twitter"></i></a><?php
		}		
		// Google+
		if( !empty($azkaban_options['google_url']) && $azkaban_options['google_url'] != ' ' ) {
			?><a title="Google+" href="<?php echo $azkaban_options['google_url']; ?>" target="_blank"><i class="fa fa-google-plus"></i></a><?php 
		}
		// LinkedIN
		if ( !empty($azkaban_options['linkedin_url']) && $azkaban_options['linkedin_url'] != ' ' ) {
			?><a title="LinkedIn" href="<?php echo $azkaban_options['linkedin_url']; ?>" target="_blank"><i class="fa fa-linkedin"></i></a><?php
		}
		// Pinterest
		if ( !empty($azkaban_options['pinterest_url']) && $azkaban_options['pinterest_url'] != ' ') {
			?><a title="Pinterest" href="<?php echo $azkaban_options['pinterest_url']; ?>" target="_blank"><i class="fa fa-pinterest"></i></a><?php
		}
		// YouTube
		if ( !empty($azkaban_options['youtube_url']) && $azkaban_options['youtube_url'] != ' ' ) {
			?><a title="Youtube" href="<?php echo $azkaban_options['youtube_url']; ?>" target="_blank"><i class="fa fa-youtube"></i></a><?php
		}
        ?>
    </div> <!-- end of az-footersocials --> 
    <?php } ?>    
</div> <!-- end of az-footer --> 
</div> <!-- End of grid-container -->
</div> <!-- end of az-footerwrap -->

</body>
</html>