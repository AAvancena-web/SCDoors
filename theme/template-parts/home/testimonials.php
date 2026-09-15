<?php
/** Testimonials and brand logos. @package SCDoors */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<section class="scd-section" id="testimonials">
	<div class="scd-container">
		<div class="scd-sec-head scd-center scd-reveal">
			<?php if ( $e = scd_field( 'reviews_eyebrow' ) ) : ?>
				<span class="scd-eyebrow"><?php echo esc_html( $e ); ?></span>
			<?php endif; ?>
			<?php if ( $h = scd_field( 'reviews_heading' ) ) : ?>
				<h2><?php echo esc_html( $h ); ?></h2>
			<?php endif; ?>
			<?php if ( $t = scd_field( 'reviews_text' ) ) : ?>
				<p><?php echo esc_html( $t ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( have_rows( 'reviews' ) ) : ?>
			<div class="scd-reviews">
				<?php while ( have_rows( 'reviews' ) ) : the_row();
					$name = (string) get_sub_field( 'name' ); ?>
					<article class="scd-review">
						<?php echo scd_stars(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<blockquote><?php echo esc_html( get_sub_field( 'quote' ) ); ?></blockquote>
						<div class="scd-review__person">
							<span class="scd-avatar"><?php echo esc_html( strtoupper( mb_substr( $name, 0, 1 ) ) ); ?></span>
							<div>
								<strong><?php echo esc_html( $name ); ?></strong>
								<span><?php echo esc_html( get_sub_field( 'meta' ) ); ?></span>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<?php scd_section_cta( scd_field( 'reviews_cta_text' ), scd_field( 'reviews_cta_label', false, 'Get a Free Quote' ) ); ?>

		<?php if ( have_rows( 'brands' ) ) : ?>
			<div class="scd-brands">
				<?php while ( have_rows( 'brands' ) ) : the_row();
					$img = get_sub_field( 'image' );
					if ( empty( $img['url'] ) ) { continue; } ?>
					<img src="<?php echo esc_url( $img['url'] ); ?>"
						alt="<?php echo esc_attr( $img['alt'] ); ?>"
						<?php echo ! empty( $img['width'] ) ? 'width="' . esc_attr( $img['width'] ) . '"' : ''; ?>
						<?php echo ! empty( $img['height'] ) ? 'height="' . esc_attr( $img['height'] ) . '"' : ''; ?>
						loading="lazy">
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
