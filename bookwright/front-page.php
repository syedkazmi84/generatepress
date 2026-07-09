<?php
/**
 * Front page — a complete, self-contained publishing-studio landing page.
 *
 * @package Bookwright
 */

get_header();
?>

<!-- ===================== HERO ===================== -->
<section class="bw-hero">
	<div class="bw-wrap bw-hero__grid">
		<div class="bw-hero__intro">
			<span class="bw-eyebrow"><?php echo esc_html( bookwright_option( 'bw_hero_eyebrow', 'Ghostwriting · Editing · Publishing · Marketing' ) ); ?></span>
			<h1><?php
				$title = bookwright_option( 'bw_hero_title', 'Your story, published the right way' );
				// Emphasise the last word.
				$words = explode( ' ', $title );
				$last  = array_pop( $words );
				echo esc_html( implode( ' ', $words ) ) . ' <span class="bw-accent">' . esc_html( $last ) . '</span>';
			?></h1>
			<p class="bw-hero__lead"><?php echo esc_html( bookwright_option( 'bw_hero_lead', 'From first draft to final launch, our team helps you write, edit, design, publish and market your book — while you keep full ownership every step of the way.' ) ); ?></p>
			<div class="bw-hero__actions">
				<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php echo esc_html( bookwright_option( 'bw_hero_btn1', 'Book a free consultation' ) ); ?> <?php bookwright_icon( 'arrow' ); ?></a>
				<a class="bw-btn bw-btn--ghost" href="<?php echo esc_url( get_permalink( get_page_by_path( 'services' ) ) ); ?>"><?php echo esc_html( bookwright_option( 'bw_hero_btn2', 'Explore our services' ) ); ?></a>
			</div>
			<div class="bw-hero__stats">
				<div class="bw-hero__stat"><strong>750+</strong><span><?php esc_html_e( 'Books published', 'bookwright' ); ?></span></div>
				<div class="bw-hero__stat"><strong>900+</strong><span><?php esc_html_e( 'Happy authors', 'bookwright' ); ?></span></div>
				<div class="bw-hero__stat"><strong>100%</strong><span><?php esc_html_e( 'Ownership you keep', 'bookwright' ); ?></span></div>
			</div>
		</div>
		<div class="bw-hero__art">
			<img src="<?php echo bookwright_img( 'hero.svg' ); ?>" alt="<?php esc_attr_e( 'Stacked books and manuscript illustration', 'bookwright' ); ?>" width="560" height="520" />
			<div class="bw-hero__badge">
				<span class="bw-stars">★★★★★</span>
				<span><strong><?php esc_html_e( '4.9 / 5 rating', 'bookwright' ); ?></strong><?php esc_html_e( 'from 600+ authors', 'bookwright' ); ?></span>
			</div>
		</div>
	</div>
</section>

<!-- ===================== TRUSTED BY ===================== -->
<div class="bw-logos">
	<div class="bw-wrap">
		<p><?php esc_html_e( 'As featured in', 'bookwright' ); ?></p>
		<ul>
			<?php
			$logos = array_filter( array_map( 'trim', explode( ',', bookwright_option( 'bw_logos', 'The Paper Review, Inkwell, Readerly, Prose & Co., Bindery' ) ) ) );
			foreach ( $logos as $logo ) {
				echo '<li>' . esc_html( $logo ) . '</li>';
			}
			?>
		</ul>
	</div>
</div>

<!-- ===================== SERVICES ===================== -->
<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php echo esc_html( bookwright_option( 'bw_services_eyebrow', 'What we do' ) ); ?></span>
			<h2><?php echo esc_html( bookwright_option( 'bw_services_title', 'Everything your book needs, in one place' ) ); ?></h2>
			<p class="bw-lead"><?php echo esc_html( bookwright_option( 'bw_services_lead', 'From writing to marketing, our team handles the hard parts of publishing so you can focus on your story. Choose a package or build your own.' ) ); ?></p>
		</div>

		<div class="bw-cards">
			<?php
			$services_url = esc_url( get_permalink( get_page_by_path( 'services' ) ) );
			if ( bookwright_has_items( 'bw_service' ) ) :
				$q = bookwright_get_items( 'bw_service', 6 );
				while ( $q->have_posts() ) :
					$q->the_post();
					$icon = get_post_meta( get_the_ID(), '_bw_icon', true );
					$link = get_post_meta( get_the_ID(), '_bw_link', true );
					?>
					<article class="bw-card">
						<div class="bw-card__icon"><?php bookwright_icon( $icon ? $icon : 'book' ); ?></div>
						<h3><?php the_title(); ?></h3>
						<p><?php echo esc_html( get_the_excerpt() ); ?></p>
						<a class="bw-card__link" href="<?php echo $link ? esc_url( $link ) : $services_url; ?>"><?php esc_html_e( 'Learn more', 'bookwright' ); ?></a>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				foreach ( bookwright_default_services() as $s ) :
					?>
					<article class="bw-card">
						<div class="bw-card__icon"><?php bookwright_icon( $s[0] ); ?></div>
						<h3><?php echo esc_html( $s[1] ); ?></h3>
						<p><?php echo esc_html( $s[2] ); ?></p>
						<a class="bw-card__link" href="<?php echo $services_url; ?>"><?php esc_html_e( 'Learn more', 'bookwright' ); ?></a>
					</article>
					<?php
				endforeach;
			endif;
			?>
		</div>
	</div>
