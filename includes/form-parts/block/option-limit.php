	<th>
		<?php /* translators: This is on the Blocks admin screen. */ ?>
		<?php _ex( 'Quantity', 'strong-testimonials' ); ?>
	</th>
	<td>
		<select id="block-count" name="block[data][all]" class="if select">
			<option value="1" <?php selected( $block['all'] ); ?> />
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php _e( 'All', 'strong-testimonials' ); ?>
			</option>
			<option class="trip" value="0" <?php selected( ! $block['all'] ); ?> />
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php _ex( 'Count:', 'noun', 'strong-testimonials' ); ?>
			</option>
		</select>
		<input id="block-count" class="then_count" type="number" min="1" name="block[data][count]" value="<?php echo $block['count']; ?>" size="5" style="display: none;" />
	</td>
