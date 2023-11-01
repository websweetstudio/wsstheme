<?php
/**
 * Wss Theme Customizer
 *
 * @package Wss
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
if ( ! function_exists( 'wss_customize_register' ) ) {
	/**
	 * Register basic customizer support.
	 *
	 * @param object $wp_customize Customizer reference.
	 */
	function wss_customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	}
}
add_action( 'customize_register', 'wss_customize_register' );

if ( ! function_exists( 'wss_theme_customize_register' ) ) {
	/**
	 * Register individual settings through customizer's API.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer reference.
	 */
	function wss_theme_customize_register( $wp_customize ) {

		// Theme layout settings.
		$wp_customize->add_section(
			'wss_theme_layout_options',
			array(
				'title'       => __( 'Theme Layout Settings', 'wss' ),
				'capability'  => 'edit_theme_options',
				'description' => __( 'Container width and sidebar defaults', 'wss' ),
				'priority'    => apply_filters( 'wss_theme_layout_options_priority', 160 ),
			)
		);

		/**
		 * Select sanitization function
		 *
		 * @param string               $input   Slug to sanitize.
		 * @param WP_Customize_Setting $setting Setting instance.
		 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
		 */
		function wss_theme_slug_sanitize_select( $input, $setting ) {

			// Ensure input is a slug (lowercase alphanumeric characters, dashes and underscores are allowed only).
			$input = sanitize_key( $input );

			// Get the list of possible select options.
			$choices = $setting->manager->get_control( $setting->id )->choices;

			// If the input is a valid key, return it; otherwise, return the default.
			return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

		}

		$wp_customize->add_setting(
			'wss_container_type',
			array(
				'default'           => 'container',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wss_theme_slug_sanitize_select',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wss_container_type',
				array(
					'label'       => __( 'Container Width', 'wss' ),
					'description' => __( 'Choose between Bootstrap\'s container and container-fluid', 'wss' ),
					'section'     => 'wss_theme_layout_options',
					'settings'    => 'wss_container_type',
					'type'        => 'select',
					'choices'     => array(
						'container'       => __( 'Fixed width container', 'wss' ),
						'container-fluid' => __( 'Full width container', 'wss' ),
					),
					'priority'    => apply_filters( 'wss_container_type_priority', 10 ),
				)
			)
		);

		$wp_customize->add_setting(
			'wss_header_position',
			array(
				'default'           => 'position-relative',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wss_theme_slug_sanitize_select',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wss_header_position',
				array(
					'label'             => __( 'Header Position', 'wss' ),
					'description'       => __(
						'Set header\'s default position. Can either be: fixed, relative, or static.',
						'wss'
					),
					'section'           => 'wss_theme_layout_options',
					'settings'          => 'wss_header_position',
					'type'              => 'select',
					'sanitize_callback' => 'wss_theme_slug_sanitize_select',
					'choices'           => array(
						'position-relative' => __( 'Relative', 'wss' ),
						'fixed-top'  => __( 'Fixed', 'wss' ),
						'sticky-top'  => __( 'Static', 'wss' ),
					),
					'priority'          => apply_filters( 'wss_sidebar_position_priority', 20 ),
				)
			)
		);

		$wp_customize->add_setting(
			'wss_navbar_type',
			array(
				'default'           => 'offcanvas',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wss_navbar_type',
				array(
					'label'             => __( 'Responsive Navigation Type', 'wss' ),
					'description'       => __(
						'Choose between an expanding and collapsing navbar or an offcanvas drawer.',
						'wss'
					),
					'section'           => 'wss_theme_layout_options',
					'settings'          => 'wss_navbar_type',
					'type'              => 'select',
					'sanitize_callback' => 'wss_theme_slug_sanitize_select',
					'choices'           => array(
						'collapse'  => __( 'Collapse', 'wss' ),
						'offcanvas' => __( 'Offcanvas', 'wss' ),
					),
					'priority'          => apply_filters( 'wss_navbar_type_priority', 20 ),
				)
			)
		);

		$wp_customize->add_setting(
			'wss_sidebar_position',
			array(
				'default'           => 'right',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wss_sidebar_position',
				array(
					'label'             => __( 'Sidebar Positioning', 'wss' ),
					'description'       => __(
						'Set sidebar\'s default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.',
						'wss'
					),
					'section'           => 'wss_theme_layout_options',
					'settings'          => 'wss_sidebar_position',
					'type'              => 'select',
					'sanitize_callback' => 'wss_theme_slug_sanitize_select',
					'choices'           => array(
						'right' => __( 'Right sidebar', 'wss' ),
						'left'  => __( 'Left sidebar', 'wss' ),
						'both'  => __( 'Left & Right sidebars', 'wss' ),
						'none'  => __( 'No sidebar', 'wss' ),
					),
					'priority'          => apply_filters( 'wss_sidebar_position_priority', 20 ),
				)
			)
		);

		$wp_customize->add_setting(
			'wss_site_info_override',
			array(
				'default'           => '',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wp_kses_post',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wss_site_info_override',
				array(
					'label'       => __( 'Footer Site Info', 'wss' ),
					'description' => __( 'Override site info located at the footer of the page.', 'wss' ),
					'section'     => 'wss_theme_layout_options',
					'settings'    => 'wss_site_info_override',
					'type'        => 'textarea',
					'priority'    => 20,
				)
			)
		);
		

		// Add typography settings.
		$wp_customize->add_section(
			'wss_google_fonts_section',
			array(
				'title'       => __( 'Typography Setting', 'wss' ),
				'capability'  => 'edit_theme_options',
				'description' => __( 'Container width and sidebar defaults', 'wss' ),
				'priority'    => apply_filters( 'wss_google_fonts_section_priority', 50 ),
			)
		);

		//Sanitizes Fonts
		function wss_sanitize_fonts( $input ) {
			$valid = array(
				'Poppins :400italic,700italic,400,700' => 'Poppins',
				'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',
				'Open Sans:400italic,700italic,400,700' => 'Open Sans',
				'Oswald:400,700' => 'Oswald',
				'Playfair Display:400,700,400italic' => 'Playfair Display',
				'Montserrat:400,700' => 'Montserrat',
				'Raleway:400,700' => 'Raleway',
				'Droid Sans:400,700' => 'Droid Sans',
				'Lato:400,700,400italic,700italic' => 'Lato',
				'Arvo:400,700,400italic,700italic' => 'Arvo',
				'Lora:400,700,400italic,700italic' => 'Lora',
				'Merriweather:400,300italic,300,400italic,700,700italic' => 'Merriweather',
				'Oxygen:400,300,700' => 'Oxygen',
				'PT Serif:400,700' => 'PT Serif',
				'PT Sans:400,700,400italic,700italic' => 'PT Sans',
				'PT Sans Narrow:400,700' => 'PT Sans Narrow',
				'Cabin:400,700,400italic' => 'Cabin',
				'Fjalla One:400' => 'Fjalla One',
				'Francois One:400' => 'Francois One',
				'Josefin Sans:400,300,600,700' => 'Josefin Sans',
				'Libre Baskerville:400,400italic,700' => 'Libre Baskerville',
				'Arimo:400,700,400italic,700italic' => 'Arimo',
				'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
				'Bitter:400,700,400italic' => 'Bitter',
				'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
				'Roboto:400,400italic,700,700italic' => 'Roboto',
				'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
				'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
				'Roboto Slab:400,700' => 'Roboto Slab',
				'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
				'Rokkitt:400' => 'Rokkitt',
			);

			if ( array_key_exists( $input, $valid ) ) {
				return $input;
			} else {
				return '';
			}
		}

		$font_choices = array(
			'Poppins :400italic,700italic,400,700' => 'Poppins',
			'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',
			'Open Sans:400italic,700italic,400,700' => 'Open Sans',
			'Oswald:400,700' => 'Oswald',
			'Playfair Display:400,700,400italic' => 'Playfair Display',
			'Montserrat:400,700' => 'Montserrat',
			'Raleway:400,700' => 'Raleway',
			'Droid Sans:400,700' => 'Droid Sans',
			'Lato:400,700,400italic,700italic' => 'Lato',
			'Arvo:400,700,400italic,700italic' => 'Arvo',
			'Lora:400,700,400italic,700italic' => 'Lora',
			'Merriweather:400,300italic,300,400italic,700,700italic' => 'Merriweather',
			'Oxygen:400,300,700' => 'Oxygen',
			'PT Serif:400,700' => 'PT Serif',
			'PT Sans:400,700,400italic,700italic' => 'PT Sans',
			'PT Sans Narrow:400,700' => 'PT Sans Narrow',
			'Cabin:400,700,400italic' => 'Cabin',
			'Fjalla One:400' => 'Fjalla One',
			'Francois One:400' => 'Francois One',
			'Josefin Sans:400,300,600,700' => 'Josefin Sans',
			'Libre Baskerville:400,400italic,700' => 'Libre Baskerville',
			'Arimo:400,700,400italic,700italic' => 'Arimo',
			'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
			'Bitter:400,700,400italic' => 'Bitter',
			'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
			'Roboto:400,400italic,700,700italic' => 'Roboto',
			'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
			'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
			'Roboto Slab:400,700' => 'Roboto Slab',
			'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
			'Rokkitt:400' => 'Rokkitt',
		);

		$wp_customize->add_setting(
			'wss_headings_fonts', 
			array(
				'default'           => 'Open Sans:400italic,700italic,400,700',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wss_sanitize_fonts',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wss_headings_fonts',
				array(
					'label'       => __( 'Site Typograpy', 'wss' ),
					'description' => __( 'Select your site typography.', 'wss' ),
					'section'     => 'wss_google_fonts_section',
					'settings'    => 'wss_headings_fonts',
					'type'        => 'select',
					'priority'    => 20,
					'choices' => $font_choices
				)
			)
		);

		$wp_customize->add_setting(
			'wss_body_fonts', 
			array(
				'default'           => 'Open Sans:400italic,700italic,400,700',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wss_sanitize_fonts',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wss_body_fonts',
				array(
					'label'       => __( 'Body Typography', 'wss' ),
					'description' => __( 'Select your body typography.', 'wss' ),
					'section'     => 'wss_google_fonts_section',
					'settings'    => 'wss_body_fonts',
					'type'        => 'select',
					'priority'    => 30,
					'choices' => $font_choices
				)
			)
		);
	}
} // End of if function_exists( 'wss_theme_customize_register' ).
add_action( 'customize_register', 'wss_theme_customize_register' );


