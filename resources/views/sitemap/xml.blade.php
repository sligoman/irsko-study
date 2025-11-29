<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($staticUrls as $u)
  <url>
    <loc>{{ $u['loc'] }}</loc>
    @if(!empty($u['lastmod']))
      <lastmod>{{ $u['lastmod'] }}</lastmod>
    @endif
    <changefreq>{{ $u['changefreq'] }}</changefreq>
    <priority>{{ $u['priority'] }}</priority>
  </url>
@endforeach

@foreach($posts as $post)
  <url>
    <loc>{{ url('/blog/' . $post->slug) }}</loc>
    @if(!empty($post->updated_at))
      <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
    @endif
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
  </url>
@endforeach
</urlset>
