# Prakticky Pruvodce URL Migration Design

## Goal

Move the public guide from `/blog` to `/prakticky-pruvodce` while preserving inbound links and search-engine equity.

## Routes

- `GET /prakticky-pruvodce` uses `BlogController@index` and keeps the `blog` route name.
- `GET /prakticky-pruvodce/{slug}` uses `BlogController@show` and keeps the `blog.show` route name.
- `GET /blog` permanently redirects to `/prakticky-pruvodce`.
- `GET /blog/{slug}` permanently redirects to `/prakticky-pruvodce/{slug}`.
- The new routes are declared before the legacy redirects so an article slug is never treated as a redirect wildcard incorrectly.

## Supporting URLs

- XML sitemap and `SitemapGenerator` emit only new guide URLs.
- All existing `route('blog')` and `route('blog.show')` callers automatically resolve to the new paths.

## Verification

- Feature tests assert new named-route URLs and `301` legacy redirects.
- Full Laravel suite passes.
