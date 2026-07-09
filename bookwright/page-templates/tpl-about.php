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
		<h1><?php esc_html_e( 'Your creative partner in publishing', 'bookwright' ); ?></h1>
		<p><?php esc_html_e( 'We’re writers, editors, designers and marketers who help authors publish confidently — while keeping full ownership of their work.', 'bookwright' ); ?></p>
	</div>
</section>

<!-- Story -->
<section class="bw-section">
	<div class="bw-wrap bw-split">
		<div><img src="<?php echo bookwright_img( 'about.svg' ); ?>" alt="<?php esc_attr_e( 'Our team at work', 'bookwright' ); ?>" /></div>
		<div>
			<span class="bw-eyebrow"><?php esc_html_e( 'Our story', 'bookwright' ); ?></span>
			<h2><?php esc_html_e( 'We’re not just a publishing company — we’re your creative partners', 'bookwright' ); ?></h2>
			<p><?php esc_html_e( 'We exist to simplify publishing. From the first idea to the final launch, we combine ghostwriting, editing, design, publishing and marketing under one roof — so you can share your message with readers around the world.', 'bookwright' ); ?></p>
			<p><?php esc_html_e( 'Hundreds of authors have trusted us for writing, editing, publishing and marketing — with consistent, professional results and honest, transparent pricing. We treat every story like our own, and you always remain the sole author and rights-holder.', 'bookwright' ); ?></p>
			<ul class="bw-checklist">
				<li><?php esc_html_e( 'Personalised, one-on-one guidance on every project', 'bookwright' ); ?></li>
				<li><?php esc_html_e( 'You keep 100% ownership — always', 'bookwright' ); ?></li>
				<li><?php esc_html_e( 'A transparent process with no hidden fees', 'bookwright' ); ?></li>
			</ul>
		</div>
	</div>
</section>

<!-- Values / stats -->
<section class="bw-section bw-section--tight bw-section--ink">
	<div class="bw-wrap">
		<div class="bw-stats-band">
			<div class="bw-stat-lg"><strong>750+</strong><span><?php esc_html_e( 'Books published', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong>900+</strong><span><?php esc_html_e( 'Authors served', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong>15+</strong><span><?php esc_html_e( 'Years of experience', 'bookwright' ); ?></span></div>
			<div class="bw-stat-lg"><strong>100%</strong><span><?php esc_html_e( 'Ownership you keep', 'bookwright' ); ?></span></div>
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
				array( 'shield', 'Author-first', 'You keep 100% ownership, your royalties and the final say. We stay behind the scenes.' ),
				array( 'award', 'Uncompromising craft', 'Skilled ghostwriters, editors, designers and consultants with real publishing experience.' ),
				array( 'chart', 'Transparent process', 'Clear steps, flexible options and honest pricing with no hidden fees — start to finish.' ),
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
