<?php 
/**
 * @package WordPress
 * @subpackage azkaban
 */
?>
<?php global $azkaban_options; ?>
<div class="footer">
<p class="left">
<?php if( $azkaban_options['footer_text'] == "" ) { ?>
	&copy; Copyright <?php echo date('Y');?> Bak-One  |  One Page Flat Template
	<?php } else { ?>
		<?php echo $azkaban_options['footer_text']; ?>
	<?php } ?>
</p>

</div><!--footer-->
</div><!--inner-wrap-->
</div><!--wrapper-->
<div id="az-backtotop"> </div>
</body>
</html>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var infowindow = new google.maps.InfoWindow();
var pinkmarker = new google.maps.MarkerImage('<?php echo get_stylesheet_directory_uri();?>/assets/map/pink_Marker.png', new google.maps.Size(20, 34) );
var shadow = new google.maps.MarkerImage('<?php echo get_stylesheet_directory_uri();?>/assets/map/shadow.png', new google.maps.Size(37, 34) );

function initialize() {
	map = new google.maps.Map(document.getElementById('map'), { 
		zoom: 12, 
		center: new google.maps.LatLng(<?php echo $azkaban_options['contact_lattitude'];?>, <?php echo $azkaban_options['contact_longitude'];?>), 
		mapTypeId: google.maps.MapTypeId.ROADMAP 
	});

	for (var i = 0; i < locations.length; i++) {  
		var marker = new google.maps.Marker({
	    	position: locations[i].latlng,
			icon: pinkmarker,
			shadow: shadow,
			map: map
		});
		google.maps.event.addListener(marker, 'click', (function(marker, i) {
		  return function() {
		    infowindow.setContent(locations[i].info);
		    infowindow.open(map, marker);
		  }
		})(marker, i));
	}

}
</script>
<?php wp_footer();?>