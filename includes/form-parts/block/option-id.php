	<th>
		<label for="block-id">
		<?php /* translators: This is on the Blocks admin screen. */ ?>
		<?php _e( 'Testimonial ID', 'strong-testimonials' ); ?>
		</label>
	</th>
	<td>
		<div>
			<select id="block-id" name="block[data][id]" class="if selectany" autocomplete="off">
				<option value="">
					<?php /* translators: This is on the Blocks admin screen. */ ?>
					<?php _e( '— select a testimonial —', 'strong-testimonials' ); ?>
				</option>
				<?php foreach ( $posts_list as $post ) : ?>
				<option value="<?php echo $post->ID; ?>" <?php selected( $block['id'], $post->ID ); ?>>
					<?php echo sprintf( '%d: %s', $post->ID, $post->post_title ? $post->post_title : '(untitled)' ); ?>
				</option>
				<?php endforeach; ?>
			</select>
			<label for="block-post_id">
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php _ex( 'or enter its ID or slug', 'testimonial post', 'strong-testimonials' ); ?>
			</label>
			<input type="text" id="block-post_id" name="block[data][post_id]" size="30" />
		</div>
	</td>
