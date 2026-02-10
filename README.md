# IrskoStudy.cz — Developer Notes

This README contains developer-focused documentation for the lead/contact flow implemented in this repository.

## Lead (contact) flow — overview

The project includes a small lead capture system used by the public contact form. It persists leads, sends a notification email, and dispatches an event for further processing (logging, CRM integration, etc.).

Key pieces:

- `app/Http/Controllers/LeadController.php` — handles POST `/contact` requests, validates input, creates the `Lead` model, sends the `LeadStored` mailable, dispatches `LeadSubmitted` event, and returns a JSON response.
- `app/Models/Lead.php` — the Eloquent model representing a captured lead. Fillable fields: `name`, `email`, `phone`, `message`, `page`.
- `database/migrations/*create_leads_table.php` — migration that creates the `leads` table.
- `app/Mail/LeadStored.php` and `resources/views/emails/lead-stored.blade.php` — the mailable used to notify the configured recipient about a new lead.
- `app/Events/LeadSubmitted.php` — an event that carries the saved `Lead` instance and can be listened to for side effects (analytics, CRM sync, Slack, etc.).
- `routes/web.php` — route declaration for the contact endpoint:

```php
Route::post('/contact', [\App\Http\Controllers\LeadController::class, 'store'])->name('lead.store');
```

## Request contract (inputs/outputs)

- Inputs (POST JSON or form-encoded):
	- `name` (string, required)
	- `email` (string, required, valid email)
	- `phone` (string, optional)
	- `message` (string, optional)
	- `page` (string, optional) — optional page identifier where the lead originated

- Success response: HTTP 201 JSON { "ok": true, "message": "Lead stored" }
- Failure: HTTP 422 on validation errors with standard Laravel validation payload.

## Where the email is sent

The recipient is read from the site configuration `config('contacts.email')`. If you need to change the address used for notifications, update the `contacts` config file or the `.env` variables that populate it.

## Lead logging (separate channel)

Lead intake and mail delivery status are logged to a dedicated channel named `leads`. By default it writes to `storage/logs/leads.log` with daily rotation (30 days). You can adjust levels and retention with:

- `LOG_LEADS_LEVEL` (default: `info`)
- `LOG_LEADS_DAYS` (default: `30`)

## How to run locally

1. Install dependencies and build front-end assets if needed:

```sh
composer install
npm install
npm run dev   # or npm run build for production
```

2. Run migrations (this will create the `leads` table):

```sh
php artisan migrate
```

3. Start a local server (optional):

```sh
php artisan serve
```

4. Submit a lead from the frontend contact form or send a POST request to `/contact`.

Example using curl:

```sh
curl -X POST http://localhost:8000/contact \
	-H "Content-Type: application/json" \
	-d '{"name":"Test User","email":"test@example.com","phone":"+420123456789","message":"Interested in help","page":"services"}'
```

## Tests

- There is a feature test that covers the lead creation flow: `tests/Feature/LeadTest.php`.
- The test uses `Mail::fake()` and `Event::fake()` to assert that the mailable was queued/sent and the event dispatched.

Run the tests with:

```sh
./vendor/bin/phpunit --filter=LeadTest
```

Note: Some tests or vendor code in this repository perform database inspection queries that assume MySQL. If you run tests under SQLite you may encounter SQL errors (for example, `SHOW COLUMNS` is MySQL-specific). If that happens, run the test suite using a MySQL test database or temporarily mock/guard vendor calls during testing.

## Extending the flow

- Queue the mailable: change `Mail::to(...)->send()` to `->queue()` and configure your queue worker.
- Add a listener for `LeadSubmitted` in `EventServiceProvider` to push leads to a CRM or send Slack notifications.
- Add IP throttling / rate limiting in `LeadController` to prevent spam.

## Troubleshooting

- If you don't receive emails locally, ensure `MAIL_MAILER` and related mail settings in `.env` are configured (or use `log` driver in `.env` for development).
- If migrations fail, inspect migration files in `database/migrations` and ensure the DB connection in `.env` is correct.

If you'd like, I can add a small README section that documents how to wire a queue listener for `LeadSubmitted` or create a sample listener that forwards leads to an external CRM.


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

