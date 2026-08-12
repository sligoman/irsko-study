# Hero Background Realignment — Design Spec

**Date:** 2026-08-08
**Branch:** `redesign` (worktree `irskostudy_cz_redesign`)
**Status:** Approved

## Goal

The flat dark-green hero with radial-gradient blobs used across inner pages does not fit the irsko reference design. Reference heroes are either (a) dark green under a full-bleed photo with a black gradient overlay (`hero-card`, `hero-stats`) or (b) a plain light header with dark-green typography (`kontakty`, `ochrana-udaju`). Realign the heroes to the reference, hybrid approach.

## Scope

### Changed
- **New component `resources/views/components/subpage/hero-light.blade.php`** — plain light header + light stat strip. Used by: `contact`, `privacy`, `faq`, `sitemap`.
- **New component `resources/views/components/subpage/hero-photo.blade.php`** — photo-under-green (mirrors reference `hero-card`). Used by: courses finder (`/kurzy`).
- **`resources/views/pages/finder/course.blade.php`** — hero section updated to photo-under-green; right-column image card removed.
- **`resources/views/pages/finder/school.blade.php`** — hero section updated to photo-under-green; right-column image card removed.

### Unchanged
- `x-subpage.hero` (green + blobs) stays as-is for `about`, `universities`, `why`, `services` (candidate follow-up, out of scope).
- All routes, models, controllers, config. No CSS token additions.
- Content (eyebrow/title/accent/text/stats strings) preserved on every page.

## Components

### `x-subpage.hero-light`

Props (same interface as current `hero`): `eyebrow`, `title`, `accent`, `text`, `stats`.

Markup:
- `<section class="bg-base-white pt-12 md:pt-16">` + `.layout-container`
- eyebrow: `type-text-md-semibold text-brand-orange`
- `<h1 class="type-display-xl text-brand-dark-green">` title, accent word wrapped in `text-brand-orange`
- intro: `type-text-lg mt-4 text-brand-dark-green`
- stat strip: `mt-10 grid max-w-[940px] gap-4 sm:grid-cols-3`; each card `rounded-[12px] bg-brand-light-gray p-5` with value `type-display-xs text-brand-dark-green` and label `type-text-sm text-brand-dark-green`
- no dark-green surface anywhere

Marker: `data-component="subpage-hero-light"`.

### `x-subpage.hero-photo`

Props: `eyebrow`, `title`, `accent`, `text`, `stats`, `photo` (`['src'=>null, 'srcset'=>null, 'alt'=>'']`).

Markup (mirrors reference `hero-card`):
- `<section class="relative overflow-hidden rounded-b-[24px] bg-brand-dark-green text-white">`
- `<img class="absolute inset-0 h-full w-full object-cover" loading="eager">` using `photo.src` / `photo.srcset` / `photo.alt`
- overlay: `absolute inset-0 bg-gradient-to-b from-black/10 via-black/35 to-black/60`
- content over it, `.layout-container relative py-16 md:py-20`:
  - eyebrow pill: `inline-flex rounded-full bg-white/10 px-4 py-2 text-white ring-1 ring-white/15`
  - `<h1 class="type-display-2xl mt-6 text-white">` title, accent in `text-brand-orange`
  - intro: `type-text-lg mt-6 max-w-[760px] text-white/85`
  - stats: `mt-12 grid max-w-[940px] gap-4 sm:grid-cols-3`, cards `rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15` (value `type-display-xs text-white`, label `type-text-sm text-white/80`)

Marker: `data-component="subpage-hero-photo"`.

Courses finder passes `photo` = `public/img/blog/dublin_centrum` with existing 1x/2x/3x/4x srcset variants.

### Detail heroes (`course.blade.php`, `school.blade.php`)

Replace the current decorative radial-gradient overlay block with:
- full-bleed school image via `x-subpage.university-image` (`img-class="absolute inset-0 h-full w-full object-cover"`)
- overlay: `absolute inset-0 bg-gradient-to-b from-black/10 via-black/35 to-black/60`
- remove the `hidden lg:block` right-column `<x-subpage.university-image>` card
- keep school pill, title, `title_en` (course only), and the three `bg-white/10` stat tiles over the photo

## Testing

- Add/extend redesign tests asserting:
  - `components.subpage.hero-light` rendered standalone contains `data-component="subpage-hero-light"` and does NOT contain `bg-brand-dark-green` (assert the component view directly — the layout's `#app` wrapper is `bg-brand-dark-green` for the contact/faq routes, so a full-page `assertDontSee` would be unreliable)
  - inner pages (`/kontakt`, `/ochrana-soukromi`, `/faq`, `/sitemap`) render `data-component="subpage-hero-light"`
  - courses page renders `data-component="subpage-hero-photo"` and references `dublin_centrum`
  - course/school detail heroes render the school image (`uni-*.jpg`) and the gradient overlay class (`from-black/10`)
- Existing Phase 1 tests (RoutesCleanup, UniversitySlideshowAssets, Privacy, Sitemap, Contact, Faq, CoursesFinder, CourseDetail, SchoolDetail, KurzyNavLinks, Homepage, Blog) must stay green.
- Live checks: `/`, `/kurzy`, `/kontakt`, `/faq`, `/ochrana-soukromi`, `/sitemap`, `/vysoke-skoly/university-of-galway`, `/kurzy/prvni-rocnik-umeni-a-designu` all 200.

## Non-Goals

- No changes to about/universities/why/services heroes.
- No route/model/data/config changes.
- No new libraries or CSS tokens.
