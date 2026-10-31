# Atelier B2B

Premium B2B apparel theme. Elementor Free optional. No Pro / ACF / WooCommerce required.

## Quick start

1. Activate **Atelier B2B** (+ Elementor Free if you want visual editing).
2. **Appearance → Customize → Logo** (use a dark logo).
3. **Appearance → Menus** → assign **Primary Menu** (optional; defaults exist).
4. Create pages and pick a template under **Page → Template**.

| Template | Use for |
|---|---|
| Atelier Homepage | Full homepage on any page |
| Atelier About | About Us |
| Atelier Contact | Contact |
| Atelier Collections | Product catalog + filters |

Site front page still uses `front-page.php` when set in **Settings → Reading**.

## What the theme includes

- Global header (announcement + nav) and footer
- Homepage: Hero → Trust marquee → Categories slider → Editorial feature → Selected work → Partnership CTA
- About, Contact, Collections pages
- GSAP scroll animations, category drag-slider, mobile menu

Static copy lives in `inc/content-config.php`. Media paths in `inc/asset-config.php`. Replace files in `assets/images/` (and optional `assets/video/hero-video.mp4`).

## Elementor (optional)

1. **Atelier Header → Active Header** → create / assign header → Edit with Elementor.
2. **Home Sections → Active Sections** → assign Hero, Trust, Categories, etc. Empty slot = PHP fallback.
3. Page templates: Edit with Elementor, or use Atelier widgets (Hero, About Hero, Product Catalog, …).
4. Starter JSON files: `inc/elementor/templates/`.

**Hero media:** landscape image (desktop/tablet) + optional portrait (mobile, image-only). If a **video** is set, it plays on all devices. Style → Layout → Minimum height works on Home Hero and About Hero.

**Collections products:** Elementor → **Atelier Product Catalog** widget → Products repeater (title, category slug, primary + hover image). Or edit `collections` items in `content-config.php`.

## Animations (keep as-is)

Driven by theme CSS/JS — do not add extra libraries.

| Attribute | Effect |
|---|---|
| `data-lines` | Headline line reveal |
| `data-fade` | Fade / slide up |
| `data-clip` | Image clip reveal |
| `data-parallax` | Slow parallax |
| `data-hero-media` | Hero background parallax |
| `data-slider` | Categories drag/swipe |

- Scripts: `assets/js/animations.js`, `sliders.js`, `header.js` (+ local GSAP in `assets/js/vendor/`).
- `prefers-reduced-motion` turns motion off automatically.
- If GSAP fails to load, content stays visible.

## Tips

- Clear cache after CSS/JS changes.
- Kill-switches (optional in `wp-config.php`): `ATELIER_DISABLE_ELEMENTOR_HEADER`, `ATELIER_DISABLE_ELEMENTOR_SECTIONS`.
