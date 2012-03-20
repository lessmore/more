<?php

/*
----- Table of Contents

	1.  Load other functions
	2.  Set up theme specific variables
	3.  Image max width
	4.  Enqueue Client Files
	5.  Register Sidebars
	6.  Main Menu Fallback
	7.  Navigation Function
	8.  Define theme options
	9.  Theme option return functions
				I.    Logo Functions
				II.
				III.  Footer Functions
				IV.   Alertbox Functions
				V.    Banner Functions
				VI.   Feed Functions
				VII.  Twitter Functions
				VIII. Side Image Functions
				IX.   CSS Functions

*/

/*---------------------------------------------------------
	1. Load other functions
------------------------------------------------------------ */
locate_template( array( 'functions' . DIRECTORY_SEPARATOR . 'comments.php' ), true );
locate_template( array( 'functions' . DIRECTORY_SEPARATOR . 'ttf-admin.php' ), true );


if (!class_exists( 'Vigilance' )) {
	class Vigilance extends TTFCore {

		/*---------------------------------------------------------
			2. Set up theme specific variables
		------------------------------------------------------------ */
		function Vigilance () {

			$this->themename = "Vigilance";
			$this->themeurl = "http://thethemefoundry.com/vigilance/";
			$this->shortname = "V";
			$this->domain = "vigilance";

			add_action( 'init', array(&$this, 'registerMenus' ) );
			add_action( 'setup_theme_vigilance', array(&$this, 'setOptions' ) );

			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueueClientFiles' ) );

			parent::TTFCore();
		}

		/*---------------------------------------------------------
			3. Image max width
		------------------------------------------------------------ */
		function addContentWidth() {
			global $content_width;
			if ( ! isset( $content_width ) ) {
				$content_width = 600;
			}
		}

		/*---------------------------------------------------------
			4. Enqueue Client Files
		------------------------------------------------------------ */
		function enqueueClientFiles() {
			global $wp_styles;

			if ( ! is_admin() ) {
				wp_enqueue_style(
					'vigilance-style',
					get_bloginfo( 'stylesheet_url' ),
					'',
					null
				);

				wp_enqueue_style(
					'vigilance-ie-style',
					get_template_directory_uri() . '/stylesheets/ie.css',
					array( 'vigilance-style' ),
					null
				);
				$wp_styles->add_data( 'vigilance-ie-style', 'conditional', 'lt IE 8' );

				wp_enqueue_style(
					'vigilance-ie6-style',
					get_template_directory_uri() . '/stylesheets/ie6.css',
					array( 'vigilance-style' ),
					null
				);
				$wp_styles->add_data( 'vigilance-ie6-style', 'conditional', 'lte IE 6' );

				if ( is_singular() ) {
					wp_enqueue_script( 'comment-reply' );
				}

				wp_enqueue_script( 'jquery' );

				wp_enqueue_script(
					'vigilance',
					get_template_directory_uri() . '/javascripts/vigilance.js',
					array( 'jquery' ),
					null
				);
			}
		}

		/*---------------------------------------------------------
			5. Register Sidebars
		------------------------------------------------------------ */
		function registerSidebars() {

			register_sidebar( array(
				'name'=> __( 'Wide Sidebar', 'vigilance' ),
				'id' => 'wide_sidebar',
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget' => '</li>',
				'before_title' => '<h2 class="widgettitle">',
				'after_title' => '</h2>',
			) );

		}

		/*---------------------------------------------------------
			6. Main Menu Fallback
		------------------------------------------------------------ */
		function main_menu_fallback() {
			?>
			<div id="navigation">
				<ul class="nav">
					<?php
						wp_list_pages( 'title_li=&number=6' );
					?>
				</ul>
			</div>
			<?php
			}

		/*---------------------------------------------------------
			7. Navigation Function
		------------------------------------------------------------ */
		function registerMenus() {
			register_nav_menu( 'nav-1', __( 'Top Navigation', 'vigilance' ) );
		}

		/*---------------------------------------------------------
			8. Define theme options
		------------------------------------------------------------ */
		function setOptions() {

			/*
				OPTION TYPES:
				- checkbox: name, id, desc, std, type
				- radio: name, id, desc, std, type, options
				- text: name, id, desc, std, type
				- colorpicker: name, id, desc, std, type
				- select: name, id, desc, std, type, options
				- textarea: name, id, desc, std, type, options
			*/

			$this->options = array(
				array(
					"name" => __( 'Custom Logo Image <span>insert your custom logo image in the header</span>', 'vigilance' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Enable custom logo image', 'vigilance' ),
					"id" => $this->shortname."_logo",
					"desc" => __( 'Check to use a custom logo in the header.', 'vigilance' ),
					"std" => "false",
					"pro" => 'true',
					"type" => "checkbox"),

				array(
					"name" => __( 'Logo URL', 'vigilance' ),
					"id" => $this->shortname."_logo_img",
					"desc" => sprintf( __( 'Upload an image or enter an URL for your image.', 'shelf' ), '<code>' . STYLESHEETPATH . '/images/</code>' ),
					"std" => '',
					"pro" => 'true',
					"upload" => true,
					"class" => "logo-image-input",
					"type" => "upload"),

				array(
					"name" => __( 'Logo image <code>&lt;alt&gt;</code> tag', 'vigilance' ),
					"id" => $this->shortname."_logo_img_alt",
					"desc" => __( 'Specify the <code>&lt;alt&gt;</code> tag for your logo image.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Display tagline', 'vigilance' ),
					"id" => $this->shortname."_tagline",
					"desc" => __( 'Check to show your tagline below your logo.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "checkbox"),

				array(
					"name" => __( 'Color Scheme <span>customize your color scheme</span>', 'vigilance' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Customize colors', 'vigilance' ),
					"id" => $this->shortname."_background_css",
					"desc" => __( 'If enabled your theme will use the layouts and colors you choose below.', 'vigilance' ),
					"std" => "disabled",
					"pro" => 'true',
					"type" => "select",
					"options" => array(
						"disabled" => __( 'Disabled', 'vigilance' ),
						"enabled" => __( 'Enabled', 'vigilance' ),
					) ),

				array(
					"name" => __( 'Background color', 'vigilance' ),
					"id" => $this->shortname."_background_color",
					"desc" => __( 'Use hex values and be sure to include the leading #.', 'vigilance' ),
					"std" => "#a39c8a",
					"pro" => 'true',
					"type" => "colorpicker"),

				array(
					"name" => __( 'Border color', 'vigilance' ),
					"id" => $this->shortname."_border_color",
					"desc" => __( 'Use hex values and be sure to include the leading #.', 'vigilance' ),
					"std" => "#9a927f",
					"pro" => 'true',
					"type" => "colorpicker"),

				array(
					"name" => __( 'Link color', 'vigilance' ),
					"id" => $this->shortname."_link_color",
					"desc" => __( 'Use hex values and be sure to include the leading #.', 'vigilance' ),
					"std" => "#772124",
					"pro" => 'true',
					"type" => "colorpicker"),

				array(
					"name" => __( 'Link hover color', 'vigilance' ),
					"id" => $this->shortname."_hover_color",
					"desc" => __( 'Use hex values and be sure to include the leading #.', 'vigilance' ),
					"std" => "#58181b",
					"pro" => 'true',
					"type" => "colorpicker"),

				array(
					"name" => __( 'Disable hover background images', 'vigilance' ),
					"id" => $this->shortname."_image_hover",
					"desc" => __( 'Check this box if you use custom link colors and do not want the default red showing when a user hovers over the comments bubble or the sidebar menu items.', 'vigilance' ),
					"std" => "false",
					"pro" => 'true',
					"type" => "checkbox"),

				array(
					"name" => __( 'Top Banner Image <span>control your top banner image state</span>', 'vigilance' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Banner state', 'vigilance' ),
					"id" => $this->shortname."_banner_state",
					"desc" => sprintf( __( 'Add your images to the top banner rotation by uploading them to the %s directory.', 'vigilance' ), '<code>' . STYLESHEETPATH . '/images/top-banner</code>' ),
					"std" => "hide",
					"pro" => 'true',
					"type" => "select",
					"options" => array(
						"rotate" => __( 'Rotating images', 'vigilance' ),
						"static" => __( 'Static image', 'vigilance' ),
						"specific" => __( 'Page or post specific', 'vigilance' ),
						"custom" => __( 'Custom code', 'vigilance' ),
						"hide" => __( 'Do not show an image', 'vigilance' ) ) ),

				array(
					"name" => __( 'Banner height', 'vigilance' ),
					"id" => $this->shortname."_banner_height",
					"desc" => __( 'The height of your image. The width is fixed at 596px.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Banner <code>&lt;alt&gt;</code> tag', 'vigilance' ),
					"id" => $this->shortname."_banner_alt",
					"desc" => __( 'Specify the <code>&lt;alt&gt;</code> tag for your banner image(s). Will default to your blog title if left blank.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Static image name', 'vigilance' ),
					"id" => $this->shortname."_banner_url",
					"desc" => sprintf( __( 'Set the <em>Banner State</em> to "Static Image" and upload your image to the %s directory.', 'vigilance' ), '<code>' . STYLESHEETPATH . '/images/top-banner</code>' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Home image name', 'vigilance' ),
					"id" => $this->shortname."_banner_home",
					"desc" => sprintf( __( 'To replace your home top banner with a specific image upload it to the %s directory.', 'vigilance' ), '<code>' . STYLESHEETPATH . '/images/top-banner</code>' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Custom code', 'vigilance' ),
					"id" => $this->shortname."_banner_custom",
					"desc" => __( 'Replace your top banner with custom code. The <em>Banner State</em> must be set to "Custom code" for this to work.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "textarea",
					"options" => array(
						"rows" => "5",
						"cols" => "40") ),

				array(
					"name" => __( 'Alert Box <span>toggle your custom alert box</span>', 'vigilance' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Alert Box on/off switch', 'vigilance' ),
					"id" => $this->shortname."_alertbox_state",
					"desc" => __( 'Toggle the alert box on or off.', 'vigilance' ),
					"std" => "off",
					"pro" => 'true',
					"type" => "select",
					"options" => array(
						"off" => __( 'Off', 'vigilance' ),
						"on" => __( 'On', 'vigilance' ) ) ),

				array(
					"name" => __( 'Alert Title', 'vigilance' ),
					"id" => $this->shortname."_alertbox_title",
					"desc" => __( 'The heading for your alert.', 'vigilance' ),
					"std" => "Your Alert Header",
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Alert Message', 'vigilance' ),
					"id" => $this->shortname."_alertbox_content",
					"desc" => __( 'A special alert message that is shown on the homepage of your blog.', 'vigilance' ),
					"std" => "Your alert message goes here.",
					"pro" => 'true',
					"type" => "textarea",
					"options" => array(
						"rows" => "8",
						"cols" => "70") ),

				array(
					"name" => __( 'Sidebar Image <span>control your sidebar image state</span>', 'vigilance' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Image state', 'vigilance' ),
					"desc" => sprintf( __( 'Add your images to the sidebar rotation by uploading them to the %s directory.', 'vigilance' ), '<code>' . STYLESHEETPATH . '/images/sidebar</code>' ),
					"id" => $this->shortname."_sideimg_state",
					"std" => "hide",
					"pro" => 'true',
					"type" => "select",
					"options" => array(
						"rotate" => __( 'Rotating images', 'vigilance' ),
						"static" => __( 'Static image', 'vigilance' ),
						"custom" => __( 'Custom code', 'vigilance' ),
						"specific" => __( 'Page or post specific', 'vigilance' ),
						"hide" => __( 'Do not show an image', 'vigilance' ) ) ),

				array(
					"name" => __( 'Image height', 'vigilance' ),
					"id" => $this->shortname."_sideimg_height",
					"desc" => __( 'The height of your image. The width is fixed at 300px.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Image <code>&lt;alt&gt;</code> tag', 'vigilance' ),
					"id" => $this->shortname."_sideimg_alt",
					"desc" => __( 'The <code>&lt;alt&gt;</code> tag for your sidebar image(s). Will default to your blog title if left blank.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Static image', 'vigilance' ),
					"id" => $this->shortname."_sideimg_url",
					"desc" => sprintf( __( 'Set the <em>Image State</em> to "Static Image" and upload your image to the %s directory.', 'vigilance' ), '<code>' . STYLESHEETPATH . '/images/sidebar</code>' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Image link', 'vigilance' ),
					"id" => $this->shortname."_sideimg_link",
					"desc" => __( 'Define a hyperlink for your sidebar image. If left empty the anchor tags will not be included.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Custom code', 'vigilance' ),
					"id" => $this->shortname."_sideimg_custom",
					"desc" => __( 'Replace your sidebar image with custom code. The <em>Image State</em> must be set to "Custom code" for this to work.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "textarea",
					"options" => array(
						"rows" => "5",
						"cols" => "40") ),

				array(
					"name" => __( 'Sidebar Feed Box <span>share feeds and updates with your readers</span>', 'vigilance' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Feed box state', 'vigilance' ),
					"desc" => __( 'Enable or disable the feed box located in the sidebar.', 'vigilance' ),
					"id" => $this->shortname."_feed_state",
					"std" => "enabled",
					"pro" => 'true',
					"type" => "select",
					"options" => array(
						"disabled" => __( 'Disabled', 'vigilance' ),
						"enabled" => __( 'Enabled', 'vigilance' ))),

				array(
					"name" => __( 'Feed box title text', 'vigilance' ),
					"id" => $this->shortname."_feed_title",
					"desc" => __( 'Header for your feed box.', 'vigilance' ),
					"std" => __( 'Get Free Updates', 'vigilance' ),
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Feed box intro text', 'vigilance' ),
					"id" => $this->shortname."_feed_intro",
					"desc" => __( 'Enter your feed intro text here.', 'vigilance' ),
					"std" => __( 'Get the latest and the greatest news delivered for free to your reader or your inbox:', 'vigilance' ),
					"pro" => 'true',
					"type" => "textarea",
					"options" => array(
						"rows" => "5",
						"cols" => "40") ),

				array(
					"name" => __( '<a href="http://feedburner.google.com">Feedburner</a> email updates link', 'vigilance' ),
					"id" => $this->shortname."_feed_email",
					"desc" => __( 'Enter your feed email link here. Do not paste the provided link code, extract and paste the URL only.', 'vigilance' ),
					"std" => "http://feedburner.google.com/fb/a/mailverify?uri=YOURFEEDID&loc=en_US",
					"pro" => 'true',
					"type" => "textarea",
					"options" => array(
						"rows" => "2",
						"cols" => "80") ),

				array(
					"name" => __( 'Enable Twitter', 'vigilance' ),
					"id" => $this->shortname."_twitter_toggle",
					"desc" => __( 'Hip to Twitter? Check this box.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "checkbox"),

				array(
					"name" => __( 'Twitter updates link', 'vigilance' ),
					"id" => $this->shortname."_twitter",
					"desc" => __( 'Enter your twitter link here.', 'vigilance' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Footer <span>add a copyright notice and tracking codes</span>', 'vigilance' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Copyright notice', 'vigilance' ),
					"id" => $this->shortname."_copyright_name",
					"desc" => __( 'Your name or the name of your business.', 'vigilance' ),
					"std" => '',
					"type" => "text"),

				array(
					"name" => __( 'Stats code', 'vigilance' ),
					"id" => $this->shortname."_stats_code",
					"desc" => sprintf( __( 'If you would like to use Google Analytics or any other tracking script in your footer just paste it here. The script will be inserted before the closing %s tag.', 'vigilance' ), '<code>&#60;/body&#62;</code>' ),
					"std" => '',
					"type" => "textarea",
					"options" => array(
						"rows" => "5",
						"cols" => "40") ),

			);
		}

		/*---------------------------------------------------------
			9.  Theme option return functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			I. Logo Functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			III. Footer Functions
		------------------------------------------------------------ */
		function copyrightName() {
			return stripslashes( wp_filter_post_kses(get_option($this->shortname.'_copyright_name' )) );
		}

		/*---------------------------------------------------------
			IV. Alertbox Functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			V. Banner Functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			VI. Feed Functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			VII. Twitter Functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			VIII. Side Image Functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			IX. CSS Functions
		------------------------------------------------------------ */

	}
}

/* SETTING EVERYTHING IN MOTION */
function load_vigilance_pro_theme() {
	$GLOBALS['vigilance'] = new Vigilance;
}

add_action( 'after_setup_theme', 'load_vigilance_pro_theme' );