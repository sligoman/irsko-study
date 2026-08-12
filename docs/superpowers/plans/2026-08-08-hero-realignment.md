# Hero Background Realignment Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the flat dark-green + radial-blob heroes with the approved hybrid: plain light header + stat strip on contact/privacy/FAQ/sitemap, a photo-under-green hero (Dublin, from a new `public/img/static/` folder with 1x-4x variants, irsko-style responsive srcset) on the courses finder, and photo-under-green heroes on course/school detail using the school's own image.

**Architecture:** Two new Blade components — `x-subpage.hero-light` (white header, `text-brand-dark-green` type, orange accent, light stat cards, no green surface) and `x-subpage.hero-photo` (dark-green section under a full-bleed photo + `bg-gradient-to-b from-black/10 via-black/35 to-black/60` overlay, mirroring irsko's `hero-card`; srcset built exactly like irsko's `x-redesign.ui.responsive-image` from `{name}-{1..4}x` in `public/img/static/`). The existing `x-subpage.hero` (green + blobs) stays untouched for about/universities/why/services. Course/school detail hero sections swap their decorative radial-gradient overlay for a full-bleed school image (via the existing `x-subpage.university-image`) + the same black gradient overlay, and drop the right-column image card.

**Tech Stack:** Laravel 11, Blade, Tailwind CSS v4 token utilities (`type-*`, `layout-container`, `home-section`, `bg-base-white`, `bg-brand-light-gray`, `bg-brand-dark-green`, `text-brand-orange`), ImageMagick (`convert`) on the host for asset generation.

## Global Constraints

- Worktree: `/home/david/Development/sites/irskostudy_cz_redesign`, branch `redesign`.
- Preserve all content strings (eyebrow/title/accent/text/stats) on every page; only the hero markup changes.
- `public/img` is gitignored; generated/copied images stay local-only (commit code + tests only).
- Tests run inside the Docker PHP container:
  `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=...'`
- Image generation runs on the host (ImageMagick `convert` at `/usr/bin/convert`, PIL available). The container has no convert.
- Frontend rebuild not required for Blade-only changes; rebuild only if a Vue component changes (not the case here).
- Full `php artisan test` has one unrelated failure (`Tests\Feature\LeadTest` — sqlite `information_schema`). Run only focused filters; never claim full-suite green.
- Commit style follows repo history: short lowercase `fix: ...` / `feat: ...` / `docs: ...` messages.
- Never assert full-page absence of `bg-brand-dark-green` for contact/faq routes: the layout's `#app` wrapper (layouts/app.blade.php:44) is `bg-brand-dark-green` for those routes. Assert against the component view standalone instead.

---

### Task 1: Generate Dublin hero 1x-4x variants in `public/img/static/`

**Files:**
- Create (images): `public/img/static/irsko_dublin_hero-1x.jpg`, `-2x.jpg`, `-3x.jpg`, `-4x.jpg`
- Test: `tests/Feature/HeroStaticAssetsTest.php`

