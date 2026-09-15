<?php
/**
 * ACF field groups, registered in code.
 *
 * Registering in PHP rather than the admin UI means the fields travel with the
 * theme, cannot be deleted by accident in wp-admin, and need no import step on
 * the live site. Values still live in the database as normal post meta.
 *
 * @package SCDoors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Everything is gated on ACF being active so the theme degrades rather than
 * fatals if the plugin is ever disabled.
 */
function scd_acf_ready() {
	return function_exists( 'acf_add_local_field_group' );
}

add_action( 'acf/init', 'scd_register_field_groups' );

function scd_register_field_groups() {
	if ( ! scd_acf_ready() ) {
		return;
	}

	scd_register_banner_group();
	scd_register_home_group();
	scd_register_options_group();
}

/* -------------------------------------------------------------------------
 * 1. Inner page banner, shown on every page that has a featured image.
 *    Field names match the ones already used in the current header.php so
 *    existing content keeps working.
 * ---------------------------------------------------------------------- */
function scd_register_banner_group() {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_scd_banner',
			'title'                 => 'Page Banner',
			'menu_order'            => 0,
			'position'              => 'acf_after_title',
			'label_placement'       => 'top',
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'page',
					),
				),
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'post',
					),
				),
			),
			'fields'                => array(
				array(
					'key'           => 'field_scd_banner_heading_sub',
					'label'         => 'Eyebrow',
					'name'          => 'banner_heading_sub',
					'type'          => 'text',
					'instructions'  => 'Small line above the heading. Leave empty to hide.',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_scd_banner_heading',
					'label'         => 'Heading',
					'name'          => 'banner_heading',
					'type'          => 'text',
					'instructions'  => 'Leave empty to use the page title.',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_scd_banner_content',
					'label'         => 'Intro',
					'name'          => 'banner_content',
					'type'          => 'wysiwyg',
					'tabs'          => 'visual',
					'media_upload'  => 0,
					'toolbar'       => 'basic',
					'delay'         => 1,
				),
				array(
					'key'           => 'field_scd_banner_form',
					'label'         => 'Banner quote form',
					'name'          => 'banner_form',
					'type'          => 'radio',
					'instructions'  => 'Shows the quote form on the right of the banner.',
					'choices'       => array(
						'show' => 'Show',
						'hide' => 'Hide',
					),
					'default_value' => 'show',
					'layout'        => 'horizontal',
				),
			),
		)
	);
}

/* -------------------------------------------------------------------------
 * 2. Homepage content, only on the assigned template.
 * ---------------------------------------------------------------------- */
