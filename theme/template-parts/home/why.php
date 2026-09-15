<?php
/** Why choose us, plus accreditation badges. @package SCDoors */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<section class="scd-section scd-section--soft" id="why-us">
	<div class="scd-container">
		<div class="scd-sec-head scd-center scd-reveal">
			<?php if ( $e = scd_field( 'why_eyebrow' ) ) : ?>
				<span class="scd-eyebrow"><?php echo esc_html( $e ); ?></span>
			<?php endif; ?>
			<?php if ( $h = scd_field( 'why_heading' ) ) : ?>
				<h2><?php echo esc_html( $h ); ?></h2>
			<?php endif; ?>
			<?php if ( $t = scd_field( 'why_text' ) ) : ?>
				<p><?php echo esc_html( $t ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( have_rows( 'why_cards' ) ) : ?>
			<div class="scd-why-grid">
				<?php while ( have_rows( 'why_cards' ) ) : the_row(); ?>
					<article class="scd-why-card scd-reveal" data-anim="up">
						<div class="scd-why-card__icon"><?php scd_the_icon( get_sub_field( 'icon' ) ); ?></div>
						<h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
						<p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
					</article>
				<?php endwhile; ?>

				<?php if ( $cta = scd_field( 'why_cta_heading' ) ) : ?>
					<div class="scd-why-cta scd-reveal">
						<h3><?php echo esc_html( $cta ); ?></h3>
						<p><?php echo esc_html( scd_field( 'why_cta_text' ) ); ?></p>
						<a class="scd-btn scd-btn--primary" href="#scd-contact"><?php esc_html_e( 'Get in Touch', 'siteorigin-corp' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( have_rows( 'badges' ) ) : ?>
			<div class="scd-badges">
				<?php while ( have_rows( 'badges' ) ) : the_row();
					$img = get_sub_field( 'image' ); ?>
					<div class="scd-badge scd-reveal" data-anim="up">
						<?php if ( ! empty( $img['url'] ) ) : ?>
							<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="lazy">
						<?php endif; ?>
						<h4><?php echo esc_html( get_sub_field( 'title' ) ); ?></h4>
						<p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
