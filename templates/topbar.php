<?php global $azkaban_options; ?>

<span class="hide-on-tablet"><span class="hide-on-mobile">
<?php if( $azkaban_options['show_topbar'] ) { ?>
<div id="az-topbarwrap">
<div class="grid-container">

	<div class="grid-50" id="az-topbarleft">

		<?php if( $azkaban_options['topbar_left_content'] == 1 ) { ?>

            <p><i class="fa fa-phone"></i> <?php echo $azkaban_options['topbar_phone'] .' | <i class="fa fa-envelope"></i>
 '. $azkaban_options['topbar_email']; ?></p>

		<?php } elseif( $azkaban_options['topbar_left_content'] == 2 ) { ?>

            <?php wp_nav_menu( array( 'theme_location' => 'top-navleft', 'depth' => 3, 'container_class' => 'az-topmenu', 'fallback_cb' => 'nk_topnav_fallback' ) ); ?>

		<?php } elseif( $azkaban_options['topbar_left_content'] == 3 ) { ?>

        <div class="az-topbarsocialsleft">
        <?php
		if( !$azkaban_options['hide_rss_icon'] ) {
            if( !empty($azkaban_options['rss_url']) && !$azkaban_options['rss_url'] != '' && $azkaban_options['rss_url'] != ' ' ) $rss = $azkaban_options['rss_url'];
            else $rss = get_bloginfo('rss2_url');
			?><a title="RSS" href="<?php echo $rss ; ?>" target="_blank"><i class="fa fa-rss"></i></a><?php 
		}
		// Facebook
		if ( !empty($azkaban_options['facebook_url']) && $azkaban_options['facebook_url'] != ' ' ) {
			?><a title="Facebook" href="<?php echo $azkaban_options['google_url']; ?>" target="_blank"><i class="fa fa-facebook"></i></a><?php 
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
        </div>
        
        <?php } ?>
        
	</div> <!-- End of az-topbarleft -->

	<div class="grid-50" id="az-topbarright">

		<?php if( $azkaban_options['topbar_right_content'] == 1 ) { ?>

            <p><?php echo $azkaban_options['topbar_phone'] . ' | ' . $azkaban_options['topbar_email']; ?></p>

		<?php } elseif( $azkaban_options['topbar_right_content'] == 2 ) { ?>

            <?php wp_nav_menu( array( 'theme_location' => 'top-navright', 'depth' => 3, 'container_class' => 'az-topmenur', 'fallback_cb' => 'nk_topnav_fallback' ) ); ?>

		<?php } elseif( $azkaban_options['topbar_right_content'] == 3 ) { ?>

        <div class="az-topbarsocialslright">
        <?php
		if( !$azkaban_options['hide_rss_icon'] ) {
            if( !empty($azkaban_options['rss_url']) && !$azkaban_options['rss_url'] != '' && $azkaban_options['rss_url'] != ' ' ) $rss = $azkaban_options['rss_url'];
            else $rss = get_bloginfo('rss2_url');
			?><a title="RSS" href="<?php echo $rss ; ?>" target="_blank"><i class="fa fa-rss"></i></a><?php 
		}
		// Facebook
		if ( !empty($azkaban_options['facebook_url']) && $azkaban_options['facebook_url'] != ' ' ) {
			?><a title="Facebook" href="<?php echo $azkaban_options['google_url']; ?>" target="_blank"><i class="fa fa-facebook"></i></a><?php 
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
        </div>
        
        <?php } ?>
        
	</div> <!-- End of az-topbarright -->

</div> <!-- End of grid-container -->
<div class="az-topbarafter"></div>
</div> <!-- End of az-topbarwrap -->
<?php } ?>
</span></span>
