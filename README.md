# IrskoStudy.cz — Developer Notes

This README contains developer-focused documentation for the lead/contact flow implemented in this repository.

## Lead (contact) flow — n8n review and CRM handoff

Public contact forms submit to `POST /contact`. The application validates the request, requires consent, rate-limits requests to two per minute per IP, and stores each lead as `pending_review`. Submission does **not** send email or contact the CRM.

n8n owns qualification:

1. n8n fetches unprocessed leads with `GET /api/internal/leads/pending?mark_fetched=1`.
2. n8n checks the lead for spam and validates it.
3. n8n calls `PATCH /api/internal/leads/{id}/review` with `approved` or `rejected`, spam score, notes, and optional payload.
4. The first approval dispatches `LeadSubmitted` after commit.
5. Queued listeners send the internal notification, applicant confirmation, and CRM payload to `CRM_LEAD_URL`.

Rejected leads remain stored with their spam/qualification details and never receive CRM or email handoff. Repeating a review request after processing returns `already_processed: true`, so external handoff runs once.

### n8n authentication

The review API uses Sanctum bearer tokens. Create a dedicated local `User` for n8n and create a token in the irskostudy application; do not reuse the irsko.ie token because Sanctum tokens are application-specific. See [`docs/sanctum-commands.md`](docs/sanctum-commands.md) for the complete command reference.

```sh
php artisan sanctum:create-user "n8n Lead Review" "n8n@irskostudy.cz" "use-a-strong-password"
```

Store the printed token only in n8n credentials and send it as `Authorization: Bearer <token>`.

### n8n API contract

`GET /api/internal/leads/pending?mark_fetched=1`

- Requires Sanctum bearer token.
- Optional `limit` query parameter, range 1–100; default 50.
- `mark_fetched=1` writes `n8n_fetched_at` and transitions `unprocessed` leads to `in_review`.

`PATCH /api/internal/leads/{lead}/review`

```json
{
  "approval_status": "approved",
  "qualification_status": "qualified",
  "qualification_source": "n8n",
  "spam_score": 0.1,
  "qualification_notes": "Validated by n8n.",
  "qualification_payload": {
    "decision": "approve"
  }
}
```

Use `approval_status: "rejected"` and `qualification_status: "disqualified"` for spam. The approval endpoint accepts only the first decision; subsequent calls return the stored lead with `already_processed: true`.

### CRM and queue configuration

```dotenv
CRM_LEAD_URL=https://crm.irsko.ie/api/lead
CRM_LEAD_TIMEOUT=10
QUEUE_CONNECTION=database
```

Run a queue worker in production so approval handoff is retried outside the n8n/API request:

```sh
php artisan queue:work --tries=3
```

Key implementation files:

- `app/Http/Controllers/LeadController.php` — stores pending public leads.
- `app/Http/Controllers/Api/LeadReviewController.php` — Sanctum review API for n8n.
- `app/Services/LeadApprovalService.php` — idempotent approval/rejection transitions.
- `app/Listeners/PostLeadToCRM.php` — CRM delivery, invoked only after approval.
- `app/Listeners/SendLeadStoredNotification.php` and `SendLeadNotification.php` — approval-only emails.

## Finder (courses & universities)

This project includes a small course finder (courses + universities) implemented with a Laravel controller, Blade views and a Vue 3 component for interactive search and filtering.

- Controller: `app/Http/Controllers/FinderController.php` — handles:
	- universities listing and university profile (`universities`, `showSchool`)
	- courses listing and course detail (`courses`, `showCourse`)
	- AJAX search/filter endpoint (`search`) used by the Vue component
- Routes: Czech-friendly endpoints are registered in `routes/web.php`:

```php
// universities
Route::get('/vysoke-skoly', [FinderController::class, 'universities'])->name('universities');
Route::get('/vysoke-skoly/{url}', [FinderController::class, 'showSchool'])->name('finder.school.show');

// courses
Route::get('/kurzy', [FinderController::class, 'courses'])->name('finder.courses');
Route::get('/kurzy/hledat', [FinderController::class, 'search'])->name('finder.search');
Route::get('/kurzy/{url}', [FinderController::class, 'showCourse'])->name('finder.course.show');
```

