	<th>
		<?php /* translators: This is on the Blocks admin screen. */ ?>
		<?php _e( 'CSS classes', 'strong-testimonials' ); ?>
	</th>
	<td>
		<div style="display: none;" class="then then_display then_form then_slideshow input">
			<input type="text" id="block-class" name="block[data][class]" value="<?php echo $block['class']; ?>" size="30" autocomplete="off" />
			<?php /* translators: This is on the Blocks admin screen. */ ?>
			<p class="description"><?php _e( 'separated by commas', 'strong-testimonials' ); ?></p>
		</div>
	</td>
