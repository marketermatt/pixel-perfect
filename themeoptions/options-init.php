<?php
/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('azkaban_options_redux_framework_config')) {

    class azkaban_options_redux_framework_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);
            
            // Change the arguments after they've been declared, but before the panel is created
            
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);

        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css, $changed_values) {
            //echo '<h1>The compiler hook has run!</h1>';
            //echo "<pre>";
            //print_r($changed_values); // Values that have changed since the last save
            //echo "</pre>";
            //print_r($options); //Option values
           // print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/compiled_style' . '.css';
             
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
            */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'azkaban_options'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'azkaban_options'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'azkaban_options'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','azkaban'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','azkaban'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'azkaban_options'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'azkaban_options'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'azkaban_options') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.','azkaban') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'azkaban_options'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'icon'      => 'el-icon-credit-card',
                'title'     => __('Homepage Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'home_sidebar',
                        'type'      => 'image_select',
                        'title'     => __('Homepage Sidebar', 'azkaban_options'),
                        'desc'  => __('Select full width, right sidebar or left sidebar layout.', 'azkaban_options'),
                        'options'   => array(
                            '1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                            '3' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png')
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'home_layout',
                        'type'      => 'image_select',
                        'title'     => __('Homepage Layout', 'azkaban_options'),
                        'desc'  => __('Select homepage content layout.', 'azkaban_options'),
                        'options'   => array(
                            '1' => array('alt' => 'Home Layout 1',       'img' => ReduxFramework::$_url . 'assets/img/1bl.png'),
                            '2' => array('alt' => 'Home Layout 1',  'img' => ReduxFramework::$_url . 'assets/img/2bl.png'),
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'layout2-categoriesleft',
                        'type'      => 'select',
                        'required'  => array('home_layout', '=', '2'),
                        'data'      => 'categories',
                        'title'     => __('Select Left Column Categories', 'azkaban_options'),
                        'desc'      => __('Select the categories for left column posts.', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'layout2-categoriesright',
                        'type'      => 'select',
                        'required'  => array('home_layout', '=', '2'),
                        'data'      => 'categories',
                        'title'     => __('Select Right Column Categories', 'azkaban_options'),
                        'desc'      => __('Select the categories for right column posts.', 'azkaban_options'),
                    ),
                    /*
                    array(
                        'id'        => 'blog-layout',
                        'type'      => 'select',
                        'title'     => __('Select Blog Layout', 'azkaban_options'),
                        'desc'      => __('Select site wide blog layout.', 'azkaban_options'),
                        'options'   => array(
                            '1' => 'Large Featured',
                            '2' => 'Medium Featured',
                            '3' => 'Grid',
                        ),
                        'default'   => '1'
                    ),
                    */
                ),
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-credit-card',
                'title'     => __('Topbar Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'show_topbar',
                        'type'      => 'switch',
                        'title'     => __('Show Topbar', 'azkaban_options'),
                        'desc'  => __('Enable or disable topbar.', 'azkaban_options'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'topbar_left_content',
                        'type'      => 'select',
                        'required'  => array('show_topbar', '=', '1'),
                        'title'     => __('Top Bar Left Content', 'azkaban_options'),
                        'subtitle'  => false,
                        'desc'      => __('Select which content displays in the top left area of the top bar.', 'azkaban_options'),
                        'options'  => array(
                            '1' => 'Contact Info',
                            '2' => 'Navigation',
                            '3' => 'Social Links',
                            '4' => 'Leave Empty'
                        ),
                        'default'  => '1',
                    ),
                    array(
                        'id'        => 'topbar_right_content',
                        'type'      => 'select',
                        'required'  => array('show_topbar', '=', '1'),
                        'title'     => __('Top Bar Right Content', 'azkaban_options'),
                        'subtitle'  => false,
                        'desc'      => __('Select which content displays in the top right area of the top bar.', 'azkaban_options'),
                        'options'  => array(
                            '1' => 'Contact Info',
                            '2' => 'Navigation',
                            '3' => 'Social Links',
                            '4' => 'Leave Empty'
                        ),
                        'default'  => '2',
                    ),
                    array(
                        'id'        => 'topbar_phone',
                        'type'      => 'text',
                        'required'  => array('show_topbar', '=', '1'),
                        'title'     => __('Topbar Phone Number', 'azkaban_options'),
                        'desc'      => __('Phone number will display in the Contact Info section of your topbar.', 'azkaban_options'),
                        'validate'  => 'no_html',
                        'default'   => 'Call Us Today! 1.234.567.890',
                    ),
                    array(
                        'id'        => 'topbar_email',
                        'type'      => 'text',
                        'required'  => array('show_topbar', '=', '1'),
                        'title'     => __('Topbar Email Address', 'azkaban_options'),
                        'desc'      => __('Email address will display in the Contact Info section of your topbar.', 'azkaban_options'),
                        'validate'  => 'no_html',
                        'default'   => 'info@yourdomain.com',
                    ),
                    array(
                        'id'        => 'topbar_background_styles',
                        'type'      => 'background',
                        'required'  => array('show_topbar', '=', '1'),
                        'output'  => array('#az-topbarwrap'),
                        'title'     => __('Top Bar Background', 'azkaban_options'),
                        'desc'  => __('Specify the top bar background properties including image, color, etc.', 'azkaban_options'),
                        //'default'   => '#FFFFFF',
                    ),
                ),
            );


            $this->sections[] = array(
                'icon'      => 'el-icon-cogs',
                'title'     => __('Header Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'       => 'header_layout',
                        'type'     => 'image_select',
                        'title'    => __('Select Header Layout', 'azkaban_options'),
                        'options'  => array(
                            '1'      => array(
                                'alt'   => '1 Column',
                                'img'   => ReduxFramework::$_url.'assets/img/header1.jpg'
                            ),
                            '2'      => array(
                                'alt'   => '2 Column Left',
                                'img'   => ReduxFramework::$_url.'assets/img/header2.jpg'
                            ),
                            '3'      => array(
                                'alt'   => '2 Column Left',
                                'img'   => ReduxFramework::$_url.'assets/img/header3.jpg'
                            ),
                            '4'      => array(
                                'alt'   => '2 Column Right',
                                'img'  => ReduxFramework::$_url.'assets/img/header4.jpg'
                            ),
                        ),
                        'default' => '1'
                    ),
                    array(
                        'id'        => 'custom_logo_img',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Upload Logo', 'azkaban_options'),
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('To upload or select an existing image click on "Upload" button.', 'azkaban_options'),
                        'default'   => array('url' => get_stylesheet_directory_uri() .'/images/azkaban-logo.jpg'),
                    ),
					array(
                        'id'        => 'custom_logo_text',
                        'type'      => 'text',
                        'required'  => array('custom_logo_type', '=', 'logo_text'),
                        'title'     => __('Add Logo Text', 'azkaban_options'),
                        'desc'      => __('Enter the text for Logo.', 'azkaban_options'),
                        'validate'  => 'no_html',
                        'default'   => 'Insert Logo custom text here',
                    ),
					array(
                        'id'        => 'custom_logo_type',
                        'type'      => 'radio',
                        'title'     => __('Select Logo type', 'azkaban_options'),
                       'options'   => array(
                            'logo_img'  => 'Logo type Image', 
                            'logo_text' => 'Logo type Text'
                        ),
                        'default'   => 'logo_img'
                    ),
					array(
                        'id'        => 'custom_favicon_img',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Upload Favicon', 'azkaban_options'),
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('To upload or select an existing image click on "Upload" button.', 'azkaban_options'),
                        'default'   => array('url' => get_stylesheet_directory_uri() .'/images/favicon.ico'),
                    ),
                    array(
                        'id'        => 'header_tagline',
                        'type'      => 'text',
                        'required'  => array('header_layout', '=', '2'),
                        'title'     => __('Header Tagline', 'azkaban_options'),
                        'desc'      => __('Enter the text for header tagline.', 'azkaban_options'),
                        'validate'  => 'no_html',
                        'default'   => 'Insert Any Headline Or Link You Want Here',
                    ),
                    array(
                        'id'        => 'header_ad_code',
                        'type'      => 'textarea',
                        'required'  => array('header_layout', '=', '3'),
                        'title'     => __('Banner Ad Code', 'azkaban_options'),
                        'desc'      => __('Enter text or banner ad code. Banner size 468X60.', 'azkaban_options'),
                        //'validate'  => 'html',
                        'default'   => ''
                    ),
                    
                    array(
                        'id'        => 'header_background_style',
                        'type'      => 'background',
                        'output'  => array('#az-headerwrap'),
                        'title'     => __('Header Background', 'azkaban_options'),
                        'desc'  => __('Specify the header background properties including image, color, etc.', 'azkaban_options'),
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'        => 'header_padding_top',
                        'type'      => 'text',
                        'title'     => __('Header Padding Top', 'azkaban_options'),
                        'desc'  => __('Specify the header top padding (without any unit, eg: 10 for 10px).', 'azkaban_options'),
                        'validate'  => 'numeric',
                        'default'   => '0',
                    ),
                    array(
                        'id'        => 'header_padding_bottom',
                        'type'      => 'text',
                        'title'     => __('Header Padding Bottom', 'azkaban_options'),
                        'desc'  => __('Specify the header bottom padding (without any unit, eg: 10 for 10px).', 'azkaban_options'),
                        'validate'  => 'numeric',
                        'default'   => '0',
                    ),
					array(
						'id'        => 'show_main_menu',
						'type'      => 'switch',
						'title'     => __('Show/Hide main navigation', 'azkaban_options'),
						'desc'  => __('Show or Hide Main Navigation', 'azkaban_options'),
						'default'   => 'on',
						'on'        => 'Enabled',
						'off'       => 'Disabled',
					),
                ),
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-cogs',
                'title'     => __('Page Title Bar Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'show_titlebar',
                        'type'      => 'switch',
                        'title'     => __('Show Page Title Bar', 'azkaban_options'),
                        'desc'  => __('This is a global option for every page or post, and this can be overridden by individual page/post options.', 'azkaban_options'),
                        'default'   => 1,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'site_title',
                        'type'      => 'text',
                        'required'  => array('show_titlebar', '=', '1'),
                        'title'     => __('Site Title', 'azkaban_options'),
                        'desc'  => __('This will display as the title on home page', 'azkaban_options'),
                        'default'   => 'Home'
                    ),
                    array(
                        'id'        => 'site_subtitle',
                        'type'      => 'text',
                        'required'  => array('show_titlebar', '=', '1'),
                        'title'     => __('Site Subtitle', 'azkaban_options'),
                        'desc'  => __('This will display as the subtitle on home page', 'azkaban_options'),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'page_titlebar_height',
                        'type'      => 'text',
                        'required'  => array('show_titlebar', '=', '1'),
                        'title'     => __('Page Title Bar Height', 'azkaban_options'),
                        'desc'  => __('In pixels, ex: 10px', 'azkaban_options'),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'page_titlebar_background_style',
                        'type'      => 'background',
                        'required'  => array('show_titlebar', '=', '1'),
                        'output'  => array('#az-pagetitlewrap'),
                        'title'     => __('Page Title Bar Background', 'azkaban_options'),
                        'desc'  => __('Specify the page title bar background properties including image, color, etc.', 'azkaban_options'),
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'        => 'page_titlebar_typography_styles',
                        'type'      => 'typography',
                        'output'  => array('.az-sectiontitle'),
                        'title'     => __('Page Title Bar Font', 'nandonik_options'),
                        'subtitle'  => __('Specify the font styles for page title bar.', 'nandonik_options'),
                        'google'    => true,
                        'font-backup'   => true,
                    ),
                    array(
                        'id'        => 'breadcrumb_divider',
                        'type'      => 'section',
                        'required'  => array('show_titlebar', '=', '1'),
                        'title'     => __('Breadcrumb Settings', 'azkaban_options'),
                        'indent'    => false,
                    ),
                    array(
                        'id'        => 'enable_breadcrumbs',
                        'type'      => 'switch',
                        'required'  => array('show_titlebar', '=', '1'),
                        'title'     => __('Display Breadcrumbs/Search Bar', 'azkaban_options'),
                        'default'   => true,
                        'desc'      => __('Show breadcrumbs or search bar on page title bar.', 'azkaban_options'),
                    ),
                    /*
                    array(
                        'id'        => 'breadcrumb_prefix',
                        'type'      => 'text',
                        'required'  => array('show_titlebar', '=', '1'),
                        'title'     => __('Breadcrumb Prefix', 'azkaban_options'),
                        'desc'  => __('This text will display before the breadcrumb menu.', 'azkaban_options'),
                        'default'   => ''
                    ),
                    */
                    array(
                        'id'        => 'breadcrumb_typography_styles',
                        'type'      => 'typography',
                        'output'  => array('#az-breadcrumb'),
                        'title'     => __('Breadcrumb Font', 'azkaban_options'),
                        'subtitle'  => __('Specify the font styles for breadcrumbs.', 'azkaban_options'),
                        'google'    => true,
                        'font-backup'   => true,
                    ),
                ),
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-slideshare',
                'title'     => __('Slider Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'show_slider',
                        'type'      => 'switch',
                        'title'     => __('Show Slider', 'azkaban_options'),
                        'subtitle'  => __('Enable or disable slider on front page', 'azkaban_options'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'slider_type',
                        'type'      => 'select',
                        'required'  => array('show_slider', '=', '1'),
                        'title'     => __('Slider Type', 'azkaban_options'),
                        //Must provide key => value pairs for select options
                        'options'   => array(
                            '1'  => 'Flex Slider', 
                            '2' => 'Layer Slider',
							'3' => 'Revolution Slider', 
                        ),
                        'default'   => '1'
                    ),
                    
                    array(
                        'id'        => 'homepage_slider',
                        'type'      => 'switch',
                        'required'  => array('show_slider', '=', '1'),
                        'title'     => __('Frontpage Only', 'azkaban_options'),
                        'subtitle'  => __('Enable to show slider on front page only', 'azkaban_options'),
                        'default'   => 1,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'slider_caption',
                        'type'      => 'switch',
                        'required'  => array('slider_type', '=', '1'),
                        'title'     => __('Show Slides Caption', 'azkaban_options'),
                        'default'   => 1,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    
                    array(
                        'id'        => 'flexi_animation',
                        'type'      => 'select',
                        'required'  => array('slider_type', '=', '1'),
                        'title'     => __('Animation Effect', 'azkaban_options'),
                        //Must provide key => value pairs for select options
                        'options'   => array(
                            'fade'  => 'Fade', 
                            'slideV' => 'Slide Vertical', 
                            'slideH' => 'Slide Horizontal'
                        ),
                        'default'   => 'fade'
                    ),
                    array(
                        'id'            => 'flexi_slideshow_speed',
                        'type'          => 'slider',
                        'required'  => array('slider_type', '=', '1'),
                        'title'         => __('Slideshow Speed', 'azkaban_options'),
                        'default'       => 3000,
                        'min'           => 100,
                        'step'          => 100,
                        'max'           => 40000,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'flexi_animation_speed',
                        'type'          => 'slider',
                        'required'  => array('slider_type', '=', '1'),
                        'title'         => __('Animation Speed', 'azkaban_options'),
                        'default'       => 3000,
                        'min'           => 100,
                        'step'          => 100,
                        'max'           => 40000,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'        => 'slider_number',
                        'type'      => 'text',
                        'required'  => array('slider_type', '=', '1'),
                        'title'     => __('Number Of Posts To Show', 'azkaban_options'),
                        'subtitle'  => false,
                        'desc'      => __('Enter the number of posts to be shown on slider.', 'azkaban_options'),
                        'validate'  => 'numeric',
                        'default'   => '5',
                    ),
                    array(
                        'id'        => 'slider_query_type',
                        'type'      => 'radio',
                        'required'  => array('slider_type', '=', '1'),
                        'title'     => __('Query Type', 'azkaban_options'),
                         //Must provide key => value pairs for radio options
                        'options'   => array(
                            'category' => 'Category', 
                            'selected_posts' => 'Selected Posts', 
                            'selected_pages' => 'Selected Pages', 
                            //'custom' => 'Custom Slides', 
                        ),
                        'default'   => 'category'
                    ),
                    array(
                        'id'        => 'slider_categories',
                        'type'      => 'select',
                        'required'  => array('slider_query_type', '=', 'category'),
                        'data'      => 'categories',
                        'multi'     => true,
                        'title'     => __('Category', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'slider_posts',
                        'type'      => 'text',
                        'required'  => array('slider_query_type', '=', 'selected_posts'),
                        'title'     => __('Selected Posts IDs', 'azkaban_options'),
                        'desc'      => __('Enter the post IDs separated by comma.', 'azkaban_options'),
                        'validate'  => 'numeric',
                    ),
                    array(
                        'id'        => 'slider_pages',
                        'type'      => 'text',
                        'required'  => array('slider_query_type', '=', 'selected_pages'),
                        'title'     => __('Selected Page IDs', 'azkaban_options'),
                        'desc'      => __('Enter the page IDs separated by comma.', 'azkaban_options'),
                        'validate'  => 'numeric',
                    ),
                    
                    array(
                        'id'        => 'layerslider_id',
                        'type'      => 'text',
                        'required'  => array('slider_type', '=', '2'),
                        'title'     => __('Layer Slider ID', 'azkaban_options'),
                        'subtitle'  => false,
                        'desc'      => __('Enter the Layer Slider ID you want to enable.', 'azkaban_options'),
                        'validate'  => 'numeric',
                        'default'   => '',
                    ),
					array(
                        'id'        => 'revolutionslider_id',
                        'type'      => 'text',
                        'required'  => array('slider_type', '=', '3'),
                        'title'     => __('Revolution alias name', 'azkaban_options'),
                        'subtitle'  => false,
                        'desc'      => __('Enter the Revolution alias name you want to enable.', 'azkaban_options'),
                        'default'   => '',
                    ),
                ),
            );


            $this->sections[] = array(
                'icon'      => 'el-icon-laptop',
                'title'     => __('Blog Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'single_layout',
                        'type'      => 'image_select',
                        'title'     => __('Single Post Layout', 'azkaban_options'),
                        'subtitle'  => __('Select single post content and sidebar alignment. Choose between full width, right sidebar or left sidebar layout.', 'azkaban_options'),
                        'options'   => array(
                            '1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                            '3' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png')
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'archive_layout',
                        'type'      => 'image_select',
                        'title'     => __('Archive Page Layout', 'azkaban_options'),
                        'subtitle'  => __('Select Archive (e.g. Category archives, Date archives, Tag archives, etc.), 404, Search pages content and sidebar alignment. Choose between full width, right sidebar or left sidebar layout.', 'azkaban_options'),
                        'options'   => array(
                            '1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                            '3' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png')
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'enable_featuredimage',
                        'type'      => 'switch',
                        'title'     => __('Featured Image', 'azkaban_options'),
                        'default'   => true,
                        'desc'      => __('Show or hide featured image on top of single posts.', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'enable_author',
                        'type'      => 'switch',
                        'title'     => __('Post Author', 'azkaban_options'),
                        'default'   => true,
                        'desc'      => __('If you enable this option, the Author Name will be added to the postmetadata box. The author\'s name will be displayed as specified under <strong>Users -> Your Profile Display</strong> name publicly as field and linking it to the author\'s page.', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'enable_postdate',
                        'type'      => 'switch',
                        'title'     => __('Post Date', 'azkaban_options'),
                        'default'   => true,
                        'desc'      => __('Show or hide date on post meta section.', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'enable_postcategory',
                        'type'      => 'switch',
                        'title'     => __('Post Category', 'azkaban_options'),
                        'default'   => true,
                        'desc'      => __('Show or hide post category on post meta section.', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'enable_tags',
                        'type'      => 'switch',
                        'title'     => __('Post Tags', 'azkaban_options'),
                        'default'   => true,
                        'desc'      => __('Show or hide post tags on single post pages.', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'enable_excerpt',
                        'type'      => 'switch',
                        'title'     => __('Excerpt', 'azkaban_options'),
                        'default'   => 1,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                        'desc'      => __('Show or hide excerpt on Archive (e.g. Category archives, Date archives, Tag archives, etc.), 404, Search pages.', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'readmore_text',
                        'type'      => 'text',
                        'required'  => array('enable_excerpt', '=', '1'),
                        'title'     => __('Read More Button', 'azkaban_options'),
                        'desc'      => __('Enter the text for Read More button link.', 'azkaban_options'),
                        'validate'  => 'no_html',
                        'default'   => 'Read More',
                        'desc'      => __('Example: Read More or More Details or More...', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'enable_prevnext',
                        'type'      => 'switch',
                        'title'     => __('Previous/Next Links', 'azkaban_options'),
                        'default'   => true,
                        'desc'      => __('Show or hide previous/next links on single posts.', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'enable_author_details',
                        'type'      => 'switch',
                        'title'     => __('Author Details', 'azkaban_options'),
                        'default'   => true,
                        'desc'      => __('Show or hide author details on single posts.', 'azkaban_options'),
                    ),
					array(
                        'id'        => 'select_blog_page',
                        'type'      => 'select',
						'data'	=> 'pages',
                        'title'     => __('Select Blog Page', 'azkaban_options'),
                        'desc'      => __('Select default blog page.', 'azkaban_options'),
                    ),
					array(
                        'id'        => 'select_blog_categories',
                        'type'      => 'select',
						'multi'    	=> true,
						'data'      => 'categories',
                        'title'     => __('Select Categories for Blog', 'azkaban_options'),
                        'desc'      => __('Select categories for blog which you want to display.', 'azkaban_options'),
                    ),
                ),
            );
            
            
            $this->sections[] = array(
                'icon'      => 'el-icon-laptop',
                'title'     => __('Page Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'page_layout',
                        'type'      => 'image_select',
                        'title'     => __('Page Layout', 'azkaban_options'),
                        'desc'  => __('Select page content and sidebar alignment. Choose between full width, right sidebar or left sidebar layout.', 'azkaban_options'),
                        'options'   => array(
                            '1' => array('alt' => '2 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                            '2' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png')
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'show_comments_on_pages',
                        'type'      => 'switch',
                        'title'     => __('Comments', 'azkaban_options'),
                        'default'   => false,
                        'desc'      => __('Show or hide comments on pages.', 'azkaban_options'),
                    ),
                ),
            );
            
            $this->sections[] = array(
                'icon'      => 'el-icon-laptop',
                'title'     => __('Contact Page Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'contactpage_layout',
                        'type'      => 'image_select',
                        'title'     => __('Contact Page Layout', 'azkaban_options'),
                        'subtitle'  => __('Select contact page content and sidebar alignment. Choose between full width, right sidebar or left sidebar layout.', 'azkaban_options'),
                        'options'   => array(
                            '1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                            '3' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png')
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'contact_email',
                        'type'      => 'text',
                        'title'     => __('Email Address', 'azkaban_options'),
                        'default'   => 'info@bak-onecompany.com',
                        'desc'      => __('Enter the email address the form will be sent to.', 'azkaban_options'),
                    ),
					array(
                        'id'        => 'contact_address',
                        'type'      => 'text',
                        'title'     => __('Address', 'azkaban_options'),
                        'default'   => '11209 Prestwick Dr, Lansing, MI 48917, USA',
                        'desc'      => __('Enter the address.', 'azkaban_options'),
                    ),
					array(
                        'id'        => 'contact_phone',
                        'type'      => 'text',
                        'title'     => __('Phone', 'azkaban_options'),
                        'default'   => '+444 (Phone) 123456',
                        'desc'      => __('Enter the phone number.', 'azkaban_options'),
                    ),
					array(
                        'id'        => 'contact_fax',
                        'type'      => 'text',
                        'title'     => __('FAX', 'azkaban_options'),
                        'default'   => '+123 (FAX) 0011223',
                        'desc'      => __('Enter the FAX number', 'azkaban_options'),
                    ),
					array(
                        'id'        => 'contact_lattitude',
                        'type'      => 'text',
                        'title'     => __('Lattitude', 'azkaban_options'),
                        'default'   => '42.7285345',
                        'desc'      => __('Enter the lattitude.', 'azkaban_options'),
                    ),
					array(
                        'id'        => 'contact_longitude',
                        'type'      => 'text',
                        'title'     => __('Longitude', 'azkaban_options'),
                        'default'   => '-84.686921',
                        'desc'      => __('Enter the longitude.', 'azkaban_options'),
                    ),

                ),
            );
            
            $this->sections[] = array(
                'icon'      => 'el-icon-idea',
                'title'     => __('Ad Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'enable_sidebar_top_ad',
                        'type'      => 'switch',
                        'title'     => __('Enable Sidebar Top Ad', 'azkaban_options'),
                        'subtitle'  => __('Enable or Disable advertisement on sidebar top.', 'azkaban_options'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'sidebar_top_ad_code',
                        'required'  => array('enable_sidebar_top_ad', '=', '1'),
                        'type'      => 'textarea',
                        'title'     => __('Sidebar Top Ad Code', 'azkaban_options'),
                        'subtitle'  => __('Enter text or banner ad code', 'azkaban_options'),
                        'desc'      => __('Banner size 336X280 or 300X250 or 250X250.', 'azkaban_options'),
                        'validate'  => 'html',
                        'default'   => ''
                    ),

                    array(
                        'id'        => 'enable_sidebar_bottom_ad',
                        'type'      => 'switch',
                        'title'     => __('Enable Sidebar Bottom Ad', 'azkaban_options'),
                        'subtitle'  => __('Enable or Disable advertisement on sidebar top.', 'azkaban_options'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'sidebar_bottom_ad_code',
                        'required'  => array('enable_sidebar_bottom_ad', '=', '1'),
                        'type'      => 'textarea',
                        'title'     => __('Sidebar Bottom Ad Code', 'azkaban_options'),
                        'subtitle'  => __('Enter text or banner ad code', 'azkaban_options'),
                        'desc'      => __('Banner size 336X280 or 300X250 or 250X250.', 'azkaban_options'),
                        'validate'  => 'html',
                        'default'   => ''
                    )
                )
            );
            

            $this->sections[] = array(
                'icon'      => 'el-icon-hand-down',
                'title'     => __('Footer Settings', 'azkaban_options'),
                'fields'    => array(

                    array(
                        'id'        => 'footer_text',
                        'type'      => 'textarea',
                        'title'     => __('Footer Text', 'azkaban_options'),
                        'subtitle'  => __('Enter text to be shown on footer', 'azkaban_options'),
                        'validate'  => 'html',
                        'default'   => '&copy; Copyright 2015 Bak-One  |  One Page Flat Template'
                    ),
                    array(
                        'id'        => 'show_wp_link_in_footer',
                        'type'      => 'switch',
                        'title'     => __('Show WordPress Credit Link', 'azkaban_options'),
                        'subtitle'  => __('Enable or Disable "Powered by WordPress" link in footer', 'azkaban_options'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'show_entries_rss_in_footer',
                        'type'      => 'switch',
                        'title'     => __('Show Entries RSS link', 'azkaban_options'),
                        'subtitle'  => __('Enable or Disable Entries RSS link in footer', 'azkaban_options'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'show_comments_rss_in_footer',
                        'type'      => 'switch',
                        'title'     => __('Show Comments RSS Link', 'azkaban_options'),
                        'subtitle'  => __('Enable or Disable Comments RSS link in footer', 'azkaban_options'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'show_footer_social_icons',
                        'type'      => 'switch',
                        'title'     => __('Social Icons', 'azkaban_options'),
                        'default'   => 1,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),

                    array(
                        'id'        => 'section_footer_style',
                        'type'      => 'section',
                        'title'     => __('Footer Style', 'azkaban_options'),
                        'indent'    => false
                    ),
                    array(
                        'id'        => 'footer_background_styles',
                        'type'      => 'background',
                        'output'  => array('#az-footerwrap'),
                        'title'     => __('Footer Background', 'azkaban_options'),
                        'subtitle'  => __('Specify the footer background properties including image, color, etc.', 'azkaban_options'),
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'       => 'footer_border_style',
                        'type'     => 'border',
                        'title'    => __('Footer Border Style', 'azkaban_options'),
                        'subtitle' => __('Specify the footer border properties', 'azkaban_options'),
                        'output'   => array('#az-footerwrap'),
                        'all'      => false,
                    ),
                    array(
                        'id'        => 'footer_typography_styles',
                        'type'      => 'typography',
                        'output'  => array('#az-footer p'),
                        'title'     => __('Footer Font', 'azkaban_options'),
                        'subtitle'  => __('Specify the footer font properties.', 'azkaban_options'),
                        'google'    => true,
                        'font-backup'   => true,
                    ),
                    array(
                        'id'       => 'footer_link_color',
                        'type'     => 'link_color',
                        'output'  => array('#az-footer a'),
                        'title'    => __('Footer Links Color', 'azkaban_options'),
                        'subtitle' => __('Specify the footer link color properties', 'azkaban_options'),
                    ),
                    array(
                        'id'            => 'footer_padding',
                        'type'          => 'spacing',
                        'output'        => array('#az-footerwrap'),
                        'mode'          => 'padding',
                        //'all'           => true,
                        //'top'           => false,
                        'right'         => false,
                        //'bottom'        => false,
                        'left'          => false,
                        'units'         => 'px',
                        'units_extended'=> 'false',
                        'display_units' => 'false',
                        'title'         => __('Footer Padding', 'azkaban_options'),
                        'subtitle'      => __('Specify the footer top and bottom padding.', 'azkaban_options'),
                        'default'       => array(
                            'padding-top'    => '0px', 
                            'padding-bottom' => '0px', 
                        )
                    ),

                    array(
                        'id'        => 'section_footer_widgets',
                        'type'      => 'section',
                        'title'     => __('Footer Widgets Settings', 'azkaban_options'),
                        'indent'    => false
                    ),
                    array(
                        'id'        => 'footer_widgets_layout',
                        'type'      => 'image_select',
                        'title'     => __('Footer Widgets Layout', 'azkaban_options'),
                        'subtitle'  => __('Select layout for footer widgets area.', 'azkaban_options'),
                        'options'   => array(
                            '1' => array('alt' => '4 Columns', 'img' => ReduxFramework::$_url . 'assets/img/4fw-2.png'),
                            '2' => array('alt' => '4 Columns', 'img' => ReduxFramework::$_url . 'assets/img/4fw.png'),
                            '3' => array('alt' => '3 Columns', 'img' => ReduxFramework::$_url . 'assets/img/3fw.png')
                        ),
                        'default'   => '1'
                    ),
                    
                    array(
                        'id'        => 'section_footer_widgets_style',
                        'type'      => 'section',
                        'title'     => __('Footer Widgets Style', 'azkaban_options'),
                        'indent'    => false
                    ),
                    array(
                        'id'        => 'footerwidget_background_styles',
                        'type'      => 'background',
                        'output'  => array('#az-footerwidgetwrap, #az-footerwidgets .widget_title span'),
                        'title'     => __('Footer Widget Background', 'azkaban_options'),
                        'subtitle'  => __('Specify the footer widget background properties including image, color, etc.', 'azkaban_options'),
                    ),
                    array(
                        'id'       => 'footerwidget_border_style',
                        'type'     => 'border',
                        'title'    => __('Footer Widget Border Style', 'azkaban_options'),
                        'subtitle' => __('Specify the footer widget border properties', 'azkaban_options'),
                        'output' => array('#az-footerwidgetwrap'),
                        'all'      => false,
                    ),
                    array(
                        'id'        => 'footerwidget_heading_styles',
                        'type'      => 'typography',
                        'output'  => array('#az-footerwidgets h2'),
                        'title'     => __('Footer Widget Heading Font', 'azkaban_options'),
                        'subtitle'  => __('Specify the footer widget heading font properties.', 'azkaban_options'),
                        'google'    => true,
                        'font-backup'   => true,
                    ),
                    array(
                        'id'        => 'footerwidget_typography_styles',
                        'type'      => 'typography',
                        'output'  => array('#az-footerwidgets'),
                        'title'     => __('Footer Widget Font', 'azkaban_options'),
                        'subtitle'  => __('Specify the footer widget font properties.', 'azkaban_options'),
                        'google'    => true,
                        'font-backup'   => true,
                    ),
                    array(
                        'id'       => 'footerwidget_link_color',
                        'type'     => 'link_color',
                        'output'  => array('#az-footerwidgets a'),
                        'title'    => __('Footer Widget Links Color', 'azkaban_options'),
                        'subtitle' => __('Specify the footer widget link color properties', 'azkaban_options'),
                    ),
                    array(
                        'id'            => 'footer_widget_padding',
                        'type'          => 'spacing',
                        'output'        => array('#az-footerwidgetwrap'),
                        'mode'          => 'padding',
                        //'all'           => true,
                        //'top'           => false,
                        'right'         => false,
                        //'bottom'        => false,
                        'left'          => false,
                        'units'         => 'px',
                        'units_extended'=> 'false',
                        'display_units' => 'false',
                        'title'         => __('Footer Widget Padding', 'azkaban_options'),
                        'subtitle'      => __('Specify the footer widget top and bottom padding.', 'azkaban_options'),
                        'default'       => array(
                            'padding-top'    => '0px', 
                            'padding-bottom' => '0px', 
                        )
                    ),

                )
            );
            
            $this->sections[] = array(
                'icon'      => 'el-icon-hand-down',
                'title'     => __('RSS and Social Networking', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'section_rss_feed_settings',
                        'type'      => 'section',
                        'title'     => __('RSS Feed Settings', 'azkaban_options'),
                        'indent'    => false
                    ),
                    array(
                        'id'        => 'hide_rss_icon',
                        'type'      => 'switch',
                        'title'     => __('Hide RSS Icon', 'azkaban_options'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'rss_url',
                        'type'      => 'text',
                        'title'     => __('Custom Feed URL', 'azkaban_options'),
                        'default'   => '',
                        'desc'      => __('Example: http://feedburner.com/userid', 'azkaban_options'),
                    ),

                    array(
                        'id'        => 'section_social_networking',
                        'type'      => 'section',
                        'title'     => __('Social Networking', 'azkaban_options'),
                        'indent'    => false
                    ),
                    array(
                        'id'        => 'facebook_url',
                        'type'      => 'text',
                        'title'     => __('Facebook URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'twitter_url',
                        'type'      => 'text',
                        'title'     => __('Twitter  URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'google_url',
                        'type'      => 'text',
                        'title'     => __('Google+ URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'linkedin_url',
                        'type'      => 'text',
                        'title'     => __('LinkedIn URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'pinterest_url',
                        'type'      => 'text',
                        'title'     => __('Pinterest URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'youtube_url',
                        'type'      => 'text',
                        'title'     => __('YouTube URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'skype_url',
                        'type'      => 'text',
                        'title'     => __('Skype URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'instagram_url',
                        'type'      => 'text',
                        'title'     => __('Instagram URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'myspace_url',
                        'type'      => 'text',
                        'title'     => __('MySpace URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'dribbble_url',
                        'type'      => 'text',
                        'title'     => __('Dribbble URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'evernote_url',
                        'type'      => 'text',
                        'title'     => __('EverNote URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'flickr_url',
                        'type'      => 'text',
                        'title'     => __('Flickr URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'dropbox_url',
                        'type'      => 'text',
                        'title'     => __('Dropbox URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'picasa_url',
                        'type'      => 'text',
                        'title'     => __('Picasa Web URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'deviantart_url',
                        'type'      => 'text',
                        'title'     => __('DeviantArt URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'grooveshark_url',
                        'type'      => 'text',
                        'title'     => __('GrooveShark URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'vimeo_url',
                        'type'      => 'text',
                        'title'     => __('Vimeo URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'sharethis_url',
                        'type'      => 'text',
                        'title'     => __('ShareThis URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'digg_url',
                        'type'      => 'text',
                        'title'     => __('Digg URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'reddit_url',
                        'type'      => 'text',
                        'title'     => __('Reddit URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'delicious_url',
                        'type'      => 'text',
                        'title'     => __('Delicious URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'stumbleupon_url',
                        'type'      => 'text',
                        'title'     => __('StumbleUpon URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'friendfeed_url',
                        'type'      => 'text',
                        'title'     => __('FriendFeed URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'tumblr_url',
                        'type'      => 'text',
                        'title'     => __('Tumblr URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'blogger_url',
                        'type'      => 'text',
                        'title'     => __('Blogger URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'wordpress_url',
                        'type'      => 'text',
                        'title'     => __('Wordpress URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'yelp_url',
                        'type'      => 'text',
                        'title'     => __('Yelp URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'lastfm_url',
                        'type'      => 'text',
                        'title'     => __('Last.fm URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'apple_url',
                        'type'      => 'text',
                        'title'     => __('Apple URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'foursquare_url',
                        'type'      => 'text',
                        'title'     => __('FourSquare URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'github_url',
                        'type'      => 'text',
                        'title'     => __('Github URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'soundcloud_url',
                        'type'      => 'text',
                        'title'     => __('SoundCloud URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'spotify_url',
                        'type'      => 'text',
                        'title'     => __('Spotify URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'paypal_url',
                        'type'      => 'text',
                        'title'     => __('PayPal URL', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'behance_url',
                        'type'      => 'text',
                        'title'     => __('Behance URL', 'azkaban_options'),
                    ),
                )
            );
            
            $this->sections[] = array(
                'icon'      => 'el-icon-list-alt',
                'title'     => __('Advanced Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'use_timthumb',
                        'type'      => 'switch',
                        'title'     => __('Enable TimThumb', 'azkaban_options'),
                        'default'   => false,
                        'desc'      => __('Enable timthumb to resize your images.', 'azkaban_options'),
                    ),
					array(
                        'id'        => 'hide_featured_image',
                        'type'      => 'switch',
                        'title'     => __('Hide/Show Featured Image', 'azkaban_options'),
                        'default'   => false,
                        'desc'      => __('Hide/Show Featured Image from fronend.', 'azkaban_options'),
                    ),
                )
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-list-alt',
                'title'     => __('Code Embed Settings', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'header_embed_codes',
                        'type'      => 'textarea',
                        'title'     => __('Embed Codes on Header', 'azkaban_options'),
                        'desc'      => __('Paste your Google Analytics or other tracking code here. It will be inserted just before the closing </head> tag.', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'footer_embed_codes',
                        'type'      => 'textarea',
                        'title'     => __('Embed Codes on Footer', 'azkaban_options'),
                        'desc'      => __('Paste any JS codes you want to be inserted in the footer. It will be inserted just before the closing </body> tag.', 'azkaban_options'),
                    )
                )
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-website',
                'title'     => __('Typography Options', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'body_typography_styles',
                        'type'      => 'typography',
                        'output'  => array('body'),
                        'title'     => __('Body Font Style', 'azkaban_options'),
                        'subtitle'  => __('Specify the body font properties.', 'azkaban_options'),
                        'google'    => true,
                        'font-backup'   => true,
                    ),
                    array(
                        'id'        => 'h1_typography_styles',
                        'type'      => 'typography',
                        'output'  => array('h1'),
                        'title'     => __('Heading 1 Style', 'azkaban_options'),
                        'subtitle'  => __('Specify the Heading 1 properties.', 'azkaban_options'),
                        'google'    => true,
                        'units'     => 'px',
/*                        'default'     => array(
                            'color'       => '#1a1a1a',
                            'font-style'  => 'normal',
                            'font-family' => 'Oswald',
                            'font-weight' => '300',
                            'google'      => true,
                            'font-size'   => '1.6em',
                            'line-height' => '1.6em'
                        ),
*/                    ),
                    array(
                        'id'        => 'h2_typography_styles',
                        'type'      => 'typography',
                        'output'  => array('h2'),
                        'title'     => __('Heading 2 Style', 'azkaban_options'),
                        'subtitle'  => __('Specify the Heading 2 properties.', 'azkaban_options'),
                        'google'    => true,
                        'units'     => 'px',
/*                        'default'     => array(
                            'color'       => '#1a1a1a',
                            'font-style'  => 'normal',
                            'font-family' => 'Oswald',
                            'font-weight' => '300',
                            'google'      => true,
                            'font-size'   => '1.4em',
                            'line-height' => '1.4em'
                        ),
*/                    ),
                    array(
                        'id'        => 'h3_typography_styles',
                        'type'      => 'typography',
                        'output'  => array('h3'),
                        'title'     => __('Heading 3 Style', 'azkaban_options'),
                        'subtitle'  => __('Specify the Heading 3 properties.', 'azkaban_options'),
                        'google'    => true,
                        'units'    => 'px',
/*                        'default'     => array(
                            'color'       => '#1a1a1a',
                            'font-style'  => 'normal',
                            'font-family' => 'Oswald',
                            'font-weight' => '300',
                            'google'      => true,
                            'font-size'   => '1.3em',
                            'line-height' => '1.2em'
                        ),
*/                    ),
                    array(
                        'id'        => 'sidebar_typography_styles',
                        'type'      => 'typography',
                        'output'  => array('#az-sidebar'),
                        'title'     => __('Sidebar Font', 'azkaban_options'),
                        'subtitle'  => __('Specify the sidebar font properties.', 'azkaban_options'),
                        'google'    => true,
                        'font-backup'   => true,
                    ),
                )
            );
                        
            $this->sections[] = array(
                'icon'      => 'el-icon-website',
                'title'     => __('Styling Options', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'body_background_styles',
                        'type'      => 'background',
                        'output'  => array('body'),
                        'title'     => __('Body Background', 'azkaban_options'),
                        'subtitle'  => __('Specify the body background properties including image, color, etc.', 'azkaban_options'),
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'        => 'link_color_styles',
                        'type'      => 'link_color',
                        'output'  => array('a'),
                        'title'     => __('Links Color Option', 'azkaban_options'),
                        'subtitle'  => __('Specify the link color styles.', 'azkaban_options'),
                    ),
                    array(
                        'id'        => 'content_background_styles',
                        'type'      => 'background',
                        'output'  => array('#az-container, #az-FullContainer'),
                        'title'     => __('Content Background', 'azkaban_options'),
                        'subtitle'  => __('Specify the content background properties including image, color, etc.', 'azkaban_options'),
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'        => 'sidebar_background_styles',
                        'type'      => 'background',
                        'output'  => array('#az-sidebar'),
                        'title'     => __('Sidebar Background', 'azkaban_options'),
                        'subtitle'  => __('Specify the sidebar background properties including image, color, etc.', 'azkaban_options'),
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'       => 'sidebar_link_color',
                        'type'     => 'link_color',
                        'output'  => array('#az-sidebar a'),
                        'title'    => __('Sidebar Links Color', 'azkaban_options'),
                        'subtitle' => __('Specify the sidebar link color properties', 'azkaban_options'),
                    ),
					array(
                        'id'        => 'box_width',
                        'type'      => 'switch',
                        'title'     => __('Add box width', 'azkaban_options'),
                        'subtitle'  => __('Enable or disable box width', 'azkaban_options'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
					array(
                        'id'        => 'header_box_background',
                        'type'      => 'color',
						'required'  => array('box_width', '=', '1'),
                        'title'     => __('Header box background color', 'azkaban_options'),
                        'subtitle'  => __('Specify the header box background color', 'azkaban_options'),
                        'default'   => '#008080',
                    ),
					array(
                        'id'        => 'body_box_background',
                        'type'      => 'color',
						'required'  => array('box_width', '=', '1'),
                        'title'     => __('Body box background color', 'azkaban_options'),
                        'subtitle'  => __('Specify the body box background color', 'azkaban_options'),
                        'default'   => '#A52A2A2',
                    ),	
                    array(
                        'id'        => 'custom_css_embed',
                        'type'      => 'textarea',
                        //'compiler'  => auto,
                        'title'     => __('Custom CSS', 'azkaban_options'),
                        'subtitle'  => __('Quickly add some custom CSS to the theme by adding it here.', 'azkaban_options'),
                        'desc'      => __('CSS will be validated automatically!', 'azkaban_options'),
                        //'validate'  => 'css',
                    ),
                )
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-cogs',
                'title'     => __('Navigation Styling', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'sitenav_typography_styles',
                        'type'      => 'typography',
                        'output'  => array('#az-navigationwrap, #az-navigationright, #az-navigation'),
                        'title'     => __('Menu Font', 'azkaban_options'),
                        'google'    => true,
                        'font-backup'   => true,
                    ),
                    array(
                        'id'        => 'sitenav_background_styles',
                        'type'      => 'background',
                        'output'  => array('#az-navigationwrap, #az-navigationright, #az-navigation'),
                        'title'     => __('Menu Background', 'azkaban_options'),
                        //'default'   => '#FFFFFF',
                    ),

                    array(
                        'id'       => 'sitenav-link',
                        'type'     => 'color',
                        'title'    => __('Top Level Link Color', 'azkaban_options'),
                        'output'  => array('.nk-sitenav a, .nk-sitenavr a'),
                        'default'  => '',
                        'validate' => 'color',
                    ),
                    array(
                        'id'       => 'sitenav-linkhover',
                        'type'     => 'color',
                        'title'    => __('Top Level Link Hover Color', 'azkaban_options'),
                        'output'  => array('.nk-sitenav li:hover > a, .nk-sitenav ul ul li:hover > a, .nk-sitenavr li:hover > a, .nk-sitenavr ul ul li:hover > a, .nk-sitenav li.current-menu-item a, .nk-sitenavr li.current-menu-item a'),
                        'default'  => '',
                        'validate' => 'color',
                    ),
                    array(
                        'id'        => 'sitenav-linkhoverbg',
                        'type'      => 'background',
                        'title'     => __('Top Level Link Hover Background Color', 'azkaban_options'),
                        'output'    => array('.nk-sitenav li:hover > a, .nk-sitenav ul ul li:hover > a, .nk-sitenavr li:hover > a, .nk-sitenavr ul ul li:hover > a'),
                        'background-repeat' => false,
                        'background-size' => false,
                        'background-attachment' => false,
                        'background-position' => false,
                        'background-image' => false,
                        'default'   => array(
                        )
                    ),

                    array(
                        'id'       => 'sitenav-sublevellink',
                        'type'     => 'color',
                        'title'    => __('Sub Level Link Color', 'azkaban_options'),
                        'output'  => array('nk-sitenav ul ul a, .nk-sitenavr ul ul a, .nk-sitenav li.current-menu-item ul li a, .nk-sitenavr li.current-menu-item ul li a'),
                        'default'  => '',
                        'validate' => 'color',
                    ),
                    array(
                        'id'        => 'sitenav-sublevelbg',
                        'type'      => 'background',
                        'title'     => __('Sub Level Background Color', 'azkaban_options'),
                        'output'    => array('.nk-sitenav ul ul, .nk-sitenavr ul ul, nk-sitenav ul ul li, .nk-sitenavr ul ul li'),
                        'background-repeat' => false,
                        'background-size' => false,
                        'background-attachment' => false,
                        'background-position' => false,
                        'background-image' => false,
                        'preview' => false,
                        'default'   => array(
                        )
                    ),
                    array(
                        'id'       => 'sitenav-sublevelborder',
                        'type'     => 'border',
                        'title'    => __('Sub Level Border Style', 'azkaban_options'),
                        'output'   => array('.nk-sitenav ul ul li, .nk-sitenavr ul ul li'),
                        'all'       => false,
                        'default'  => array(
                        )
                    ),

                    array(
                        'id'       => 'sitenav-sublevellinkhover',
                        'type'     => 'color',
                        'title'    => __('Sub Level Link Hover Color', 'azkaban_options'),
                        'output'  => array('.nk-sitenav ul ul li a:hover, .nk-sitenavr ul ul li a:hover'),
                        'default'  => '',
                        'validate' => 'color',
                    ),
                    array(
                        'id'        => 'sitenav-sublevelhoverbg',
                        'type'      => 'background',
                        'title'     => __('Sub Level Link Hover Background Color', 'azkaban_options'),
                        'output'    => array('.nk-sitenav ul ul li a:hover, .nk-sitenavr ul ul li a:hover'),
                        'background-repeat' => false,
                        'background-size' => false,
                        'background-attachment' => false,
                        'background-position' => false,
                        'background-image' => false,
                        'preview' => false,
                        'default'   => array(
                        )
                    ),

                ),
            );
            
            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'azkaban_options') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'azkaban_options') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'azkaban_options') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'azkaban_options') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            // You can append a new section at any time.
            $this->sections[] = array(
                'title'     => __('Backup', 'azkaban_options'),
                'desc'      => __('Import and Export your theme settings from file, text or URL.', 'azkaban_options'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your theme settings',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', 'azkaban_options'),
                'desc'      => __('<p class="description">Visit us at <a href="http://PixelThemeStudio.ca/">PixelThemeStudio.ca</a></p>', 'azkaban_options'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'azkaban-help-tab-1',
                'title'     => __('More Information', 'azkaban_options'),
                'content'   => __('<p>Feel free to <a href="http://PixelThemeStudio.ca/contact/">contact us</a> for any help you need.</p>', 'azkaban_options')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'azkaban-help-tab-2',
                'title'     => __('Submit Review', 'azkaban_options'),
                'content'   => __('<p>Submit your review at <a href="http://PixelThemeStudio.ca/">PixelThemeStudio.ca</a>.</p>', 'azkaban_options')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p><a href="https://www.facebook.com/pthemestudio" target="_blank"><img src="'.get_template_directory_uri().'/images/socialicons/fb.png" title="Find Us On Facebook" /></a> <a href="https://twitter.com/pthemestudio" target="_blank"><img src="'.get_template_directory_uri().'/images/socialicons/twitter.png" title="Follow Us On Twitter" /></a> <a href="https://www.youtube.com/channel/UCHBQpt1gn7woiKQkgVDAObw" target="_blank"><img src="'.get_template_directory_uri().'/images/socialicons/youtube.png" title="Check Us On YouTube" /></a> <a href="http://www.pinterest.com/berkansanches/pthemestudio/" target="_blank"><img src="'.get_template_directory_uri().'/images/socialicons/pinterest.png" title="Check Us On Pinterest" /></a></p>', 'azkaban_options');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array (
                'opt_name' => 'azkaban_options',
                'display_name'     => $theme->get('Name'),
                'display_version'  => $theme->get('Version'),
                'global_variable' => 'azkaban_options',
                'admin_bar' => '1',
                'allow_sub_menu' => '1',
                'customizer' => '1',
                'default_show' => '1',
                'default_mark' => '*',
                'footer_text' => '',
                'google_api_key' => 'sdfadfasdfasdfasdfasdf',
                'hint-icon' => 'el-icon-question-sign',
                'icon_position' => 'right',
                'icon_color' => '#1e73be',
                'icon_size' => 'normal',
                'tip_style_color' => 'light',
                'tip_style_rounded' => '1',
                'tip_style_style' => 'youtube',
                'tip_position_my' => 'top left',
                'tip_position_at' => 'bottom right',
                'tip_show_duration' => '500',
                'tip_show_event' => 
                array (
                  0 => 'mouseover',
                ),
                'tip_hide_duration' => '500',
                'tip_hide_event' => 
                array (
                  0 => 'mouseleave',
                  1 => 'unfocus',
                ),
                'intro_text' => '<p><center></center></p>',
                'menu_title' => $theme->get('Name') . ' Options',
                'menu_type' => 'menu',
                'output' => '1',
                'output_tag' => '1',
                'page_icon' => 'icon-themes',
                'page_parent_post_type' => 'your_post_type',
                'page_priority' => '4',
                'page_permissions' => 'manage_options',
                'page_slug' => '_options',
                'page_title' => $theme->get('Name') . ' Theme Options',
                'save_defaults' => '1',
                'show_import_export' => '1',
                'update_notice' => '1',
                'footer_credit' => 'Powered By <a href="http://www.reduxframework.com/">Redux Framework</a> v3.2.7.1 - Developed By <a href="http://PixelThemeStudio.ca">PixelThemeStudio.ca</a>',
            );

            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/CraftyThemes',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://twitter.com/CraftyThemes',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.youtube.com/channel/UCHBQpt1gn7woiKQkgVDAObw',
                'title' => 'Visit us on YouTube',
                'icon'  => 'el-icon-youtube'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://www.pinterest.com/berkansanches/pthemestudio/',
                'title' => 'Find us on Pinterest',
                'icon'  => 'el-icon-pinterest'
            );
        }
    }
    
    global $reduxConfig;
    $reduxConfig = new azkaban_options_redux_framework_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('azkaban_options_my_custom_field')):
    function azkaban_options_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('azkaban_options_validate_callback_function')):
    function azkaban_options_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
