<?php
/**
 * Theme footer, 2026 design.
 *
 * @package SCDoors
 */

$scd_facebook  = scd_option( 'scd_facebook', '' );
$scd_instagram = scd_option( 'scd_instagram', '' );
$scd_hours     = scd_option( 'scd_hours', '' );
?>

		</div><!-- .corp-container -->
	</div><!-- #content -->

	<?php
	if ( siteorigin_page_setting( 'footer', true ) ) {
		do_action( 'siteorigin_corp_footer_before' );
		?>

		<footer class="scd-site-footer scd-scope" id="colophon">
			<?php do_action( 'siteorigin_corp_footer_top' ); ?>

			<div class="scd-container">
				<div class="scd-footer-grid">

					<div>
						<?php
						$scd_logo_id = get_theme_mod( 'custom_logo' );
						if ( $scd_logo_id ) {
							echo wp_get_attachment_image( $scd_logo_id, 'full', false, array( 'class' => 'scd-flogo', 'alt' => get_bloginfo( 'name' ) ) );
						} else {
							echo '<p class="scd-flogo">' . esc_html( get_bloginfo( 'name' ) ) . '</p>';
						}
						?>
						<p><?php echo esc_html( scd_option( 'scd_footer_about', '' ) ); ?></p>

						<?php if ( $scd_facebook || $scd_instagram ) : ?>
							<div class="scd-footer-social">
								<?php if ( $scd_facebook ) : ?>
									<a href="<?php echo esc_url( $scd_facebook ); ?>" target="_blank" rel="noopener" aria-label="Facebook"><?php scd_the_icon( 'facebook' ); ?></a>
								<?php endif; ?>
								<?php if ( $scd_instagram ) : ?>
									<a href="<?php echo esc_url( $scd_instagram ); ?>" target="_blank" rel="noopener" aria-label="Instagram"><?php scd_the_icon( 'instagram' ); ?></a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>

					<div>
						<h4><?php esc_html_e( 'Quick Links', 'siteorigin-corp' ); ?></h4>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer-quick',
								'container'      => false,
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</div>

					<div>
						<h4><?php esc_html_e( 'Our Services', 'siteorigin-corp' ); ?></h4>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer-services',
								'container'      => false,
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</div>

					<div>
						<h4><?php esc_html_e( 'Contact Us', 'siteorigin-corp' ); ?></h4>
						<ul class="scd-footer-contact">
							<li><?php scd_the_icon( 'pin' ); ?><a href="<?php echo esc_url( scd_map_link() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( scd_address() ); ?></a></li>
							<li><?php scd_the_icon( 'phone' ); ?><a href="tel:<?php echo esc_attr( scd_phone_link() ); ?>"><?php echo esc_html( scd_phone() ); ?></a></li>
							<li><?php scd_the_icon( 'mail' ); ?><a href="mailto:<?php echo esc_attr( scd_email() ); ?>"><?php echo esc_html( scd_email() ); ?></a></li>
							<?php if ( $scd_hours ) : ?>
								<li><?php scd_the_icon( 'clock' ); ?><span><?php echo esc_html( $scd_hours ); ?></span></li>
							<?php endif; ?>
						</ul>
					</div>

				</div>

				<div class="scd-footer-bottom">
					<span><?php echo esc_html( sprintf( __( 'Copyright %1$s %2$s. All rights reserved.', 'siteorigin-corp' ), gmdate( 'Y' ), get_bloginfo( 'name' ) ) ); ?></span>
					<span><?php esc_html_e( 'Designed by', 'siteorigin-corp' ); ?> <a href="https://www.digitalmovement.com.au/" target="_blank" rel="noopener">Digital Movement</a></span>
				</div>
			</div>

			<?php do_action( 'siteorigin_corp_footer_bottom' ); ?>
		</footer>
	<?php } ?>
</div><!-- #page -->

<button class="scd-to-top scd-scope" id="scdToTop" type="button" aria-label="<?php esc_attr_e( 'Back to top', 'siteorigin-corp' ); ?>">
	<?php scd_the_icon( 'up' ); ?>
</button>

<a class="scd-call-float scd-scope" href="tel:<?php echo esc_attr( scd_phone_link() ); ?>">
	<?php scd_the_icon( 'phone' ); ?>
	<?php esc_html_e( 'Call Now', 'siteorigin-corp' ); ?>
</a>

<?php wp_footer(); ?>
<?php do_action( 'siteorigin_corp_footer_after' ); ?>
</body>
</html>
