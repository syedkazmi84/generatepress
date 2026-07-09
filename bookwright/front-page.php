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
			<span class="bw-eyebrow"><?php echo esc_html( bookwright_option( 'bw_hero_eyebrow', 'Trusted by 900+ authors worldwide' ) ); ?></span>
			<h1><?php
				$title = bookwright_option( 'bw_hero_title', 'From manuscript to masterpiece' );
				// Emphasise the last word.
				$words = explode( ' ', $title );
				$last  = array_pop( $words );
				echo esc_html( implode( ' ', $words ) ) . ' <span class="bw-accent">' . esc_html( $last ) . '</span>';
			?></h1>
			<p class="bw-hero__lead"><?php echo esc_html( bookwright_option( 'bw_hero_lead', 'Editing, cover design, publishing and marketing under one roof. We help you turn your story into a book readers can’t put down.' ) ); ?></p>
			<div class="bw-hero__actions">
				<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php echo esc_html( bookwright_option( 'bw_hero_btn1', 'Start your book' ) ); ?> <?php bookwright_icon( 'arrow' ); ?></a>
				<a class="bw-btn bw-btn--ghost" href="<?php echo esc_url( get_permalink( get_page_by_path( 'services' ) ) ); ?>"><?php echo esc_html( bookwright_option( 'bw_hero_btn2', 'View our services' ) ); ?></a>
			</div>
			<div class="bw-hero__stats">
				<div class="bw-hero__stat"><strong data-count="1200" data-suffix="+">1200+</strong><span><?php esc_html_e( 'Books published', 'bookwright' ); ?></span></div>
				<div class="bw-hero__stat"><strong data-count="98" data-suffix="%">98%</strong><span><?php esc_html_e( 'Author satisfaction', 'bookwright' ); ?></span></div>
				<div class="bw-hero__stat"><strong data-count="35" data-suffix="+">35+</strong><span><?php esc_html_e( 'Bestseller lists', 'bookwright' ); ?></span></div>
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
			<h2><?php echo esc_html( bookwright_option( 'bw_services_title', 'Everything your book needs, in one studio' ) ); ?></h2>
			<p class="bw-lead"><?php echo esc_html( bookwright_option( 'bw_services_lead', 'Pick a single service or hand us the whole journey. Either way you get a dedicated team and a clear plan.' ) ); ?></p>
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
			<h2><?php echo esc_html( bookwright_option( 'bw_process_title', 'A calm, four-step path to publication' ) ); ?></h2>
		</div>
		<div class="bw-steps">
			<?php
			$step_defaults = array(
				1 => array( 'Discover', 'We read your manuscript and map the fastest route to a finished book.' ),
				2 => array( 'Refine', 'Editing and design shape the words and the look until both sing.' ),
				3 => array( 'Publish', 'We format, proof and distribute across every major store and format.' ),
				4 => array( 'Launch', 'A tailored marketing push builds momentum from day one.' ),
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

<!-- ===================== FEATURED BOOKS ===================== -->
<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'From our catalog', 'bookwright' ); ?></span>
			<h2><?php echo esc_html( bookwright_option( 'bw_books_title', 'Books we helped bring to the world' ) ); ?></h2>
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
				// Fallback static covers if no books exist yet.
				$fallback = array(
					array( 'The Lantern Keeper', 'Eleanor Vance', 'cover-1.svg' ),
					array( 'Building Quiet Wealth', 'Marcus Ellison', 'cover-2.svg' ),
					array( 'Saltwater Girlhood', 'Nadia Okafor', 'cover-3.svg' ),
					array( 'The Clockwork Garden', 'Theo Marsh', 'cover-4.svg' ),
				);
				foreach ( $fallback as $f ) :
					?>
					<article class="bw-book">
						<div class="bw-book__cover"><img src="<?php echo bookwright_img( $f[2] ); ?>" alt="<?php echo esc_attr( $f[0] ); ?>" /></div>
						<h3><?php echo esc_html( $f[0] ); ?></h3>
						<p class="bw-book__author"><?php echo esc_html( $f[1] ); ?></p>
					</article>
					<?php
				endforeach;
			endif;
			?>
		</div>

		<div class="bw-center" style="margin-top:44px;">
			<a class="bw-btn bw-btn--ghost" href="<?php echo esc_url( get_permalink( get_page_by_path( 'books-catalog' ) ) ); ?>"><?php esc_html_e( 'Browse the full catalog', 'bookwright' ); ?> <?php bookwright_icon( 'arrow' ); ?></a>
		</div>
	</div>
</section>

<!-- ===================== STATS BAND ===================== -->
<section class="bw-section bw-section--tight bw-section--ink">
	<div class="bw-wrap">
		<div class="bw-stats-band">
			<?php
			$stat_defaults = array(
				1 => array( '1200+', 'Titles published' ),
				2 => array( '45M+', 'Copies sold' ),
				3 => array( '35+', 'Bestseller lists' ),
				4 => array( '60+', 'Countries reached' ),
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
			<h2><?php esc_html_e( 'A publishing partner that actually reads your book', 'bookwright' ); ?></h2>
			<p class="bw-lead"><?php esc_html_e( 'No templates, no assembly line. Every project gets a dedicated editor, designer and project manager who care about your work as much as you do.', 'bookwright' ); ?></p>
			<ul class="bw-checklist">
				<li><?php esc_html_e( 'Transparent, milestone-based pricing — no surprise fees', 'bookwright' ); ?></li>
				<li><?php esc_html_e( 'You keep 100% of your rights and royalties', 'bookwright' ); ?></li>
				<li><?php esc_html_e( 'Award-winning designers and editors from major imprints', 'bookwright' ); ?></li>
				<li><?php esc_html_e( 'A dedicated project manager from first draft to launch', 'bookwright' ); ?></li>
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
			<h2><?php echo esc_html( bookwright_option( 'bw_journal_title', 'Guides for writers & self-publishers' ) ); ?></h2>
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

<!-- ===================== FINAL CTA ===================== -->
<section class="bw-section bw-section--tight">
	<div class="bw-wrap">
		<div class="bw-cta">
			<span class="bw-eyebrow" style="color:var(--bw-gold);"><?php esc_html_e( 'Ready when you are', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Let’s make the book you’ve been meaning to write', 'bookwright' ); ?></h2>
			<p><?php esc_html_e( 'Book a free 30-minute consultation. We’ll read a sample, talk goals, and map out exactly what your book needs — no obligation.', 'bookwright' ); ?></p>
			<div class="bw-cta__actions">
				<a class="bw-btn bw-btn--primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>"><?php esc_html_e( 'Book a free consult', 'bookwright' ); ?></a>
				<a class="bw-btn bw-btn--ghost" href="<?php echo esc_url( get_permalink( get_page_by_path( 'pricing' ) ) ); ?>"><?php esc_html_e( 'See pricing', 'bookwright' ); ?></a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
