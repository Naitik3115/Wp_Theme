# Atelier B2B – Phase 2: Elementor-editable global header (patch package)

This is a **patch**, not a full theme. It adds an Elementor Free–editable header to your existing Atelier B2B theme (v1.0.0 → 1.1.0). Homepage sections and footer are untouched.

Requires: Elementor Free (3.16+ recommended – Container layouts must be enabled, which is the default), WordPress 6.0+, PHP 7.4+. No Pro, ACF or add-ons.

## 1. File manifest

| File | Action |
|---|---|
| `functions.php` | **Replace** (adds one `require_once` line, version 1.1.0) |
| `header.php` | **Replace** (Elementor header if active, otherwise original static header) |
| `template-parts/header/mobile-menu.php` | **Replace** (same menu source as the desktop nav) |
| `assets/js/header.js` | **Replace** (adds editor guard + header-height measurement; scroll logic unchanged) |
| `single-atelier_header.php` | **Add** (theme root – canvas for editing the header) |
| `assets/css/elementor-header.css` | **Add** |
| `inc/elementor/elementor-integration.php` | **Add** (create folder `inc/elementor/`) |
| `inc/elementor/header-cpt.php` | **Add** |
| `inc/elementor/header-render.php` | **Add** |
| `inc/elementor/header-settings.php` | **Add** |
| `inc/elementor/atelier-global-header.json` | **Add** – starter layout (used by the one-click setup; can also be imported manually) |
| `inc/elementor/widgets/class-atelier-navigation.php` | **Add** (create folder `inc/elementor/widgets/`) |
| `inc/elementor/widgets/class-atelier-marquee.php` | **Add** |

Unchanged and **not** in this package: `inc/theme-setup.php`, `inc/enqueue-assets.php`, `main.css`, `responsive.css`, `main.js`, `animations.js`, `sliders.js`, `announcement-bar.php`, `navigation.php`, all homepage template parts, footer.

> Back up `wp-content/themes/atelier-b2b/` first. Upload via FTP / File Manager, keeping the folder structure above, and overwrite the four "Replace" files.

## 2. Setup (≈ 3 minutes)

1. Install and activate **Elementor** (free). Keep **Atelier B2B** active.
2. In the dashboard you now have **Atelier Header** in the left menu.
3. Go to **Atelier Header → Active Header** and click **Create starter header**. This builds the header from the bundled JSON (announcement marquee, logo, Atelier Navigation, CTA), publishes it and sets it active.
4. Visit your site – the header should look like the original.
5. **Atelier Header → All Headers → Atelier Global Header → Edit with Elementor** to edit visually.

If you create headers by hand: Atelier Header → Add New → *Edit with Elementor* → build → Publish → then choose it under **Active Header** (or use the row action **Set as active header**).

## 3. What you can edit in Elementor

- **Announcement bar** (container with class `atelier-announcement`): background, padding, min-height, Advanced → Responsive → visibility (hide on desktop/tablet/mobile). Inside it the **Atelier Marquee** widget: messages (add/remove/reorder), speed, separator on/off, pause on hover, typography, text colour, separator colour/spacing.
- **Logo** (Image widget, class `atelier-header-logo`): change image, Width / Max width / Height, alignment, link.
- **Menu** (**Atelier Navigation** widget): choose any WordPress menu or the *Primary Menu* location; typography, link colours, spacing, hamburger colour. Menu items are managed in Appearance → Menus (top-level items only, as in the original design). The fullscreen mobile menu uses the **same menu**; its button text/link/logo are in the widget's *Mobile menu* section.
- **CTA** (Button widget, class `atelier-header-cta`): text, link, typography, colours, border, padding.
- **Nav bar container** (class `atelier-navigation`): padding, min-height, background.

Don't remove the stable classes (`atelier-announcement`, `atelier-navigation`, `atelier-header-logo`, `atelier-header-menu`, `atelier-header-cta`) – the scroll behaviour and styling key off them (they are in each element's *Advanced → CSS Classes*).

### Colour behaviour (important)
The theme switches the header automatically: transparent with light text/white logo over the hero, beige blurred bar with dark text once you scroll back up, solid on inner pages. Leave background/colour fields **empty** to keep that behaviour. If you set a background or colour in Elementor it applies in **all** states (e.g. a nav bar background set in Elementor stays on even over the hero). The logo is auto-inverted to white over the hero, so use a **dark** logo.

## 4. Importing the JSON manually (optional)

Only needed if you don't use the one-click setup.
1. Open `inc/elementor/atelier-global-header.json` in a text editor and replace the three tokens with real values: `__HOME_URL__` (e.g. `https://yoursite.com/`), `__CONTACT_URL__` (e.g. `https://yoursite.com/contact/`), `__LOGO_URL__` (full URL of your logo image).
2. **Elementor → Templates → Saved Templates → Import Templates**, upload the file.
3. **Atelier Header → Add New**, title it, click **Edit with Elementor**, click the folder icon (*Add Template*) → *My Templates* → **Insert** the imported template. Update, then publish the header and select it under **Active Header**.

## 5. Testing checklist (needs a live WordPress staging site)

- [ ] Home page: header overlays the hero, transparent, white logo/links; no extra gap above the hero.
- [ ] Scroll down → whole header (bar + nav) slides away; scroll up slightly → it fades/slides in with beige blurred background; back at top → transparent again.
- [ ] Inner page: solid header, content not hidden under it.
- [ ] Resize below 1180px: links + CTA disappear, hamburger appears; menu opens fullscreen, body scroll locked, Esc closes, Tab stays inside, links = same as desktop.
- [ ] Edit the marquee text / logo / CTA in Elementor → Update → hard refresh the site: changes appear.
- [ ] Elementor editor: header stays visible, no hiding on scroll, mobile overlay absent, widgets clickable; Desktop/Tablet/Mobile previews work.
- [ ] View source: exactly one `<header id="site-header">`.
- [ ] Deactivate Elementor → static header appears automatically.
- [ ] Homepage sections and footer identical to before.

## 6. Restoring the original static header

Any of these works, fastest first:
1. **Atelier Header → Active Header** → choose *Static PHP header (fallback)* → Save.
2. Add `define( 'ATELIER_DISABLE_ELEMENTOR_HEADER', true );` to `wp-config.php` (above "That's all, stop editing").
3. Deactivate Elementor, or trash/unpublish the active header – the static header takes over automatically (the theme never renders an empty header).
4. Full rollback: restore the four replaced files from your backup and delete the added files.

## 7. Notes & known limits

- Verified here: PHP syntax lint (PHP 8.3) of every file, JS syntax check, JSON validity with unique element IDs, patch-over-theme file/require paths. **Not verified** (no WordPress/Elementor/browser available): actual rendering, Elementor editor behaviour, the starter-header creation, and the `elementor-device-desktop` preview rule. Please run the checklist on staging.
- The starter header is written straight into Elementor's data (`_elementor_data`). If Elementor ever shows it as empty, use the manual import in section 4.
- Mobile menu look (fonts, colours, animation) stays theme-controlled; it is not an Elementor-styled element.
- Header previews at `?atelier_header=…` are noindex and redirect non-editors to the homepage.
- The existing static `navigation.php` / `announcement-bar.php` are kept as the fallback – editing them has no effect while the Elementor header is active.
