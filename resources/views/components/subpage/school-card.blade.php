@props([
  'school' => null,
  'image' => null,
  'description' => null,
  'profileHref' => null,
  'coursesHref' => null,
])

<article data-component="school-card" class="flex h-full flex-col overflow-hidden rounded-[16px] bg-brand-light-gray transition duration-300 hover:-translate-y-1 hover:shadow-lg">
  @if($image)
    <img src="{{ $image }}" alt="{{ $school->name }}" class="h-44 w-full object-cover" loading="lazy" decoding="async">
  @else
    <div class="flex h-44 items-center justify-center bg-brand-dark-green text-white">
      <span class="type-display-sm text-white">{{ $school->acronym ?: mb_substr($school->name, 0, 2) }}</span>
    </div>
  @endif

  <div class="flex flex-1 flex-col p-6">
    <p class="type-text-sm text-brand-orange">{{ $school->courses_count ?? 0 }} programů</p>
    <h3 class="type-display-xs mt-2 text-brand-dark-green">{{ $school->name }}@if($school->acronym) ({{ $school->acronym }})@endif</h3>
    @if($description)
      <p class="type-text-md mt-3 text-brand-dark-green">{{ \Illuminate\Support\Str::limit(strip_tags($description), 170) }}</p>
    @endif

    @if(!empty($school->link))
      <a href="{{ $school->link }}" target="_blank" rel="noopener" class="type-text-sm mt-4 text-brand-dark-green hover:text-brand-orange">Oficiální web školy →</a>
    @endif

    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
      <a href="{{ $coursesHref }}" class="type-input-label transition-color-figma inline-flex items-center justify-center rounded-[8px] bg-brand-light-green px-5 py-3 text-brand-dark-green hover:bg-[#7db709]">Prohlédnout kurzy</a>
      <a href="{{ $profileHref }}" class="type-input-label transition-color-figma inline-flex items-center justify-center rounded-[8px] border border-brand-dark-green px-5 py-3 text-brand-dark-green hover:bg-white">Profil školy</a>
    </div>
  </div>
</article>
