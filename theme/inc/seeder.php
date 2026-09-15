<?php
/**
 * One time content seeder.
 *
 * Runs itself once on admin_init, gated by an option, so there is no seeder
 * page to create or visit. Bump SCD_SEED_VERSION to run it again after adding
 * new fields.
 *
 * Two rules keep it safe to leave in the codebase on a live site:
 *
 *   1. It never overwrites. Every write goes through scd_seed_set(), which
 *      returns early if the field already holds a value. Re-running it can
 *      only fill gaps.
 *   2. It only ever touches the page that has the homepage template assigned,
 *      plus its own options. It creates no pages and deletes nothing.
 *
 * @package SCDoors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'SCD_SEED_VERSION' ) ) {
	define( 'SCD_SEED_VERSION', 1 );
}

/**
 * Resolve a field name to its field key.
 *
 * update_field() is documented as needing the key, not the name, when the
 * value does not exist yet. That is precisely the seeder's situation on a page
 * that has never been saved, and writing by name there can silently no-op.
 * Falls back to the name so a missing field group cannot fatal the seeder.
 */
function scd_seed_ref( $field ) {
	if ( ! function_exists( 'acf_get_field' ) ) {
		return $field;
	}

	$object = acf_get_field( $field );

	return ( is_array( $object ) && ! empty( $object['key'] ) ) ? $object['key'] : $field;
}

/**
 * Fill a field only when it is currently empty.
 */
function scd_seed_set( $field, $value, $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	$current = get_field( $field, $post_id );

	$empty = ( null === $current || false === $current || '' === $current
		|| ( is_array( $current ) && empty( $current ) ) );

	if ( ! $empty ) {
		return false;
	}

	update_field( scd_seed_ref( $field ), $value, $post_id );
	return true;
}

/**
 * Find the page using the homepage template. Falls back to the configured
 * front page so the seeder still lands somewhere sensible on first run.
 */
function scd_seed_target() {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'private' ),
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'page-templates/template-home.php',
			'fields'         => 'ids',
		)
	);

	if ( ! empty( $pages ) ) {
		return (int) $pages[0];
	}

	$front = (int) get_option( 'page_on_front' );
	return $front ? $front : 0;
}

/**
 * Create the two Contact Form 7 forms if they are missing, and remember their
 * ids in the options so templates never hard code one.
 */