- Views & frontend:
	- Blade pages live under `resources/views/pages/finder/` (e.g. `courses.blade.php`, `course.blade.php`, `school.blade.php`).
	- Interactive search component: `resources/js/components/course-finder.vue` — this component calls the `/kurzy/hledat` endpoint and expects each course item to include a `url` property used to generate links (for example `/kurzy/{url}`).

- Database note: the controller prefers an explicit `url` column on both `cao_courses` and `cao_schools` for SEO-friendly slugs. If your database schema does not include `url` on `cao_schools`, the app may throw SQL errors when attempting `where('url', ...)`.

Recommended fixes if `url` is missing:

1. Add a migration to add `url` to `cao_schools`, index it and backfill values (for example use `school_id` or slugified `name`):

```php
// example (conceptual):
Schema::table('cao_schools', function (Blueprint $table) {
		$table->string('url')->nullable()->index();
});
```

2. Backfill `url` for existing rows (artisan command or DB script) and then run `php artisan migrate`.

- Build & cache steps after changing routes or frontend code:

```sh
npm install
npm run build    # build production assets so compiled JS calls the Czech endpoints
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

Note: while migrating the routes from legacy English paths we keep temporary permanent redirects from `/finder/*` to the Czech routes to avoid 404s for older cached assets; after rebuilding assets you can remove those redirects.

If you'd like, I can add a small migration and backfill script for `cao_schools.url` and wire a safety check into `FinderController` to avoid QueryExceptions when that column is absent.

## Universities data & slideshow

The project includes a small JSON catalog used by the frontend university slideshow component.

- Location: `public/img/universities/universities.json`
- Format: an array of objects with these fields:
	- `file` — filename (string). The component constructs an image URL by prefixing this with `/img/blog/medium/` (see note below).
	- `name` — university display name (string).
	- `description` — short description used in the overlay (string).

Example (from `public/img/universities/universities.json`):

```json
[
	{
		"file": "uni-atu.jpg",
		"name": "Atlantic Technological University (ATU)",
		"description": "Moderní univerzita s praxí orientovanými programy a silným zapojením do regionu."
	},
	{
		"file": "uni-dcu.jpg",
		"name": "Dublin City University (DCU)",
		"description": "Dynamické prostředí pro technologie a podnikání, vhodné pro studenty se zájmem o inovace."
	}
]
```

How the slideshow uses it

- Component: `resources/js/components/university-slideshow.vue`.
- The component fetches the JSON from `/img/universities/universities.json` (no-cache) and stores it in `slides`.
- Image URL construction: the component uses `imageUrl(file)` which currently returns `/img/blog/medium/` + `file`. Ensure your image files are available at that path (or update the method if you place images elsewhere).
- The component preloads images, auto-rotates slides (configurable `interval`) and dispatches a DOM `CustomEvent` named `universitySlideChange` with the current slide details so other parts of the page can react (for example to update a separate overlay).

To customize

- Change image location: edit `imageUrl()` inside the component to point to your preferred folder (for example `/img/universities/<file>`).
- Change timing: update `interval` (ms) and `transitionDuration` (ms) in the component's `data()`.
- Add or remove items: edit `public/img/universities/universities.json`. If you add images, remember that `/public/img` is ignored by git in this repo (`.gitignore`) — keep that in mind when deploying assets.


## Sitemap generation (automated)

The project includes a small sitemap generator command that writes chunked sitemap files into `public/sitemaps/` and a sitemap index at `public/sitemaps/sitemap-index.xml`.

- Run manually:

```sh
php artisan sitemap:generate        # generates gzipped sitemaps by default
php artisan sitemap:generate --no-gzip  # skip creating .gz copies
```

- Recommended schedule (Laravel scheduler): run daily at 02:00. Add the following to your server's crontab to run the Laravel scheduler every minute:

```sh
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

Then register the scheduled command in `app/Console/Kernel.php` (or ensure it is present):

```php
$schedule->command('sitemap:generate')->dailyAt('02:00');
```

This keeps the public sitemap files static and fast to serve; Search Console and crawlers can fetch `https://your-site/sitemap.xml` which routes to the generated index.
