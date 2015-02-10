	<th colspan="2">
		<input type="checkbox" id="block-title" name="block[data][title]" value="1" <?php checked( $block['title'] ); ?> class="checkbox" />
		<label for="block-title">
			<?php /* translators: This is on the Blocks admin screen. */ ?>
			<?php _ex( 'Title', 'the testimonial title', 'strong-testimonials' ); ?>
		</label>
		<div class="inline">
			<p class="description">
				<?php /* translators: This is on the Blocks admin screen. Refers to your custom fields. */ ?>
				<?php _e( '(if included in Fields)', 'strong-testimonials' ); ?>
			</p>
		</div>
	</th>
