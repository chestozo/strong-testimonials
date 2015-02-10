	<th>
		<?php /* translators: This is on the Blocks admin screen. */ ?>
		<?php _e( 'Target page', 'strong-testimonials' ); ?>
	</th>
	<td>
		<div>
			<select id="block-page" name="block[data][page]" autocomplete="off">
				<option value="">
					<?php /* translators: This is on the Blocks admin screen. */ ?>
					<?php // _ex( '— Select a page —', '"Read more" target page', 'strong-testimonials' ); ?>
					&mdash;
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
