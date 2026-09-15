<?php
/** FAQ with contact details in the left column. @package SCDoors */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! have_rows( 'faqs' ) ) { return; }
$hours = scd_option( 'scd_hours', '' );
?>
<section class="scd-section scd-faq-section" id="faq">
	<div class="scd-container">
		<div class="scd-sec-head scd-center scd-reveal">
			<?php if ( $e = scd_field( 'faq_eyebrow' ) ) : ?>
				<span class="scd-eyebrow"><?php echo esc_html( $e ); ?></span>
			<?php endif; ?>
			<?php if ( $h = scd_field( 'faq_heading' ) ) : ?>
				<h2><?php echo esc_html( $h ); ?></h2>
			<?php endif; ?>
			<?php if ( $t = scd_field( 'faq_text' ) ) : ?>
				<p><?php echo esc_html( $t ); ?></p>
			<?php endif; ?>
		</div>

		<div class="scd-faq-layout">
			<aside class="scd-faq-aside">
				<div class="scd-faq-aside__head scd-reveal">
					<h3><?php echo esc_html( scd_field( 'faq_aside_heading' ) ); ?></h3>
					<p><?php echo esc_html( scd_field( 'faq_aside_text' ) ); ?></p>
				</div>

				<div class="scd-contact-info">
					<div class="scd-info-card scd-reveal">
						<div class="scd-info-card__icon"><?php scd_the_icon( 'phone' ); ?></div>
						<div class="scd-info-card__text">
							<h4><?php esc_html_e( 'Call Us', 'siteorigin-corp' ); ?></h4>
							<a href="tel:<?php echo esc_attr( scd_phone_link() ); ?>"><?php echo esc_html( scd_phone() ); ?></a>
						</div>
					</div>
					<div class="scd-info-card scd-reveal">
						<div class="scd-info-card__icon"><?php scd_the_icon( 'mail' ); ?></div>
						<div class="scd-info-card__text">
							<h4><?php esc_html_e( 'Email Us', 'siteorigin-corp' ); ?></h4>
							<a href="mailto:<?php echo esc_attr( scd_email() ); ?>"><?php echo esc_html( scd_email() ); ?></a>
						</div>
					</div>
					<div class="scd-info-card scd-reveal">
						<div class="scd-info-card__icon"><?php scd_the_icon( 'pin' ); ?></div>
						<div class="scd-info-card__text">
							<h4><?php esc_html_e( 'Service Area', 'siteorigin-corp' ); ?></h4>
							<p><?php echo esc_html( scd_address() ); ?></p>
						</div>
					</div>
					<?php if ( $hours ) : ?>
						<div class="scd-info-card scd-reveal">
							<div class="scd-info-card__icon"><?php scd_the_icon( 'clock' ); ?></div>
							<div class="scd-info-card__text">
								<h4><?php esc_html_e( 'Opening Hours', 'siteorigin-corp' ); ?></h4>
								<p><?php echo esc_html( $hours ); ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</aside>

			<div class="scd-faq">
				<?php while ( have_rows( 'faqs' ) ) : the_row(); ?>
					<div class="scd-faq-item">
						<button type="button" aria-expanded="false">
							<?php echo esc_html( get_sub_field( 'question' ) ); ?>
							<span class="scd-plus"><?php scd_the_icon( 'plus' ); ?></span>
						</button>
						<div class="scd-faq-item__body">
							<p><?php echo esc_html( get_sub_field( 'answer' ) ); ?></p>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</section>
