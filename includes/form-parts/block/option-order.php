	<th>
		<?php /* translators: This is on the Blocks admin screen. */ ?>
		<?php _ex( 'Order', 'noun', 'strong-testimonials' ); ?>
	</th>
	<td>
		<select id="block-order" name="block[data][order]" autocomplete="off">
			<?php foreach ( $order_list as $order => $order_label ) : ?>
			<option value="<?php echo $order; ?>" <?php selected( $order, $block['order'] ); ?>><?php echo $order_label; ?></option>
			<?php endforeach; ?>
		</select>
	</td>
