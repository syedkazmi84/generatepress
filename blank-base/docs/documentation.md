# Blank Base — Documentation

Blank Base is a clean, standards-compliant blank starter theme. It ships with
no hard-coded content — you add everything dynamically — but includes every
option a production theme needs.

- **Requires:** WordPress 6.0+, PHP 7.4+
- **Text domain:** `blank-base`
- **License:** GPLv2 or later

---

## 1. Installation

1. Go to **Appearance → Themes → Add New → Upload Theme**.
2. Upload `blank-base.zip` and click **Install Now**, then **Activate**.
3. On a fresh site, open **Appearance → Customize** — the theme's **starter
   content** (sample pages, menus, widgets and a static front page) appears in
   the preview. It is only saved if you click **Publish**.

For customizations, install the bundled **Blank Base Child** theme (see
`blank-base-child/readme.txt`) so your changes survive parent updates.

---

## 2. Customizer options

All options live in **Appearance → Customize**.

### Site Identity
- Logo (with a **Logo Max Height** control under *Header*), site title,
  tagline, site icon.

### Typography (typography manager)
- **Body Font** and **Heading Font** — system stacks plus Google Fonts
  (Inter, Poppins, Roboto, Montserrat, Playfair Display, Lora, Merriweather).
  Google Fonts are only requested when selected, and `preconnect` hints are
  added so the download starts a round-trip sooner.
  **Privacy note:** requesting a font from Google exposes visitor IP addresses
  to Google, which can be a GDPR concern in the EU. The default is a system
  font stack, which makes no third-party request; for a hosted look without the
  privacy tradeoff, self-host the font files instead.
- **Base Font Size** (12–24px) with live preview.
- **Body**: font weight and line-height.
- **Headings**: font weight, text-transform, line-height, and per-heading
  **H1–H6 sizes** (px; leave 0 to keep the fluid default).
- **Site Title** size and full **Navigation** typography (size, weight,
  transform).

### Colors (color manager)
- **Color Preset** — Classic Blue, Ocean, Forest, Sunset, Royal, Slate, or
  Custom (uses the accent color picker).
- **Per-element colors** — Content (background, text, link, link hover),
  Header (background, text, link, link hover, site title), Buttons
  (background/text and their hover states), Footer widgets and Footer bar.
  Each color inherits the base stylesheet until you set it.

### Header
- **Header Layout** — Branding left / Centered / Minimal.
- **Sticky Header**, **Announcement Bar Text** (dismissible bar),
  **Logo Max Height**.

### Primary Navigation
- **Navigation Location** — inside the header (left, right or centered) or a
  full-width bar above or below the header.
- **Menu Alignment** (for the full-width bars), **Sub-Menu Opens On**
  (hover or click), **Mobile Menu Style** (dropdown or off-canvas slide-in).
- **Navigation colors** — background, text, item-hover background/text and
  sub-menu background/text.

### Blog & Posts
- **Blog Layout** — List / Grid / Masonry.
- Toggles: Reading Time, Reading Progress Bar, Table of Contents,
  Related Posts, Author Box, Social Share.

### Footer
- **Footer Widget Columns** — 0 to 5.
- **Show Footer Bar** and **Footer Bar Layout** (copyright left + menu right,
  centered, or all left).

### Theme Options (layout)
- **Sidebar Position** — the global default: right / left / **both** / none /
  **full width**.
- **Content Container** — boxed (max width) or full width, plus an adjustable
  **Sidebar Width** (%).
- **Per-context sidebars** — separate layouts for the blog/posts page, single
  posts, static pages and archives/search (each can inherit the global
  default).
- Content Width, Sticky Sidebar, Breadcrumbs, Back-to-Top button, Footer Text,
  Accent Color.

Individual posts and pages can override the layout (and hide the content
title) from the **Blank Base Layout** meta box in the editor sidebar. The same
meta box has a **Breadcrumbs** control (*Default / Show / Hide*) that turns the
breadcrumb trail on or off for that single page or post, overriding the
site-wide *Show Breadcrumbs* Customizer option. Leave it on *Default* to follow
the Customizer setting.

