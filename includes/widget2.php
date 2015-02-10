<?php
/**
 * Strong Testimonials - Widget2
 */

class Strong_Testimonials_Widget extends WP_Widget {

	// -----
	// setup
	// -----
	function Strong_Testimonials_Widget() {

		$widget_ops = array(
				'classname'   => 'strong_testimonials-widget',
				'description' => _x( 'Add a block of your Strong Testimonials.', 'description', 'strong-testimonials' )
		);

		$control_ops = array(
				'id_base' => 'strong_testimonials-widget',
		);

		$this->WP_Widget( 'strong_testimonials-widget', _x( 'Testimonials Block', 'widget title', 'strong-testimonials' ), $widget_ops, $control_ops );

		$this->defaults = array(
				'title'         => _x( 'Strong Testimonials Block', 'widget title', 'strong-testimonials' ),
				'block'         => 'base',
		);

	}

	// -------
	// display
	// -------
	function widget( $args, $instance ) {
		echo do_shortcode( '[strong block="base"]' );
	}

	// ----
	// form
	// ----
	function form( $instance ) {
		echo 'base';
	}

	// ----
	// save
	// ----
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$defaults = $this->defaults;
		return $instance;
	}

}


/*
 * Load widget
 */
function wpmtst_load_widget2() {
	register_widget( 'Strong_Testimonials_Widget' );
}
add_action( 'widgets_init', 'wpmtst_load_widget2' );
