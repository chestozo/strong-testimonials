	<th>
		<label class="checkbox">
			<input type="checkbox" id="block-pagination" class="if toggle" name="block[data][pagination]" value="1" <?php checked( $block['pagination'] ); ?> class="checkbox" />
			<?php /* translators: This is on the Blocks admin screen. */ ?>
			<?php _e( 'Pagination', 'strong-testimonials' ); ?>
		</label>
	</th>
	<td>
		<div class="inline checkbox then then_pagination" style="display: none;">
			<label for="block-per_page">
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php _ex( 'Per page', 'quantity', 'strong-testimonials' ); ?>
			</label>
			<input id="block-per_page" style="width: 60px;" type="number" min="1" id="block-per_page" name="block[data][per_page]" value="<?php echo $block['per_page']; ?>" size="3" />
		</div>
		<div class="inline checkbox then then_pagination" style="display: none;">
			<label for="block-nav">
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php _e( 'Page links ', 'strong-testimonials' ); ?>
			</label>
			<select id="block-nav" name="block[data][nav]">
				<option value="before" <?php selected( in_array( 'before', $block['nav'] ) ); ?>>
					<?php /* translators: This is on the Blocks admin screen. */ ?>
					<?php _e( 'before', 'strong-testimonials' ); ?>
				</option>
				<option value="after" <?php selected( in_array( 'after', $block['nav'] ) ); ?>>
					<?php /* translators: This is on the Blocks admin screen. */ ?>
					<?php _e( 'after', 'strong-testimonials' ); ?>
				</option>
				<option value="before,after" <?php selected( in_array( 'before', $block['nav'] ) && in_array( 'after', $block['nav'] ) ); ?>>
					<?php /* translators: This is on the Blocks admin screen. */ ?>
					<?php _e( 'before & after', 'strong-testimonials' ); ?>
				</option>
			</select>
		</div>
	</th>
