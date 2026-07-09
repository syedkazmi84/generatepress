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
			<li>The Paper Review</li>
			<li>Inkwell</li>
			<li>Readerly</li>
			<li>Prose &amp; Co.</li>
			<li>Bindery</li>
		</ul>
	</div>
</div>

<!-- ===================== SERVICES ===================== -->
<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'What we do', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Everything your book needs, in one studio', 'bookwright' ); ?></h2>
			<p class="bw-lead"><?php esc_html_e( 'Pick a single service or hand us the whole journey. Either way you get a dedicated team and a clear plan.', 'bookwright' ); ?></p>
		</div>

		<div class="bw-cards">
			<?php
			$services = array(
				array( 'edit', 'Editorial &amp; Proofreading', 'Developmental, line and copy editing that sharpens your story while protecting your voice.' ),
				array( 'design', 'Cover &amp; Interior Design', 'Scroll-stopping covers and beautiful, readable interiors for print and ebook.' ),
				array( 'book', 'Publishing &amp; Distribution', 'We set up and launch across Amazon KDP, IngramSpark, Apple Books and more.' ),
				array( 'megaphone', 'Book Marketing', 'Launch strategy, ads, email and PR that put your book in front of the right readers.' ),
				array( 'quill', 'Ghostwriting', 'Have the idea but not the time? Our writers turn your vision into finished chapters.' ),
				array( 'globe', 'Audiobook Production', 'Professional narration and mastering to reach listeners on Audible and beyond.' ),
			);
			foreach ( $services as $s ) :
				?>
				<article class="bw-card">
					<div class="bw-card__icon"><?php bookwright_icon( $s[0] ); ?></div>
					<h3><?php echo wp_kses_post( $s[1] ); ?></h3>
					<p><?php echo esc_html( $s[2] ); ?></p>
					<a class="bw-card__link" href="<?php echo esc_url( get_permalink( get_page_by_path( 'services' ) ) ); ?>"><?php esc_html_e( 'Learn more', 'bookwright' ); ?></a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===================== PROCESS ===================== -->
<section class="bw-section bw-section--sand">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'How it works', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'A calm, four-step path to publication', 'bookwright' ); ?></h2>
		</div>
		<div class="bw-steps">
			<?php
			$steps = array(
				array( 'Discover', 'We read your manuscript and map the fastest route to a finished book.' ),
				array( 'Refine', 'Editing and design shape the words and the look until both sing.' ),
				array( 'Publish', 'We format, proof and distribute across every major store and format.' ),
				array( 'Launch', 'A tailored marketing push builds momentum from day one.' ),
			);
			$n = 1;
			foreach ( $steps as $st ) :
				?>
				<div class="bw-step">
					<div class="bw-step__num"><?php echo esc_html( sprintf( '%02d', $n ) ); ?></div>
					<h4><?php echo esc_html( $st[0] ); ?></h4>
					<p><?php echo esc_html( $st[1] ); ?></p>
				</div>
				<?php
				$n++;
			endforeach;
			?>
		</div>
	</div>
</section>

<!-- ===================== FEATURED BOOKS ===================== -->
<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'From our catalog', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Books we helped bring to the world', 'bookwright' ); ?></h2>
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
			<div class="bw-stat-lg"><strong data-count="1200" data-suffix="+">1200+</strong><span><?php esc_html_e( 'Titles published', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong data-count="45" data-suffix="M+">45M+</strong><span><?php esc_html_e( 'Copies sold', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong data-count="35" data-suffix="+">35+</strong><span><?php esc_html_e( 'Bestseller lists', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong data-count="60" data-suffix="+">60+</strong><span><?php esc_html_e( 'Countries reached', 'bookwright' ); ?></span></div>
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
			<h2><?php esc_html_e( 'What our authors say', 'bookwright' ); ?></h2>
		</div>
		<div class="bw-quotes">
			<?php
			$quotes = array(
				array( 'Bookwright took my rough manuscript and turned it into a book I’m genuinely proud of. The cover alone doubled my click-through.', 'Eleanor Vance', 'Author of The Lantern Keeper', 'avatar-1.svg' ),
				array( 'The team hit every deadline and treated my little memoir like it was the next big bestseller. I felt supported the whole way.', 'Nadia Okafor', 'Author of Saltwater Girlhood', 'avatar-2.svg' ),
				array( 'I came for editing and stayed for the full launch. First week on Amazon we hit #1 in two categories. Worth every penny.', 'Marcus Ellison', 'Author of Building Quiet Wealth', 'avatar-3.svg' ),
			);
			foreach ( $quotes as $q ) :
				?>
				<figure class="bw-quote">
					<div class="bw-quote__stars">★★★★★</div>
					<blockquote style="border:0;background:none;padding:0;margin:0;font-size:1.12rem;"><p style="margin:0;">&ldquo;<?php echo esc_html( $q[0] ); ?>&rdquo;</p></blockquote>
					<figcaption class="bw-quote__by">
						<img src="<?php echo bookwright_img( $q[3] ); ?>" alt="<?php echo esc_attr( $q[1] ); ?>" />
						<span><strong><?php echo esc_html( $q[1] ); ?></strong><span><?php echo esc_html( $q[2] ); ?></span></span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===================== LATEST FROM JOURNAL ===================== -->
<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'From the journal', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Guides for writers &amp; self-publishers', 'bookwright' ); ?></h2>
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
