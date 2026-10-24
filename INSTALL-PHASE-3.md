# Atelier B2B – Phase 3: Elementor-editable Hero + Trust Marquee (patch package)

Patch for the existing theme. **Apply the Phase 2 header update first** (this patch reuses its integration: Elementor detection, "Atelier" widget category, capability constant, kill-switch pattern). Requires Elementor Free (3.16+ recommended, Container layouts). No Pro / ACF / add-ons.

## 1. File manifest

| File | Action |
|---|---|
| `functions.php` | **Replace** (adds `inc/sections-markup.php`, version 1.2.0) |
| `front-page.php` | **Replace** (Hero + Trust now go through `atelier_render_home_section()`; the other 4 sections unchanged) |
| `inc/helpers.php` | **Replace** (only `atelier_marquee()` changed: optional custom/no separator; defaults identical) |
| `inc/sections-markup.php` | **Add** – shared markup for Hero and Trust (used by fallback AND widgets) |
| `template-parts/home/hero.php` | **Replace** – now calls the shared function; output identical |
| `template-parts/home/trust-marquee.php` | **Replace** – same |
| `inc/elementor/elementor-integration.php` | **Replace** (adds new files + 2 widgets) |
| `inc/elementor/sections-cpt.php` | **Add** – "Home Sections" post type + section-type box |
| `inc/elementor/section-render.php` | **Add** – resolve / render / fallback |
| `inc/elementor/section-settings.php` | **Add** – "Active Sections" screen, starter creation |
| `inc/elementor/widgets/class-atelier-hero.php` | **Add** |
| `inc/elementor/widgets/class-atelier-trust-marquee.php` | **Add** |
| `inc/elementor/templates/atelier-hero.json` | **Add** – importable Elementor template |
| `inc/elementor/templates/atelier-trust-marquee.json` | **Add** – importable Elementor template |
| `assets/css/elementor-sections.css` | **Add** |
| `single-atelier_section.php` | **Add** (theme root – editing canvas) |

Not touched: header files from Phase 2, categories / feature / showcase / partner templates, footer, `main.css`, `responsive.css`, `animations.css`, `main.js`, `animations.js` (existing GSAP code is reused as-is: the Hero widget outputs the same `data-hero-media`, `#hero` markup that `animations.js` already targets).

Back up the theme folder, upload keeping paths, overwrite the "Replace" files. Create `inc/elementor/templates/` if missing.

## 2. Activate

1. Elementor active, theme active.
2. Dashboard → **Home Sections → Active Sections**.
3. Click **Create starter Hero**, then **Create starter Trust Marquee**. Each creates a published Elementor section recreating the current design and assigns it to its slot.
4. Reload the homepage – it should look identical.
5. **Home Sections → All Sections** → hover a section → **Edit with Elementor**. Edit, **Update**, reload the homepage.

Other ways: Home Sections → Add New, set **Section type** (side box: Hero / Trust Marquee), Edit with Elementor, publish, then pick it under Active Sections (or use the row action *Set as active section*). Slots only accept sections of the matching type, so a Hero can't be put in the Trust slot.

## 3. What you can edit

**Atelier Hero widget** – Content: eyebrow, headline (one line per row = one animated line), subheading, scroll-indicator text (empty hides it), two buttons (text + link incl. new-tab / nofollow; empty text hides the secondary), background *type* (auto / image / video), background image (also the video fallback), MP4 video, video poster, entrance animation on/off, parallax on/off. Style: minimum height (empty = 100svh), text alignment, vertical position (both responsive), content padding (responsive), typography + colour for eyebrow / headline / subheading / buttons, overlay top & bottom colour (alpha slider = opacity) and overall overlay opacity.

**Atelier Trust Marquee widget** – label, items (add / remove / drag-reorder), separator (default asterisk icon, any custom symbol, or none), seconds per loop, direction (right-to-left or left-to-right), pause on hover; style: section background, padding, text/label typography + colours, separator colour / size / spacing. Seamless looping is unchanged (two identical groups translated −100%); `prefers-reduced-motion` stops the animation (existing CSS).

The wrapper container (classes `atelier-home-section-root…`) can be given other elements too, but keep the widget in it – the hero anchor `#hero`, the scroll link to `#trust` and the GSAP animations rely on it.

## 4. Manual JSON import (optional)

Templates are in `inc/elementor/templates/`. Replace the tokens first: `__HERO_POSTER_URL__`, `__HERO_VIDEO_URL__` (can be an empty string), `__EXPERTISE_URL__`, `__COLLECTIONS_URL__` (hero only). Then **Elementor → Templates → Saved Templates → Import**, create **Home Sections → Add New** (set Section type), *Edit with Elementor* → folder icon → *My Templates* → **Insert**, Update, publish, assign under Active Sections.

## 5. Fallback behaviour (never blank)

The original PHP section is rendered automatically when: Elementor is inactive; no section is assigned; the assigned one is unpublished/trashed/wrong type/not built with Elementor; it renders empty; or rendering throws. The Hero poster `<link rel="preload">` for the old image is dropped while an Elementor hero is active.

## 6. Testing checklist (needs a live staging site)

- [ ] Before editing, homepage Hero + Trust look identical to before (transparent header overlays hero, 100svh, headline line-reveal, image scale-in, parallax, scroll cue).
- [ ] Edit headline / eyebrow / buttons / colours / alignment (desktop, tablet, mobile) → Update → reload.
- [ ] Hero: swap the image; add an MP4 – plays muted/looping, poster shows first; remove it – image returns.
- [ ] Trust: add/remove/reorder items, change separator, direction, speed; loop has no jump or gap at 320 px and ≥1920 px.
- [ ] OS "reduce motion" on: marquees static, no hero motion.
- [ ] Elementor editor: widgets render, controls clickable, no header overlay, no duplicate sections.
- [ ] View source: one `id="hero"`, one `id="trust"`.
- [ ] Deactivate Elementor → original sections appear.
- [ ] Categories / feature / showcase / partner / footer / header unchanged.

## 7. Rollback

1. Active Sections → set a slot to *Original PHP section (fallback)* → Save (per section, instant).
2. Or `define( 'ATELIER_DISABLE_ELEMENTOR_SECTIONS', true );` in `wp-config.php` (header kill-switch is separate).
3. Or deactivate Elementor / trash the sections.
4. Full rollback: restore the replaced files from backup and delete the added ones.

## 8. Verification status

Done here: PHP lint (8.3) of all files in the merged theme; JSON validity; a stubbed-WordPress render comparing the original Hero + Trust HTML with the refactored static templates – identical after whitespace normalisation. **Not verified** (no WordPress / Elementor / browser): widget rendering in the editor, the starter-section creation, alignment CSS across Elementor breakpoints (assumes Elementor defaults: tablet ≤1024px, mobile ≤767px – custom breakpoints will shift it), and the visual result. Run the checklist on staging.