**Interfaces:**
- Consumes: `/home/david/Development/sites/irsko_ie_web/public/img/static/irsko_lokalita_dublin.png` (3840×2160, landscape).
- Produces: 16:9 JPEG variants 640×360, 960×540, 1200×675, 1600×900 under `public/img/static/` named `irsko_dublin_hero-{1..4}x.jpg` — consumed by `x-subpage.hero-photo` (Task 2) and the courses page (Task 3).

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class HeroStaticAssetsTest extends TestCase
{
    public function test_dublin_hero_variants_exist(): void
    {
        foreach ([1, 2, 3, 4] as $scale) {
            $this->assertFileExists(
                public_path("img/static/irsko_dublin_hero-{$scale}x.jpg")
            );
        }
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=HeroStaticAssetsTest'`
Expected: FAIL — `img/static/irsko_dublin_hero-1x.jpg` does not exist.

- [ ] **Step 3: Generate the variants on the host**

```bash
mkdir -p /home/david/Development/sites/irskostudy_cz_redesign/public/img/static
SRC=/home/david/Development/sites/irsko_ie_web/public/img/static/irsko_lokalita_dublin.png
DST=/home/david/Development/sites/irskostudy_cz_redesign/public/img/static
convert "$SRC" -resize 640x360 "$DST/irsko_dublin_hero-1x.jpg"
convert "$SRC" -resize 960x540 "$DST/irsko_dublin_hero-2x.jpg"
convert "$SRC" -resize 1200x675 "$DST/irsko_dublin_hero-3x.jpg"
convert "$SRC" -resize 1600x900 "$DST/irsko_dublin_hero-4x.jpg"
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=HeroStaticAssetsTest'`
Expected: 1 passed.

- [ ] **Step 5: Commit**

Note: images are gitignored (`public/img`); they will not appear in `git status`. Commit only the test.

```bash
git add tests/Feature/HeroStaticAssetsTest.php
git commit -m "test: assert static dublin hero image variants exist"
```

---

### Task 2: Create `x-subpage.hero-photo` component

**Files:**
- Create: `resources/views/components/subpage/hero-photo.blade.php`
- Test: `tests/Feature/HeroPhotoComponentTest.php`

**Interfaces:**
- Consumes: `public/img/static/irsko_dublin_hero-{1..4}x.jpg` (Task 1).
- Produces: component `x-subpage.hero-photo` with props `eyebrow`, `title`, `accent`, `text`, `stats`, `photo` (`['src'=>string, 'sizes'=>string, 'alt'=>string]`); renders `data-component="subpage-hero-photo"`, full-bleed `<img>` with srcset built from the `-{1..4}x` variants at 640/960/1200/1600w, and the black gradient overlay `bg-gradient-to-b from-black/10 via-black/35 to-black/60`. Consumed by the courses page (Task 3).

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class HeroPhotoComponentTest extends TestCase
{
    public function test_hero_photo_builds_responsive_srcset(): void
    {
        $html = (string) view('components.subpage.hero-photo', [
            'eyebrow' => 'Kurzy a programy',
            'title' => 'Najdi kurz',
            'accent' => 'sedne',
            'text' => 'Procházej programy.',
            'stats' => [],
            'photo' => [
                'src' => 'img/static/irsko_dublin_hero.jpg',
                'sizes' => '(max-width: 768px) 100vw, 1280px',
                'alt' => 'Dublin, Irsko',
            ],
        ])->render();

        $this->assertStringContainsString('data-component="subpage-hero-photo"', $html);
        $this->assertStringContainsString('from-black/10', $html);
        $this->assertStringContainsString('irsko_dublin_hero-1x.jpg', $html);
        $this->assertStringContainsString('irsko_dublin_hero-4x.jpg', $html);
        $this->assertStringContainsString('640w', $html);
        $this->assertStringContainsString('Najdi kurz', $html);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=HeroPhotoComponentTest'`
Expected: FAIL — view `components.subpage.hero-photo` not found.

- [ ] **Step 3: Create the component**

```blade
@props([
  'eyebrow' => null,
  'title' => null,
  'accent' => null,
  'text' => null,
  'stats' => [],
  'photo' => ['src' => null, 'sizes' => '(max-width: 768px) 100vw, 1280px', 'alt' => ''],
])

@php
  $imageExtension = strtolower((string) pathinfo($photo['src'] ?? '', PATHINFO_EXTENSION));
  $imageBase = $imageExtension !== ''
      ? substr((string) $photo['src'], 0, -(strlen($imageExtension) + 1))
      : (string) $photo['src'];
  $imageBase = preg_replace('/-(?:1|2|3|4)x$/', '', $imageBase ?? '');
  $src = asset($imageBase . '-2x.' . $imageExtension);
  $srcset = collect([1 => 640, 2 => 960, 3 => 1200, 4 => 1600])
    ->map(fn ($width, $scale) => asset($imageBase . '-' . $scale . 'x.' . $imageExtension) . ' ' . $width . 'w')
    ->implode(', ');
@endphp

<section data-component="subpage-hero-photo" class="relative overflow-hidden rounded-b-[24px] bg-brand-dark-green text-white">
  @if(!empty($photo['src']))
    <img
      src="{{ $src }}"
      srcset="{{ $srcset }}"
      sizes="{{ $photo['sizes'] }}"
      alt="{{ $photo['alt'] ?? '' }}"
      class="absolute inset-0 h-full w-full object-cover"
      loading="eager"
      decoding="async"
    >
  @endif
  <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/35 to-black/60"></div>

  <div class="layout-container relative py-16 md:py-20">
    <div class="max-w-[940px]">
      @if($eyebrow)
        <p class="type-text-md-semibold inline-flex rounded-full bg-white/10 px-4 py-2 text-white ring-1 ring-white/15">{{ $eyebrow }}</p>
      @endif
      <h1 class="type-display-2xl mt-6 text-white">
        {{ $title }} @if($accent)<span class="text-brand-orange">{{ $accent }}</span>@endif
      </h1>
      @if($text)
        <p class="type-text-lg mt-6 max-w-[760px] text-white/85">{{ $text }}</p>
      @endif
    </div>

    @if(count($stats))
      <div class="mt-12 grid max-w-[940px] gap-4 sm:grid-cols-3">
        @foreach($stats as $stat)
          <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
            <p class="type-display-xs text-white">{{ $stat['value'] }}</p>
            <p class="type-text-sm mt-1 text-white/80">{{ $stat['label'] }}</p>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=HeroPhotoComponentTest'`
Expected: 1 passed.

- [ ] **Step 5: Commit**

```bash
git add resources/views/components/subpage/hero-photo.blade.php tests/Feature/HeroPhotoComponentTest.php
git commit -m "feat: add photo hero component with responsive static images"
```

---

### Task 3: Switch the courses finder to `x-subpage.hero-photo`

**Files:**
- Modify: `resources/views/pages/finder/courses.blade.php:7-22` (hero block)
- Test: `tests/Feature/CoursesFinderRedesignTest.php` (update one assertion, add one test)

**Interfaces:**
- Consumes: `x-subpage.hero-photo` (Task 2) and `public/img/static/irsko_dublin_hero-{1..4}x.jpg` (Task 1).
- Produces: courses page hero rendered as `data-component="subpage-hero-photo"` referencing `irsko_dublin_hero`.

- [ ] **Step 1: Update the failing test**

Replace the `test_courses_page_uses_redesign_sections` body in `tests/Feature/CoursesFinderRedesignTest.php` with:

```php
    public function test_courses_page_uses_redesign_sections(): void
    {
        $courses = collect([
            (object) ['id' => 1, 'title_cs' => 'Počítačové systémy', 'title_en' => 'Computer Systems', 'url' => 'atu-101', 'school' => (object) ['name' => 'ATU'], 'description_cs' => 'Popis.', 'description_en' => null],
        ]);

        $paginator = new LengthAwarePaginator($courses, $courses->count(), 12, 1, [
            'path' => route('finder.courses'),
        ]);

        $initialData = [
            'schools' => new Collection([(object) ['id' => 1, 'school_id' => 'atu', 'name' => 'Atlantic Technological University']]),
            'fields' => new Collection([(object) ['id' => 1, 'description' => 'Technika']]),
            'levels' => new Collection([(object) ['id' => 1, 'name' => 'Bachelor']]),
            'results' => $paginator,
            'filters' => ['q' => null, 'school' => null, 'field' => null, 'level' => null, 'page' => 1],
        ];

        $response = $this->view('pages.finder.courses', ['initialData' => $initialData]);

        $response->assertSee('data-redesign-page="courses-finder"', false);
        $response->assertSee('data-redesign-section="course-finder"', false);
        $response->assertSee('data-component="subpage-hero-photo"', false);
        $response->assertSee('irsko_dublin_hero', false);
        $response->assertSee('<course-finder', false);
    }
```

Note: the old assertion `assertSee('text-brand-dark-green', false)` was removed because the photo hero uses white text and the course-finder section has no dark-green text in the HTML output.

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=CoursesFinderRedesignTest'`
Expected: `test_courses_page_uses_redesign_sections` FAIL — page still uses `x-subpage.hero`, no `data-component="subpage-hero-photo"`.

- [ ] **Step 3: Update the courses page hero**

In `resources/views/pages/finder/courses.blade.php`, replace the `<x-subpage.hero ... />` block (currently lines 8-21) with:

```blade
    <x-subpage.hero-photo
      eyebrow="Kurzy a programy"
      title="Najdi kurz, který"
      accent="sedne právě tobě"
      text="Procházej programy irských univerzit a škol. Filtruj podle školy, oboru a úrovně studia — výsledky se aktualizují bez načítání stránky."
      :photo="[
        'src' => 'img/static/irsko_dublin_hero.jpg',
        'sizes' => '(max-width: 768px) 100vw, 1280px',
        'alt' => 'Dublin, Irsko',
      ]"
      :stats="[
        ['value' => '1 000+', 'label' => 'programů v databázi'],
        ['value' => '25+', 'label' => 'škol a univerzit'],
        ['value' => 'CS', 'label' => 'české popisy oborů'],
      ]"
    />
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=CoursesFinderRedesignTest'`
Expected: 3 passed.

- [ ] **Step 5: Verify the live courses page loads**

Run: `curl -s -o /dev/null -w "%{http_code}\n" -H "Host: irskostudy-redesign.local" http://127.0.0.1:8080/kurzy`
Expected: `200`

- [ ] **Step 6: Commit**

```bash
git add resources/views/pages/finder/courses.blade.php tests/Feature/CoursesFinderRedesignTest.php
git commit -m "fix: use photo hero on courses finder"
```

---

### Task 4: Create `x-subpage.hero-light` component

**Files:**
- Create: `resources/views/components/subpage/hero-light.blade.php`
- Test: `tests/Feature/HeroLightComponentTest.php`

**Interfaces:**
- Produces: component `x-subpage.hero-light` with props `eyebrow`, `title`, `accent`, `text`, `stats`; renders `data-component="subpage-hero-light"`, no `bg-brand-dark-green` anywhere, `text-brand-dark-green` + `text-brand-orange` typography, light stat cards. Consumed by the four inner pages (Task 5).

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class HeroLightComponentTest extends TestCase
{
    public function test_hero_light_has_no_dark_green_surface(): void
    {
        $html = (string) view('components.subpage.hero-light', [
            'eyebrow' => 'Kontakt',
            'title' => 'Napiš nám a domluv si',
            'accent' => 'konzultaci zdarma',
            'text' => 'Ozveme se s dalším krokem.',
            'stats' => [
                ['value' => '30 min', 'label' => 'úvodní rozhovor'],
                ['value' => '0 Kč', 'label' => 'nezávazný začátek'],
            ],
        ])->render();

        $this->assertStringContainsString('data-component="subpage-hero-light"', $html);
        $this->assertStringNotContainsString('bg-brand-dark-green', $html);
        $this->assertStringContainsString('text-brand-dark-green', $html);
        $this->assertStringContainsString('text-brand-orange', $html);
        $this->assertStringContainsString('30 min', $html);
        $this->assertStringContainsString('úvodní rozhovor', $html);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=HeroLightComponentTest'`
Expected: FAIL — view `components.subpage.hero-light` not found.

- [ ] **Step 3: Create the component**

```blade
@props([
  'eyebrow' => null,
  'title' => null,
  'accent' => null,
  'text' => null,
  'stats' => [],
])

<section data-component="subpage-hero-light" class="bg-base-white pt-12 md:pt-16">
  <div class="layout-container">
    <div class="max-w-[940px]">
      @if($eyebrow)
        <p class="type-text-md-semibold text-brand-orange">{{ $eyebrow }}</p>
      @endif
      <h1 class="type-display-xl mt-3 text-brand-dark-green">
        {{ $title }} @if($accent)<span class="text-brand-orange">{{ $accent }}</span>@endif
      </h1>
      @if($text)
        <p class="type-text-lg mt-4 max-w-[760px] text-brand-dark-green">{{ $text }}</p>
      @endif
    </div>

    @if(count($stats))
      <div class="mt-10 grid max-w-[940px] gap-4 sm:grid-cols-3">
        @foreach($stats as $stat)
          <div class="rounded-[12px] bg-brand-light-gray p-5">
            <p class="type-display-xs text-brand-dark-green">{{ $stat['value'] }}</p>
            <p class="type-text-sm mt-1 text-brand-dark-green">{{ $stat['label'] }}</p>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=HeroLightComponentTest'`
Expected: 1 passed.

- [ ] **Step 5: Commit**

```bash
git add resources/views/components/subpage/hero-light.blade.php tests/Feature/HeroLightComponentTest.php
git commit -m "feat: add light hero component for inner pages"
```

---

### Task 5: Switch contact, privacy, FAQ, and sitemap to `x-subpage.hero-light`

**Files:**
- Modify: `resources/views/pages/contact.blade.php:8-22` (hero block)
- Modify: `resources/views/pages/privacy-cs.blade.php:81-95` (hero block)
- Modify: `resources/views/pages/faq.blade.php:8-22` (hero block)
- Modify: `resources/views/pages/sitemap.blade.php:8-22` (hero block)
- Test: `tests/Feature/ContactPageRedesignTest.php` (extend)
- Test: `tests/Feature/PrivacyPageRedesignTest.php` (extend)
- Test: `tests/Feature/FaqPageRedesignTest.php` (extend)
- Test: `tests/Feature/SitemapPageRedesignTest.php` (extend)

**Interfaces:**
- Consumes: `x-subpage.hero-light` (Task 4).
- Produces: each of the four pages renders `data-component="subpage-hero-light"` in place of the previous green hero. All existing redesign markers and content stay.

- [ ] **Step 1: Extend the failing tests**

Append to each of the four test classes a new test:

In `tests/Feature/ContactPageRedesignTest.php`:

```php
    public function test_contact_page_uses_light_hero(): void
    {
        $response = $this->get('/kontakt');

        $response->assertOk();
        $response->assertSee('data-component="subpage-hero-light"', false);
    }
```

In `tests/Feature/PrivacyPageRedesignTest.php`:

```php
    public function test_privacy_page_uses_light_hero(): void
    {
        $response = $this->get('/ochrana-soukromi');

        $response->assertOk();
        $response->assertSee('data-component="subpage-hero-light"', false);
    }
```

In `tests/Feature/FaqPageRedesignTest.php`:

```php
    public function test_faq_page_uses_light_hero(): void
    {
        $response = $this->get('/faq');

        $response->assertOk();
        $response->assertSee('data-component="subpage-hero-light"', false);
    }
```

In `tests/Feature/SitemapPageRedesignTest.php`:

```php
    public function test_sitemap_page_uses_light_hero(): void
    {
        $posts = new Collection();

        $response = $this->view('pages.sitemap', ['posts' => $posts]);

        $response->assertSee('data-component="subpage-hero-light"', false);
    }
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter="ContactPageRedesignTest|PrivacyPageRedesignTest|FaqPageRedesignTest|SitemapPageRedesignTest"'`
Expected: the four new tests FAIL (pages still use `x-subpage.hero`); existing tests still pass.

- [ ] **Step 3: Update the four pages**

In each page, replace the `<x-subpage.hero ... />` block with an identical `<x-subpage.hero-light ... />` block (same `eyebrow`, `title`, `accent`, `text`, `:stats="..."` — only the component name changes).

- [ ] **Step 4: Run tests to verify they pass**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter="ContactPageRedesignTest|PrivacyPageRedesignTest|FaqPageRedesignTest|SitemapPageRedesignTest"'`
Expected: all pass.

- [ ] **Step 5: Verify the live pages load**

Run:
```bash
for path in /kontakt /ochrana-soukromi /faq /sitemap; do
  echo "$path -> $(curl -s -o /dev/null -w '%{http_code}' -H 'Host: irskostudy-redesign.local' http://127.0.0.1:8080$path)"
done
```
Expected: all `200`.

- [ ] **Step 6: Commit**

```bash
git add resources/views/pages/contact.blade.php resources/views/pages/privacy-cs.blade.php resources/views/pages/faq.blade.php resources/views/pages/sitemap.blade.php tests/Feature/ContactPageRedesignTest.php tests/Feature/PrivacyPageRedesignTest.php tests/Feature/FaqPageRedesignTest.php tests/Feature/SitemapPageRedesignTest.php
git commit -m "fix: use light hero on inner pages"
```

---

### Task 6: Course detail hero → photo-under-green

**Files:**
- Modify: `resources/views/pages/finder/course.blade.php:26-60` (hero section)
- Test: `tests/Feature/CourseDetailRedesignTest.php` (extend)

**Interfaces:**
- Consumes: `x-subpage.university-image` (existing component) for the school photo.
- Produces: `course-detail-hero` section with a full-bleed school image + `bg-gradient-to-b from-black/10 via-black/35 to-black/60` overlay and no right-column image card.

- [ ] **Step 1: Extend the failing test**

Append to `tests/Feature/CourseDetailRedesignTest.php`:

```php
    public function test_course_detail_hero_uses_photo_overlay(): void
    {
        $response = $this->view('pages.finder.course', [
            'course' => $this->course(),
            'relatedCourses' => new Collection(),
        ]);

        $response->assertSee('from-black/10', false);
        $response->assertSee('uni-atu.jpg', false);

        $blade = file_get_contents(resource_path('views/pages/finder/course.blade.php'));
        $this->assertStringNotContainsString('hidden lg:block', $blade);
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=CourseDetailRedesignTest'`
Expected: the new test FAILS (`from-black/10` and `uni-atu.jpg` absent, `hidden lg:block` still present).

- [ ] **Step 3: Update the course detail hero**

In `resources/views/pages/finder/course.blade.php`, inside `data-redesign-section="course-detail-hero"`, replace the decorative overlay div

```blade
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_14%_22%,rgba(255,120,46,0.26),transparent_28%),radial-gradient(circle_at_88%_18%,rgba(156,204,87,0.2),transparent_30%)]"></div>
```

with

```blade
      <div class="absolute inset-0">
        <x-subpage.university-image
          :school-id="$course->school->school_id ?? null"
          :name="$course->school->name ?? 'Univerzita'"
          img-class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/35 to-black/60"></div>
      </div>
```

Then delete the right-column image card block:

```blade
          <div class="hidden lg:block">
            <x-subpage.university-image
              :school-id="$course->school->school_id ?? null"
              :name="$course->school->name ?? 'Univerzita'"
              img-class="h-[360px] w-full object-cover ring-1 ring-white/20"
            />
          </div>
```

Leave the rest of the hero (school pill, title, `title_en`, three stat tiles) unchanged.

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=CourseDetailRedesignTest'`
Expected: 5 passed.

- [ ] **Step 5: Verify a live course page loads**

Run: `curl -s -o /dev/null -w "%{http_code}\n" -H "Host: irskostudy-redesign.local" http://127.0.0.1:8080/kurzy/prvni-rocnik-umeni-a-designu`
Expected: `200`

- [ ] **Step 6: Commit**

```bash
git add resources/views/pages/finder/course.blade.php tests/Feature/CourseDetailRedesignTest.php
git commit -m "fix: use photo-under-green hero on course detail"
```

---

### Task 7: School detail hero → photo-under-green

**Files:**
- Modify: `resources/views/pages/finder/school.blade.php:7-38` (hero section)
- Test: `tests/Feature/SchoolDetailRedesignTest.php` (extend)

**Interfaces:**
- Consumes: `x-subpage.university-image` for the school photo.
- Produces: `school-detail-hero` section with a full-bleed school image + black gradient overlay and no right-column image card.

- [ ] **Step 1: Extend the failing test**

Append to `tests/Feature/SchoolDetailRedesignTest.php`:

```php
    public function test_school_detail_hero_uses_photo_overlay(): void
    {
        $response = $this->view('pages.finder.school', ['school' => $this->school()]);

        $response->assertSee('from-black/10', false);
        $response->assertSee('uni-atu.jpg', false);

        $blade = file_get_contents(resource_path('views/pages/finder/school.blade.php'));
        $this->assertStringNotContainsString('hidden lg:block', $blade);
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=SchoolDetailRedesignTest'`
Expected: the new test FAILS.

- [ ] **Step 3: Update the school detail hero**

In `resources/views/pages/finder/school.blade.php`, inside `data-redesign-section="school-detail-hero"`, replace the decorative overlay div

```blade
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_14%_22%,rgba(255,120,46,0.26),transparent_28%),radial-gradient(circle_at_88%_18%,rgba(156,204,87,0.2),transparent_30%)]"></div>
```

with

```blade
      <div class="absolute inset-0">
        <x-subpage.university-image
          :school-id="$school->school_id ?? null"
          :name="$school->name ?? 'Univerzita'"
          img-class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/35 to-black/60"></div>
      </div>
```

Then delete the right-column image card block:

```blade
          <div class="hidden lg:block">
            <x-subpage.university-image
              :school-id="$school->school_id ?? null"
              :name="$school->name ?? 'Univerzita'"
              img-class="h-[360px] w-full object-cover ring-1 ring-white/20"
            />
          </div>
```

Leave the rest of the hero unchanged.

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=SchoolDetailRedesignTest'`
Expected: 4 passed.

- [ ] **Step 5: Verify a live school page loads**

Run: `curl -s -o /dev/null -w "%{http_code}\n" -H "Host: irskostudy-redesign.local" http://127.0.0.1:8080/vysoke-skoly/university-of-galway`
Expected: `200`

- [ ] **Step 6: Commit**

```bash
git add resources/views/pages/finder/school.blade.php tests/Feature/SchoolDetailRedesignTest.php
git commit -m "fix: use photo-under-green hero on school detail"
```

---

### Task 8: Final regression + live verification

**Files:** none (verification only)

- [ ] **Step 1: Run all Phase 1 + hero tests**

```bash
docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter="RoutesCleanupTest|UniversitySlideshowAssetsTest|PrivacyPageRedesignTest|SitemapPageRedesignTest|ContactPageRedesignTest|FaqPageRedesignTest|CoursesFinderRedesignTest|CourseDetailRedesignTest|SchoolDetailRedesignTest|KurzyNavLinksTest|HomepageRedesignTest|BlogRedesignTest|HeroStaticAssetsTest|HeroPhotoComponentTest|HeroLightComponentTest"'
```
Expected: all pass.

- [ ] **Step 2: Verify live pages**

```bash
for path in / /kurzy /kontakt /faq /ochrana-soukromi /sitemap /vysoke-skoly /blog /vysoke-skoly/university-of-galway /kurzy/prvni-rocnik-umeni-a-designu /img/static/irsko_dublin_hero-2x.jpg; do
  echo "$path -> $(curl -s -o /dev/null -w '%{http_code}' -H 'Host: irskostudy-redesign.local' http://127.0.0.1:8080$path)"
done
```
Expected: all `200`.

- [ ] **Step 3: Verify git status (only gitignored assets + docs untracked)**

Run: `git -C /home/david/Development/sites/irskostudy_cz_redesign status --short`
Expected: only `?? docs/superpowers/` (or nothing). Images under `public/img` do not appear (gitignored).

---

## Final Notes

- After the last task, `git status` should be clean except gitignored assets (`public/img`, `public/build`) and the `docs/superpowers/` plan/spec files.
- Known unrelated failure: `Tests\Feature\LeadTest` (sqlite `information_schema`); do not attempt to fix in this plan.
- `about`, `universities`, `why`, `services` keep the existing `x-subpage.hero` (green + blobs) — out of scope, candidate follow-up.
- No frontend rebuild is required for any task in this plan (Blade-only changes).
