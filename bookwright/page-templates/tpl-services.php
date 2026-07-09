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
		$flip = false;

		// Robust bundled image for a given icon, with graceful fallback.
		$svc_image = function ( $icon ) {
			$file = 'service-' . $icon . '.svg';
			return file_exists( BOOKWRIGHT_DIR . '/assets/images/' . $file ) ? bookwright_img( $file ) : bookwright_img( 'about.svg' );
		};

		if ( bookwright_has_items( 'bw_service' ) ) :
			$q = bookwright_get_items( 'bw_service' );
			while ( $q->have_posts() ) :
				$q->the_post();
				$icon = get_post_meta( get_the_ID(), '_bw_icon', true );
				$icon = $icon ? $icon : 'book';
				$img  = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : $svc_image( $icon );
				?>
				<div class="bw-split" style="margin-bottom:70px;<?php echo $flip ? 'direction:rtl;' : ''; ?>">
					<div style="direction:ltr;">
						<div class="bw-card__icon" style="width:64px;height:64px;margin-bottom:20px;"><?php bookwright_icon( $icon ); ?></div>
						<h2><?php the_title(); ?></h2>
						<div class="bw-entry"><?php the_content(); ?></div>
					</div>
					<div style="direction:ltr;">
						<img src="<?php echo esc_url( $img ); ?>" alt="<?php the_title_attribute(); ?>" style="border-radius:var(--bw-radius);box-shadow:var(--bw-shadow);" />
					</div>
				</div>
				<?php
				$flip = ! $flip;
			endwhile;
			wp_reset_postdata();
		else :
			$fallback = array(
				array( 'edit', 'Editing & Proofreading', 'Great books are rewritten, not written. Our editors work in four clear stages so your money goes exactly where your manuscript needs it.', array( 'Developmental / structural editing', 'Line & copy editing', 'Proofreading', 'Editorial assessment reports' ) ),
				array( 'design', 'Cover & Interior Design', 'A cover sells the click; a beautiful interior keeps the reader turning pages. We design both, for print and digital.', array( 'Three original cover concepts', 'Print & ebook interior layout', 'Typesetting & formatting', 'Print-ready files for KDP & IngramSpark' ) ),
				array( 'book', 'Publishing & Distribution', 'We handle the technical maze of getting your book listed, priced and available everywhere readers shop.', array( 'Amazon KDP & IngramSpark setup', 'ISBN & metadata optimisation', 'Global print & ebook distribution', 'Pricing & category strategy' ) ),
				array( 'megaphone', 'Marketing & Launch', 'A finished book is the start line, not the finish. Our marketing team builds momentum that lasts beyond launch week.', array( 'Launch strategy & timeline', 'Amazon & Meta advertising', 'Email & newsletter campaigns', 'PR, reviews & influencer outreach' ) ),
			);
			foreach ( $fallback as $s ) :
				?>
				<div class="bw-split" style="margin-bottom:70px;<?php echo $flip ? 'direction:rtl;' : ''; ?>">
					<div style="direction:ltr;">
						<div class="bw-card__icon" style="width:64px;height:64px;margin-bottom:20px;"><?php bookwright_icon( $s[0] ); ?></div>
						<h2><?php echo esc_html( $s[1] ); ?></h2>
						<p class="bw-lead"><?php echo esc_html( $s[2] ); ?></p>
						<ul class="bw-checklist">
							<?php foreach ( $s[3] as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div style="direction:ltr;">
						<img src="<?php echo esc_url( $svc_image( $s[0] ) ); ?>" alt="<?php echo esc_attr( $s[1] ); ?>" style="border-radius:var(--bw-radius);box-shadow:var(--bw-shadow);" />
					</div>
				</div>
				<?php
				$flip = ! $flip;
			endforeach;
		endif;
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
