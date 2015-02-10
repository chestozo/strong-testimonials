<?php
/**
 * Strong shortcode functions.
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */


/**
 * Do not texturize [strong].
 *
 * @since 1.11.5
 */
function wpmtst_no_texturize_shortcodes( $shortcodes ) {
	$shortcodes[] = 'strong';
	return $shortcodes;
}
add_filter( 'no_texturize_shortcodes', 'wpmtst_no_texturize_shortcodes' );


/**
 * Strong shortcode.
 *
 * @since 1.11.0
 */
function wpmtst_strong_shortcode( $atts, $content = null, $parent_tag ) {
	global $child_shortcode_tags;
	// $content contains only child shortcodes like [client]
	
	// SEQUENCE
	// 1. normalize_empty_atts( $atts ) turns `attribute` into `attribute=true`
	// 2. shortcode_atts merges normalized attributes onto default pairs
	// 3. filter is applied (where block is fetched)
	// 4. variables are populated from array so $array['attribute'] becomes $attribute
	extract( shortcode_atts(
		array(
				// modes
				'block' => 'default',
				'display' => '',
				'form' => '',
				'slideshow' => '',
				'read_more' => '',
				// selection
				'id' => '',
				'category' => '',
				'count' => -1,
				//style
				'class' => '',
				'template' => '',
				// pagination
				'per_page' => '',
				'nav' => 'after',
				// order
				'random' => '',
				'newest' => '',
				'oldest' => '',
				// parts
				'title'  => '',
				'thumbnail' => '',
				'thumbnail_size' => 'thumbnail',
				// content
				'excerpt' => '',
				'length' => '',
				// "read more" to post
				'more_post' => '',
				'more_text' => _x( 'Read more', 'link', 'strong-testimonials' ),
				// slideshow attributes
				'show_for' => '',
				'effect_for' => '',
				'no_pause' => 'false',
				// "read more" link to page
				'page' => '',
		),
		normalize_empty_atts( $atts ), 
		$parent_tag
	) );

	$options = get_option( 'wpmtst_options' );
	
	// ==========
	// MODE: FORM
	// ==========
	if ( $form ) {
		if ( $options['load_form_style'] )
			wp_enqueue_style( 'wpmtst-form-style' );
		
		if ( is_rtl() && $options['load_rtl_style'] )
			wp_enqueue_style( 'wpmtst-rtl-style' );
		
		wp_enqueue_script( 'wpmtst-validation-plugin' );
		add_action( 'wp_footer', 'wpmtst_validation_function' );
		return wpmtst_form_shortcode( $atts );  // move this to include
	}
	
	// ====================
	// MODE: READ MORE LINK
	// shortcode only
	// ====================
	if ( $read_more ) {
		// page ID or slug?
		if ( ! is_numeric( $page ) )
			$page = get_page_by_slug( $page );
			
		if ( ! $content )
			$content = _x( 'Read more', 'link', 'strong-testimonials' );
			
		return '<div class="' . $class . '"><a href="' . get_permalink( $page ) . '">' . $content . '</a></div>';
	}
	
	// =======================
	// MODE: DISPLAY (default)
	// =======================
	$container_class_list = '';
	$content_class_list   = '';
	
	// -----------
	// stylesheets
	// -----------
	if ( $options['load_page_style'] ) {
		// wp_enqueue_style( 'wpmtst-style' );
		// wp_enqueue_style( 'strong-page-style' );
	}
	if ( $options['load_widget_style'] ) {
		// wp_enqueue_style( 'wpmtst-widget-style' );
		// wp_enqueue_style( 'strong-widget-style' );
	}
	if ( is_rtl() && $options['load_rtl_style'] )
		wp_enqueue_style( 'wpmtst-rtl-style' );

	// ===================
	// SUB-MODE: SLIDESHOW
	// ===================
	if ( $slideshow ) {
		// slideshow overrides ID
		$id = '';
		$random_string = substr( str_shuffle( str_repeat( '0123456789abcdefghijklmnopqrstuvwxyz', 10 ) ), 0, 10 );
		$content_class_list .= ' tcycle tcycle_shortcode_' . $random_string;
		// custom action hook to localize script
		// need to incorporate default settings
		do_action(
			'wpmtst_cycle_hook', 
			'fade', 
			$effect_for, 
			$show_for, 
			$no_pause ? false : true,
			'tcycle_shortcode_' . $random_string
		);
	}
	
	// client info
	$has_client_shortcodes = false;
	$has_client_section    = false;
	
	// thumbnail size
	if ( $thumbnail && 'custom' == $thumbnail_size ) {
		$thumbnail_size = array( $thumbnail_width, $thumbnail_height );
	}
	
	// block
	if ( isset( $client_section ) ) {
		$has_client_section = true;
	}
	// shortcode
	elseif ( $content ) {
		$shortcode_content = reverse_wpautop( $content ); // needed in template
		$has_client_shortcodes = has_child_shortcode( $shortcode_content, 'client', $parent_tag );
	}
	
	// extract comma-separated values
	$categories = explode( ',', $category );
	$ids = explode( ',', $id );
	if ( $class )
		$container_class_list .= ' ' . str_replace( ',', ' ', $class );
	
	// ------------------------
	// assemble query arguments
	// ------------------------
	
	$args = array(
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => $count,
			'orderby'        => 'post_date',
			'post_status'    => 'publish',
	);
	
	// ID overrides category
	if ( $id ) {
		$args['post__in'] = $ids;
	}
	elseif ( $category ) {
		$args['tax_query'] = array(
				array(
						'taxonomy' => 'wpm-testimonial-category',
						'field'    => 'id',
						'terms'    => $categories
				)
		);
	}
	
	// order by
	if ( $random ) {
		$args['orderby'] = 'rand';
	}
	else {
		$args['orderby'] = 'post_date';
		if ( $newest )
			$args['order'] = 'DESC';
		else 
			$args['order'] = 'ASC';
	}
	
	// -----
	// query
	// -----
	$query = new WP_Query( $args );
	$post_count = $query->post_count;
	
	// -------------------------------
	// conditional loading: pagination
	// -------------------------------
	// slideshow overrides pagination
	if ( ! $slideshow && $per_page && $post_count > $per_page ) {
		if ( false !== strpos( $nav, 'before' ) && false !== strpos( $nav, 'after') )
			$nav = 'both';
		
		$pager = array (
				'pageSize'      => $per_page,
				'currentPage'   => 1, 
				'pagerLocation' => $nav
		);
		wp_enqueue_style( 'wpmtst-pagination-style' );  // style
		wp_enqueue_script( 'wpmtst-pager-plugin' );  // plugin
		wp_enqueue_script( 'wpmtst-pager-script' );  // script
		wp_localize_script( 'wpmtst-pager-script', 'pagerVar', $pager );
	}

	// ------------------------------
	// individual testimonial classes
	// ------------------------------
	$post_class_list = "testimonial";
	
	// excerpt overrides length
	if ( $excerpt )
		$post_class_list .= ' excerpt';
	elseif ( $length ) 
		$post_class_list .= ' truncated';
		
	if ( $slideshow )
		$post_class_list .= ' t-slide';
		
	// -------------
	// load template
	// -------------
	// $mode = $form ? 'form' : $slideshow ? 'slideshow' : 'page';
	// $template_file = wpmtst_find_template( $template, $mode );
	$template_file = wpmtst_find_template( $template );
	// logmore($template_file);
	
	ob_start();
	include( $template_file );
	$html = ob_get_contents();
	ob_end_clean();
	
	wp_reset_postdata();
	
	$html = apply_filters( 'strong_html', $html );
	return $html;
}
add_shortcode( 'strong', 'wpmtst_strong_shortcode' );


