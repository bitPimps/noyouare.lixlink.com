<?php
/**
 * No You Are Theme Options
 *
 * @package WordPress
 * @subpackage No_You_Are
 * @since No You Are 1.0
 */

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 *
 * @since No You Are 1.0
 *
 * @param string $hook_suffix An admin page's hook suffix.
 */
function noyouare_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'noyouare-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-04-28' );
	wp_enqueue_script( 'noyouare-theme-options', get_template_directory_uri() . '/inc/theme-options.js', array( 'farbtastic' ), '2011-06-10' );
	wp_enqueue_style( 'farbtastic' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'noyouare_admin_enqueue_scripts' );

/**
 * Register the form setting for our noyouare_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, noyouare_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * @since No You Are 1.0
 */
function noyouare_theme_options_init() {

	register_setting(
		'noyouare_options',       // Options group, see settings_fields() call in noyouare_theme_options_render_page()
		'noyouare_theme_options', // Database option, see noyouare_get_theme_options()
		'noyouare_theme_options_validate' // The sanitization callback, see noyouare_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see noyouare_theme_options_add_page()
	);

	// Register our individual settings fields
	add_settings_field(
		'color_scheme',                             // Unique identifier for the field for this section
		__( 'Color Scheme', 'noyouare' ),       // Setting field label
		'noyouare_settings_field_color_scheme', // Function that renders the settings field
		'theme_options',                            // Menu slug, used to uniquely identify the page; see noyouare_theme_options_add_page()
		'general'                                   // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field( 'link_color', __( 'Link Color',     'noyouare' ), 'noyouare_settings_field_link_color', 'theme_options', 'general' );
	add_settings_field( 'layout',     __( 'Default Layout', 'noyouare' ), 'noyouare_settings_field_layout',     'theme_options', 'general' );
}
add_action( 'admin_init', 'noyouare_theme_options_init' );

/**
 * Change the capability required to save the 'noyouare_options' options group.
 *
 * @see noyouare_theme_options_init()     First parameter to register_setting() is the name of the options group.
 * @see noyouare_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function noyouare_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_noyouare_options', 'noyouare_option_page_capability' );

/**
 * Add a theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since No You Are 1.0
 */
function noyouare_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'noyouare' ),   // Name of page
		__( 'Theme Options', 'noyouare' ),   // Label in menu
		'edit_theme_options',                    // Capability required
		'theme_options',                         // Menu slug, used to uniquely identify the page
		'noyouare_theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;

	add_action( "load-$theme_page", 'noyouare_theme_options_help' );
}
add_action( 'admin_menu', 'noyouare_theme_options_add_page' );

function noyouare_theme_options_help() {

	$help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, No You Are, provides the following Theme Options:', 'noyouare' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Color Scheme</strong>: You can choose a color palette of "Light" (light background with dark text) or "Dark" (dark background with light text) for your site.', 'noyouare' ) . '</li>' .
				'<li>' . __( '<strong>Link Color</strong>: You can choose the color used for text links on your site. You can enter the HTML color or hex code, or you can choose visually by clicking the "Select a Color" button to pick from a color wheel.', 'noyouare' ) . '</li>' .
				'<li>' . __( '<strong>Default Layout</strong>: You can choose if you want your site&#8217;s default layout to have a sidebar on the left, the right, or not at all.', 'noyouare' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'noyouare' ) . '</p>';

	$sidebar = '<p><strong>' . __( 'For more information:', 'noyouare' ) . '</strong></p>' .
		'<p>' . __( '<a href="https://codex.wordpress.org/Appearance_Theme_Options_Screen" target="_blank">Documentation on Theme Options</a>', 'noyouare' ) . '</p>' .
		'<p>' . __( '<a href="https://wordpress.org/support/" target="_blank">Support Forums</a>', 'noyouare' ) . '</p>';

	$screen = get_current_screen();

	if ( method_exists( $screen, 'add_help_tab' ) ) {
		// WordPress 3.3.0
		$screen->add_help_tab( array(
			'title' => __( 'Overview', 'noyouare' ),
			'id' => 'theme-options-help',
			'content' => $help,
			)
		);

		$screen->set_help_sidebar( $sidebar );
	} else {
		// WordPress 3.2.0
		add_contextual_help( $screen, $help . $sidebar );
	}
}

/**
 * Return an array of color schemes registered for No You Are.
 *
 * @since No You Are 1.0
 */
