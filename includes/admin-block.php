<?php
/**
 * An individual block settings page.
 *
 * @since 1.15.0
 * @package Strong_Testimonials
 */

function wpmtst_block_settings( $action = '', $block_id = null ) {
	if ( 'edit' == $action && ! $block_id )
		return;

	define( 'WPMTST_INC_BLOCK_PARTS', plugin_dir_path( __FILE__ ) . 'form-parts/block/' );

	$options = get_option( 'wpmtst_options' );
	 
	// @TODO de-duplicate
	$order_list = array(
			'random' => _x( 'Random', 'display order', 'strong-testimonials' ),
			'newest' => _x( 'Newest first', 'display order', 'strong-testimonials' ),
			'oldest' => _x( 'Oldest first', 'display order', 'strong-testimonials' ),
	);

	$posts_list = get_posts( array(
			'orderby'          => 'post_date',
			'order'            => 'ASC',
			'post_type'        => 'wpm-testimonial',
			'post_status'      => 'publish',
	) );

	$category_list = wpmtst_get_category_list();
	$category_ids  = wpmtst_get_category_ids();

	$pages_list = get_pages( array(
			'sort_order'  => 'ASC',
			'sort_column' => 'menu_order',
			'post_type'   => 'page',
			'post_status' => 'publish'
	) );
	
	$block_options = get_option( 'wpmtst_block_options' );

	// Get current block
	if ( 'edit' == $action ) {
		$block_array = wpmtst_get_block( $block_id );
		$block = unserialize( $block_array['value'] );
		$block_name  = $block_array['name'];
		// $block_title = $block_array['title'];
	}
	else {
		$block_id    = 0;
		// $block_array = wpmtst_get_block( 1 );
		$block = get_option( 'wpmtst_block_default' );
		$block_name  = 'new';
		// $block_title = 'new';
	}
	// $block = unserialize( $block_array['value'] );
	// echo '<div style="background: #ddd; float: right;"><pre>'.print_r($block,true).'</pre></div>';

	$block['nav'] = explode( ',', str_replace( ' ', '', $block['nav'] ) );
	$block_cats_array = explode( ',', $block['category'] );

	// Assemble list of templates
	$theme_templates  = wpmtst_get_theme_templates();
	$plugin_templates = wpmtst_get_plugin_templates();

	// Get list of image sizes
	$image_sizes = wpmtst_get_image_sizes();

	?>
	<!--
	<h3><?php echo $block_name; ?> Block</h3>
	-->

	<form id="wpmtst-blocks-form" method="post" action="<?php echo get_admin_url() . 'admin-post.php'; ?>">
	
		<input type="hidden" name="action" value="block_<?php echo $action; ?>_form" />
		<?php wp_nonce_field( 'block_form_submit', 'block_form_nonce', true, true ); ?>
		
		<input type="hidden" name="block[id]" value="<?php echo $block_id; ?>" />
		<!--
		<input type="hidden" name="block[name]" value="<?php echo $block_name; ?>" />
		<input type="hidden" name="block[title]" value="<?php echo $block_title; ?>" />
		-->
		<div class="form-block-id">ID: <?php echo $block_id; ?></div>
		<div class="form-block-name">Name: <input type="text" class="block-name" name="block[name]" value="<?php echo $block_name; ?>" autocomplete="off"/></div>
		<div class="form-block-shortcode"><code>[strong block="<?php echo $block_id; ?>"]</code></div>
		
		<?php include( WPMTST_INC_BLOCK_PARTS . 'mode.php' ); ?>
		<?php include( WPMTST_INC_BLOCK_PARTS . 'group-slideshow.php' ); ?>
		<?php include( WPMTST_INC_BLOCK_PARTS . 'group-select.php' ); ?>
		<?php include( WPMTST_INC_BLOCK_PARTS . 'group-layout.php' ); ?>
		<?php include( WPMTST_INC_BLOCK_PARTS . 'group-content.php' ); ?>
		<?php include( WPMTST_INC_BLOCK_PARTS . 'group-style.php' ); ?>

		<p class="submit">
			<?php	submit_button( '', 'primary', 'submit', false ); ?>
			<?php	submit_button( __( 'Undo Changes', 'strong-testimonials' ), 'secondary', 'reset', false ); ?>
			<?php submit_button( __( 'Restore Defaults', 'strong-testimonials' ), 'secondary', 'restore-defaults', false ); ?>
		</p>

	</form><!-- Blocks -->

	<?php
}
?>
