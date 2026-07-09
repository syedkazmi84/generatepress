<?php
/**
 * Template Name: Services Page
 *
 * @package Bookwright
 */

get_header();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1><?php esc_html_e( 'Full-service publishing, à la carte', 'bookwright' ); ?></h1>
		<p><?php esc_html_e( 'Choose exactly the help your book needs — from a single proofread to a complete publish-and-launch package.', 'bookwright' ); ?></p>
	</div>
</section>

<!-- Service detail blocks -->
<section class="bw-section">
	<div class="bw-wrap">
		<?php
		$services = array(
			array( 'edit', 'Editing &amp; Proofreading', 'Great books are rewritten, not written. Our editors work in four clear stages so your money goes exactly where your manuscript needs it.', array( 'Developmental / structural editing', 'Line &amp; copy editing', 'Proofreading', 'Editorial assessment reports' ) ),
			array( 'design', 'Cover &amp; Interior Design', 'A cover sells the click; a beautiful interior keeps the reader turning pages. We design both, for print and digital.', array( 'Three original cover concepts', 'Print &amp; ebook interior layout', 'Typesetting &amp; formatting', 'Print-ready files for KDP &amp; IngramSpark' ) ),
			array( 'book', 'Publishing &amp; Distribution', 'We handle the technical maze of getting your book listed, priced and available everywhere readers shop.', array( 'Amazon KDP &amp; IngramSpark setup', 'ISBN &amp; metadata optimisation', 'Global print &amp; ebook distribution', 'Pricing &amp; category strategy' ) ),
			array( 'megaphone', 'Marketing &amp; Launch', 'A finished book is the start line, not the finish. Our marketing team builds momentum that lasts beyond launch week.', array( 'Launch strategy &amp; timeline', 'Amazon &amp; Meta advertising', 'Email &amp; newsletter campaigns', 'PR, reviews &amp; influencer outreach' ) ),
		);
		$flip = false;
		foreach ( $services as $s ) :
			?>
			<div class="bw-split" style="margin-bottom:70px;<?php echo $flip ? 'direction:rtl;' : ''; ?>">
				<div style="direction:ltr;">
					<div class="bw-card__icon" style="width:64px;height:64px;margin-bottom:20px;"><?php bookwright_icon( $s[0] ); ?></div>
					<h2><?php echo wp_kses_post( $s[1] ); ?></h2>
					<p class="bw-lead"><?php echo esc_html( $s[2] ); ?></p>
					<ul class="bw-checklist">
						<?php foreach ( $s[3] as $item ) : ?>
							<li><?php echo wp_kses_post( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div style="direction:ltr;">
					<img src="<?php echo bookwright_img( 'service-' . $s[0] . '.svg' ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $s[1] ) ); ?>" onerror="this.src='<?php echo bookwright_img( 'about.svg' ); ?>'" style="border-radius:var(--bw-radius);box-shadow:var(--bw-shadow);" />
				</div>
			</div>
			<?php
			$flip = ! $flip;
		endforeach;
		?>
	</div>
</section>

<!-- Process -->
<section class="bw-section bw-section--sand">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'Our process', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'How a Bookwright project runs', 'bookwright' ); ?></h2>
		</div>
		<div class="bw-steps">
			<?php
			$steps = array(
				array( 'Free consult', 'We read a sample and talk goals, budget and timeline — no charge, no pressure.' ),
				array( 'Tailored plan', 'You get a fixed-price proposal with clear milestones and deliverables.' ),
				array( 'Production', 'Editing, design and formatting run on a schedule you can actually see.' ),
				array( 'Publish &amp; launch', 'We distribute everywhere and drive readers to your book from day one.' ),
			);
			$n = 1;
			foreach ( $steps as $st ) :
				?>
				<div class="bw-step">
					<div class="bw-step__num"><?php echo esc_html( sprintf( '%02d', $n ) ); ?></div>
					<h4><?php echo wp_kses_post( $st[0] ); ?></h4>
					<p><?php echo esc_html( $st[1] ); ?></p>
				</div>
				<?php
				$n++;
			endforeach;
			?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
