	<th colspan="2" class="checkbox">
		<input type="checkbox" id="block-images" class="if toggle" name="block[data][thumbnail]" value="1" <?php checked( $block['thumbnail'] ); ?> class="checkbox" />
		<label for="block-images">
			<?php /* translators: This is on the Blocks admin screen. */ ?>
			<?php _e( 'Featured Image', 'strong-testimonials' ); ?>
		</label>
		<div class="inline">
			<p class="description">
				<?php /* translators: This is on the Blocks admin screen. Refers to your custom fields. */ ?>
				<?php _e( '(if included in Fields)', 'strong-testimonials' ); ?>
			</p>
		</div>
		<div class="inline then then_images" style="display: none;">
			<div class="inline">
				<label for="block-thumbnail_size" class="">Size</label>
				<select id="block-thumbnail_size" class="if select" name="block[data][thumbnail_size]">
					<?php foreach ( $image_sizes as $key => $size ) : ?>
						<option<?php if ( 'custom' == $key ) echo ' class="trip"'; ?> value="<?php echo $key; ?>"<?php selected( $key, $block['thumbnail_size'] ); ?>><?php echo $size['label']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="inline tight then then_thumbnail_size">
				<label for="thumbnail_width" class="">width</label>
				<input id="thumbnail_width" class="input-number" type="text" name="block[data][thumbnail_width]" value="<?php echo $block['thumbnail_width']; ?>" />px
			</div>
			<div class="inline tight then then_thumbnail_size">
				<label for="thumbnail_height" class="">height</label>
				<input id="thumbnail_height" class="input-number" type="text" name="block[data][thumbnail_height]" value="<?php echo $block['thumbnail_height']; ?>" />px
			</div>
		<div>
	</th>
