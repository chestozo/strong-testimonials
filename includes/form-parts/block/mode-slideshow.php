	<th>
		<div class="inline">
			<label for="block-show_for">
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php _ex( 'Show each for', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
			<input type="text" id="block-show_for" name="block[data][show_for]" value="<?php echo $block['show_for']; ?>" size="3" />
			<?php /* translators: This is on the Blocks admin screen. */ ?>
			<?php _ex( 'seconds', 'time setting', 'strong-testimonials' ); ?>
		</div>
		<div class="inline">
			<label for="block-effect_for">
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php _ex( 'Fade transition for', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
			<input type="text" id="block-effect_for" name="block[data][effect_for]" value="<?php echo $block['effect_for']; ?>" size="3" />
			<?php /* translators: This is on the Blocks admin screen. */ ?>
			<?php _ex( 'seconds', 'time setting', 'strong-testimonials' ); ?>
		</div>
		<div class="inline">
			<input type="checkbox" id="block-no_pause" name="block[data][no_pause]" value="0" <?php checked( ! $block['no_pause'] ); ?> class="checkbox" />
			<label for="block-no_pause">
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php _ex( 'Pause on hover', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
		</div>
	</th>