/**
 * Template search.
 *
 * Similar to get_query_template.
 * Called by shortcode and when enqueueing stylesheets.
 *
 * @since 1.15.0
 */
// function wpmtst_find_template( $template = '', $mode = '' ) {
function wpmtst_find_template( $template = '' ) {
	// logmore($template,'template');
	// logmore($mode,'mode');
	$template_file = '';
	
	// 1st: specific template in plugin
	if ( '.php' == substr( $template, -4 ) && file_exists( WPMTST_TPL . $template ) ) {
		$template_file = WPMTST_TPL . $template;
	}
	
	// 2nd: theme
	if ( ! $template_file ) {
		$search_array = array();
		if ( $template ) {
			$search_array[] = "testimonials-{$template}.php";
		}
		$search_array[] = 'testimonials.php';
		$template_file = get_query_template( 'testimonials', $search_array );
	}

	// 3rd: default template for the mode
	// if ( ! template_file && $mode ) {
		// $block_options = get_option( 'wpmtst_block_options' );
		// if ( isset( $block_options['default_templates'][$mode] ) ) {
			// $template = $block_options['default_templates'][$mode];
			// if ( '.php' == substr( $template, -4 ) && file_exists( WPMTST_TPL . $template ) ) {
				// $template_file = WPMTST_TPL . $template;
			// }
		// }
	// }
	
	// last: the default
	// if ( ! $template_file )
		// $template_file = WPMTST_TPL . 'default/testimonials-page.php';
	
	return $template_file;
}


