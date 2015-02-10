	<th colspan="2">
		<div>
			<div class="inline checkbox">
				<input type="checkbox" id="block-more_post" class="if toggle" name="block[data][more_post]" value="1" <?php checked( $block['more_post'] );?> class="checkbox" />
				<label for="block-more_post">
					<?php /* translators: This is on the Blocks admin screen. */ ?>
					<?php _e( '"Read more" link to the testimonial', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div class="inline then_more_post">
				<label for="block-more_text" class="">
					<?php /* translators: This is on the Blocks admin screen. */ ?>
					<?php _e( 'Link text', 'strong-testimonials' ); ?>
				</label>
				<input type="text" id="block-more_text" name="block[data][more_text]" value="<?php echo $block['more_text']; ?>" size="30" />
			</div>
		</div>
		<p class="description under-checkbox">
			<?php /* translators: This is on the Blocks admin screen. */ ?>
			<?php _e( 'Typically used with excerpts and truncated content.', 'strong-testimonials' ); ?>
		</p>
	</th>
