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

