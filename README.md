# Atelier B2B – WordPress Theme (Phase 1)

Premium editorial theme for a B2B apparel manufacturing / sourcing brand.
Phase 1 covers the **global header, complete homepage and global footer**. No WooCommerce, no plugins required.

## Install
1. WordPress Dashboard → Appearance → Themes → Add New → Upload Theme.
2. Upload `atelier-b2b-theme.zip`, install, activate.
3. The homepage renders through `front-page.php` automatically (works whether the front page is "latest posts" or a static page).
4. Optional: Appearance → Menus → assign a menu to **Primary Menu**. Without one, the default links (Home, About Us, Our Expertise, Collections, Manufacturing, Contact) are used. Default link slugs: `/about-us/`, `/our-expertise/`, `/collections/`, `/manufacturing/`, `/contact/`.
5. Logo: Appearance → Customize → Site Identity → Logo (otherwise `assets/images/logo.svg` placeholder is used). Use a **dark** logo; the theme inverts it to white over the hero and in the footer.

## File structure
```
atelier-b2b/
├── style.css, functions.php, index.php, front-page.php, header.php, footer.php, page.php, 404.php, screenshot.png
├── inc/        theme-setup.php · enqueue-assets.php · asset-config.php · content-config.php · helpers.php
├── template-parts/
│   ├── header/ announcement-bar.php · navigation.php · mobile-menu.php
│   ├── home/   hero · trust-marquee · categories · featured-collection · product-showcase · partnership-cta
│   └── footer/ footer-main.php
└── assets/     css/ (main, animations, responsive) · js/ (main, header, sliders, animations, vendor/gsap) · images/ · fonts/ · video/
```

## ⚠ Placeholder media
All images in `assets/images/` (except `logo.svg`) are **generated placeholders, not photography** – each is labelled "PLACEHOLDER". No licensed photos could be downloaded in the build environment. Replace these (same file name, WebP):

| File | Size | Used for |
|---|---|---|
| hero-poster.webp | 1920×1080 | Hero image / video poster (preloaded, high priority) |
| category-men / women / streetwear / essentials / activewear.webp | 1200×1600 | Category slider |
| editorial-collection.webp | 1400×1800 | Brown editorial feature |
| showcase-01 / 02 / 04.webp | 1200×1600 / 1200×1500 / 1200×1600 | Showcase |
| showcase-03.webp | 1800×1125 | Showcase (landscape) |
| logo.svg | 200×44 | Placeholder wordmark |

If a size differs, update `w`/`h` in `inc/asset-config.php` to avoid layout shift. `screenshot.png` is also a placeholder.

**Hero video:** drop `assets/video/hero-video.mp4` in place – it is used automatically (muted, looping, inline, poster = hero-poster.webp). Without the file the image is used. Keep it under ~6 MB, H.264.

## Editing content
All homepage copy, links and image keys live in `inc/content-config.php`; media in `inc/asset-config.php`; marquee speeds are the `speed` values (seconds per loop – lower is faster). Footer email/phone/location/social are **placeholders** (`hello@example.com`, etc.).

## Elementor homepage sections
Elementor Free can replace Hero, Trust Marquee, Featured Categories, Editorial Collection, Selected Work, Partnership CTA, and the global Footer independently. Create or edit sections under **Home Sections → Active Sections**; each slot falls back to its original PHP template when Elementor is unavailable or no section is assigned. The Elementor widgets use the theme's existing CSS, slider, and GSAP selectors.

The importable starter templates are in `inc/elementor/templates/atelier-categories.json`, `inc/elementor/templates/atelier-featured-collection.json`, `inc/elementor/templates/atelier-selected-work.json`, `inc/elementor/templates/atelier-partnership-cta.json`, and `inc/elementor/templates/atelier-footer.json`. Import them through Elementor's template importer and edit the matching Atelier widget. If no image is selected, those widgets use the matching bundled theme imagery.

The Elementor navigation widget uses the selected WordPress menu for both desktop and mobile links; manage menu items under **Appearance → Menus**. Top-level links are shown, and external-link targets are preserved in both menus. The active global header is selected under **Atelier Header → Active Header**. The current page, including a static front page, is correctly marked in the Elementor menu.

## Assigning the complete homepage to a page
Create or edit a WordPress page and choose **Atelier Homepage** under **Page → Template**. It uses the same shared homepage assembly as `front-page.php`, including active Elementor sections, static PHP fallbacks, and the global header/footer. WordPress continues to prioritize `front-page.php` for the configured front page; assigning the template to another page does not change Reading Settings or duplicate homepage content.

## Dependencies
- GSAP 3.12.5 + ScrollTrigger – bundled locally (`assets/js/vendor`). If they fail to load, all content stays visible; only scroll reveals/parallax are skipped.
- Fonts: Manrope, Cormorant Garamond, Inter via Google Fonts (with system fallbacks). To self-host, download the WOFF2 files into `assets/fonts/`, add `@font-face` rules to `main.css` and remove the `atelier-fonts` enqueue in `inc/enqueue-assets.php`.
- Lenis smooth scroll was intentionally **not** used (avoids scroll hijacking); native smooth scroll only.
- Slider is custom vanilla JS (no Swiper).

## Behaviour notes
- Header: transparent over hero → hides on scroll down (with jitter threshold) → reveals on scroll up with beige blur background → transparent again at top. Announcement bar and nav move as one unit. Inner pages use the solid state.
- Full navigation shows from 1180px; below that the fullscreen mobile menu is used (focus trap, Esc to close, body scroll lock).
- `prefers-reduced-motion` disables marquees, hero sequence and GSAP effects.

## Converting to dynamic later
Each section template reads one array from `atelier_content()` / `atelier_assets()`. Replace those arrays with `get_field()` (ACF), theme options, or a custom post type query (e.g. categories → taxonomy terms, showcase → CPT) without touching markup or CSS.

## Testing status
Verified in the build environment: PHP syntax lint (PHP 8.3) on every PHP file, JavaScript syntax check, asset path existence. **Not tested:** activation inside a real WordPress install and in-browser rendering/visual QA (no WordPress or browser was available). Please run through the checklist on a staging site: activation, header scroll behaviour, mobile menu, marquees, slider drag, hero video, 320–1920px layouts, console errors.
