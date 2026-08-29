# Prakticky Pruvodce URL Migration Design

## Goal

Move the public guide from `/blog` to `/prakticky-pruvodce` while preserving inbound links and search-engine equity.

## Routes

- `GET /prakticky-pruvodce` uses `BlogController@index` and keeps the `blog` route name.
- `GET /prakticky-pruvodce/{slug}` uses `BlogController@show` and keeps the `blog.show` route name.
- `GET /blog` permanently redirects to `/prakticky-pruvodce`.
- `GET /blog/{slug}` permanently redirects to `/prakticky-pruvodce/{slug}`.
- The new routes are declared before the legacy redirects so an article slug is never treated as a redirect wildcard incorrectly.

## Content-Type Placement

Blog placement is determined by `aiblog_posts.content_type_id`, not the legacy `type_id` relationship:

- `content_type_id = 1` (`guide`) is listed at `/prakticky-pruvodce`.
- `content_type_id = 2` (`news`) is listed at `/novinky`.

The controller eager-loads the `contentType` relationship and uses the same field when loading related articles. Sitemap generation and the human-readable sitemap include content types 1 and 2 so valid guide and news posts are not omitted because of unrelated legacy post types.

## Supporting URLs

- XML sitemap and `SitemapGenerator` emit only new canonical article URLs for content types 1 and 2.
- All existing `route('blog')` and `route('blog.show')` callers automatically resolve to the new paths.
- Article detail URLs remain available through `/prakticky-pruvodce/{slug}`.

## Verification

- Feature tests assert new named-route URLs and `301` legacy redirects.
- `AiblogPlacementRoutesTest` verifies that the guide and news listings query content types 1 and 2 respectively.
- Full Laravel suite passes.
