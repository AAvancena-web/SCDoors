<?php
/** Services. @package SCDoors */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<section class="scd-section" id="services">
	<div class="scd-container">
		<div class="scd-sec-head scd-center scd-reveal">
			<?php if ( $e = scd_field( 'services_eyebrow' ) ) : ?>
				<span class="scd-eyebrow"><?php echo esc_html( $e ); ?></span>
			<?php endif; ?>
			<?php if ( $h = scd_field( 'services_heading' ) ) : ?>
				<h2><?php echo esc_html( $h ); ?></h2>
			<?php endif; ?>
			<?php if ( $t = scd_field( 'services_text' ) ) : ?>
				<p><?php echo esc_html( $t ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( have_rows( 'services' ) ) : ?>
			<div class="scd-cards-3">
				<?php while ( have_rows( 'services' ) ) : the_row();
					$img = get_sub_field( 'image' ); ?>
					<article class="scd-service-card scd-reveal" data-anim="up">
						<div class="scd-service-card__media">
							<?php if ( $tag = get_sub_field( 'tag' ) ) : ?>
								<span class="scd-service-card__tag"><?php echo esc_html( $tag ); ?></span>
							<?php endif; ?>
							<?php if ( ! empty( $img['url'] ) ) : ?>
								<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="lazy">
							<?php endif; ?>
						</div>
						<div class="scd-service-card__body">
							<h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
							<p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
							<?php if ( $link = get_sub_field( 'link' ) ) : ?>
								<a class="scd-link-arrow" href="<?php echo esc_url( $link ); ?>">
									<?php esc_html_e( 'Learn more', 'siteorigin-corp' ); ?> <?php scd_the_icon( 'arrow' ); ?>
								</a>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<?php scd_section_cta( scd_field( 'services_cta_text' ), scd_field( 'services_cta_label', false, 'Get a Free Quote' ) ); ?>
	</div>
</section>
