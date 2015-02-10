<?php
/**
 * Block form POST
 *
 * @since 1.15.0
 */

 
function wpmtst_block_edit_form() {
	
	$query_arg = 'error';
	
	if ( ! empty( $_POST ) && check_admin_referer( 'block_form_submit', 'block_form_nonce' ) ) {
		
		$block_id    = $_POST['block']['id'];
		$block_name  = $_POST['block']['name'];
		// $block_title = $_POST['block']['title'];
	
		// Undo changes
		if ( isset( $_POST['reset'] ) ) {

			$block      = wpmtst_get_block( $block_id );
			// $block_data = $block['value'];

			$query_arg = 'changes-undone';

		}
		// Restore defaults
		elseif ( isset( $_POST['restore-defaults'] ) ) {
			
			// $default_block = wpmtst_get_block( 1 );
			$default_block = get_option( 'wpmtst_block_default' );
			
			$block = array(
					'id'    => $block_id,
					'name'  => $block_name,
					// 'title' => $block_title,
					// 'data'  => unserialize( $default_block['value'] )
					'data'  => $default_block
			);
			wpmtst_save_block( $block );
			
			$query_arg = 'defaults-restored';
			
		}
		// Sanitize & validate
		else {
			
			$block = array(
					'id'    => $block_id,
					'name'  => sanitize_text_field( $block_name ),
					// 'title' => $block_title,
					'data'  => wpmtst_sanitize_block( $_POST['block']['data'] )
			);
			$num = wpmtst_save_block( $block );
		
			$query_arg = 'block-saved';
		
		}
		
	}

	$goback = add_query_arg( $query_arg, true, wp_get_referer() );
	wp_redirect( $goback );
	exit;

}
// Thanks http://stackoverflow.com/a/20003981/51600
add_action( 'admin_post_block_edit_form', 'wpmtst_block_edit_form' );
add_action( 'admin_post_nopriv_block_edit_form', 'wpmtst_block_edit_form' );


function wpmtst_block_add_form() {
	
	$query_arg = 'error';
	
	if ( ! empty( $_POST ) && check_admin_referer( 'block_form_submit', 'block_form_nonce' ) ) {
		
		// $block_id    = $_POST['block']['id'];
		$block_id    = 0;
		$block_name  = $_POST['block']['name'];
		// $block_title = $_POST['block']['title'];
	
		// Restore defaults
		if ( isset( $_POST['restore-defaults'] ) ) {
			
			// $default_block = wpmtst_get_block( 1 );
			$default_block = get_option( 'wpmtst_block_default' );
			
			$block = array(
					'id'    => $block_id,
					'name'  => $block_name,
					// 'title' => $block_title,
					'data'  => unserialize( $default_block['value'] )
			);
			wpmtst_save_block( $block, 'add' );
			
			$query_arg = 'defaults-restored';
			
		}
		// Sanitize & validate
		else {
			
			$block = array(
					'id'    => 0,
					'name'  => sanitize_text_field( $block_name ),
					// 'title' => $block_title,
					'data'  => wpmtst_sanitize_block( $_POST['block']['data'] )
			);
			$block['id'] = wpmtst_save_block( $block, 'add' );
		
			$query_arg = 'block-saved';
		
		}
		
	}

	logmore(wp_get_referer());
	// $referer = remove_query_arg( 'action', wp_get_referer() );
	$goback = add_query_arg( array( 'action' => 'edit', 'id' => $block['id'], $query_arg => true ), remove_query_arg( 'action', wp_get_referer() ) );
	logmore($goback);
	wp_redirect( $goback );
	exit;

}
add_action( 'admin_post_block_add_form', 'wpmtst_block_add_form' );
add_action( 'admin_post_nopriv_block_add_form', 'wpmtst_block_add_form' );


