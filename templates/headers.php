<?php global $azkaban_options; ?>

<div id="az-headerwrap">
<div class="az-headerbefore"></div>
<div class="grid-container">
<div class="grid-100" id="az-header">
    
    <?php 
		//echo $azkaban_options['header_layout'];
		$custom_logo_type = $azkaban_options['custom_logo_type'];
		if($custom_logo_type=='logo_text')
		{
			$custom_logo_text = $azkaban_options['custom_logo_text'];
		}
		else
		{
			$custom_logo_text = '';
		}
		
		if($azkaban_options['header_layout'] == 1 ) {
		$custom_logo_type = $azkaban_options['custom_logo_type'];
		
	
	?>
	    <div class="grid-40 tablet-grid-100 mobile-grid-100" id="az-logo">
            <h1><a title="<?php echo $custom_logo_text; ?>" href="<?php echo home_url(); ?>"><?php echo $custom_logo_text; ?></a></h1>
		</div> <!-- End of az-logo --> 
        <span class="hide-on-tablet"><span class="hide-on-mobile">
		<?php if( $azkaban_options['show_main_menu'] ) { ?>
        <div class="grid-60 grid-parent" id="az-navigationright">
            <?php wp_nav_menu( array( 'theme_location' => 'site-nav' , 'container_class' => 'az-sitenavr', 'fallback_cb' => 'ax_nav_fallbackr' ) ); ?>
        </div> <!-- End of az-navigation -->
		<?php } ?>
        </span></span>
    <?php 
	} elseif($azkaban_options['header_layout'] == 2 ) {		

	?>
	    <div class="grid-40 tablet-grid-100 mobile-grid-100" id="az-logo">
            <h1><a title="<?php echo $custom_logo_text; ?>" href="<?php echo home_url(); ?>"><?php echo $custom_logo_text; ?></a></h1>
		</div> <!-- End of az-logo -->
	    <div class="grid-60 tablet-grid-100 mobile-grid-100" id="az-headertagline">
            <?php echo $azkaban_options['header_tagline']; ?>
		</div> <!-- End of az-logo -->
    <?php } elseif($azkaban_options['header_layout'] == 3 ) { ?>
	    <div class="grid-40 tablet-grid-100 mobile-grid-100" id="az-logo">
            <h1><a title="<?php echo $custom_logo_text; ?>" href="<?php echo home_url(); ?>"><?php echo $custom_logo_text; ?></a></h1>
		</div> <!-- End of az-logo -->
		<span class="hide-on-tablet"><span class="hide-on-mobile">
	    <div class="grid-60" id="az-headerads">
			<?php if( $azkaban_options['header_ad_code'] != "" ) { ?>
				<?php echo $azkaban_options['header_ad_code']; ?>
			<?php } ?>
		</div> <!-- End of az-headerads -->
		</span></span>
    <?php } elseif($azkaban_options['header_layout'] == 4 ) { ?>
	    <div class="grid-100" id="az-fulllogo">
            <h1><a title="<?php echo $custom_logo_text; ?>" href="<?php echo home_url(); ?>"><?php echo $custom_logo_text; ?></a></h1>
		</div> <!-- End of az-logo -->
    <?php } ?>    

</div> <!-- End of az-header -->
</div> <!-- End of grid-container -->
</div> <!-- End of az-headerwrap -->

<?php if($azkaban_options['header_layout'] != 1 ) { ?>
<span class="hide-on-tablet"><span class="hide-on-mobile">
<div id="az-navigationwrap">
<div class="az-navigationbefore"></div>
<div class="grid-container">
<?php if( $azkaban_options['show_main_menu'] ) { ?>
<div class="grid-100 grid-parent" id="az-navigation">
    <?php wp_nav_menu( array( 'theme_location' => 'site-nav', 'depth' => 3, 'container_class' => 'az-sitenav', 'fallback_cb' => 'ax_nav_fallback' ) ); ?>
</div> <!-- End of az-navigation -->
<?php } ?>
</div> <!-- End of grid-container -->
<div class="az-navigationafter"></div>
</div> <!-- End of az-navigationwrap -->
</span></span>
<?php } ?>    

<span class="hide-on-desktop">
<div id="az-navigationwrap">
<div class="az-navigationbefore"></div>
<div class="grid-container">
<div class="tablet-grid-100 mobile-grid-100 grid-parent" id="az-navigation">
    <nav class="clearfix">  
        <?php wp_nav_menu( array( 'theme_location' => 'mobile-nav' , 'container_class' => 'clearfix' ) ); ?>
        <a href="#" id="pull">&nbsp;</a>  
    </nav>  
</div> <!-- End of az-navigation -->
</div> <!-- End of grid-container -->
<div class="az-navigationafter"></div>
</div> <!-- End of az-navigationwrap -->
</span>
