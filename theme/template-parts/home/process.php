<?php
/** Process steps. @package SCDoors */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<section class="scd-section">
	<div class="scd-container">
		<div class="scd-sec-head scd-center scd-reveal">
			<?php if ( $e = scd_field( 'process_eyebrow' ) ) : ?>
				<span class="scd-eyebrow"><?php echo esc_html( $e ); ?></span>
			<?php endif; ?>
			<?php if ( $h = scd_field( 'process_heading' ) ) : ?>
				<h2><?php echo esc_html( $h ); ?></h2>
			<?php endif; ?>
			<?php if ( $t = scd_field( 'process_text' ) ) : ?>
				<p><?php echo esc_html( $t ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( have_rows( 'steps' ) ) : ?>
			<div class="scd-steps">
				<?php $i = 0; while ( have_rows( 'steps' ) ) : the_row(); $i++; ?>
					<div class="scd-step scd-reveal" data-anim="up">
						<div class="scd-step__num"><?php echo esc_html( str_pad( $i, 2, '0', STR_PAD_LEFT ) ); ?></div>
						<h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
						<p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
