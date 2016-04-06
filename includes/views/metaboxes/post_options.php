<div class='nand_metabox'>
<h2>Page Title Bar Options:</h2>
<?php
$this->select(	'page_title',
				'Page Title Bar',
				array('default' => 'Default', 'yes' => 'Show', 'no' => 'Hide'),
				''
			);
?>
<?php
$this->select(	'page_title_text',
				'Page Title Bar Text',
				array('yes' => 'Show', 'no' => 'Hide'),
				''
			);
?>
<?php
$this->text(	'page_title_custom_text',
				'Page Title Bar Custom Text',
				''
			);
?>
<?php
$this->text(	'page_title_custom_subheader',
				'Page Title Bar Custom Subheader Text',
				''
			);
?>
<?php
$this->text(	'page_title_height',
				'Page Title Bar Height (in pixels "px")',
				''
			);
?>
<?php $this->upload('page_title_bar_bg', 'Page Title Bar Background'); ?>
<?php
$this->text(	'page_title_bar_bg_color',
				'Page Title Bar Background Color (Hex Code)',
				''
			);
?>

<h2>Slider Options:</h2>
<?php
$this->select(	'slider_type',
				'Slider Type',
				array(  'no' => 'No Slider',
                        'layer' => 'LayerSlider',
                ),''
			);
?>
<?php
global $wpdb;
$slides_array[0] = 'Select a slider';
// Table name
$table_name = $wpdb->prefix . "layerslider";

// Get sliders
$sliders = $wpdb->get_results( "SELECT * FROM $table_name
									WHERE flag_hidden = '0' AND flag_deleted = '0'
									ORDER BY date_c ASC" );

if(!empty($sliders)):
foreach($sliders as $key => $item):
	$slides[$item->id] = '';
endforeach;
endif;

if(isset($slides) && $slides){
foreach($slides as $key => $val){
	$slides_array[$key] = 'LayerSlider #'.($key);
}
}
$this->select(	'slider',
				'Select LayerSlider',
				$slides_array,
				''
			);
?>

<h2>Sidebar Options:</h2>
<?php sidebar_generator::edit_form(); ?>
<?php
$this->select(	'sidebar_position',
				'Sidebar Position',
				array('default' => 'Default', 'right' => 'Right', 'left' => 'Left'),
				''
			);
?>
</div>