function wpmtst_sanitize_block( $input ) {
	// logmore($input);
	$block_data = array();
	
	$block_data['mode'] = sanitize_text_field( $input['mode'] );

	// Read more target
	// If page ID or slug is entered, use that to populate the dropdown, then clear the input field.
	// Will try to handle this in JavaScript first.
	if ( ! $input['page_id'] ) {
		$block_data['page'] = intval( sanitize_text_field( $input['page'] ) );
	}
	else {
		// is page ID?
		$id = intval( $input['page_id'] );
		if ( $id ) {
			if ( ! get_post( $id ) ) {
				$id = null;
			}
		}
		// is slug?
		else {
			$target = get_posts( array( 
					'name'        => $input['page_id'],
					'post_type'   => 'page',
					'post_status' => 'publish'
			) );
			if ( $target ) {
				$id = $target[0]->ID;
			}
		}
		
		$block_data['page']    = $id;
		$block_data['page_id'] = '';
	}

	// Single testimonial
	// If page ID or slug is entered, use that to populate the dropdown, then clear the input field.
	// Will try to handle this in JavaScript first.
	if ( ! $input['post_id'] ) {
		$block_data['id'] = intval( sanitize_text_field( $input['id'] ) );
	}
	else {
		// is post ID?
		$id = intval( $input['post_id'] ); 
		if ( $id ) {
			if ( ! get_posts( array( 'p' => $id, 'post_type' => 'wpm-testimonial', 'post_status' => 'publish' ) ) ) {
				$id = null;
			}
		}
		// is post slug?
		else {
			$target = get_posts( array( 
					'name'           => $input['post_id'],
					'post_type'      => 'wpm-testimonial',
					'post_status'    => 'publish'
			) );
			if ( $target ) {
				$id = $target[0]->ID;
			}
		}
		
		$block_data['id']      = $id;
		$block_data['post_id'] = '';
	}

	if ( $input['category'] == wpmtst_get_category_ids() )
		$block_data['category'] = 'all';
	else
		$block_data['category'] = sanitize_text_field( implode( ',', $input['category'] ) );

	$block_data['order'] = sanitize_text_field( $input['order'] );
	$block_data['all']   = sanitize_text_field( $input['all'] );
	$block_data['count'] = intval( sanitize_text_field( $input['count'] ) );

	$block_data['pagination'] = isset( $input['pagination'] ) ? 1 : 0;
	$block_data['per_page']   = intval( sanitize_text_field( $input['per_page'] ) );
	$block_data['nav']        = str_replace( ' ', '', sanitize_text_field( $input['nav'] ) );

	$block_data['more_post'] = isset( $input['more_post'] ) ? 1 : 0;
	$block_data['more_text'] = sanitize_text_field( $input['more_text'] );

	$block_data['title']          = isset( $input['title'] ) ? 1 : 0;
	$block_data['content']        = sanitize_text_field( $input['content'] );
	$block_data['length']         = intval( sanitize_text_field( $input['length'] ) );

	$block_data['thumbnail']        = isset( $input['thumbnail'] ) ? 1 : 0;
	$block_data['thumbnail_size']   = sanitize_text_field( $input['thumbnail_size'] );
	$block_data['thumbnail_width']  = sanitize_text_field( $input['thumbnail_width'] );
	$block_data['thumbnail_height'] = sanitize_text_field( $input['thumbnail_height'] );

	$block_data['stylesheet'] = sanitize_text_field( $input['stylesheet'] );
	$block_data['class']      = sanitize_text_field( $input['class'] );
	$block_data['template']   = sanitize_text_field( $input['template'] );

	$block_data['show_for']   = floatval( sanitize_text_field( $input['show_for'] ) );
	$block_data['effect_for'] = floatval( sanitize_text_field( $input['effect_for'] ) );
	$block_data['no_pause']   = isset( $input['no_pause'] ) ? 0 : 1;

	foreach ( $input['client_section'] as $key => $field ) {
		if ( empty( $field['field'] ) ) 
			break;
		
		$block_data['client_section'][$key]['field'] = sanitize_text_field( $field['field'] );
		$block_data['client_section'][$key]['type']  = sanitize_text_field( $field['type'] );
		$block_data['client_section'][$key]['class'] = sanitize_text_field( $field['class'] );
		if ( 'link' == $field['type'] ) {
			$block_data['client_section'][$key]['url']     = sanitize_text_field( $field['url'] );
			$block_data['client_section'][$key]['new_tab'] = isset( $field['new_tab'] ) ? 1 : 0;
		}
	}

	ksort( $block_data );
	return $block_data;
}