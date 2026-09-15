<?php
/** Trust bar. @package SCDoors */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! have_rows( 'trust_items' ) ) { return; }
?>
<section class="scd-trustbar">
	<div class="scd-container">
		<?php while ( have_rows( 'trust_items' ) ) : the_row(); ?>
			<div class="scd-trustbar__item">
				<?php scd_the_icon( get_sub_field( 'icon' ) ); ?>
				<div>
					<strong><?php echo esc_html( get_sub_field( 'title' ) ); ?></strong>
					<span><?php echo esc_html( get_sub_field( 'subtitle' ) ); ?></span>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
</section>
