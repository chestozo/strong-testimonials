<?php
/**
 * Block admin functions.
 *
 * @since 1.15.0
 * @package Strong_Testimonials
 */


/**
 * Block list page
 *
 * @since 1.15.0
 */
function wpmtst_settings_blocks() {
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	?>
	<div class="wrap wpmtst2">
	
		<h2><?php _e( 'Blocks', 'strong-testimonials' ); ?></h2>
	
		<?php
		// @TODO move to options
		$message = '';
		if ( isset( $_REQUEST['changes-undone'] ) ) {
			$message = __( 'Changes undone.', 'strong-testimonials' );
		}
		elseif ( isset( $_REQUEST['defaults-restored'] ) ) {
			$message = __( 'Defaults restored.', 'strong-testimonials' );
		}
		elseif ( isset( $_REQUEST['block-saved'] ) ) {
			$message = __( 'Block saved.', 'strong-testimonials' );
		}
		if ( $message )
			printf( '<div id="message" class="updated"><p>%s</p></div>', $message );

		// ---------------
		// Editing a block
		// ---------------
		if ( 'edit' == $_REQUEST['action'] && isset( $_REQUEST['id'] ) ) {
			wpmtst_block_settings( $_REQUEST['action'], $_REQUEST['id'] );
		}
		elseif ( 'add' == $_REQUEST['action'] ) {
			wpmtst_block_settings( $_REQUEST['action'] );
		}
		// --------------
		// Block list
		// --------------
		else {
			$screen = get_current_screen();
			$url = $screen->parent_file;
			
			// Get current blocks
			$blocks = wpmtst_get_blocks();
			logmore($blocks);
			
			foreach ( $blocks as $key => $block ) {
				echo '<div><a href="' . $url . '&page=blocks&action=edit&id=' . $block['id'] . '">' . $block['name'] . '</a></div>';
			}
			
			echo '<div><a href="' . $url . '&page=blocks&action=add">Add New Block</a></div>';
			
		}
		?>
	</div><!-- .wrap -->
	<?php
}


/**
 * [Add New Field] Ajax receiver
 *
 * @since 1.15.0
 */
function wpmtst_block_add_field_function() {
	$new_key = intval( $_REQUEST['key'] );
	$empty_field = array( 'field' => '', 'type' => 'text', 'class' => '' );
	wpmtst_block_field_inputs( $new_key, $empty_field, true );
	die();
}
add_action( 'wp_ajax_wpmtst_block_add_field', 'wpmtst_block_add_field_function' );


/**
 * [Field Type: Link] Ajax receiver
 *
 * @since 1.15.0
 */
function wpmtst_block_add_field_link_function() {
	$key = intval( $_REQUEST['key'] );
	$empty_field = array( 'url' => '', 'new_tab' => true );
	wpmtst_block_field_link( $key, $empty_field );
	die();
}
add_action( 'wp_ajax_wpmtst_block_add_field_link', 'wpmtst_block_add_field_link_function' );


/**
 * [Mode Change: Set Default Template] Ajax receiver
 *
 * @since 1.15.0
 */
function wpmtst_get_default_template_function() {
	$mode = $_REQUEST['mode'];
	$block_options = get_option( 'wpmtst_block_options' );
	$default_template = $block_options['default_templates'][$mode];
	echo $default_template;
	die();
}
// add_action( 'wp_ajax_wpmtst_get_default_template', 'wpmtst_get_default_template_function' );


/**
 * Show a single client field's inputs.
 *
 * @since 1.15.0
 */
function wpmtst_block_field_inputs( $key, $field, $adding = false ) {
	$custom_fields = wpmtst_get_custom_fields();
	$types = array( 'text', 'link' );
	?>
	<tr class="field2" id="field-<?php echo $key; ?>">
		<td class="field-name">
			<select name="block[data][client_section][<?php echo $key; ?>][field]">
				<option value="">
					<?php /* translators: This is on the Blocks admin screen. */ ?>
					<?php //_e( '— select a field —', 'strong-testimonials' ); ?>
				</option>
				<?php foreach ( $custom_fields as $key2 => $field2 ) : ?>
					<?php if ( 'custom' == $field2['record_type'] && 'email' != $field2['input_type'] ) : ?>
						<option value="<?php echo $field2['name']; ?>" <?php selected( $field2['name'], $field['field'] ); ?>><?php echo $field2['name']; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</td>
		
		<td class="field-type">
			<select name="block[data][client_section][<?php echo $key; ?>][type]">
			<?php foreach ( $types as $type ) : ?>
				<option value="<?php echo $type; ?>" <?php selected( $type, $field['type'] ); ?>><?php echo $type; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
		<td class="field-link">
			<?php if ( 'link' == $field['type'] ) wpmtst_block_field_link( $key, $field); ?>
		</td>
		<td class="field-class">
			<input type="text" name="block[data][client_section][<?php echo $key; ?>][class]" value="<?php echo $field['class']; ?>" />
		</td>
		<td class="controls">
			<span class="delete-field"><span class="dashicons dashicons-no"></span></span>
			<span class="handle"><span class="dashicons dashicons-menu"></span></span>
		</td>
	</tr>
	<?php
}


/**
 * Show a single client link field's inputs.
 *
 * @since 1.15.0
 */
function wpmtst_block_field_link( $key, $field, $adding = false ) {
	$custom_fields = wpmtst_get_custom_fields();
	?>
	<select name="block[data][client_section][<?php echo $key; ?>][url]" class="field-type-select">
		<?php foreach ( $custom_fields as $key2 => $field2 ) : ?>
			<?php if ( 'url' == $field2['input_type'] ) : ?>
			<option value="<?php echo $field2['name']; ?>" <?php selected( $field2['name'], $field['url'] ); ?>><?php echo $field2['name']; ?></option>
			<?php endif; ?>
		<?php endforeach; ?>
	</select>
	<span class="new_tab">
		<input type="checkbox" name="block[data][client_section][<?php echo $key; ?>][new_tab]" value="1" <?php checked( $field['new_tab'] ); ?> /> new_tab
	</span>
	<?php
}
