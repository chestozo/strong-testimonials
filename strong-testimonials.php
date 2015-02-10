<?php
/**
 * Plugin Name: Strong Testimonials
 * Plugin URI: http://www.wpmission.com/plugins/strong-testimonials/
 * Description: A powerful testimonial manager.
 * Author: Chris Dillon
 * Version: 1.16
 * Forked From: GC Testimonials version 1.3.2 by Erin Garscadden
 * Author URI: http://www.wpmission.com/contact
 * Text Domain: strong-testimonials
 * Domain Path: /languages
 * Requires: 3.5 or higher
 * License: GPLv3 or later
 *
 * Copyright 2014  Chris Dillon  chris@wpmission.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 * Setup
 */
define( 'WPMTST_DIR', plugin_dir_url( __FILE__ ) );
define( 'WPMTST_INC', plugin_dir_path( __FILE__ ) . 'includes/' );
define( 'WPMTST_TPL', plugin_dir_path( __FILE__ ) . 'templates/plugin/' );
define( 'WPMTST_TPL_URI', plugin_dir_url( __FILE__ ) . 'templates/plugin/' );

global $strong_testimonials_db_version;
$strong_testimonials_db_version = '1.0';


/**
 * Plugin action links
 */
function wpmtst_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$settings_link = '<a href="' . admin_url( 'edit.php?post_type=wpm-testimonial&page=settings' ) . '">' 
				. __( 'Settings', 'strong-testimonials' ) . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'wpmtst_plugin_action_links', 10, 2 );


/**
 * Text domain
 */
