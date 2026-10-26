# Atelier B2B — Phase 4 Install Guide

Dynamic **Categories** + **Editorial Collection** Elementor Free widgets, assignable **Atelier Homepage** page template, shared homepage assembly.

In this repository the Phase 4 theme files are **already applied** under the normal theme paths. Use `atelier-phase-4-update.zip` only when patching a separate Phase 2/3 install.

---

## 1. Backup (when applying the ZIP elsewhere)

Copy these theme paths before overwriting:

- `front-page.php`, `functions.php`, `style.css`
- `page-templates/template-atelier-home.php` (may be new)
- `template-parts/homepage-content.php` (may be new)
- `template-parts/home/categories.php`
- `template-parts/home/featured-collection.php`
- `inc/theme-setup.php`, `inc/enqueue-assets.php`
- `inc/elementor/` (entire folder tree listed in `MANIFEST.txt`)
- `assets/js/sliders.js`

---

## 2. Copy files (ZIP install)

1. Unzip `atelier-phase-4-update.zip`.
2. From the extracted folder root, copy every theme path in `MANIFEST.txt` into:

   `wp-content/themes/atelier-b2b/`

   Keep the same relative folders (`page-templates/`, `inc/elementor/widgets/`, etc.).
3. Confirm `Version: 1.6.0` in `style.css` and `ATELIER_VERSION` in `functions.php`.

Do **not** change **Settings → Reading**. Do not paste homepage section content into every new page.

---

## 3. Elementor JSON import

Theme copies (also used by Active Sections helpers):

- `inc/elementor/templates/atelier-categories.json`
- `inc/elementor/templates/atelier-featured-collection.json`

Convenience copies at package root (same files):

- `atelier-categories.json`
- `atelier-featured-collection.json`

### Import steps (Elementor Free)

1. **Templates → Saved Templates → Import Templates** (or Elementor → Tools → Import).
2. Import `atelier-categories.json`.
3. Import `atelier-featured-collection.json`.
4. For each import, create or open an **Atelier Section** (`atelier_section` CPT) of the matching type, or use a Saved Template / Section post your theme already uses for Home Sections.
5. Insert the matching widget (**Atelier Categories** / **Atelier Editorial Collection**) if the JSON did not land as a full template — or open the imported template and save it as the section content.

### Wire Active Sections (no hardcoded post IDs)

1. Go to **Atelier → Active Sections** (or your theme’s Home Sections settings screen).
2. Assign the Categories Elementor section to the **Categories** slot.
3. Assign the Editorial Collection section to the **Feature / Editorial** slot.
4. Leave a slot empty to keep the static PHP fallback (`template-parts/home/categories.php` / `featured-collection.php`).

---

## 4. Assignable Atelier Homepage template

1. **Pages → Add New**.
2. In the Page Attributes / Template dropdown, choose **Atelier Homepage**.
3. Publish. You should see the full homepage stack (Hero → Trust → Categories → Editorial → Selected Work → Partnership) with the same Elementor assignments and PHP fallbacks as the front page.
4. Global header and footer come from `get_header()` / `get_footer()` — do not add them again in Elementor.

### Front page vs page template

| Situation | What WordPress loads | Result |
|-----------|----------------------|--------|
| Page set as **Settings → Reading → Homepage** | `front-page.php` | Shared `template-parts/homepage-content.php` |
| Any other page with template **Atelier Homepage** | `page-templates/template-atelier-home.php` | Same shared template part |

Both paths use one assembly file so sections are not duplicated and Elementor is not rendered recursively.

---

## 5. Testing checklist

- [ ] Front page still renders complete homepage.
- [ ] New page with **Atelier Homepage** matches front page section order.
- [ ] Categories: drag/swipe, arrows, ~2.3 cards on desktop, hover animations.
- [ ] Editorial Collection: brown background, left image, cream typography, GSAP reveal/scroll.
- [ ] Edit Categories / Editorial in Elementor Free; frontend updates after save.
- [ ] Empty Active Section slot → static PHP fallback appears.
- [ ] Hero, Trust, Showcase, Partnership, Header, Footer unchanged and working.
- [ ] No double header/footer or duplicate sections.
- [ ] `prefers-reduced-motion` still respected.
- [ ] Elementor editor preview for both new widgets looks correct.

---

## 6. Rollback

Restore the backed-up files from step 1. Clear caches (plugin / host / Elementor CSS). Reactivate previous Active Section assignments if you changed them.

---

## 7. In-repo Phase 4 paths

| Path | Role |
|------|------|
| `page-templates/template-atelier-home.php` | Assignable **Atelier Homepage** |
| `template-parts/homepage-content.php` | Shared section stack |
| `inc/elementor/widgets/class-atelier-categories.php` | Categories widget |
| `inc/elementor/widgets/class-atelier-featured-collection.php` | Editorial Collection widget |
| `inc/elementor/templates/atelier-categories.json` | Importable template |
| `inc/elementor/templates/atelier-featured-collection.json` | Importable template |
| `atelier-phase-4-update.zip` | Redistributable patch package |
| `MANIFEST.txt` | ADD / REPLACE list for the ZIP |

Widgets: **Atelier Categories** (`atelier-categories`), **Atelier Editorial Collection** (`atelier-featured-collection`).
