<?php
/** About split. @package SCDoors */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$img = scd_field( 'about_image' );
?>
<section class="scd-section scd-section--soft">
	<div class="scd-container scd-split">
		<div class="scd-split__media scd-reveal" data-anim="left">
			<?php if ( ! empty( $img['url'] ) ) : ?>
				<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="lazy">
			<?php endif; ?>
			<?php if ( $n = scd_field( 'about_badge_number' ) ) : ?>
				<div class="scd-badge-years">
					<strong><?php echo esc_html( $n ); ?></strong>
					<span><?php echo esc_html( scd_field( 'about_badge_label' ) ); ?></span>
				</div>
			<?php endif; ?>
		</div>

		<div class="scd-reveal">
			<?php if ( $e = scd_field( 'about_eyebrow' ) ) : ?>
				<span class="scd-eyebrow"><?php echo esc_html( $e ); ?></span>
			<?php endif; ?>
			<?php if ( $h = scd_field( 'about_heading' ) ) : ?>
				<h2><?php echo esc_html( $h ); ?></h2>
			<?php endif; ?>
			<?php if ( $t = scd_field( 'about_text' ) ) : ?>
				<?php echo wp_kses_post( $t ); ?>
			<?php endif; ?>

			<?php if ( have_rows( 'about_stats' ) ) : ?>
				<div class="scd-stats">
					<?php
					while ( have_rows( 'about_stats' ) ) :
						the_row();
						$value = (string) get_sub_field( 'value' );
						// Split a value like "25+" into a number the counter can
						// animate and a suffix it appends.
						preg_match( '/^([\d.]+)(.*)$/', $value, $m );
						$num      = isset( $m[1] ) ? $m[1] : '';
						$suffix   = isset( $m[2] ) ? $m[2] : '';
						$decimals = ( $num && false !== strpos( $num, '.' ) ) ? strlen( substr( strrchr( $num, '.' ), 1 ) ) : 0;
						?>
						<div>
							<?php if ( $num ) : ?>
								<strong data-count="<?php echo esc_attr( $num ); ?>"
									data-suffix="<?php echo esc_attr( $suffix ); ?>"
									data-decimals="<?php echo esc_attr( $decimals ); ?>"><?php echo esc_html( $value ); ?></strong>
							<?php else : ?>
								<strong><?php echo esc_html( $value ); ?></strong>
							<?php endif; ?>
							<span><?php echo esc_html( get_sub_field( 'label' ) ); ?></span>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>

			<a class="scd-btn scd-btn--primary" href="#scd-contact"><?php esc_html_e( 'Get a Free Quote', 'siteorigin-corp' ); ?></a>
		</div>
	</div>
</section>