function wss_customizer_scripts() {
	$headings_font = esc_html(get_theme_mod('wss_headings_fonts'));
	$body_font = esc_html(get_theme_mod('wss_body_fonts'));

	if( $headings_font ) {
		wp_enqueue_style( 'wss-headings-fonts', '//fonts.googleapis.com/css?family='. $headings_font );
	} else {
		wp_enqueue_style( 'wss-source-sans', '//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
	}
	if( $body_font ) {
		wp_enqueue_style( 'wss-body-fonts', '//fonts.googleapis.com/css?family='. $body_font );
	} else {
		wp_enqueue_style( 'wss-source-body', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,700,600');
	}
}
add_action( 'wp_enqueue_scripts', 'wss_customizer_scripts' );

/*
 * Props to the BLDR Theme: https://wordpress.org/themes/bldr/
 * */
function wss_custom_styles($custom) {

	//Fonts
	$headings_font = esc_html(get_theme_mod('wss_headings_fonts'));
	$body_font = esc_html(get_theme_mod('wss_body_fonts'));

	if ( $headings_font ) {
		$font_pieces = explode(":", $headings_font);
		$custom .= "h1, h2, h3, h4, h5, h6 { font-family: {$font_pieces[0]}; }"."\n";
	}

	if ( $body_font ) {
		$font_pieces = explode(":", $body_font);
		$custom .= "body, button, input, select, textarea { font-family: {$font_pieces[0]}; }"."\n";
	}

	//Output all the styles
    wp_register_style( 'wss-inline-style', false );
    wp_enqueue_style( 'wss-inline-style' );
    wp_add_inline_style( 'wss-inline-style', $custom );
}
add_action( 'wp_enqueue_scripts', 'wss_custom_styles' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
if ( ! function_exists( 'wss_customize_preview_js' ) ) {
	/**
	 * Setup JS integration for live previewing.
	 */
	function wss_customize_preview_js() {
		wp_enqueue_script(
			'wss_customizer',
			get_template_directory_uri() . '/js/customizer.js',
			array( 'customize-preview' ),
			'20130508',
			true
		);
	}
}
add_action( 'customize_preview_init', 'wss_customize_preview_js' );

/**
 * Loads javascript for conditionally showing customizer controls.
 */
if ( ! function_exists( 'wss_customize_controls_js' ) ) {
	/**
	 * Setup JS integration for live previewing.
	 */
	function wss_customize_controls_js() {
		wp_enqueue_script(
			'wss_customizer',
			get_template_directory_uri() . '/js/customizer-controls.js',
			array( 'customize-preview' ),
			'20130508',
			true
		);
	}
}
add_action( 'customize_controls_enqueue_scripts', 'wss_customize_controls_js' );



if ( ! function_exists( 'wss_default_navbar_type' ) ) {
	/**
	 * Overrides the responsive navbar type for Bootstrap 4
	 *
	 * @param string $current_mod
	 * @return string
	 */
	function wss_default_navbar_type( $current_mod ) {
		return $current_mod;
	}
}
add_filter( 'theme_mod_wss_navbar_type', 'wss_default_navbar_type', 20 );
