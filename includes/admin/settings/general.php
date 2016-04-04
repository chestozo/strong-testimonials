<?php
/**
 * Settings
 *
 * @package Strong_Testimonials
 * @since 1.13
 */

$options = get_option( 'wpmtst_options' );
?>
<h3>Admin</h3>
<table class="form-table" cellpadding="0" cellspacing="0">

	<tr valign="top">
		<th scope="row">
			<?php _e( 'Reordering', 'strong-testimonials' ); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wpmtst_options[reorder]" <?php checked( $options['reorder'] ); ?>>
				<?php _e( 'Enable drag-and-drop reordering in the testimonial list. Off by default.', 'strong-testimonials' ); ?>
			</label>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<?php _e( 'Custom Fields Meta Box', 'strong-testimonials' ); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wpmtst_options[support_custom_fields]" <?php checked( $options['support_custom_fields'] ); ?>>
				<?php _e( 'Show the <strong>Custom Fields</strong> meta box in the testimonial post editor. This does not affect the <strong>Client Fields</strong> meta box. Off by default.', 'strong-testimonials' ); ?>
				<p class="description">For advanced users.</p>
			</label>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<?php _e( 'Troubleshooting', 'strong-testimonials' ); ?>
		</th>
		<td>
			<p>
				<span style="display: inline-block; margin-right: 20px; vertical-align: middle;">Notification Emails</span>
				<label style="display: inline-block; vertical-align: middle;">
					<select id="email_log_level" name="wpmtst_options[email_log_level]">
						<option value="0" <?php selected( $options['email_log_level'], 0 ); ?>>
							<?php _e( 'Log nothing', 'strong-testimonials' ); ?>
						</option>
						<option value="1" <?php selected( $options['email_log_level'], 1 ); ?>>
							<?php _e( 'Log failed emails only (default)', 'strong-testimonials' ); ?>
						</option>
						<option value="2" <?php selected( $options['email_log_level'], 2 ); ?>>
							<?php _e( 'Log both successful and failed emails', 'strong-testimonials' ); ?>
						</option>
					</select>
				</label>
			</p>
			<?php if ( file_exists( WPMTST_DIR . 'strong-debug.log' ) ) : ?>
				<p><a href="<?php echo WPMTST_URL . 'strong-debug.log'; ?>" download="strong-testimonials.log">Download log file</a></p>
			<?php else : ?>
				<p><em>No log file yet.</em></p>
			<?php endif; ?>
		</td>
	</tr>

</table>

<h3>Output</h3>
<table class="form-table" cellpadding="0" cellspacing="0">

	<tr valign="top">
		<th scope="row">
			<?php _e( 'Scroll Top', 'strong-testimonials' ); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wpmtst_options[scrolltop]" <?php checked( $options['scrolltop'] ); ?>>
				<?php _e( 'In paginated Views, scroll to the top when a new page is selected. On by default.', 'strong-testimonials' ); ?>
			</label>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<?php _e( 'Remove Whitespace', 'strong-testimonials' ); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wpmtst_options[remove_whitespace]" <?php checked( $options['remove_whitespace'] ); ?>>
				<?php _e( 'Remove space between HTML tags in View output to prevent double paragraphs <em>(wpautop)</em>. On by default.', 'strong-testimonials' ); ?>
			</label>
		</td>
	</tr>


	<tr valign="top">
		<th scope="row">
			<?php _e( 'Allow comments', 'strong-testimonials' ); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wpmtst_options[support_comments]" <?php checked( $options['support_comments'] ); ?>>
				<?php _e( 'Allow comments on new testimonials. Requires using your theme\'s single post template. Off by default.', 'strong-testimonials' ); ?>
				<p class="description">To enable comments:</p>
				<ul class="description">
					<li>For individual testimonials, use the <strong>Discussion</strong> meta box in the post editor or <strong>Quick Edit</strong> in the testimonial list.</li>
					<li>For multiple testimonials, use <strong>Bulk Edit</strong> in the testimonial list.</li>
				</ul>
			</label>
		</td>
	</tr>

</table>
