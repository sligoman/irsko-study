@props(['line' => true])

<div class="flex @if($line) justify-end @else justify-center @endif relative my-12">
    
    <div class="@if($line) absolute @endif bg-white w-40 left-[50%]">
      <x-svg.shamrock background="transparent"></x-svg.shamrock>
    </div>  
  
    @if($line)    
    <svg class="w-[85%]" id="visual" viewBox="0 0 2400 100" width="2400" height="100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"><path d="M0 48L240 38L480 38L720 59L960 53L1200 51L1440 46L1680 52L1920 52L2160 61L2400 50" fill="none" stroke-linecap="square" stroke-linejoin="bevel" stroke="#E65100" stroke-width="40"></path></svg>
    @endif
  
  </div>