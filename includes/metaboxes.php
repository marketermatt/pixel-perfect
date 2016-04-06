<?php
class azkabanThemeMetaboxes {

	public function __construct()
	{
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_meta_boxes'));
		add_action('admin_enqueue_scripts', array($this, 'admin_script_loader'));
	}

	// Load backend scripts
	function admin_script_loader() {
		global $pagenow;
		if (is_admin() && ($pagenow=='post-new.php' || $pagenow=='post.php')) {
	    	wp_register_script('azkaban_upload', get_template_directory() . '/assets/javascripts/upload.js');
	    	wp_enqueue_script('azkaban_upload');
	    	wp_enqueue_script('media-upload');
	    	wp_enqueue_script('thickbox');
	   		wp_enqueue_style('thickbox');
		}
	}

	public function add_meta_boxes()
	{
		$post_types = get_post_types( array( 'public' => true ) );

		$disallowed = array( 'page', 'post', 'attachment', 'azkaban_portfolio', 'product', 'wpsc-product', 'slide' );

		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type, $disallowed ) )
				continue;

			$this->add_meta_box('post_options', 'azkaban Options', $post_type);
		}

		$this->add_meta_box('post_options', 'Post Options', 'post');

		$this->add_meta_box('page_options', 'Page Options', 'page');

		$this->add_meta_box('portfolio_options', 'Portfolio Options', 'azkaban_portfolio');

		//$this->add_meta_box('woocommerce_options', 'Product Options', 'product');

		$this->add_meta_box('slide_options', 'Slide Options', 'slide');
	}

	public function add_meta_box($id, $label, $post_type)
	{
	    add_meta_box(
	        'nand_' . $id,
	        $label,
	        array($this, $id),
	        $post_type
	    );
	}

	public function save_meta_boxes($post_id)
	{
		if(defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		foreach($_POST as $key => $value) {
			if(strstr($key, 'nand_')) {
				update_post_meta($post_id, $key, $value);
			}
		}
	}

	public function post_options()
	{
		include 'views/metaboxes/style.php';
		include 'views/metaboxes/post_options.php';
	}

	public function page_options()
	{
		include 'views/metaboxes/style.php';
		include 'views/metaboxes/page_options.php';
	}

	public function portfolio_options()
	{
		include 'views/metaboxes/style.php';
		include 'views/metaboxes/portfolio_options.php';
	}

	public function es_options()
	{
		include 'views/metaboxes/style.php';
		include 'views/metaboxes/es_options.php';
	}

	public function woocommerce_options()
	{
		include 'views/metaboxes/style.php';
		//include 'views/metaboxes/woocommerce_options.php';
	}

	public function slide_options()
	{
		include 'views/metaboxes/style.php';
		include 'views/metaboxes/slide_options.php';
	}

	public function text($id, $label, $desc = '')
	{
		global $post;

		$html = '';
		$html .= '<div class="nand_metabox_field">';
			$html .= '<label for="nand_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<input type="text" id="nand_' . $id . '" name="nand_' . $id . '" value="' . get_post_meta($post->ID, 'nand_' . $id, true) . '" />';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function select($id, $label, $options, $desc = '')
	{
		global $post;

		$html = '';
		$html .= '<div class="nand_metabox_field">';
			$html .= '<label for="nand_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<select id="nand_' . $id . '" name="nand_' . $id . '">';
				foreach($options as $key => $option) {
					if(get_post_meta($post->ID, 'nand_' . $id, true) == $key) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}

					$html .= '<option ' . $selected . 'value="' . $key . '">' . $option . '</option>';
				}
				$html .= '</select>';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function multiple($id, $label, $options, $desc = '')
	{
		global $post;

		$html = '';
		$html .= '<div class="nand_metabox_field">';
			$html .= '<label for="nand_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<select multiple="multiple" id="nand_' . $id . '" name="nand_' . $id . '[]">';
				foreach($options as $key => $option) {
					if(is_array(get_post_meta($post->ID, 'nand_' . $id, true)) && in_array($key, get_post_meta($post->ID, 'nand_' . $id, true))) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}

					$html .= '<option ' . $selected . 'value="' . $key . '">' . $option . '</option>';
				}
				$html .= '</select>';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function textarea($id, $label, $desc = '', $default = '' )
	{
		global $post;

		$db_value = get_post_meta($post->ID, 'nand_' . $id, true);

		if( $db_value ) {
			$value = $db_value;
		} else {
			$value = $default;
		}

		$html = '';
		$html = '';
		$html .= '<div class="nand_metabox_field">';
			$html .= '<label for="nand_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<textarea cols="120" rows="10" id="nand_' . $id . '" name="nand_' . $id . '">' . $value . '</textarea>';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function upload($id, $label, $desc = '')
	{
		global $post;

		$html = '';
		$html = '';
		$html .= '<div class="nand_metabox_field">';
			$html .= '<label for="nand_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
			    $html .= '<input name="nand_' . $id . '" class="upload_field" id="nand_' . $id . '" type="text" value="' . get_post_meta($post->ID, 'nand_' . $id, true) . '" />';
			    $html .= '<input class="nk_upload_button" type="button" value="Browse" />';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

}

$metaboxes = new azkabanThemeMetaboxes;