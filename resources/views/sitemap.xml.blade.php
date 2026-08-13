<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

@foreach($staticUrls as $item)
  <url>
    <loc>{{ $item['loc'] }}</loc>
    @if(!empty($item['lastmod']))<lastmod>{{ $item['lastmod'] }}</lastmod>@endif
    @if(!empty($item['changefreq']))<changefreq>{{ $item['changefreq'] }}</changefreq>@endif
    @if(!empty($item['priority']))<priority>{{ $item['priority'] }}</priority>@endif
  </url>
@endforeach

@foreach($posts as $post)
  <url>
    <loc>{{ url('/prakticky-pruvodce/' . $post->slug) }}</loc>
    @if(!empty($post->updated_at))<lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>@endif
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
@endforeach

@if(!empty($schools))
  @foreach($schools as $s)
    <url>
      <loc>{{ $s['loc'] }}</loc>
      @if(!empty($s['lastmod']))<lastmod>{{ $s['lastmod'] }}</lastmod>@endif
      <changefreq>{{ $s['changefreq'] ?? 'monthly' }}</changefreq>
      <priority>{{ $s['priority'] ?? '0.7' }}</priority>
    </url>
  @endforeach
@endif

@if(!empty($courses))
  @foreach($courses as $c)
    <url>
      <loc>{{ $c['loc'] }}</loc>
      @if(!empty($c['lastmod']))<lastmod>{{ $c['lastmod'] }}</lastmod>@endif
      <changefreq>{{ $c['changefreq'] ?? 'monthly' }}</changefreq>
      <priority>{{ $c['priority'] ?? '0.6' }}</priority>
    </url>
  @endforeach
@endif

</urlset>
