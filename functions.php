<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

/** Load widgets from /lib/widgets/ */
foreach ( glob( CHILD_DIR . "/lib/widgets/*-widget.php" ) as $file ) {
	require_once $file;
}

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Theme001' );
define( 'CHILD_THEME_URL', 'http://yoast.com/' );
define( 'CHILD_THEME_VERSION', '0.0.1' );

//* Enqueue Lato Google font
function genesis_sample_google_fonts() {
	wp_enqueue_style( 'google-font-lato', '//fonts.googleapis.com/css?family=Lato:300,700', array(), CHILD_THEME_VERSION );
}

add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

// Used for defaults in for instance the banner widget.
define( 'YST_SIDEBAR_WIDTH', 261 );

// Activate blogroll widget
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// Disable site layouts that must not be used
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

/** Register widget areas */
genesis_register_sidebar( array(
	'id'          => 'yoast-after-header-1',
	'name'        => __( 'After Header 1', 'yoast' ),
	'description' => __( 'After Header 1 widget area.', 'yoast' ),
) );

genesis_register_sidebar( array(
	'id'          => 'yoast-after-header-2',
	'name'        => __( 'After Header 2', 'yoast' ),
	'description' => __( 'After Header 2 widget area.', 'yoast' ),
) );

genesis_register_sidebar( array(
	'id'          => 'yoast-after-header-3',
	'name'        => __( 'After Header 3', 'yoast' ),
	'description' => __( 'After Header 3 widget area.', 'yoast' ),
) );

genesis_register_sidebar( array(
	'id'          => 'yoast-tagline-after-header',
	'name'        => __( 'Tagline After Header', 'yoast' ),
	'description' => __( 'Tagline After Header widget area.', 'yoast' ),
) );


/**
 * Add yoast-after-header widget support for site. If widget not active, don't display
 *
 */
function yoast_after_header_genesis() {

	echo '<div id="yoast-after-header-container"><div class="wrap">';

	// Change 'true' to specific page to show/hide widget area on that page
	if ( true ) {
		genesis_widget_area( 'yoast-after-header-1', array(
			'before' => '<div id="yoast-after-header-1" class="yoast-after-header-widget">',
			'after'  => '</div>',
		) );
		genesis_widget_area( 'yoast-after-header-2', array(
			'before' => '<div id="yoast-after-header-2" class="yoast-after-header-widget">',
			'after'  => '</div>',
		) );
		genesis_widget_area( 'yoast-after-header-3', array(
			'before' => '<div id="yoast-after-header-3" class="yoast-after-header-widget">',
			'after'  => '</div>',
		) );
		echo '<div class="clearfloat"></div></div></div>';
	}

	if ( is_active_sidebar( 'yoast-tagline-after-header' ) ) {
		echo '<div id="yoast-tagline-after-header-container"><div class="wrap">';
		genesis_widget_area( 'yoast-tagline-after-header', array(
			'before' => '<div id="yoast-tagline-after-header" class="yoast-tagline-after-header-widget">',
			'after'  => '</div>',
		) );
		echo '</div></div>';

	}
}

add_action( 'genesis_after_header', 'yoast_after_header_genesis' );

// Add Read More Link to Excerpts
add_filter( 'excerpt_more', 'get_read_more_link' );
add_filter( 'the_content_more_link', 'get_read_more_link' );
function get_read_more_link() {
	return '...&nbsp;<div class="exerptreadmore"><a href="' . get_permalink() . '">Read more</a></div>';
}

/**
 * Test adding widget on Theme-change
 */
add_action( 'after_switch_theme', 'yst_add_widget_after_activating_theme', 10, 2 );

function yst_add_widget_after_activating_theme( $oldname, $oldtheme = false ) {
	$sidebar_id                    = 'yoast-after-header-3';
	$sidebars_widgets              = get_option( 'sidebars_widgets' );
	$id                            = count( $sidebars_widgets ) + 1;
	$sidebars_widgets[$sidebar_id] = array( "text-" . $id );

	$ops      = get_option( 'widget_text' );
	$ops[$id] = array(
		'title' => 'Automatic Widget',
		'text'  => 'Works!',
	);
	update_option( 'widget_text', $ops );
	update_option( 'sidebars_widgets', $sidebars_widgets );
}