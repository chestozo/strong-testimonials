<div class="mode-select">
	<?php /* translators: This is on the Blocks admin screen. */ ?>
	<h3 class="large"><?php _e( 'Mode:', 'strong-testimonials' ); ?></h3>
	<select id="block-mode" name="block[data][mode]" class="large" autocomplete="off">
		<?php foreach ( $block_options['mode']['options'] as $mode ) : ?>
		<option value="<?php echo $mode['name']; ?>" <?php selected( $block['mode'], $mode['name'] ); ?>><?php echo $mode['label']; ?></option>
		<?php endforeach; ?>
	</select>
</div>
