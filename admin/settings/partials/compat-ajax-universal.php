<?php
/**
 * Universal (timer)
 */
?>
<div class="row">
  <div>
    <label for="method-universal">
      <input type="radio" id="method-universal" name="wpmtst_compat_options[ajax][method]" value="universal"
					<?php checked( $options['ajax']['method'], 'universal' ); ?>
             data-group="universal"/>
			<?php _e( 'Universal', 'strong-testimonials' ); ?>
    </label>
  </div>
  <div>
    <span class="about"><?php _e( 'about this option', 'strong-testimonials' ); ?></span>
  </div>
</div>

<div class="row" data-sub="universal">
  <div class="radio-sub">
    <label for="universal-timer">
			<?php _ex( 'Check every', 'timer setting', 'strong-testimonials' ); ?>
    </label>
  </div>
  <div>
    <input type="number" id="universal-timer" name="wpmtst_compat_options[ajax][universal_timer]"
           min=".1" max="5" step=".1"
           value="<?php echo $options['ajax']['universal_timer']; ?>" size="3">
		<?php _ex( 'seconds', 'timer setting', 'strong-testimonials' ); ?>
  </div>
</div>
