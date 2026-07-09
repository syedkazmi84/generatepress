<?php
/**
 * Template Name: Contact Page
 *
 * @package Bookwright
 */

get_header();
?>
<section class="bw-page-hero">
	<div class="bw-wrap">
		<?php bookwright_breadcrumb(); ?>
		<h1><?php esc_html_e( 'Book your free consultation', 'bookwright' ); ?></h1>
		<p><?php esc_html_e( 'Tell us about your book and we’ll get back to you within one business day to schedule your free, no-obligation call.', 'bookwright' ); ?></p>
	</div>
</section>

<section class="bw-section">
	<div class="bw-wrap">
		<div class="bw-contact-grid">

			<!-- Info column -->
			<div>
				<span class="bw-eyebrow"><?php esc_html_e( 'Get in touch', 'bookwright' ); ?></span>
				<h2><?php esc_html_e( 'We’d love to hear your story', 'bookwright' ); ?></h2>
				<p class="bw-lead"><?php esc_html_e( 'Prefer email or phone? Reach us directly, or drop by the studio.', 'bookwright' ); ?></p>

				<div class="bw-contact-info" style="margin-top:26px;">
					<div class="bw-contact-item">
						<div class="bw-card__icon"><?php bookwright_icon( 'mail' ); ?></div>
						<div><strong><?php esc_html_e( 'Email', 'bookwright' ); ?></strong><span><a href="mailto:<?php echo esc_attr( bookwright_option( 'bw_email', 'hello@bookwright.studio' ) ); ?>"><?php echo esc_html( bookwright_option( 'bw_email', 'hello@bookwright.studio' ) ); ?></a></span></div>
					</div>
					<div class="bw-contact-item">
						<div class="bw-card__icon"><?php bookwright_icon( 'phone' ); ?></div>
						<div><strong><?php esc_html_e( 'Phone', 'bookwright' ); ?></strong><span><a href="tel:<?php echo esc_attr( bookwright_option( 'bw_phone', '' ) ); ?>"><?php echo esc_html( bookwright_option( 'bw_phone', '+1 (212) 555-0139' ) ); ?></a></span></div>
					</div>
					<div class="bw-contact-item">
						<div class="bw-card__icon"><?php bookwright_icon( 'pin' ); ?></div>
						<div><strong><?php esc_html_e( 'Studio', 'bookwright' ); ?></strong><span><?php echo esc_html( bookwright_option( 'bw_address', '48 Gramercy Park, New York, NY 10010' ) ); ?></span></div>
					</div>
					<div class="bw-contact-item">
						<div class="bw-card__icon"><?php bookwright_icon( 'clock' ); ?></div>
						<div><strong><?php esc_html_e( 'Hours', 'bookwright' ); ?></strong><span><?php echo esc_html( bookwright_option( 'bw_hours', 'Mon–Fri · 9am–6pm EST' ) ); ?></span></div>
					</div>
				</div>

				<div class="bw-map" style="margin-top:26px;">
					<iframe title="<?php esc_attr_e( 'Studio location map', 'bookwright' ); ?>" src="https://www.openstreetmap.org/export/embed.html?bbox=-73.99%2C40.735%2C-73.978%2C40.742&amp;layer=mapnik" loading="lazy"></iframe>
				</div>
			</div>

			<!-- Form column -->
			<div class="bw-card" style="padding:38px;">
				<h3><?php esc_html_e( 'Book a free consultation', 'bookwright' ); ?></h3>
				<p style="color:var(--bw-muted);"><?php esc_html_e( 'Share a few details and we’ll be in touch to set up your call.', 'bookwright' ); ?></p>
				<?php
				// Use Contact Form 7 / WPForms shortcode if the page contains one; otherwise show a styled demo form.
				$content = get_post_field( 'post_content', get_the_ID() );
				if ( has_shortcode( $content, 'contact-form-7' ) || has_shortcode( $content, 'wpforms' ) || has_shortcode( $content, 'gravityform' ) ) {
					echo do_shortcode( $content );
				} else {
					?>
					<form class="bw-form" data-demo-form action="#" method="post" style="margin-top:18px;">
						<div class="bw-row">
							<div class="bw-field">
								<label for="bw-name"><?php esc_html_e( 'Your name', 'bookwright' ); ?></label>
								<input id="bw-name" type="text" name="name" required />
							</div>
							<div class="bw-field">
								<label for="bw-email"><?php esc_html_e( 'Email', 'bookwright' ); ?></label>
								<input id="bw-email" type="email" name="email" required />
							</div>
						</div>
						<div class="bw-field">
							<label for="bw-service"><?php esc_html_e( 'What do you need?', 'bookwright' ); ?></label>
							<select id="bw-service" name="service">
								<option><?php esc_html_e( 'Editing &amp; proofreading', 'bookwright' ); ?></option>
								<option><?php esc_html_e( 'Cover &amp; interior design', 'bookwright' ); ?></option>
								<option><?php esc_html_e( 'Publishing &amp; distribution', 'bookwright' ); ?></option>
								<option><?php esc_html_e( 'Marketing &amp; launch', 'bookwright' ); ?></option>
								<option><?php esc_html_e( 'The full package', 'bookwright' ); ?></option>
								<option><?php esc_html_e( 'Not sure yet', 'bookwright' ); ?></option>
							</select>
						</div>
						<div class="bw-field">
							<label for="bw-msg"><?php esc_html_e( 'Tell us about your book', 'bookwright' ); ?></label>
							<textarea id="bw-msg" name="message" placeholder="<?php esc_attr_e( 'Genre, word count, where you are in the process…', 'bookwright' ); ?>"></textarea>
						</div>
						<button type="submit" class="bw-btn bw-btn--primary"><?php esc_html_e( 'Send message', 'bookwright' ); ?> <?php bookwright_icon( 'arrow' ); ?></button>
						<p class="bw-form__note" style="display:none;background:var(--bw-sand);padding:12px 16px;border-radius:8px;color:var(--bw-gold-dark);margin:0;"></p>
					</form>
					<p style="font-size:.82rem;color:var(--bw-muted);margin-top:14px;"><?php esc_html_e( 'Tip: install a form plugin (Contact Form 7 or WPForms) and paste its shortcode into this page to receive real submissions.', 'bookwright' ); ?></p>
					<?php
				}
				?>
			</div>

		</div>
	</div>
</section>

<?php
get_footer();
