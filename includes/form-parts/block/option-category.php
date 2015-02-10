	<th>
		<?php /* translators: This is on the Blocks admin screen. */ ?>
		<?php _e( 'Categories', 'strong-testimonials' ); ?>
	</th>
	<td>
		<div id="block-category">
			<?php if ( $category_ids ) : ?>
			<div class="checkbox-toggle">
				<input type="checkbox" id="block_category_all" name="block[data][category_all]" value="all" <?php checked( 'all', $block['category'] ); ?>>
				<?php _e( 'All', 'strong-testimonials' ); ?>
			</div>
			<ul id="block_category_list" class="checkbox-horizontal">
				<?php foreach ( $category_list as $cat ) : ?>
					<li>
						<input type="checkbox" name="block[data][category][]" value="<?php echo $cat->term_id; ?>" <?php checked( in_array( $cat->term_id, $block_cats_array ) ); ?>>
						<?php echo $cat->name . ' (' . $cat->count . ')'; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php else : ?>
			<input type="hidden" name="block[data][category_all]" value="all">
			<p class="description">No categories found.</p>
			<?php endif; ?>
		</div>
	</td>
