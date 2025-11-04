<div>
    <h2>Nový lead byl odeslán</h2>
    <p><strong>Jméno:</strong> {{ $lead->name }}</p>
    <p><strong>E-mail:</strong> {{ $lead->email }}</p>
    @if($lead->phone)
        <p><strong>Telefon:</strong> {{ $lead->phone }}</p>
    @endif
    @if($lead->page)
        <p><strong>Stránka:</strong> {{ $lead->page }}</p>
    @endif
    {{-- courses removed from lead model --}}

    <h3>Zpráva</h3>
    <pre style="white-space:pre-wrap">{{ $lead->message }}</pre>
</div>
