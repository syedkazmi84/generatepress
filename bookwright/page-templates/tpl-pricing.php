<?php
/**
 * Template Name: Pricing Page
 *
 * @package Bookwright
 */

get_header();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1><?php esc_html_e( 'Simple, transparent pricing', 'bookwright' ); ?></h1>
		<p><?php esc_html_e( 'Fixed-price packages with no hidden fees. Need something custom? We build à-la-carte quotes too.', 'bookwright' ); ?></p>
	</div>
</section>

<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-pricing">
			<?php
			$plans = array(
				array(
					'name'    => 'Starter',
					'price'   => '$899',
					'per'     => 'one-time',
					'desc'    => 'For authors who need a polished, publish-ready manuscript.',
					'featured' => false,
					'feats'   => array(
						array( 'Professional proofreading', true ),
						array( 'Ebook formatting', true ),
						array( 'Amazon KDP setup', true ),
						array( 'Cover design', false ),
						array( 'Marketing launch', false ),
						array( 'Dedicated project manager', false ),
					),
				),
				array(
					'name'    => 'Publish',
					'price'   => '$2,499',
					'per'     => 'one-time',
					'desc'    => 'Our most popular package — everything to publish beautifully.',
					'featured' => true,
					'feats'   => array(
						array( 'Copy &amp; line editing', true ),
						array( 'Custom cover design', true ),
						array( 'Print &amp; ebook formatting', true ),
						array( 'KDP &amp; IngramSpark distribution', true ),
						array( 'Basic launch marketing', true ),
						array( 'Dedicated project manager', true ),
					),
				),
				array(
					'name'    => 'Bestseller',
					'price'   => '$5,900',
					'per'     => 'one-time',
					'desc'    => 'A full-scale publish-and-launch built to hit the charts.',
					'featured' => false,
					'feats'   => array(
						array( 'Developmental + copy editing', true ),
						array( 'Premium cover &amp; interior design', true ),
						array( 'Global distribution setup', true ),
						array( 'Full marketing &amp; ad campaign', true ),
						array( 'PR &amp; review outreach', true ),
						array( 'Audiobook production add-on', true ),
					),
				),
			);
			foreach ( $plans as $p ) :
				?>
				<div class="bw-plan <?php echo $p['featured'] ? 'bw-plan--featured' : ''; ?>">
					<?php if ( $p['featured'] ) : ?>
						<span class="bw-plan__flag"><?php esc_html_e( 'Most popular', 'bookwright' ); ?></span>
					<?php endif; ?>
					<h3><?php echo esc_html( $p['name'] ); ?></h3>
					<div class="bw-plan__price"><?php echo esc_html( $p['price'] ); ?> <span><?php echo esc_html( $p['per'] ); ?></span></div>
					<p class="bw-plan__desc"><?php echo esc_html( $p['desc'] ); ?></p>
					<ul>
						<?php foreach ( $p['feats'] as $f ) : ?>
							<li class="<?php echo $f[1] ? '' : 'bw-off'; ?>"><?php echo wp_kses_post( $f[0] ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a class="bw-btn <?php echo $p['featured'] ? 'bw-btn--primary' : 'bw-btn--ghost'; ?>" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php esc_html_e( 'Choose plan', 'bookwright' ); ?></a>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="bw-center" style="margin-top:34px;color:var(--bw-muted);">
			<?php esc_html_e( 'All packages include a free consultation and a satisfaction guarantee. Payment plans available.', 'bookwright' ); ?>
		</p>
	</div>
</section>

<!-- FAQ -->
<section class="bw-section bw-section--sand">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'Questions', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Frequently asked questions', 'bookwright' ); ?></h2>
		</div>
		<div class="bw-faq">
			<?php
			$faqs = array(
				array( 'Do I keep the rights to my book?', 'Absolutely. You retain 100% of your rights and royalties, always. We’re a service provider, not a publisher that owns your work.' ),
				array( 'How long does a typical project take?', 'Most Publish-package projects run 8–14 weeks depending on manuscript length and the level of editing required. We’ll give you a firm timeline before we start.' ),
				array( 'Can I mix and match services?', 'Yes. Every service is available à la carte. Tell us what you need and we’ll build a custom quote.' ),
				array( 'Do you offer payment plans?', 'We do. Most packages can be split into two or three milestone payments at no extra cost.' ),
				array( 'What if I’m not happy with the work?', 'Every package includes rounds of revision and a satisfaction guarantee. Your dedicated project manager is with you the whole way.' ),
			);
			foreach ( $faqs as $f ) :
				?>
				<details>
					<summary><?php echo esc_html( $f[0] ); ?></summary>
					<p><?php echo esc_html( $f[1] ); ?></p>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