</section>

<!-- ===================== PROCESS ===================== -->
<section class="bw-section bw-section--sand">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'How it works', 'bookwright' ); ?></span>
			<h2><?php echo esc_html( bookwright_option( 'bw_process_title', 'How we bring your book to life' ) ); ?></h2>
		</div>
		<div class="bw-steps">
			<?php
			$step_defaults = array(
				1 => array( 'Book a free call', 'Grab a no-obligation consultation at a time that suits you.' ),
				2 => array( 'Share your vision', 'Tell us about your book, your goals and where you are right now.' ),
				3 => array( 'Get a tailored plan', 'We recommend the right services and send an honest, no-pressure quote.' ),
				4 => array( 'We bring it to life', 'Your dedicated team writes, edits, designs, publishes and markets your book.' ),
			);
			for ( $n = 1; $n <= 4; $n++ ) :
				?>
				<div class="bw-step">
					<div class="bw-step__num"><?php echo esc_html( sprintf( '%02d', $n ) ); ?></div>
					<h4><?php echo esc_html( bookwright_option( 'bw_step' . $n . '_t', $step_defaults[ $n ][0] ) ); ?></h4>
					<p><?php echo esc_html( bookwright_option( 'bw_step' . $n . '_d', $step_defaults[ $n ][1] ) ); ?></p>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</section>

<!-- ===================== BOOK A CALL ===================== -->
<section class="bw-section bw-section--tight">
	<div class="bw-wrap">
		<div class="bw-consult">
			<div class="bw-consult__text">
				<span class="bw-eyebrow" style="color:var(--bw-gold);"><?php esc_html_e( 'No cost, no obligation', 'bookwright' ); ?></span>
				<h2><?php esc_html_e( 'Book your free consultation', 'bookwright' ); ?></h2>
				<p><?php esc_html_e( 'Talk to a publishing expert about your book. We\'ll listen to your goals, answer your questions and map out exactly what your book needs — with an honest, no-pressure quote.', 'bookwright' ); ?></p>
				<p class="bw-consult__hours"><?php bookwright_icon( 'clock' ); ?> <?php echo esc_html( bookwright_option( 'bw_hours', 'Mon–Sat · 8:00 AM–6:00 PM' ) ); ?></p>
			</div>
			<div class="bw-consult__actions">
				<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php bookwright_icon( 'calendar' ); ?> <?php esc_html_e( 'Book a free call', 'bookwright' ); ?></a>
				<a class="bw-btn bw-btn--light" href="tel:<?php echo esc_attr( bookwright_option( 'bw_phone', '' ) ); ?>"><?php bookwright_icon( 'phone' ); ?> <?php echo esc_html( bookwright_option( 'bw_phone', '+1 (555) 018-2420' ) ); ?></a>
			</div>
		</div>
	</div>
</section>