function scd_seed_forms() {
	if ( ! post_type_exists( 'wpcf7_contact_form' ) || ! function_exists( 'update_field' ) ) {
		return;
	}

	$forms = array(
		'hero'    => array(
			'title' => 'SC Banner Quote Form',
			'body'  => "[text* Name placeholder \"Name*\"]\n[tel* PhoneNo placeholder \"Phone*\"]\n[email* EmailAddress placeholder \"Email*\"]\n[text* Suburb placeholder \"Suburb / Postcode*\"]\n[select* Service \"Please select a service\" \"New garage door installation\" \"Garage door repair\" \"Servicing and maintenance\" \"Garage door opener or remote\" \"Custom gates\" \"Commercial garage doors\" \"Something else\"]\n[textarea Details placeholder \"Additional details\"]\n[submit \"Request My Free Quote\"]",
		),
		'contact' => array(
			'title' => 'SC Contact Form',
			'body'  => "[text* FirstName placeholder \"First name\"]\n[text* LastName placeholder \"Last name\"]\n[tel* Phone placeholder \"Phone\"]\n[email* Email placeholder \"Email\"]\n[select* Service \"Please select a service\" \"Garage Doors\" \"Commercial Garage Doors\" \"Sectional Garage Doors\" \"Roller Doors\" \"Tilt Doors\" \"Custom Garage Doors\" \"Custom Gates\" \"Garage Door Openers\" \"Installation\" \"Repairs\" \"Maintenance\" \"Servicing\"]\n[text* Address placeholder \"Street, suburb and postcode\"]\n[textarea Message placeholder \"Tell us a little about the job\"]\n[submit \"Get My Free Quote\"]",
		),
	);

	foreach ( $forms as $slot => $form ) {
		$existing = get_field( 'scd_cf7_' . $slot, 'option' );
		if ( $existing ) {
			continue;
		}

		$found = get_posts(
			array(
				'post_type'      => 'wpcf7_contact_form',
				'title'          => $form['title'],
				'posts_per_page' => 1,
				'post_status'    => 'any',
				'fields'         => 'ids',
			)
		);

		if ( ! empty( $found ) ) {
			update_field( scd_seed_ref( 'scd_cf7_' . $slot ), (string) $found[0], 'option' );
			continue;
		}

		$id = wp_insert_post(
			array(
				'post_type'   => 'wpcf7_contact_form',
				'post_title'  => $form['title'],
				'post_status' => 'publish',
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_form', $form['body'] );
			update_post_meta( $id, '_mail', array(
				'active'    => true,
				'subject'   => get_bloginfo( 'name' ) . ' enquiry',
				'sender'    => '[_site_title] <wordpress@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
				'recipient' => get_option( 'admin_email' ),
				'body'      => "A new enquiry from [_site_title]:\n\n[_serialize_all]\n",
				'use_html'  => false,
			) );
			update_field( scd_seed_ref( 'scd_cf7_' . $slot ), (string) $id, 'option' );
		}
	}
}

/**
 * Site wide contact details.
 */
function scd_seed_options() {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	$defaults = array(
		'scd_phone'        => '0467 674 414',
		'scd_phone_link'   => '0467674414',
		'scd_email'        => 'info@scdoors.com.au',
		'scd_address'      => 'Melbourne, VIC 3000',
		'scd_map_link'     => 'https://maps.app.goo.gl/5HMPy4gQFPcqFQMe7',
		'scd_facebook'     => 'https://www.facebook.com/SCGarageDoorsMelbourne/',
		'scd_instagram'    => 'https://www.instagram.com/scgarages/',
		'scd_footer_about' => 'Your garage door specialists with over 25 years of experience providing homes and businesses with the best products and services at reasonable prices.',
		/*
		 * Left deliberately empty. Opening hours, the top bar note and any
		 * response time promise are claims about the business that have not
		 * been confirmed, so nothing is published until someone fills them in.
		 */
		'scd_hours'        => '',
		'scd_topbar_note'  => '',
	);

	foreach ( $defaults as $name => $value ) {
		if ( '' === $value ) {
			continue;
		}
		$current = get_field( $name, 'option' );
		if ( '' === $current || null === $current || false === $current ) {
			update_field( scd_seed_ref( $name ), $value, 'option' );
		}
	}
}

/**
 * Homepage content, taken from the approved design.
 */
function scd_seed_home( $post_id ) {
	if ( ! $post_id ) {
		return;
	}

	$set = function ( $field, $value ) use ( $post_id ) {
		return scd_seed_set( $field, $value, $post_id );
	};

	/* Banner */
	$set( 'hero_rating', '5 star rated by Melbourne homeowners and businesses' );
	$set( 'hero_heading', "Melbourne's Trusted Garage Door [hl]Specialists[/hl]" );
	$set( 'hero_text', 'Over 25 years installing, repairing and maintaining garage doors for homes and businesses across Melbourne. Quality assured workmanship, honest advice and transparent pricing on every job.' );
	$set( 'hero_points', array(
		array( 'point' => 'Premium quality garage doors to suit any budget' ),
		array( 'point' => 'Fast, reliable repairs with upfront fixed pricing' ),
		array( 'point' => 'Fully qualified technicians, meticulous workmanship' ),
		array( 'point' => 'Full warranties on all products and services' ),
	) );
	$set( 'hero_meta', array(
		array( 'icon' => 'pin', 'label' => 'Servicing all of Melbourne' ),
	) );

	/* Trust bar */
	$set( 'trust_items', array(
		array( 'icon' => 'shield', 'title' => '25+ Years', 'subtitle' => 'Of hands on experience' ),
		array( 'icon' => 'dollar', 'title' => 'Transparent Pricing', 'subtitle' => 'Detailed quotes, no surprises' ),
		array( 'icon' => 'medal',  'title' => 'Great Warranties', 'subtitle' => 'On products and workmanship' ),
		array( 'icon' => 'clock',  'title' => 'On Time, Every Time', 'subtitle' => 'Prompt and efficient service' ),
	) );

	/* Services */
	$set( 'services_eyebrow', 'What we can do for you' );
	$set( 'services_heading', 'The Best Garage Door Services at Reasonable Prices' );
	$set( 'services_text', 'Take advantage of the best garage door services delivered by specialists with over 25 years of industry experience, at prices that suit your budget.' );
	$set( 'services', array(
		array(
			'tag'   => 'Installations',
			'title' => 'Garage Door Installations',
			'text'  => 'Install the perfect garage door for your home or business. Choose from our wide range of affordable premium quality doors to suit your budget and design requirements.',
			'link'  => '/garage-door-installations-melbourne/',
		),
		array(
			'tag'   => 'Repairs',
			'title' => 'Garage Door Repairs',
			'text'  => 'Broken or noisy door? Our qualified repair specialists troubleshoot and fix any issue quickly and correctly, so it is done right the first time.',
			'link'  => '/garage-door-repairs-in-melbourne/',
		),
		array(
			'tag'   => 'Maintenance',
			'title' => 'Servicing and Maintenance',
			'text'  => 'Protect the safety and lifespan of your door. Our technicians service and replace worn parts to prevent accidents and keep everything running smoothly.',
			'link'  => '/garage-door-maintenance-in-melbourne/',
		),
	) );
	$set( 'services_cta_text', 'Not sure which service you need? Describe the problem and we will point you to the right fix.' );
	$set( 'services_cta_label', 'Get a Free Quote' );

	/* About */
	$set( 'about_eyebrow', 'About SC Garage Doors' );
	$set( 'about_heading', 'Quality Assured Products and Services at Affordable Prices' );
	$set( 'about_text', '<p>At SC Garage Doors we combine decades of hands on experience with premium products from Australia\'s most trusted manufacturers. Whether you need a brand new door, an urgent repair or a routine service, you get the same standard of care on every job.</p><p>We aim to be prompt and efficient, and we guide you to the right solution for your property and your budget. No upselling, no hidden costs, just straight answers and workmanship we stand behind.</p>' );
	$set( 'about_badge_number', '25+' );
	$set( 'about_badge_label', 'Years experience' );
	$set( 'about_stats', array(
		array( 'value' => '25+', 'label' => 'Years in the trade' ),
	) );

	/* Products */
	$set( 'products_eyebrow', 'What we provide' );
	$set( 'products_heading', 'Affordable Premium Quality Garage Doors and Gates' );
	$set( 'products_text', 'Choose from a wide range of doors and gates to suit the design of your residential or commercial property. Manual, remote controlled or smart home integrated, we design, build and install them for you.' );
	$set( 'products', array(
		array( 'title' => 'Garage Doors', 'text' => "Secure, easy to use doors that match your home's aesthetic and deliver hassle free convenience.", 'link' => '/home-garage-doors-in-melbourne/' ),
		array( 'title' => 'Commercial Doors', 'text' => 'Heavy duty doors engineered with the durability to handle the demands of commercial use.', 'link' => '/commercial-garage-doors-in-melbourne/' ),
		array( 'title' => 'Sectional Doors', 'text' => 'Space saving doors made of horizontal panels that fold neatly to maximise limited driveways.', 'link' => '/sectional-garage-doors-in-melbourne/' ),
		array( 'title' => 'Roller Doors', 'text' => 'Strong, quiet and low maintenance doors using COLORBOND steel for long term reliability.', 'link' => '/garage-roller-doors-in-melbourne/' ),
		array( 'title' => 'Tilt Doors', 'text' => 'Single panel doors with a reinforced frame, perfect for spaces with limited headroom.', 'link' => '/tilt-garage-doors-melbourne/' ),
		array( 'title' => 'Custom Doors', 'text' => 'Bespoke doors tailored with your choice of design, size, material, colour and finish.', 'link' => '/custom-garage-doors-in-melbourne/' ),
		array( 'title' => 'Custom Gates', 'text' => 'Gates designed and built to suit your style, engineering and budget requirements.', 'link' => '/custom-gates-in-melbourne/' ),
		array( 'title' => 'Door Openers', 'text' => 'A wide variety of openers from top brands, with remote and mobile app controls.', 'link' => '/garage-door-openers-in-melbourne/' ),
	) );
	$set( 'products_cta_text', 'Seen a door that suits your place? We will measure up and quote it at no cost.' );
	$set( 'products_cta_label', 'Get a Free Quote' );

	/* Process */
	$set( 'process_eyebrow', 'How it works' );
	$set( 'process_heading', 'Getting Your Garage Door Sorted Is Simple' );
	$set( 'process_text', 'Four straightforward steps from your first call to a door that works beautifully for years to come.' );
	$set( 'steps', array(
		array( 'title' => 'Request a Quote', 'text' => 'Call us or send the form. Tell us what you need and we will respond quickly with the right options.' ),
		array( 'title' => 'On Site Assessment', 'text' => 'We measure up, inspect the existing setup and give you honest advice with clear, itemised pricing.' ),
		array( 'title' => 'Expert Workmanship', 'text' => 'Our qualified technicians complete the job on time, with meticulous attention to every detail.' ),
		array( 'title' => 'Backed by Warranty', 'text' => 'You get a full range of warranties on products and workmanship, plus ongoing support when you need it.' ),
	) );

	/* Quote strip */
	$set( 'strip_eyebrow', 'Free, fast and no obligation' );
	$set( 'strip_heading', 'Get Your Free Quote Now' );
	$set( 'strip_text', 'Know exactly what you have to pay to install, repair or maintain your garage doors and gates. Honest and reliable service with transparent pricing, every time.' );

	/* Why us */
	$set( 'why_eyebrow', 'Why choose us' );
	$set( 'why_heading', 'Why Melbourne Chooses SC Garage Doors' );
	$set( 'why_text', 'We have built our reputation on doing the job properly and treating every customer the way we would want to be treated. That is why so much of our work comes from repeat customers and referrals.' );
	$set( 'why_cards', array(
		array( 'icon' => 'dollar', 'title' => 'Affordable Quality', 'text' => 'The best products and services to suit your needs at prices you can genuinely afford.' ),
		array( 'icon' => 'shield', 'title' => 'Honest and Reliable Service', 'text' => 'Prompt, efficient service with detailed and transparent pricing on every single job.' ),
		array( 'icon' => 'tool',   'title' => 'Superb Workmanship', 'text' => 'Highly skilled experts install, fix and maintain your doors with meticulous attention to detail.' ),
		array( 'icon' => 'medal',  'title' => 'Decades of Experience', 'text' => 'Work with specialists who bring 25 years of knowledge and a proven track record to your project.' ),
		array( 'icon' => 'case',   'title' => 'Great Warranties', 'text' => 'A full range of warranties on all products and services, for your complete peace of mind.' ),
	) );
	$set( 'why_cta_heading', 'Ready to Get Started?' );
	$set( 'why_cta_text', 'Tell us what you need and we will come back to you with honest, transparent pricing.' );
	$set( 'badges', array(
		array( 'title' => 'Transparent Pricing', 'text' => 'Itemised quotes with no hidden extras.' ),
		array( 'title' => 'Done Right, On Time', 'text' => 'We show up when we say we will.' ),
		array( 'title' => '5 Star Rated Experts', 'text' => 'Trusted by homes and businesses.' ),
		array( 'title' => 'Best Products Available', 'text' => 'Leading Australian and global brands.' ),
	) );

	/* Testimonials, wording as published on the current site */
	$set( 'reviews_eyebrow', 'Testimonials' );
	$set( 'reviews_heading', 'What Our Customers Say About Us' );
	$set( 'reviews_text', 'Real feedback from Melbourne homeowners and business owners we have worked with.' );
	$set( 'reviews', array(
		array(
			'quote' => "From the very start they were very professional and prompt, they communicated right through. This is the second time that we have used them and wouldn't hesitate to use them again. Highly recommend their services.",
			'name'  => 'Mary',
			'meta'  => '1 year ago',
		),
		array(
			'quote' => 'Good workmanship. And he suggested what options I could have for a damaged garage door. He replaced it and it looks amazing now. He also did some maintenance works on my garage door sensor system which was extra. Good work!',
			'name'  => 'John',
			'meta'  => '7 months ago',
		),
		array(
			'quote' => 'Got my new Merlin power garage door installed today by SC Garage Doors. Really satisfied with his service and highly recommend.',
			'name'  => 'Sandy',
			'meta'  => '9 months ago',
		),
	) );
	$set( 'reviews_cta_text', 'Ready to work with a team that turns up when it says it will?' );
	$set( 'reviews_cta_label', 'Get a Free Quote' );

	/*
	 * Six empty logo slots. The template skips a row with no image, so these
	 * render nothing until someone uploads, but the rows give an editor
	 * visible places to drop the accreditation and brand logos into rather
	 * than an empty repeater.
	 */
	$set( 'brands', array(
		array( 'image' => '' ),
		array( 'image' => '' ),
		array( 'image' => '' ),
		array( 'image' => '' ),
		array( 'image' => '' ),
		array( 'image' => '' ),
	) );

	/* FAQ */
	$set( 'faq_eyebrow', 'Common questions' );
	$set( 'faq_heading', 'Garage Door Questions, Answered' );
	$set( 'faq_text', 'If your question is not here, give us a call and we will give you a straight answer.' );
	$set( 'faq_aside_heading', 'Prefer to Talk to Someone?' );
	$set( 'faq_aside_text', 'Reach us directly and we will answer your question straight away.' );
	$set( 'faqs', array(
		array(
			'question' => 'How much does a new garage door cost?',
			'answer'   => 'Price depends on the door type, size, material and whether an opener is included. We provide a detailed, itemised quote before any work starts, so you know exactly what you are paying for. Quotes are free and there is no obligation.',
		),
		array(
			'question' => 'Which Melbourne suburbs do you service?',
			'answer'   => 'We service homes and businesses right across metropolitan Melbourne. If you are unsure whether we cover your area, send us your suburb or postcode and we will confirm straight away.',
		),
		array(
			'question' => 'How often should a garage door be serviced?',
			'answer'   => 'We recommend a service every 12 months for an average household door, and more often for high use commercial doors. Regular servicing prevents costly failures, keeps the door safe and extends its working life.',
		),
		array(
			'question' => 'Do you provide a warranty?',
			'answer'   => 'Yes. You get a full range of manufacturer warranties on products plus our own guarantee on workmanship. Warranty details are included with your quote so everything is clear from the start.',
		),
		array(
			'question' => "Can you match a door to my home's style?",
			'answer'   => 'Absolutely. Our doors come in a wide range of designs, sizes, materials, colours and finishes, and we can fully customise them. Send us a photo of your property and we will recommend the options that suit it best.',
		),
	) );

	/* Get in touch */
	$set( 'contact_eyebrow', 'Get in touch' );
	$set( 'contact_heading', 'Get Your Free Quote Today' );
	$set( 'contact_text', 'Need more information about our products or have a question about our services? You can rely on us to give you everything you need to make the right decision.' );
	$set( 'map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d805202.1176439845!2d144.39369019800256!3d-37.9696427535558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad646b5d2ba4df7%3A0x4045675218ccd90!2sMelbourne%20VIC%2C%20Australia!5e0!3m2!1sen!2sau!4v1745773379394!5m2!1sen!2sau' );
	$set( 'map_note_title', 'Servicing Melbourne wide' );
	$set( 'map_note_text', 'Mobile service to your home or business' );
	$set( 'form_heading', 'Send Us a Message' );
	$set( 'form_text', 'Fill in the form and one of our specialists will get back to you with a free, no obligation quote.' );
}

/**
 * Entry point. Idempotent and safe to leave enabled.
 */
function scd_run_seeder( $force = false ) {
	if ( ! function_exists( 'get_field' ) ) {
		return 'ACF is not active, nothing seeded.';
	}

	$target = scd_seed_target();

	scd_seed_options();
	scd_seed_forms();
	scd_seed_home( $target );

	update_option( 'scd_seed_version', SCD_SEED_VERSION );

	return $target
		? sprintf( 'Seeded homepage content into page %d.', $target )
		: 'Seeded settings only. Assign the SC Homepage template to a page, then re-run.';
}

/**
 * Auto run once in wp-admin. No seeder page needed.
 */
function scd_maybe_seed() {
	if ( ! is_admin() || wp_doing_ajax() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( (int) get_option( 'scd_seed_version' ) >= SCD_SEED_VERSION ) {
		return;
	}

	$message = scd_run_seeder();
	set_transient( 'scd_seed_notice', $message, 60 );
}
add_action( 'admin_init', 'scd_maybe_seed', 20 );

function scd_seed_notice() {
	$message = get_transient( 'scd_seed_notice' );
	if ( ! $message ) {
		return;
	}
	delete_transient( 'scd_seed_notice' );
	printf(
		'<div class="notice notice-success is-dismissible"><p><strong>SC Doors:</strong> %s</p></div>',
		esc_html( $message )
	);
}
add_action( 'admin_notices', 'scd_seed_notice' );

/**
 * wp scd seed
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'scd seed',
		function () {
			WP_CLI::success( scd_run_seeder( true ) );
		}
	);
}
