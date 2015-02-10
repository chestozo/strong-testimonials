	<th>
		<label class="checkbox">
			<input type="checkbox" id="block-read_more" class="if toggle" name="block[data][read_more]" value="1" <?php checked( $block['read_more'] ); ?> class="checkbox" />
			<?php /* translators: This is on the Blocks admin screen. */ ?>
			<?php _e( '"Read More" link to a page', 'strong-testimonials' ); ?>
		</label>
	</th>
	<td>
		<div class="checkbox then then_read_more" style="display: none;">
			<label for="block-page">
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php // _e( 'Target', 'strong-testimonials' ); ?>
			</label>
			<select id="block-page" name="block[data][page]" autocomplete="off">
				<option value="">
					<?php /* translators: This is on the Blocks admin screen. */ ?>
					<?php _ex( '— select a page —', '"Read more" target page', 'strong-testimonials' ); ?>
				</option>
				<?php foreach ( $pages_list as $pages ) : ?>
				<?php // Shortcode takes ID or slug; only ID here ?>
				<option value="<?php echo $pages->ID; ?>" <?php selected( $block['page'], $pages->ID ); ?>><?php echo $pages->post_title; ?></option>
				<?php endforeach; ?>
			</select>
			<label for="block-page_id">
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php _ex( 'or enter its ID or slug', '"Read more" target page', 'strong-testimonials' ); ?>
			</label>
			<input type="text" id="block-page_id" name="block[data][page_id]" size="30" />
		</div>
	</td>