/**
 * Template filter.
 *
 * Returns plugin's default template if template not found in theme.
 *
 * @since 1.11.0
 */
function wpmtst_template_filter( $template ) {
	if ( $template )
		return $template;
	
	return WPMTST_TPL . 'default/testimonials-page.php';
}
add_filter( 'testimonials_template', 'wpmtst_template_filter', 99 );


/**
 * Extract template attribute then find template file and stylesheet.
 *
 * A combination of has_shortcode and shortcode_parse_atts.
 *
 * @since 1.16.0
 */
/*
function wpmtst_get_template( $content ) {
	if ( false === strpos( $content, '[' ) ) {
		return false;
	}

	preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
	if ( empty( $matches ) )
		return false;
	
	foreach ( $matches as $shortcode ) {
		
		if ( 'strong' === $shortcode[2] ) {
			
			$template = '';
			$atts = shortcode_parse_atts( $shortcode[3] );
			
			// 1. Using template attribute?
			if ( isset( $atts['template'] ) ) {
				$template = $atts['template'];
			}
			// 2. Using block attribute?
			elseif( isset( $atts['block'] ) ) {
				$block = $atts['block'];
				
				$block_object = wpmtst_get_block( $block );
				if ( ! $block )
					return false;
		
				$block_data = unserialize( $block_object['value'] );
				$template = $block_data['template'];
			}
			
			$template_file = wpmtst_find_template( $template );
			
			$template_stylesheet = str_replace( '.php', '.css', $template_file );
			if ( file_exists( $template_stylesheet ) )
				$template_stylesheet = str_replace( WPMTST_TPL, WPMTST_TPL_URI, $template_stylesheet );
			else
				$template_stylesheet = '';
				
			$template_files = array( 'php' => $template_file, 'css' => $template_stylesheet );
			return $template_files;
			
		}
	}

	return false;
}
*/


/**
 * Attribute filter for strong shortcode.
 *
 * @since 1.11.0
 */
function wpmtst_strong_shortcode_filter( $out, $pairs, $atts ) {
	// logmore($out,'out');logmore($pairs,'pairs');logmore($atts,'atts');logmore(str_repeat('-',50));
	
	// ===========
	// MODE: BLOCK
	// ===========
	if ( ! isset( $atts['block'] ) )
		return $out;
	
	// fetch the block
	$block = wpmtst_get_block( $atts['block'] );
	
	if ( ! $block )
		return $out;
		
	$block_data = unserialize( $block['value'] );
	// logmore($block_data,'block_data');
	
	// =============================================================
	// DECENTRALIZE
	// This is necessary because of the way we use empty attributes;
	// e.g. random --> random="true"
	// =============================================================
	
	// -----------------------------------------------------------------
	// rule: unset unused defaults that interfere (i.e. dependent rules)
	// -----------------------------------------------------------------
	
	if ( 'all' == $block_data['category']  )
		unset( $block_data['category'] );
	
	if ( ! $block_data['id'] )
		unset( $block_data['id'] );
	
	if ( $block_data['all'] )
		unset( $block_data['count'] );
	
	if ( ! $block_data['pagination'] )
		unset( $block_data['per_page'] );
	
	if ( 'entire' == $block_data['content'] )
		unset( $block_data['length'] );
	
	if ('slideshow' == $block_data['mode'] ) {
		unset( $block_data['id'] );
		// $block_data['no_pause'] = ! $block_data['pause'];
	}
	else {
		unset( $block_data['show_for'] );
		unset( $block_data['effect_for'] );
		unset( $block_data['no_pause'] );
	}

	// ------------------------------
	// rule: extract value from array
	// ------------------------------
	
	$out[$block_data['mode']] = true;
	unset( $block_data['mode'] );
	
	$out[$block_data['order']] = true;
	unset( $block_data['order'] );
	
	$out[$block_data['content']] = true;
	unset( $block_data['content'] );
	
	// ----------------------------------------------
	// merge block onto user settings and sort result
	// ----------------------------------------------
	
	$out = array_merge( $out, $block_data );
	// ksort( $out );
	// logmore($out,'new out for `'.$atts['block'].'` block','sep');

	return $out;
}
add_filter( 'shortcode_atts_strong', 'wpmtst_strong_shortcode_filter', 10, 3 );


/*===========================================================================*/


/**
 * Child shortcode for the client section.
 *
 * Just a wrapper for client child shortcodes. No attributes yet.
 *
 * @since 1.11.0
 */
