# Prakticky Pruvodce URL Migration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Move the guide to `/prakticky-pruvodce` and permanently redirect legacy blog URLs.

**Architecture:** The existing `blog` and `blog.show` named routes retain their names and controller actions but use the Czech guide paths. Explicit legacy routes permanently redirect each old path, and sitemap producers emit the new canonical URLs.

**Tech Stack:** Laravel routing, Blade sitemap views, PHPUnit.

## Global Constraints

- Keep `blog` and `blog.show` route names unchanged.
- Serve `301` redirects from `/blog` and `/blog/{slug}`.
- Emit only `/prakticky-pruvodce` URLs in sitemaps.

---

### Task 1: Move Named Guide Routes and Preserve Legacy URLs

**Files:**
- Modify: `routes/web.php:20-21`
- Modify: `tests/Feature/RoutesCleanupTest.php`

- [ ] **Step 1: Write failing route assertions**

```php
$this->assertSame(url('/prakticky-pruvodce'), route('blog'));
$this->get('/blog')->assertRedirect('/prakticky-pruvodce');
$this->get('/blog/example')->assertRedirect('/prakticky-pruvodce/example');
```

- [ ] **Step 2: Run the focused test and confirm failure**

Run: `php artisan test tests/Feature/RoutesCleanupTest.php`

- [ ] **Step 3: Replace the named paths and add explicit permanent redirects**

```php
Route::get('/prakticky-pruvodce', [BlogController::class, 'index'])->name('blog');
Route::get('/prakticky-pruvodce/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::permanentRedirect('/blog', '/prakticky-pruvodce');
Route::permanentRedirect('/blog/{slug}', '/prakticky-pruvodce/{slug}');
```

- [ ] **Step 4: Run focused tests**

Run: `php artisan test tests/Feature/RoutesCleanupTest.php`

### Task 2: Emit New Sitemap URLs

**Files:**
- Modify: `app/Services/SitemapGenerator.php`
- Modify: `resources/views/sitemap.xml.blade.php`
- Modify: `resources/views/sitemap/xml.blade.php`

- [ ] **Step 1: Replace `/blog` and `/blog/{slug}` sitemap entries with the guide paths.**
- [ ] **Step 2: Run the full Laravel test suite.**

Run: `php artisan test`
