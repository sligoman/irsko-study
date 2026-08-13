@if ($paginator->hasPages())
  <nav class="flex items-center justify-between border-t border-brand-dark-green/15 pt-6" aria-label="Stránkování průvodce">
    <div class="flex flex-1 justify-between sm:hidden">
      @if ($paginator->onFirstPage())
        <span class="type-input-label text-brand-dark-green/35">← Předchozí</span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" class="group type-input-label inline-flex items-center gap-2 text-brand-dark-green transition-color-figma hover:text-[#041b19]">
          <span class="transition-move-figma group-hover:-translate-x-[2px]">←</span><span>Předchozí</span>
        </a>
      @endif
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="group type-input-label inline-flex items-center gap-2 text-brand-dark-green transition-color-figma hover:text-[#041b19]">
          <span>Další</span><span class="transition-move-figma group-hover:translate-x-[2px]">→</span>
        </a>
      @else
        <span class="type-input-label text-brand-dark-green/35">Další →</span>
      @endif
    </div>
    <div class="hidden flex-1 items-center justify-between sm:flex">
      @if ($paginator->onFirstPage())
        <span class="type-input-label text-brand-dark-green/35">← Předchozí</span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" class="group type-input-label inline-flex items-center gap-2 text-brand-dark-green transition-color-figma hover:text-[#041b19]">
          <span class="transition-move-figma group-hover:-translate-x-[2px]">←</span><span>Předchozí</span>
        </a>
      @endif
      <span class="type-text-sm text-utility-text-placeholder-dark">Strana {{ $paginator->currentPage() }} z {{ $paginator->lastPage() }}</span>
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="group type-input-label inline-flex items-center gap-2 text-brand-dark-green transition-color-figma hover:text-[#041b19]">
          <span>Další</span><span class="transition-move-figma group-hover:translate-x-[2px]">→</span>
        </a>
      @else
        <span class="type-input-label text-brand-dark-green/35">Další →</span>
      @endif
    </div>
  </nav>
@endif
