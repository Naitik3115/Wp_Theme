<?php
/**
 * Static homepage content (Phase 1).
 *
 * Every section reads from atelier_content( 'section' ). Later, swap the arrays
 * below for ACF / options / custom post types without touching the templates.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;

function atelier_url( $slug = '' ) {
	return home_url( '/' . ltrim( $slug, '/' ) . ( $slug ? '/' : '' ) );
}

function atelier_content( $section ) {
	static $c = null;

	if ( null === $c ) {
		$c = array(
			'announcement' => array(
				'speed' => 45, // seconds per loop. Lower = faster.
				'items' => array(
					'Premium apparel manufacturing',
					'Designed for global brands',
					'Quality without compromise',
					'Your vision, our craft',
				),
			),

			'nav' => array(
				array( 'label' => 'Home', 'url' => atelier_url() ),
				array( 'label' => 'About Us', 'url' => atelier_url( 'about-us' ) ),
				array( 'label' => 'Our Expertise', 'url' => atelier_url( 'our-expertise' ) ),
				array( 'label' => 'Collections', 'url' => atelier_url( 'collections' ) ),
				array( 'label' => 'Manufacturing', 'url' => atelier_url( 'manufacturing' ) ),
				array( 'label' => 'Contact', 'url' => atelier_url( 'contact' ) ),
			),

			'nav_cta' => array( 'label' => 'Partner with us', 'url' => atelier_url( 'contact' ) ),

			'hero' => array(
				'eyebrow'   => 'Crafted for the world',
				'title'     => "WE CREATE\nWHAT BRANDS\nBECOME.",
				'sub'       => 'Premium apparel manufacturing, thoughtfully crafted for ambitious brands across the globe.',
				'cta_1'     => array( 'label' => 'Explore our expertise', 'url' => atelier_url( 'our-expertise' ) ),
				'cta_2'     => array( 'label' => 'Discover collections', 'url' => atelier_url( 'collections' ) ),
				'scroll'    => 'Scroll',
			),

			'trust' => array(
				'label' => 'Your trusted apparel manufacturing partner',
				'speed' => 60,
				'items' => array(
					'Premium Quality',
					'Global Sourcing',
					'Precision Manufacturing',
					'Responsible Production',
					'Made for your brand',
				),
			),

			'categories' => array(
				'label'    => '01 / What we create',
				'title'    => "CATEGORIES\nOF EXPRESSION.",
				'text'     => 'From everyday essentials to statement collections, explore apparel crafted around your brand’s vision.',
				'items'    => array(
					array( 'title' => 'Menswear', 'slug' => 'menswear', 'image' => 'category_men', 'url' => atelier_url( 'collections' ) . '?category=menswear' ),
					array( 'title' => 'Womenswear', 'slug' => 'womenswear', 'image' => 'category_women', 'url' => atelier_url( 'collections' ) . '?category=womenswear' ),
					array( 'title' => 'Streetwear', 'slug' => 'streetwear', 'image' => 'category_street', 'url' => atelier_url( 'collections' ) . '?category=streetwear' ),
					array( 'title' => 'Essentials', 'slug' => 'essentials', 'image' => 'category_essential', 'url' => atelier_url( 'collections' ) . '?category=essentials' ),
					array( 'title' => 'Activewear', 'slug' => 'activewear', 'image' => 'category_active', 'url' => atelier_url( 'collections' ) . '?category=activewear' ),
				),
			),

			'collections' => array(
				'eyebrow' => '01 / Product catalog',
				'title'   => "PRODUCTS\nBY\nCATEGORY.",
				'sub'     => 'Explore finished pieces across every category. Filter the grid — hover any card to see a second look.',
				'filters_label' => 'Filter by category',
				'all_label'     => 'All',
				'empty'         => 'No products in this category yet. Try another filter.',
				'count_label'   => '%d products',
				'items' => array(
					array( 'title' => 'Relaxed Essentials', 'category' => 'essentials', 'category_label' => 'Essentials', 'image' => 'showcase_01', 'image_hover' => 'category_essential' ),
					array( 'title' => 'Modern Tailoring', 'category' => 'menswear', 'category_label' => 'Menswear', 'image' => 'showcase_02', 'image_hover' => 'category_men' ),
					array( 'title' => 'Elevated Streetwear', 'category' => 'streetwear', 'category_label' => 'Streetwear', 'image' => 'showcase_03', 'image_hover' => 'category_street' ),
					array( 'title' => 'Performance Wear', 'category' => 'activewear', 'category_label' => 'Activewear', 'image' => 'showcase_04', 'image_hover' => 'category_active' ),
					array( 'title' => 'Soft Tailoring', 'category' => 'womenswear', 'category_label' => 'Womenswear', 'image' => 'category_women', 'image_hover' => 'editorial' ),
					array( 'title' => 'Core Layering', 'category' => 'menswear', 'category_label' => 'Menswear', 'image' => 'category_men', 'image_hover' => 'showcase_01' ),
					array( 'title' => 'Studio Active', 'category' => 'activewear', 'category_label' => 'Activewear', 'image' => 'category_active', 'image_hover' => 'showcase_04' ),
					array( 'title' => 'Daily Knit', 'category' => 'essentials', 'category_label' => 'Essentials', 'image' => 'category_essential', 'image_hover' => 'showcase_01' ),
					array( 'title' => 'Urban Outer', 'category' => 'streetwear', 'category_label' => 'Streetwear', 'image' => 'category_street', 'image_hover' => 'showcase_03' ),
					array( 'title' => 'Fluid Dressing', 'category' => 'womenswear', 'category_label' => 'Womenswear', 'image' => 'editorial', 'image_hover' => 'category_women' ),
				),
			),

			'feature' => array(
				'eyebrow' => 'Beyond the ordinary / 2026',
				'title'   => "DESIGNED\nTO MAKE\nA STATEMENT.",
				'text'    => 'Every collection begins with an idea. Through thoughtful materials, refined craftsmanship, and attention to detail, we transform creative visions into apparel that speaks for itself.',
				'cta'     => array( 'label' => 'Explore the collection', 'url' => atelier_url( 'collections' ) ),
				'image'   => 'editorial',
				'index'   => '01',
				'tag'     => 'Featured collection',
			),

			'showcase' => array(
				'label' => '02 / Selected work',
				'title' => "THE ART OF\nEVERYDAY WEAR.",
				'text'  => 'Thoughtfully developed apparel, made to represent the identity of every brand we partner with.',
				'cta'   => array( 'label' => 'View all collections', 'url' => atelier_url( 'collections' ) ),
				'items' => array(
					array( 'index' => '01', 'title' => 'Relaxed Essentials', 'category' => 'Everyday Collection', 'image' => 'showcase_01', 'url' => atelier_url( 'collections' ) . '?category=essentials' ),
					array( 'index' => '02', 'title' => 'Modern Tailoring', 'category' => 'Contemporary Apparel', 'image' => 'showcase_02', 'url' => atelier_url( 'collections' ) . '?category=menswear' ),
					array( 'index' => '03', 'title' => 'Elevated Streetwear', 'category' => 'Urban Collection', 'image' => 'showcase_03', 'url' => atelier_url( 'collections' ) . '?category=streetwear' ),
					array( 'index' => '04', 'title' => 'Performance Wear', 'category' => 'Active Collection', 'image' => 'showcase_04', 'url' => atelier_url( 'collections' ) . '?category=activewear' ),
				),
			),

			'cta' => array(
				'label' => 'Let’s build something together',
				'title' => "YOUR VISION.\nOUR CRAFT.",
				'text'  => 'From concept to creation, we partner with brands to bring exceptional apparel to life.',
				'cta'   => array( 'label' => 'Start a conversation', 'url' => atelier_url( 'contact' ) ),
			),

			'about' => array(
				'hero' => array(
					'eyebrow' => '01 / The story behind the craft',
					'title'   => "MORE THAN\nCLOTHING.\nA COMMITMENT\nTO CRAFT.",
					'sub'     => 'We partner with ambitious brands to turn ideas into apparel that feels considered, enduring, and unmistakably yours.',
					'scroll'  => 'Scroll',
					'cta_1'   => array( 'label' => 'Start a conversation', 'url' => atelier_url( 'contact' ) ),
					'cta_2'   => array( 'label' => 'Discover collections', 'url' => atelier_url( 'collections' ) ),
				),
				'who' => array(
					'label'   => '02 / Who we are',
					'title'   => "BUILT ON\nPASSION.\nDEFINED BY\nQUALITY.",
					'lede'    => 'A manufacturing partner shaped by craft, curiosity, and care.',
					'text'    => 'Atelier exists for brands that want more than production. We combine thoughtful design support with precise manufacturing, so every collection arrives with clarity, consistency, and character.',
					'aside'   => 'From first sketch to finished garment, we stay close to the details that make a brand feel true.',
					'image_1' => 'editorial',
					'image_2' => 'category_women',
				),
				'philosophy' => array(
					'label'   => '03 / Our philosophy',
					'title'   => "GREAT CLOTHING\nBEGINS WITH GREAT\nPARTNERSHIPS.",
					'text'    => 'We believe the strongest collections are built together - through clear communication, shared standards, and a respect for the people and processes behind every piece.',
					'pillars' => array(
						array(
							'index' => '01',
							'title' => 'Thoughtful Design',
							'text'  => 'We start with intention: silhouette, material, and finish considered as one, so each garment feels resolved from the first fitting.',
							'image' => 'category_essential',
						),
						array(
							'index' => '02',
							'title' => 'Purposeful Craftsmanship',
							'text'  => 'Precision in cut, stitch, and finish. Quality is not a final check - it is the habit that shapes every stage of production.',
							'image' => 'category_street',
						),
						array(
							'index' => '03',
							'title' => 'Lasting Partnerships',
							'text'  => 'We build relationships that grow with your brand - reliable timelines, honest counsel, and a shared commitment to what comes next.',
							'image' => 'category_active',
						),
					),
				),
				'commitment' => array(
					'label'   => '04 / Our commitment',
					'title'   => "CREATING\nWITH\nPURPOSE.",
					'text'    => 'Purpose, for us, is practical. It shows in the fabrics we recommend, the finishes we refine, and the way we work with partners over seasons - not single orders.',
					'points'  => array(
						'Quality that holds through wear and time',
						'Responsible practices woven into daily process',
						'Attention to detail at every stage',
						'Partnerships built for the long term',
					),
					'image'   => 'category_men',
				),
				'cta' => array(
					'label' => '05 / Next chapter',
					'title' => "YOUR VISION.\nOUR CRAFT.",
					'text'  => 'Tell us where your brand is headed. We will help you bring the next collection to life with clarity, care, and craft.',
					'cta_1' => array( 'label' => 'Start a partnership', 'url' => atelier_url( 'contact' ) ),
					'cta_2' => array( 'label' => 'Explore our collections', 'url' => atelier_url( 'collections' ) ),
				),
			),

			'contact' => array(
				'title'       => 'Contact us',
				'intro_title' => 'Get In Touch',
				'intro_text'  => 'Have a question or want to work together? We’d love to hear from you.',
				'button'      => 'Send message',
				'fields'      => array(
					array( 'name' => 'contact_name', 'label' => 'Full name', 'type' => 'text', 'placeholder' => '', 'required' => true ),
					array( 'name' => 'contact_email', 'label' => 'Email address', 'type' => 'email', 'placeholder' => '', 'required' => true ),
					array( 'name' => 'contact_subject', 'label' => 'Subject', 'type' => 'text', 'placeholder' => '', 'required' => false ),
					array( 'name' => 'contact_message', 'label' => 'Message', 'type' => 'textarea', 'placeholder' => '', 'required' => true ),
				),
			),

			'offices' => array(
				'title'         => 'Our Offices',
				'address_label' => 'Studio',
				// PLACEHOLDERS – replace with real address / map query.
				'address'       => "City, Country\nReplace with your full street address.",
				'map_query'     => 'City, Country',
			),

			'footer' => array(
				'statement' => "LET’S CREATE\nWHAT’S NEXT.",
				'cta'       => array( 'label' => 'Get in touch', 'url' => atelier_url( 'contact' ) ),
				'intro'     => 'Premium apparel manufacturing for ambitious brands around the world.',
				'col_1'     => array(
					array( 'label' => 'Home', 'url' => atelier_url() ),
					array( 'label' => 'About Us', 'url' => atelier_url( 'about-us' ) ),
					array( 'label' => 'Expertise', 'url' => atelier_url( 'our-expertise' ) ),
					array( 'label' => 'Collections', 'url' => atelier_url( 'collections' ) ),
				),
				'col_2'     => array(
					array( 'label' => 'Manufacturing', 'url' => atelier_url( 'manufacturing' ) ),
					array( 'label' => 'Contact', 'url' => atelier_url( 'contact' ) ),
					array( 'label' => 'Privacy Policy', 'url' => 'privacy' ),
				),
				// PLACEHOLDERS – replace with real details.
				'email'     => 'hello@example.com',
				'phone'     => '+00 000 000 0000',
				'location'  => 'City, Country',
				'social'    => array(
					array( 'label' => 'Instagram', 'url' => '#' ),
					array( 'label' => 'LinkedIn', 'url' => '#' ),
					array( 'label' => 'Pinterest', 'url' => '#' ),
				),
				'terms_url' => '#',
			),
		);
	}

	return isset( $c[ $section ] ) ? $c[ $section ] : array();
}
