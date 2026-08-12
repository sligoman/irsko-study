# Phase 1 Launch Fixes Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Fix Phase 1 launch blockers on the irskostudy_cz_redesign worktree: remove duplicate/welcome routes, repair university slideshow assets, and redesign privacy, sitemap, contact, FAQ, and finder (courses/course/school) pages to match the irsko-style redesign patterns.

**Architecture:** Blade views extend `layouts.app` (already renders navbar/footer/Vue root `#app`). Inner pages reuse existing `x-subpage.*` components, CSS tokens (`brand-dark-green`, `brand-orange`, `brand-light-green`, `brand-light-gray`, `type-*` utilities, `layout-container`, `home-section`), and the existing `<contact-form>` Vue component. No new libraries, no Alpine. University slideshow JSON keeps its 5 entries; the two missing images are copied from `public/img/blog/large/` into `public/img/universities/`. A new reusable `x-subpage.university-image` component centralizes the (currently broken) school-hero image lookup. Finders are restyled in place (Vue), not rebuilt.

**Tech Stack:** Laravel 11, Blade, Vue 3 (finders, contact form), Tailwind CSS v4 (token utilities), MariaDB (CAO finder data), Docker PHP container `server_php`.

## Global Constraints

- Worktree: `/home/david/Development/sites/irskostudy_cz_redesign`, branch `redesign`.
- Preserve all existing content and URLs; never rename a public route.
- No new routes. Route changes are deletions only.
- Use existing assets/images; no new external images.
- Reduce dark-green backgrounds on inner content; prefer `bg-base-white`/`bg-brand-light-gray` surfaces with dark-green typography and `brand-orange` accents.
- No Alpine.js anywhere — Vue only.
- `public/img` is gitignored; copied images stay local-only (production serves its own assets).
- Tests run inside the Docker PHP container:
  `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=...'`
- Frontend build (node v24 + node_modules present in the PHP container):
  `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && npm run build'`
- Full `php artisan test` has one unrelated failure (`Tests\Feature\LeadTest` — sqlite `information_schema`). Run only focused filters; never claim full-suite green.
- Preview: `http://irskostudy-redesign.local:8080` or `curl -H "Host: irskostudy-redesign.local" http://127.0.0.1:8080/...`
- Commit style follows repo history: short lowercase `fix: ...` / `feat: ...` messages.

---

### Task 1: Remove duplicate `/` and `/welcome` routes

**Files:**
- Modify: `routes/web.php:9-19`
- Test: `tests/Feature/RoutesCleanupTest.php`

**Interfaces:**
- Produces: single `GET /` route named `home` (serves `pages.home`); no `/welcome`; no anonymous `view('welcome')` route.