function wpmtst_textdomain() {
	$success = load_plugin_textdomain( 'strong-testimonials', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wpmtst_textdomain' );


/**
 * Plugin activation
 */
register_activation_hook( __FILE__, 'wpmtst_update_tables' );
// register_activation_hook( __FILE__, 'wpmtst_add_data' );
register_activation_hook( __FILE__, 'wpmtst_register_cpt' );
register_activation_hook( __FILE__, 'wpmtst_flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'wpmtst_flush_rewrite_rules' );

function wpmtst_flush_rewrite_rules() {
	flush_rewrite_rules();
}


/**
 * Add tables for Blocks.
 *
 * @since 1.15.0
 */
function wpmtst_update_tables() {
	global $wpdb;
	global $strong_testimonials_db_version;
	
	$charset_collate = $wpdb->get_charset_collate();
	
	$table_name = $wpdb->prefix . 'strong_blocks';
	
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(100) NOT NULL,
		value text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	add_option( "strong_testimonials_db_version", $strong_testimonials_db_version );
}


/**
 * Default Blocks.
 *
 * @since 1.15.0
 */
function wpmtst_add_data() {
	global $wpdb;
	
	$defaults = array(
			'mode'           => 'display',
			'category'       => 'all',
			'class'          => '',
			'template'       => 'default/testimonials-page.php',
			'all'            => true,
			'count'          => 5,
			'pagination'     => false,
			'per_page'       => 5,
			'nav'            => 'after',
			'id'             => '',
			'order'          => 'oldest',
			'title'          => true,
			'thumbnail'      => true,
			'thumbnail_size' => 'thumbnail',
			'content'        => 'entire',
			'length'         => 200,
			'more_post'      => false,
			'more_text'      => 'Read more',
			'show_for'       => 8,
			'effect_for'     => 1.5,
			'no_pause'       => false,
			'page'           => '',
			'client_section' => array(
					0 => array( 'field' => 'client_name', 'type' => 'text', 'class' => 'name' ),
					1 => array( 'field' => 'company_name', 'type' => 'link', 'url' => 'company_website', 'class' => 'company', 'new_tab' => true )
			)
	);

	ksort( $defaults );
	
	$serialized = serialize( $defaults );

	$table_name = $wpdb->prefix . 'strong_blocks';
	
	// The unmutable defaults.
	// Insert or update, 
	// thanks s_ha_dum http://wordpress.stackexchange.com/a/145112/32076
	$sql = "INSERT INTO {$table_name} (name, value) VALUES (%s, %s) ON DUPLICATE KEY UPDATE value = %s";
	$sql = $wpdb->prepare( $sql, '_default', $serialized, $serialized );
	$wpdb->query( $sql );
	
	// The base for new blocks.
	if ( ! wpmtst_get_block( 'base' ) ) {
		$sql = "INSERT INTO {$table_name} (name, value) VALUES (%s, %s)";
		$sql = $wpdb->prepare( $sql, 'Base', $serialized );
		$wpdb->query( $sql );
	}
}


/**
 * Update tables.
 *
 * @since 1.15.0
 */
function wpmtst_update_db_check() {
	global $strong_testimonials_db_version;
	if ( get_site_option( 'strong_testimonials_db_version' ) != $strong_testimonials_db_version ) {
		wpmtst_update_tables();
	}
}
add_action( 'plugins_loaded', 'wpmtst_update_db_check' );


/**
 * Check WordPress version
 */
function wpmtst_version_check() {
	global $wp_version;
	$plugin_info = get_plugin_data( __FILE__, false );
	$require_wp = "3.5";  // minimum Wordpress version
	$plugin = plugin_basename( __FILE__ );

	if ( version_compare( $wp_version, $require_wp, '<' ) ) {
		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
			$message = '<h2>';
			/* translators: %s is the name of the plugin. */
			$message .= sprintf( _x( 'Unable to load %s', 'installation', 'strong-testimonials' ), $plugin_info['Name'] );
			$message .= '</h2>';
			/* translators: %s is a WordPress version number. */
			$message .= '<p>' . sprintf( _x( 'This plugin requires <strong>WordPress %s</strong> or higher so it has been deactivated.', 'installation', 'strong-testimonials' ), $require_wp ) . '<p>';
			$message .= '<p>' . _x( 'Please upgrade WordPress and try again.', 'installation', 'strong-testimonials' ) . '<p>';
			$message .= '<p>' . sprintf( _x( 'Back to the WordPress <a href="%s">Plugins page</a>', 'installation', 'strong-testimonials' ), get_admin_url( null, 'plugins.php' ) ) . '<p>';
			wp_die( $message );
		}
	}
}


/**
 * Register Post Type and Taxonomy
 */
function wpmtst_register_cpt() {

	$testimonial_labels = array(
			'name'                  => _x( 'Testimonials', 'post type general name', 'strong-testimonials' ),
			'singular_name'         => _x( 'Testimonial', 'post type singular name', 'strong-testimonials' ),
			'add_new'               => _x( 'Add New', 'post type', 'strong-testimonials' ),
			'add_new_item'          => __( 'Add New Testimonial', 'strong-testimonials' ),
			'edit_item'             => __( 'Edit Testimonial', 'strong-testimonials' ),
			'new_item'              => __( 'New Testimonial', 'strong-testimonials' ),
			'all_items' 			      => __( 'All Testimonials', 'strong-testimonials' ),
			'view_item'             => __( 'View Testimonial', 'strong-testimonials' ) ,
			'search_items'          => __( 'Search Testimonials', 'strong-testimonials' ),
			'not_found'             => __( 'Nothing Found', 'strong-testimonials' ),
			'not_found_in_trash'    => __( 'Nothing found in Trash', 'strong-testimonials' ),
			'parent_item_colon'     => ''
	);

	$testimonial_args = array(
			'labels'                => $testimonial_labels,
			'singular_label'        => _x( 'testimonial', 'post type singular label', 'strong-testimonials' ),
			'public'                => true,
			'show_ui'               => true,
			'capability_type'       => 'post',
			'hierarchical'          => false,	// @since 1.8
			// 'rewrite'               => true,
			'rewrite'               => array( 'slug' => _x( 'testimonial', 'slug', 'strong-testimonials' ) ), // @since 1.8
			'menu_icon'				      => 'dashicons-editor-quote',
			// 'menu_icon'				      => 'dashicons-testimonial',
			'menu_position'			    => 20,
			'exclude_from_search' 	=> true,
			'supports'              => array( 'title', 'excerpt', 'editor', 'thumbnail', 'custom-fields' )
	);

	register_post_type( 'wpm-testimonial', $testimonial_args );

	// Additional permastructure.
	// This will override other CPTs with same slug.
	// $permastruct_args = $testimonial_args['rewrite'];
	// add_permastruct( 'wpm-testimonial', "review/%wpm-testimonial%", array( 'slug' => __( 'review', 'strong-testimonials' ) ) );

	
	$categories_labels = array(
			'name'                  => __( 'Categories', 'strong-testimonials' ),
			'singular_name'         => __( 'Category', 'strong-testimonials' ),
			'all_items' 			      => __( 'All Categories', 'strong-testimonials' ),
			'add_new_item'          => __( 'Add New Category', 'strong-testimonials' ),
			'edit_item'             => __( 'Edit Category', 'strong-testimonials' ),
			'new_item'              => __( 'New Category', 'strong-testimonials' ),
			'view_item'             => __( 'View Category', 'strong-testimonials' ),
			'search_items'          => __( 'Search Category', 'strong-testimonials' ),
			'not_found'             => __( 'Nothing Found', 'strong-testimonials' ),
			'not_found_in_trash'    => __( 'Nothing found in Trash', 'strong-testimonials' ),
			'parent_item_colon'     => ''
	);

	register_taxonomy( 'wpm-testimonial-category', array( 'wpm-testimonial' ), array(
			'hierarchical' => true,
			'labels'       => $categories_labels,
			'rewrite'      => array(
					'slug'         => 'view',
					'hierarchical' => true,
					'with_front'   => false
			)
	) );

}
// add_action( 'init', 'wpmtst_register_cpt' );
add_action( 'init', 'wpmtst_register_cpt', 5 );


/**
 * Theme support for this custom post type only.
 */
function wpmtst_theme_support() {
	add_theme_support( 'post-thumbnails', array( 'wpm-testimonial' ) );
}
add_action( 'after_theme_setup', 'wpmtst_theme_support' );


/**
 * Add widget thumbnail size.
 * 
 * @since 1.15.0
 */
function wpmtst_add_image_sizes() {
	add_image_size( 'widget-thumbnail', 75, 75, false );
}
add_action( 'after_setup_theme', 'wpmtst_add_image_sizes' );


/**
 * Register scripts and styles.
 */
function wpmtst_scripts() {
	global $post;
	$options = get_option( 'wpmtst_options' );
	$form_options = get_option( 'wpmtst_form_options' );

	wp_register_style( 'wpmtst-style', WPMTST_DIR . 'css/wpmtst.css' );
	// wp_register_style( 'strong-page-style', WPMTST_DIR . 'css/strong-page.css' );
	
	wp_register_style( 'wpmtst-form-style', WPMTST_DIR . 'css/wpmtst-form.css' );
	
	wp_register_style( 'wpmtst-pagination-style', WPMTST_DIR . 'css/wpmtst-pagination.css' );
	
	wp_register_style( 'wpmtst-rtl-style', WPMTST_DIR . 'css/wpmtst-rtl.css' );

	wp_register_script( 'wpmtst-pager-plugin', WPMTST_DIR . 'js/quickpager.jquery.js', array( 'jquery' ) );
	wp_register_script( 'wpmtst-pager-script', WPMTST_DIR . 'js/wpmtst-pager.js', array( 'wpmtst-pager-plugin' ) );
	wp_register_script( 'wpmtst-validation-plugin', WPMTST_DIR . 'js/jquery.validate.min.js', array( 'jquery' ) );
	
	/**
	 * Check for shortcodes. Keep these exploded!
	 */
	if ( $post ) {
		
		/**
		 * Load stylesheets here so themes can easily override styles.
		 * 
		 * @since 1.15.0
		 */
			
		// ~1.16
		// if ( $strong_template = wpmtst_get_template( $post->post_content ) )	{
			// logmore($strong_template,'strong_template','sep');
			// if ( $strong_template['css'] )
				// wp_enqueue_style( 'strong-template', $strong_template['css'] );
		// }

		
		if ( has_shortcode( $post->post_content, 'strong' ) ) {
			if ( $options['load_page_style'] ) {
				wp_enqueue_style( 'wpmtst-style' );
			}
			
			if ( is_rtl() && $options['load_rtl_style'] )
				wp_enqueue_style( 'wpmtst-rtl-style' );
		}

		/**
		 * Original shortcodes
		 */
		
		if ( has_shortcode( $post->post_content, 'wpmtst-all' ) ) {
			if ( $options['load_page_style'] )
				wp_enqueue_style( 'wpmtst-style' );
			
			if ( is_rtl() && $options['load_rtl_style'] )
				wp_enqueue_style( 'wpmtst-rtl-style' );
			
			wp_enqueue_style( 'wpmtst-pagination-style' );  // style
			wp_enqueue_script( 'wpmtst-pager-plugin' );  // plugin
			add_action( 'wp_footer', 'wpmtst_pagination_function' ); // script
		}

		if ( has_shortcode( $post->post_content, 'wpmtst-form' ) ) {
			if ( $options['load_form_style'] )
				wp_enqueue_style( 'wpmtst-form-style' );
			
			if ( is_rtl() && $options['load_rtl_style'] )
				wp_enqueue_style( 'wpmtst-rtl-style' );
				
			wp_enqueue_script( 'wpmtst-validation-plugin' );
			add_action( 'wp_footer', 'wpmtst_validation_function' );
			
			if ( $form_options['honeypot_before'] ) {
				add_action( 'wp_footer', 'wpmtst_honeypot_before_script' );
				add_action( 'wpmtst_honeypot_before', 'wpmtst_honeypot_before' );
			}
			
			if ( $form_options['honeypot_after'] ) {
				add_action( 'wp_footer', 'wpmtst_honeypot_after_script' );
				add_action( 'wpmtst_honeypot_after', 'wpmtst_honeypot_after' );
			}
		}

		if ( has_shortcode( $post->post_content, 'wpmtst-cycle' ) ) {
			if ( $options['load_page_style'] )
				wp_enqueue_style( 'wpmtst-style' );
			
			if ( is_rtl() && $options['load_rtl_style'] )
				wp_enqueue_style( 'wpmtst-rtl-style' );
		}

		if ( has_shortcode( $post->post_content, 'wpmtst-single' ) ) {
			if ( $options['load_page_style'] )
				wp_enqueue_style( 'wpmtst-style' );
			
			if ( is_rtl() && $options['load_rtl_style'] )
				wp_enqueue_style( 'wpmtst-rtl-style' );
		}

		if ( has_shortcode( $post->post_content, 'wpmtst-random' ) ) {
			if ( $options['load_page_style'] )
				wp_enqueue_style( 'wpmtst-style' );
			
			if ( is_rtl() && $options['load_rtl_style'] )
				wp_enqueue_style( 'wpmtst-rtl-style' );
		}
	
	}
	
	/**
	 * Widgets
	 *
	 * Previously deferred to footer.
	 * Loading here so themes can easily override styles.
	 * Monitor for compatibility with page builder plugins.
	 *
	 * @since 1.15.0
	 */
	if ( is_active_widget( false, false, 'wpmtst-widget', true ) ) {
		if ( $options['load_widget_style'] ) {
			wp_enqueue_style( 'wpmtst-widget-style', WPMTST_DIR . 'css/wpmtst-widget.css' );
			if ( is_rtl() && $options['load_rtl_style'] ) {
				wp_enqueue_style( 'wpmtst-widget-rtl', WPMTST_DIR . 'css/wpmtst-widget-rtl.css' );
			}	
		}
	}
	
	if ( is_active_widget( false, false, 'strong_testimonials-widget', true ) ) {
		if ( $options['load_widget_style'] ) {
			wp_enqueue_style( 'strong-widget-style', WPMTST_DIR . 'css/strong-widget.css' );
			if ( is_rtl() && $options['load_rtl_style'] ) {
				wp_enqueue_style( 'wpmtst-widget-rtl', WPMTST_DIR . 'css/wpmtst-widget-rtl.css' );
			}	
		}
	}
	
	/**
	 * To be compatible with Page Builder plugin, widget scripts
	 * are enqueued later when widget is active using custom action hook 
	 * `wpmtst_cycle_hook` and `wpmtst_is_registered` function.
	 *
	 * @since 1.9.0
	 */
}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts' );


/**
 * Register jQuery Cycle plugin.
 *
 * In case the theme loads cycle.js for a slider, we check after it's enqueue function.
 * If registered, we register our slider script using its handle.
 * If not registered, we register it with our handle.
 * @since 1.14.1
 */
function wpmtst_scripts_after_theme() {
	global $post;
	$options      = get_option( 'wpmtst_options' );
	// $form_options = get_option( 'wpmtst_form_options' );

	// jQuery Cycle plugin (all flavors)
	$filenames = array( 'jquery.cycle.all.min.js', 'jquery.cycle.all.js', 'jquery.cycle2.min.js', 'jquery.cycle2.js' );
	$cycle_handle = wpmtst_is_registered( $filenames );
	// use ours
	if ( ! $cycle_handle ) {
		$cycle_handle = 'jquery-cycle';
		wp_register_script( $cycle_handle, WPMTST_DIR . 'js/jquery.cycle2.min.js', array( 'jquery' ) );
	}
	
	// our slider handler, dependent on jQuery Cycle plugin
	wp_register_script( 'wpmtst-slider', WPMTST_DIR . 'js/wpmtst-cycle.js', array ( $cycle_handle ), false, true );
}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts_after_theme', 50 );


/**
 * Show version number in <head> section.
 *
 * For troubleshooting only.
 * @since 1.12.0
 */
function wpmtst_show_version_number() {
	global $wp_version;
	$headers = array(
		'Name' => 'Plugin Name',
		'Version' => 'Version',
	);
	$plugin_info = get_file_data( __FILE__, $headers );
	echo '<!-- WordPress ' . $wp_version . ' | ' . $plugin_info['Name'] . ' ' . $plugin_info['Version'] . ' -->' . "\n";
}
add_action( 'wp_head', 'wpmtst_show_version_number', 999 );


/**
 * Includes
 */
include( WPMTST_INC . 'functions.php' );
include( WPMTST_INC . 'child-shortcodes.php' );
include( WPMTST_INC . 'shims.php' );
include( WPMTST_INC . 'widget.php' );
include( WPMTST_INC . 'widget2.php' );
if ( is_admin() ) {
	include( WPMTST_INC . 'upgrade.php' );
	include( WPMTST_INC . 'admin.php' );
	include( WPMTST_INC . 'settings.php' );
	include( WPMTST_INC . 'admin-blocks.php' );
	include( WPMTST_INC . 'admin-block.php' );
	include( WPMTST_INC . 'prg-block.php' );
	include( WPMTST_INC . 'admin-custom-fields.php' );
	include( WPMTST_INC . 'guide.php' );
}
else {
	include( WPMTST_INC . 'shortcodes.php' );
	include( WPMTST_INC . 'shortcode-form.php' );
	include( WPMTST_INC . 'shortcode-strong.php' );
	include( WPMTST_INC . 'captcha.php' );
}