#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Generate a complete professional AuthorWings homepage as GenerateBlocks 2.x
markup (generateblocks/element + generateblocks/text) for GeneratePress.

Each block gets: uniqueId, a `styles` object (camelCase — editor-friendly) and
a compiled `css` string (drives the frontend). Selectors follow GB conventions:
  element -> .gb-element-{id}   text -> .gb-text-{id}

Output: post_content HTML written to homepage.html
"""
import json, re, sys

_counter = [1000]
def uid():
    _counter[0] += 1
    return "aw%05d" % _counter[0]

def kebab(p):
    return re.sub(r'([A-Z])', r'-\1', p).lower()

def esc_html(s):
    return s.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;')

def _decls(d):
    return ';'.join('%s:%s' % (kebab(k), v) for k, v in d.items()
                    if not isinstance(v, dict))

def compile_css(selector, styles):
    """Compile a styles dict (with optional nested selector / @media keys) to CSS."""
    base = {k: v for k, v in styles.items() if not isinstance(v, dict)}
    css = ''
    if base:
        css += '%s{%s}' % (selector, _decls(base))
    for k, v in styles.items():
        if not isinstance(v, dict):
            continue
        if k.startswith('@'):                       # at-rule (media query)
            css += '%s{%s{%s}}' % (k, selector, _decls(v))
            # allow nested selectors inside media too
            for k2, v2 in v.items():
                if isinstance(v2, dict):
                    sub = (k2.replace('&', selector) if '&' in k2 else selector + ' ' + k2)
                    css += '%s{%s}' % (k, compile_css(sub, v2))
        else:                                        # combinator / pseudo / child
            sub = k.replace('&', selector) if '&' in k else selector + ' ' + k
            css += compile_css(sub, v)
    return css

def block(name, tag, styles, inner='', extra_attrs=None, base_class=None):
    _id = uid()
    slug = name.split('/')[1]                        # element | text
    cls = base_class or ('gb-%s' % slug)
    selector = '.gb-%s-%s' % (slug, _id)
    attrs = {"uniqueId": _id, "tagName": tag}
    if styles:
        attrs["styles"] = styles
        attrs["css"] = compile_css(selector, styles)
    if extra_attrs:
        attrs.update(extra_attrs)
    j = json.dumps(attrs, separators=(',', ':'), ensure_ascii=False)
    full_cls = '%s gb-%s-%s' % (cls, slug, _id)
    # Render htmlAttributes (href, id, ...) directly into the saved tag, since
    # for pre-serialized content GB returns the markup as-is on the frontend.
    html_attr = ''
    for k, v in (attrs.get("htmlAttributes") or {}).items():
        html_attr += ' %s="%s"' % (k, str(v).replace('"', '&quot;'))
    open_c = '<!-- wp:%s %s -->' % (name, j)
    close_c = '<!-- /wp:%s -->' % name
    el = '<%s class="%s"%s>%s</%s>' % (tag, full_cls, html_attr, inner, tag)
    return '%s\n%s\n%s' % (open_c, el, close_c)

def element(tag, styles, children, **kw):
    return block('generateblocks/element', tag, styles, ''.join(children), **kw)

def text(tag, content, styles, **kw):
    return block('generateblocks/text', tag, styles, content, **kw)

# ---------------------------------------------------------------- design tokens
INK      = 'var(--contrast)'      # #20262E
MUTED    = 'var(--contrast-2)'    # #5B6470
LINE     = 'var(--contrast-3)'    # #D8D2C6
CREAM    = 'var(--base)'          # #F1ECE2
PAPER    = 'var(--base-2)'        # #FAF8F3
WHITE    = 'var(--base-3)'        # #FFFFFF
NAVY     = 'var(--accent)'        # #1F3A5F
GOLD     = 'var(--accent-2)'      # #B0803A
BURG     = 'var(--accent-3)'      # #7A2E39
SERIF    = "'Playfair Display', serif"
SANS     = "'Inter', sans-serif"

M_TAB = '@media (max-width:1024px)'
M_MOB = '@media (max-width:767px)'

def section(bg, children, pad_top=96, pad_bot=96, anchor=None):
    st = {"backgroundColor": bg, "paddingTop": "%dpx" % pad_top,
          "paddingBottom": "%dpx" % pad_bot, "paddingRight": "24px",
          "paddingLeft": "24px",
          M_MOB: {"paddingTop": "56px", "paddingBottom": "56px"}}
    kw = {}
    if anchor:
        kw["extra_attrs"] = {"htmlAttributes": {"id": anchor}}
    return element('section', st, children, **kw)

def container(children, width='1200px', styles=None):
    st = {"maxWidth": width, "marginRight": "auto", "marginLeft": "auto", "width": "100%"}
    if styles:
        st.update(styles)
    return element('div', st, children)

def eyebrow(txt, color=GOLD):
    return text('p', '— ' + esc_html(txt), {
        "fontFamily": SANS, "fontSize": "14px", "fontWeight": "600",
        "letterSpacing": "2px", "textTransform": "uppercase", "color": color,
        "marginTop": "0", "marginBottom": "18px"})

def heading(txt, color=INK, size='38px', mob='28px', tag='h2', mt='0', mb='20px', maxw=None):
    st = {"fontFamily": SERIF, "fontSize": size, "fontWeight": "700",
          "lineHeight": "1.18", "color": color, "marginTop": mt, "marginBottom": mb,
          "letterSpacing": "-0.3px", M_MOB: {"fontSize": mob}}
    if maxw:
        st["maxWidth"] = maxw
    return text(tag, esc_html(txt), st)

def lede(txt, color=MUTED, maxw='760px', center=False, mb='0'):
    st = {"fontFamily": SANS, "fontSize": "18px", "lineHeight": "1.75",
          "color": color, "maxWidth": maxw, "marginTop": "0", "marginBottom": mb}
    if center:
        st["marginRight"] = "auto"; st["marginLeft"] = "auto"; st["textAlign"] = "center"
    return text('p', esc_html(txt), st)

def para(txt, color=MUTED, size='16px', mb='16px', maxw=None):
    st = {"fontFamily": SANS, "fontSize": size, "lineHeight": "1.75",
          "color": color, "marginTop": "0", "marginBottom": mb}
    if maxw:
        st["maxWidth"] = maxw
    return text('p', esc_html(txt), st)

def button(label, primary=True, href='#'):
    if primary:
        st = {"display": "inline-flex", "alignItems": "center", "gap": "8px",
              "backgroundColor": GOLD, "color": "#20262E", "fontFamily": SANS,
              "fontSize": "16px", "fontWeight": "600", "letterSpacing": "0.2px",
              "paddingTop": "15px", "paddingBottom": "15px", "paddingLeft": "30px",
              "paddingRight": "30px", "borderTopLeftRadius": "6px",
              "borderTopRightRadius": "6px", "borderBottomLeftRadius": "6px",
              "borderBottomRightRadius": "6px", "textDecoration": "none",
              "transition": "transform .15s ease, background-color .15s ease",
              "&:hover": {"backgroundColor": "#c79646", "color": "#20262E",
                          "transform": "translateY(-2px)"}}
    else:
        st = {"display": "inline-flex", "alignItems": "center", "gap": "8px",
              "backgroundColor": "transparent", "color": "#ffffff", "fontFamily": SANS,
              "fontSize": "16px", "fontWeight": "600", "letterSpacing": "0.2px",
              "paddingTop": "14px", "paddingBottom": "14px", "paddingLeft": "28px",
              "paddingRight": "28px", "borderTopLeftRadius": "6px",
              "borderTopRightRadius": "6px", "borderBottomLeftRadius": "6px",
              "borderBottomRightRadius": "6px", "borderTopWidth": "1.5px",
              "borderRightWidth": "1.5px", "borderBottomWidth": "1.5px",
              "borderLeftWidth": "1.5px", "borderStyle": "solid",
              "borderColor": "rgba(255,255,255,0.35)", "textDecoration": "none",
              "transition": "background-color .15s ease, border-color .15s ease",
              "&:hover": {"backgroundColor": "rgba(255,255,255,0.08)",
                          "borderColor": "rgba(255,255,255,0.7)", "color": "#ffffff"}}
    return text('a', esc_html(label), st,
                extra_attrs={"htmlAttributes": {"href": href}})

def link_more(label='Learn more →', color=GOLD, href='#'):
    return text('a', esc_html(label), {
        "fontFamily": SANS, "fontSize": "15px", "fontWeight": "600",
        "color": color, "textDecoration": "none", "display": "inline-block",
        "&:hover": {"textDecoration": "underline", "color": color}},
        extra_attrs={"htmlAttributes": {"href": href}})

def grid(children, cols=3, gap='28px', tab=2, mob=1, styles=None):
    st = {"display": "grid",
          "gridTemplateColumns": "repeat(%d,minmax(0,1fr))" % cols, "gap": gap,
          M_TAB: {"gridTemplateColumns": "repeat(%d,minmax(0,1fr))" % tab},
          M_MOB: {"gridTemplateColumns": "repeat(%d,minmax(0,1fr))" % mob}}
    if styles:
        st.update(styles)
    return element('div', st, children)

def head_block(eb, title, intro=None, color=INK, eb_color=GOLD, center=False,
               title_size='38px', title_mob='28px', intro_color=MUTED, mb='48px'):
    kids = [eyebrow(eb, eb_color),
            heading(title, color=color, size=title_size, mob=title_mob,
                    maxw=('820px' if not center else '820px'))]
    if intro:
        kids.append(lede(intro, color=intro_color, center=center, mb='0'))
    wrap = {"marginBottom": mb}
    if center:
        wrap["textAlign"] = "center"
        # center children
        kids = [eyebrow(eb, eb_color)]
        h = heading(title, color=color, size=title_size, mob=title_mob)
        kids.append(h)
        if intro:
            kids.append(lede(intro, color=intro_color, center=True))
    return element('div', wrap, kids)

# ================================================================= SECTIONS
S = []

# ---- 1. HERO ----------------------------------------------------------------
hero_stats = []
for num, lab in [("100%", "Royalties Kept"), ("Full-Service", "Book Publishing"),
                 ("Flat Fee", "One Transparent Price"), ("5+", "Years of Experience")]:
    hero_stats.append(element('div', {
        "backgroundColor": "rgba(255,255,255,0.04)",
        "borderTopWidth": "1px", "borderRightWidth": "1px", "borderBottomWidth": "1px",
        "borderLeftWidth": "1px", "borderStyle": "solid",
        "borderColor": "rgba(255,255,255,0.12)", "borderTopLeftRadius": "10px",
        "borderTopRightRadius": "10px", "borderBottomLeftRadius": "10px",
        "borderBottomRightRadius": "10px", "paddingTop": "26px", "paddingBottom": "26px",
        "paddingLeft": "24px", "paddingRight": "24px", "textAlign": "center"}, [
        text('div', esc_html(num), {"fontFamily": SERIF, "fontSize": "34px",
             "fontWeight": "700", "color": GOLD, "lineHeight": "1.1",
             "marginBottom": "6px", M_MOB: {"fontSize": "28px"}}),
        text('div', esc_html(lab), {"fontFamily": SANS, "fontSize": "14px",
             "fontWeight": "500", "letterSpacing": "0.4px", "color": "#C9CCD1"})]))

hero = section(INK, [container([
    element('div', {"maxWidth": "860px"}, [
        text('p', '❦  New York registered · Fee-for-service publishing', {
            "fontFamily": SANS, "fontSize": "14px", "fontWeight": "600",
            "letterSpacing": "1.5px", "textTransform": "uppercase", "color": GOLD,
            "marginTop": "0", "marginBottom": "22px"}),
        text('h1', 'Book Publishing Services for Authors Who Keep 100% of Their Rights', {
            "fontFamily": SERIF, "fontSize": "56px", "fontWeight": "700",
            "lineHeight": "1.1", "letterSpacing": "-0.5px", "color": "#ffffff",
            "marginTop": "0", "marginBottom": "26px",
            M_TAB: {"fontSize": "44px"}, M_MOB: {"fontSize": "34px"}}),
        text('p', esc_html('Publishing companies take your royalties for life. Vanity presses own your ISBN. DIY paths take six months. AuthorWings does none of that. Full-service book publishing on a flat fee, with every Amazon, Apple, Kobo, and IngramSpark account set up in your name. Live on every major retailer in as little as 30 days, 100% of royalties yours.'), {
            "fontFamily": SANS, "fontSize": "19px", "lineHeight": "1.7",
            "color": "#C9CCD1", "maxWidth": "720px", "marginTop": "0",
            "marginBottom": "34px", M_MOB: {"fontSize": "17px"}})]),
    element('div', {"display": "flex", "flexWrap": "wrap", "gap": "16px",
                    "marginBottom": "56px"}, [
        button('See What We Offer  ↓', primary=True, href='#what-we-offer'),
        button('Run the Cost Calculator  →', primary=False, href='#next-steps')]),
    grid(hero_stats, cols=4, gap='18px', tab=2, mob=2)])],
    pad_top=104, pad_bot=100)
S.append(hero)

# ---- 2. WHO WE ARE ----------------------------------------------------------
who = section(PAPER, [container([
    element('div', {"display": "grid", "gridTemplateColumns": "0.9fr 1.1fr",
                    "gap": "56px", "alignItems": "start",
                    M_TAB: {"gridTemplateColumns": "1fr", "gap": "28px"}}, [
        element('div', {}, [
            eyebrow('Who We Are'),
            heading('One publishing partner instead of five freelancers you manage',
                    size='36px', mob='27px', mb='0', maxw='460px')]),
        element('div', {}, [
            para('Hiring separate freelancers for editing, design, formatting, and marketing costs you three months and twice the budget. The timelines never align. The rates never match. You become the project manager for a team that has never worked together.', size='18px', mb='20px'),
            para('AuthorWings handles every publishing service in one place. One project manager. One invoice. The tier defines the scope, and the price is on the page before you ask.', size='18px', mb='20px'),
            para('AuthorWings LLC is a New York registered company with a team that has 5+ years of experience and hundreds of projects delivered across fiction, non-fiction, memoir, and children’s books. The pricing stays competitive because the talent is global, not because the scope gets cut. You keep creative control; AuthorWings handles the rest.', size='18px', mb='0')])])])])
S.append(who)

# ---- 3. WHAT WE OFFER -------------------------------------------------------
services = [
    ("01", "Ghostwriting", "A professional writer turns your concept, transcripts, or rough draft into a finished manuscript in your voice. Memoir, non-fiction, fiction, and children’s books. NDAs standard. You hold the byline, the copyright, and the royalties.", "From $5,495"),
    ("02", "Book Editing", "Developmental, line, copy, and proof editing on a per-service or bundled basis. Pricing transparent on the page. EFA rate-aligned. Sample edit free before commitment.", "From $299"),
    ("03", "Book Design", "Cover design and interior formatting for ebook and print. Genre-correct visual language, KDP and IngramSpark file specs, fixed-layout options for children’s and illustrated books.", "From $199"),
    ("04", "Book Publishing", "Distribution setup across Amazon KDP, IngramSpark, Apple Books, Kobo, and Google Play. ISBN registration, metadata optimization, pricing strategy, and copyright registration guidance. Every account in your name.", "From $249"),
    ("05", "Book Marketing", "Pre-launch positioning, ARC distribution coordination, Amazon Ads strategy, and ongoing visibility campaigns. Optional premium services for podcast booking, PR, and book fair exhibition.", "From $1,495"),
    ("06", "Book Coaching", "One-on-one work for authors writing the book themselves. Manuscript review, structural feedback, accountability, and craft coaching. Designed for writers who want the book to stay theirs.", "From $549"),
]
svc_cards = []
for num, title, desc, price in services:
    svc_cards.append(element('div', {
        "backgroundColor": PAPER, "borderTopWidth": "1px", "borderRightWidth": "1px",
        "borderBottomWidth": "1px", "borderLeftWidth": "1px", "borderStyle": "solid",
        "borderColor": LINE, "borderTopLeftRadius": "12px", "borderTopRightRadius": "12px",
        "borderBottomLeftRadius": "12px", "borderBottomRightRadius": "12px",
        "paddingTop": "34px", "paddingBottom": "34px", "paddingLeft": "32px",
        "paddingRight": "32px", "display": "flex", "flexDirection": "column",
        "transition": "border-color .15s ease, box-shadow .15s ease",
        "&:hover": {"borderColor": GOLD, "boxShadow": "0 14px 34px rgba(32,38,46,0.09)"}}, [
        text('div', num + ' ·', {"fontFamily": SERIF, "fontSize": "22px",
             "fontWeight": "700", "color": GOLD, "marginBottom": "12px"}),
        heading(title, size='24px', mob='22px', tag='h3', mb='12px'),
        para(desc, size='15.5px', mb='22px'),
        element('div', {"marginTop": "auto"}, [
            text('div', esc_html(price), {"fontFamily": SANS, "fontSize": "18px",
                 "fontWeight": "700", "color": NAVY, "marginBottom": "10px"}),
            link_more('Learn more →')])]))

offer = section(WHITE, [container([
        head_block('What We Offer', 'Six author services from idea to launch day',
                   'A finished book on Amazon is the result of six different jobs, not one. Writing the manuscript. Editing it until it stops sounding like a draft. Designing a cover that does not announce “self-published.” Formatting interiors for ebook and print. Setting up distribution. And running a launch that gets the book in front of actual readers. AuthorWings runs all six as a single contract, or any one on its own.'),
        grid(svc_cards, cols=3, gap='26px', tab=2, mob=1)])],
    anchor='what-we-offer')
S.append(offer)

# ---- 4. CATEGORIES ----------------------------------------------------------
cats = [
    ("Fiction Publishing", "Novels across thriller, romance, fantasy, mystery, science fiction, literary, and historical fiction. Cover design follows genre conventions readers actually scan for. Metadata optimization targets the BISAC codes that matter for visibility. Series setup for multi-book launches."),
    ("Non-Fiction Publishing", "Business, self-help, memoir, biography, history, and how-to. Authority-positioning matters more than fiction conventions. Subtitle architecture, back-cover copy, and Amazon A+ Content built to convert browsers into buyers. Print-on-demand and audiobook setup standard."),
    ("Children’s Publishing", "Picture books, chapter books, middle grade, and young adult. Fixed-layout formatting for illustrated books. Age-band metadata. Reading-level placement. Print specs that survive a four-year-old. Children’s book publishing is its own category, and it is priced as one."),
    ("Specialty Publishing", "Cookbooks, photo books, poetry, art books, religious texts, academic, and technical. Custom trim sizes, color interior printing, layout-heavy designs, and distribution setup that handles non-standard formats. Specialty pricing depends on production complexity."),
]
cat_cards = []
for title, desc in cats:
    cat_cards.append(element('div', {
        "backgroundColor": WHITE, "borderTopLeftRadius": "12px",
        "borderTopRightRadius": "12px", "borderBottomLeftRadius": "12px",
        "borderBottomRightRadius": "12px", "paddingTop": "32px", "paddingBottom": "32px",
        "paddingLeft": "32px", "paddingRight": "32px",
        "borderTopWidth": "1px", "borderTopStyle": "solid", "borderTopColor": LINE,
        "borderRightWidth": "1px", "borderRightStyle": "solid", "borderRightColor": LINE,
        "borderBottomWidth": "1px", "borderBottomStyle": "solid", "borderBottomColor": LINE,
        "borderLeftWidth": "4px", "borderLeftStyle": "solid", "borderLeftColor": GOLD,
        "display": "flex", "flexDirection": "column"}, [
        heading(title, size='23px', mob='21px', tag='h3', mb='12px'),
        para(desc, size='15.5px', mb='20px'),
        element('div', {"marginTop": "auto"}, [link_more('Learn more →')])]))

categories = section(CREAM, [container([
    head_block('Categories We Cover', 'Why genre changes how a book gets published',
               'A thriller and a children’s picture book do not share a single production decision. Different trim sizes. Different cover conventions. Different metadata categories on Amazon. Different reader expectations the moment the book opens. Publishing services that ignore genre produce books that sit in the wrong category and never find their audience.'),
    grid(cat_cards, cols=2, gap='24px', tab=2, mob=1)])])
S.append(categories)

# ---- 5. FREE TOOLS ----------------------------------------------------------
tools = [
    ("Title Generator", "Generate dozens of working title options across genre, tone, and theme inputs. Useful when the manuscript is finished but the title still feels close-but-not-right."),
    ("Description Generator", "Drafts an Amazon-ready book description from genre, premise, and tone inputs. Outputs the back-cover-style copy that converts browsers into buyers. Useful at launch and for repositioning a slow-selling backlist title."),
    ("Hook Generator", "Produces one-line book hooks under 16 words across five hook styles. Outputs the line that opens an Amazon description, a Facebook ad, and a TikTok caption all at once. Useful when ad click-through is flat and the cover is not the problem."),
    ("Character Name Generator", "Returns ten character names per run with etymology, cultural origin, and archetype fit. Filters by genre, era, and personality traits. Useful when the protagonist has been called Sarah as a placeholder for forty thousand words."),
    ("Bio Generator", "Drafts a professional author bio in three lengths (short, medium, full) from career, credentials, and tone inputs. The bio that goes on the back cover, the Amazon Author Page, and every podcast booking form."),
    ("Pen Name Generator", "Generates pen names with cultural authenticity, genre fit, and era feel. Comes with the legal mechanics most pen name guides skip: KDP setup, copyright filing, and tax reporting. Useful before launching a second pen name across genres."),
]
tool_cards = []
for title, desc in tools:
    tool_cards.append(element('div', {
        "backgroundColor": PAPER, "borderTopLeftRadius": "12px",
        "borderTopRightRadius": "12px", "borderBottomLeftRadius": "12px",
        "borderBottomRightRadius": "12px", "paddingTop": "30px", "paddingBottom": "30px",
        "paddingLeft": "30px", "paddingRight": "30px", "borderTopWidth": "1px",
        "borderRightWidth": "1px", "borderBottomWidth": "1px", "borderLeftWidth": "1px",
        "borderStyle": "solid", "borderColor": LINE, "display": "flex",
        "flexDirection": "column",
        "&:hover": {"borderColor": NAVY}}, [
        heading(title, size='21px', mob='20px', tag='h3', mb='10px'),
        para(desc, size='15px', mb='20px'),
        element('div', {"marginTop": "auto"}, [link_more('Try Now →', color=NAVY)])]))

free_tools = section(WHITE, [container([
    head_block('Free Tools', 'Six free author tools you can use right now',
               'Most author tools online sit behind an email gate, a free trial, or a “verify your account” loop. AuthorWings tools do not. Each one runs in the browser, returns results in seconds, and asks for nothing in return. Useful the moment a finished manuscript still needs a sharper title, or when a sleepy Amazon listing needs a better description hours before launch.'),
    grid(tool_cards, cols=3, gap='24px', tab=2, mob=1)])])
S.append(free_tools)

# ---- 6. WHY AUTHORWINGS (dark) ---------------------------------------------
def promise(title, paras):
    kids = [heading(title, color=GOLD, size='26px', mob='23px', tag='h3', mb='16px')]
    for i, p in enumerate(paras):
        kids.append(para(p, color="#C9CCD1", size='16px',
                         mb=('0' if i == len(paras)-1 else '16px')))
    return element('div', {
        "backgroundColor": "rgba(255,255,255,0.03)", "borderTopWidth": "1px",
        "borderRightWidth": "1px", "borderBottomWidth": "1px", "borderLeftWidth": "1px",
        "borderStyle": "solid", "borderColor": "rgba(255,255,255,0.12)",
        "borderTopLeftRadius": "12px", "borderTopRightRadius": "12px",
        "borderBottomLeftRadius": "12px", "borderBottomRightRadius": "12px",
        "paddingTop": "40px", "paddingBottom": "40px", "paddingLeft": "38px",
        "paddingRight": "38px"}, kids)

why = section(INK, [container([
    head_block('Why AuthorWings', 'Two promises behind every book publishing project',
               'Most publishing companies bury the things that matter to authors behind a “request a quote” form. Pricing. Royalty splits. Rights ownership. Account registration. AuthorWings runs the opposite model. Two promises sit at the front of every full-service project, and both are written into the contract before a dollar changes hands.',
               color="#ffffff", intro_color="#C9CCD1"),
    grid([
        promise('Transparent tiered pricing on every page', [
            'Every service page on this site shows tier prices in plain numbers. Ghostwriting from $5,495. Editing from $299. Publishing from $249. Cover design from $199. The price is the price. There is no “starting at” trick that becomes triple after the discovery call. There is no “custom quote” that arrives priced for the customer’s apparent budget rather than the work itself.',
            'The reason the rest of the industry hides pricing is that hybrid contracts are reverse-engineered to the buyer. AuthorWings does not run that model. The bundle, the tier, and the scope determine the price. Not the customer’s perceived ability to pay.']),
        promise('100% of rights, royalties, and accounts retained', [
            'Every distribution account is opened in the author’s own name and email. Amazon KDP. IngramSpark. Apple Books. Kobo. Google Play. The ISBN belongs to the author. The copyright belongs to the author. The royalties pay out directly from each retailer to the author’s bank account, not through AuthorWings.',
            'This is the part hybrid publishers refuse to write into a contract. They take 30% to 50% of net royalties for the life of the work, register the ISBN to themselves, and hold the distribution accounts under company emails. AuthorWings is built on the opposite premise: the author owns everything.']),
    ], cols=2, gap='28px', tab=1, mob=1)])])
S.append(why)

# ---- 7. INDUSTRY DATA -------------------------------------------------------
data = [
    ("On Hybrid Contracts", "The Authors Guild",
     "The Authors Guild, the oldest professional organization for writers in the United States, runs a Fair Contract Initiative that advocates for contract transparency, royalty fairness, and limited-term agreements. Their guidance focuses on the contract terms most often blurred in hybrid and rights-share arrangements.",
     "Authors Guild Fair Contract Initiative"),
    ("On Indie Publishing Volume", "Bowker · 2025 data",
     "Bowker, the official US ISBN agency, registered 4.2 million US titles in 2025, up 32.5% over 2024. Of those, 3.5 million were self-published, a 38.7% jump. Indie publishing is no longer a niche route. It is the dominant path for new books in the United States.",
     "Publishers Weekly on Bowker 2025 data"),
    ("On Industry Editing Rates", "Editorial Freelancers Association",
     "The Editorial Freelancers Association publishes a public rates chart used by professional editors across the industry. AuthorWings editing prices align with EFA-documented rates rather than the inflated prices that hybrid publishers fold into bundled contracts. The rates are public, and so is the math.",
     "EFA rate chart"),
]
data_cards = []
for tag_, src, body, link in data:
    data_cards.append(element('div', {
        "backgroundColor": WHITE, "borderTopWidth": "1px", "borderRightWidth": "1px",
        "borderBottomWidth": "1px", "borderLeftWidth": "1px", "borderStyle": "solid",
        "borderColor": LINE, "borderTopLeftRadius": "12px", "borderTopRightRadius": "12px",
        "borderBottomLeftRadius": "12px", "borderBottomRightRadius": "12px",
        "paddingTop": "34px", "paddingBottom": "34px", "paddingLeft": "32px",
        "paddingRight": "32px", "display": "flex", "flexDirection": "column"}, [
        text('div', esc_html(tag_), {"fontFamily": SANS, "fontSize": "13px",
             "fontWeight": "700", "letterSpacing": "1px", "textTransform": "uppercase",
             "color": GOLD, "marginBottom": "10px"}),
        heading(src, size='22px', mob='20px', tag='h3', mb='14px'),
        para(body, size='15.5px', mb='20px'),
        element('div', {"marginTop": "auto"}, [link_more(link + ' →', color=NAVY)])]))

industry = section(PAPER, [container([
    head_block('The Industry Data', 'What industry data says about book publishing services',
               'The case for full-service book publishing on a fee-for-service model is not an AuthorWings opinion. It sits in the data published by industry bodies that have no commercial interest in selling author services. Three sources matter most.'),
    grid(data_cards, cols=3, gap='24px', tab=1, mob=1)])])
S.append(industry)

# ---- 8. PUBLISHING NETWORK --------------------------------------------------
platforms = ["Amazon KDP", "IngramSpark", "Apple Books", "Kobo", "Google Play",
             "Barnes & Noble", "Goodreads", "BookBub", "Draft2Digital", "Audible"]
pills = []
for p in platforms:
    pills.append(text('span', esc_html(p), {
        "fontFamily": SANS, "fontSize": "15px", "fontWeight": "600", "color": INK,
        "backgroundColor": WHITE, "borderTopWidth": "1px", "borderRightWidth": "1px",
        "borderBottomWidth": "1px", "borderLeftWidth": "1px", "borderStyle": "solid",
        "borderColor": LINE, "borderTopLeftRadius": "40px", "borderTopRightRadius": "40px",
        "borderBottomLeftRadius": "40px", "borderBottomRightRadius": "40px",
        "paddingTop": "11px", "paddingBottom": "11px", "paddingLeft": "22px",
        "paddingRight": "22px", "display": "inline-block"}))
network = section(CREAM, [container([
    element('div', {"textAlign": "center", "marginBottom": "40px"}, [
        eyebrow('Our Publishing Network'),
        heading('Your book, connected everywhere that matters', size='36px', mob='27px'),
        lede('From publishing and distribution to discovery and reviews, your book is supported across trusted platforms that help you reach readers, build visibility, and establish credibility.', center=True)]),
    element('div', {"display": "flex", "flexWrap": "wrap", "gap": "14px",
                    "justifyContent": "center"}, pills)],
    width='980px')])
S.append(network)

# ---- 9. FAQ (native <details> accordion via core block) ---------------------
faqs = [
    ("How much do AuthorWings book publishing services cost?",
     "Every service is priced on the page in plain numbers: Ghostwriting from $5,495, Editing from $299, Book Design from $199, Publishing from $249, Marketing from $1,495, and Coaching from $549. The tier and scope set the price — not your apparent budget — and you can run the cost calculator for an instant, tier-by-tier estimate with no card and no email."),
    ("Is AuthorWings a vanity press or a self-publishing service?",
     "Neither. A vanity press takes your rights and registers the ISBN to itself; AuthorWings is a fee-for-service partner. You pay a flat fee for the work, and you keep 100% of your rights, royalties, and distribution accounts."),
    ("Will my book be available everywhere, or locked to Amazon?",
     "Everywhere. Distribution is set up across Amazon KDP, IngramSpark, Apple Books, Kobo, and Google Play, with print-on-demand and audiobook options — never locked to a single retailer."),
    ("Who actually owns the rights to my book after the project ends?",
     "You do. The copyright and the ISBN belong to the author, and that ownership is written into the contract before any money changes hands."),
    ("Whose name is on the Amazon, IngramSpark, and Apple Books accounts?",
     "Yours. Every distribution account is opened in the author’s own name and email, and royalties pay out directly from each retailer to your bank account — not through AuthorWings."),
    ("How do payment terms work for larger projects?",
     "Larger engagements such as ghostwriting are structured in milestone-based installments tied to delivery, so payment tracks the work completed. Exact terms are agreed in writing before the project begins."),
    ("What is the difference between AuthorWings and a hybrid publisher?",
     "A hybrid publisher typically takes 30% to 50% of net royalties for the life of the work, registers the ISBN to itself, and holds accounts under company emails. AuthorWings charges a flat fee once and leaves all rights, royalties, and accounts with you."),
    ("Do you serve authors outside the United States?",
     "Yes. AuthorWings works with authors worldwide and sets up distribution accounts in the author’s name regardless of country, with guidance on retailer tax and payment requirements."),
    ("How long does a full publishing project actually take?",
     "A book can be live on every major retailer in as little as 30 days once production is complete. Timelines that also include ghostwriting or developmental editing are scoped up front so you know the schedule before you commit."),
    ("What if I already have part of a manuscript or some of the production work done?",
     "Any single service can be booked on its own. If your manuscript, cover, or formatting is already partway done, AuthorWings picks up only the pieces you need rather than charging for a full bundle."),
    ("What makes AuthorWings different from any other book publishing company?",
     "Two promises written into every contract: transparent tiered pricing shown on the page, and 100% of rights, royalties, and accounts retained by the author. Most of the industry hides both."),
]
faq_items = []
for q, a in faqs:
    faq_items.append(
        '<!-- wp:details {"style":{"border":{"top":{"width":"1px","style":"solid"},'
        '"right":[],"bottom":[],"left":[]},"spacing":{"padding":{"top":"18px","bottom":"18px"}}},'
        '"borderColor":"contrast-3"} -->\n'
        '<details class="wp-block-details" style="border-top-color:var(--wp--preset--color--contrast-3);'
        'border-top-width:1px;border-top-style:solid;padding-top:18px;padding-bottom:18px">'
        '<summary style="font-family:\'Playfair Display\',serif;font-size:20px;font-weight:600;'
        'color:var(--contrast);cursor:pointer">' + esc_html(q) + '</summary>'
        '<!-- wp:paragraph {"style":{"typography":{"fontFamily":"\'Inter\',sans-serif","fontSize":"16px",'
        '"lineHeight":"1.7"},"spacing":{"margin":{"top":"14px"}}},"textColor":"contrast-2"} -->\n'
        '<p class="has-contrast-2-color has-text-color" style="margin-top:14px;'
        'font-family:\'Inter\',sans-serif;font-size:16px;line-height:1.7">' + esc_html(a) + '</p>\n'
        '<!-- /wp:paragraph --></details>\n<!-- /wp:details -->')

faq = section(WHITE, [
    element('div', {"maxWidth": "860px", "marginRight": "auto", "marginLeft": "auto",
                    "width": "100%"}, [
        element('div', {"textAlign": "center", "marginBottom": "40px"}, [
            eyebrow('Frequently Asked Questions'),
            heading('What authors ask before hiring a book publishing company',
                    size='36px', mob='26px')]),
        element('div', {}, faq_items)])])
S.append(faq)

# ---- 10. FINAL CTA ----------------------------------------------------------
cta = section(NAVY, [
    element('div', {"maxWidth": "820px", "marginRight": "auto", "marginLeft": "auto",
                    "width": "100%", "textAlign": "center"}, [
        text('div', '❦', {"fontFamily": SERIF, "fontSize": "34px", "color": GOLD,
             "marginBottom": "10px"}),
        text('p', 'NEXT STEPS', {"fontFamily": SANS, "fontSize": "14px",
             "fontWeight": "600", "letterSpacing": "2px", "textTransform": "uppercase",
             "color": "#BFD0E4", "marginTop": "0", "marginBottom": "16px"}),
        text('h2', 'Get a real price or talk to a real specialist', {
            "fontFamily": SERIF, "fontSize": "40px", "fontWeight": "700",
            "lineHeight": "1.15", "color": "#ffffff", "marginTop": "0",
            "marginBottom": "20px", M_MOB: {"fontSize": "29px"}}),
        text('p', esc_html('Two ways to start. Run the calculator for an instant tier-by-tier estimate based on word count, genre, and bundle — no card, no email. Or book a call with a publishing specialist who reads your pages before you commit to anything.'), {
            "fontFamily": SANS, "fontSize": "18px", "lineHeight": "1.7",
            "color": "#D7E1EC", "maxWidth": "660px", "marginTop": "0",
            "marginBottom": "32px", "marginRight": "auto", "marginLeft": "auto"}),
        element('div', {"display": "flex", "flexWrap": "wrap", "gap": "16px",
                        "justifyContent": "center", "marginBottom": "22px"}, [
            button('Build your quote in 60 seconds', primary=True, href='#'),
            button('Book a Call with a Publishing Specialist', primary=False, href='#')]),
        text('p', esc_html('Mutual NDA available before the call on request. 100% rights retention guaranteed. No contracts until you decide.'), {
            "fontFamily": SANS, "fontSize": "14px", "color": "#AFC2D8",
            "marginTop": "0", "marginBottom": "0"})])],
    anchor='next-steps')
S.append(cta)

# ---- 11. FOOTER STRIP -------------------------------------------------------
strip = section(INK, [
    element('div', {"maxWidth": "1200px", "marginRight": "auto", "marginLeft": "auto",
                    "width": "100%", "textAlign": "center"}, [
        text('p', 'AuthorWings  ·  Transparent Pricing  ·  100% Your Rights', {
            "fontFamily": SERIF, "fontSize": "18px", "fontWeight": "600",
            "color": "#ffffff", "marginTop": "0", "marginBottom": "8px"}),
        text('p', 'Last updated: July 2026', {"fontFamily": SANS, "fontSize": "13px",
             "color": "#8A929C", "marginTop": "0", "marginBottom": "0"})])],
    pad_top=40, pad_bot=40)
S.append(strip)

# ================================================================= WRITE
out = '\n\n'.join(S) + '\n'
with open('homepage.html', 'w', encoding='utf-8') as f:
    f.write(out)
print("blocks generated, bytes=%d, uids=%d" % (len(out), _counter[0]-1000))
