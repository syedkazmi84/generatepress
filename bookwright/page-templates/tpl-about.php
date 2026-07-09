<?php
/**
 * Template Name: About Page
 *
 * @package Bookwright
 */

get_header();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1><?php esc_html_e( 'A studio built by book people', 'bookwright' ); ?></h1>
		<p><?php esc_html_e( 'We’re editors, designers and marketers who left big publishing to give independent authors the same craft — without giving up their rights.', 'bookwright' ); ?></p>
	</div>
</section>

<!-- Story -->
<section class="bw-section">
	<div class="bw-wrap bw-split">
		<div><img src="<?php echo bookwright_img( 'about.svg' ); ?>" alt="<?php esc_attr_e( 'The Bookwright team at work', 'bookwright' ); ?>" /></div>
		<div>
			<span class="bw-eyebrow"><?php esc_html_e( 'Our story', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Founded on a simple belief: every good book deserves great publishing', 'bookwright' ); ?></h2>
			<p><?php esc_html_e( 'Bookwright began in 2014 when a group of publishing veterans grew frustrated watching brilliant manuscripts fail — not for lack of talent, but for lack of support. We set out to build the studio we wished authors had.', 'bookwright' ); ?></p>
			<p><?php esc_html_e( 'A decade later we’ve helped more than 1,200 authors publish books that sell, win awards and change lives — while keeping 100% of their rights and royalties.', 'bookwright' ); ?></p>
			<ul class="bw-checklist">
				<li><?php esc_html_e( 'Craft first — real editors, real designers, real care', 'bookwright' ); ?></li>
				<li><?php esc_html_e( 'Author-owned — your book, your rights, always', 'bookwright' ); ?></li>
				<li><?php esc_html_e( 'Transparent pricing with no hidden fees', 'bookwright' ); ?></li>
			</ul>
		</div>
	</div>
</section>

<!-- Values / stats -->
<section class="bw-section bw-section--tight bw-section--ink">
	<div class="bw-wrap">
		<div class="bw-stats-band">
			<div class="bw-stat-lg"><strong data-count="2014">2014</strong><span><?php esc_html_e( 'Founded', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong data-count="1200" data-suffix="+">1200+</strong><span><?php esc_html_e( 'Authors served', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong data-count="40" data-suffix="+">40+</strong><span><?php esc_html_e( 'Team members', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong data-count="18" data-suffix="+">18+</strong><span><?php esc_html_e( 'Industry awards', 'bookwright' ); ?></span></div>
		</div>
	</div>
</section>

<!-- Values cards -->
<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'What we stand for', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'The values behind every project', 'bookwright' ); ?></h2>
		</div>
		<div class="bw-cards">
			<?php
			$vals = array(
				array( 'shield', 'Author-first', 'You keep your rights, your royalties and the final say. We’re partners, not gatekeepers.' ),
				array( 'award', 'Uncompromising craft', 'The same editors and designers behind major imprint titles, focused on your book.' ),
				array( 'chart', 'Results that matter', 'We measure success in reviews, rankings and readers — not just delivered files.' ),
			);
			foreach ( $vals as $v ) :
				?>
				<article class="bw-card">
					<div class="bw-card__icon"><?php bookwright_icon( $v[0] ); ?></div>
					<h3><?php echo esc_html( $v[1] ); ?></h3>
					<p><?php echo esc_html( $v[2] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Team -->
<section class="bw-section bw-section--sand">
	<div class="bw-wrap">
		<div class="bw-section-head bw-center">
			<span class="bw-eyebrow"><?php esc_html_e( 'The people', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'Meet the team', 'bookwright' ); ?></h2>
		</div>
		<div class="bw-team">
			<?php
			if ( bookwright_has_items( 'bw_team' ) ) :
				$q = bookwright_get_items( 'bw_team' );
				while ( $q->have_posts() ) :
					$q->the_post();
					$role = get_post_meta( get_the_ID(), '_bw_role', true );
					?>
					<div class="bw-member">
						<div class="bw-member__photo"><img src="<?php echo esc_url( bookwright_entry_photo() ); ?>" alt="<?php the_title_attribute(); ?>" /></div>
						<h4><?php the_title(); ?></h4>
						<span><?php echo esc_html( $role ); ?></span>
						<?php if ( get_the_content() ) : ?><p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 22 ) ); ?></p><?php endif; ?>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				foreach ( bookwright_default_team() as $m ) :
					?>
					<div class="bw-member">
						<div class="bw-member__photo"><img src="<?php echo bookwright_img( $m[2] ); ?>" alt="<?php echo esc_attr( $m[0] ); ?>" /></div>
						<h4><?php echo esc_html( $m[0] ); ?></h4>
						<span><?php echo esc_html( $m[1] ); ?></span>
					</div>
					<?php
				endforeach;
			endif;
			?>
		</div>
	</div>
</section>

<?php
// Optional editor content from the page itself.
while ( have_posts() ) :
	the_post();
	if ( trim( get_the_content() ) ) :
		?>
		<section class="bw-section">
			<div class="bw-wrap" style="max-width:820px;">
				<div class="bw-entry"><?php the_content(); ?></div>
			</div>
		</section>
		<?php
	endif;
endwhile;

get_template_part( 'template-parts/cta' );
get_footer();
