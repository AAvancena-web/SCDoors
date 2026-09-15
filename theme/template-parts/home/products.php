<?php
/** Products grid. @package SCDoors */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bg = scd_field( 'products_bg' );
?>
<section class="scd-section scd-products" id="products"<?php echo ! empty( $bg['url'] ) ? ' style="background-image:url(\'' . esc_url( $bg['url'] ) . '\')"' : ''; ?>>
	<div class="scd-container">
		<div class="scd-products__head scd-reveal">
			<?php if ( $e = scd_field( 'products_eyebrow' ) ) : ?>
				<span class="scd-eyebrow"><?php echo esc_html( $e ); ?></span>
			<?php endif; ?>
			<?php if ( $h = scd_field( 'products_heading' ) ) : ?>
				<h2><?php echo esc_html( $h ); ?></h2>
			<?php endif; ?>
			<?php if ( $t = scd_field( 'products_text' ) ) : ?>
				<p><?php echo esc_html( $t ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( have_rows( 'products' ) ) : ?>
			<div class="scd-grid-4">
				<?php while ( have_rows( 'products' ) ) : the_row();
					$img = get_sub_field( 'image' ); ?>
					<article class="scd-product-card scd-reveal" data-anim="zoom">
						<div class="scd-product-card__media">
							<?php if ( ! empty( $img['url'] ) ) : ?>
								<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="lazy">
							<?php endif; ?>
						</div>
						<div class="scd-product-card__body">
							<h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
							<p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
							<?php if ( $link = get_sub_field( 'link' ) ) : ?>
								<a class="scd-link-arrow" href="<?php echo esc_url( $link ); ?>">
									<?php esc_html_e( 'Explore', 'siteorigin-corp' ); ?> <?php scd_the_icon( 'arrow' ); ?>
								</a>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<?php scd_section_cta( scd_field( 'products_cta_text' ), scd_field( 'products_cta_label', false, 'Get a Free Quote' ) ); ?>
	</div>
</section>
