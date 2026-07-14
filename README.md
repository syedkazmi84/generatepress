# Quill & Press — Book Publishing Website (GeneratePress)

A **complete, ready-to-import book-publishing-services website** built as a
GeneratePress child theme with a **one-click demo importer**. Activate the
theme, click **Import All**, and the entire website — every page, the blog,
menus, artwork and settings — is built for you in a few seconds.

This repository contains everything you need:

| File | Purpose |
|------|---------|
| `quillpress-child/` | The child theme source (styles, artwork, importer, content) |
| `quillpress-child.zip` | The child theme, ready to upload to WordPress |
| `generatepress.3.6.1.zip` | GeneratePress parent theme (required) |
| `generateblocks.2.3.0.zip` | GenerateBlocks plugin (recommended) |
| `generateblocks-pro-2.5.0.zip` | GenerateBlocks Pro (optional) |
| `gp-premium-2.5.6.zip` | GP Premium (optional) |
| `generatecloud-1.1.0.zip` | GenerateCloud (optional) |

## Quick start

1. **Install & activate GeneratePress** — Appearance → Themes → Add New →
   Upload → `generatepress.3.6.1.zip`.
2. **Install GenerateBlocks** — Plugins → Add New → Upload → `generateblocks.2.3.0.zip`, then activate.
3. **Upload & activate the child theme** — Appearance → Themes → Add New →
   Upload → `quillpress-child.zip`, then activate.
4. **Import the website** — Appearance → **Import Demo** → click **Import All Content**.
5. Visit your site. It's done.

## What gets built

Home · About · Services · Editing & Proofreading · Ghostwriting · Cover Design ·
Formatting & Typesetting · Book Marketing · Publishing & Distribution ·
Portfolio · Pricing · Testimonials · FAQ · Contact · Blog (with 3 posts) —
plus the header & footer menus, a static homepage, imported artwork, and
matching theme settings.

Every page is authored entirely with **GenerateBlocks blocks** (Container,
Grid, Headline, Button, Image) and styled by the child theme's `qp-`-prefixed
design system, so it looks polished immediately and stays fully editable in the
GenerateBlocks editor. GenerateBlocks must be active.

## Design

A refined, literary aesthetic: deep ink-navy, brass gold and parchment cream,
with a serif/sans type pairing (Fraunces + Inter). All illustrations, icons,
book covers and logos are custom SVGs bundled in
`quillpress-child/assets/images/`.

## Notes

- Re-running the importer refreshes the demo pages in place — it never
  duplicates content, and never touches your own posts or pages.
- Rename **Quill & Press** to your own brand (Settings → General, the footer in
  `functions.php`, and each page in the editor).
- The contact form is display-only; connect Contact Form 7 / WPForms / Fluent
  Forms to make it live.

See `quillpress-child/readme.txt` for full theme documentation.
