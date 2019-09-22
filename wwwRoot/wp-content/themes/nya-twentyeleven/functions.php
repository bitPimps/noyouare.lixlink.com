<?php
/**
 * No You Are functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, noyouare_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see https://codex.wordpress.org/Theme_Development and
 * https://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'noyouare_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see https://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage No_You_Are
 * @since No You Are 1.0
 */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 584;

/*
 * Tell WordPress to run noyouare_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'noyouare_setup' );

if ( ! function_exists( 'noyouare_setup' ) ):
/**
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override noyouare_setup() in a child theme, add your own noyouare_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain()    For translation/localization support.
 * @uses add_editor_style()         To style the visual editor.
 * @uses add_theme_support()        To add support for post thumbnails, automatic feed links, custom headers
 * 	                                and backgrounds, and post formats.
 * @uses register_nav_menus()       To add support for navigation menus.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size()  To set a custom post thumbnail size.
 *
 * @since No You Are 1.0
 */
function noyouare_setup() {

	/*
	 * Make No You Are available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on No You Are, use
	 * a find and replace to change 'noyouare' to the name
	 * of your theme in all the template files.
	 */
	load_theme_textdomain( 'noyouare', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Load up our theme options page and related code.
	require( get_template_directory() . '/inc/theme-options.php' );

	// Grab No You Are's Ephemera widget.
	require( get_template_directory() . '/inc/widgets.php' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'noyouare' ) );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

	$theme_options = noyouare_get_theme_options();
	if ( 'dark' == $theme_options['color_scheme'] )
		$default_background_color = '1d1d1d';
	else
		$default_background_color = 'e2e2e2';

	// Add support for custom backgrounds.
	add_theme_support( 'custom-background', array(
		/*
		 * Let WordPress know what our default background color is.
		 * This is dependent on our current color scheme.
		 */
		'default-color' => $default_background_color,
	) );

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );

	// Add support for custom headers.
	$custom_header_support = array(
		// The default header text color.
		'default-text-color' => '000',
		// The height and width of our custom header.
		/**
		 * Filter the No You Are default header image width.
		 *
		 * @since No You Are 1.0
		 *
		 * @param int The default header image width in pixels. Default 1000.
		 */
		'width' => apply_filters( 'noyouare_header_image_width', 1000 ),
		/**
		 * Filter the No You Are default header image height.
		 *
		 * @since No You Are 1.0
		 *
		 * @param int The default header image height in pixels. Default 288.
		 */
		'height' => apply_filters( 'noyouare_header_image_height', 288 ),
		// Support flexible heights.
		'flex-height' => true,
		// Random image rotation by default.
		'random-default' => true,
		// Callback for styling the header.
		'wp-head-callback' => 'noyouare_header_style',
		// Callback for styling the header preview in the admin.
		'admin-head-callback' => 'noyouare_admin_header_style',
		// Callback used to display the header preview in the admin.
		'admin-preview-callback' => 'noyouare_admin_header_image',
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
	 * Add No You Are's custom image sizes.
	 * Used for large feature (header) images.
	 */
	add_image_size( 'large-feature', $custom_header_support['width'], $custom_header_support['height'], true );
	// Used for featured posts if a large-feature doesn't exist.
	add_image_size( 'small-feature', 500, 300 );

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'benji_madden' => array(
			'url' => '%s/images/headers/benji_madden.jpg',
			'thumbnail_url' => '%s/images/headers/benji_madden_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Benji Madden', 'noyouare' )
		),
		'ellis_announcing' => array(
			'url' => '%s/images/headers/ellis_announcing.jpg',
			'thumbnail_url' => '%s/images/headers/ellis_announcing_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Ellis Announcing', 'noyouare' )
		),
		'em_ashley_makeout' => array(
			'url' => '%s/images/headers/em_ashley_makeout.jpg',
			'thumbnail_url' => '%s/images/headers/em_ashley_makeout_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Erica Ashley Makeout', 'noyouare' )
		),
		'mirra_vs_deegan' => array(
			'url' => '%s/images/headers/mirra_vs_deegan.jpg',
			'thumbnail_url' => '%s/images/headers/mirra_vs_deegan_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Mirra vs Deegan', 'noyouare' )
		),
		'em7' => array(
			'url' => '%s/images/headers/em7.jpg',
			'thumbnail_url' => '%s/images/headers/em7_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'EM7 Face Punch', 'noyouare' )
		),
		'emilyinsd' => array(
			'url' => '%s/images/headers/emilyinsd.jpg',
			'thumbnail_url' => '%s/images/headers/emilyinsd_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Emily Destroying', 'noyouare' )
		),
		'em8_ddd' => array(
			'url' => '%s/images/headers/em8_ddd.jpg',
			'thumbnail_url' => '%s/images/headers/em8_ddd_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Death! Death! Die!', 'noyouare' )
		),
		'em8_ddd_others' => array(
			'url' => '%s/images/headers/em8_ddd_others.jpg',
			'thumbnail_url' => '%s/images/headers/em8_ddd_others_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Death! Death! Die! Others', 'noyouare' )
		),
		'em8_ddd_show' => array(
			'url' => '%s/images/headers/em8_ddd_show.jpg',
			'thumbnail_url' => '%s/images/headers/em8_ddd_show_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Death! Death! Die! Show', 'noyouare' )
		),
		'em6_jb_001' => array(
			'url' => '%s/images/headers/jingleberries_em6_001.jpg',
			'thumbnail_url' => '%s/images/headers/jingleberries_em6_001_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'EllisMania 6 Jingleberries', 'noyouare' )
		),
		'nyr001' => array(
			'url' => '%s/images/headers/nyr001.jpg',
			'thumbnail_url' => '%s/images/headers/nyr001_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'ellisbenjipool' => array(
			'url' => '%s/images/headers/ellis_benji_pool.jpg',
			'thumbnail_url' => '%s/images/headers/ellis_benji_pool_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'ellisfirerain' => array(
			'url' => '%s/images/headers/ellis_fire_rain.jpg',
			'thumbnail_url' => '%s/images/headers/ellis_fire_rain_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'ellismayhem' => array(
			'url' => '%s/images/headers/ellis_n_mayhem.jpg',
			'thumbnail_url' => '%s/images/headers/ellis_n_mayhem_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'ellispals' => array(
			'url' => '%s/images/headers/ellis_n_pals.jpg',
			'thumbnail_url' => '%s/images/headers/ellis_n_pals_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'cumtardmerica' => array(
			'url' => '%s/images/headers/cumtard001.jpg',
			'thumbnail_url' => '%s/images/headers/cumtard001_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'rawdogcollage' => array(
			'url' => '%s/images/headers/rawdog_collage.jpg',
			'thumbnail_url' => '%s/images/headers/rawdog_collage_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_3hotchicks' => array(
			'url' => '%s/images/headers/em9_3hotchicks.jpg',
			'thumbnail_url' => '%s/images/headers/em9_3hotchicks_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_alicia' => array(
			'url' => '%s/images/headers/em9_alicia.jpg',
			'thumbnail_url' => '%s/images/headers/em9_alicia_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_cockoffchest' => array(
			'url' => '%s/images/headers/em9_cockoffchest.jpg',
			'thumbnail_url' => '%s/images/headers/em9_cockoffchest_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_cogdeth' => array(
			'url' => '%s/images/headers/em9_cogdeth.jpg',
			'thumbnail_url' => '%s/images/headers/em9_cogdeth_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_ig_cogdeth' => array(
			'url' => '%s/images/headers/em9_ig_cogdeth.jpg',
			'thumbnail_url' => '%s/images/headers/em9_ig_cogdeth_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_ddd' => array(
			'url' => '%s/images/headers/em9_ddd.jpg',
			'thumbnail_url' => '%s/images/headers/em9_ddd_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_elliswins' => array(
			'url' => '%s/images/headers/em9_elliswins.jpg',
			'thumbnail_url' => '%s/images/headers/em9_elliswins_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_fartlife' => array(
			'url' => '%s/images/headers/em9_fartlife.jpg',
			'thumbnail_url' => '%s/images/headers/em9_fartlife_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_kenjiplg' => array(
			'url' => '%s/images/headers/em9_kenjiplg.jpg',
			'thumbnail_url' => '%s/images/headers/em9_kenjiplg_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_moodown' => array(
			'url' => '%s/images/headers/em9_moodown.jpg',
			'thumbnail_url' => '%s/images/headers/em9_moodown_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_mooout' => array(
			'url' => '%s/images/headers/em9_mooout.jpg',
			'thumbnail_url' => '%s/images/headers/em9_mooout_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_musicalchairs' => array(
			'url' => '%s/images/headers/em9_musicalchairs.jpg',
			'thumbnail_url' => '%s/images/headers/em9_musicalchairs_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_over' => array(
			'url' => '%s/images/headers/em9_over.jpg',
			'thumbnail_url' => '%s/images/headers/em9_over_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_perry' => array(
			'url' => '%s/images/headers/em9_perry.jpg',
			'thumbnail_url' => '%s/images/headers/em9_perry_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_plgswardson' => array(
			'url' => '%s/images/headers/em9_plgswardson.jpg',
			'thumbnail_url' => '%s/images/headers/em9_plgswardson_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_prisonfight' => array(
			'url' => '%s/images/headers/em9_prisonfight.jpg',
			'thumbnail_url' => '%s/images/headers/em9_prisonfight_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_rawdog' => array(
			'url' => '%s/images/headers/em9_rawdog.jpg',
			'thumbnail_url' => '%s/images/headers/em9_rawdog_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_rawdogswardson' => array(
			'url' => '%s/images/headers/em9_rawdogswardson.jpg',
			'thumbnail_url' => '%s/images/headers/em9_rawdogswardson_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_rudiger' => array(
			'url' => '%s/images/headers/em9_rudiger.jpg',
			'thumbnail_url' => '%s/images/headers/em9_rudiger_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_trophy' => array(
			'url' => '%s/images/headers/em9_trophy.jpg',
			'thumbnail_url' => '%s/images/headers/em9_trophy_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'em9_tully' => array(
			'url' => '%s/images/headers/em9_tully.jpg',
			'thumbnail_url' => '%s/images/headers/em9_tully_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'emxi' => array(
			'url' => '%s/images/headers/emxi.jpg',
			'thumbnail_url' => '%s/images/headers/emxi_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'emxi-biggest-loser' => array(
			'url' => '%s/images/headers/emxi-biggest-loser.jpg',
			'thumbnail_url' => '%s/images/headers/emxi-biggest-loser_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'emxi-joel-mills' => array(
			'url' => '%s/images/headers/emxi-joel-mills.jpg',
			'thumbnail_url' => '%s/images/headers/emxi-joel-mills_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'emxi-jude-taco' => array(
			'url' => '%s/images/headers/emxi-jude-taco.jpg',
			'thumbnail_url' => '%s/images/headers/emxi-jude-taco_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'emxi-kenda-sam' => array(
			'url' => '%s/images/headers/emxi-kenda-sam.jpg',
			'thumbnail_url' => '%s/images/headers/emxi-kenda-sam_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'emxi-shock-collar' => array(
			'url' => '%s/images/headers/emxi-shock-collar.jpg',
			'thumbnail_url' => '%s/images/headers/emxi-shock-collar_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'emxi-strip-fight' => array(
			'url' => '%s/images/headers/emxi-strip-fight.jpg',
			'thumbnail_url' => '%s/images/headers/emxi-strip-fight_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'No You Are', 'noyouare' )
		),
		'gabe_ruediger' => array(
			'url' => '%s/images/headers/gabe_ruediger.jpg',
			'thumbnail_url' => '%s/images/headers/gabe_ruediger_thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Gabe Ruediger', 'noyouare' )
		)
	) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // noyouare_setup

if ( ! function_exists( 'noyouare_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog.
 *
 * @since No You Are 1.0
 */
function noyouare_header_style() {
	$text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail.
	if ( $text_color == HEADER_TEXTCOLOR )
		return;

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css" id="noyouare-header-css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $text_color ) :
	?>
		#site-title,
		#site-description {
			position: absolute;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo $text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // noyouare_header_style

if ( ! function_exists( 'noyouare_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_theme_support('custom-header') in noyouare_setup().
 *
 * @since No You Are 1.0
 */
function noyouare_admin_header_style() {
?>
	<style type="text/css" id="noyouare-admin-header-css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	#headimg img {
		max-width: 1000px;
		height: auto;
		width: 100%;
	}
	</style>
<?php
}
endif; // noyouare_admin_header_style

if ( ! function_exists( 'noyouare_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_theme_support('custom-header') in noyouare_setup().
 *
 * @since No You Are 1.0
 */
function noyouare_admin_header_image() { ?>
	<div id="headimg">
		<?php
		$color = get_header_textcolor();
		$image = get_header_image();
		$style = 'display: none;';
		if ( $color && $color != 'blank' ) {
			$style = 'color: #' . $color . ';';
		}
		?>
		<h1 class="displaying-header-text"><a id="name" style="<?php echo esc_attr( $style ); ?>" onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>" tabindex="-1"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc" class="displaying-header-text" style="<?php echo esc_attr( $style ); ?>"><?php bloginfo( 'description' ); ?></div>
  		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // noyouare_admin_header_image

/**
 * Set the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove
 * the filter and add your own function tied to
 * the excerpt_length filter hook.
 *
 * @since No You Are 1.0
 *
 * @param int $length The number of excerpt characters.
 * @return int The filtered number of characters.
 */
function noyouare_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'noyouare_excerpt_length' );

if ( ! function_exists( 'noyouare_continue_reading_link' ) ) :
/**
 * Return a "Continue Reading" link for excerpts
 *
 * @since No You Are 1.0
 *
 * @return string The "Continue Reading" HTML link.
 */
function noyouare_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'noyouare' ) . '</a>';
}
endif; // noyouare_continue_reading_link

/**
 * Replace "[...]" in the Read More link with an ellipsis.
 *
 * The "[...]" is appended to automatically generated excerpts.
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since No You Are 1.0
 *
 * @param string $more The Read More text.
 * @return The filtered Read More text.
 */
function noyouare_auto_excerpt_more( $more ) {
	if ( ! is_admin() ) {
		return ' &hellip;' . noyouare_continue_reading_link();
	}
	return $more;
}
add_filter( 'excerpt_more', 'noyouare_auto_excerpt_more' );

/**
 * Add a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since No You Are 1.0
 *
 * @param string $output The "Continue Reading" link.
 * @return string The filtered "Continue Reading" link.
 */
function noyouare_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() && ! is_admin() ) {
		$output .= noyouare_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'noyouare_custom_excerpt_more' );

/**
 * Show a home link for the wp_nav_menu() fallback, wp_page_menu().
 *
 * @since No You Are 1.0
 *
 * @param array $args The page menu arguments. @see wp_page_menu()
 * @return array The filtered page menu arguments.
 */
function noyouare_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'noyouare_page_menu_args' );

/**
 * Register sidebars and widgetized areas.
 *
 * Also register the default Epherma widget.
 *
 * @since No You Are 1.0
 */
function noyouare_widgets_init() {

	register_widget( 'No_You_Are_Ephemera_Widget' );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'noyouare' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Showcase Sidebar', 'noyouare' ),
		'id' => 'sidebar-2',
		'description' => __( 'The sidebar for the optional Showcase Template', 'noyouare' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'noyouare' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'noyouare' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'noyouare' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'noyouare' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'noyouare' ),
		'id' => 'sidebar-5',
		'description' => __( 'An optional widget area for your site footer', 'noyouare' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'noyouare_widgets_init' );

if ( ! function_exists( 'noyouare_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable.
 *
 * @since No You Are 1.0
 *
 * @param string $html_id The HTML id attribute.
 */
function noyouare_content_nav( $html_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo esc_attr( $html_id ); ?>">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'noyouare' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'noyouare' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'noyouare' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}
endif; // noyouare_content_nav

/**
 * Return the first link from the post content. If none found, the
 * post permalink is used as a fallback.
 *
 * @since No You Are 1.0
 *
 * @uses get_url_in_content() to get the first URL from the post content.
 *
 * @return string The first link.
 */
function noyouare_get_first_url() {
	$content = get_the_content();
	$has_url = function_exists( 'get_url_in_content' ) ? get_url_in_content( $content ) : false;

	if ( ! $has_url )
		$has_url = noyouare_url_grabber();

	/** This filter is documented in wp-includes/link-template.php */
	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Return the URL for the first link found in the post content.
 *
 * @since No You Are 1.0
 *
 * @return string|bool URL or false when no link is present.
 */
function noyouare_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer.
 *
 * @since No You Are 1.0
 */
function noyouare_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . esc_attr( $class ) . '"';
}

if ( ! function_exists( 'noyouare_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own noyouare_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since No You Are 1.0
 *
 * @param object $comment The comment object.
 * @param array  $args    An array of comment arguments. @see get_comment_reply_link()
 * @param int    $depth   The depth of the comment.
 */
function noyouare_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'noyouare' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'noyouare' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'noyouare' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'noyouare' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'noyouare' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'noyouare' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'noyouare' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for noyouare_comment()

if ( ! function_exists( 'noyouare_posted_on' ) ) :
/**
 * Print HTML with meta information for the current post-date/time and author.
 *
 * Create your own noyouare_posted_on to override in a child theme
 *
 * @since No You Are 1.0
 */
function noyouare_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'noyouare' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'noyouare' ), get_the_author() ) ),
		get_the_author()
	);
}
endif;

/**
 * Add two classes to the array of body classes.
 *
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since No You Are 1.0
 *
 * @param array $classes Existing body classes.
 * @return array The filtered array of body classes.
 */
function noyouare_body_classes( $classes ) {

	if ( function_exists( 'is_multi_author' ) && ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';

	return $classes;
}
add_filter( 'body_class', 'noyouare_body_classes' );

/**
 * Retrieve the IDs for images in a gallery.
 *
 * @uses get_post_galleries() First, if available. Falls back to shortcode parsing,
 *                            then as last option uses a get_posts() call.
 *
 * @since No You Are 1.6
 *
 * @return array List of image IDs from the post gallery.
 */
function noyouare_get_gallery_images() {
	$images = array();

	if ( function_exists( 'get_post_galleries' ) ) {
		$galleries = get_post_galleries( get_the_ID(), false );
		if ( isset( $galleries[0]['ids'] ) )
		 	$images = explode( ',', $galleries[0]['ids'] );
	} else {
		$pattern = get_shortcode_regex();
		preg_match( "/$pattern/s", get_the_content(), $match );
		$atts = shortcode_parse_atts( $match[3] );
		if ( isset( $atts['ids'] ) )
			$images = explode( ',', $atts['ids'] );
	}

	if ( ! $images ) {
		$images = get_posts( array(
			'fields'         => 'ids',
			'numberposts'    => 999,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'post_mime_type' => 'image',
			'post_parent'    => get_the_ID(),
			'post_type'      => 'attachment',
		) );
	}

	return $images;
}