---

## 3. Menus

Three menu locations (**Appearance → Menus**):

| Location | Purpose |
| --- | --- |
| Primary Menu | Main header navigation (supports dropdowns) |
| Footer Menu | Simple footer links |
| Social Links Menu | Rendered as pill links in the footer |

**Mega menu:** add the CSS class `mega-menu` to a top-level menu item (enable
*CSS Classes* via Screen Options in the Menus screen) to display its submenu
full-width in columns.

---

## 4. Widget areas

- **Right Sidebar** — shown as the right column for the *Right sidebar* layout,
  and as the right column of the *Both sidebars* layout.
- **Left Sidebar** — shown as the left column for the *Left sidebar* layout, and
  as the left column of the *Both sidebars* layout.

If the widget area for the selected layout has no widgets, the theme
automatically serves a full-width layout for that view instead of leaving an
empty sidebar gap.
- **Footer 1 … 5** — footer columns (the number displayed is set by
  *Customize → Footer → Footer Widget Columns*).

Add widgets in **Appearance → Widgets**.

---

## 5. Page templates

Set via the editor's **Page → Template** selector:

- **Default template** — the normal page, whose layout is controlled by the
  **Blank Base Layout** meta box and the Customizer.
- **Blank Canvas (No Header/Footer)** — bare content with no header, footer,
  sidebar or container; ideal for landing / coming-soon pages built in the
  block editor.

For a **full-width / no-sidebar** page that still keeps the header and footer,
leave the template on *Default* and set the **Blank Base Layout → Sidebar
Layout** meta box to *No sidebar* or *Full width (edge to edge)*. (A separate
"Full Width" page template was removed in 2.5.1 because the layout meta box now
covers it per-page without a conflicting second control.)

---

## 6. Block patterns

19 production-ready patterns with professional default copy and bundled
placeholder imagery — just insert and edit.

- **Blank Base** category (sections): Hero, Three features with icons,
  Stats row, Pricing (highlighted tier), Testimonial, Call to action, About,
  Content + image, FAQ, Team, Page header, Logo cloud, Newsletter, Contact,
  Latest posts (Query Loop), How it works (steps).
- **Interactive** (lightweight vanilla JS, respects reduced-motion):
  Animated stats counter, Skill bars, Testimonial slider. These are edited
  like any other block — the counter is a **Heading** (type e.g. `4.9/5`,
  `10k+`, `500+`) and each skill bar is a **Paragraph** (type e.g.
  `Design 92%`). The script reads the value from the text you type, so there
  is no HTML to edit and you can duplicate a block to add more.
- **Blank Base: Pages** category (full-page layouts, also offered in the
  pattern picker when you create a Page): Landing, About, Contact.

Interactive patterns are enhanced by `assets/js/theme.js` using
`IntersectionObserver` — no jQuery or third-party libraries.

Placeholder images live in `assets/images/` — replace them with your own.

## 7. Block styles

Extra variations in the block toolbar: Image → *Framed* / *Rounded*,
Button → *Pill*, Paragraph → *Lead*, Separator → *Thick*.

## 7a. Scroll animations (any block)

Reveal any block as it scrolls into view. Two ways:

1. **One click** — select a block, open the **Styles** panel and choose
   *Animate: Rise up*, *Animate: Fade in* or *Animate: Zoom in* (available on
   paragraphs, headings, images, groups, columns, buttons, cover, quote, list
   and media-text).
2. **Any block** — open **Advanced → Additional CSS class(es)** and add:
   - `bb-animate` — fade + rise (default)
   - direction: `bb-from-top`, `bb-from-left`, `bb-from-right`, `bb-zoom`,
     or `bb-fade` (fade only)
   - delay: `bb-delay-1`, `bb-delay-2`, `bb-delay-3`
   - e.g. `bb-animate bb-from-left bb-delay-2`

Animations honour *prefers-reduced-motion* and never hide content when
JavaScript is disabled (the hidden start state is scoped to `html.js`).

