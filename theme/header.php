<?php
/**
 * Theme header, 2026 design.
 *
 * Markup is prefixed with scd- so none of the legacy child theme rules in
 * style.css apply to it, which keeps the old WPBakery pages untouched.
 *
 * @package SCDoors
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
do_action( 'siteorigin_corp_body_top' );

$scd_phone      = scd_phone();
$scd_phone_link = scd_phone_link();
$scd_topbar     = scd_option( 'scd_topbar_note', '' );
?>

<div class="scd-scroll-progress" id="scdProgress" aria-hidden="true"></div>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'siteorigin-corp' ); ?></a>

	<?php do_action( 'siteorigin_corp_header_before' ); ?>

	<?php if ( siteorigin_page_setting( 'header', true ) ) : ?>

		<div class="scd-topbar scd-scope">
			<div class="scd-container">
				<?php if ( $scd_topbar ) : ?>
					<div class="scd-topbar__promo">
						<?php scd_the_icon( 'clock' ); ?>
						<span><?php echo esc_html( $scd_topbar ); ?></span>
					</div>
				<?php else : ?>
					<div class="scd-topbar__promo"></div>
				<?php endif; ?>

				<ul class="scd-topbar__list">
					<li><a href="tel:<?php echo esc_attr( $scd_phone_link ); ?>"><?php scd_the_icon( 'phone' ); ?><?php echo esc_html( $scd_phone ); ?></a></li>
					<li><a href="mailto:<?php echo esc_attr( scd_email() ); ?>"><?php scd_the_icon( 'mail' ); ?><?php echo esc_html( scd_email() ); ?></a></li>
					<li><a href="<?php echo esc_url( scd_map_link() ); ?>" target="_blank" rel="noopener"><?php scd_the_icon( 'pin' ); ?><?php echo esc_html( scd_address() ); ?></a></li>
				</ul>
			</div>
		</div>

		<header class="scd-site-header scd-scope" id="scdHeader">
			<div class="scd-container scd-header-inner">

				<div class="scd-brand">
					<?php siteorigin_corp_display_logo(); ?>
				</div>

				<nav class="scd-main-nav" aria-label="<?php esc_attr_e( 'Primary', 'siteorigin-corp' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'scd-primary-menu',
							'container'      => false,
							'depth'          => 2,
							'fallback_cb'    => false,
							'link_before'    => '<span>',
							'link_after'     => '</span>',
						)
					);
					?>
				</nav>

				<div class="scd-header-actions">
					<a class="scd-btn scd-btn--primary scd-header-cta" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
						<?php scd_the_icon( 'doc' ); ?>
						<?php esc_html_e( 'Get a Free Quote', 'siteorigin-corp' ); ?>
					</a>

					<a class="scd-phone-circle" href="tel:<?php echo esc_attr( $scd_phone_link ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Call us on %s', 'siteorigin-corp' ), $scd_phone ) ); ?>">
						<?php scd_the_icon( 'phone' ); ?>
					</a>

					<button class="scd-burger" id="scdBurger" type="button" aria-label="<?php esc_attr_e( 'Open menu', 'siteorigin-corp' ); ?>" aria-expanded="false" aria-controls="scdDrawer">
						<span></span><span></span><span></span>
					</button>
				</div>

			</div>
		</header>

		<?php get_template_part( 'template-parts/global/mobile-drawer' ); ?>

	<?php endif; ?>

	<?php
	do_action( 'siteorigin_corp_content_before' );

	// Inner page banner. The homepage template renders its own banner.
	if ( ! is_page_template( 'page-templates/template-home.php' ) ) {
		get_template_part( 'template-parts/global/inner-hero' );
	}
	?>

	<div id="content" class="site-content">

		<?php
		/*
		 * The parent theme's container is kept so every legacy WPBakery page
		 * keeps its layout. On the 2026 templates it is neutralised in CSS
		 * rather than removed, which keeps this markup balanced with footer.php.
		 */
		?>
		<div class="corp-container">

			<?php do_action( 'siteorigin_corp_content_top' ); ?>
