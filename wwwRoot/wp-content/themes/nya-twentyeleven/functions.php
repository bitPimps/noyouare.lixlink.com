<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
   wp_enqueue_style( 'child-style', get_stylesheet_directory_uri().'/style.css' );
}

/*
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'twentyeleven_setup' );

if ( ! function_exists( 'twentyeleven_setup' ) ) :
	/**
	 * Set up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
	 * functions.php file.
	 *
	 * @uses load_theme_textdomain()    For translation/localization support.
	 * @uses add_editor_style()         To style the visual editor.
	 * @uses add_theme_support()        To add support for post thumbnails, automatic feed links, custom headers
	 *                                  and backgrounds, and post formats.
	 * @uses register_nav_menus()       To add support for navigation menus.
	 * @uses register_default_headers() To register the default custom header images provided with the theme.
	 * @uses set_post_thumbnail_size()  To set a custom post thumbnail size.
	 *
	 * @since Twenty Eleven 1.0
	 */
	function twentyeleven_setup() {

		/*
		 * Make Twenty Eleven available for translation.
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on Twenty Eleven, use
		 * a find and replace to change 'twentyeleven' to the name
		 * of your theme in all the template files.
		 */
		load_theme_textdomain( 'twentyeleven', get_template_directory() . '/languages' );

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Load regular editor styles into the new block-based editor.
		add_theme_support( 'editor-styles' );

		// Load default block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Add support for custom color scheme.
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Blue', 'twentyeleven' ),
					'slug'  => 'blue',
					'color' => '#1982d1',
				),
				array(
					'name'  => __( 'Black', 'twentyeleven' ),
					'slug'  => 'black',
					'color' => '#000',
				),
				array(
					'name'  => __( 'Dark Gray', 'twentyeleven' ),
					'slug'  => 'dark-gray',
					'color' => '#373737',
				),
				array(
					'name'  => __( 'Medium Gray', 'twentyeleven' ),
					'slug'  => 'medium-gray',
					'color' => '#666',
				),
				array(
					'name'  => __( 'Light Gray', 'twentyeleven' ),
					'slug'  => 'light-gray',
					'color' => '#e2e2e2',
				),
				array(
					'name'  => __( 'White', 'twentyeleven' ),
					'slug'  => 'white',
					'color' => '#fff',
				),
			)
		);

		// Load up our theme options page and related code.
		require get_template_directory() . '/inc/theme-options.php';

		// Grab Twenty Eleven's Ephemera widget.
		require get_template_directory() . '/inc/widgets.php';

		// Add default posts and comments RSS feed links to <head>.
		add_theme_support( 'automatic-feed-links' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menu( 'primary', __( 'Primary Menu', 'twentyeleven' ) );

		// Add support for a variety of post formats.
		add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

		$theme_options = twentyeleven_get_theme_options();
		if ( 'dark' == $theme_options['color_scheme'] ) {
			$default_background_color = '1d1d1d';
		} else {
			$default_background_color = 'e2e2e2';
		}

		// Add support for custom backgrounds.
		add_theme_support(
			'custom-background',
			array(
				/*
				* Let WordPress know what our default background color is.
				* This is dependent on our current color scheme.
				*/
				'default-color' => $default_background_color,
			)
		);

		// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images.
		add_theme_support( 'post-thumbnails' );

		// Add support for custom headers.
		$custom_header_support = array(
			// The default header text color.
			'default-text-color'     => '000',
			// The height and width of our custom header.
			/**
			 * Filter the Twenty Eleven default header image width.
			 *
			 * @since Twenty Eleven 1.0
			 *
			 * @param int The default header image width in pixels. Default 1000.
			 */
			'width'                  => apply_filters( 'twentyeleven_header_image_width', 1000 ),
			/**
			 * Filter the Twenty Eleven default header image height.
			 *
			 * @since Twenty Eleven 1.0
			 *
			 * @param int The default header image height in pixels. Default 288.
			 */
			'height'                 => apply_filters( 'twentyeleven_header_image_height', 288 ),
			// Support flexible heights.
			'flex-height'            => true,
			// Random image rotation by default.
			'random-default'         => true,
			// Callback for styling the header.
			'wp-head-callback'       => 'twentyeleven_header_style',
			// Callback for styling the header preview in the admin.
			'admin-head-callback'    => 'twentyeleven_admin_header_style',
			// Callback used to display the header preview in the admin.
			'admin-preview-callback' => 'twentyeleven_admin_header_image',
		);

		add_theme_support( 'custom-header', $custom_header_support );

		if ( ! function_exists( 'get_custom_header' ) ) {
			// This is all for compatibility with versions of WordPress prior to 3.4.
			define( 'HEADER_TEXTCOLOR', $custom_header_support['default-text-color'] );
			define( 'HEADER_IMAGE', '' );
			define( 'HEADER_IMAGE_WIDTH', $custom_header_support['width'] );
			define( 'HEADER_IMAGE_HEIGHT', $custom_header_support['height'] );
			add_custom_image_header( $custom_header_support['wp-head-callback'], $custom_header_support['admin-head-callback'], $custom_header_support['admin-preview-callback'] );
			add_custom_background();
		}

		/*
		 * We'll be using post thumbnails for custom header images on posts and pages.
		 * We want them to be the size of the header image that we just defined.
		 * Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
		 */
		set_post_thumbnail_size( $custom_header_support['width'], $custom_header_support['height'], true );

		/*
		 * Add Twenty Eleven's custom image sizes.
		 * Used for large feature (header) images.
		 */
		add_image_size( 'large-feature', $custom_header_support['width'], $custom_header_support['height'], true );
		// Used for featured posts if a large-feature doesn't exist.
		add_image_size( 'small-feature', 500, 300 );

		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers(
			array(
				'benji_madden'      => array(
					'url'           => '%s/images/headers/wheel.jpg',
					'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Benji Madden', 'twentyeleven' ),
				),
				'ellis_announcing'      => array(
					'url'           => '%s/images/headers/wheel.jpg',
					'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Ellis Announcing', 'twentyeleven' ),
				),
				'em_ashley_makeout'    => array(
					'url'           => '%s/images/headers/wheel.jpg',
					'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Erica Ashley Makeout', 'twentyeleven' ),
				),
			)
		);

		// Indicate widget sidebars can use selective refresh in the Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}
endif; // twentyeleven_setup()