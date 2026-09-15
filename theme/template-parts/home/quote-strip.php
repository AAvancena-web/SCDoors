<?php
/** Full width quote strip. @package SCDoors */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bg = scd_field( 'strip_bg' );
$h  = scd_field( 'strip_heading' );
if ( ! $h ) { return; }
?>
<section class="scd-cta-strip"<?php echo ! empty( $bg['url'] ) ? ' style="background-image:url(\'' . esc_url( $bg['url'] ) . '\')"' : ''; ?>>
	<div class="scd-container">
		<?php if ( $e = scd_field( 'strip_eyebrow' ) ) : ?>
			<span class="scd-eyebrow"><?php echo esc_html( $e ); ?></span>
		<?php endif; ?>
		<h2><?php echo esc_html( $h ); ?></h2>
		<?php if ( $t = scd_field( 'strip_text' ) ) : ?>
			<p><?php echo esc_html( $t ); ?></p>
		<?php endif; ?>
		<div class="scd-cta-strip__btns">
			<a class="scd-btn scd-btn--primary scd-btn--lg" href="#scd-contact"><?php esc_html_e( 'Get a Free Quote', 'siteorigin-corp' ); ?></a>
			<a class="scd-btn scd-btn--ghost scd-btn--lg" href="tel:<?php echo esc_attr( scd_phone_link() ); ?>">
				<?php scd_the_icon( 'phone' ); ?>
				<?php echo esc_html( 'Call ' . scd_phone() ); ?>
			</a>
		</div>
	</div>
</section>
