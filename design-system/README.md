# Book Publishing Services — GeneratePress Design System

A professional, editorial design system for a book-publishing-services website,
configured entirely through the **Appearance → Customize** interface of
GeneratePress (Global Colors, Font Manager, and a responsive Typography scale).

The concept is **ink + paper + antique gold** — a literary, trustworthy, premium
feel appropriate for editing, ghostwriting, cover design, formatting, and
publishing services.

---

## 1. Global Colors

Set under **Customize → Colors → Global Colors**. The first seven slugs are
GeneratePress core roles (they drive text, links, and backgrounds site-wide);
`accent-2` and `accent-3` are decorative brand colors exposed as `--accent-2`
and `--accent-3` and added to the block editor palette.

| Name          | Slug         | Hex       | Role                                   |
|---------------|--------------|-----------|----------------------------------------|
| Contrast      | `contrast`   | `#20262E` | Ink — body text & headings             |
| Contrast 2    | `contrast-2` | `#5B6470` | Muted slate — meta / secondary text    |
| Contrast 3    | `contrast-3` | `#D8D2C6` | Warm grey — borders / dividers         |
| Base          | `base`       | `#F1ECE2` | Cream — alternating section background |
| Base 2        | `base-2`     | `#FAF8F3` | Soft paper — page background           |
| Base 3        | `base-3`     | `#FFFFFF` | White — cards / content surfaces       |
| Accent        | `accent`     | `#1F3A5F` | Ink navy — links & primary buttons     |
| Accent 2 Gold | `accent-2`   | `#B0803A` | Antique gold — decorative accent       |
| Burgundy      | `accent-3`   | `#7A2E39` | Burgundy — secondary accent / hovers   |

**Contrast checks (WCAG):** body text `#20262E` on paper `#FAF8F3` ≈ 14:1;
navy links `#1F3A5F` on white ≈ 10:1; white on navy buttons ≈ 10:1 — all pass
AA/AAA for their use. Gold is used decoratively (large text, borders, hovers),
not for body copy.

### Core color roles
- Page background: `var(--base-2)`
- Body text: `var(--contrast)`
- Links: `var(--accent)` → hover `var(--accent-2)` (gold)
- Buttons: navy background, white text → hover burgundy `var(--accent-3)`
- Content links underline: on hover only

---

## 2. Fonts (Font Manager)

Set under **Customize → Typography → Font Manager**. Loaded from Google Fonts.

| Use      | Family                | Weights                              |
|----------|-----------------------|--------------------------------------|
| Headings | **Playfair Display** (serif) | 400, 500, 600, 700 + italics |
| Body/UI  | **Inter** (sans-serif)       | 300, 400, 500, 600, 700      |

Playfair Display is a high-contrast display serif — the quintessential editorial
/ publishing voice. Inter is a highly legible humanist sans for body copy and UI.
`font-display: swap` is set for performance.

---

## 3. Typography scale

Set under **Customize → Typography**. Responsive sizes are desktop / tablet
(≤1024px) / mobile (≤768px), in px.

| Element                | Font             | Weight | Size (D/T/M)   | Line height |
|------------------------|------------------|--------|----------------|-------------|
| Body                   | Inter            | 400    | 18 / 17 / 16   | 1.7         |
| All headings (base)    | Playfair Display | 700    | —              | 1.2         |
| H1                     | Playfair Display | 700    | 46 / 38 / 32   | 1.15        |
| H2                     | Playfair Display | 600    | 36 / 30 / 26   | 1.2         |
| H3                     | Playfair Display | 600    | 27 / 24 / 22   | 1.3         |
| H4                     | Inter            | 600    | 21 / – / 19    | 1.4         |
| Single content title   | Playfair Display | 700    | 44 / 36 / 30   | 1.15        |
| Primary menu items     | Inter            | 500    | 16             | —           |
| Buttons                | Inter            | 600    | 16             | —           |
| Widget titles          | Playfair Display | 600    | 20             | 1.3         |
| Site description       | Inter            | 400    | 15             | —           |

Paragraph spacing: `1.5em`. Container width: `1200px`. Content layout:
separate containers (card-style sections).

---

## 4. Applying this to a site

These settings live in the WordPress option `generate_settings`.

### Option A — WP-CLI (recommended)
```bash
wp option update generate_settings "$(cat design-system/generate_settings.json)" --format=json
# clear GeneratePress dynamic CSS cache so it regenerates
wp option delete generate_dynamic_css_output generate_dynamic_css_cached_version
```

### Option B — PHP script
Run `apply-design.php` (rebuilds the same settings from source, well-commented):
```bash
wp eval-file design-system/apply-design.php
```

### Option C — By hand
Recreate the tables above in **Appearance → Customize** (Colors → Global Colors,
Typography → Font Manager, Typography). This is what the Customizer produces.

> After importing, open the Customizer once and click **Publish** to ensure the
> theme regenerates its cached CSS.

---

## Files
- `generate_settings.json` — exported GeneratePress settings (colors + fonts + typography).
- `apply-design.php` — commented script that builds the same settings from scratch.
- `README.md` — this document.
