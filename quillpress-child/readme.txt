=== Quill & Press — Book Publishing Services (GeneratePress Child Theme) ===

Contributors: quillandpress
Requires at least: WordPress 6.4
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Template: generatepress

A complete, ready-to-import website for a professional book-publishing-services
company. One click builds every page, the blog, menus and settings — with
bundled artwork included, so the site is ready the moment the import finishes.


== What you get ==

Clicking "Import All" builds an entire, professionally designed website:

* Home ....................... hero, services, "why us", stats, process,
                               portfolio preview and testimonials
* About ...................... story, values, team and stats
* Services ................... overview of all six services
* 6 service pages ............ Editing & Proofreading, Ghostwriting,
                               Cover Design, Formatting & Typesetting,
                               Book Marketing, Publishing & Distribution
* Portfolio .................. six illustrated book covers
* Pricing .................... three packages with a "most popular" plan
* Testimonials ............... six author reviews
* FAQ ........................ eight questions in native accordions
* Contact .................... contact tiles and a styled enquiry form
* Blog ....................... three ready-written posts with featured images

It also configures the header + footer menus, sets the homepage, imports the
bundled artwork into the Media Library, and applies matching theme settings.

Everything is built with GenerateBlocks blocks, so every page stays
fully editable after import.


== Requirements ==

1. The GeneratePress theme (free) installed. This is a child theme of it.
2. The GenerateBlocks plugin (free) active — REQUIRED. Every page is built with
   GenerateBlocks blocks (Container, Grid, Headline, Button, Image), so the
   plugin must be active for the layouts and their generated CSS to render.

The bundled zips for GeneratePress, GenerateBlocks, GenerateBlocks Pro and
GP Premium are included in this project's repository.


== Installation ==

1. Install and activate the GeneratePress parent theme (Appearance → Themes →
   Add New → Upload, using generatepress.3.6.1.zip).
2. Install the GenerateBlocks plugin (Plugins → Add New → Upload).
3. Upload and activate this child theme (Appearance → Themes → Add New →
   Upload → quillpress-child.zip).
4. Go to Appearance → Import Demo.
5. Click "Import All Content" and wait a few seconds. Done.

Prefer WP-CLI? After activating the theme you can also run:

    wp --user=admin eval 'Quill_Press_Demo_Importer::instance()->run_import();'


== After importing ==

* Re-running the importer refreshes the demo pages to their original content.
  Your other posts and pages are never touched — demo items are matched by a
  hidden meta key and updated in place, never duplicated.
* Rename "Quill & Press" to your own brand in Settings → General (site title),
  in the footer text (functions.php → quillpress_render_footer), and on each
  page in the editor.
* Replace the bundled SVG artwork in /assets/images with your own images.
* The contact page form is display-only. Drop in Contact Form 7, WPForms or
  Fluent Forms to make it send email.


== Notes ==

* SVG uploads are enabled so the bundled artwork works in the Media Library.
  Only upload SVGs from sources you trust.
* All presentation lives in /assets/css/main.css. Class names are prefixed
  "qp-" so they never clash with GeneratePress or GenerateBlocks.


== Changelog ==

= 1.0.0 =
* Initial release: full book-publishing demo site + one-click importer.
