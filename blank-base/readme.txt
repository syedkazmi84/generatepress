=== Blank Base ===

Contributors: blankbase
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 8.0
Stable tag: 3.1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: blog, one-column, two-columns, right-sidebar, left-sidebar, custom-background, custom-logo, custom-menu, custom-header, featured-images, footer-widgets, sticky-post, threaded-comments, translation-ready, block-styles, wide-blocks, accessibility-ready, editor-style, full-width-template, rtl-language-support, theme-options

A clean, lightweight and fully standards-compliant blank starter theme for WordPress.

== Description ==

Blank Base is an intentionally blank WordPress theme: it ships with no hard-coded
content. Every piece of data — the logo, menus, widgets, colors, footer text and
all posts and pages — is added dynamically by the site owner through the
WordPress admin, the Customizer and widgets.

Even though it starts blank, Blank Base includes every option and template a
production theme needs:

* The full WordPress template hierarchy (index, single, page, archive, search,
  404, home, front-page, comments and template parts).
* Custom logo, custom header and custom background support.
* Three navigation menu locations (Primary, Footer and Social).
* Two sidebar widget areas (right + left) plus up to five footer widget columns.
* A GeneratePress-style layout engine: choose right, left, both, no sidebar or
  full-width — globally, per context (blog, posts, pages, archives) or per post
  via a Layout meta box (which can also hide the content title).
* A theme hook framework with 20+ action hooks (before/after header, content,
  main, sidebars, footer and the footer bar) for child themes and plugins.
* Hook Elements (Appearance → Elements): inject block content into any hook
  with display rules — no code required, like GeneratePress Elements.
* Primary navigation options: location (inside header left/right/centered, or a
  full-width bar above or below the header), hover or click sub-menus, and a
  dropdown or off-canvas mobile menu.
* A footer bar (copyright + footer menu + social links) with its own layout,
  separate from the configurable footer widget columns.
* A full color manager: per-element colors for the header, navigation, content,
  buttons, footer widgets and footer bar.
* A full typography manager: font family, weight, size, line-height and
  text-transform for the body, headings (including per-heading H1–H6 sizes),
  site title and navigation.
* A Customizer "Theme Options" section with sidebar position, accent color and
  footer text — all with live preview.
* theme.json with color palette, typography and spacing presets for the block
  editor, including wide/full alignment and editor styles.
* Accessibility-ready markup: skip links, screen-reader text, keyboard-friendly
  dropdown menus and proper landmark roles.
* Translation-ready with a bundled .pot file, and RTL language support.
* Block editor and block styles support.
* Getwid-style interactive blocks: Tabs, Accordion, Image Slider, Content
  Slider, Testimonial Carousel and Post Carousel, plus counters, icon boxes,
  pricing, team and more — each with a shared alignment / entrance-animation /
  hover options panel.

== Installation ==

1. In your admin panel, go to Appearance → Themes and click the Add New button.
2. Click Upload Theme and choose the blank-base.zip file, then click Install Now.
3. Click Activate to use the theme.
4. Navigate to Appearance → Customize to configure your options, set a logo,
   choose menus and add widgets.

== Frequently Asked Questions ==

= Does it include demo content? =

The theme activates blank — nothing is forced onto your site. If you would like
a ready-made starting point, go to Appearance → Import Demo Content and click
"Import Demo Content" to build a complete "Quill & Press" book-publishing
website (pages, posts, images, menus and colours) in one click. Everything is
authored in native Gutenberg blocks, so it is fully editable, and the import is
optional and idempotent. You can also build pages from scratch with the bundled
block patterns (inserter → "Blank Base" categories) and Appearance → Customize.

= Does it support the block editor? =

Yes. The theme ships a theme.json file, editor styles, block styles and support
for wide and full-width alignments.

= Is it translation ready? =

Yes. All strings use the `blank-base` text domain and a languages/blank-base.pot
file is bundled. RTL layouts are supported via rtl.css.

== Changelog ==

= 3.1.0 =
* New: one-click Demo Content importer under Appearance → Import Demo Content. It builds a complete, ready-to-use "Quill & Press" book-publishing website — Home, About, Services, Our Books, Pricing, FAQ, Contact and a Journal (blog) with three posts — all authored in native Gutenberg blocks and fully editable after import.
* The importer side-loads the bundled demo images (logo, hero, book covers, service tiles, team avatars and blog art) into the Media Library, builds the primary and footer menus, sets the homepage and blog page, uploads the logo and applies a cohesive book-publishing colour scheme. Everything ships inside the theme — no plugin and no remote server.
* The import is idempotent: running it again refreshes the demo pages, posts and menus instead of creating duplicates, and it enables pretty permalinks and clears WordPress's default sample content for a clean start.
* Added a book-publishing brand palette and gradients to theme.json (Gold Accent, Ink, Cream, Terracotta) plus a demo-content stylesheet for the à-la-carte check lists and contact form.