<!-- ===================== FEATURED BOOKS ===================== -->
<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'Our work', 'bookwright' ); ?></span>
			<h2><?php echo esc_html( bookwright_option( 'bw_books_title', 'Books we’ve helped bring to life' ) ); ?></h2>
		</div>

		<div class="bw-books">
			<?php
			$books = new WP_Query(
				array(
					'post_type'      => 'book',
					'posts_per_page' => 4,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				)
			);
			if ( $books->have_posts() ) :
				while ( $books->have_posts() ) :
					$books->the_post();
					get_template_part( 'template-parts/book', 'card' );
				endwhile;
				wp_reset_postdata();
			else :
				// Fallback sample covers if no portfolio projects exist yet.
					for ( $fi = 1; $fi <= 4; $fi++ ) :
						?>
						<article class="bw-book">
							<div class="bw-book__cover"><img src="<?php echo bookwright_img( 'cover-' . $fi . '.svg' ); ?>" alt="" /></div>
							<h3><?php esc_html_e( 'Recent project', 'bookwright' ); ?></h3>
							<p class="bw-book__author"><?php esc_html_e( 'Client author', 'bookwright' ); ?></p>
						</article>
						<?php
					endfor;
			endif;
			?>
		</div>

		<div class="bw-center" style="margin-top:44px;">
			<a class="bw-btn bw-btn--ghost" href="<?php echo esc_url( get_permalink( get_page_by_path( 'portfolio' ) ) ); ?>"><?php esc_html_e( 'See our full portfolio', 'bookwright' ); ?> <?php bookwright_icon( 'arrow' ); ?></a>
		</div>
	</div>
</section>

<!-- ===================== STATS BAND ===================== -->
<section class="bw-section bw-section--tight bw-section--ink">
	<div class="bw-wrap">
		<div class="bw-stats-band">
			<?php
			$stat_defaults = array(
				1 => array( '750+', 'Books published' ),
				2 => array( '900+', 'Happy authors' ),
				3 => array( '15+', 'Years of experience' ),
				4 => array( '100%', 'Ownership you keep' ),
			);
			for ( $i = 1; $i <= 4; $i++ ) :
				?>
				<div class="bw-stat-lg">
					<strong><?php echo esc_html( bookwright_option( 'bw_stat' . $i . '_n', $stat_defaults[ $i ][0] ) ); ?></strong>
					<span><?php echo esc_html( bookwright_option( 'bw_stat' . $i . '_l', $stat_defaults[ $i ][1] ) ); ?></span>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</section>

<!-- ===================== WHY US (split) ===================== -->
<section class="bw-section">
	<div class="bw-wrap bw-split">
		<div>
			<img src="<?php echo bookwright_img( 'about.svg' ); ?>" alt="<?php esc_attr_e( 'Editors reviewing a manuscript', 'bookwright' ); ?>" />
		</div>
		<div>
			<span class="bw-eyebrow"><?php esc_html_e( 'Why authors choose us', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Your creative partner from first draft to final launch', 'bookwright' ); ?></h2>
			<p class="bw-lead"><?php esc_html_e( 'We treat every book like it’s our own. One dedicated team writes, edits, designs, publishes and markets your book — while you stay the author and owner throughout.', 'bookwright' ); ?></p>
			<ul class="bw-checklist">
				<li><?php esc_html_e( 'You keep 100% ownership and royalties — always', 'bookwright' ); ?></li>
				<li><?php esc_html_e( 'Transparent process with honest, no-hidden-fee pricing', 'bookwright' ); ?></li>
				<li><?php esc_html_e( 'A dedicated project manager as your single point of contact', 'bookwright' ); ?></li>
				<li><?php esc_html_e( 'On-time delivery — every step, every milestone', 'bookwright' ); ?></li>
			</ul>
			<div style="margin-top:28px;">
				<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' ) ) ); ?>"><?php esc_html_e( 'More about us', 'bookwright' ); ?></a>
			</div>
		</div>
	</div>
</section>

<!-- ===================== TESTIMONIALS ===================== -->
<section class="bw-section bw-section--sand">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'Loved by authors', 'bookwright' ); ?></span>
			<h2><?php echo esc_html( bookwright_option( 'bw_testi_title', 'What our authors say' ) ); ?></h2>
		</div>
		<div class="bw-quotes">
			<?php
			if ( bookwright_has_items( 'bw_testimonial' ) ) :
				$q = bookwright_get_items( 'bw_testimonial', 3 );
				while ( $q->have_posts() ) :
					$q->the_post();
					$role   = get_post_meta( get_the_ID(), '_bw_role', true );
					$rating = (int) get_post_meta( get_the_ID(), '_bw_rating', true );
					$rating = $rating ? $rating : 5;
					?>
					<figure class="bw-quote">
						<div class="bw-quote__stars"><?php echo esc_html( str_repeat( '★', $rating ) ); ?></div>
						<blockquote style="border:0;background:none;padding:0;margin:0;font-size:1.12rem;"><p style="margin:0;">&ldquo;<?php echo esc_html( wp_strip_all_tags( get_the_content() ) ); ?>&rdquo;</p></blockquote>
						<figcaption class="bw-quote__by">
							<img src="<?php echo esc_url( bookwright_entry_photo() ); ?>" alt="<?php the_title_attribute(); ?>" />
							<span><strong><?php the_title(); ?></strong><span><?php echo esc_html( $role ); ?></span></span>
						</figcaption>
					</figure>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				foreach ( bookwright_default_testimonials() as $t ) :
					?>
					<figure class="bw-quote">
						<div class="bw-quote__stars"><?php echo esc_html( str_repeat( '★', (int) $t[3] ) ); ?></div>
						<blockquote style="border:0;background:none;padding:0;margin:0;font-size:1.12rem;"><p style="margin:0;">&ldquo;<?php echo esc_html( $t[1] ); ?>&rdquo;</p></blockquote>
						<figcaption class="bw-quote__by">
							<img src="<?php echo bookwright_img( $t[4] ); ?>" alt="<?php echo esc_attr( $t[0] ); ?>" />
							<span><strong><?php echo esc_html( $t[0] ); ?></strong><span><?php echo esc_html( $t[2] ); ?></span></span>
						</figcaption>
					</figure>
					<?php
				endforeach;
			endif;
			?>
		</div>
	</div>