function wpmtst_strong_client_shortcode( $atts, $content = null, $tag ) {
	return do_child_shortcode( 'strong', $content );
}
add_child_shortcode( 'strong', 'client', 'wpmtst_strong_client_shortcode' );


/**
 * Attribute filter for client child shortcode.
 *
 * @since 1.11.0
 */
function wpmtst_client_shortcode_filter( $out, $pairs, $atts, $post ) {
	return $out;
}
add_filter( 'child_shortcode_atts_client', 'wpmtst_client_shortcode_filter', 10, 3 );


/*===========================================================================*/


/**
 * Child shortcode for a custom field.
 *
 * [field name="client_name" class="name"]
 * [field name="company_name" url="company_website" class="name" new_tab]
 * No child shortcodes.
 *
 * @since 1.11.0
 */
function wpmtst_strong_field_shortcode( $atts, $content = null, $tag ) {
	extract( child_shortcode_atts(
		array(
				'name'     => '',	// custom field
				'url'      => '', // custom field
				'class'    => '', // CSS
				'new_tab'  => false,
				// 'nofollow' => false   // approach: no global + local enable (via filter)
		),
		normalize_empty_atts( $atts ),
		$tag
	) );

	if ( $url ) {
		if ( '' == $name ) {
			$name = preg_replace( '(^https?://)', '', $url );
		}
		$name = "<a href=\"$url\"" . link_new_tab( $new_tab, false ) . link_nofollow( $nofollow, false ) . ">$name</a>";
	}
	
	/*
	 * Bug fix: Return blank string.
	 *
	 * @since 1.12.0
	 */
	if ( '' == $name )
		return;
	else
		return '<div class="' . $class . '">' . $name . '</div>';
}
add_child_shortcode( 'strong', 'field', 'wpmtst_strong_field_shortcode' );


/**
 * Attribute filter for [field] child shortcode.
 *
 * @since 1.11.0
 * @param array $out   The output array of shortcode attributes.
 * @param array $pairs The array of accepted parameters and their defaults.
 * @param array $atts  The input array of shortcode attributes.
 */
function wpmtst_field_shortcode_filter( $out, $pairs, $atts ) {
	global $post;
	if ( $post )
		return wpmtst_atts_to_values( $out, $atts, $post );
	else
		return $out;
}
add_filter( 'child_shortcode_atts_field', 'wpmtst_field_shortcode_filter', 10, 3 );


/*===========================================================================*/


/**
 * Replace attribute values with $post values
 * but don't overwrite other attributes like "class".
 *
 * from : [url] => company_website
 *   to : [url] => http://example.com -OR- (empty string)
 *
 * @since 1.11.0
 * @param array $out   The output array of shortcode attributes.
 * @param array $atts  The input array of shortcode attributes.
 * @param array $post  The testimonial post.
 */
function wpmtst_atts_to_values( $out, $atts, $post ) {
	// for fields listed in shortcode attributes:
	foreach ( $atts as $key => $field ) {
		if ( 'name' == $key || 'url' == $key ) {
			if ( isset( $post->$field ) )
				$out[$key] = $post->$field;
			else
				$out[$key] = '';  // @since 1.12
		}
	}

	// for fields *not* listed in shortcode attributes:
	// approach: no global + local enable
	$out['nofollow'] = ( 'on' == $post->nofollow );
	return $out;
}


/**
 * Client section
 *
 * @since 1.15.0
 * @param array $client_section An array of client fields.
 */
function strong_do_client_section( $client_section ) {
	global $post;
	$html = '';
	foreach ( $client_section as $field ) {
		$output = '';

		// link field
		if ( 'link' == $field['type'] ) {
			$text = get_post_meta( $post->ID, $field['field'], true );
			$url  = get_post_meta( $post->ID, $field['url'], true );
			if ( $url ) {
				if ( isset( $field['new_tab'] ) )
					$new_tab = $field['new_tab'];
				else
					$new_tab = false;
				
				$nofollow = get_post_meta( $post->ID, 'nofollow', true );
				
				if ( '' == $text )
					$text = preg_replace( '(^https?://)', '', $url );
				
				$output = sprintf( '<a href="%s"%s%s>%s</a>', $url, link_new_tab( $new_tab, false ), link_nofollow( $nofollow, false ), $text );
			}
		}
		// text field
		else {
			$output = get_post_meta( $post->ID, $field['field'], true );
		}
		
		if ( $output )
			$html .= '<div class="' . $field['class'] . '">' . $output . '</div>';

	}
	return $html;
}