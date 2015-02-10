<?php
/**
 * Template Name: Default Testimonials Widget Template
 *
 * Used by the plugin for the [strong] shortcode. 
 * Not for use in a theme.
 *
 * @package Strong_Testimonials
 * @since 1.15.0
 */
?>
<!-- Strong Testimonials default widget template -->
<div class="strong-container widget <?php echo $container_class_list; ?>">
	<div class="strong-content <?php echo $content_class_list; ?>">
	
		<?php /* Nested Loop */ ?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
		<div class="<?php echo $post_class_list; ?> post-<?php echo the_ID(); ?>">
			<div class="inner">
			
				<?php if ( $title && get_the_title() ) : ?>
				<h5 class="heading"><?php echo the_title(); ?></h5>
				<?php endif; ?>
				
				<div class="content">
					<?php if ( $thumbnail && has_post_thumbnail( get_the_ID() ) ) : ?>
					<div class="photo">
					<?php 
						if ( 'custom' == $thumbnail_size )
							the_post_thumbnail( array( $thumbnail_width, $thumbnail_height ) );
						else
							the_post_thumbnail( $thumbnail_size );
					?>					</div>
					<?php endif; ?>
					
					<?php 
					if ( $excerpt ) : // excerpt overrides length 
						$show_content = the_excerpt();
					elseif( $length ) : // truncated
						$show_content = wpmtst_get_field( 'truncated', array( 'char_limit' => $length ) );
					else : // entire
						$show_content = wpautop( the_content() );
					endif;
					echo do_shortcode( $show_content ); 
					?>
				</div><!-- .content -->
				
				<?php if ( $has_client_shortcodes || $has_client_section ) : ?>
					<div class="client">
					<?php if ( $has_client_shortcodes ) : ?>
							<?php echo do_child_shortcode( $parent_tag, $shortcode_content ); ?>
					<?php else : ?>
							<?php echo strong_do_client_section( $client_section ); ?>
					<?php endif; ?>
					</div><!-- .client -->
				<?php endif; ?>
				
				<?php if ( $more_post && ( $excerpt || $length ) ) : ?>
				<div class="readmore"><a href="<?php echo get_permalink(); ?>"><?php echo $more_text; ?></a></div>
				<?php endif; ?>

				<div class="clear"></div>
				
			</div><!-- .inner -->
		</div><!-- .testimonial -->
		
		<?php endwhile; ?>
	</div><!-- .strong-content -->
</div><!-- .strong-container -->
