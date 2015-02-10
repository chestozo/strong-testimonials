	<th>
		<?php /* translators: This is on the Blocks admin screen. */ ?>
		<?php _e( 'Template', 'strong-testimonials' ); ?>
	</th>
	<td>
		<!--
		<input type="hidden" name="last_block_template" value="<?php //echo $block['template']; ?>">
		-->
		<select id="block-template" name="block[data][template]"<?php selected( !$block['template'] ); ?>>
			<option value="">
				<?php /* translators: This is on the Blocks admin screen. */ ?>
				<?php //_e( 'use default', 'strong-testimonials' ); ?>
			</option>
			<optgroup label="Theme">
				<?php if ( ! $theme_templates ) : ?>
					<option disabled="disabled">none</option>
				<?php else : ?>
					<?php foreach ( $theme_templates as $name => $file ) : ?>
						<?php
							$file = str_replace( '.php', '', $file );
							$file = str_replace( 'testimonials-', '', $file );
						?>
						<option value="<?php echo $file; ?>"<?php selected( $file, $block['template'] ); ?>><?php echo $name; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</optgroup>
			<optgroup label="Plugin">
				<?php foreach ( $plugin_templates as $name => $file ) : ?>
				<option value="<?php echo $file; ?>"<?php selected( $file, $block['template'] ); ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
			</optgroup>
		</select>
		<!--
		<div class="inline info">template name</div>
		-->
	</td>
<?php
/*
<select id="block-template" name="block[data][template]">
	<option value="">— select a template —</option>
	<optgroup label="Theme">
		<option value="custom">Custom Testimonials</option>
		<option value="testimonials">My Testimonials</option>
	</optgroup>
	<optgroup label="Plugin">
		<option value="default/testimonials-form.php">Default Testimonials Form Template</option>
		<option value="default/testimonials-page.php" selected='selected'>Default Testimonials Page Template</option>
		<option value="default/testimonials-widget.php">Default Testimonials Widget Template</option>
	</optgroup>
</select>
*/
?>
