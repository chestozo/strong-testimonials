	<th>
		<?php /* translators: This is on the Blocks admin screen. */ ?>
		<?php _e( 'Testimonial Content', 'strong-testimonials' ); ?>
	</th>
	<td>
		<table class="fields">
			<tr>
				<td>
					<div class="radio">
						<label>
							<input type="radio" name="block[data][content]" <?php checked( 'entire', $block['content'] ); ?> value="entire" />
							<?php /* translators: This is on the Blocks admin screen. */ ?>
							<?php _ex( 'Entire content', 'display setting', 'strong-testimonials' ); ?>
						</label>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="radio">
						<label>
							<input type="radio" name="block[data][content]" <?php checked( 'truncated', $block['content'] ); ?> value="truncated" />
							<?php $input = '<input id="block-length" type="number" min="10" max="995" step="5" name="block[data][length]" value="' . $block['length'] . '" size="3" />'; ?>
							<?php /* translators: This is on the Blocks admin screen. %s is a number input field. */ ?>
							<?php printf( _x( 'Content up to %s characters', 'display setting', 'strong-testimonials' ), $input ); ?>
						</label>
						<p class="description">
							<?php /* translators: This is on the Blocks admin screen. */ ?>
							<?php _e( 'Will break on a space and add an ellipsis.', 'strong-testimonials' ); ?>
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="radio">
						<label>
							<input type="radio" name="block[data][content]" <?php checked( 'excerpt', $block['content'] ); ?> value="excerpt" />
							<?php /* translators: This is on the Blocks admin screen. */ ?>
							<?php _ex( 'Excerpt', 'the testimonial excerpt', 'strong-testimonials' ); ?>
							<p class="description">
								<?php /* translators: This is on the Blocks admin screen. */ ?>
								<?php _e( 'Excerpts are hand-crafted summaries of your testimonial.', 'strong-testimonials' ); ?>
								<br />
								<?php /* translators: This is on the Blocks admin screen. */ ?>
								<?php _e( 'You may need to enable them in the post editor like in this <a id="toggle-screen-options">screenshot</a>.', 'strong-testimonials' ); ?>
							</p>
						</label>
					</div>
				</td>
			</tr>
		</table>
		<div class="screenshot" id="screenshot-screen-options" style="display: none;">
			<div style="background: url(<?php echo WPMTST_DIR; ?>/images/screen-options.png); height: 241px; width: 730px;"></div>
		</div>
	</td>