</section>

<!-- ===================== LATEST FROM JOURNAL ===================== -->
<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'From the journal', 'bookwright' ); ?></span>
			<h2><?php echo esc_html( bookwright_option( 'bw_journal_title', 'Tips for writers & authors' ) ); ?></h2>
		</div>
		<div class="bw-cards">
			<?php
			$recent = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3, 'ignore_sticky_posts' => true ) );
			if ( $recent->have_posts() ) :
				while ( $recent->have_posts() ) :
					$recent->the_post();
					?>
					<article class="bw-article" style="box-shadow:var(--bw-shadow-sm);">
						<a href="<?php the_permalink(); ?>" class="bw-article__thumb"><?php bookwright_thumbnail(); ?></a>
						<div class="bw-article__body">
							<div class="bw-article__meta"><?php bookwright_post_meta(); ?></div>
							<h2 style="font-size:1.25rem;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="bw-article__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
							<a class="bw-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'bookwright' ); ?> <?php bookwright_icon( 'arrow' ); ?></a>
						</div>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				esc_html_e( 'Journal posts will appear here once published.', 'bookwright' );
			endif;
			?>
		</div>
	</div>
</section>

<!-- ===================== FAQ ===================== -->
<section class="bw-section bw-section--sand">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'Good to know', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Frequently asked questions', 'bookwright' ); ?></h2>
		</div>
		<div class="bw-faq">
			<?php
			$faq_i = 0;
			if ( bookwright_has_items( 'bw_faq' ) ) :
				$fq = bookwright_get_items( 'bw_faq', 6 );
				while ( $fq->have_posts() ) :
					$fq->the_post();
					?>
					<details<?php echo 0 === $faq_i ? ' open' : ''; ?>>
						<summary><?php the_title(); ?></summary>
						<div class="bw-entry" style="padding-bottom:8px;"><?php the_content(); ?></div>
					</details>
					<?php
					$faq_i++;
				endwhile;
				wp_reset_postdata();
			else :
				foreach ( bookwright_default_faqs() as $f ) :
					?>
					<details<?php echo 0 === $faq_i ? ' open' : ''; ?>>
						<summary><?php echo esc_html( $f[0] ); ?></summary>
						<p><?php echo esc_html( $f[1] ); ?></p>
					</details>
					<?php
					$faq_i++;
				endforeach;
			endif;
			?>
		</div>
		<p class="bw-center" style="margin-top:26px;">
			<a class="bw-btn bw-btn--ghost" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php esc_html_e( 'Still have questions? Talk to us', 'bookwright' ); ?></a>
		</p>
	</div>
</section>

<!-- ===================== FINAL CTA ===================== -->
<section class="bw-section bw-section--tight">
	<div class="bw-wrap">
		<div class="bw-cta">
			<span class="bw-eyebrow" style="color:var(--bw-gold);"><?php esc_html_e( 'Ready when you are', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Ready to publish your book?', 'bookwright' ); ?></h2>
			<p><?php esc_html_e( 'Book a free, no-obligation consultation. We’ll talk through your goals and map out exactly what your book needs — start to finish.', 'bookwright' ); ?></p>
			<div class="bw-cta__actions">
				<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php esc_html_e( 'Book a free consult', 'bookwright' ); ?></a>
				<a class="bw-btn bw-btn--ghost" href="<?php echo esc_url( get_permalink( get_page_by_path( 'pricing' ) ) ); ?>"><?php esc_html_e( 'See pricing', 'bookwright' ); ?></a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
