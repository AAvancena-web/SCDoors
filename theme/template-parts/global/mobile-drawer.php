<?php
/**
 * Mobile drawer.
 *
 * Deliberately a sibling of the header, not a child. The header carries a
 * backdrop-filter, which makes it the containing block for fixed position
 * descendants, and a drawer nested inside it can never fill the viewport.
 *
 * @package SCDoors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$scd_phone_link = scd_phone_link();
$scd_hours      = scd_option( 'scd_hours', '' );
$scd_facebook   = scd_option( 'scd_facebook', '' );
$scd_instagram  = scd_option( 'scd_instagram', '' );
?>
<nav class="scd-mobile-nav scd-scope" id="scdDrawer" aria-label="<?php esc_attr_e( 'Mobile', 'siteorigin-corp' ); ?>">
	<div class="scd-mobile-nav__inner">

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'menu-1',
				'menu_class'     => 'scd-m-menu',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => false,
				'walker'         => new SCD_Drawer_Walker(),
			)
		);
		?>

		<div class="scd-mobile-nav__cta">
			<a class="scd-btn scd-btn--primary scd-btn--block scd-btn--lg" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
				<?php esc_html_e( 'Get a Free Quote', 'siteorigin-corp' ); ?>
			</a>
			<a class="scd-btn scd-btn--dark scd-btn--block scd-btn--lg" href="tel:<?php echo esc_attr( $scd_phone_link ); ?>">
				<?php scd_the_icon( 'phone' ); ?> <?php echo esc_html( 'Call ' . scd_phone() ); ?>
			</a>
		</div>

		<ul class="scd-mobile-nav__meta">
			<li><?php scd_the_icon( 'mail' ); ?><a href="mailto:<?php echo esc_attr( scd_email() ); ?>"><?php echo esc_html( scd_email() ); ?></a></li>
			<li><?php scd_the_icon( 'pin' ); ?><span><?php echo esc_html( scd_address() ); ?></span></li>
			<?php if ( $scd_hours ) : ?>
				<li><?php scd_the_icon( 'clock' ); ?><span><?php echo esc_html( $scd_hours ); ?></span></li>
			<?php endif; ?>
		</ul>

		<?php if ( $scd_facebook || $scd_instagram ) : ?>
			<div class="scd-mobile-nav__social">
				<?php if ( $scd_facebook ) : ?>
					<a href="<?php echo esc_url( $scd_facebook ); ?>" target="_blank" rel="noopener" aria-label="Facebook"><?php scd_the_icon( 'facebook' ); ?></a>
				<?php endif; ?>
				<?php if ( $scd_instagram ) : ?>
					<a href="<?php echo esc_url( $scd_instagram ); ?>" target="_blank" rel="noopener" aria-label="Instagram"><?php scd_the_icon( 'instagram' ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	</div>
</nav>