Background: `routes/web.php` lines 9-16 define `/welcome` (plain text) and an anonymous `Route::get('/', view('welcome'))`. Line 19 defines `Route::view('/', 'pages.home')->name('home')`. The anonymous `/` is dead weight that can shadow the real home in some environments; `/welcome` is a leftover Laravel scaffold route. Both must go.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutesCleanupTest extends TestCase
{
    public function test_welcome_route_is_removed(): void
    {
        $this->get('/welcome')->assertNotFound();
    }

    public function test_root_serves_redesign_home(): void
    {
        $this->get('/')->assertOk();
        $this->get('/')->assertSee('data-redesign-section="hero-stats"', false);
    }

    public function test_web_routes_have_single_root_definition(): void
    {
        $routes = file_get_contents(base_path('routes/web.php'));

        $this->assertStringContainsString("Route::view('/', 'pages.home')->name('home');", $routes);
        $this->assertStringNotContainsString("return view('welcome');", $routes);
        $this->assertStringNotContainsString("'/welcome'", $routes);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=RoutesCleanupTest'`
Expected: `test_welcome_route_is_removed` and `test_web_routes_have_single_root_definition` FAIL (route + file assertions).

- [ ] **Step 3: Edit `routes/web.php`**

Delete lines 9-16 (the `//welcome` block: the `/welcome` route and the anonymous `Route::get('/', ...)`). Keep the `// Static pages` block starting at the former line 18.

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FinderController;

// Static pages (blade views in resources/views/pages)
Route::view('/', 'pages.home')->name('home');
Route::view('/o-nas', 'pages.about')->name('about');
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=RoutesCleanupTest'`
Expected: 3 passed.

- [ ] **Step 5: Commit**

```bash
git add routes/web.php tests/Feature/RoutesCleanupTest.php
git commit -m "fix: remove duplicate root and welcome routes"
```

---

### Task 2: Repair university slideshow assets

**Files:**
- Create (copy): `public/img/universities/uni-tu.jpeg`
- Create (copy): `public/img/universities/uni-galway.jpeg`
- Modify: `routes/web.php:31-39` (remove storage-backed JSON route)
- Test: `tests/Feature/UniversitySlideshowAssetsTest.php`

**Interfaces:**
- Consumes: `public/img/universities/universities.json` (5 entries referencing `uni-atu.jpg`, `uni-dcu.jpg`, `uni-trinity.png`, `uni-tu.jpeg`, `uni-galway.jpeg`).
- Produces: every file referenced by that JSON exists under `public/img/universities/`; the dead `storage_path` JSON route is gone (the public file is served by nginx/`public` and the Vue component fetches `/img/universities/universities.json`).

Background: `public/img/universities/universities.json` lists 5 schools but only `uni-atu.jpg`, `uni-dcu.jpg`, `uni-trinity.png` exist there. `uni-tu.jpeg` and `uni-galway.jpeg` exist in `public/img/blog/large/` and must be copied. The route at `routes/web.php:31-39` reads `storage_path('app/universities/universities.json')` which does not exist (would 404 if ever reached); nginx already serves the public file (curl returns 200), so the route is dead code and must be removed.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class UniversitySlideshowAssetsTest extends TestCase
{
    public function test_every_json_entry_has_an_asset_file(): void
    {
        $json = json_decode(
            file_get_contents(public_path('img/universities/universities.json')),
            true
        );

        $this->assertNotEmpty($json);

        foreach ($json as $slide) {
            $this->assertArrayHasKey('file', $slide);
            $this->assertFileExists(
                public_path('img/universities/' . $slide['file'])
            );
        }
    }

    public function test_universities_json_route_no_longer_reads_storage(): void
    {
        $routes = file_get_contents(base_path('routes/web.php'));

        $this->assertStringNotContainsString(
            "storage_path('app/universities/universities.json')",
            $routes
        );
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=UniversitySlideshowAssetsTest'`
Expected: `test_every_json_entry_has_an_asset_file` FAIL (missing `uni-tu.jpeg` / `uni-galway.jpeg`); `test_universities_json_route_no_longer_reads_storage` FAIL.

- [ ] **Step 3: Copy the two images and remove the dead route**

```bash
cp "/home/david/Development/sites/irskostudy_cz_redesign/public/img/blog/large/uni-tu.jpeg" "/home/david/Development/sites/irskostudy_cz_redesign/public/img/universities/uni-tu.jpeg"
cp "/home/david/Development/sites/irskostudy_cz_redesign/public/img/blog/large/uni-galway.jpeg" "/home/david/Development/sites/irskostudy_cz_redesign/public/img/universities/uni-galway.jpeg"
```

In `routes/web.php`, delete lines 31-39 (the block):

```php
// Serve universities JSON from storage so the frontend can fetch it from the same URL
Route::get('/img/universities/universities.json', function () {
    $path = storage_path('app/universities/universities.json');
    if (!file_exists($path)) {
        abort(404);
    }
    $content = file_get_contents($path);
    return response($content, 200)->header('Content-Type', 'application/json');
});
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=UniversitySlideshowAssetsTest'`
Expected: 2 passed.

- [ ] **Step 5: Verify the slideshow JSON endpoint still serves**

Run: `curl -s -o /dev/null -w "%{http_code}\n" -H "Host: irskostudy-redesign.local" http://127.0.0.1:8080/img/universities/universities.json`
Expected: `200`

- [ ] **Step 6: Commit**

Note: the two copied images are gitignored (`public/img`); they will not appear in `git status`. Commit only the route + test.

```bash
git add routes/web.php tests/Feature/UniversitySlideshowAssetsTest.php
git commit -m "fix: repair university slideshow assets and drop dead json route"
```

---

### Task 3: Redesign the privacy page

**Files:**
- Modify: `resources/views/pages/privacy-cs.blade.php` (full rewrite)
- Test: `tests/Feature/PrivacyPageRedesignTest.php`

**Interfaces:**
- Consumes: `config('contacts.company_name')`, `config('contacts.address')`, `config('contacts.email')`, `x-subpage.hero`.
- Produces: `data-redesign-page="privacy"`, section markers `data-redesign-section="privacy-content"`, dark-green-on-light legal content with the real contact email (no `privacy@irsko.ie` placeholder).

Background: current file is a placeholder legal page (`privacy@irsko.ie`, "upravte na reálný kontakt"). Model the rewrite on `irsko_ie_web/resources/views/pages/redesign/ochrana-udaju.blade.php` structure but single-language (CS), using irskostudy config values and `config('contacts.email')` (default `study@irsko.ie`).

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class PrivacyPageRedesignTest extends TestCase
{
    public function test_privacy_page_uses_redesign_structure_and_real_contact(): void
    {
        $response = $this->get('/ochrana-soukromi');

        $response->assertOk();
        $response->assertSee('data-redesign-page="privacy"', false);
        $response->assertSee('data-redesign-section="privacy-content"', false);
        $response->assertSee('text-brand-dark-green', false);
        $response->assertSee('text-brand-orange', false);

        $this->assertStringContainsString(
            config('contacts.email'),
            $response->getContent()
        );
    }

    public function test_privacy_page_has_no_placeholder_contact(): void
    {
        $response = $this->get('/ochrana-soukromi');

        $this->assertStringNotContainsString('privacy@irsko.ie', $response->getContent());
        $this->assertStringNotContainsString('upravte na reálný kontakt', $response->getContent());
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=PrivacyPageRedesignTest'`
Expected: FAIL (no `data-redesign-page="privacy"`; placeholder still present).

- [ ] **Step 3: Rewrite `resources/views/pages/privacy-cs.blade.php`**

```blade
@extends('layouts.app')

@section('title', 'Ochrana osobních údajů')
@section('meta_description', 'Informace o zpracování osobních údajů a ochraně soukromí na IrskoStudy — jak a proč shromažďujeme data.')

@php
  $sections = [
    [
      'title' => '1) Kdo je správcem osobních údajů',
      'paragraphs' => [
        'Správcem osobních údajů je provozovatel webu ' . config('contacts.company_name') . ', sídlo: ' . config('contacts.address') . '.',
        'Ve většině případů vystupujeme jako správce, protože určujeme, za jakým účelem a jakým způsobem budou údaje zpracovávány.',
      ],
    ],
    [
      'title' => '2) Jaké osobní údaje zpracováváme',
      'list' => [
        'Údaje z kontaktních formulářů: jméno, e-mail, telefon a zpráva.',
        'Informace o tvém studijním plánu, které nám sdělíš během konzultace.',
        'Technická metadata (IP adresa, typ prohlížeče) zaznamenaná v logu pro bezpečnost a analytiku.',
        'Cookies a nástroje pro analytiku (pokud jsou aktivní).',
      ],
    ],
    [
      'title' => '3) K čemu údaje používáme',
      'paragraphs' => [
        'Údaje slouží k odpovědi na dotazy, přípravě nabídky studia, komunikaci se školami a partnerům v Irsku, technickému provozu webu a zlepšování služeb.',
        'Kontaktní zprávy mohou být ukládány v logu nebo odeslány na e-mail správce.',
      ],
    ],
    [
      'title' => '4) Komu mohou být údaje předány',
      'list' => [
        'Místním partnerům v Irsku: vysokým školám, univerzitám a ubytovacím zařízením.',
        'Dodavatelům: účetním, IT a administrativním poskytovatelům.',
        'Technologickým partnerům: poskytovatelům analytických a cloudových nástrojů.',
      ],
    ],
    [
      'title' => '5) Jak dlouho údaje uchováváme',
      'paragraphs' => [
        'Údaje uchováváme pouze po dobu nutnou pro daný účel — typicky do vyřízení dotazu, po dobu trvání spolupráce, případně po dobu stanovenou zákonem (např. 10 let u daňových dokladů).',
      ],
    ],
    [
      'title' => '6) Jaká máte práva',
      'list' => [
        'Právo na přístup k údajům a informace o jejich zpracování.',
        'Právo na opravu nepřesných údajů.',
        'Právo na výmaz („právo být zapomenut“).',
        'Právo na omezení zpracování.',
        'Právo na přenositelnost údajů.',
        'Právo vznést námitku proti zpracování.',
        'Právo kdykoliv odvolat souhlas.',
      ],
    ],
    [
      'title' => '7) Jak můžete svá práva uplatnit',
      'paragraphs' => [
        'Své žádosti zasílejte na e-mail ' . config('contacts.email') . '. Na vaši žádost odpovíme bez zbytečného odkladu, nejpozději do jednoho měsíce.',
        'Pokud se domníváte, že vaše údaje zpracováváme v rozporu s předpisy, máte právo podat stížnost u dozorového úřadu (Data Protection Commission, Ireland).',
      ],
    ],
    [
      'title' => '8) Cookies a zabezpečení',
      'paragraphs' => [
        'Web používá HTTPS a přijímá rozumná technická opatření k ochraně dat. Nezbytné cookies používáme pro provoz webu, ostatní (analytické) pouze s vaším souhlasem.',
      ],
    ],
    [
      'title' => '9) Změny a aktualizace',
      'paragraphs' => [
        'Tento dokument můžeme průběžně aktualizovat. Aktuální verze je vždy dostupná na této stránce.',
      ],
    ],
  ];
@endphp

@section('content')
  <main data-redesign-page="privacy" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Právní dokumenty"
      title="Ochrana osobních"
      accent="údajů"
      text="Vysvětlujeme, jaké osobní údaje zpracováváme, k jakým účelům, jak dlouho je uchováváme, komu je můžeme předat a jaká máte práva."
      :stats="[
        ['value' => 'GDPR', 'label' => 'zpracování v souladu s předpisy'],
        ['value' => 'CS', 'label' => 'aktuální znění dokumentu'],
        ['value' => '30 dní', 'label' => 'lhůta pro odpověď na žádost'],
      ]"
    />

    <section data-redesign-section="privacy-content" class="home-section pb-20">
      <div class="layout-container">
        <div class="mx-auto max-w-[920px] space-y-4">
          @foreach($sections as $section)
            <section class="rounded-[16px] bg-brand-light-gray p-6 md:p-8">
              <h2 class="type-display-sm text-brand-dark-green">{{ $section['title'] }}</h2>

              @foreach($section['paragraphs'] ?? [] as $paragraph)
                <p class="type-text-md mt-4 text-brand-dark-green">
                  @if(str_contains($paragraph, config('contacts.email')))
                    {!! str_replace(
                        config('contacts.email'),
                        '<a href="mailto:' . config('contacts.email') . '" class="underline underline-offset-4">' . config('contacts.email') . '</a>',
                        e($paragraph)
                    ) !!}
                  @else
                    {{ $paragraph }}
                  @endif
                </p>
              @endforeach

              @if(!empty($section['list']))
                <ul class="type-text-md mt-4 list-disc space-y-2 pl-6 text-brand-dark-green">
                  @foreach($section['list'] as $item)
                    <li>{{ $item }}</li>
                  @endforeach
                </ul>
              @endif
            </section>
          @endforeach

          <section class="rounded-[16px] bg-brand-light-gray p-6 md:p-8">
            <p class="type-text-md text-brand-dark-green">Poslední aktualizace: 8. srpna 2026</p>
          </section>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=PrivacyPageRedesignTest'`
Expected: 2 passed.

- [ ] **Step 5: Commit**

```bash
git add resources/views/pages/privacy-cs.blade.php tests/Feature/PrivacyPageRedesignTest.php
git commit -m "fix: redesign privacy page with real contact details"
```

---

### Task 4: Redesign the sitemap page

**Files:**
- Modify: `resources/views/pages/sitemap.blade.php` (full rewrite)
- Test: `tests/Feature/SitemapPageRedesignTest.php`

**Interfaces:**
- Consumes: `$posts` collection (AiblogPost), route names `home`, `about`, `why`, `universities`, `finder.courses`, `services`, `faq`, `contact`, `privacy.cs`, `blog.show`.
- Produces: single clean Blade file (no duplicated `@extends`/`@section` blocks), `data-redesign-page="sitemap"`, includes `Kurzy` link.

Background: current file has the entire page duplicated (two `@extends` + two `@section('content')` blocks). Rewrite as one clean redesign page.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Tests\TestCase;

class SitemapPageRedesignTest extends TestCase
{
    public function test_sitemap_page_is_single_clean_redesign(): void
    {
        $posts = new Collection();

        $response = $this->view('pages.sitemap', ['posts' => $posts]);

        $response->assertSee('data-redesign-page="sitemap"', false);
        $response->assertSee('data-redesign-section="sitemap-links"', false);
        $response->assertSee('text-brand-dark-green', false);
        $response->assertSee('Kurzy a programy', false);
        $response->assertSee(route('finder.courses'), false);
    }

    public function test_sitemap_blade_has_no_duplicate_layout_blocks(): void
    {
        $blade = file_get_contents(resource_path('views/pages/sitemap.blade.php'));

        $this->assertStringContainsString("@extends('layouts.app')", $blade);
        $this->assertEquals(1, substr_count($blade, "@extends('layouts.app')"));
        $this->assertEquals(1, substr_count($blade, "@section('content')"));
    }

    public function test_sitemap_lists_blog_posts(): void
    {
        $posts = collect([
            (object) ['slug' => 'jak-studovat', 'title' => 'Jak studovat v Irsku'],
        ]);

        $response = $this->view('pages.sitemap', ['posts' => $posts]);

        $response->assertSee('Jak studovat v Irsku', false);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=SitemapPageRedesignTest'`
Expected: FAIL (duplicate blocks, no markers, no Kurzy link).

- [ ] **Step 3: Rewrite `resources/views/pages/sitemap.blade.php`**

```blade
@extends('layouts.app')

@section('title', 'Sitemap — Irsko Study')
@section('meta_description', 'Mapa stránek IrskoStudy — seznam veřejných stránek a článků pro snadnou orientaci.')

@section('content')
  <main data-redesign-page="sitemap" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Mapa stránek"
      title="Najdeš tady"
      accent="vše"
      text="Přehled všech důležitých stránek a článků na IrskoStudy. Klikni na odkaz a pokračuj tam, kam potřebuješ."
      :stats="[
        ['value' => '10+', 'label' => 'hlavních stránek'],
        ['value' => 'CS', 'label' => 'přehledné členění'],
        ['value' => 'Blog', 'label' => 'články z praxe'],
      ]"
    />

    <section data-redesign-section="sitemap-links" class="home-section pb-20">
      <div class="layout-container">
        <div class="grid gap-5 lg:grid-cols-2">
          <div class="rounded-[16px] bg-brand-light-gray p-6 md:p-10">
            <h2 class="type-display-lg text-brand-dark-green">Hlavní stránky</h2>
            <ul class="mt-6 space-y-3">
              @foreach([
                ['label' => 'Domů', 'href' => route('home')],
                ['label' => 'O nás', 'href' => route('about')],
                ['label' => 'Proč Irsko', 'href' => route('why')],
                ['label' => 'Vysoké školy', 'href' => route('universities')],
                ['label' => 'Kurzy a programy', 'href' => route('finder.courses')],
                ['label' => 'Služby', 'href' => route('services')],
                ['label' => 'FAQ', 'href' => route('faq')],
                ['label' => 'Kontakt', 'href' => route('contact')],
                ['label' => 'Ochrana osobních údajů', 'href' => route('privacy.cs')],
                ['label' => 'Sitemap (XML)', 'href' => url('/sitemap.xml')],
              ] as $link)
                <li>
                  <a href="{{ $link['href'] }}" class="type-text-md text-brand-dark-green underline underline-offset-4 hover:text-brand-orange">{{ $link['label'] }}</a>
                </li>
              @endforeach
            </ul>
          </div>

          <div class="rounded-[16px] bg-brand-light-gray p-6 md:p-10">
            <h2 class="type-display-lg text-brand-dark-green">Blog a novinky</h2>
            @if($posts->count())
              <ul class="mt-6 space-y-3">
                @foreach($posts as $post)
                  <li>
                    <a href="{{ route('blog.show', $post->slug) }}" class="type-text-md text-brand-dark-green underline underline-offset-4 hover:text-brand-orange">{{ $post->title }}</a>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="type-text-md mt-6 text-brand-dark-green">Žádné články k zobrazení.</p>
            @endif
          </div>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=SitemapPageRedesignTest'`
Expected: 3 passed.

- [ ] **Step 5: Commit**

```bash
git add resources/views/pages/sitemap.blade.php tests/Feature/SitemapPageRedesignTest.php
git commit -m "fix: redesign sitemap page and remove duplicated markup"
```

---

### Task 5: Redesign the contact page

**Files:**
- Modify: `resources/views/pages/contact.blade.php` (full rewrite)
- Test: `tests/Feature/ContactPageRedesignTest.php`

**Interfaces:**
- Consumes: `config('contacts.mobile')`, `config('contacts.email')`, `config('contacts.address')`, `<contact-form>` Vue component (position `bottom`), `x-subpage.hero`, `components/cta`.
- Produces: `data-redesign-page="contact"`, sections `contact-info` + `contact-form`, dark-green-on-light contact cards, working `<contact-form>`.

Background: current page is bare (heading + form). Model on `irsko_ie_web/resources/views/pages/redesign/kontakty.blade.php` but reusing irskostudy's `<contact-form>` Vue component and `x-subpage.hero`.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactPageRedesignTest extends TestCase
{
    public function test_contact_page_uses_redesign_sections(): void
    {
        $response = $this->get('/kontakt');

        $response->assertOk();
        $response->assertSee('data-redesign-page="contact"', false);
        $response->assertSee('data-redesign-section="contact-info"', false);
        $response->assertSee('data-redesign-section="contact-form"', false);
        $response->assertSee('<contact-form', false);
        $response->assertSee('text-brand-orange', false);
    }

    public function test_contact_page_shows_contact_details(): void
    {
        $response = $this->get('/kontakt');

        $html = $response->getContent();
        $this->assertStringContainsString(config('contacts.mobile'), $html);
        $this->assertStringContainsString(config('contacts.email'), $html);
        $this->assertStringContainsString(config('contacts.address'), $html);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=ContactPageRedesignTest'`
Expected: FAIL (no markers, no contact cards).

- [ ] **Step 3: Rewrite `resources/views/pages/contact.blade.php`**

```blade
@extends('layouts.app')

@section('title', 'Kontakt — Irsko Study')
@section('meta_description', 'Kontaktujte IrskoStudy — napište nám, zavolejte nebo napište na WhatsApp. Rádi poradíme se studiem v Irsku, přihláškami a ubytováním.')

@section('content')
  <main data-redesign-page="contact" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Kontakt"
      title="Napiš nám a domluv si"
      accent="konzultaci zdarma"
      text="Každé velké rozhodnutí začíná rozhovorem. Napiš nám, kde teď jsi a kam se chceš dostat — ozveme se s dalším krokem."
      :stats="[
        ['value' => '30 min', 'label' => 'úvodní rozhovor'],
        ['value' => '0 Kč', 'label' => 'nezávazný začátek'],
        ['value' => 'CS/SK', 'label' => 'komunikace v češtině'],
      ]"
    />

    <section data-redesign-section="contact-info" class="home-section">
      <div class="layout-container">
        <div class="grid gap-5 md:grid-cols-3">
          <a href="tel:{{ config('contacts.mobile') }}" class="rounded-[16px] bg-brand-light-gray p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
            <p class="type-text-sm text-brand-orange">Telefon / WhatsApp</p>
            <p class="type-display-xs mt-2 text-brand-dark-green">{{ config('contacts.mobile') }}</p>
          </a>

          <a href="mailto:{{ config('contacts.email') }}" class="rounded-[16px] bg-brand-light-gray p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
            <p class="type-text-sm text-brand-orange">E-mail</p>
            <p class="type-display-xs mt-2 break-all text-brand-dark-green">{{ config('contacts.email') }}</p>
          </a>

          <div class="rounded-[16px] bg-brand-light-gray p-6">
            <p class="type-text-sm text-brand-orange">Sídlo</p>
            <p class="type-display-xs mt-2 text-brand-dark-green">{{ config('contacts.address') }}</p>
          </div>
        </div>
      </div>
    </section>

    <section data-redesign-section="contact-form" class="home-section">
      <div class="layout-container">
        <div class="grid overflow-hidden rounded-[16px] bg-brand-light-gray lg:grid-cols-2">
          <div class="p-6 md:p-10 lg:p-12">
            <p class="type-text-md-semibold text-brand-orange">Bezplatná konzultace</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Napiš nám, kde teď jsi a kam se chceš dostat</h2>
            <p class="type-text-lg mt-6 text-brand-dark-green">Ozveme se s dalším krokem: doporučíme školy, vysvětlíme termíny a řekneme, co připravit jako první.</p>
          </div>
          <div class="bg-white p-6 md:p-10 lg:p-12">
            <contact-form position="bottom" page="{{ route('contact') }}"></contact-form>
          </div>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=ContactPageRedesignTest'`
Expected: 2 passed.

- [ ] **Step 5: Commit**

```bash
git add resources/views/pages/contact.blade.php tests/Feature/ContactPageRedesignTest.php
git commit -m "fix: redesign contact page with contact cards"
```

---

### Task 6: Redesign the FAQ page

**Files:**
- Modify: `resources/views/pages/faq.blade.php` (full rewrite)
- Test: `tests/Feature/FaqPageRedesignTest.php`

**Interfaces:**
- Consumes: `$items` collection from `FaqController@index` — each item is `['question' => string, 'answer' => HTML string]`.
- Produces: `data-redesign-page="faq"`, `data-redesign-section="faq-list"`, native `<details>`/`<summary>` accordion (no Vue dependency for this page), irsko-style dark-green-on-light.

Background: current page uses the Vue `<faq-accordion>` component with legacy white-card styling. The irsko reference (`faq-section.blade.php`) uses native `<details>`/`<summary>`. Answers are HTML from the DB, so render with `{!! !!}`.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Tests\TestCase;

class FaqPageRedesignTest extends TestCase
{
    public function test_faq_page_uses_redesign_accordion(): void
    {
        $items = new Collection([
            ['question' => 'Musím umět anglicky?', 'answer' => '<p>Základní předpoklad je středně pokročilá angličtina.</p>'],
            ['question' => 'Jak dlouho trvá přijetí?', 'answer' => '<p>Obvykle několik týdnů po uzávěrce.</p>'],
        ]);

        $response = $this->view('pages.faq', ['items' => $items]);

        $response->assertSee('data-redesign-page="faq"', false);
        $response->assertSee('data-redesign-section="faq-list"', false);
        $response->assertSee('<details', false);
        $response->assertSee('<summary', false);
        $response->assertSee('Musím umět anglicky?', false);
        $response->assertSee('Jak dlouho trvá přijetí?', false);
        $response->assertSee('text-brand-dark-green', false);
    }

    public function test_faq_page_no_longer_uses_vue_accordion(): void
    {
        $blade = file_get_contents(resource_path('views/pages/faq.blade.php'));

        $this->assertStringNotContainsString('faq-accordion', $blade);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=FaqPageRedesignTest'`
Expected: FAIL (uses `faq-accordion`, no details markers).

- [ ] **Step 3: Rewrite `resources/views/pages/faq.blade.php`**

```blade
@extends('layouts.app')

@section('title', 'FAQ — Irsko Study')
@section('meta_description', 'Často kladené otázky k přihláškám, studiu a životu v Irsku — rychlé odpovědi na nejčastější dotazy studentů z ČR a SK.')

@section('content')
  <main data-redesign-page="faq" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Časté dotazy"
      title="Na co se nás"
      accent="nejčastěji ptáte"
      text="Připravili jsme odpovědi na nejčastější dotazy k přihláškám, dokumentům, financování a životu v Irsku. Pokud tu nenajdeš odpověď, napiš nám."
      :stats="[
        ['value' => '10+', 'label' => 'odpovědí na časté dotazy'],
        ['value' => '0 Kč', 'label' => 'úvodní konzultace'],
        ['value' => 'CS', 'label' => 'rady od týmu v Irsku'],
      ]"
    />

    <section data-redesign-section="faq-list" class="home-section pb-20">
      <div class="layout-container">
        <div class="grid gap-8 lg:grid-cols-[400px_1fr] lg:gap-16">
          <div class="max-w-[400px]">
            <p class="type-text-md-semibold text-brand-orange">Rychlá navigace</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Co tě nejvíc zajímá</h2>
            <p class="type-text-lg mt-4 text-brand-dark-green">Odpovědi jsou řazené od základů po praktické detaily. Klikni na otázku a odpověď se rozbalí.</p>
          </div>

          <div class="grid gap-4">
            @forelse($items as $faq)
              <details class="group rounded-[8px] bg-brand-light-gray px-6 py-5 transition-color-figma hover:bg-brand-light-green/15" @if($loop->first) open @endif>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 marker:hidden">
                  <span class="type-display-xs text-brand-dark-green">{{ $faq['question'] }}</span>
                  <span class="mt-1 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-light-green text-brand-dark-green transition-transform duration-200 group-open:rotate-180">
                    <svg viewBox="0 0 16 16" class="h-4 w-4" fill="none" aria-hidden="true">
                      <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                </summary>
                <div class="type-text-md mt-3 text-brand-dark-green [&_p]:mt-3 [&_p]:text-brand-dark-green">
                  {!! $faq['answer'] !!}
                </div>
              </details>
            @empty
              <div class="rounded-[16px] bg-brand-light-gray p-8">
                <p class="type-text-lg text-brand-dark-green">Zatím zde nejsou žádné otázky k zobrazení.</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=FaqPageRedesignTest'`
Expected: 2 passed.

- [ ] **Step 5: Verify the live FAQ page still loads (controller data flows)**

Run: `curl -s -o /dev/null -w "%{http_code}\n" -H "Host: irskostudy-redesign.local" http://127.0.0.1:8080/faq`
Expected: `200`

- [ ] **Step 6: Commit**

```bash
git add resources/views/pages/faq.blade.php tests/Feature/FaqPageRedesignTest.php
git commit -m "fix: redesign faq page with native accordion"
```

---

### Task 7: Redesign courses finder page + restyle `course-finder.vue`

**Files:**
- Modify: `resources/views/pages/finder/courses.blade.php` (rewrite)
- Modify: `resources/js/components/course-finder.vue` (restyle in place)
- Test: `tests/Feature/CoursesFinderRedesignTest.php`

**Interfaces:**
- Consumes: `$initialData` (array with `schools`, `fields`, `levels`, `results` paginator, `filters`), `<course-finder>` Vue component, `x-subpage.hero`.
- Produces: `data-redesign-page="courses-finder"`, `data-redesign-section="course-finder"`, no nested `<div id="app">` (layout already provides it), Vue finder restyled with brand tokens (no emerald/gray legacy palette).

Background: the page currently renders `<course-finder>` inside its own `<div id="app">` — a duplicate `id` because `layouts.app` already mounts `#app`. Restyle the Vue component's filters/cards/pagination with brand tokens.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CoursesFinderRedesignTest extends TestCase
{
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
        $response->assertSee('<course-finder', false);
        $response->assertSee('text-brand-dark-green', false);
    }

    public function test_courses_page_has_no_duplicate_app_mount(): void
    {
        $blade = file_get_contents(resource_path('views/pages/finder/courses.blade.php'));

        $this->assertStringNotContainsString('id="app"', $blade);
    }

    public function test_course_finder_vue_uses_brand_tokens(): void
    {
        $vue = file_get_contents(resource_path('js/components/course-finder.vue'));

        $this->assertStringContainsString('brand-dark-green', $vue);
        $this->assertStringContainsString('brand-light-green', $vue);
        $this->assertStringContainsString('brand-orange', $vue);
        $this->assertStringNotContainsString('emerald', $vue);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=CoursesFinderRedesignTest'`
Expected: FAIL (legacy markup, `id="app"`, emerald in vue).

- [ ] **Step 3: Rewrite `resources/views/pages/finder/courses.blade.php`**

```blade
@extends('layouts.app')

@section('title', 'Kurzy a programy v Irsku')
@section('meta_description', 'Přehled kurzů a programů — filtrovat podle školy, oboru a úrovně. Najděte kurz, který vám sedí.')

@section('content')
  <main data-redesign-page="courses-finder" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Kurzy a programy"
      title="Najdi kurz, který"
      accent="sedne právě tobě"
      text="Procházej programy irských univerzit a škol. Filtruj podle školy, oboru a úrovně studia — výsledky se aktualizují bez načítání stránky."
      :stats="[
        ['value' => '1 000+', 'label' => 'programů v databázi'],
        ['value' => '25+', 'label' => 'škol a univerzit'],
        ['value' => 'CS', 'label' => 'české popisy oborů'],
      ]"
    />

    <section data-redesign-section="course-finder" class="home-section pb-20">
      <div class="layout-container">
        <div class="overflow-hidden rounded-[16px] bg-brand-light-gray p-4 md:p-8">
          <course-finder :initial-data='@json($initialData)'></course-finder>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection
```

- [ ] **Step 4: Restyle `resources/js/components/course-finder.vue`**

Replace the `<template>` and `<style>` sections of the existing component (keep the `<script>` block unchanged). New template:

```html
<template>
  <div>
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
      <div class="flex-1">
        <label class="type-text-md font-medium text-brand-dark-green">Hledat</label>
        <input
          v-model="filters.q"
          @input="debouncedSearch"
          type="search"
          placeholder="Např. engineering, business, psychology"
          class="mt-1 block w-full rounded-[8px] border border-brand-dark-green/20 bg-white px-4 py-3 text-brand-dark-green placeholder:text-[var(--color-utility-text-placeholder)] focus:outline-none focus:ring-2 focus:ring-brand-light-green"
        />
      </div>

      <div class="grid w-full grid-cols-1 gap-3 sm:grid-cols-3 lg:w-2/3">
        <div>
          <label class="type-text-md font-medium text-brand-dark-green">Škola</label>
          <select v-model="filters.school" @change="search" class="mt-1 block w-full rounded-[8px] border border-brand-dark-green/20 bg-white px-3 py-3 text-brand-dark-green focus:outline-none focus:ring-2 focus:ring-brand-light-green">
            <option value="">Všechny školy</option>
            <option v-for="s in initial.schools" :key="s.id" :value="s.school_id">{{ s.name }}</option>
          </select>
        </div>

        <div>
          <label class="type-text-md font-medium text-brand-dark-green">Obor</label>
          <select v-model="filters.field" @change="search" class="mt-1 block w-full rounded-[8px] border border-brand-dark-green/20 bg-white px-3 py-3 text-brand-dark-green focus:outline-none focus:ring-2 focus:ring-brand-light-green">
            <option value="">Všechny obory</option>
            <option v-for="f in initial.fields" :key="f.id" :value="f.id">{{ f.description }}</option>
          </select>
        </div>

        <div>
          <label class="type-text-md font-medium text-brand-dark-green">Úroveň</label>
          <select v-model="filters.level" @change="search" class="mt-1 block w-full rounded-[8px] border border-brand-dark-green/20 bg-white px-3 py-3 text-brand-dark-green focus:outline-none focus:ring-2 focus:ring-brand-light-green">
            <option value="">Všechny úrovně</option>
            <option v-for="l in initial.levels" :key="l.id" :value="l.id">{{ l.name }}</option>
          </select>
        </div>
      </div>

      <div class="mt-4 flex items-center gap-3 lg:mt-0">
        <button @click="clearFilters" class="type-input-label transition-color-figma inline-flex items-center justify-center rounded-[8px] border border-brand-dark-green/30 px-6 py-3 text-brand-dark-green hover:bg-white">Vymazat</button>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
      <article v-for="course in results.data" :key="course.id" class="flex h-full flex-col overflow-hidden rounded-[16px] bg-white ring-1 ring-brand-dark-green/10 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
        <div class="flex flex-1 flex-col p-6">
          <p class="type-text-sm text-brand-orange">{{ course.school ? course.school.name : 'Kurz' }}</p>
          <h3 class="type-display-xs mt-2 text-brand-dark-green">{{ course.title_cs || course.title_en }}</h3>
          <div class="type-text-md mt-3 text-brand-dark-green/80" v-html="truncate(course.description_cs || course.description_en, 200)"></div>
          <a :href="courseLink(course)" class="type-text-sm mt-4 text-brand-dark-green hover:text-brand-orange">Zobrazit detail →</a>
        </div>
      </article>
    </div>

    <div v-if="results.meta && results.meta.last_page > 1" class="mt-8 flex justify-center">
      <nav class="inline-flex gap-2" aria-label="Stránkování">
        <button @click="goto(results.meta.current_page - 1)" :disabled="results.meta.current_page <= 1" class="type-input-label inline-flex h-11 w-11 items-center justify-center rounded-[8px] border border-brand-dark-green/20 text-brand-dark-green disabled:opacity-40">«</button>
        <button
          v-for="p in pagesToShow"
          :key="p"
          @click="goto(p)"
          :class="[
            'type-input-label inline-flex h-11 w-11 items-center justify-center rounded-[8px]',
            p === results.meta.current_page ? 'bg-brand-dark-green text-white' : 'border border-brand-dark-green/20 text-brand-dark-green hover:bg-brand-light-green',
          ]"
        >{{ p }}</button>
        <button @click="goto(results.meta.current_page + 1)" :disabled="results.meta.current_page >= results.meta.last_page" class="type-input-label inline-flex h-11 w-11 items-center justify-center rounded-[8px] border border-brand-dark-green/20 text-brand-dark-green disabled:opacity-40">»</button>
      </nav>
    </div>
  </div>
</template>
```

Replace the `<style scoped>` block with an empty style tag (no longer needed):

```html
<style scoped>
</style>
```

Leave the `<script>` block as-is (same `name`, `props`, `data`, `computed`, `methods`).

- [ ] **Step 5: Run the test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=CoursesFinderRedesignTest'`
Expected: 3 passed.

- [ ] **Step 6: Build frontend and verify the finder page renders**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && npm run build'`
Expected: build completes (may print font/asset warnings — acceptable, pre-existing).

Run: `curl -s -o /dev/null -w "%{http_code}\n" -H "Host: irskostudy-redesign.local" http://127.0.0.1:8080/kurzy`
Expected: `200`

- [ ] **Step 7: Commit**

```bash
git add resources/views/pages/finder/courses.blade.php resources/js/components/course-finder.vue tests/Feature/CoursesFinderRedesignTest.php
git commit -m "fix: redesign courses finder and restyle finder component"
```

---

### Task 8: Redesign the course detail page

**Files:**
- Create: `resources/views/components/subpage/university-image.blade.php`
- Modify: `resources/views/pages/finder/course.blade.php` (full rewrite)
- Test: `tests/Feature/CourseDetailRedesignTest.php`

**Interfaces:**
- Consumes: `$course` (title_cs/title_en, description_cs/en, code, url, duration_length/unit, level->name, school->name/school_id/link, fields collection, link), `$relatedCourses` collection, `x-subpage.university-image`.
- Produces: `data-redesign-page="course-detail"`, sections `course-detail-hero` + `course-detail-content` + `course-detail-related`, dark-green-on-light layout, no emerald, working hero image via the shared component.

Background: current page has legacy prose/emerald styling and a broken hero-image path (`img/blog/large/uni-{school_id}.jpg` only; fails for `uni-trinity.png`, `uni-galway.jpeg`, `.jpeg` variants). Create a reusable image component that checks `.jpg`/`.jpeg`/`.png` in `public/img/blog/large/` then `public/img/universities/`.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Tests\TestCase;

class CourseDetailRedesignTest extends TestCase
{
    private function course(): object
    {
        return (object) [
            'title_cs' => 'Počítačové systémy',
            'title_en' => 'Computer Systems',
            'description_cs' => '<h2>O kurzu</h2><p>Popis kurzu.</p>',
            'description_en' => null,
            'code' => 'CS101',
            'url' => 'atu-cs101',
            'duration_length' => 3,
            'duration_unit' => 'years',
            'level' => (object) ['name' => 'Bachelor'],
            'school' => (object) ['name' => 'Atlantic Technological University', 'school_id' => 'atu', 'link' => 'https://www.atu.ie'],
            'fields' => collect([(object) ['name' => 'Technika']]),
            'link' => 'https://www.atu.ie/cs101',
        ];
    }

    public function test_course_detail_uses_redesign_sections(): void
    {
        $response = $this->view('pages.finder.course', [
            'course' => $this->course(),
            'relatedCourses' => new Collection(),
        ]);

        $response->assertSee('data-redesign-page="course-detail"', false);
        $response->assertSee('data-redesign-section="course-detail-hero"', false);
        $response->assertSee('data-redesign-section="course-detail-content"', false);
        $response->assertSee('Počítačové systémy', false);
        $response->assertSee('text-brand-dark-green', false);
        $response->assertSee('Oficiální web školy', false);
    }

    public function test_course_detail_has_no_legacy_styling(): void
    {
        $blade = file_get_contents(resource_path('views/pages/finder/course.blade.php'));

        $this->assertStringNotContainsString('emerald', $blade);
        $this->assertStringNotContainsString('bg-gray-50', $blade);
    }

    public function test_course_detail_renders_related_courses(): void
    {
        $related = collect([
            (object) [
                'id' => 2,
                'title_cs' => 'Softwarové inženýrství',
                'title_en' => null,
                'description_cs' => 'Popis.',
                'description_en' => null,
                'url' => 'atu-202',
                'school' => (object) ['name' => 'ATU', 'school_id' => 'atu', 'link' => null],
            ],
        ]);

        $response = $this->view('pages.finder.course', [
            'course' => $this->course(),
            'relatedCourses' => $related,
        ]);

        $response->assertSee('data-redesign-section="course-detail-related"', false);
        $response->assertSee('Softwarové inženýrství', false);
    }

    public function test_university_image_component_resolves_existing_files(): void
    {
        $html = (string) view('components.subpage.university-image', [
            'schoolId' => 'atu',
            'name' => 'ATU',
        ])->render();

        $this->assertStringContainsString('uni-atu.jpg', $html);
        $this->assertStringContainsString('<img', $html);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=CourseDetailRedesignTest'`
Expected: FAIL (legacy markup; `components.subpage.university-image` does not exist).

- [ ] **Step 3: Create `resources/views/components/subpage/university-image.blade.php`**

```blade
@props([
  'schoolId' => null,
  'name' => null,
  'imgClass' => 'h-64 w-full object-cover md:h-80',
])

@php
  $imageMap = ['tr' => 'trinity', 'gy' => 'galway'];
  $base = $imageMap[$schoolId] ?? $schoolId;
  $src = null;

  foreach (['jpg', 'jpeg', 'png'] as $ext) {
      $candidate = public_path("img/blog/large/uni-{$base}.{$ext}");
      if (file_exists($candidate)) {
          $src = asset("img/blog/large/uni-{$base}.{$ext}");
          break;
      }
  }

  if (!$src) {
      foreach (['jpg', 'jpeg', 'png'] as $ext) {
          $candidate = public_path("img/universities/uni-{$base}.{$ext}");
          if (file_exists($candidate)) {
              $src = asset("img/universities/uni-{$base}.{$ext}");
              break;
          }
      }
  }
@endphp

@if($src)
  <img src="{{ $src }}" alt="{{ $name ?? 'Univerzita' }}" class="rounded-[16px] {{ $imgClass }}" loading="lazy" decoding="async">
@endif
```

- [ ] **Step 4: Rewrite `resources/views/pages/finder/course.blade.php`**

```blade
@extends('layouts.app')

@section('title', ($course->title_cs ?? $course->title_en) . ' — Irsko Study')
@section('meta_description', Str::limit(strip_tags($course->description_cs ?? $course->description_en), 160))

@section('content')
  @php
    $courseTitle = $course->title_cs ?? $course->title_en;
    $duration = null;
    if ($course->duration_length) {
        $len = intval($course->duration_length);
        $unit = strtolower((string) ($course->duration_unit ?? ''));
        $unitLocal = match (true) {
            in_array($unit, ['years', 'year', 'yrs', 'yr']) && $len === 1 => 'rok',
            in_array($unit, ['years', 'year', 'yrs', 'yr']) && $len >= 2 && $len <= 4 => 'roky',
            in_array($unit, ['years', 'year', 'yrs', 'yr']) => 'let',
            default => $unit,
        };
        $duration = trim("$len $unitLocal");
    }
    $fields = $course->fields && $course->fields->count()
        ? $course->fields->pluck('name')->join(', ')
        : null;
  @endphp

  <main data-redesign-page="course-detail" class="bg-base-white">
    <section data-redesign-section="course-detail-hero" class="relative overflow-hidden rounded-b-[24px] bg-brand-dark-green text-white">
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_14%_22%,rgba(255,120,46,0.26),transparent_28%),radial-gradient(circle_at_88%_18%,rgba(156,204,87,0.2),transparent_30%)]"></div>
      <div class="layout-container relative py-16 md:py-24">
        <div class="grid items-center gap-10 lg:grid-cols-[1fr_400px]">
          <div>
            <p class="type-text-md-semibold inline-flex rounded-full bg-white/10 px-4 py-2 text-white ring-1 ring-white/15">{{ $course->school?->name ?? 'Univerzita' }}</p>
            <h1 class="type-display-xl mt-6 max-w-[820px] text-white">{{ $courseTitle }}</h1>
            @if(!empty($course->title_en) && $course->title_en !== $courseTitle)
              <p class="type-text-lg mt-4 text-white/80">{{ $course->title_en }}</p>
            @endif
            <div class="mt-10 grid max-w-[940px] gap-4 sm:grid-cols-3">
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">{{ $course->level?->name ?? '—' }}</p>
                <p class="type-text-sm mt-1 text-white/80">Úroveň studia</p>
              </div>
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">{{ $duration ?? '—' }}</p>
                <p class="type-text-sm mt-1 text-white/80">Délka</p>
              </div>
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">{{ $course->code }}</p>
                <p class="type-text-sm mt-1 text-white/80">Kód kurzu</p>
              </div>
            </div>
          </div>
          <div class="hidden lg:block">
            <x-subpage.university-image
              :school-id="$course->school->school_id ?? null"
              :name="$course->school->name ?? 'Univerzita'"
              img-class="h-[360px] w-full object-cover ring-1 ring-white/20"
            />
          </div>
        </div>
      </div>
    </section>

    <section data-redesign-section="course-detail-content" class="home-section">
      <div class="layout-container">
        <div class="grid gap-8 lg:grid-cols-[1fr_360px]">
          <div>
            @if(!empty($course->description_cs) || !empty($course->description_en))
              <div class="blog-post type-text-md text-brand-dark-green">
                {!! $course->description_cs ?: $course->description_en !!}
              </div>
            @else
              <p class="type-text-lg text-brand-dark-green">Popis kurzu není dostupný.</p>
            @endif
          </div>

          <aside class="space-y-4">
            <div class="rounded-[16px] bg-brand-light-gray p-6">
              <h2 class="type-display-xs text-brand-dark-green">Obor a umístění</h2>
              <p class="type-text-md mt-3 text-brand-dark-green">{{ $fields ?? '—' }}</p>
            </div>

            <div class="rounded-[16px] bg-brand-light-gray p-6">
              <h2 class="type-display-xs text-brand-dark-green">Studuj na {{ $course->school?->name ?? 'škole' }}</h2>
              @if(!empty($course->school->link))
                <a href="{{ $course->school->link }}" target="_blank" rel="noopener" class="type-text-md mt-3 inline-block text-brand-dark-green underline underline-offset-4 hover:text-brand-orange">Oficiální web školy →</a>
              @endif
            </div>

            @if(!empty($course->link))
              <a href="{{ $course->link }}" target="_blank" rel="noopener" class="type-input-label transition-color-figma inline-flex w-full items-center justify-center rounded-[8px] bg-brand-light-green px-8 py-4 text-brand-dark-green hover:bg-[#7db709]">Přejít na stránku kurzu</a>
            @endif
            <a href="{{ route('contact') }}" class="type-input-label transition-color-figma inline-flex w-full items-center justify-center rounded-[8px] border border-brand-dark-green px-8 py-4 text-brand-dark-green hover:bg-white">Poradit se se studiem</a>
          </aside>
        </div>
      </div>
    </section>

    @if(isset($relatedCourses) && $relatedCourses->count())
      <section data-redesign-section="course-detail-related" class="home-section pb-20">
        <div class="layout-container">
          <h2 class="type-display-lg text-brand-dark-green">Podobné kurzy</h2>
          <p class="type-text-lg mt-3 text-brand-dark-green">Další programy, které spadají do stejných oborů.</p>
          <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach($relatedCourses as $rc)
              @php
                $rcSlug = $rc->url;
                if (empty($rcSlug) && !empty($rc->school->school_id) && !empty($rc->code)) {
                    $rcSlug = strtolower($rc->school->school_id . '-' . $rc->code);
                } elseif (empty($rcSlug)) {
                    $rcSlug = $rc->code ?? $rc->id;
                }
              @endphp
              <article class="flex h-full flex-col overflow-hidden rounded-[16px] bg-brand-light-gray transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-1 flex-col p-6">
                  <p class="type-text-sm text-brand-orange">{{ $rc->school?->name ?? 'Kurz' }}</p>
                  <h3 class="type-display-xs mt-2 text-brand-dark-green">{{ $rc->title_cs ?? $rc->title_en }}</h3>
                  <p class="type-text-md mt-3 text-brand-dark-green">{{ Str::limit(strip_tags($rc->description_cs ?? $rc->description_en), 120) }}</p>
                  <a href="{{ route('finder.course.show', $rcSlug) }}" class="type-text-sm mt-4 text-brand-dark-green hover:text-brand-orange">Zobrazit detail →</a>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif

    @include('components.cta')
  </main>
@endsection
```

- [ ] **Step 5: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=CourseDetailRedesignTest'`
Expected: 4 passed.

- [ ] **Step 6: Verify a live course page loads**

Run: `curl -s -o /dev/null -w "%{http_code}\n" -H "Host: irskostudy-redesign.local" http://127.0.0.1:8080/kurzy/prvni-rocnik-umeni-a-designu`
Expected: `200`

- [ ] **Step 7: Commit**

```bash
git add resources/views/components/subpage/university-image.blade.php resources/views/pages/finder/course.blade.php tests/Feature/CourseDetailRedesignTest.php
git commit -m "fix: redesign course detail page with reusable university image"
```

---

### Task 9: Redesign the school detail page

**Files:**
- Modify: `resources/views/pages/finder/school.blade.php` (full rewrite)
- Test: `tests/Feature/SchoolDetailRedesignTest.php`

**Interfaces:**
- Consumes: `$school` (name, acronym, school_id, description_cs/en, link, locations collection with name/embed/map, courses collection with title_cs/en/description/level/code/url/school), `x-subpage.university-image`.
- Produces: `data-redesign-page="school-detail"`, sections `school-detail-hero` + `school-detail-content` + `school-detail-courses` + `school-detail-campuses`, no emerald.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Tests\TestCase;

class SchoolDetailRedesignTest extends TestCase
{
    private function school(): object
    {
        return (object) [
            'name' => 'Atlantic Technological University',
            'acronym' => 'ATU',
            'school_id' => 'atu',
            'description_cs' => '<h2>O škole</h2><p>Moderní univerzita.</p>',
            'description_en' => null,
            'link' => 'https://www.atu.ie',
            'courses' => new Collection([
                (object) [
                    'id' => 1,
                    'title_cs' => 'Počítačové systémy',
                    'title_en' => null,
                    'description_cs' => 'Popis kurzu.',
                    'description_en' => null,
                    'code' => 'CS101',
                    'url' => 'atu-cs101',
                    'level' => (object) ['name' => 'Bachelor'],
                    'school' => (object) ['school_id' => 'atu', 'name' => 'ATU'],
                ],
            ]),
            'locations' => new Collection([
                (object) ['name' => 'Sligo', 'embed' => '<iframe src="https://maps.google.com/embed"></iframe>', 'map' => 'https://maps.google.com'],
            ]),
        ];
    }

    public function test_school_detail_uses_redesign_sections(): void
    {
        $response = $this->view('pages.finder.school', ['school' => $this->school()]);

        $response->assertSee('data-redesign-page="school-detail"', false);
        $response->assertSee('data-redesign-section="school-detail-hero"', false);
        $response->assertSee('data-redesign-section="school-detail-content"', false);
        $response->assertSee('data-redesign-section="school-detail-courses"', false);
        $response->assertSee('data-redesign-section="school-detail-campuses"', false);
        $response->assertSee('Atlantic Technological University', false);
        $response->assertSee('text-brand-dark-green', false);
    }

    public function test_school_detail_has_no_legacy_styling(): void
    {
        $blade = file_get_contents(resource_path('views/pages/finder/school.blade.php'));

        $this->assertStringNotContainsString('emerald', $blade);
        $this->assertStringNotContainsString('bg-gray-50', $blade);
    }

    public function test_school_detail_renders_courses_and_campuses(): void
    {
        $response = $this->view('pages.finder.school', ['school' => $this->school()]);

        $response->assertSee('Počítačové systémy', false);
        $response->assertSee('Sligo', false);
        $response->assertSee('iframe', false);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=SchoolDetailRedesignTest'`
Expected: FAIL (legacy markup).

- [ ] **Step 3: Rewrite `resources/views/pages/finder/school.blade.php`**

```blade
@extends('layouts.app')

@section('title', ($school->name ?? 'Univerzita') . ' — Irsko Study')
@section('meta_description', Str::limit(strip_tags($school->description_cs ?? $school->description_en ?? ''), 160))

@section('content')
  <main data-redesign-page="school-detail" class="bg-base-white">
    <section data-redesign-section="school-detail-hero" class="relative overflow-hidden rounded-b-[24px] bg-brand-dark-green text-white">
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_14%_22%,rgba(255,120,46,0.26),transparent_28%),radial-gradient(circle_at_88%_18%,rgba(156,204,87,0.2),transparent_30%)]"></div>
      <div class="layout-container relative py-16 md:py-24">
        <div class="grid items-center gap-10 lg:grid-cols-[1fr_400px]">
          <div>
            <p class="type-text-md-semibold inline-flex rounded-full bg-white/10 px-4 py-2 text-white ring-1 ring-white/15">Vysoká škola</p>
            <h1 class="type-display-xl mt-6 max-w-[820px] text-white">{{ $school->name }}@if($school->acronym) ({{ $school->acronym }})@endif</h1>
            @if(!empty($school->description_cs) || !empty($school->description_en))
              <p class="type-text-lg mt-4 max-w-[760px] text-white/85">{{ Str::limit(strip_tags($school->description_cs ?: $school->description_en), 220) }}</p>
            @endif
            <div class="mt-10 grid max-w-[940px] gap-4 sm:grid-cols-3">
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">{{ $school->courses ? $school->courses->count() : 0 }}</p>
                <p class="type-text-sm mt-1 text-white/80">programů v nabídce</p>
              </div>
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">{{ $school->locations && $school->locations->count() ? $school->locations->count() : 0 }}</p>
                <p class="type-text-sm mt-1 text-white/80">kampusů</p>
              </div>
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">EN</p>
                <p class="type-text-sm mt-1 text-white/80">výuka v angličtině</p>
              </div>
            </div>
          </div>
          <div class="hidden lg:block">
            <x-subpage.university-image
              :school-id="$school->school_id ?? null"
              :name="$school->name ?? 'Univerzita'"
              img-class="h-[360px] w-full object-cover ring-1 ring-white/20"
            />
          </div>
        </div>
      </div>
    </section>

    <section data-redesign-section="school-detail-content" class="home-section">
      <div class="layout-container">
        <div class="grid gap-8 lg:grid-cols-[1fr_360px]">
          <div>
            <div class="blog-post type-text-md text-brand-dark-green">
              @if(!empty($school->description_cs))
                {!! $school->description_cs !!}
              @elseif(!empty($school->description_en))
                {!! $school->description_en !!}
              @else
                <p>Informace o škole nejsou dostupné.</p>
              @endif
            </div>
          </div>

          <aside class="space-y-4">
            <div class="rounded-[16px] bg-brand-light-gray p-6">
              <h2 class="type-display-xs text-brand-dark-green">O škole</h2>
              @if(!empty($school->link))
                <a href="{{ $school->link }}" target="_blank" rel="noopener" class="type-text-md mt-3 inline-block text-brand-dark-green underline underline-offset-4 hover:text-brand-orange">Oficiální web školy →</a>
              @endif
            </div>
            <a href="{{ route('finder.courses', ['school' => $school->school_id ?? $school->id]) }}" class="type-input-label transition-color-figma inline-flex w-full items-center justify-center rounded-[8px] bg-brand-light-green px-8 py-4 text-brand-dark-green hover:bg-[#7db709]">Zobrazit všechny kurzy</a>
            <a href="{{ route('contact') }}" class="type-input-label transition-color-figma inline-flex w-full items-center justify-center rounded-[8px] border border-brand-dark-green px-8 py-4 text-brand-dark-green hover:bg-white">Poradit se se studiem</a>
          </aside>
        </div>
      </div>
    </section>

    @if(isset($school->courses) && $school->courses->count())
      <section data-redesign-section="school-detail-courses" class="home-section">
        <div class="layout-container">
          <h2 class="type-display-lg text-brand-dark-green">Programy na {{ $school->name }}</h2>
          <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach($school->courses as $c)
              @php
                $courseUrl = $c->url;
                if (empty($courseUrl) && !empty($c->school->school_id) && !empty($c->code)) {
                    $courseUrl = strtolower($c->school->school_id . '-' . $c->code);
                } elseif (empty($courseUrl)) {
                    $courseUrl = $c->code ?? $c->id;
                }
              @endphp
              <article class="flex h-full flex-col overflow-hidden rounded-[16px] bg-brand-light-gray transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-1 flex-col p-6">
                  <p class="type-text-sm text-brand-orange">{{ $c->level?->name ?? 'Program' }}</p>
                  <h3 class="type-display-xs mt-2 text-brand-dark-green">{{ $c->title_cs ?? $c->title_en }}</h3>
                  <p class="type-text-md mt-3 text-brand-dark-green">{{ Str::limit(strip_tags($c->description_cs ?? $c->description_en), 120) }}</p>
                  <div class="mt-6 flex items-center justify-between gap-3">
                    <a href="{{ route('finder.course.show', $courseUrl) }}" class="type-text-sm text-brand-dark-green hover:text-brand-orange">Zobrazit detail →</a>
                    <span class="type-text-sm text-brand-dark-green">Kód: {{ $c->code }}</span>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif

    @if(!empty($school->locations) && $school->locations->count())
      <section data-redesign-section="school-detail-campuses" class="home-section pb-20">
        <div class="layout-container">
          <h2 class="type-display-lg text-brand-dark-green">Kampusy</h2>
          <div class="mt-8 grid gap-5 md:grid-cols-2">
            @foreach($school->locations as $loc)
              <div class="rounded-[16px] bg-brand-light-gray p-6">
                <h3 class="type-display-xs text-brand-dark-green">{{ $loc->name }}</h3>
                @php
                  $embedHtml = trim($loc->embed ?? $loc->map ?? '');
                @endphp
                @if($embedHtml)
                  <div class="mt-4 overflow-hidden rounded-[8px]">
                    @if(str_contains($embedHtml, '<iframe'))
                      {!! $embedHtml !!}
                    @else
                      <iframe src="{{ $embedHtml }}" width="100%" height="300" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    @endif
                  </div>
                  <a href="{{ $loc->map ?? $loc->embed }}" target="_blank" rel="noopener" class="type-text-sm mt-3 inline-block text-brand-dark-green underline underline-offset-4 hover:text-brand-orange">Otevřít v Google Maps</a>
                @else
                  <p class="type-text-md mt-3 text-brand-dark-green">K dispozici není mapa pro tuto pobočku.</p>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      </section>
    @endif

    @include('components.cta')
  </main>
@endsection
```

- [ ] **Step 4: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=SchoolDetailRedesignTest'`
Expected: 3 passed.

- [ ] **Step 5: Verify a live school page loads**

Run: `curl -s -o /dev/null -w "%{http_code}\n" -H "Host: irskostudy-redesign.local" http://127.0.0.1:8080/vysoke-skoly/university-of-galway`
Expected: `200`

- [ ] **Step 6: Commit**

```bash
git add resources/views/pages/finder/school.blade.php tests/Feature/SchoolDetailRedesignTest.php
git commit -m "fix: redesign school detail page with programs and campuses"
```

---

### Task 10: Add `Kurzy` navigation link + final verification

**Files:**
- Modify: `resources/views/components/navbar.blade.php`
- Modify: `resources/views/components/footer.blade.php`
- Test: `tests/Feature/KurzyNavLinksTest.php`

**Interfaces:**
- Consumes: `route('finder.courses')`.
- Produces: a `Kurzy` link in the desktop nav, mobile nav (via `...$desktopLinks`), and footer Study section; active-state detection for the finder routes.

Background: navbar `$desktopLinks` has no `Kurzy` entry; footer Study section lists `O nás`, `Proč Irsko`, `Vysoké školy`, `Služby` but no `Kurzy`. Add both. The `$isRouteActive` match for `universities` currently swallows all `finder.*` routes; split so `kurzy` highlights on `/kurzy*` and `universities` only on `/vysoke-skoly`.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class KurzyNavLinksTest extends TestCase
{
    public function test_navbar_contains_kurzy_link(): void
    {
        $blade = file_get_contents(resource_path('views/components/navbar.blade.php'));

        $this->assertStringContainsString("'label' => 'Kurzy'", $blade);
        $this->assertStringContainsString("route('finder.courses')", $blade);
        $this->assertStringContainsString("'kurzy'", $blade);
    }

    public function test_footer_contains_kurzy_link(): void
    {
        $blade = file_get_contents(resource_path('views/components/footer.blade.php'));

        $this->assertStringContainsString('>Kurzy</a>', $blade);
        $this->assertStringContainsString("route('finder.courses')", $blade);
    }

    public function test_homepage_renders_kurzy_link(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Kurzy', false);
        $response->assertSee(route('finder.courses'), false);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=KurzyNavLinksTest'`
Expected: FAIL (no Kurzy links).

- [ ] **Step 3: Edit `resources/views/components/navbar.blade.php`**

Replace the `$isRouteActive` closure `universities` branch (lines 8-9) with two branches:

```php
    return match ($name) {
        'home' => $routeName === 'home',
        'about' => $routeName === 'about',
        'why' => $routeName === 'why',
        'universities' => $routeName === 'universities' || $routeName === 'finder.school.show',
        'kurzy' => in_array($routeName, ['finder.courses', 'finder.course.show', 'finder.search']),
        'services' => $routeName === 'services',
        'blog' => str_starts_with($routeName, 'blog'),
        'faq' => $routeName === 'faq',
        'contact' => $routeName === 'contact',
        default => false,
    };
```

In `$desktopLinks`, insert the `kurzy` entry between `universities` and `services`:

```php
$desktopLinks = [
    ['name' => 'about', 'label' => 'O nás', 'href' => route('about')],
    ['name' => 'why', 'label' => 'Proč Irsko', 'href' => route('why')],
    ['name' => 'universities', 'label' => 'Vysoké školy', 'href' => route('universities')],
    ['name' => 'kurzy', 'label' => 'Kurzy', 'href' => route('finder.courses')],
    ['name' => 'services', 'label' => 'Služby', 'href' => route('services')],
    ['name' => 'blog', 'label' => 'Blog', 'href' => route('blog')],
    ['name' => 'faq', 'label' => 'Co tě zajímá', 'href' => route('faq')],
];
```

(`$mobileLinks` spreads `...$desktopLinks`, so it picks up `Kurzy` automatically.)

- [ ] **Step 4: Edit `resources/views/components/footer.blade.php`**

In the Study column, after the `Vysoké školy` link (line 27), add:

```blade
              <a href="{{ route('finder.courses') }}" class="{{ $footerLinksClass }}">Kurzy</a>
```

- [ ] **Step 5: Run test to verify it passes**

Run: `docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter=KurzyNavLinksTest'`
Expected: 3 passed.

- [ ] **Step 6: Final regression + live verification**

Run all Phase 1 focused tests:
```bash
docker compose -f /home/david/Development/docker/docker-compose.yml exec php sh -c 'cd /home/sites/irskostudy_cz_redesign && php artisan test --filter="RoutesCleanupTest|UniversitySlideshowAssetsTest|PrivacyPageRedesignTest|SitemapPageRedesignTest|ContactPageRedesignTest|FaqPageRedesignTest|CoursesFinderRedesignTest|CourseDetailRedesignTest|SchoolDetailRedesignTest|KurzyNavLinksTest|HomepageRedesignTest|BlogRedesignTest"'
```
Expected: all pass.

Verify live pages return 200:
```bash
for path in / /kurzy /kontakt /faq /ochrana-soukromi /sitemap /vysoke-skoly /blog; do
  code=$(curl -s -o /dev/null -w "%{http_code}" -H "Host: irskostudy-redesign.local" "http://127.0.0.1:8080$path");
  echo "$path -> $code";
done
```
Expected: all `200`.

Verify `/welcome` is gone:
```bash
curl -s -o /dev/null -w "%{http_code}\n" -H "Host: irskostudy-redesign.local" http://127.0.0.1:8080/welcome
```
Expected: `404`

- [ ] **Step 7: Commit**

```bash
git add resources/views/components/navbar.blade.php resources/views/components/footer.blade.php tests/Feature/KurzyNavLinksTest.php
git commit -m "fix: add kurzy navigation link in navbar and footer"
```

---

## Final Notes

- After the last task, `git status` should be clean except gitignored assets (`public/img`, `public/build`).
- Known unrelated failure: `Tests\Feature\LeadTest` (sqlite `information_schema`); do not attempt to fix in this plan.
- If any live page returns non-200 after the final verification, re-run the corresponding task's focused test before touching code, per systematic-debugging.
