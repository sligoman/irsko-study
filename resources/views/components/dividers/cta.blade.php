@props(['url'])

<div class="flex justify-center relative my-12">
    
    @if(isset($url))
    <x-button url="{{ $url }}">{{ $slot ?? 'Mám zájem' }}</x-button>
    @else
    <x-button>{{ $slot ?? 'Mám zájem' }}</x-button>
    @endif
  
</div>