## 8. Translation

The theme is translation-ready (`blank-base` text domain). A template lives at
`languages/blank-base.pot`. After changing strings, regenerate it with
`wp i18n make-pot . languages/blank-base.pot`.

---

## 8a. Theme hooks (for child themes & plugins)

Blank Base fires action hooks throughout its templates so you can add markup
without editing template files. Attach to any of them with `add_action()`:

```php
add_action( 'blank_base_after_header', function () {
    echo '<div class="promo-bar">Free shipping this week!</div>';
} );
```

Available hooks:

| Hook | Location |
| --- | --- |
| `blank_base_top_bar` | Very top of the page, above the header |
| `blank_base_before_header` / `blank_base_after_header` | Around the site header |
| `blank_base_inside_header` | Inside the header actions |
| `blank_base_before_navigation` / `blank_base_after_navigation` | Around the primary menu |
| `blank_base_inside_navigation` | Inside the primary menu |
| `blank_base_before_content` / `blank_base_after_content` | Just inside `#content` |
| `blank_base_before_main` / `blank_base_after_main` | Top/bottom of the main column |
| `blank_base_before_right_sidebar` / `blank_base_after_right_sidebar` | Around the right sidebar |
| `blank_base_before_left_sidebar` / `blank_base_after_left_sidebar` | Around the left sidebar |
| `blank_base_before_entry_content` / `blank_base_after_entry_content` | Around the post/page content |
| `blank_base_before_footer` / `blank_base_after_footer` | Around the footer |
| `blank_base_inside_footer` | Inside the footer, before the footer bar |
| `blank_base_before_footer_bar` / `blank_base_footer_bar` / `blank_base_after_footer_bar` | Around/inside the footer bar |

The `blank_base_layout` filter lets you override the resolved layout
(`right-sidebar`, `left-sidebar`, `both-sidebars`, `no-sidebar`,
`full-width`) programmatically.

### Hook Elements (no code)

Prefer not to write PHP? Go to **Appearance → Elements** to attach content to
any of the hooks above without a child theme:

1. **Add New Element**, give it a title and build the content in the block
   editor (any blocks, shortcodes or HTML).
2. In the **Element Settings** box choose a **Hook location** (e.g. *After
   header*, *Before footer*), a **Priority**, and a **Display on** rule —
   *Entire site*, *Front page*, *Blog*, *All posts*, *All pages*, *All
   archives*, or *Specific IDs*. Optionally list **Exclude IDs**.
3. **Publish**. The element is injected into that hook everywhere the rule
   matches. Draft/pending elements are not shown.

This is the equivalent of GeneratePress's Hook Elements. The
`blank_base_element_display` filter lets you refine the matching logic in code.

**Adding scripts (analytics, verification tags, chat widgets):** choose the
**Site &lt;head&gt;** or **Site footer (before &lt;/body&gt;)** hook location,
then paste your `<script>`, `<style>` or `<meta>` into a **Custom HTML** block.
These two locations output the code raw — no wrapper element and no
auto-paragraphs — so it runs verbatim. (WordPress only saves raw `<script>`
tags for users who can use unfiltered HTML — administrators on a normal
single-site install. Combine with a display rule to scope a tag to, say, the
front page only.)

---

## 9. File map

```
blank-base/
├── *.php                    Template hierarchy (index, single, page, archive, …)
├── template-parts/          Reusable content partials
├── templates/               Custom page templates
├── inc/                     Setup, customizer, dynamic CSS, features, patterns
│   ├── hooks.php            Theme hook framework
│   ├── layout.php           Layout engine + per-post layout meta box
│   ├── css-output.php       Color / typography / layout CSS generators
│   ├── structure/           Navigation positioning + footer (widgets, footer bar)
│   └── customizer/fields/   Modular Customizer field groups
├── assets/js/               navigation, theme (back-to-top / reading progress / …), customizer
├── assets/css/              editor-style, print, gp-style
├── languages/               Translation template
└── theme.json               Block editor presets
```
