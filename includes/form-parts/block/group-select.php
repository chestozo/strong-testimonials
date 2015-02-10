<div class="then then_display then_form then_slideshow" style="display: none;">
	<?php /* translators: This is on the Blocks admin screen. */ ?>
	
	<div class="then then_display then_not_form then_slideshow" style="display: none;">
		<h3><?php _e( 'Select Testimonials', 'strong-testimonials' ); ?></h3>
	</div>
	<div class="then then_not_display then_form then_not_slideshow" style="display: none;">
		<h3><?php _e( 'Assign new submissions', 'strong-testimonials' ); ?></h3>
	</div>
	
	<table class="form-table multiple group-select" cellpadding="0" cellspacing="0">
		<tr valign="top" style="display: none;" class="then then_display then_not_form then_not_slideshow">
			<?php include( 'option-id.php' ); ?>
		</tr>
		<tr valign="top" style="display: none;" class="then then_display then_form then_slideshow then_not_id" >
			<?php include( 'option-category.php' ); ?>
		</tr>
		<tr valign="top" style="display: none;" class="then then_display then_not_form then_slidesho then_not_id">
			<?php include( 'option-order.php' ); ?>
		</tr>
		<tr valign="top" style="display: none;" class="then then_display then_not_form then_slideshow then_not_id">
			<?php include( 'option-limit.php' ); ?>
		</tr>
	</table>
</div>
