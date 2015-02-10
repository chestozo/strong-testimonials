<?php
/**
 * Template Name: Default Testimonials Page Template
 *
 * Used by the plugin for the [strong] shortcode and blocks.
 * Not for use in a theme.
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */
?>
<div style="font-size: 0.8em; padding: 0 5px; background: blue; color: white;"><?php echo __FILE__; ?></div>
<!-- Strong Testimonials default page template -->
<div class="strong-container page <?php echo $container_class_list; ?>">
	<div class="strong-content <?php echo $content_class_list; ?>">
	
		<?php /* Nested Loop */ ?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
		<div class="<?php echo $post_class_list; ?> post-<?php echo the_ID(); ?>">
			<div class="inner">
			
				<?php if ( $title && get_the_title() ) : ?>
				<h3 class="heading"><?php echo the_title(); ?></h3>
				<?php endif; ?>
				
				<div class="content">
					<?php if ( $thumbnail && has_post_thumbnail( get_the_ID() ) ) : ?>
					<div class="photo"><?php the_post_thumbnail( $thumbnail_size ); ?></div>
					<?php endif; ?>
					
					<?php 
					if ( $excerpt )  // excerpt overrides length 
						echo do_shortcode( the_excerpt() );
					elseif( $length )  // truncated
						echo do_shortcode( wpmtst_get_field( 'truncated', array( 'char_limit' => $length ) ) );
					else  // entire
						echo do_shortcode( wpautop( the_content() ) );
					?>
				</div><!-- .content -->
				
				<?php if ( $has_client_shortcodes || $has_client_section ) : ?>
					<div class="client">
					<?php
					if ( $has_client_shortcodes )
						echo do_child_shortcode( $parent_tag, $shortcode_content );
					else
						echo strong_do_client_section( $client_section );
					?>
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
