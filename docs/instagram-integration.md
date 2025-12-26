Instagram / Meta Graph integration

This document explains the backend integration that fetches an Instagram Business account's media via the Meta Graph API and exposes it via `/api/instagram/feed` for the website widget.

Environment variables (add to your `.env`):

- `INSTAGRAM_BUSINESS_ACCOUNT_ID` — the Instagram Business Account ID connected to your Facebook Page.
- `FACEBOOK_PAGE_ACCESS_TOKEN` — a long-lived Page access token with `instagram_basic` and `pages_read_engagement` scopes.
- `META_GRAPH_VERSION` — optional, defaults to `v17.0` (e.g. `v17.0`).
- `INSTAGRAM_CACHE_TTL` — optional cache ttl in seconds (defaults to `3600`).
- `INSTAGRAM_USE_LOCAL_JSON` — optional boolean (true/false). When true or when Graph credentials are absent, the service will read `storage/app/instagram.json` as a fallback.

Files added/changed:

- `app/Services/InstagramService.php` — service that calls the Graph API, maps items, caches them.
- `app/Http/Controllers/InstagramController.php` — exposes `GET /api/instagram/feed` and a local fallback at `/api/instagram`.
- `config/instagram.php` — central config for the integration.
- `app/Console/Commands/RefreshInstagramFeed.php` — artisan command `instagram:refresh` to force-refresh the cache.
- `routes/api.php` — added route `/api/instagram/feed`.
- `resources/js/components/instagram-widget.vue` — updated to fetch `/api/instagram/feed` and render posts.

Local JSON fallback

- For local development or when you prefer to use a static JSON file, set `INSTAGRAM_USE_LOCAL_JSON=true` in your `.env` or set `use_local_json` in `config/instagram.php`.
- The service will look for `storage/app/instagram.json`. Supported shapes:
    - Full Graph-like items:
        [{ "id": "..", "media_url": "..", "thumbnail_url": "..", "caption": "..", "permalink": "..", "timestamp": ".." }]
    - Simple shape (existing sample file):
        [{ "id": "..", "image": "..", "caption": "..", "link": ".." }]

When the local file is used, the API `/api/instagram/feed` returns the same `items` + `fetched_at` payload as when using the Graph API.

Scheduling / Refresh strategy:

- The codebase includes an artisan command `php artisan instagram:refresh` which calls the Graph API and caches results.
- You should schedule this command with the Laravel scheduler or a server cron job. Example cron (runs every 10 minutes):

```bash
# crontab -e
*/10 * * * * cd /path/to/project && php artisan instagram:refresh --limit=12 >> /var/log/instagram_refresh.log 2>&1
```

Or register the command in `app/Console/Kernel.php` with the scheduler:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('instagram:refresh')->hourly();
}
```

Notes on error handling and rate limits:

- The service uses `Http::retry` to handle transient errors and sets a short timeout.
- On failure, the service will return cached data if available, otherwise the controller returns a 503 with an error message.
- Monitor logs for `InstagramService` errors (token expiration, permission errors). Refresh tokens/perms via Facebook for Developers when needed.

Security:

- Keep `FACEBOOK_PAGE_ACCESS_TOKEN` secure and do not expose it to the frontend. This endpoint is server-side only.

Testing locally:

- You can keep `storage/app/instagram.json` for local development; the controller still exposes `/api/instagram` which reads this file.
- To manually refresh cached data run:

```bash
php artisan instagram:refresh --limit=8
```

If you want, I can:
- Add Kernel scheduling entry (I couldn't find `app/Console/Kernel.php` in the workspace to modify it safely), or
- Add automated monitoring/logging for token expiry, or
- Extend the service to fetch carousel children for `CAROUSEL_ALBUM` media types.

*** End of doc