= 3.0.1 =
* Removed the collapsible header search icon and all of its supporting code (the site search remains available via search.php and the search form).
* Elements assigned to the "Inside header" location now align with the navigation (fixes an account/login button sitting below the nav baseline).
* Reduced the header height with tighter top/bottom padding.

= 3.0.0 =
* Per page/post breadcrumb control: the Blank Base Layout meta box now has a "Breadcrumbs" option (Default / Show / Hide) so you can turn the breadcrumb trail on or off for any individual page or post, overriding the site-wide "Show Breadcrumbs" Customizer default. "Default" follows the Customizer setting.

= 2.9.2 =
* Added a GeneratePress-style front-end skin (assets/css/gp-style.css): separate boxed containers, 1200px container, 70/30 sidebar and GeneratePress default typography, spacing and colours.
* Removed Dark Mode entirely: the Customizer "Dark Mode" control, the no-flash head script, the front-end toggle button and all `[data-theme="dark"]` styles. The theme is now light only. The Colors section keeps the preset and per-element colour controls.

= 2.9.1 =
* Unified the accent colour system: theme.json (Primary/Secondary) and the "Classic Blue" default preset now match the front-end `--bb-color-link` tokens (#2563eb / #1d4ed8), so the editor, buttons and links agree on a fresh install.
* Aligned theme.json `wideSize` (75rem) with the front-end `--bb-wide-width`, so wide-aligned blocks match between the editor and the page.
* Performance: the Hook Elements loader is now cached in a transient (busted on element save/trash/delete) and short-circuits when no elements exist, instead of running a get_posts() on every front-end request.
* Added `preconnect` resource hints for Google Fonts, and documented the GDPR/self-hosting tradeoff.
* Housekeeping: bumped "Tested up to" to 6.8 and raised the PHP floor to 8.0.

= 2.9.0 =
* Removed the bundled demo import: deleted demo/blank-base-demo.xml, demo/README.md and tools/generate-demo.php, and removed the "Import demo content" button and all demo references from the help page, documentation and readme. Build pages with the block patterns instead.


= 2.8.3 =
* Merged Post Slider into Post Carousel. The two blocks were the same engine differing only in default slides-per-view, so Post Slider is no longer offered in the inserter — use Post Carousel and set "Slides per view" to 1 for a full-width, one-post-at-a-time slider. Any existing Post Slider blocks keep rendering (their server render is retained; the block is just hidden from the inserter).


= 2.8.2 =
* Post Carousel / Post Slider: additionally declare the align, className and shared Design & Motion attributes on the server block so the ServerSideRender editor preview can never be rejected by the REST block-renderer ("Invalid parameter(s): attributes"), regardless of what the editor sends. Belt-and-suspenders with the 2.8.1 fix.


= 2.8.1 =
* Fixed the "Error loading block: Invalid parameter(s): attributes" shown by the Post Carousel and Post Slider in the editor. Their ServerSideRender preview was sending the shared align / Design & Motion attributes, which the server block does not declare, so the REST renderer rejected them. The preview now sends only the query/carousel attributes the server registers.


= 2.8.0 =
* New blocks (Getwid-style, no libraries): Tabs, Accordion, Image Slider, Content Slider, Testimonial Carousel, Post Carousel and Post Slider. Sliders/carousels support slides-per-view (desktop + tablet), autoplay, arrows, dots and touch/swipe; Tabs and Accordion are keyboard-accessible. The Post Carousel and Post Slider are dynamic (query recent posts by number, order, category, with featured image / excerpt toggles).
* New: striped and bordered style variations for the core Table block.
* Interactive behaviour is powered by a single lightweight vanilla-JS engine (assets/js/blocks-interactive.js) that respects prefers-reduced-motion and degrades gracefully without JavaScript.

= 2.7.1 =
* Icon Box spacing polish: the icon is now a block element with a consistent 1rem gap below it, and the title/text gaps and line-heights were increased so the icon, heading and description have clear, even vertical rhythm (fixes the cramped default spacing).


= 2.7.0 =
* Blocks: added a shared "Design & Motion" panel to all 12 custom blocks — content alignment, an entrance animation (fade / rise / zoom / slide / drop, with a delay, playing on scroll) and a hover effect (lift / grow / shadow). Implemented with block filters, so a block left untouched saves exactly as before and existing content stays valid.
* Icon Box: greatly expanded options — icon style (plain / circle / square), icon size, icon color and background, layout (icon on top / left / right), and the heading tag (H2–H6).
* Testimonial: expanded options — photo shape (circle / rounded / square), photo size, layout (stacked / photo left / photo right) and separate colors for the quote, name and role.
* These bring the two blocks much closer to a dedicated blocks plugin like Getwid while keeping the theme's blocks lightweight by default.

= 2.6.1 =
* Hook Elements can now inject scripts/analytics: added "Site <head>" and "Site footer (before </body>)" as hook locations. These output their content raw — no wrapper element and no auto-paragraphs — so a <script>, <style> or <meta> pasted into a Custom HTML block runs verbatim (e.g. Google Analytics, a search-console verification tag, or a chat widget). Display rules still apply, so you can scope a tag to specific pages. (WordPress only stores raw script tags for users allowed to post unfiltered HTML — administrators on a standard single-site install.)

= 2.6.0 =
* New: Hook Elements (Appearance → Elements) — a GeneratePress-style manager for injecting block-editor content into the theme's hook locations without writing code. Each Element picks a hook (top bar, before/after header, before/after content or main, before/after entry content, before/inside/after footer, footer bar, and more), a priority, and a display rule (entire site, front page, blog, all posts, all pages, all archives, or specific IDs) with an optional exclude list. Content is authored in the block editor and rendered wherever the rule matches. Adds a `blank_base_element_display` filter to refine matching in code.

= 2.5.5 =
* "Full width (edge to edge)" now genuinely fills the width. Pages normally cap their content, title and headers to the wide content width (~1200px) for readability; that cap was still applying under the edge-to-edge layout, so content stopped short of the edges and did not align with the breadcrumb. The edge-to-edge layout now removes that cap, so the breadcrumb, title and body all span the full container (keeping only the small gutter so text never touches the raw screen edge).

= 2.5.4 =
* Polish for the "Full width (edge to edge)" layout: the content container now keeps its gutter padding, so the breadcrumb, title, body text and edit link all line up instead of the breadcrumb/edit link hugging the raw screen edge. Full-width (alignfull) blocks inside the content still break out to span the whole column.

= 2.5.3 =
* Fixed: the "Full width (edge to edge)" layout looked identical to "No sidebar" because only the inner column had its max-width removed while the content container kept its boxed max-width. The container is now released so full-width content truly spans the viewport, while normal (non-aligned) blocks inside it stay readable and full/wide-aligned blocks reach the edges. (Note: "Default" and "No sidebar" look the same when the sidebar has no widgets — that is expected; add widgets to the Right Sidebar to see the difference.)

= 2.5.2 =
* Fixed: the "Hide the content title" option in the Blank Base Layout meta box had no effect on static pages, because pages render through template-parts/content-page.php, which still output the title unconditionally. The page content part now respects the hide-title setting (and fires the before/after entry-content hooks) exactly like the post content part.

= 2.5.1 =
* Removed the "Full Width (No Sidebar)" page template. It overlapped with the new layout meta box (No sidebar / Full width), which now handles full-width and no-sidebar pages per-post without a conflicting second control. For a full-width page that keeps the header and footer, leave the template on Default and set the Blank Base Layout meta box to "No sidebar" or "Full width (edge to edge)". The "Blank Canvas (No Header/Footer)" template is unchanged. Demo content and documentation updated to match.

= 2.5.0 =
* Layout engine: right / left / both / no-sidebar / full-width layouts, selectable globally, per context (blog, single posts, pages, archives) and per post via a new "Blank Base Layout" meta box (which can also hide the content title). Added a second (left) sidebar widget area and a Content Container option (boxed or full width) with an adjustable sidebar width.
* Theme hook framework: 20+ documented action hooks placed throughout the templates (before/after header, navigation, content, main, right/left sidebars, footer and footer bar, plus entry-content and top-bar hooks) so child themes and plugins can inject markup without editing templates. See Appearance → Blank Base for the full list.
* Primary Navigation Customizer section: navigation location (inside header left/right/centered, or a full-width bar above or below the header), hover or click sub-menus, dropdown or off-canvas (slide-in) mobile menu, menu alignment, and per-element navigation colors.
* Footer section: configurable 0–5 footer widget columns and a dedicated footer bar (copyright + footer menu + social links) with its own layout, separate from the widget area.
* Color manager: per-element color controls for the header, navigation, content, buttons, footer widgets and footer bar — each inherits the base stylesheet until set.
* Typography manager: weight, line-height and text-transform for body and headings, per-heading H1–H6 sizes, site-title size, and full navigation typography (size, weight, transform).
* All new options are additive and backward compatible — existing settings and content are preserved.

= 1.9.1 =
* Added a design-token layer (font-size, weight, line-height, spacing, radius and motion scales) as CSS variables, and wired the base typography, links, buttons and form fields to it — consistent values, easy to retune in one place.
* Polish: semibold buttons with hover transitions, form-field focus rings, and a visible keyboard focus ring on all interactive elements (accessibility).

= 1.9.0 =
* Added a full typographic scale: explicit sizes and weights for h1–h6 (h1/h2 fluid via clamp), balanced headings, and styling for small, mark, sub/sup, abbr, dl/dt/dd, address, figcaption, ins/del and kbd. Mirrored in the editor.

= 1.8.2 =
* Responsive hardening: wide tables now scroll within their own container, long words/URLs wrap, and embeds/media stay within the content column on small screens (without breaking the sticky header/sidebar).

= 1.8.1 =
* Added a built-in reference page at Appearance → Blank Base (and a "Help" link on the Themes screen) documenting the animation classes, mega-menu class and interactive patterns — so nothing needs to be memorised.
* Added one-click "Animate: Slide from left/right" block styles.

= 1.8.0 =
* Added scroll-reveal animations you can apply to any block: one-click "Animate: Rise up / Fade in / Zoom in" styles on common blocks, or utility classes (bb-animate, bb-from-left, bb-zoom, bb-delay-2, …) via Advanced → Additional CSS class(es). Uses IntersectionObserver, respects prefers-reduced-motion, and never hides content when JavaScript is off.

= 1.7.1 =
* Animated stats and Skill bars are now edited directly in the block editor: the counter is a normal Heading block (type any value like "4.9/5" or "10k+") and each skill bar is a normal Paragraph (type e.g. "Design 92%"). The script reads the value from the text — no HTML editing needed.

= 1.7.0 =
* Added importable demo content (demo/blank-base-demo.xml) themed as an AI-tools directory: a full landing Home page, Blog, About and Contact pages, six AI-tool review posts across six categories, and a primary menu. Includes import instructions (demo/README.md) and a generator (tools/generate-demo.php).

= 1.6.0 =
* Added three interactive patterns (built with lightweight vanilla JS, no libraries): Animated stats counter, Skill bars, and a Testimonial slider.
* All animations respect prefers-reduced-motion and use IntersectionObserver.

= 1.5.0 =
* Reworked all block patterns to be production-ready: professional default copy, bundled SVG placeholder imagery (no empty images), consistent spacing, feature icons, and a highlighted "Most popular" pricing tier.
* Added a separate "Blank Base: Pages" pattern category for the full-page layouts.
* Added pattern helper styles (feature icons, check-mark pricing lists) to the front end and editor.

= 1.4.0 =
* Expanded the block pattern library to 19 patterns.
* New section patterns: Page header, Stats row, Logo cloud, Newsletter signup, Contact section, Latest posts (Query Loop), How it works (steps), Content + image.
* New full-page patterns shown when creating a Page: Landing, About, Contact.

= 1.3.0 =
* Added a bundled child theme (blank-base-child) and a docs/documentation.md guide.
* Added a print stylesheet for clean printed articles.
* Registered custom block styles: Image (Framed, Rounded), Button (Pill), Paragraph (Lead), Separator (Thick).
* Added Sticky Sidebar and Logo Max Height Customizer options.
* Regenerated the translation template (languages/blank-base.pot).

= 1.2.0 =
* Reading experience: blog layout options (list/grid/masonry), reading time, reading-progress bar, auto table of contents, related posts, author box, and social share buttons.
* Navigation: dismissible announcement bar, off-canvas mobile drawer, social-links menu output, and mega-menu support (add the "mega-menu" class to a menu item).
* Pages & onboarding: Blank Canvas page template, starter content for fresh installs, and new About, Pricing, FAQ and Team block patterns.

= 1.1.0 =
* Added a Typography section (body/heading font choices incl. Google Fonts, base font size).
* Added Colors & Dark Mode section: color presets, custom accent, and a light/dark toggle (with auto/OS mode and no-flash loading).
* Added Header section: header layouts (default, centered, minimal) and a sticky header option.
* Added content width, breadcrumbs and back-to-top controls to Theme Options.
* Added schema.org breadcrumbs, a back-to-top button, and new Hero, Features and Testimonial block patterns.

= 1.0.0 =
* Initial release.

== Credits ==

* Based on Underscores https://underscores.me/, (C) 2012-2024 Automattic, Inc.,
  [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)
* normalize.css https://necolas.github.io/normalize.css/, (C) 2012-2018 Nicolas
  Gallagher and Jonathan Neal, [MIT](https://opensource.org/licenses/MIT)