function noyouare_color_schemes() {
	$color_scheme_options = array(
		'light' => array(
			'value' => 'light',
			'label' => __( 'Light', 'noyouare' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/light.png',
			'default_link_color' => '#1b8be0',
		),
		'dark' => array(
			'value' => 'dark',
			'label' => __( 'Dark', 'noyouare' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/dark.png',
			'default_link_color' => '#e4741f',
		),
	);

	/**
	 * Filter the No You Are color scheme options.
	 *
	 * @since No You Are 1.0
	 *
	 * @param array $color_scheme_options An associative array of color scheme options.
	 */
	return apply_filters( 'noyouare_color_schemes', $color_scheme_options );
}

/**
 * Return an array of layout options registered for No You Are.
 *
 * @since No You Are 1.0
 */
function noyouare_layouts() {
	$layout_options = array(
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => __( 'Content on left', 'noyouare' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar.png',
		),
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => __( 'Content on right', 'noyouare' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content.png',
		),
		'content' => array(
			'value' => 'content',
			'label' => __( 'One-column, no sidebar', 'noyouare' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content.png',
		),
	);

	/**
	 * Filter the No You Are layout options.
	 *
	 * @since No You Are 1.0
	 *
	 * @param array $layout_options An associative array of layout options.
	 */
	return apply_filters( 'noyouare_layouts', $layout_options );
}

/**
 * Return the default options for No You Are.
 *
 * @since No You Are 1.0
 *
 * @return array An array of default theme options.
 */
function noyouare_get_default_theme_options() {
	$default_theme_options = array(
		'color_scheme' => 'light',
		'link_color'   => noyouare_get_default_link_color( 'light' ),
		'theme_layout' => 'content-sidebar',
	);

	if ( is_rtl() )
 		$default_theme_options['theme_layout'] = 'sidebar-content';

	/**
	 * Filter the No You Are default options.
	 *
	 * @since No You Are 1.0
	 *
	 * @param array $default_theme_options An array of default theme options.
	 */
	return apply_filters( 'noyouare_default_theme_options', $default_theme_options );
}

/**
 * Return the default link color for No You Are, based on color scheme.
 *
 * @since No You Are 1.0
 *
 * @param string $color_scheme Optional. Color scheme.
 *                             Default null (or the active color scheme).
 * @return string The default link color.
*/
function noyouare_get_default_link_color( $color_scheme = null ) {
	if ( null === $color_scheme ) {
		$options = noyouare_get_theme_options();
		$color_scheme = $options['color_scheme'];
	}

	$color_schemes = noyouare_color_schemes();
	if ( ! isset( $color_schemes[ $color_scheme ] ) )
		return false;

	return $color_schemes[ $color_scheme ]['default_link_color'];
}

/**
 * Return the options array for No You Are.
 *
 * @since No You Are 1.0
 */
function noyouare_get_theme_options() {
	return get_option( 'noyouare_theme_options', noyouare_get_default_theme_options() );
}

/**
 * Render the Color Scheme setting field.
 *
 * @since No You Are 1.3
 */
function noyouare_settings_field_color_scheme() {
	$options = noyouare_get_theme_options();

	foreach ( noyouare_color_schemes() as $scheme ) {
	?>
	<div class="layout image-radio-option color-scheme">
	<label class="description">
		<input type="radio" name="noyouare_theme_options[color_scheme]" value="<?php echo esc_attr( $scheme['value'] ); ?>" <?php checked( $options['color_scheme'], $scheme['value'] ); ?> />
		<input type="hidden" id="default-color-<?php echo esc_attr( $scheme['value'] ); ?>" value="<?php echo esc_attr( $scheme['default_link_color'] ); ?>" />
		<span>
			<img src="<?php echo esc_url( $scheme['thumbnail'] ); ?>" width="136" height="122" alt="" />
			<?php echo esc_html( $scheme['label'] ); ?>
		</span>
	</label>
	</div>
	<?php
	}
}

/**
 * Render the Link Color setting field.
 *
 * @since No You Are 1.3
 */
function noyouare_settings_field_link_color() {
	$options = noyouare_get_theme_options();
	?>
	<input type="text" name="noyouare_theme_options[link_color]" id="link-color" value="<?php echo esc_attr( $options['link_color'] ); ?>" />
	<a href="#" class="pickcolor hide-if-no-js" id="link-color-example"></a>
	<input type="button" class="pickcolor button hide-if-no-js" value="<?php esc_attr_e( 'Select a Color', 'noyouare' ); ?>" />
	<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
	<br />
	<span><?php printf( __( 'Default color: %s', 'noyouare' ), '<span id="default-color">' . noyouare_get_default_link_color( $options['color_scheme'] ) . '</span>' ); ?></span>
	<?php
}

/**
 * Render the Layout setting field.
 *
 * @since No You Are 1.3
 */
function noyouare_settings_field_layout() {
	$options = noyouare_get_theme_options();
	foreach ( noyouare_layouts() as $layout ) {
		?>
		<div class="layout image-radio-option theme-layout">
		<label class="description">
			<input type="radio" name="noyouare_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
			<span>
				<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="" />
				<?php echo esc_html( $layout['label'] ); ?>
			</span>
		</label>
		</div>
		<?php
	}
}

/**
 * Render the options page for No You Are.
 *
 * @since No You Are 1.2
 */
function noyouare_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
		<h2><?php printf( __( '%s Theme Options', 'noyouare' ), $theme_name ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'noyouare_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input.
 *
 * Accepts an array, return a sanitized array.
 *
 * @see noyouare_theme_options_init()
 * @todo set up Reset Options action
 *
 * @since No You Are 1.0
 *
 * @param array $input An array of form input.
 */
function noyouare_theme_options_validate( $input ) {
	$output = $defaults = noyouare_get_default_theme_options();

	// Color scheme must be in our array of color scheme options
	if ( isset( $input['color_scheme'] ) && array_key_exists( $input['color_scheme'], noyouare_color_schemes() ) )
		$output['color_scheme'] = $input['color_scheme'];

	// Our defaults for the link color may have changed, based on the color scheme.
	$output['link_color'] = $defaults['link_color'] = noyouare_get_default_link_color( $output['color_scheme'] );

	// Link color must be 3 or 6 hexadecimal characters
	if ( isset( $input['link_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['link_color'] ) )
		$output['link_color'] = '#' . strtolower( ltrim( $input['link_color'], '#' ) );

	// Theme layout must be in our array of theme layout options
	if ( isset( $input['theme_layout'] ) && array_key_exists( $input['theme_layout'], noyouare_layouts() ) )
		$output['theme_layout'] = $input['theme_layout'];

	/**
	 * Filter the No You Are sanitized form input array.
	 *
	 * @since No You Are 1.0
	 *
	 * @param array $output   An array of sanitized form output.
	 * @param array $input    An array of un-sanitized form input.
	 * @param array $defaults An array of default theme options.
	 */
	return apply_filters( 'noyouare_theme_options_validate', $output, $input, $defaults );
}

/**
 * Enqueue the styles for the current color scheme.
 *
 * @since No You Are 1.0
 */
function noyouare_enqueue_color_scheme() {
	$options = noyouare_get_theme_options();
	$color_scheme = $options['color_scheme'];

	if ( 'dark' == $color_scheme )
		wp_enqueue_style( 'dark', get_template_directory_uri() . '/colors/dark.css', array(), null );

	/**
	 * Fires after the styles for the No You Are color scheme are enqueued.
	 *
	 * @since No You Are 1.0
	 *
	 * @param string $color_scheme The color scheme.
	 */
	do_action( 'noyouare_enqueue_color_scheme', $color_scheme );
}
add_action( 'wp_enqueue_scripts', 'noyouare_enqueue_color_scheme' );

/**
 * Add a style block to the theme for the current link color.
 *
 * This function is attached to the wp_head action hook.
 *
 * @since No You Are 1.0
 */
function noyouare_print_link_color_style() {
	$options = noyouare_get_theme_options();
	$link_color = $options['link_color'];

	$default_options = noyouare_get_default_theme_options();

	// Don't do anything if the current link color is the default.
	if ( $default_options['link_color'] == $link_color )
		return;
?>
	<style>
		/* Link color */
		a,
		#site-title a:focus,
		#site-title a:hover,
		#site-title a:active,
		.entry-title a:hover,
		.entry-title a:focus,
		.entry-title a:active,
		.widget_noyouare_ephemera .comments-link a:hover,
		section.recent-posts .other-recent-posts a[rel="bookmark"]:hover,
		section.recent-posts .other-recent-posts .comments-link a:hover,
		.format-image footer.entry-meta a:hover,
		#site-generator a:hover {
			color: <?php echo $link_color; ?>;
		}
		section.recent-posts .other-recent-posts .comments-link a:hover {
			border-color: <?php echo $link_color; ?>;
		}
		article.feature-image.small .entry-summary p a:hover,
		.entry-header .comments-link a:hover,
		.entry-header .comments-link a:focus,
		.entry-header .comments-link a:active,
		.feature-slider a.active {
			background-color: <?php echo $link_color; ?>;
		}
	</style>
<?php
}
add_action( 'wp_head', 'noyouare_print_link_color_style' );

/**
 * Add No You Are layout classes to the array of body classes.
 *
 * @since No You Are 1.0
 *
 * @param array $existing_classes An array of existing body classes.
 */
function noyouare_layout_classes( $existing_classes ) {
	$options = noyouare_get_theme_options();
	$current_layout = $options['theme_layout'];

	if ( in_array( $current_layout, array( 'content-sidebar', 'sidebar-content' ) ) )
		$classes = array( 'two-column' );
	else
		$classes = array( 'one-column' );

	if ( 'content-sidebar' == $current_layout )
		$classes[] = 'right-sidebar';
	elseif ( 'sidebar-content' == $current_layout )
		$classes[] = 'left-sidebar';
	else
		$classes[] = $current_layout;

	/**
	 * Filter the No You Are layout body classes.
	 *
	 * @since No You Are 1.0
	 *
	 * @param array  $classes        An array of body classes.
	 * @param string $current_layout The current theme layout.
	 */
	$classes = apply_filters( 'noyouare_layout_classes', $classes, $current_layout );

	return array_merge( $existing_classes, $classes );
}
add_filter( 'body_class', 'noyouare_layout_classes' );

/**
 * Implements No You Are theme options into Customizer
 *
 * @since No You Are 1.3
 *
 * @param object $wp_customize Customizer object.
 */
function noyouare_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '#site-title a',
			'container_inclusive' => false,
			'render_callback' => 'noyouare_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '#site-description',
			'container_inclusive' => false,
			'render_callback' => 'noyouare_customize_partial_blogdescription',
		) );
	}

	$options  = noyouare_get_theme_options();
	$defaults = noyouare_get_default_theme_options();

	$wp_customize->add_setting( 'noyouare_theme_options[color_scheme]', array(
		'default'    => $defaults['color_scheme'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );

	$schemes = noyouare_color_schemes();
	$choices = array();
	foreach ( $schemes as $scheme ) {
		$choices[ $scheme['value'] ] = $scheme['label'];
	}

	$wp_customize->add_control( 'noyouare_color_scheme', array(
		'label'    => __( 'Color Scheme', 'noyouare' ),
		'section'  => 'colors',
		'settings' => 'noyouare_theme_options[color_scheme]',
		'type'     => 'radio',
		'choices'  => $choices,
		'priority' => 5,
	) );

	// Link Color (added to Color Scheme section in Customizer)
	$wp_customize->add_setting( 'noyouare_theme_options[link_color]', array(
		'default'           => noyouare_get_default_link_color( $options['color_scheme'] ),
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_hex_color',
		'capability'        => 'edit_theme_options',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'    => __( 'Link Color', 'noyouare' ),
		'section'  => 'colors',
		'settings' => 'noyouare_theme_options[link_color]',
	) ) );

	// Default Layout
	$wp_customize->add_section( 'noyouare_layout', array(
		'title'    => __( 'Layout', 'noyouare' ),
		'priority' => 50,
	) );

	$wp_customize->add_setting( 'noyouare_theme_options[theme_layout]', array(
		'type'              => 'option',
		'default'           => $defaults['theme_layout'],
		'sanitize_callback' => 'sanitize_key',
	) );

	$layouts = noyouare_layouts();
	$choices = array();
	foreach ( $layouts as $layout ) {
		$choices[ $layout['value'] ] = $layout['label'];
	}

	$wp_customize->add_control( 'noyouare_theme_options[theme_layout]', array(
		'section'    => 'noyouare_layout',
		'type'       => 'radio',
		'choices'    => $choices,
	) );
}
add_action( 'customize_register', 'noyouare_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @since No You Are 2.4
 * @see noyouare_customize_register()
 *
 * @return void
 */
function noyouare_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since No You Are 2.4
 * @see noyouare_customize_register()
 *
 * @return void
 */
function noyouare_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Bind JS handlers to make Customizer preview reload changes asynchronously.
 *
 * Used with blogname and blogdescription.
 *
 * @since No You Are 1.3
 */
function noyouare_customize_preview_js() {
	wp_enqueue_script( 'noyouare-customizer', get_template_directory_uri() . '/inc/theme-customizer.js', array( 'customize-preview' ), '20120523', true );
}
add_action( 'customize_preview_init', 'noyouare_customize_preview_js' );
