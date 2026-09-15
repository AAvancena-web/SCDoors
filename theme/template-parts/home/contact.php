<?php
/** Get in touch: map and form. @package SCDoors */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$map  = scd_field( 'map_embed' );
$form = scd_form( 'contact' );
?>
<section class="scd-section scd-contact" id="scd-contact">
	<div class="scd-container">
		<div class="scd-sec-head scd-center scd-reveal">
			<?php if ( $e = scd_field( 'contact_eyebrow' ) ) : ?>
				<span class="scd-eyebrow"><?php echo esc_html( $e ); ?></span>
			<?php endif; ?>
			<?php if ( $h = scd_field( 'contact_heading' ) ) : ?>
				<h2><?php echo esc_html( $h ); ?></h2>
			<?php endif; ?>
			<?php if ( $t = scd_field( 'contact_text' ) ) : ?>
				<p><?php echo esc_html( $t ); ?></p>
			<?php endif; ?>
		</div>

		<div class="scd-contact-grid">
			<?php if ( $map ) : ?>
				<div class="scd-map-panel scd-reveal" data-anim="left">
					<iframe src="<?php echo esc_url( $map ); ?>"
						title="<?php esc_attr_e( 'Service area map', 'siteorigin-corp' ); ?>"
						loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
					<div class="scd-map-panel__foot">
						<div>
							<strong><?php echo esc_html( scd_field( 'map_note_title' ) ); ?></strong>
							<span><?php echo esc_html( scd_field( 'map_note_text' ) ); ?></span>
						</div>
						<a class="scd-btn scd-btn--dark" href="<?php echo esc_url( scd_map_link() ); ?>" target="_blank" rel="noopener">
							<?php esc_html_e( 'Open in Maps', 'siteorigin-corp' ); ?>
						</a>
					</div>
				</div>
			<?php endif; ?>

			<div class="scd-form-panel scd-reveal" data-anim="right">
				<h3><?php echo esc_html( scd_field( 'form_heading', false, 'Send Us a Message' ) ); ?></h3>
				<p><?php echo esc_html( scd_field( 'form_text' ) ); ?></p>
				<?php echo $form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
</section>