function scd_register_home_group() {

	$cta_fields = function ( $prefix, $label ) {
		return array(
			array(
				'key'   => "field_scd_{$prefix}_cta_text",
				'label' => "{$label} closing line",
				'name'  => "{$prefix}_cta_text",
				'type'  => 'text',
			),
			array(
				'key'   => "field_scd_{$prefix}_cta_label",
				'label' => "{$label} button label",
				'name'  => "{$prefix}_cta_label",
				'type'  => 'text',
				'default_value' => 'Get a Free Quote',
			),
		);
	};

	$fields = array(

		/* ---- Hero ---- */
		array(
			'key'   => 'field_scd_tab_hero',
			'label' => 'Banner',
			'type'  => 'tab',
		),
		array(
			'key'           => 'field_scd_hero_image',
			'label'         => 'Banner image',
			'name'          => 'hero_image',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'instructions'  => 'Leave room around the subject. The banner pans slowly and parallaxes on scroll.',
		),
		array(
			'key'   => 'field_scd_hero_rating',
			'label' => 'Rating line',
			'name'  => 'hero_rating',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_hero_heading',
			'label' => 'Heading',
			'name'  => 'hero_heading',
			'type'  => 'text',
			'instructions' => 'Wrap words in [hl]...[/hl] to colour them.',
		),
		array(
			'key'   => 'field_scd_hero_text',
			'label' => 'Intro paragraph',
			'name'  => 'hero_text',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'          => 'field_scd_hero_points',
			'label'        => 'Tick list',
			'name'         => 'hero_points',
			'type'         => 'repeater',
			'layout'       => 'table',
			'button_label' => 'Add point',
			'sub_fields'   => array(
				array(
					'key'   => 'field_scd_hero_point',
					'label' => 'Point',
					'name'  => 'point',
					'type'  => 'text',
				),
			),
		),
		array(
			'key'          => 'field_scd_hero_meta',
			'label'        => 'Reassurance row',
			'name'         => 'hero_meta',
			'type'         => 'repeater',
			'layout'       => 'table',
			'button_label' => 'Add item',
			'sub_fields'   => array(
				array(
					'key'     => 'field_scd_hero_meta_icon',
					'label'   => 'Icon',
					'name'    => 'icon',
					'type'    => 'select',
					'choices' => array(
						'shield' => 'Shield',
						'clock'  => 'Clock',
						'pin'    => 'Map pin',
					),
				),
				array(
					'key'   => 'field_scd_hero_meta_label',
					'label' => 'Label',
					'name'  => 'label',
					'type'  => 'text',
				),
			),
		),

		/* ---- Trust bar ---- */
		array(
			'key'   => 'field_scd_tab_trust',
			'label' => 'Trust bar',
			'type'  => 'tab',
		),
		array(
			'key'          => 'field_scd_trust',
			'label'        => 'Trust items',
			'name'         => 'trust_items',
			'type'         => 'repeater',
			'layout'       => 'block',
			'max'          => 4,
			'button_label' => 'Add item',
			'sub_fields'   => array(
				array(
					'key'     => 'field_scd_trust_icon',
					'label'   => 'Icon',
					'name'    => 'icon',
					'type'    => 'select',
					'choices' => array(
						'shield' => 'Shield',
						'dollar' => 'Dollar',
						'medal'  => 'Medal',
						'clock'  => 'Clock',
					),
				),
				array(
					'key'   => 'field_scd_trust_title',
					'label' => 'Title',
					'name'  => 'title',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_scd_trust_sub',
					'label' => 'Subtitle',
					'name'  => 'subtitle',
					'type'  => 'text',
				),
			),
		),

		/* ---- Services ---- */
		array(
			'key'   => 'field_scd_tab_services',
			'label' => 'Services',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_scd_services_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'services_eyebrow',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_services_heading',
			'label' => 'Heading',
			'name'  => 'services_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_services_text',
			'label' => 'Intro',
			'name'  => 'services_text',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'          => 'field_scd_services',
			'label'        => 'Service cards',
			'name'         => 'services',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Add service',
			'sub_fields'   => array(
				array(
					'key'           => 'field_scd_service_image',
					'label'         => 'Image',
					'name'          => 'image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
				),
				array(
					'key'   => 'field_scd_service_tag',
					'label' => 'Tag',
					'name'  => 'tag',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_scd_service_title',
					'label' => 'Title',
					'name'  => 'title',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_scd_service_text',
					'label' => 'Text',
					'name'  => 'text',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'   => 'field_scd_service_link',
					'label' => 'Link',
					'name'  => 'link',
					'type'  => 'text',
				),
			),
		),
		$cta_fields( 'services', 'Services' )[0],
		$cta_fields( 'services', 'Services' )[1],

		/* ---- About ---- */
		array(
			'key'   => 'field_scd_tab_about',
			'label' => 'About',
			'type'  => 'tab',
		),
		array(
			'key'           => 'field_scd_about_image',
			'label'         => 'Image',
			'name'          => 'about_image',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
		),
		array(
			'key'   => 'field_scd_about_badge_number',
			'label' => 'Badge number',
			'name'  => 'about_badge_number',
			'type'  => 'text',
			'wrapper' => array( 'width' => '50' ),
		),
		array(
			'key'   => 'field_scd_about_badge_label',
			'label' => 'Badge label',
			'name'  => 'about_badge_label',
			'type'  => 'text',
			'wrapper' => array( 'width' => '50' ),
		),
		array(
			'key'   => 'field_scd_about_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'about_eyebrow',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_about_heading',
			'label' => 'Heading',
			'name'  => 'about_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_about_text',
			'label' => 'Body',
			'name'  => 'about_text',
			'type'  => 'wysiwyg',
			'tabs'  => 'visual',
			'media_upload' => 0,
			'toolbar' => 'basic',
		),
		array(
			'key'          => 'field_scd_about_stats',
			'label'        => 'Stats',
			'name'         => 'about_stats',
			'type'         => 'repeater',
			'layout'       => 'table',
			'max'          => 3,
			'button_label' => 'Add stat',
			'sub_fields'   => array(
				array(
					'key'   => 'field_scd_stat_value',
					'label' => 'Value',
					'name'  => 'value',
					'type'  => 'text',
					'instructions' => 'For example 25+, 5.0 or 100%.',
				),
				array(
					'key'   => 'field_scd_stat_label',
					'label' => 'Label',
					'name'  => 'label',
					'type'  => 'text',
				),
			),
		),

		/* ---- Products ---- */
		array(
			'key'   => 'field_scd_tab_products',
			'label' => 'Products',
			'type'  => 'tab',
		),
		array(
			'key'           => 'field_scd_products_bg',
			'label'         => 'Background image',
			'name'          => 'products_bg',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
		),
		array(
			'key'   => 'field_scd_products_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'products_eyebrow',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_products_heading',
			'label' => 'Heading',
			'name'  => 'products_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_products_text',
			'label' => 'Intro',
			'name'  => 'products_text',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'          => 'field_scd_products',
			'label'        => 'Product cards',
			'name'         => 'products',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Add product',
			'sub_fields'   => array(
				array(
					'key'           => 'field_scd_product_image',
					'label'         => 'Image',
					'name'          => 'image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
				),
				array(
					'key'   => 'field_scd_product_title',
					'label' => 'Title',
					'name'  => 'title',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_scd_product_text',
					'label' => 'Text',
					'name'  => 'text',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'   => 'field_scd_product_link',
					'label' => 'Link',
					'name'  => 'link',
					'type'  => 'text',
				),
			),
		),
		$cta_fields( 'products', 'Products' )[0],
		$cta_fields( 'products', 'Products' )[1],

		/* ---- Process ---- */
		array(
			'key'   => 'field_scd_tab_process',
			'label' => 'Process',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_scd_process_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'process_eyebrow',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_process_heading',
			'label' => 'Heading',
			'name'  => 'process_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_process_text',
			'label' => 'Intro',
			'name'  => 'process_text',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'          => 'field_scd_steps',
			'label'        => 'Steps',
			'name'         => 'steps',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Add step',
			'sub_fields'   => array(
				array(
					'key'   => 'field_scd_step_title',
					'label' => 'Title',
					'name'  => 'title',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_scd_step_text',
					'label' => 'Text',
					'name'  => 'text',
					'type'  => 'textarea',
					'rows'  => 2,
				),
			),
		),

		/* ---- Quote strip ---- */
		array(
			'key'   => 'field_scd_tab_strip',
			'label' => 'Quote strip',
			'type'  => 'tab',
		),
		array(
			'key'           => 'field_scd_strip_bg',
			'label'         => 'Background image',
			'name'          => 'strip_bg',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
		),
		array(
			'key'   => 'field_scd_strip_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'strip_eyebrow',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_strip_heading',
			'label' => 'Heading',
			'name'  => 'strip_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_strip_text',
			'label' => 'Text',
			'name'  => 'strip_text',
			'type'  => 'textarea',
			'rows'  => 2,
		),

		/* ---- Why us ---- */
		array(
			'key'   => 'field_scd_tab_why',
			'label' => 'Why choose us',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_scd_why_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'why_eyebrow',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_why_heading',
			'label' => 'Heading',
			'name'  => 'why_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_why_text',
			'label' => 'Intro',
			'name'  => 'why_text',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'          => 'field_scd_why_cards',
			'label'        => 'Reasons',
			'name'         => 'why_cards',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Add reason',
			'sub_fields'   => array(
				array(
					'key'     => 'field_scd_why_icon',
					'label'   => 'Icon',
					'name'    => 'icon',
					'type'    => 'select',
					'choices' => array(
						'dollar' => 'Dollar',
						'shield' => 'Shield',
						'tool'   => 'Tool',
						'medal'  => 'Medal',
						'case'   => 'Briefcase',
					),
				),
				array(
					'key'   => 'field_scd_why_title',
					'label' => 'Title',
					'name'  => 'title',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_scd_why_text_sub',
					'label' => 'Text',
					'name'  => 'text',
					'type'  => 'textarea',
					'rows'  => 2,
				),
			),
		),
		array(
			'key'   => 'field_scd_why_cta_heading',
			'label' => 'CTA card heading',
			'name'  => 'why_cta_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_why_cta_text',
			'label' => 'CTA card text',
			'name'  => 'why_cta_text',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'          => 'field_scd_badges',
			'label'        => 'Accreditation badges',
			'name'         => 'badges',
			'type'         => 'repeater',
			'layout'       => 'block',
			'max'          => 4,
			'button_label' => 'Add badge',
			'sub_fields'   => array(
				array(
					'key'           => 'field_scd_badge_image',
					'label'         => 'Image',
					'name'          => 'image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
				),
				array(
					'key'   => 'field_scd_badge_title',
					'label' => 'Title',
					'name'  => 'title',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_scd_badge_text',
					'label' => 'Text',
					'name'  => 'text',
					'type'  => 'text',
				),
			),
		),

		/* ---- Testimonials ---- */
		array(
			'key'   => 'field_scd_tab_reviews',
			'label' => 'Testimonials',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_scd_reviews_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'reviews_eyebrow',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_reviews_heading',
			'label' => 'Heading',
			'name'  => 'reviews_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_reviews_text',
			'label' => 'Intro',
			'name'  => 'reviews_text',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'          => 'field_scd_reviews',
			'label'        => 'Reviews',
			'name'         => 'reviews',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Add review',
			'instructions' => 'Only publish reviews you have actually received.',
			'sub_fields'   => array(
				array(
					'key'   => 'field_scd_review_quote',
					'label' => 'Quote',
					'name'  => 'quote',
					'type'  => 'textarea',
					'rows'  => 4,
				),
				array(
					'key'   => 'field_scd_review_name',
					'label' => 'Name',
					'name'  => 'name',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_scd_review_meta',
					'label' => 'Meta',
					'name'  => 'meta',
					'type'  => 'text',
					'instructions' => 'For example Melbourne, 7 months ago.',
				),
			),
		),
		$cta_fields( 'reviews', 'Testimonials' )[0],
		$cta_fields( 'reviews', 'Testimonials' )[1],
		array(
			'key'          => 'field_scd_brands',
			'label'        => 'Brand logos',
			'name'         => 'brands',
			'type'         => 'repeater',
			'layout'       => 'table',
			'button_label' => 'Add logo',
			'sub_fields'   => array(
				array(
					'key'           => 'field_scd_brand_image',
					'label'         => 'Logo',
					'name'          => 'image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
				),
			),
		),

		/* ---- FAQ ---- */
		array(
			'key'   => 'field_scd_tab_faq',
			'label' => 'FAQ',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_scd_faq_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'faq_eyebrow',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_faq_heading',
			'label' => 'Heading',
			'name'  => 'faq_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_faq_text',
			'label' => 'Intro',
			'name'  => 'faq_text',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'   => 'field_scd_faq_aside_heading',
			'label' => 'Side panel heading',
			'name'  => 'faq_aside_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_faq_aside_text',
			'label' => 'Side panel text',
			'name'  => 'faq_aside_text',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'          => 'field_scd_faqs',
			'label'        => 'Questions',
			'name'         => 'faqs',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Add question',
			'sub_fields'   => array(
				array(
					'key'   => 'field_scd_faq_q',
					'label' => 'Question',
					'name'  => 'question',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_scd_faq_a',
					'label' => 'Answer',
					'name'  => 'answer',
					'type'  => 'textarea',
					'rows'  => 4,
				),
			),
		),

		/* ---- Contact ---- */
		array(
			'key'   => 'field_scd_tab_contact',
			'label' => 'Get in touch',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_scd_contact_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'contact_eyebrow',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_contact_heading',
			'label' => 'Heading',
			'name'  => 'contact_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_contact_text',
			'label' => 'Intro',
			'name'  => 'contact_text',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'   => 'field_scd_map_embed',
			'label' => 'Google map embed URL',
			'name'  => 'map_embed',
			'type'  => 'url',
			'instructions' => 'The src value from the Google Maps embed code.',
		),
		array(
			'key'   => 'field_scd_map_note_title',
			'label' => 'Map note title',
			'name'  => 'map_note_title',
			'type'  => 'text',
			'wrapper' => array( 'width' => '50' ),
		),
		array(
			'key'   => 'field_scd_map_note_text',
			'label' => 'Map note text',
			'name'  => 'map_note_text',
			'type'  => 'text',
			'wrapper' => array( 'width' => '50' ),
		),
		array(
			'key'   => 'field_scd_form_heading',
			'label' => 'Form heading',
			'name'  => 'form_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_scd_form_text',
			'label' => 'Form intro',
			'name'  => 'form_text',
			'type'  => 'textarea',
			'rows'  => 2,
		),
	);

	acf_add_local_field_group(
		array(
			'key'             => 'group_scd_home',
			'title'           => 'Homepage 2026',
			'menu_order'      => 1,
			'position'        => 'normal',
			'label_placement' => 'top',
			'location'        => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-templates/template-home.php',
					),
				),
			),
			'fields'          => $fields,
		)
	);
}

