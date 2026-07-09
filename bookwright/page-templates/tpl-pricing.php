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
		<p><?php esc_html_e( 'Flexible packages with honest, no-hidden-fee pricing. Every book is different, so we tailor a quote to exactly what you need.', 'bookwright' ); ?></p>
	</div>
</section>

<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-pricing">
			<?php
			$contact_url = esc_url( get_permalink( get_page_by_path( 'contact' ) ) );

			// Normalise CPT plans and defaults into one shape.
			$plans = array();
			if ( bookwright_has_items( 'bw_plan' ) ) {
				$q = bookwright_get_items( 'bw_plan' );
				while ( $q->have_posts() ) {
					$q->the_post();
					$plans[] = array(
						'name'     => get_the_title(),
						'price'    => get_post_meta( get_the_ID(), '_bw_price', true ),
						'per'      => get_post_meta( get_the_ID(), '_bw_period', true ),
						'desc'     => get_post_meta( get_the_ID(), '_bw_desc', true ),
						'featured' => '1' === get_post_meta( get_the_ID(), '_bw_featured', true ),
						'feats'    => bookwright_parse_features( get_post_meta( get_the_ID(), '_bw_features', true ) ),
					);
				}
				wp_reset_postdata();
			} else {
				foreach ( bookwright_default_plans() as $d ) {
					$plans[] = array(
						'name'     => $d['name'],
						'price'    => $d['price'],
						'per'      => $d['period'],
						'desc'     => $d['desc'],
						'featured' => $d['featured'],
						'feats'    => bookwright_parse_features( $d['features'] ),
					);
				}
			}

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
							<li class="<?php echo $f[1] ? '' : 'bw-off'; ?>"><?php echo esc_html( $f[0] ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a class="bw-btn <?php echo $p['featured'] ? 'bw-btn--primary' : 'bw-btn--ghost'; ?>" href="<?php echo $contact_url; ?>"><?php esc_html_e( 'Get a custom quote', 'bookwright' ); ?></a>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="bw-center" style="margin-top:34px;color:var(--bw-muted);">
			<?php esc_html_e( 'Every package starts with a free consultation. Prefer to pick services individually? We build custom plans too — just ask.', 'bookwright' ); ?>
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
		<?php echo bookwright_render_faqs( array( 'category' => 'pricing', 'first_open' => true ) ); ?>
		</div>
</section>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