/* -------------------------------------------------------------------------
 * 3. Site wide options used by the header, footer and drawer.
 * ---------------------------------------------------------------------- */
function scd_register_options_group() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title' => 'SC Doors Settings',
				'menu_title' => 'SC Doors',
				'menu_slug'  => 'scd-settings',
				'capability' => 'manage_options',
				'icon_url'   => 'dashicons-admin-home',
				'redirect'   => false,
			)
		);
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_scd_options',
			'title'    => 'Contact details',
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'scd-settings',
					),
				),
			),
			'fields'   => array(
				array(
					'key'     => 'field_scd_phone',
					'label'   => 'Phone number',
					'name'    => 'scd_phone',
					'type'    => 'text',
					'wrapper' => array( 'width' => '50' ),
				),
				array(
					'key'     => 'field_scd_phone_link',
					'label'   => 'Phone link',
					'name'    => 'scd_phone_link',
					'type'    => 'text',
					'instructions' => 'Digits only, used in the tel: link.',
					'wrapper' => array( 'width' => '50' ),
				),
				array(
					'key'     => 'field_scd_email',
					'label'   => 'Email',
					'name'    => 'scd_email',
					'type'    => 'text',
					'wrapper' => array( 'width' => '50' ),
				),
				array(
					'key'     => 'field_scd_address',
					'label'   => 'Service area',
					'name'    => 'scd_address',
					'type'    => 'text',
					'wrapper' => array( 'width' => '50' ),
				),
				array(
					'key'     => 'field_scd_map_link',
					'label'   => 'Google Maps link',
					'name'    => 'scd_map_link',
					'type'    => 'url',
				),
				array(
					'key'     => 'field_scd_hours',
					'label'   => 'Opening hours',
					'name'    => 'scd_hours',
					'type'    => 'text',
					'instructions' => 'Confirm these before publishing.',
				),
				array(
					'key'   => 'field_scd_topbar_note',
					'label' => 'Top bar note',
					'name'  => 'scd_topbar_note',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_scd_facebook',
					'label' => 'Facebook URL',
					'name'  => 'scd_facebook',
					'type'  => 'url',
					'wrapper' => array( 'width' => '50' ),
				),
				array(
					'key'   => 'field_scd_instagram',
					'label' => 'Instagram URL',
					'name'  => 'scd_instagram',
					'type'  => 'url',
					'wrapper' => array( 'width' => '50' ),
				),
				array(
					'key'   => 'field_scd_footer_about',
					'label' => 'Footer blurb',
					'name'  => 'scd_footer_about',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'          => 'field_scd_cf7_hero',
					'label'        => 'Banner form ID',
					'name'         => 'scd_cf7_hero',
					'type'         => 'text',
					'instructions' => 'Contact Form 7 id used in the banner.',
					'wrapper'      => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_scd_cf7_contact',
					'label'        => 'Contact form ID',
					'name'         => 'scd_cf7_contact',
					'type'         => 'text',
					'instructions' => 'Contact Form 7 id used in the Get in touch section.',
					'wrapper'      => array( 'width' => '50' ),
				),
			),
		)
	);
}
